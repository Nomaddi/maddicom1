<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// item info
$item_type = !empty($_POST['type'    ]) ? $_POST['type'    ] : '';
$field_id  = !empty($_POST['field_id']) ? $_POST['field_id'] : '';
$group_id  = !empty($_POST['group_id']) ? $_POST['group_id'] : '';

// do not delete the default group id
if($group_id == 1 && $item_type == 'group') {
	echo 'Error: Cannot delete default group';
	return;
}

// remove custom field permanently
if(!empty($field_id) && $item_type == 'field') {
	$query = "DELETE FROM custom_fields WHERE field_id = :field_id AND field_status = 0";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':field_id', $field_id);

	if($stmt->execute()) {
		echo '1';
	}

	else {
		echo 'Error: Could not remove custom field';
	}
}

// remove group permanently
if(!empty($group_id) && $item_type == 'group') {
	try {
		$conn->beginTransaction();

		// assign related fields to the default group
		$query = "UPDATE custom_fields SET field_group = 1 WHERE field_group = :group_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':group_id', $group_id);
		$stmt->execute();

		// delete from database
		$query = "DELETE FROM custom_fields_groups WHERE group_id = :group_id AND group_status = 0";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':group_id', $group_id);

		if($stmt->execute()) {
			echo '1';
		}

		else {
			echo 'Error: Could not remove custom fields group';
		}

		// commit transaction
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		echo $e->getMessage();
	}
}