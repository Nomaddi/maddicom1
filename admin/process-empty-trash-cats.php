<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// get all deleted categories
$deleted_cats = array();

$query = "SELECT id FROM cats WHERE cat_status = 0";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$deleted_cats[] = $row['id'];
}

// delete cats from db
$query = "DELETE FROM cats WHERE cat_status = 0";
$stmt = $conn->prepare($query);

if($stmt->execute()) {
	foreach($deleted_cats as $v) {
		// delete category images
		$this_cat_img = glob("$pic_basepath/category/cat-$v.*");
		foreach($this_cat_img as $v) {
			unlink($v);
		}
	}

	echo '1';
}
