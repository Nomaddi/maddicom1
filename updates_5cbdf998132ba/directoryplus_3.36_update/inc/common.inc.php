<?php
require_once(__DIR__ . '/functions.php');
include_once(__DIR__ . '/iso-639-1-native-names.php');

// version
$version = '3.36';

// error reporting
error_reporting(E_ALL);

// set exception handler
set_exception_handler('exception_handler');

//set error handler
set_error_handler('error_handler');

// set initial timezone(will be changed later on)
date_default_timezone_set('America/Los_Angeles');

// composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// get install path from baseurl
$parsed_url   = parse_url($baseurl);
$install_path = empty($parsed_url['path']) ? '/' : $parsed_url['path'];
$install_dir = __DIR__ . '/../';

// widget path
$widget_path = __DIR__;

// set inc/ folder in include path
set_include_path(__DIR__);

// construct PDO dsn
$dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;

// Create PDO object
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4');
try {
	$conn = new PDO($dsn, $db_user, $db_user_pass, $options);
	// setAttribute(ATTRIBUTE, OPTION);
	// default is silent error mode. Changing to throw exceptions
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Leave column names as returned by the database driver. Some PDO extensions return them in uppercase
	$conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
	// This is so as to use native prepare, which doesn't have problems with numeric params in LIMIT clause
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}

catch(PDOException $e) {
	echo "<h2>Error</h2>";
	echo nl2br(htmlspecialchars($e->getMessage()));
	exit();
}

// set sql_mode, disable ONLY_FULL_GROUP_BY
$stmt = $conn->prepare("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
$stmt->execute();

// if 'config' table doesn't exist, return (being called from the install script)
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'config')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] == 0) {
	$is_installed = false;

	if(basename($_SERVER['SCRIPT_NAME']) == 'install.php' || basename($_SERVER['SCRIPT_NAME']) == 'process-install.php') {
		return;
	}

	else {
		die('Directoryplus is not installed');
	}
}

// get global settings
// first init vars
$admin_email                  = '';
$country_name                 = '';
$currency_code                = '';
$currency_symbol              = '';
$default_city_slug            = '';
$default_country_code         = '';
$default_lat                  = '';
$default_lng                  = '';
$default_loc_id               = '';
$dev_email                    = '';
$disqus_shortname             = '';
$facebook_key                 = '';
$facebook_secret              = '';
$google_key                   = '';
$here_key                     = '';
$here_secret                  = '';
$html_lang                    = '';
$items_per_page               = 0;
$mail_after_post              = '';
$maintenance_mode             = '';
$mapbox_secret                = '';
$max_pics                     = 0;
$notify_url                   = '';
$paypal_bn                    = '';
$paypal_checkout_logo_url     = '';
$paypal_locale                = '';
$paypal_merchant_id           = '';
$paypal_mode                  = '';
$paypal_sandbox_merch_id      = '';
$site_name                    = '';
$smtp_pass                    = '';
$smtp_port                    = '';
$smtp_server                  = '';
$smtp_user                    = '';
$stripe_currency_symbol       = '';
$stripe_data_currency         = '';
$stripe_data_description      = '';
$stripe_data_image            = '';
$stripe_live_publishable_key  = '';
$stripe_live_secret_key       = '';
$stripe_mode                  = '';
$stripe_test_publishable_key  = '';
$stripe_test_secret_key       = '';
$timezone                     = '';
$tomtom_secret                = '';
$twitter_key                  = '';
$twitter_secret               = '';
$user_created_notify          = 0;
$site_logo_width              = '180';

/*--------------------------------------------------
Default values for $cfg_* vars
--------------------------------------------------*/
// input field type for fields like smtp passwords, api secrets, etc.
$input_password = "text";

// coupon default size (w x h)
$coupon_size = array(480, 480);

// logo default size (w x h)
$cfg_logo_size = array(480, 480);
$cfg_logo_quality = 85;

// short_desc field max length
$short_desc_length = 100;

// use disqus (yes = 1; no = 0)
$use_disqus = 1;

// default cat icon
$cfg_default_cat_icon = '';

// default custom field type toggle values list
$cfg_custom_field_toggle_values = 'yes;no';

// show custom field icons
$cfg_show_custom_fields_icons = true;

// drop down city limit
$cfg_city_dropdown_limit = 200;

// homepage hero image
$cfg_hero_img = $baseurl . '/assets/imgs/hero01.jpg';

