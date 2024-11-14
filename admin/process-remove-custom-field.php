<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// item id
$item_type = !empty($_POST['type'    ]) ? $_POST['type'    ] : '';
$field_id  = !empty($_POST['field_id']) ? $_POST['field_id'] : '';
$group_id  = !empty($_POST['group_id']) ? $_POST['group_id'] : '';

// do not delete the default group id
if($group_id == 1 && $item_type == 'group') {
	echo 'Error: Cannot delete default group';
	return;
}

// custom field
if(!empty($field_id) && $item_type == 'field') {
	$query = "UPDATE custom_fields SET field_status = 0 WHERE field_id = :field_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':field_id', $field_id);
	$stmt->execute();

	echo '1';
}

// custom fields group
if(!empty($group_id) && $item_type == 'group') {
	$query = "UPDATE custom_fields_groups SET group_status = 0 WHERE group_id = :group_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':group_id', $group_id);
	$stmt->execute();

	echo '1';
}