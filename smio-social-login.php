<?php
/*
Plugin Name: Simpla Social Login
Plugin URI: https://smartiolabs.com/product/simpla-social-login
Description: Provides a simple and very lite social login system for your Wordpress without any server overloads !
Author: Smart IO Labs
Version: 1.0
Author URI: https://smartiolabs.com
*/
    
define('smioslogin_dir', plugin_dir_path(__FILE__));
define('smioslogin_imgpath', plugins_url('/images', __FILE__));
define('smioslogin_csspath', plugins_url('/css', __FILE__));
define('smioslogin_jspath', plugins_url('/js', __FILE__));
define('SMIOSLOGINVERSION', 1.0);
define('smioslogin_env', 'production');

include(smioslogin_dir.'/class.controller.php');
include(smioslogin_dir.'/class.bridge.php');

register_activation_hook(__FILE__, 'smioslogin_install');
register_uninstall_hook(__FILE__, 'smioslogin_uninstall');

add_action('init', 'smioslogin_start');
add_action('wpmu_new_blog', 'smioslogin_new_blog_installed', 99, 6);

function smioslogin_start() {
  $smioslogin_controller = new smioslogin_controller();
  $smioslogin_bridge = new smioslogin_bridge();
  
  $smioslogin_version = get_option('smioslogin_version');
  if ($smioslogin_version != SMIOSLOGINVERSION) {
    smioslogin_upgrade($smioslogin_version);
  }
  add_action('template_redirect', array($smioslogin_bridge, 'start_fetch_method'));
  add_action('admin_menu', array($smioslogin_controller, 'build_menus'));
  add_action('admin_enqueue_scripts', array($smioslogin_controller, 'scripts'));
  add_filter('login_form_middle', array($smioslogin_bridge, 'login_form'));
  add_action('login_form', array($smioslogin_bridge, 'login_form2'));
}

function smioslogin_new_blog_installed($blog_id, $user_id, $domain, $path, $site_id, $meta) {
  smioslogin_install($blog_id);
}

function smioslogin_install($blog_id = false) {
  if ($blog_id !== false) {
    switch_to_blog($blog_id);
  }
  if (get_option('smioslogin_version') > 0) {
    if ($blog_id !== false) {
      restore_current_blog();
    }
    return;
  }
  smioslogin_uninstall_code();
  global $wpdb;
  $wpdb->hide_errors();
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smioslogin_tokens` (
    `userid` bigint(20) NOT NULL,
    `access_token` text NOT NULL,
    `social_id` bigint(20) NOT NULL,
    `platform` enum('twitter','facebook','google') NOT NULL,
    PRIMARY KEY (`userid`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
  dbDelta($sql);
  
  $settings = array();
  $settings['fb_login'] = 0;
  $settings['fb_appid'] = '';
  $settings['fb_secret'] = '';
  $settings['fb_icon'] = '';
  $settings['twt_login'] = 0;
  $settings['twt_email'] = 0;
  $settings['twt_appid'] = '';
  $settings['twt_secret'] = '';
  $settings['twt_icon'] = '';
  $settings['google_login'] = 0;
  $settings['google_client_id'] = '';
  $settings['google_client_secret'] = '';
  $settings['google_developer_key'] = '';
  $settings['google_icon'] = '';
  $settings['custom_css'] = '';
  add_option('smioslogin_options', $settings);
  add_option('smioslogin_version', SMIOSLOGINVERSION);

  if ($blog_id !== false) {
    restore_current_blog();
  }
}

function smioslogin_upgrade($version) {
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  global $wpdb;
  $wpdb->hide_errors();
  if ($version <= 1.0) {
    $version = 1.1;
  }
  update_option('smioslogin_version', $version);
}

function smioslogin_uninstall() {
  global $wpdb;
  if (is_multisite()) {
    $blogs = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
    if ($blogs) {
      foreach ($blogs as $blog) {
        switch_to_blog($blog->blog_id);
        smioslogin_uninstall_code();
      }
      restore_current_blog();
    }
  } else {
    smioslogin_uninstall_code();
  }
}

function smioslogin_uninstall_code() {
  global $wpdb;
  $wpdb->hide_errors();
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smioslogin_tokens`");
  delete_option('smioslogin_options');
  delete_option('smioslogin_version');
}
