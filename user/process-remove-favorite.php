<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// post data
$place_id = $_POST['place_id'];

// check listing
$stmt = $conn->prepare('SELECT * FROM rel_favorites WHERE place_id = :place_id AND userid = :userid');
$stmt->bindValue(':place_id', $place_id);
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user is owner of this listing
if($row['userid'] == $userid) {
	// delete photos first, before cascading foreign key
	$query = "DELETE FROM rel_favorites WHERE place_id = :place_id AND userid = :userid";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();

	echo "success";
}

else {
	echo "Problem removing favorite";
}