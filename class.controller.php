<?php

class smioslogin_controller {
  public static $apisetting;

  public function __construct(){
    $this->get_api_setting();
  }

  public static function setting(){
    if($_POST){
      self::saveOptions();
    }
    else{
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
      wp_enqueue_script('jquery');
      wp_enqueue_style('thickbox');
      self::loadpage('setting', 1);
    }
  }

  public static function loadpage($template, $noheader=0, $params=0){
    self::load_jsplugins();
    $noheader = ($noheader == 0)?'':'&noheader=1';
    $page_url = admin_url().'admin.php?page=smioslogin_'.$template.$noheader;
    include(smioslogin_dir.'/pages/'.$template.'.php');
  }

  public static function load_jsplugins(){
    wp_enqueue_style('smioslogin-style');
    if(is_rtl()){
      wp_enqueue_style('smioslogin-rtl');
    }
    wp_enqueue_script('smioslogin-mainscript');
    wp_enqueue_script('smioslogin-plugins');
  }

  public static function saveOptions(){
    if(smioslogin_env == 'demo'){
      echo 1;
      die();
    }
    $newsetting = array();
    foreach($_POST AS $key=>$value){
      if(!in_array($key, array('submit'))){
        $newsetting[$key] = $value;
        unset(self::$apisetting[$key]);
      }
    }
    $checkbox = array(
    'fb_login',
    'twt_login',
    'twt_email',
    'google_login',
    );
    foreach($checkbox AS $inptname){
      if(!isset($_POST[$inptname])){
        self::$apisetting[$inptname] = 0;
      }
    }
    self::$apisetting = array_map('addslashes', self::$apisetting);
    self::$apisetting = array_merge($newsetting, self::$apisetting);
    update_option('smioslogin_options', self::$apisetting);
    echo 1;
    die();
  }
  
  public function scripts(){
    wp_register_script('smioslogin-mainscript', smioslogin_jspath.'/smio-function.js', array('jquery'), SMPUSHVERSION);
    wp_register_script('smioslogin-plugins', smioslogin_jspath.'/smio-plugins.js', array('jquery'), SMPUSHVERSION);
    wp_register_style('smioslogin-mainstyle', smioslogin_csspath.'/autoload-style.css', array(), SMPUSHVERSION);
    wp_register_style('smioslogin-style', smioslogin_csspath.'/smio-style.css', array(), SMPUSHVERSION);
    wp_enqueue_style('smioslogin-mainstyle');
    if(is_rtl()){
      wp_register_style('smioslogin-rtl', smioslogin_csspath.'/smio-style-rtl.css', array(), SMPUSHVERSION);
    }
    if(get_bloginfo('version') > 3.7){
      wp_register_style('smioslogin-fix38', smioslogin_csspath.'/autoload-style38.css', array(), SMPUSHVERSION);
      wp_enqueue_style('smioslogin-fix38');
    }
  }

  public function build_menus(){
    add_menu_page('Settings', 'Simpla Social', 'delete_pages', 'smioslogin_setting', array('smioslogin_controller', 'setting'), 'div', 75);
  }

  public function get_option($index){
    return self::$apisetting[$index];
  }

  public function get_api_setting(){
    self::$apisetting = get_option('smioslogin_options');
    self::$apisetting = array_map('stripslashes', self::$apisetting);
  }
  
}