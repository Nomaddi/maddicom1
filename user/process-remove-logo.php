<?php
require_once(__DIR__ . '/../inc/config.php');
//require_once(__DIR__ . '/user_area_inc.php');

// only allow access to this file for logged in users
if(!array_key_exists('userid', $_SESSION) && empty($_SESSION['userid'])) {
    die('You do not have permission to access this page');
}

// this is the tmp logo
$logo_img = $_POST['logo_img'];

if(!empty($logo_img)) {
	$logo_img = $pic_basepath . '/logo-tmp/' . $logo_img;

	if(unlink($logo_img)) {
		echo 'success:' . $logo_img;
	}

	else {
		echo 'fail:' . $logo_img;
	}
}

// if requested from the edit listing page, then try to delete saved logo
$existing_logo = !empty($_POST['existing_logo']) ? $_POST['existing_logo'] : '';

if(!empty($existing_logo)) {
	$existing_logo = $pic_basepath . '/logo/' . substr($existing_logo, 0, 2) . '/' . $existing_logo;

	if(unlink($existing_logo)) {
		echo 'success:' . $existing_logo;
	}

	else {
		echo 'fail:' . $existing_logo;
	}
}