<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../inc/img-exts.php');

// only allow access to this file for logged in users
if(!array_key_exists('userid', $_SESSION) && empty($_SESSION['userid'])) {
    die('You do not have permission to access this page');
}

// coupons enabled check
if(!$cfg_enable_coupons) {
	die("Invalid request");
}

// file exists check
if(empty($_FILES['coupon_img'])) {
	die('You do not have permission to access this page');
}

// initialize response
$response = array();

// if upload error
if($_FILES['coupon_img']['error'] != 0) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($_FILES['coupon_img']['error']),
		'filename' => '',
	);
}

else if(!exif_imagetype($_FILES['coupon_img']['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => 'Invalid image type. Please select another image',
		'filename' => '',
	);
}

else {
	// basename
	$uploaded_img = basename($_FILES['coupon_img']['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	// if valid image file extension
	if(in_array($extension, $img_exts)) {
		// generate file name
		$filename = uniqid();

		// paths
		$filename = $filename . '.' . $extension;
		$path_tmp = $pic_basepath . '/coupons-tmp/' . $filename;
		$url_tmp  = $pic_baseurl . '/coupons-tmp/' . $filename;

		// move uploaded
		if(@move_uploaded_file($_FILES['coupon_img']['tmp_name'], $path_tmp)) {
			if(empty($cfg_coupon_size)) {
				$cfg_coupon_size = array(540, 540);
			}

			else {
				if(empty($cfg_coupon_size[0])) {
					$cfg_coupon_size[0] = 540;
				}

				if(empty($cfg_coupon_size[1])) {
					$cfg_coupon_size[1] = 540;
				}
			}

			smart_resize_image($path_tmp, null, $cfg_coupon_size[0], $cfg_coupon_size[1], true, $path_tmp, false, false, 75);

			$response = array(
				'result' => 'success',
				'message' => $url_tmp,
				'filename' => $filename,
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

	else {
		$response = array(
			'result' => 'fail',
			'message' => 'Invalid image extension',
			'filename' => '',
		);
	}
}

echo json_encode($response);