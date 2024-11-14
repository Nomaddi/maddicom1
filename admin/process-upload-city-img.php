<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../inc/img-exts.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// max size
$upload_max_filesize = ini_get('upload_max_filesize');

if($_FILES['city_img']['error'] != 0) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($_FILES['city_img']['error']),
		'filename' => ''
	);
}

elseif (!exif_imagetype($_FILES['city_img']['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => 'Invalid image type',
		'filename' => ''
	);
}

else {
	// basename - Returns trailing name component of path
	$uploaded_img = basename($_FILES['city_img']['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	if(!in_array($extension, $img_exts)) {
		$response = array(
			'result' => 'fail',
			'message' => 'Extension not allowed: ' . e($extension),
			'filename' => ''
		);
	}

	else {
		// generate file name
		$filename = uniqid();

		// paths
		$filename = $filename . '.' . $extension;
		$path_tmp = $pic_basepath . '/city-tmp/' . $filename;
		$url_tmp  = $pic_baseurl . '/city-tmp/' . $filename;

		// move uploaded
		if(@move_uploaded_file($_FILES['city_img']['tmp_name'], $path_tmp)) {
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
}

echo json_encode($response);