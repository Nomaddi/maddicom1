<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// coupons enabled check
if(!$cfg_enable_coupons) {
	die("Invalid request");
}

$coupon_id = $_POST['coupon_id'];

$query = "SELECT * FROM coupons WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $coupon_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// check if this user really owns the coupon
if($row['userid'] == $userid) {
	// delete coupon img
	$coupon_img = !empty($row['img']) ? $row['img'] : '';

	if(!empty($coupon_img)) {
		$coupon_img = $pic_basepath . '/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;
		unlink($coupon_img);
	}

	// delete coupon from db
	$query = "UPDATE coupons SET coupon_status = -2 WHERE id = :id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':id', $coupon_id);
	$stmt->execute();
}