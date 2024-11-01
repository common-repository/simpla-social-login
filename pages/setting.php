<div class="wrap">
  <h2>Settings</h2>

  <div id="col-container" class="smioslogin-settings-page">
    <form action="<?php echo $page_url; ?>" method="post" id="smioslogin_jform" class="validate">
      <div id="col-left">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><span>General</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first">Custom CSS</td>
                      <td>
                        <textarea name="custom_css" rows="8" cols="70" class="regular-text" placeholder="for example: padding:10px;height:50px;"><?php echo self::$apisetting['custom_css']; ?></textarea>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save All Settings">
                          <img src="<?php echo smioslogin_imgpath; ?>/wpspin_light.gif" class="smioslogin_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smioslogin_imgpath; ?>/facebook.png" alt="" /> <span>Facebook</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="fb_login" type="checkbox" value="1" <?php if (self::$apisetting['fb_login'] == 1) { ?>checked="checked"<?php } ?>> Enable Facebook login</label>
                        <p class="description">Check this tutorial fow how to setup Facebook login <a href="https://smartiolabs.com/blog/68/setup-facebook-social-login-application-wordpress-plugin" target="_blank">here</a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">App ID</td>
                      <td>
                        <input type="text" name="fb_appid" value="<?php echo self::$apisetting['fb_appid']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">App Secret</td>
                      <td>
                        <input type="text" name="fb_secret" value="<?php echo self::$apisetting['fb_secret']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Icon</td>
                      <td>
                        <input class="smioslogin_upload_field_fbicon" type="url" size="50" name="fb_icon" value="<?php echo self::$apisetting['fb_icon']; ?>" />
                        <input class="smioslogin_upload_file_btn button action" data-container="smioslogin_upload_field_fbicon" type="button" value="Select File" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Callback URI</td>
                      <td>
                        <input type="text" value="<?php echo get_bloginfo('url'); ?>/?smio-callback=facebook" class="regular-text" size="60" readonly="readonly" onclick="$(this).select()" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save All Settings">
                          <img src="<?php echo smioslogin_imgpath; ?>/wpspin_light.gif" class="smioslogin_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smioslogin_imgpath; ?>/twitter.png" alt="" /> <span>Twitter</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="twt_login" type="checkbox" value="1" <?php if (self::$apisetting['twt_login'] == 1) { ?>checked="checked"<?php } ?>> Enable Twitter login</label>
                        <p class="description">Check this tutorial fow how to setup Twitter login <a href="https://smartiolabs.com/blog/70/setup-twitter-social-login-application-wordpress-plugin" target="_blank">here</a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="twt_email" type="checkbox" value="1" <?php if (self::$apisetting['twt_email'] == 1) { ?>checked="checked"<?php } ?>> Request the user email address from Twitter</label>
                        <p class="description">This feature require special permission request from Twitter <a href="https://smartiolabs.com/blog/74/request-email-permission-twitter" target="_blank">here</a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Consumer Key</td>
                      <td>
                        <input type="text" name="twt_appid" value="<?php echo self::$apisetting['twt_appid']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Consumer Secret</td>
                      <td>
                        <input type="text" name="twt_secret" value="<?php echo self::$apisetting['twt_secret']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Icon</td>
                      <td>
                        <input class="smioslogin_upload_field_twticon" type="url" size="50" name="twt_icon" value="<?php echo self::$apisetting['twt_icon']; ?>" />
                        <input class="smioslogin_upload_file_btn button action" data-container="smioslogin_upload_field_twticon" type="button" value="Select File" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Callback URI</td>
                      <td>
                        <input type="text" value="<?php echo get_bloginfo('url'); ?>/?smio-callback=twitter" class="regular-text" size="60" readonly="readonly" onclick="$(this).select()" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save All Settings">
                          <img src="<?php echo smioslogin_imgpath; ?>/wpspin_light.gif" class="smioslogin_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smioslogin_imgpath; ?>/google.png" alt="" /> <span>Google</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="google_login" type="checkbox" value="1" <?php if (self::$apisetting['google_login'] == 1) { ?>checked="checked"<?php } ?>> Enable Google login</label>
                        <p class="description">Check this tutorial fow how to setup Twitter login <a href="https://smartiolabs.com/blog/72/how-to-setup-google-social-login-application-for-wordpress-plugin" target="_blank">here</a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Client ID</td>
                      <td>
                        <input type="text" name="google_client_id" value="<?php echo self::$apisetting['google_client_id']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Client Secret</td>
                      <td>
                        <input type="text" name="google_client_secret" value="<?php echo self::$apisetting['google_client_secret']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">API Key</td>
                      <td>
                        <input type="text" name="google_developer_key" value="<?php echo self::$apisetting['google_developer_key']; ?>" class="regular-text" size="60" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Icon</td>
                      <td>
                        <input class="smioslogin_upload_field_googleicon" type="url" size="50" name="google_icon" value="<?php echo self::$apisetting['google_icon']; ?>" />
                        <input class="smioslogin_upload_file_btn button action" data-container="smioslogin_upload_field_googleicon" type="button" value="Select File" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first">Callback URI</td>
                      <td>
                        <input type="text" value="<?php echo get_bloginfo('url'); ?>/?smio-callback=google" class="regular-text" size="60" readonly="readonly" onclick="$(this).select()" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save All Settings">
                          <img src="<?php echo smioslogin_imgpath; ?>/wpspin_light.gif" class="smioslogin_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>