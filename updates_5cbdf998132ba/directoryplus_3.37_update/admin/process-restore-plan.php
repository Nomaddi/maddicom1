<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$plan_id = !empty($_POST['plan_id']) ? $_POST['plan_id'] : 0;

// update status
if(!empty($plan_id)) {
	$query = "UPDATE plans SET plan_status = 0 WHERE plan_id = :plan_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':plan_id', $plan_id);
	$stmt->execute();
}

echo '1';
