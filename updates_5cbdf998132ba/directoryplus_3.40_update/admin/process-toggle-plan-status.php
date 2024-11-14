<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$plan_id     = intval($_POST['plan_id']);
$plan_status = $_POST['plan_status'];

if(!in_array($plan_status, array('on', 'off'))) {
	echo "Invalid plan status $plan_status";
	die();
}

if(!empty($plan_id)) {
	if($plan_status == 'off'){
		$query  = "UPDATE plans SET plan_status = 1 WHERE plan_id= :plan_id";
		$status = 'on';
	}

	else {
		$query  = "UPDATE plans SET plan_status = 0 WHERE plan_id= :plan_id";
		$status = 'off';
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':plan_id', $plan_id);
	$stmt->execute();

	echo $status;
}

else {
	echo "Invalid plan_id";
}