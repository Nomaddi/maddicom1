<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/img-exts.php');

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=data.json");

$all_data = array();
if(!isset($cfg_gdpr_on)) $cfg_gdpr_on = false;

/*--------------------------------------------------
Profile
--------------------------------------------------*/
$stmt = $conn->prepare('SELECT * FROM users WHERE id = :userid');
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$all_data['profile'] = $row;

/*--------------------------------------------------
Reviews
--------------------------------------------------*/
$stmt = $conn->prepare('SELECT * FROM reviews WHERE user_id = :userid');
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$all_data['reviews'] = $row;

/*--------------------------------------------------
Profile pics
--------------------------------------------------*/
$profile_pic = '';
$profile_thumb = '';
$folder = floor($userid / 1000) + 1;

if(strlen($folder) < 1) {
	$folder = '999';
}

// get profile pic filename
$profile_pic_path = $profile_full_folder . '/' . $folder . '/' . $userid;
$profile_thumb_path = $profile_thumb_folder . '/' . $folder . '/' . $userid;

foreach($img_exts as $v) {
	if(file_exists($pic_basepath . '/' . $profile_pic_path . '.' . $v)) {
		$profile_pic = $pic_baseurl . '/' . $profile_pic_path . '.' . $v;
		$profile_thumb = $pic_baseurl . '/' . $profile_thumb_path . '.' . $v;
		break;
	}
}

if(!empty($profile_pic)) {
	$all_data['profile_pic']['full'] = $profile_pic;
}

if(!empty($profile_thumb)) {
	$all_data['profile_pic']['thumb'] = $profile_thumb;
}

/*--------------------------------------------------
Build download
--------------------------------------------------*/
$all_data = json_encode($all_data);

if($cfg_gdpr_on) {
	echo $all_data;
}