// currency symbol position
$cfg_cur_symbol_pos = 'left';

// show maps on search results
$cfg_show_maps_on_listings = true;

// enable sitemaps
$cfg_enable_sitemaps = true;

// stripe cents
$stripe_min_unit_is_cent = true;

// auto approve listing
$cfg_auto_approve_listing = true;

// show country calling code on listing page
$cfg_show_country_calling_code = false;

// show image or icon for categories on the home page
$cfg_cat_display = 'image';

// show website link in results pages
$cfg_show_website = true;

// smtp encryption
$cfg_smtp_encryption = 'tls';

// other cfg vars
$cfg_languages             = 'en';
$cgf_near_listings_radius  = '150';
$cfg_latest_listings_count = '12';
$cfg_decimal_separator     = '.';
$cfg_use_select2           = 1;
$cfg_permalink_struct      = 'listing';
$cgf_max_dist_values       = '1;3;5;10;20;100';
$cgf_max_dist_unit         = 'km';

/*--------------------------------------------------
Get config vars
--------------------------------------------------*/
$query = "SELECT * FROM config WHERE type <> 'cat-lang'";
$stmt  = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	switch ($row['property']) {
		case 'admin_email':
			$admin_email = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'country_name':
			$country_name = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'currency_code':
			$currency_code = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'currency_symbol':
			$currency_symbol = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'default_city_slug':
			$default_city_slug = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'default_country_code':
			$default_country_code = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'default_lat':
			$default_lat = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'default_lng':
			$default_lng = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'default_loc_id':
			$default_loc_id = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'dev_email':
			$dev_email = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'disqus_shortname':
			$disqus_shortname = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'facebook_key':
			$facebook_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'facebook_secret':
			$facebook_secret = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'google_key':
			$google_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'here_key':
			$here_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'here_secret':
			$here_secret = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'html_lang':
			$html_lang = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'items_per_page':
			$items_per_page = !empty($row['value']) ? $row['value'] : 30;
			break;

		case 'mail_after_post':
			$mail_after_post = $row['value'] == 0 ? 0 : 1;
			break;

		case 'maintenance_mode':
			$maintenance_mode = $row['value'] == 0 ? 0 : 1;
			break;

		case 'map_provider':
			$map_providers = !empty($row['value']) ? $row['value'] : '';
			$map_provider = unserialize($map_providers);
			$map_provider_randkey = array_rand($map_provider);
			$map_provider = $map_provider[$map_provider_randkey];
			break;

		case 'mapbox_secret':
			$mapbox_secret = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'max_pics':
			$max_pics = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'notify_url':
			$notify_url = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'paypal_merchant_id':
			$paypal_merchant_id = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'paypal_bn':
			$paypal_bn = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'paypal_checkout_logo_url':
			$paypal_checkout_logo_url = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'paypal_locale':
			$paypal_locale = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'paypal_mode':
			$paypal_mode = isset($row['value']) ? $row['value'] : -1;
			break;

		case 'paypal_sandbox_merch_id':
			$paypal_sandbox_merch_id = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'site_name':
			$site_name = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'smtp_server':
			$smtp_server = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'smtp_user':
			$smtp_user = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'smtp_pass':
			$smtp_pass = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'smtp_port':
			$smtp_port = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_mode':
			$stripe_mode = isset($row['value']) ? $row['value'] : -1;
			break;

		case 'stripe_test_secret_key':
			$stripe_test_secret_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_test_publishable_key':
			$stripe_test_publishable_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_live_secret_key':
			$stripe_live_secret_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_live_publishable_key':
			$stripe_live_publishable_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_data_currency':
			$stripe_data_currency = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_currency_symbol':
			$stripe_currency_symbol = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_data_image':
			$stripe_data_image = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'stripe_data_description':
			$stripe_data_description = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'timezone':
			$timezone = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'tomtom_secret':
			$tomtom_secret = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'twitter_key':
			$twitter_key = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'twitter_secret':
			$twitter_secret = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'cfg_languages':
			$cfg_languages = !empty($row['value']) ? $row['value'] : 'en';
			break;

		case 'cgf_near_listings_radius':
			$cgf_near_listings_radius = !empty($row['value']) ? $row['value'] : 150;
			break;

		case 'cfg_latest_listings_count':
			$cfg_latest_listings_count = !empty($row['value']) ? $row['value'] : 12;
			break;

		case 'user_created_notify':
			$user_created_notify = $row['value'] == 0 ? 0 : 1;
			break;

		case 'site_logo_width':
			$site_logo_width = !empty($row['value']) ? $row['value'] : 180;
			break;

		case 'cfg_decimal_separator':
			$cfg_decimal_separator = !empty($row['value']) ? $row['value'] : '.';
			break;

		case 'cfg_use_select2':
			$cfg_use_select2 = $row['value'] == 0 ? 0 : 1;
			break;

		case 'cfg_date_format':
			$cfg_date_format = !empty($row['value']) ? $row['value'] : 'Y-m-d';
			break;

		case 'cfg_permalink_struct':
			$cfg_permalink_struct = !empty($row['value']) ? $row['value'] : 'listing';
			break;

		case 'cgf_max_dist_values':
			$cgf_max_dist_values = !empty($row['value']) ? $row['value'] : '';
			break;

		case 'cgf_max_dist_unit':
			$cgf_max_dist_unit = !empty($row['value']) ? $row['value'] : 'km';
			break;
	}
}

