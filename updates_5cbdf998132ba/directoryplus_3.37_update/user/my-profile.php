<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/img-exts.php');

$stmt = $conn->prepare('SELECT * FROM users WHERE id = :userid');
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$first_name   = '';
$last_name    = '';
$email        = '';
$profile_city = '';

$first_name         = !empty($row['first_name'        ]) ? $row['first_name'        ] : '';
$last_name          = !empty($row['last_name'         ]) ? $row['last_name'         ] : '';
$email              = !empty($row['email'             ]) ? $row['email'             ] : '';
$profile_city       = !empty($row['city_name'         ]) ? $row['city_name'         ] : '';
$profile_country    = !empty($row['country_name'      ]) ? $row['country_name'      ] : '';
$profile_pic_status = !empty($row['profile_pic_status']) ? $row['profile_pic_status'] : '';

// sanitize
$first_name         = e($first_name);
$last_name          = e($last_name);
$email              = e($email);
$profile_city       = e($profile_city);
$profile_country    = e($profile_country);

/*--------------------------------------------------
Profile pic
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
	$profile_pic_tag = '<div class="dummy container-img rounded" style="background-image:url(\'' . $profile_pic . '?' . uniqid() . '\');"></div>';
}

else {
	$profile_pic_tag = '<img src="' . $baseurl . '/assets/imgs/blank.png" width="150" height="150">';
}

/*--------------------------------------------------
$cfg
--------------------------------------------------*/
if(!isset($cfg_gdpr_on)) $cfg_gdpr_on = false;

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/my-profile';