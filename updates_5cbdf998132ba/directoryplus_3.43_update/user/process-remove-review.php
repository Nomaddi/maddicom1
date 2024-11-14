<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// reviews enabled check
if(!$cfg_enable_reviews) {
	die();
}

// review id
$review_id = $_POST['review_id'];

$query = "SELECT * FROM reviews WHERE review_id = :review_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':review_id', $review_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user owns review
if($row['user_id'] == $userid) {
	$query = "UPDATE reviews SET status = 'trashed' WHERE review_id = :review_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':review_id', $review_id);
	$stmt->execute();

	echo "Review deleted";
}
