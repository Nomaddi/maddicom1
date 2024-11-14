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
$stmt->bindValue(':template', 'edit-custom-field');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = $row['translated'];
}

// field id
$field_id = !empty($_GET['id']) ? $_GET['id'] : 0;

if(empty($field_id)) {
	die('Field id cannot be empty');
}

// get custom field data
$query = "SELECT * FROM custom_fields WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$field_name      = !empty($row['field_name'     ]) ? $row['field_name'     ] : 'undefined';
$field_type      = !empty($row['field_type'     ]) ? $row['field_type'     ] : 'text';
$filter_display  = !empty($row['filter_display' ]) ? $row['filter_display' ] : 'text';
$values_list     = !empty($row['values_list'    ]) ? $row['values_list'    ] : '';
$value_unit      = !empty($row['value_unit'     ]) ? $row['value_unit'     ] : '';
$tooltip         = !empty($row['tooltip'        ]) ? $row['tooltip'        ] : '';
$icon            = !empty($row['icon'           ]) ? $row['icon'           ] : '';
$field_order     = !empty($row['field_order'    ]) ? $row['field_order'    ] : 0;
$field_group     = !empty($row['field_group'    ]) ? $row['field_group'    ] : 1;
$show_in_results = !empty($row['show_in_results']) ? $row['show_in_results'] : 'no';

$required   = $row['required'  ] == 1 ? 'checked' : '';
$searchable = $row['searchable'] == 1 ? 'checked' : '';

// sanitize
$field_name     = e($field_name);
$field_type     = e($field_type);
$filter_display = e($filter_display);
$values_list    = e($values_list);
$value_unit     = e($value_unit);
$tooltip        = e($tooltip);
$icon           = e($icon);

// get categories with this custom field
$checked_cats = array();

$query = "SELECT cat_id FROM rel_cat_custom_fields WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$checked_cats[] = $row['cat_id'];
}

// get all categories
$cats_arr = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $row['name'],
		'plural_name' => $row['plural_name'],
		'parent_id'   => $row['parent_id'],
	);

	$cats_arr[] = $cur_loop_arr;
}

// store total number of cats in a variable
$total_cats = count($cats_arr);

$cats_grouped_by_parent = group_cats_by_parent($cats_arr);

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

/*--------------------------------------------------
Translations
--------------------------------------------------*/
$custom_field_lang = array();

$query = "SELECT * FROM translation_cf WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_lang       = !empty($row['lang'       ]) ? $row['lang'       ] : '';
	$this_field_name = !empty($row['field_name' ]) ? $row['field_name' ] : '';
	$this_tooltip    = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
	$this_value_list = !empty($row['values_list']) ? $row['values_list'] : '';

	$custom_field_lang[$this_lang] = array(
		'field_name'  => $this_field_name,
		'tooltip'     => $this_tooltip,
		'values_list' => $this_value_list,
	);
}