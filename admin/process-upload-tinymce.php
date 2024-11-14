<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/img-exts.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// max size
$upload_max_filesize = ini_get('upload_max_filesize');

// uploaded file
reset ($_FILES);
$temp = current($_FILES);

// check error
if($temp['error'] != 0 || !exif_imagetype($temp['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($temp['error']),
		'location' => file_upload_errors($temp['error'])
	);

	echo json_encode($response);
	exit();
}

else {
	// folder
	$folder = date('Y-m');
	$folder_path = $pic_basepath . '/page-imgs/' . $folder;

	if(!is_dir($folder_path)) {
		if(!mkdir($folder_path, 0755, true)) {
			$response = array(
				'result' => 'fail',
				'message' => 'Fail creating folder',
				'location' => 'Fail creating folder',
			);

			echo json_encode($response);
			exit();
		}

		// create empty index file in the folder
		touch($folder_path . '/index.php');
	}

	// basename - Returns trailing name component of path
	$uploaded_img = basename($temp['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	// validate extension
	if(!in_array($extension, $img_exts)) {
		$response = array(
			'result' => 'fail',
			'message' => 'Invalid image extension',
			'location' => 'Invalid image extension',
		);

		echo json_encode($response);
		exit();
	}

	// generate file name
	$filename = uniqid();

	// paths
	$filename = $filename . '.' . $extension;
	$img_path = $pic_basepath . '/page-imgs/' . $folder . '/' . $filename;
	$img_url  = $pic_baseurl . '/page-imgs/' . $folder . '/' . $filename;

	// move uploaded
	// tinymce requires 'location' index in the response
	if(@move_uploaded_file($temp['tmp_name'], $img_path)) {
		$response = array(
			'result' => 'success',
			'message' => $img_url,
			'location' => $img_url,
		);
	}

	else {
		$response = array(
			'result' => 'fail',
			'message' => 'move_uploaded_file error',
			'location' => 'move_uploaded_file error',
		);
	}

	echo json_encode($response);
}