$admin_email                  = trim(e($admin_email                 ));
$country_name                 = trim(e($country_name                ));
$currency_code                = trim(e($currency_code               ));
$currency_symbol              = trim(e($currency_symbol             ));
$default_city_slug            = trim(e($default_city_slug           ));
$default_country_code         = trim(e($default_country_code        ));
$default_lat                  = trim(e($default_lat                 ));
$default_lng                  = trim(e($default_lng                 ));
$default_loc_id               = trim(e($default_loc_id              ));
$dev_email                    = trim(e($dev_email                   ));
$disqus_shortname             = trim(e($disqus_shortname            ));
$facebook_key                 = trim(e($facebook_key                ));
$facebook_secret              = trim(e($facebook_secret             ));
$google_key                   = trim(e($google_key                  ));
$html_lang                    = trim(e($html_lang                   ));
$items_per_page               = trim(e($items_per_page              ));
$mail_after_post              = trim(e($mail_after_post             ));
$max_pics                     = trim(e($max_pics                    ));
$notify_url                   = trim(e($notify_url                  ));
$paypal_bn                    = trim(e($paypal_bn                   ));
$paypal_checkout_logo_url     = trim(e($paypal_checkout_logo_url    ));
$paypal_locale                = trim(e($paypal_locale               ));
$paypal_merchant_id           = trim(e($paypal_merchant_id          ));
$paypal_mode                  = trim(e($paypal_mode                 ));
$paypal_sandbox_merch_id      = trim(e($paypal_sandbox_merch_id     ));
$site_name                    = trim(e($site_name                   ));
$smtp_pass                    = trim(e($smtp_pass                   ));
$smtp_port                    = trim(e($smtp_port                   ));
$smtp_server                  = trim(e($smtp_server                 ));
$smtp_user                    = trim(e($smtp_user                   ));
$stripe_currency_symbol       = trim(e($stripe_currency_symbol      ));
$stripe_data_currency         = trim(e($stripe_data_currency        ));
$stripe_data_description      = trim(e($stripe_data_description     ));
$stripe_data_image            = trim(e($stripe_data_image           ));
$stripe_live_publishable_key  = trim(e($stripe_live_publishable_key ));
$stripe_live_secret_key       = trim(e($stripe_live_secret_key      ));
$stripe_mode                  = trim(e($stripe_mode                 ));
$stripe_test_publishable_key  = trim(e($stripe_test_publishable_key ));
$stripe_test_secret_key       = trim(e($stripe_test_secret_key      ));
$timezone                     = trim(e($timezone                    ));
$twitter_key                  = trim(e($twitter_key                 ));
$twitter_secret               = trim(e($twitter_secret              ));
$cfg_languages                = trim(e($cfg_languages               ));
$cgf_near_listings_radius     = trim(e($cgf_near_listings_radius    ));
$cfg_latest_listings_count    = trim(e($cfg_latest_listings_count   ));
$user_created_notify          = trim(e($user_created_notify         ));
$site_logo_width              = trim(e($site_logo_width             ));
$cfg_decimal_separator        = trim(e($cfg_decimal_separator       ));
$cfg_use_select2              = trim(e($cfg_use_select2             ));
$cfg_date_format              = trim(e($cfg_date_format             ));
$cfg_permalink_struct         = trim(e($cfg_permalink_struct        ));
$cgf_max_dist_values          = trim(e($cgf_max_dist_values         ));
$cgf_max_dist_unit            = trim(e($cgf_max_dist_unit           ));

