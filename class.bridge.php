<?php

class smioslogin_bridge extends smioslogin_controller {

  function build_login_url() {
    $html = '';
    if(self::$apisetting['fb_login'] == 1){
      include(smioslogin_dir.'/libs/facebook-sdk/vendor/autoload.php');
      $fb = new Facebook\Facebook([
      'app_id' => self::$apisetting['fb_appid'],
      'app_secret' => self::$apisetting['fb_secret'],
      'default_graph_version' => 'v2.2',
      ]);
      $helper = $fb->getRedirectLoginHelper();
      $loginUrl = $helper->getLoginUrl(get_bloginfo('url').'/?smio-callback=facebook', ['email']);
      
      $src = (empty(self::$apisetting['fb_icon']))? smioslogin_imgpath.'/fb-icon.png' : self::$apisetting['fb_icon'];
      $html .= '<a class="smioslogin-social-icons" href="'.htmlspecialchars($loginUrl).'"><img src="'.$src.'" alt="Login with Facebook" /></a>';
    }
    if(self::$apisetting['twt_login'] == 1){
      $src = (empty(self::$apisetting['twt_icon']))? smioslogin_imgpath.'/twt-icon.png' : self::$apisetting['twt_icon'];
      $html .= '<a class="smioslogin-social-icons" href="'.get_bloginfo('url').'/?smio-callback=twitter"><img src="'.$src.'" alt="Login with Twitter" /></a>';
    }
    if(self::$apisetting['google_login'] == 1){
      $src = (empty(self::$apisetting['google_icon']))? smioslogin_imgpath.'/google-icon.png' : self::$apisetting['google_icon'];
      $html .= '<a class="smioslogin-social-icons" href="'.get_bloginfo('url').'/?smio-callback=google"><img src="'.$src.'" alt="Login with Google+" /></a>';
    }
    $html .= '<style>.smioslogin-social-icons{width:225px;display:block;margin:4px auto;'.self::$apisetting['custom_css'].'}</style>';
    return $html;
  }

  function login_form($content, $args) {
    return $content.$this->build_login_url();
  }

  function login_form2() {
    echo $this->build_login_url();
  }

  function start_fetch_method() {
    if (empty($_GET['smio-callback'])) {
      return;
    }
    if(!session_id()){
      session_start();
    }
    if ($_GET['smio-callback'] == 'google') {
      define('SMIOSLOGIN_CALLBACKURI', get_bloginfo('url').'/?smio-callback=google');
      define('SMIOSLOGIN_SITEURI', get_bloginfo('url'));
      define('SMIOSLOGIN_SITENAME', get_bloginfo('name'));
      define('SMIOSLOGIN_CLIENTID', self::$apisetting['google_client_id']);
      define('SMIOSLOGIN_CLIENTSECRET', self::$apisetting['google_client_secret']);
      define('SMIOSLOGIN_DEVELOPERKEY', self::$apisetting['google_developer_key']);
      require smioslogin_dir.'/libs/google/Google_Client.php';
      require smioslogin_dir.'/libs/google/contrib/Google_PlusService.php';

      $client = new Google_Client();
      $plus = new Google_PlusService($client);

      if(isset($_GET['code'])){
        $client->authenticate();
        $_SESSION['smioslogin_google_token'] = $client->getAccessToken();
        $redirect = SMIOSLOGIN_CALLBACKURI;
        header('Location: '.filter_var($redirect, FILTER_SANITIZE_URL));
      }

      if(isset($_SESSION['smioslogin_google_token'])){
        $client->setAccessToken($_SESSION['smioslogin_google_token']);
      }

      if($client->getAccessToken()){
        $_SESSION['smioslogin_google_token'] = $client->getAccessToken();
        $google_token = json_decode($_SESSION['smioslogin_google_token'], true);
        $me = $plus->people->get('me');
        
        $profile = array(
        'socialID' => $me['id'],
        'fullname' => $me['displayName'],
        'fname' => '',
        'mname' => '',
        'lname' => '',
        'email' => $me['emails'][0]['value'],
        'gender' => (!empty($me['gender']))? $me['gender'] : '',
        'website' => (!empty($me['website']))? $me['website'] : '',
        'birthday' => '',
        'location' => (!empty($me['location']))? $me['location'] : '',
        'town' => '',
        'access_token' => $google_token['access_token'],
        'picture' => $me['image']['url'],
        );

        $this->setupAccount($profile, 'google');
      }
      else{
        $authUrl = $client->createAuthUrl();
        header('Location: '.$authUrl);
      }
    }
    elseif ($_GET['smio-callback'] == 'twitter-callback') {
      include(smioslogin_dir.'/libs/twitter/twitteroauth.php');
      $twitteroauth = new TwitterOAuth(self::$apisetting['twt_appid'], self::$apisetting['twt_secret'], $_SESSION['smio_social_oauth_token'], $_SESSION['smio_social_oauth_token_secret']);
      if(self::$apisetting['twt_email'] == 1){
        $include_email = true;
      }
      else{
        $include_email = false;
      }
      $accessToken = $twitteroauth->getAccessToken($_REQUEST['oauth_verifier']);
      $me = $twitteroauth->get('account/verify_credentials', array('include_email' => $include_email));
      if (empty($me->id) || empty($_SESSION['smio_social_oauth_token']) || empty($_SESSION['smio_social_oauth_token_secret'])) {
        session_destroy();
        die('Something wrong happened.');
      } else {
        $profile = array(
        'socialID' => $me->id,
        'fullname' => $me->name,
        'fname' => '',
        'mname' => '',
        'lname' => '',
        'email' => (!empty($me->email))? $me->email : $me->id.'@twitter.com',
        'gender' => '',
        'website' => (!empty($me->url))? $me->url : '',
        'birthday' => '',
        'location' => (!empty($me->location))? $me->location : '',
        'town' => '',
        'access_token' => json_encode($accessToken),
        'picture' => $me->profile_image_url,
        );

        $this->setupAccount($profile, 'twitter');
      }
    }
    elseif ($_GET['smio-callback'] == 'twitter') {
      include(smioslogin_dir.'/libs/twitter/twitteroauth.php');
      $twitteroauth = new TwitterOAuth(self::$apisetting['twt_appid'], self::$apisetting['twt_secret']);
      $request_token = $twitteroauth->getRequestToken(get_bloginfo('url').'/?smio-callback=twitter-callback');
      $_SESSION['smio_social_oauth_token'] = $request_token['oauth_token'];
      $_SESSION['smio_social_oauth_token_secret'] = $request_token['oauth_token_secret'];
      if ($twitteroauth->http_code == 200) {
        $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
        header('Location: '.$url);
      } else {
        die('Something wrong happened.');
      }
    }
    elseif ($_GET['smio-callback'] == 'facebook') {
      include(smioslogin_dir.'/libs/facebook-sdk/vendor/autoload.php');
      $fb = new Facebook\Facebook([
      'app_id' => self::$apisetting['fb_appid'], // Replace {app-id} with your app id
      'app_secret' => self::$apisetting['fb_secret'],
      'default_graph_version' => 'v2.2',
      ]);

      $helper = $fb->getRedirectLoginHelper();

      try {
        $accessToken = $helper->getAccessToken();
        //$session = $helper->getSessionFromRedirect();
      } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: '.$e->getMessage();
        exit;
      } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: '.$e->getMessage();
        exit;
      }

