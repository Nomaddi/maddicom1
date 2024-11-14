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

// show fields or groups
$show = !empty($_GET['show']) ? $_GET['show'] : 'fields';

if(!in_array($show, array('fields', 'groups'))) {
	die('Invalid show parameter');
}

// sort order
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date-desc';

if($show == 'fields') {
	if(!in_array($sort, array('date', 'date-desc', 'name', 'name-desc', 'type', 'type-desc', 'group', 'group-desc'))) {
		$sort = 'date-desc';
	}
}

if($show == 'groups') {
	if(!in_array($sort, array('date', 'date-desc', 'name', 'name-desc'))) {
		$sort = 'date-desc';
	}
}

/*--------------------------------------------------
Custom fields
--------------------------------------------------*/
// init result array
$custom_fields = array();

if($show == 'fields') {
	// category
	$cat_id = !empty($_GET['cat']) ? $_GET['cat'] : 0;

	// define sort
	$order_by = 'field_id DESC';

	if($sort == 'date') {
		$order_by = "field_id";
	}

	if($sort == 'date-desc') {
		$order_by = "field_id DESC";
	}

	if($sort == 'name') {
		$order_by = "field_name";
	}

	if($sort == 'name-desc') {
		$order_by = "field_name DESC";
	}

	if($sort == 'type') {
		$order_by = "field_type";
	}

	if($sort == 'type-desc') {
		$order_by = "field_type DESC";
	}

	if($sort == 'group') {
		$order_by = "field_group";
	}

	if($sort == 'group-desc') {
		$order_by = "field_group DESC";
	}

	// get custom fields
	if(empty($cat_id)) {
		$query = "SELECT * FROM custom_fields c
		LEFT JOIN custom_fields_groups g ON c.field_group = g.group_id
		WHERE field_status = 0
		ORDER BY $order_by";
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}

	else {
		$query = "SELECT c.*, g.group_name FROM rel_cat_custom_fields r
		LEFT JOIN custom_fields c ON r.field_id = c.field_id
		LEFT JOIN custom_fields_groups g ON c.field_group = g.group_id
		WHERE field_status = 0
		AND r.cat_id = :cat_id
		ORDER BY $order_by";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':cat_id', $cat_id);
		$stmt->execute();
	}

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : '';
		$this_field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
		$this_field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : '';
		$this_field_group = !empty($row['field_group']) ? $row['field_group'] : '';
		$this_group_name  = !empty($row['group_name' ]) ? $row['group_name' ] : '';

		// sanitize
		$this_field_id    = e($this_field_id   );
		$this_field_name  = e($this_field_name );
		$this_field_type  = e($this_field_type );
		$this_field_group = e($this_field_group);
		$this_group_name  = e($this_group_name );

		// add to array
		$custom_fields[] = array(
			'field_id'    => $this_field_id,
			'field_name'  => $this_field_name,
			'field_type'  => $this_field_type,
			'field_group' => $this_field_group,
			'group_name'  => $this_group_name,
		);
	}
}

$total_rows_fields = count($custom_fields);

/*--------------------------------------------------
Custom fields groups
--------------------------------------------------*/

// init result array
$custom_fields_groups = array();

// define sort
$order_by = 'group_id DESC';

if($sort == 'date') {
	$order_by = "group_id";
}

if($sort == 'date-desc') {
	$order_by = "group_id DESC";
}

if($sort == 'name') {
	$order_by = "group_name";
}

if($sort == 'name-desc') {
	$order_by = "group_name DESC";
}

// get custom fields groups
$query = "SELECT * FROM custom_fields_groups WHERE group_status = 0 ORDER BY $order_by";
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

$total_rows_groups = count($custom_fields_groups);