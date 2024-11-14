<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// first remove coupon images
$coupon_imgs = array();

$query = "SELECT * FROM coupons WHERE coupon_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$coupon_img = !empty($row['img']) ? $row['img'] : '';

	if(!empty($coupon_img)) {
		$coupon_img = $pic_basepath . '/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;

		if(file_exists($coupon_img)) {
			unlink($coupon_img);
		}
	}
}

// delete coupons from database
$query = "DELETE FROM coupons WHERE coupon_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();

echo '1';
