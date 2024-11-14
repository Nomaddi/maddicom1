<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

/*
This file is requested by the js-create-listing.php and js-edit-listing.php files when one of the temporary uploaded pic(s) is deleted. It deletes the entry from the tmp_photos table so that the process-upload.php file can get the actual number of pics that have been uploaded
*/

// only allow access to this file for logged in users
if(!array_key_exists('userid', $_SESSION) && empty($_SESSION['userid'])) {
    die('You do not have permission to access this page');
}

// check submit token
$submit_token = '';

if(isset($_SESSION['submit_token'])) {
	$submit_token = $_SESSION['submit_token'];
}

if(isset($_COOKIE['submit_token'])) {
	$submit_token = $_COOKIE['submit_token'];
}

if(empty($submit_token)) {
	echo "Submit token empty";
	die();
}

// this is the tmp_filename
$tmp_filename = !empty($_POST['tmp_filename']) ? $_POST['tmp_filename'] : '';

// delete from tmp_photos
$query = "DELETE FROM tmp_photos WHERE filename = :tmp_filename";
$stmt = $conn->prepare($query);
$stmt->bindValue(':tmp_filename', $tmp_filename);

if($stmt->execute()) {
	echo "successfully deleted tmp filename from tmp_photos table";
}

