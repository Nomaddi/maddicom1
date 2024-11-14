<?php
require_once(__DIR__ . '/config.php');

global $baseurl;
global $facebook_key;
global $facebook_secret;
global $twitter_key;
global $twitter_secret;

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return array(
    "base_url" => $baseurl . '/vendor/hybridauth/hybridauth/hybridauth/',
    "providers" => array(
        // openid providers
        "OpenID" => array(
            "enabled" => true,
        ),
        "Yahoo" => array(
            "enabled" => true,
            "keys" => array("id" => "", "secret" => ""),
        ),
        "AOL" => array(
            "enabled" => true,
        ),
        "Google" => array(
            "enabled" => true,
            "keys" => array("id" => "", "secret" => ""),
        ),
        "Facebook" => array(
            "enabled" => true,
            "keys" => array("id" => $facebook_key, "secret" => $facebook_secret),
			"scope" => "email, public_profile",
            "trustForwarded" => false,
        ),
        "Twitter" => array(
            "enabled" => true,
            "keys" => array("key" => $twitter_key, "secret" => $twitter_secret),
            "includeEmail" => true,
        ),
        // windows live
        "Live" => array(
            "enabled" => true,
            "keys" => array("id" => "", "secret" => ""),
        ),
        "LinkedIn" => array(
            "enabled" => true,
            "keys" => array("id" => "", "secret" => ""),
            "fields" => array(),
        ),
        "Foursquare" => array(
            "enabled" => true,
            "keys" => array("id" => "", "secret" => ""),
        ),
    ),
    // If you want to enable logging, set 'debug_mode' to true.
    // You can also set it to
    // - "error" To log only error messages. Useful in production
    // - "info" To log info and error messages (ignore debug messages)
    "debug_mode" => false,
    // Path to file writable by the web server. Required if 'debug_mode' is not false
    "debug_file" => "",
);
