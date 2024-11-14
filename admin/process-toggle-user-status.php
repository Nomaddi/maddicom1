<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$user_id = intval($_POST['user_id']);
$status = $_POST['status'];

if(!empty($user_id)) {
	if($status == 'pending'){
		$query  = "UPDATE users SET status = 'approved' WHERE id= :user_id";
		$status = 'approved';
	}

	else {
		$query  = "UPDATE users SET status = 'pending' WHERE id= :user_id";
		$status = 'pending';
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':user_id', $user_id);
	$stmt->execute();

	echo $status;
}

else {
	echo "Invalid user_id";
}