<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../inc/img-exts.php');

// only allow access to this file for logged in users
if(!array_key_exists('userid', $_SESSION) && empty($_SESSION['userid'])) {
    die('You do not have permission to access this page');
}

// initialize response
$response = array();

// if upload error
if($_FILES['logo_img']['error'] != 0) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($_FILES['logo_img']['error']),
		'filename' => '',
	);
}

else if(!exif_imagetype($_FILES['logo_img']['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => 'Invalid image type. Please select another image',
		'filename' => '',
	);
}

else {
	// basename
	$uploaded_img = basename($_FILES['logo_img']['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	// if valid image file extension
	if(in_array($extension, $img_exts)) {
		// generate file name
		$filename = uniqid();

		// paths
		$filename = $filename . '.' . $extension;
		$path_tmp = $pic_basepath . '/logo-tmp/' . $filename;
		$url_tmp  = $pic_baseurl . '/logo-tmp/' . $filename;

		// move uploaded
		if(@move_uploaded_file($_FILES['logo_img']['tmp_name'], $path_tmp)) {
			if(empty($cfg_logo_size)) {
				$cfg_logo_size = array(540, 540);
			}

			else {
				if(empty($cfg_logo_size[0])) {
					$cfg_logo_size[0] = 540;
				}

				if(empty($cfg_logo_size[1])) {
					$cfg_logo_size[1] = 540;
				}
			}

			smart_resize_image($path_tmp, null, $cfg_logo_size[0], $cfg_logo_size[1], true, $path_tmp, false, false, $cfg_logo_quality);

			$response = array(
				'result' => 'success',
				'message' => $url_tmp,
				'filename' => $filename,
			);

			// delete previous logo
			$prev_img = isset($_POST['prev_img']) ? $_POST['prev_img'] : '';

			if(!empty($prev_img)) {
				$prev_img = $pic_basepath . '/logo-tmp/' . $prev_img;

				if(is_file($prev_img)) {
					// unlink
					unlink($prev_img);
				}
			}
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