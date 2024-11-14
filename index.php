<?php
/**
 * Router file
 *
 * .htaccess routes all requests to this file
 *
 */

// remove ?var=val
$request_uri = strtok($_SERVER["REQUEST_URI"],'?');

// get install path
$install_dir = rtrim($_SERVER['SCRIPT_NAME'], 'index.php');

// query vars array
$route = preg_replace('~^' . $install_dir . '~', '', $request_uri);

$route = rtrim($route, '/');
$route = explode('/', $route);

// sanitize route
foreach($route as $k => $v) {
	$route[$k] = htmlspecialchars($v);
}

/*--------------------------------------------------
Public
--------------------------------------------------*/
$is_tpl_listing = false;

if($route[0] != 'user' && $route[0] != 'admin') {
	$valid_routes = array(
		'home',
		'categories',
		'claim',
		'contact',
		'coupon',
		'coupons',
		'favorites',
		'listing',
		'listings',
		'msg',
		'page',
		'pages',
		'post',
		'posts',
		'profile',
		'results',
		'reviews',
	);

	if($route[0] == '') {
		$route[0] = 'home';
	}

	if(in_array($route[0], $valid_routes)) {
		// include core file
		require_once(__DIR__ . '/' . $route[0] . '.php');

		if($route[0] == 'listing') {
			$is_tpl_listing = true;
		}

		// include child template if exists
		if(is_file(__DIR__ . '/templates/tpl-' . $route[0] . '-child.php')) {
			require_once(__DIR__ . '/templates/tpl-' . $route[0] . '-child.php');
		}

		// else include original template file
		else {
			require_once(__DIR__ . '/templates/tpl-' . $route[0] . '.php');
		}
	}

	// else include listing template
	else {
		// flag that this is the listing template
		$is_tpl_listing = true;

		// include core file
		require_once(__DIR__ . '/listing.php');

		// include child template if exists
		if(is_file(__DIR__ . '/templates/tpl-listing-child.php')) {
			require_once(__DIR__ . '/templates/tpl-listing-child.php');
		}

		// else include original template file
		else {
			require_once(__DIR__ . '/templates/tpl-listing.php');
		}
	}
}

/*--------------------------------------------------
User
--------------------------------------------------*/
if($route[0] == 'user') {
	$valid_routes = array(
		'create-listing',
		'edit-listing',
		'edit-pass',
		'forgot-password',
		'my-coupons',
		'my-favorites',
		'my-listings',
		'my-profile',
		'my-reviews',
		'password-reset',
		'process-claim',
		'process-create-listing',
		'process-edit-listing',
		'process-edit-pass',
		'register',
		'register-confirm',
		'resend-confirmation',
		'select-plan',
		'sign-in',
		'sign-out',
		'thanks',
	);

	if(in_array($route[1], $valid_routes)) {
		if($route[0] != '') {
			// include core file
			require_once(__DIR__ . '/user/' . $route[1] . '.php');

			// include child template if exists
			if(is_file(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '-child.php')) {
				require_once(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '-child.php');
			}

			// else include original template file
			else {
				require_once(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '.php');
			}
		}

		else {
			http_response_code(404);
			require_once(__DIR__ . '/templates/404.php');
			die();
		}
	}

	else {
		http_response_code(404);
		require_once(__DIR__ . '/templates/404.php');
		die();
	}
}

/*--------------------------------------------------
Admin area
--------------------------------------------------*/
if($route[0] == 'admin') {
	$valid_routes = array(
		'categories',
		'categories-trash',
		'coupons',
		'coupons-trash',
		'create-page',
		'create-custom-field',
		'custom-fields',
		'custom-fields-trash',
		'edit-custom-field',
		'edit-page',
		'emails',
		'home',
		'language',
		'listings',
		'listings-trash',
		'locations',
		'pages',
		'pages-trash',
		'plans',
		'plans-trash',
		'process-settings',
		'reviews',
		'reviews-trash',
		'settings',
		'tools',
		'transactions',
		'users',
		'users-trash',
	);

	if(in_array($route[1], $valid_routes)) {
		// include core file
		require_once(__DIR__ . '/admin/' . $route[1] . '.php');

		// include child template if exists
		if(is_file(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '-child.php')) {
			require_once(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '-child.php');
		}

		// else include original template file
		else {
			require_once(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '.php');
		}
	}

	else {
		http_response_code(404);
		require_once(__DIR__ . '/templates/404.php');
		die();
	}
}