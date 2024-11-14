<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'custom-fields');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'create-custom-field');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

/*--------------------------------------------------
Categories
--------------------------------------------------*/
$cats_arr = array();
$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $row['name'],
		'plural_name' => $row['plural_name'],
		'parent_id'   => $row['parent_id']
	);

	$cats_arr[] = $cur_loop_arr;
}

// store total number of cats in a variable
$total_cats = count($cats_arr);

/*--------------------------------------------------
Custom fields groups
--------------------------------------------------*/
// init result array
$custom_fields_groups = array();

// get custom fields groups
$query = "SELECT * FROM custom_fields_groups WHERE group_status = 1 ORDER BY group_id";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_group_id    = !empty($row['group_id'   ]) ? $row['group_id'   ] : '';
	$this_group_name  = !empty($row['group_name' ]) ? $row['group_name' ] : '';
	$this_group_order = !empty($row['group_order']) ? $row['group_order'] : '';

	// sanitize
	$this_group_id    = e($this_group_id);
	$this_group_name  = e($this_group_name);
	$this_group_order = e($this_group_order);

	// add to array
	$custom_fields_groups[] = array(
		'group_id'    => $this_group_id,
		'group_name'  => $this_group_name,
		'group_order' => $this_group_order,
	);
}
