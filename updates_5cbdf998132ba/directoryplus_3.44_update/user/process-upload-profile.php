<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../inc/img-exts.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// max size
$upload_max_filesize = ini_get('upload_max_filesize');

// img size
$profile_dims = isset($profile_dims) ? $profile_dims : array(720, 540, 360, 360);
$profile_img_qual = isset($profile_img_qual) ? $profile_img_qual : 100;

// check
if($_FILES['profile_pic']['error'] != 0) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($_FILES['profile_pic']['error']),
		'filename' => ''
	);
}

elseif (!exif_imagetype($_FILES['profile_pic']['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => 'Invalid image type',
		'filename' => ''
	);
}

else {
	// basename - Returns trailing name component of path
	$uploaded_img = basename($_FILES['profile_pic']['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	// validate extension
	if(!in_array($extension, $img_exts)) {
		$response = array(
			'result' => 'fail',
			'message' => 'Invalid image extension',
			'filename' => $filename,
		);
	}

	// define upload folder
	$folder = floor($userid / 1000) + 1;

	if(strlen($folder) < 1) {
		$folder = '999';
	}

	// paths
	$filename = $userid . '.' . $extension;
	$path_tmp = $pic_basepath . '/' . $profile_tmp_folder . '/' . $filename;

	// path full pic
	$path_full = $pic_basepath . '/' . $profile_full_folder . '/' . $folder;

	if (!is_dir($path_full)) {
		mkdir($path_full, 0777, true);
	}

	$dst_img_path_full = $path_full . '/' . $userid . '.' . $extension;

	// path thumb
	$path_thumb = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder;

	if (!is_dir($path_thumb)) {
		mkdir($path_thumb, 0777, true);
	}

	$dst_img_path_thumb = $path_thumb . '/' . $userid . '.' . $extension;

	// first remove previous profile pictures
	$thumb = glob("$path_thumb/$userid.*");
	foreach($thumb as $v) {
		unlink($v);
	}

	$full = glob("$path_full/$userid.*");
	foreach($full as $v) {
		unlink($v);
	}

	// move uploaded
	if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path_tmp)) {
		// update table
		$stmt = $conn->prepare("UPDATE users SET profile_pic_status = 'approved' WHERE id = :userid");
		$stmt->bindValue(':userid', $userid);
		$stmt->execute();

		// first remove previous profile pictures
		$thumb = glob("$path_thumb/$userid.*");

		foreach($thumb as $v) {
			unlink($v);
		}

		$full = glob("$path_full/$userid.*");

		foreach($full as $v) {
			unlink($v);
		}

		// upload
		smart_resize_image($path_tmp, null, $profile_dims[0], $profile_dims[1], false, $dst_img_path_full, false, false, $profile_img_qual);
		smart_resize_image($path_tmp, null, $profile_dims[2], $profile_dims[3], false, $dst_img_path_thumb, false, false, $profile_img_qual);

		// unlink
		unlink($path_tmp);

		// response
		$response = array(
			'result' => 'success',
			'message' => 'success',
			'filename' => $filename
		);

	}

	else {
		$response = array(
			'result' => 'fail',
			'message' => 'move_uploaded_file error',
			'filename' => ''
		);
	}
}

echo json_encode($response);