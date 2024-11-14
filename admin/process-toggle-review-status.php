<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$review_id = intval($_POST['review_id']);
$status = $_POST['status'];

if(!empty($review_id)) {
	if($status == 'pending'){
		$query = "UPDATE reviews SET status = 'approved' WHERE review_id= :review_id";
		$status = 'on';
	}

	else {
		$query = "UPDATE reviews SET status = 'pending' WHERE review_id= :review_id";
		$status = 'off';
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':review_id', $review_id);
	$stmt->execute();

	echo $status;
}

else {
	echo "Invalid review_id";
}