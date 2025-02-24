<?php
/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

global $apiConfig;
$apiConfig = array(
    // True if objects should be returned by the service classes.
    // False if associative arrays should be returned (default behavior).
    'use_objects' => false,

    // The application_name is included in the User-Agent HTTP header.
    'application_name' => SMIOSLOGIN_SITENAME,

    // OAuth2 Settings, you can get these keys at https://code.google.com/apis/console
    'oauth2_client_id' => SMIOSLOGIN_CLIENTID,
    'oauth2_client_secret' => SMIOSLOGIN_CLIENTSECRET,
    'oauth2_redirect_uri' => SMIOSLOGIN_CALLBACKURI,

    // The developer key, you get this at https://code.google.com/apis/console
    'developer_key' => SMIOSLOGIN_DEVELOPERKEY,

    // Site name to show in the Google's OAuth 1 authentication screen.
    'site_name' => str_replace(array('https://','http://'), '', SMIOSLOGIN_SITEURI),

    // Which Authentication, Storage and HTTP IO classes to use.
    'authClass'    => 'Google_OAuth2',
    'ioClass'      => 'Google_CurlIO',
    'cacheClass'   => 'Google_FileCache',

    // Don't change these unless you're working against a special development or testing environment.
    'basePath' => 'https://www.googleapis.com',

    // IO Class dependent configuration, you only have to configure the values
    // for the class that was configured as the ioClass above
    'ioFileCache_directory'  =>
        (function_exists('sys_get_temp_dir') ? sys_get_temp_dir() . '/Google_Client' : '/tmp/Google_Client'),

    // Definition of service specific values like scopes, oauth token URLs, etc
    'services' => array(
      'oauth2' => array(
          'scope' => array(
              'https://www.googleapis.com/auth/plus.login',
              'https://www.googleapis.com/auth/plus.me',
              'https://www.googleapis.com/auth/userinfo.email',
              'https://www.googleapis.com/auth/userinfo.profile',
          )
      ),
      'plus' => array('scope' => 'https://www.googleapis.com/auth/plus.profile.emails.read')
    )
);
