<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// category
$cat_id = !empty($_GET['cat']) ? $_GET['cat'] : 0;

// count how many fields exist
if(empty($cat)) {
	$query = "SELECT COUNT(*) AS total_rows FROM custom_fields WHERE field_status = 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];
	}

else {
	$query = "SELECT COUNT(*) AS total_rows FROM rel_cat_custom_fields r
				LEFT JOIN custom_fields c ON r.field_id = c.field_id
				WHERE c.field_status = 1
				AND r.cat_id = :cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];
}

// get all custom fields and their values
$custom_fields = array();
if($total_rows > 0) {
	if(empty($cat_id)) {
		$query = "SELECT field_id, field_name, field_type, required, searchable
		FROM custom_fields WHERE field_status = 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}

	else {
		$query = "SELECT c.* FROM rel_cat_custom_fields r
		LEFT JOIN custom_fields c ON r.field_id = c.field_id
		WHERE field_status = 1
		AND r.cat_id = :cat_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':cat_id', $cat_id);
		$stmt->execute();
	}

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$custom_fields[] = array(
			'field_id'   => $row['field_id'],
			'field_name' => $row['field_name'],
			'field_type' => $row['field_type']
		);
	}
}
