<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$coupon_id = $_POST['coupon_id'];

if(!empty($coupon_id)) {
	// remove coupon image
	$query = "SELECT * FROM coupons WHERE id = :coupon_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':coupon_id', $coupon_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$coupon_img = !empty($row['img']) ? $row['img'] : '';

	if(!empty($coupon_img)) {
		$coupon_img = $pic_basepath . '/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;

		if(file_exists($coupon_img)) {
			unlink($coupon_img);
		}
	}

	$query = "DELETE FROM coupons WHERE id = :coupon_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':coupon_id', $coupon_id);
	$stmt->execute();

	echo '1';
}

else {
	echo "Empty coupon_id";
}