// cfg_languages
$cfg_languages = explode(',', $cfg_languages);

// default lat lng
$default_latlng = "$default_lat,$default_lng";

// default timezone and locale
setlocale(LC_ALL, 'en_US');
date_default_timezone_set($timezone); // see http://php.net/manual/en/timezones.php

// if production mode
if($paypal_mode == 1) {
	$paypal_url         = 'https://www.paypal.com/cgi-bin/webscr';
}
// else is sandbox
else {
	$paypal_url         = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	$paypal_merchant_id = $paypal_sandbox_merch_id;
}

// notify url
$notify_url = $baseurl . '/payment-gateways/paypal.php';

// remove encryption protocol from smtp server address
$smtp_server = str_replace('ssl://', '', $smtp_server);
$smtp_server = str_replace('tls://', '', $smtp_server);

// start session
session_start();

/*--------------------------------------------------
City cookie management
--------------------------------------------------*/
if(!empty($_COOKIE['city_id']) && empty($_COOKIE['city_name'])) {
	$cookie_city_id = $_COOKIE['city_id'];

	// get city details
	$query = "SELECT
				c.city_name, c.slug AS city_slug,
				s.state_id, s.state_abbr, s.slug AS state_slug
				FROM cities c
				LEFT JOIN states s ON c.state_id = s.state_id
				WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $cookie_city_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$cookie_city_name  = !empty($row['city_name' ]) ? $row['city_name' ] : '';
	$cookie_city_slug  = !empty($row['city_slug' ]) ? $row['city_slug' ] : '';
	$cookie_state_id   = !empty($row['state_id'  ]) ? $row['state_id'  ] : '';
	$cookie_state_abbr = !empty($row['state_abbr']) ? $row['state_abbr'] : '';
	$cookie_state_slug = !empty($row['state_slug']) ? $row['state_slug'] : '';

	// save city details in cookie
	// signature: setcookie(name, value, expire, path, domain, secure, httponly);
	setcookie('city_id'   , $cookie_city_id   , time()+86400*90, $install_path);
	setcookie('city_name' , $cookie_city_name , time()+86400*90, $install_path);
	setcookie('city_slug' , $cookie_city_slug , time()+86400*90, $install_path);
	setcookie('state_id'  , $cookie_state_id  , time()+86400*90, $install_path);
	setcookie('state_abbr', $cookie_state_abbr, time()+86400*90, $install_path);
	setcookie('state_slug', $cookie_state_slug, time()+86400*90, $install_path);

	// instead of reloading, set cookie manually for this session
	$_COOKIE['city_id'   ] = $cookie_city_id;
	$_COOKIE['city_name' ] = $cookie_city_name;
	$_COOKIE['city_slug' ] = $cookie_city_slug;
	$_COOKIE['state_id'  ] = $cookie_state_id;
	$_COOKIE['state_abbr'] = $cookie_state_abbr;
	$_COOKIE['state_slug'] = $cookie_state_slug;
}

if(!empty($_COOKIE['city_name'])) {
	$cookie_city_name = htmlspecialchars($_COOKIE['city_name']);
}

// if no city_id cookie, delete all city related cookies
if(empty($_COOKIE['city_id'])) {

	// signature: setcookie(name, value, expire, path, domain, secure, httponly);
	// delete cookies from browser
	setcookie('city_id',  '', time()-42000, $install_path);
	setcookie('city_name',  '', time()-42000, $install_path);
	setcookie('city_slug',  '', time()-42000, $install_path);
	setcookie('state_id',   '', time()-42000, $install_path);
	setcookie('state_abbr', '', time()-42000, $install_path);
	setcookie('state_slug', '', time()-42000, $install_path);

	// delete $_COOKIE value too
	unset($_COOKIE['city_id']);
	unset($_COOKIE['city_name']);
	unset($_COOKIE['city_slug']);
	unset($_COOKIE['state_id']);
	unset($_COOKIE['state_abbr']);
	unset($_COOKIE['state_slug']);
}

/*--------------------------------------------------
User Login
--------------------------------------------------*/
$userid     = '';
$first_name = '';
$last_name  = '';

