<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// custom field details
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$group_name  = !empty($params['group_name' ]) ? $params['group_name' ] : '';
$group_order = !empty($params['group_order']) ? $params['group_order'] : '';

// trim
$group_name  = trim($group_name);
$group_order = trim($group_order);

// convert to integers
$group_order = intval($group_order);

// update table 'custom_fields'
$query = "INSERT INTO
			custom_fields_groups(group_name, group_order)
			VALUES(:group_name, :group_order)";
$stmt = $conn->prepare($query);
$stmt->bindValue(':group_name', $group_name);
$stmt->bindValue(':group_order', $group_order);
$stmt->execute();

// get the group id
$group_id = $conn->lastInsertId();

/*--------------------------------------------------
Custom fields groups translations
--------------------------------------------------*/

/*
[tr_group_name] => Array(
		[en] => Group Name EN
		[es] => Group Name ES)
*/

$tr_group_name = !empty($params['tr_group_name']) ? $params['tr_group_name'] : array();

// process submitted values
if(!empty($cfg_languages) && is_array($cfg_languages)) {
	foreach($cfg_languages as $v) {
		$query = "INSERT INTO translation_cf_groups(lang, group_id, group_name) VALUES (:lang, :group_id, :group_name)";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $v);
		$stmt->bindValue(':group_id', $group_id);
		$stmt->bindValue(':group_name', $tr_group_name[$v]);
		$stmt->execute();
	}
}

echo '1';