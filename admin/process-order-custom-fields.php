<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// custom field details
$show = !empty($_POST['show']) ? $_POST['show'] : '';
$groups_order = !empty($_POST['groups_order']) ? $_POST['groups_order'] : '';
$fields_order = !empty($_POST['fields_order']) ? $_POST['fields_order'] : '';

// trim
$groups_order = trim($groups_order);
$fields_order = trim($fields_order);

if($show == 'fields') {
	// convert to array
	$fields_order = explode(',',  $fields_order);

	// update
	foreach($fields_order as $k => $v) {
		$query = "UPDATE custom_fields SET field_order = :field_order WHERE field_id = :field_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':field_order', $k);
		$stmt->bindValue(':field_id', $v);
		$stmt->execute();

		echo $v . "\n";
	}
}

if($show == 'groups') {
	// convert to array
	$groups_order = explode(',',  $groups_order);

	// update
	foreach($groups_order as $k => $v) {
		$query = "UPDATE custom_fields_groups SET group_order = :group_order WHERE group_id = :group_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':group_order', $k);
		$stmt->bindValue(':group_id', $v);
		$stmt->execute();

		echo $v . "\n";
	}
}