// if sessions user_connected and userid exist, query db to get user first name, last name and email.
if(!empty($_SESSION['user_connected']) && !empty($_SESSION['userid']) && !empty($_SESSION['loggedin_token'])) {
	$userid = $_SESSION['userid'];

	$query = "SELECT
				u.first_name, u.email, u.last_name, u.hybridauth_provider_name,
				l.token AS loggedin_token
				FROM users u
				LEFT JOIN loggedin l ON u.id = l.userid
				WHERE u.id = :userid";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$first_name               = $row['first_name'];
	$email                    = $row['email'];
	$last_name                = $row['last_name'];
	$hybridauth_provider_name = $row['hybridauth_provider_name'];
	$loggedin_token           = $row['loggedin_token'];

	// has session but no corresponding row in users table
	// logout, destroy session and cookies
	if(empty($row)) {
		destroy_session_and_cookie();
	}

	// check session token matches the one in db
	if(sha1($_SESSION['loggedin_token']) != $loggedin_token) {
		destroy_session_and_cookie();
	}
}

// if no session, check if it has loggedin cookie
elseif(!empty($_COOKIE['loggedin'])) {
	$_SESSION['user_connected'] = false;

	$loggedin_cookie      = $_COOKIE['loggedin'];
	$cookie_frags         = explode('-', $loggedin_cookie);
	$cookie_userid        = $cookie_frags[0];
	$cookie_provider_name = $cookie_frags[1];
	$cookie_token         = $cookie_frags[2];

	// now delete previous loggedin database entry so we can issue a new one
	$query = "SELECT COUNT(*) AS total_rows FROM loggedin WHERE userid = :userid AND token = :token";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $cookie_userid);
	$stmt->bindValue(':token', sha1($cookie_token));
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// if pair cookie/token existed, then user is legit, retrieve name
	if($row['total_rows'] == 1) {
		// delete all tokens for this user
		$query = "DELETE FROM loggedin WHERE userid = :userid";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':userid', $cookie_userid);
		$stmt->execute();

		$stmt = $conn->prepare("SELECT first_name, email, last_name, hybridauth_provider_name FROM users WHERE id = :userid");
		$stmt->bindValue(':userid', $cookie_userid);
		$user_exist = $stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$first_name               = $row['first_name'];
		$email                    = $row['email'];
		$last_name                = $row['last_name'];
		$hybridauth_provider_name = $row['hybridauth_provider_name'];
		$userid                   = $cookie_userid;

		// generate new token and insert userid and token pair into db
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		record_tokens($cookie_userid, $cookie_provider_name, $token);

		// set cookie
		// signature: setcookie(name, value, expire, path, domain, secure, httponly);
		$cookie_val = "$cookie_userid-$cookie_provider_name-$token";
		setcookie('loggedin', $cookie_val, time()+86400*30, $install_path, '', '', true);

		// create sessions
		$_SESSION['user_connected'] = true;
		$_SESSION['userid'] = $cookie_userid;
		$_SESSION['loggedin_token'] = $token;
	}

	// else pair userid and token doesn't exist in db
	else {
		destroy_session_and_cookie();
	}
}

else {
	//destroy_session_and_cookie();
}

// sanitize user vars
if(!empty($first_name)) {
	$first_name = htmlspecialchars($first_name);
}

if(!empty($email)) {
	$email = htmlspecialchars($email);
}

if(!empty($last_name)) {
	$last_name = htmlspecialchars($last_name);
}

// check if is admin
$is_admin = 0;

if($userid == 1) {
	$is_admin = 1;
}

/*--------------------------------------------------
database updater
--------------------------------------------------*/
if(file_exists('db-updater.php')) {
	include('db-updater.php');
	unlink('db-updater.php');
}

/*--------------------------------------------------
Language vars from db
--------------------------------------------------*/
$user_cookie_lang = 'en';

if(isset($_COOKIE['user_language'])) {
	$html_lang = $_COOKIE['user_language'];
	$user_cookie_lang = $_COOKIE['user_language'];

	// check if lang exists, if not, default to 'en'
	$query = "SELECT COUNT(*) AS c FROM language WHERE lang = :lang";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':lang', $html_lang);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row['c'] < 1) {
		$html_lang = 'en';
		$user_cookie_lang = 'en';
	}
}

// check if html_lang translation exists, if not, default to 'en'
$query = "SELECT COUNT(*) AS c FROM language WHERE lang = :lang";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$html_lang = 'en';
}

// global translations
$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = 'global'";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = $row['translated'];
}

