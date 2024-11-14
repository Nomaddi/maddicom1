<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

/*--------------------------------------------------
session to prevent multiple form submissions
--------------------------------------------------*/
$submit_token = uniqid('', true);
$_SESSION['submit_token'] = $submit_token;

// also set cookie in case session expires
$_COOKIE['submit_token'] = $submit_token;

/*--------------------------------------------------
Plans
--------------------------------------------------*/
$plan_id = (!empty($route[2])) ? $route[2] : '';

if(empty($plan_id)) {
	trigger_error("Invalid plan selection", E_USER_ERROR);
	die();
}

/*--------------------------------------------------
All categories array
--------------------------------------------------*/

// init cat name translations array
$cat_translations = array();

// get category name translations
$query = "SELECT * FROM config WHERE type = 'cat-lang'";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id = !empty($row['property' ]) ? $row['property'] : '';
	$this_value  = !empty($row['value'    ]) ? $row['value'   ] : '';

	$this_value = explode(';', $this_value);

	if($this_value[0] == $html_lang) {
		$cat_translations[$this_cat_id] = array($this_value[1], $this_value[2]);
	}
}

// all cats array
$cats_arr = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : '';
	$this_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;

	// cat name translation
	if(isset($cat_translations[$this_cat_id])) {
		if(isset($cat_translations[$this_cat_id][1])) {
			$this_cat_name = $cat_translations[$this_cat_id][1];
		}

		if(isset($cat_translations[$this_cat_id][2])) {
			$this_plural_name = $cat_translations[$this_cat_id][2];
		}
	}

	// final values
	$cur_loop_arr = array(
		'cat_id'      => $this_cat_id,
		'cat_name'    => $this_cat_name,
		'plural_name' => $this_plural_name,
		'parent_id'   => $this_parent_id,
	);

	$cats_arr[] = $cur_loop_arr;
}

// group by parents
$cats_grouped_by_parent = group_cats_by_parent($cats_arr);

/*--------------------------------------------------
Custom fields: Global fields
--------------------------------------------------*/

// init arrays
$custom_fields = array();
$custom_fields_ids = array();

// find global fields
$query = "SELECT f.*, g.*, tr.field_name AS tr_field_name, tr.tooltip AS tr_tooltip, tr.values_list AS tr_values_list,
			trg.group_name AS tr_group_name
			FROM custom_fields f
			LEFT JOIN custom_fields_groups g ON f.field_group = g.group_id
			LEFT JOIN rel_cat_custom_fields rc ON f.field_id = rc.field_id
			LEFT JOIN translation_cf tr ON f.field_id = tr.field_id AND tr.lang = :html_lang
			LEFT JOIN translation_cf_groups trg ON g.group_id = trg.group_id AND trg.lang = :html_lang_2
			WHERE rc.rel_id IS NULL AND field_status = 1
			ORDER BY f.field_order";

$stmt = $conn->prepare($query);
$stmt->bindValue(':html_lang', $html_lang);
$stmt->bindValue(':html_lang_2', $html_lang);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_field_group = !empty($row['field_group']) ? $row['field_group'] : 1;
	$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : 0;
	$this_field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
	$this_field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : 'text';
	$this_group_name  = !empty($row['group_name' ]) ? $row['group_name' ] : '';
	$this_group_order = !empty($row['group_order']) ? $row['group_order'] : 0;
	$this_icon        = !empty($row['icon'       ]) ? $row['icon'       ] : '';
	$this_required    = !empty($row['required'   ]) ? $row['required'   ] : 0;
	$this_searchable  = !empty($row['searchable' ]) ? $row['searchable' ] : 0;
	$this_tooltip     = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
	$this_values_list = !empty($row['values_list']) ? $row['values_list'] : '';

	// required
	$this_required    = !empty($this_required) ? 'required' : '';

	// field and group translation values
	$this_tr_field_name  = !empty($row['tr_field_name' ]) ? $row['tr_field_name' ] : $this_field_name;
	$this_tr_group_name  = !empty($row['tr_group_name' ]) ? $row['tr_group_name' ] : $this_group_name;
	$this_tr_tooltip     = !empty($row['tr_tooltip'    ]) ? $row['tr_tooltip'    ] : $this_tooltip;
	$this_tr_values_list = !empty($row['tr_values_list']) ? $row['tr_values_list'] : $this_values_list;

	// escape
	$this_field_name     = e($this_field_name    );
	$this_group_name     = e($this_group_name    );
	$this_tooltip        = e($this_tooltip       );
	$this_tr_field_name  = e($this_tr_field_name );
	$this_tr_group_name  = e($this_tr_group_name );
	$this_tr_tooltip     = e($this_tr_tooltip    );
	$this_tr_values_list = e($this_tr_values_list);
	$this_values_list    = e($this_values_list   );

	// add to array
	$custom_fields[] = array(
		'field_group'    => $this_field_group,
		'field_id'       => $this_field_id,
		'field_name'     => $this_field_name,
		'field_type'     => $this_field_type,
		'field_value'    => '', // this is not used in create listing but required in get-custom-fields.php
		'group_name'     => $this_group_name,
		'group_order'    => $this_group_order,
		'icon'           => $this_icon,
		'required'       => $this_required,
		'searchable'     => $this_searchable,
		'tooltip'        => $this_tooltip,
		'tr_field_name'  => $this_tr_field_name,
		'tr_group_name'  => $this_tr_group_name,
		'tr_tooltip'     => $this_tr_tooltip,
		'tr_values_list' => $this_tr_values_list,
		'values_list'    => $this_values_list,
	);

	$custom_fields_ids[] = $this_field_id;
}

if(!empty($custom_fields_ids)) {
	$custom_fields_ids = implode(',', $custom_fields_ids);
}

else {
	$custom_fields_ids = '';
}

/*--------------------------------------------------
Custom fields groups
--------------------------------------------------*/

// init
$fields_groups = array();

// add to array of custom fields groups
foreach($custom_fields as $k => $v) {
	$fields_groups[$v['field_group']] = array(
			'group_id'    => $v['field_group'],
			'group_order' => $v['group_order'],
			'group_name'  => $v['group_name'],
		);
}

// sort by group order
$keys = array_column($fields_groups, 'group_order');
array_multisort($keys, SORT_ASC, $fields_groups);

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/create-listing';

/*--------------------------------------------------
translations
--------------------------------------------------*/
$txt_html_title = str_replace('%site_name%', $site_name, $txt_html_title);
$txt_main_title = str_replace('%site_name%', $site_name, $txt_main_title);
