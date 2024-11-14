<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// custom field details
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$group_id      = !empty($params['group_id'     ]) ? $params['group_id'     ] : '';
$group_name    = !empty($params['group_name'   ]) ? $params['group_name'   ] : '';
$group_order   = !empty($params['group_order'  ]) ? $params['group_order'  ] : '';
$tr_group_name = !empty($params['tr_group_name']) ? $params['tr_group_name'] : array();

// trim
$group_id    = trim($group_id);
$group_name  = trim($group_name);
$group_order = trim($group_order);

foreach($tr_group_name as $k => $v) {
	$tr_group_name[$k] = trim($v);
}

// convert to integers
$group_id    = intval($group_id);
$group_order = intval($group_order);

/*--------------------------------------------------
Groups translations
--------------------------------------------------*/

// init result message
$result_message = '';

try {
	$conn->beginTransaction();

	// update table 'custom_fields'
	$query = "UPDATE custom_fields_groups SET
		group_name     = :group_name,
		group_order    = :group_order
		WHERE group_id = :group_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':group_id'      , $group_id);
	$stmt->bindValue(':group_name'    , $group_name);
	$stmt->bindValue(':group_order'   , $group_order);
	$stmt->execute();

	// delete previous translations for this group, prevent orphan and duplicate rows, which could cause custom fields to show duplicate values on the listing page
	$query = "DELETE FROM translation_cf_groups WHERE group_id = :group_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':group_id', $group_id);
	$stmt->execute();

	// insert new translations
	foreach($tr_group_name as $k => $v) {
		$query = "INSERT INTO translation_cf_groups (lang, group_id, group_name)
			VALUES(:lang, :group_id, :group_name)
			ON DUPLICATE KEY UPDATE group_name= :group_name2";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $k);
		$stmt->bindValue(':group_id', $group_id);
		$stmt->bindValue(':group_name', $v);
		$stmt->bindValue(':group_name2', $v);
		$stmt->execute();
	}

	$conn->commit();
	$result_message = '1';
}

catch(PDOException $e) {
	$conn->rollBack();
	$result_message = $e->getMessage();
}

// results
echo $result_message;