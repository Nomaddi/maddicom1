<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

// sort order
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'name';

if(!in_array($sort, array('date', 'date-desc', 'name', 'name-desc', 'parent', 'parent-desc', 'order', 'order-desc'))) {
	$sort = 'name';
}

// page url
$page_url = "$baseurl/admin/categories-trash?sort=$sort&page=";

// init
$cats_arr = array();

// count how many cats
$query = "SELECT COUNT(*) AS c FROM cats WHERE cat_status = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = "id";

	if($sort == 'date-desc') {
		$order_by = "id DESC";
	}

	if($sort == 'name') {
		$order_by = "name";
	}

	if($sort == 'name-desc') {
		$order_by = "name DESC";
	}

	if($sort == 'parent') {
		$order_by = "parent_id";
	}

	if($sort == 'parent-desc') {
		$order_by = "parent_id DESC";
	}

	if($sort == 'order') {
		$order_by = "cat_order";
	}

	if($sort == 'order-desc') {
		$order_by = "cat_order DESC";
	}

	// query
	$query = "SELECT * FROM cats WHERE cat_status = 0 ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$this_cat_name        = !empty($row['name'       ]) ? $row['name'       ] : '';
		$this_cat_slug        = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
		$this_cat_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : '';
		$this_cat_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
		$this_cat_order       = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : 0;

		// sanitize
		$this_cat_name        = e(trim($this_cat_name));
		$this_cat_plural_name = e(trim($this_cat_plural_name));

		$cur_loop_arr = array(
			'cat_id'          => $this_cat_id,
			'cat_name'        => $this_cat_name,
			'cat_slug'        => $this_cat_slug,
			'cat_plural_name' => $this_cat_plural_name,
			'cat_parent_id'   => $this_cat_parent_id,
			'cat_order'       => $this_cat_order,
		);

		$cats_arr[] = $cur_loop_arr;
	}
}
