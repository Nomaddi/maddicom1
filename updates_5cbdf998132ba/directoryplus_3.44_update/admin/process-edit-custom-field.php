<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// parse posted vars
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$categories      = !empty($params['cats'           ]) ? $params['cats'           ] : array();
$field_group     = !empty($params['field_group'    ]) ? $params['field_group'    ] : 1;
$field_id        = !empty($params['field_id'       ]) ? $params['field_id'       ] : '';
$field_name      = !empty($params['field_name'     ]) ? $params['field_name'     ] : '';
$field_order     = !empty($params['field_order'    ]) ? $params['field_order'    ] : 0;
$field_type      = !empty($params['field_type'     ]) ? $params['field_type'     ] : '';
$filter_display  = !empty($params['filter_display' ]) ? $params['filter_display' ] : '';
$icon            = !empty($params['icon'           ]) ? $params['icon'           ] : '';
$required        = !empty($params['required'       ]) ? $params['required'       ] : 0;
$searchable      = !empty($params['searchable'     ]) ? $params['searchable'     ] : 0;
$show_in_results = !empty($params['show_in_results']) ? $params['show_in_results'] : 'no';
$tooltip         = !empty($params['tooltip'        ]) ? $params['tooltip'        ] : '';
$value_unit      = !empty($params['value_unit'     ]) ? $params['value_unit'     ] : '';
$values_list     = !empty($params['values_list'    ]) ? $params['values_list'    ] : '';

// trim
$field_name      = trim($field_name);
$field_type      = trim($field_type);
$filter_display  = trim($filter_display);
$icon            = trim($icon);
$show_in_results = trim($show_in_results);
$tooltip         = trim($tooltip);
$value_unit      = trim($value_unit);
$values_list     = trim($values_list);

// convert to integers
$field_group = intval($field_group);
$field_id    = intval($field_id);
$field_order = intval($field_order);
$required    = intval($required);
$searchable  = intval($searchable);

// validate custom field
if(empty($field_name)) {
	die('Field name cannot be empty');
}

// validate field id
if(empty($field_id)) {
	die('Field id must be integer');
}

// allowed field_type values
$allowed_types = array('radio', 'checkbox', 'select', 'text', 'multiline', 'url');

if(!in_array($field_type, $allowed_types)) {
	die('Invalid field type');
}

// allowed filter_display values
$allowed_filter_displays = array('radio', 'checkbox', 'select', 'text', 'range_text', 'range_select', 'range_number', 'range_decimal');

if(!in_array($filter_display, $allowed_filter_displays)) {
	die('Invalid filter display');
}

// allowed show_in_results values
$allowed_show_in_results = array('no', 'name', 'icon', 'name-icon');

if(!in_array($show_in_results, $allowed_show_in_results)) {
	echo "Invalid show_in_results";
	return;
}

// field types that ignore values_list
$ignore_values_list = array('text', 'multiline', 'url', 'number', 'decimal');

if(in_array($field_type, $ignore_values_list)) {
	$values_list = '';
}

// count total cats
$query = "SELECT COUNT(*) AS total_cats FROM cats WHERE cat_status = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_cats = $row['total_cats'];

// count orphan cats with status = 1
$query = "SELECT COUNT(*) AS total_orphans FROM (SELECT * FROM cats WHERE parent_id <> 0) AS c1 LEFT JOIN cats AS c2 ON c1.parent_id = c2.id WHERE c2.id IS NULL AND c1.cat_status=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_orphans = $row['total_orphans'];

// update total cats
$total_cats = $total_cats - $total_orphans;

// check if this field is set to show on all cats
$is_global_field = 0;

if($total_cats == count($categories)) {
	$is_global_field = 1;
}

try {
	$conn->beginTransaction();

	// update table 'custom_fields'
	$query = "UPDATE custom_fields SET
		field_name      = :field_name,
		field_type      = :field_type,
		filter_display  = :filter_display,
		values_list     = :values_list,
		value_unit      = :value_unit,
		tooltip         = :tooltip,
		icon            = :icon,
		required        = :required,
		searchable      = :searchable,
		show_in_results = :show_in_results,
		field_order     = :field_order,
		field_group     = :field_group
		WHERE field_id  = :field_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':field_id'       , $field_id);
	$stmt->bindValue(':field_name'     , $field_name);
	$stmt->bindValue(':field_type'     , $field_type);
	$stmt->bindValue(':filter_display' , $filter_display);
	$stmt->bindValue(':values_list'    , $values_list);
	$stmt->bindValue(':value_unit'     , $value_unit);
	$stmt->bindValue(':tooltip'        , $tooltip);
	$stmt->bindValue(':icon'           , $icon);
	$stmt->bindValue(':required'       , $required);
	$stmt->bindValue(':searchable'     , $searchable);
	$stmt->bindValue(':show_in_results', $show_in_results);
	$stmt->bindValue(':field_order'    , $field_order);
	$stmt->bindValue(':field_group'    , $field_group);
	$stmt->execute();

	// update table 'rel_cat_custom_fields'
	// first delete all
	$query = "DELETE FROM rel_cat_custom_fields WHERE field_id = :field_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':field_id', $field_id);
	$stmt->execute();

	if(!empty($categories)) {
		// now reinsert
		// only if it's not global field
		if(!$is_global_field) {
			$query = "INSERT INTO rel_cat_custom_fields(cat_id, field_id) VALUES";

			foreach($categories as $k => $v) {
				$v = intval($v);

				if($k == 0) {
					$query .= "( $v, $field_id)";
				}

				else {
					$query .= ",( $v, $field_id)";
				}
			}

			$stmt = $conn->prepare($query);
			$stmt->execute();
		}
	}

	$conn->commit();

	echo '1';
}

catch(PDOException $e) {
	$conn->rollBack();
	echo $e->getMessage();
}

/*--------------------------------------------------
Custom fields translations
--------------------------------------------------*/

// delete previous values
$query = "DELETE FROM translation_cf WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();

/*
[custom_field_lang] => Array(
		[en] => Fuel Type
		[es] => Combustível)

[values_list_lang] => Array(
		[en] => Gasoline;Diesel;Electric;Hybrid;Other
		[es] => gasolina;Diesel;Elétrico;Híbrido;Outro)
*/

$custom_field_lang = !empty($params['custom_field_lang']) ? $params['custom_field_lang'] : array();
$tooltip_lang      = !empty($params['tooltip_lang'     ]) ? $params['tooltip_lang'     ] : array();
$values_list_lang  = !empty($params['values_list_lang' ]) ? $params['values_list_lang' ] : array();

// process submitted values
if(!empty($cfg_languages) && is_array($cfg_languages)) {
	foreach($cfg_languages as $v) {
		$query = "INSERT INTO translation_cf(lang, field_id, field_name, tooltip, values_list) VALUES (:lang, :field_id, :field_name, :tooltip, :values_list)";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $v);
		$stmt->bindValue(':field_id', $field_id);
		$stmt->bindValue(':field_name', $custom_field_lang[$v]);
		$stmt->bindValue(':tooltip', $tooltip_lang[$v]);
		$stmt->bindValue(':values_list', $values_list_lang[$v]);
		$stmt->execute();
	}
}
