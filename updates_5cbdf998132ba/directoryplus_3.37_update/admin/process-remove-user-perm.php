<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$remove_user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : 0;

// update status
if(!empty($remove_user_id)) {
	$query = "DELETE FROM users WHERE id = :remove_user_id AND status = 'trashed'";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':remove_user_id', $remove_user_id);
	$stmt->execute();

	// remove profile picture
	$folder = floor($remove_user_id / 1000) + 1;

	if(strlen($folder) < 1) {
		$folder = '999';
	}

	// profile pic path
	$full_pic_path  = $pic_basepath . '/' . $profile_full_folder . '/' . $folder . '/' . $remove_user_id;
	$thumb_pic_path = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder . '/' . $remove_user_id;

	// check if file exists
	$full_pic_arr = glob("$full_pic_path.*");
	$thumb_pic_arr = glob("$thumb_pic_path.*");

	if(!empty($full_pic_arr)) {
		foreach($full_pic_arr as $k => $v) {
			if(is_file($v)) {
				unlink($v);
			}
		}
	}

	if(!empty($thumb_pic_arr)) {
		foreach($thumb_pic_arr as $k => $v) {
			unlink($v);
		}
	}

	echo '1';
}

else {
	echo 'Invalid user_id';
}