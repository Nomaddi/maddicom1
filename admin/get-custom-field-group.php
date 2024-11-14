<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// file requested by templates/admin-templates/tpl-custom-fields.php

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// group details
$group_id = !empty($_POST['group_id']) ? $_POST['group_id'] : '';

// init response array
$response = array();

// get group data
if(!empty($group_id)) {
	$query = "SELECT * FROM custom_fields_groups WHERE group_id = :group_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':group_id', $group_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$group_name = !empty($row['group_name']) ? $row['group_name'] : '';
	$group_order = !empty($row['group_order']) ? $row['group_order'] : '0';

	// sanitize
	$group_name = e($group_name);

	// integers
	$group_order = intval($group_order);

	/*--------------------------------------------------
	Translations
	--------------------------------------------------*/
	$group_lang = array();

	$query = "SELECT * FROM translation_cf_groups WHERE group_id = :group_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':group_id', $group_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_lang       = !empty($row['lang'       ]) ? $row['lang'       ] : '';
		$this_group_name = !empty($row['group_name' ]) ? $row['group_name' ] : '';

		$group_lang[$this_lang] = $this_group_name;
	}

	$response = array(
		'group_id'    => $group_id,
		'group_name'  => $group_name,
		'group_order' => $group_order,
		'group_lang' => $group_lang,
	);
}

echo json_encode($response);