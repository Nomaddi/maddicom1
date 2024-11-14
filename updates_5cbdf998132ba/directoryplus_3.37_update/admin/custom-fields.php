<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// show fields or groups
$show = !empty($_GET['show']) ? $_GET['show'] : 'fields';

if(!in_array($show, array('fields', 'groups'))) {
	die('Invalid show parameter');
}

/*--------------------------------------------------
Custom fields
--------------------------------------------------*/

// init result array
$custom_fields = array();

if($show == 'fields') {
	// filters
	$filter_cat = !empty($_GET['filter-cat']) ? $_GET['filter-cat'] : 0;
	$filter_group = !empty($_GET['filter-group']) ? $_GET['filter-group'] : 0;

	$filter_group_clause = "";

	if(!empty($filter_group)) {
		$filter_group_clause = " AND g.group_id = :group_id";
	}

	// get custom fields
	if(empty($filter_cat)) {
		$query = "SELECT c.*, g.group_name, g.group_id
		FROM custom_fields c
		LEFT JOIN custom_fields_groups g ON c.field_group = g.group_id
		WHERE field_status = 1
		$filter_group_clause
		ORDER BY field_order";
	}

	else {
		$query = "SELECT c.*, g.group_name, g.group_id FROM rel_cat_custom_fields r
		LEFT JOIN custom_fields c ON r.field_id = c.field_id
		LEFT JOIN custom_fields_groups g ON c.field_group = g.group_id
		WHERE field_status = 1
		AND r.cat_id = :cat_id
		$filter_group_clause
		ORDER BY field_order";
	}

	$stmt = $conn->prepare($query);

	if(!empty($filter_cat)) {
		$stmt->bindValue(':cat_id', $filter_cat);
	}

	if(!empty($filter_group)) {
		$stmt->bindValue(':group_id', $filter_group);
	}

	$stmt->execute();

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

// get custom fields groups
$query = "SELECT * FROM custom_fields_groups WHERE group_status = 1 ORDER BY group_order";
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