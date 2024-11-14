<?php
require_once(__DIR__ . '/../inc/config.php');

// check csrf token
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$listing_id = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';

	// check if record already exists
	$query = "SELECT COUNT(*) AS total_rows FROM rel_favorites WHERE place_id = :listing_id AND userid = :userid";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':listing_id', $listing_id);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// add
	if($row['total_rows'] == 0) {
		$query = "INSERT INTO rel_favorites (place_id, userid) VALUES (:listing_id, :userid)";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':listing_id', $listing_id);
		$stmt->bindValue(':userid', $userid);

		if($stmt->execute()) {
			echo 'added';
		}
	}

	// remove
	else {
		$query = "DELETE FROM rel_favorites WHERE place_id = :listing_id AND userid = :userid";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':listing_id', $listing_id);
		$stmt->bindValue(':userid', $userid);

		if($stmt->execute()) {
			echo 'removed';
		}
	}
}