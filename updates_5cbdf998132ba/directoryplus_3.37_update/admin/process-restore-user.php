<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : 0;

// update status
if(!empty($user_id)) {
	$query = "UPDATE users SET status = 'approved' WHERE id = :user_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':user_id', $user_id);
	$stmt->execute();
}

echo '1';