      if (!isset($accessToken)) {
        if ($helper->getError()) {
          header('HTTP/1.0 401 Unauthorized');
          echo "Error: ".$helper->getError()."\n";
          echo "Error Code: ".$helper->getErrorCode()."\n";
          echo "Error Reason: ".$helper->getErrorReason()."\n";
          echo "Error Description: ".$helper->getErrorDescription()."\n";
        } else {
          header('HTTP/1.0 400 Bad Request');
          echo 'Bad request';
        }
        exit;
      }

      // Logged in
      $accessToken = $accessToken->getValue();
      try {
        $response = $fb->get('/me', $accessToken);
      } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: '.$e->getMessage();
        exit;
      } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: '.$e->getMessage();
        exit;
      }

      $me = $response->getGraphUser();
      $profile = array(
      'socialID' => $me->getId(),
      'fullname' => $me->getName(),
      'fname' => $me->getFirstName(),
      'mname' => $me->getMiddleName(),
      'lname' => $me->getLastName(),
      'email' => $me->getEmail(),
      'gender' => $me->getGender(),
      'website' => $me->getLink(),
      'birthday' => $me->getBirthday(),
      'location' => $me->getLocation(),
      'town' => $me->getHometown(),
      'access_token' => $accessToken,
      'picture' => 'https://graph.facebook.com/v2.8/'.$me->getId().'/picture?type=large',
      );

      $this->setupAccount($profile, 'facebook');
    }
  }
  
  private function setupAccount($profile, $platform){
    global $wpdb;
    $newpass = wp_generate_password(10, false);
    $userid = $wpdb->get_var("SELECT userid FROM ".$wpdb->prefix."smioslogin_tokens WHERE platform='$platform' AND social_id='$profile[socialID]'");
    if (empty($userid)) {
      $duplicate = $wpdb->get_var("SELECT ID FROM ".$wpdb->users." WHERE user_login='$profile[socialID]' OR user_email='$profile[email]'");
      if(!empty($duplicate)){
        echo "<script language=\"JavaScript\">\n";
        echo "alert('Username or Email is already exists in our records!');\n";
        echo "window.location='".wp_login_url()."'";
        echo "</script>";
        exit;
      }
      $userdata = array(
      'user_login' => $profile['socialID'],
      'user_email' => $profile['email'],
      'display_name' => $profile['fullname'],
      'nickname' => $profile['fullname'],
      'first_name' => $profile['fname'],
      'last_name' => $profile['lname'],
      'user_pass' => $newpass,
      );
      $userid = wp_insert_user($userdata);
      update_user_meta($userid, 'social_id', $profile['socialID']);
      update_user_meta($userid, 'gender', $profile['gender']);

      $data = array();
      $data['userid'] = $userid;
      $data['access_token'] = $profile['access_token'];
      $data['social_id'] = $profile['socialID'];
      $data['platform'] = $platform;
      $wpdb->insert($wpdb->prefix.'smioslogin_tokens', $data);
    }
    else {
      $wpdb->update($wpdb->prefix.'smioslogin_tokens', array('access_token' => $profile['access_token']), array('userid' => $userid));
    }
    
    update_user_meta($userid, 'profile_picture', $profile['picture']);
    update_user_meta($userid, 'location', $profile['location']);
    update_user_meta($userid, 'website', $profile['website']);
    wp_set_auth_cookie($userid, true, false);
    
    wp_redirect(get_bloginfo('url').'/index.php');
    exit;
  }
  
}