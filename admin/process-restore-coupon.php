<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// coupon details
$coupon_id = !empty($_POST['coupon_id']) ? $_POST['coupon_id'] : 0;

// update status
if(!empty($coupon_id)) {
	$query = "UPDATE coupons SET coupon_status = 1 WHERE id = :coupon_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':coupon_id', $coupon_id);
	$stmt->execute();

	echo '1';
}

else {
	echo "Empty coupon_id";
}