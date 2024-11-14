<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// item
$item_type = !empty($_POST['type']) ? $_POST['type'] : '';

// define query
if($item_type == 'fields') {
	$query = "DELETE FROM custom_fields WHERE field_status = 0";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	echo '1';
}

if($item_type == 'groups') {
	try {
		$conn->beginTransaction();

		// assign related fields to the default group
		$in_str = array();
		$query = "SELECT group_id FROM custom_fields_groups WHERE group_status = 0 AND group_id <> 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if(!empty($row['group_id'])) {
				$in_str[] = $row['group_id'];
			}
		}

		// build string to be used in the IN clause
		if(!empty($in_str)) {
			$in_str = implode(',', $in_str);

			$query = "UPDATE custom_fields SET field_group = 1 WHERE field_group IN($in_str)";
			$stmt = $conn->prepare($query);
			$stmt->execute();
		}

		// delete
		$query = "DELETE FROM custom_fields_groups WHERE group_status = 0 AND group_id <> 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();

		// commit transaction
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		echo $e->getMessage();
	}

	echo '1';
}