// current route translation
if(isset($route[0])) {
	if($route[0] != 'user' && $route[0] != 'admin') {
		if($route[0] != '') {
			if(in_array($route[0], $valid_routes)) {
				$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = :template";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':lang', $html_lang);
				$stmt->bindValue(':template', $route[0]);
				$stmt->execute();

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					${$row['var_name']} = $row['translated'];
				}
			}

			else {
				$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = :template";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':lang', $html_lang);
				$stmt->bindValue(':template', 'listing');
				$stmt->execute();

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					${$row['var_name']} = $row['translated'];
				}
			}
		}

		// load home
		else {
			$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = 'home'";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':lang', $html_lang);
			$stmt->execute();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				${$row['var_name']} = $row['translated'];
			}
		}
	}

	if($route[0] == 'user') {
		// load template language
		$query = "SELECT * FROM language WHERE lang = :lang AND section = 'user' AND template = :template";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $html_lang);
		$stmt->bindValue(':template', $route[1]);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			${$row['var_name']} = $row['translated'];
		}
	}

	if($route[0] == 'admin') {
		// load global translations
		$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = 'global'";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $html_lang);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			${$row['var_name']} = $row['translated'];
		}

		// load admin global translations
		$query = "SELECT * FROM language WHERE lang = :lang AND section = 'admin' AND template = 'admin-global'";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $html_lang);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			${$row['var_name']} = $row['translated'];
		}

		// load template language
		$query = "SELECT * FROM language WHERE lang = :lang AND section = 'admin' AND template = :template";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $html_lang);
		$stmt->bindValue(':template', $route[1]);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			${$row['var_name']} = $row['translated'];
		}
	}
}

/*--------------------------------------------------
Maintenance Mode
--------------------------------------------------*/
if($maintenance_mode == 1 && !$is_admin) {
	if(!(isset($route[1]) && $route[1] == 'sign-in')) {
		http_response_code(503);
		header("Retry-After: 3600");

		// language
		$query = "SELECT * FROM language WHERE lang = :lang AND section = 'public' AND template = :template";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $html_lang);
		$stmt->bindValue(':template', 'maintenance');
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			${$row['var_name']} = $row['translated'];
		}

		// include child template if exists
		if(is_file(__DIR__ . '/../templates/tpl-maintenance-child.php')) {
			require_once(__DIR__ . '/../templates/tpl-maintenance-child.php');
		}

		// else include original template file
		else {
			require_once(__DIR__ . '/../templates/tpl-maintenance.php');
		}

		die();
	}
}

/*--------------------------------------------------
Pictures folders
--------------------------------------------------*/

$pic_baseurl = $baseurl . '/pictures';
$pic_basepath = __DIR__ . '/../pictures';

// place pics
$place_full_folder    = 'place-full';
$place_thumb_folder   = 'place-thumb';
$place_tmp_folder     = 'place-tmp';

// profile pics
$profile_full_folder  = 'profile-full';
$profile_thumb_folder = 'profile-thumb';
$profile_tmp_folder   = 'profile-tmp';

/*--------------------------------------------------
Prevent direct access
--------------------------------------------------*/
$blocked_files = array(
'home.php',
'categories.php',
'claim.php',
'contact.php',
'coupon.php',
'coupons.php',
'favorites.php',
'listing.php',
'listings.php',
'msg.php',
'page.php',
'pages.php',
'post.php',
'posts.php',
'profile.php',
'results.php',
'reviews.php',
'create-listing.php',
'edit-listing.php',
'edit-pass.php',
'forgot-password.php',
'my-coupons.php',
'my-favorites.php',
'my-listings.php',
'my-profile.php',
'my-reviews.php',
'password-reset.php',
'process-claim.php',
'process-create-listing.php',
'process-edit-listing.php',
'process-edit-pass.php',
'register.php',
'register-confirm.php',
'resend-confirmation.php',
'select-plan.php',
'sign-in.php',
'sign-out.php',
'thanks.php',
);

if(in_array(basename($_SERVER['SCRIPT_NAME']), $blocked_files)) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

/*--------------------------------------------------
Route strings definitions
--------------------------------------------------*/
$route_listing = 'listing';
$route_listings = 'listings';

/*--------------------------------------------------
my.functions.php
--------------------------------------------------*/
if(file_exists(__DIR__ . '/my.functions.php')) {
	require_once(__DIR__ . '/my.functions.php');
}
