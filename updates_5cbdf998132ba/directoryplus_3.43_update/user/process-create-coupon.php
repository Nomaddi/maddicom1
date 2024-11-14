<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// coupons enabled check
if(!$cfg_enable_coupons) {
	die("Invalid request");
}

// set default coupon expire to one year from now
$default_expire_date = new DateTime();
$default_expire_date->add(new DateInterval('P1Y'));
$default_expire_date = $default_expire_date->format('Y-m-d H:i:s');

// get post data
$params = array();
parse_str($_POST['params'], $params);

$coupon_title       = !empty($params['coupon_title'      ]) ? $params['coupon_title'      ] : '';
$coupon_description = !empty($params['coupon_description']) ? $params['coupon_description'] : '';
$uploaded_img       = !empty($params['uploaded_img'      ]) ? $params['uploaded_img'      ] : '';
$coupon_expire      = !empty($params['coupon_expire'     ]) ? $params['coupon_expire'     ] : $default_expire_date;
$coupon_place_id    = !empty($params['coupon_place_id'   ]) ? $params['coupon_place_id'   ] : '';

// trim
$coupon_title       = trim($coupon_title);
$coupon_description = trim($coupon_description);
$uploaded_img       = trim($uploaded_img);
$coupon_expire      = trim($coupon_expire);
$coupon_place_id    = trim($coupon_place_id);

// some browsers don't support input type date, so if user sent the date as string:
$timestamp = strtotime($coupon_expire);

// Creating new date format from that timestamp
$coupon_expire = date("Y-m-d", $timestamp);

// check if place id is owned by user
$allowed_places = array();

$query = "SELECT * FROM places WHERE userid = :userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$allowed_places[] = $row['place_id'];
}

if(!in_array($coupon_place_id, $allowed_places)) {
	$has_errors = true;
	die('Permission denied');
}

/*--------------------------------------------------
Submit routine
--------------------------------------------------*/
try {
	$conn->beginTransaction();

	// insert into places table
	$query = "INSERT INTO coupons(
		title,
		description,
		userid,
		place_id,
		expire,
		img
	)
	VALUES(
		:title,
		:description,
		:userid,
		:place_id,
		:expire,
		:img
	)";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':title', $coupon_title);
	$stmt->bindValue(':description', $coupon_description);
	$stmt->bindValue(':userid', $userid);
	$stmt->bindValue(':place_id', $coupon_place_id);
	$stmt->bindValue(':expire', $coupon_expire);
	$stmt->bindValue(':img', $uploaded_img);
	$stmt->execute();

	/*--------------------------------------------------
	Coupon img
	--------------------------------------------------*/

	// folder
	$folder_path = $pic_basepath . '/coupons/' . substr($uploaded_img, 0, 2);

	if (!is_dir($folder_path)) {
		if(!mkdir($folder_path, 0755, true)) {
			$has_errors = true;
			$result_message = 'Error creating directory';
		}

		// create empty index file in the folder
		touch($folder_path . '/index.php');
	}

	// paths and folders
	$path_tmp   = $pic_basepath . '/coupons-tmp/' . $uploaded_img;
	$path_final = $folder_path . '/' . $uploaded_img;

	if(is_file($path_tmp)) {
		if(copy($path_tmp, $path_final)) {

			/**
			* easy image resize function
			* @param  $file - file name to resize
			* @param  $string - The image data, as a string
			* @param  $width - new image width
			* @param  $height - new image height
			* @param  $proportional - keep image proportional, default is no
			* @param  $output - name of the new file (include path if needed)
			* @param  $delete_original - if true the original image will be deleted
			* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
			* @param  $quality - enter 1-100 (100 is best quality) default is 100
			* @return boolean|resource
			*/

			//smart_resize_image($path_final, null, $coupon_size[0], $coupon_size[1], false, 'file', true, false, 85);

			unlink($path_tmp);
		}
	}

	/*--------------------------------------------------
	Commit
	--------------------------------------------------*/
	$conn->commit();
	$has_errors = false;
}

catch(PDOException $e) {
	$conn->rollBack();
	$has_errors = true;
	$result_message = $e->getMessage();

}