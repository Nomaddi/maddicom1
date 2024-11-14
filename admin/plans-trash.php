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

// sort order (date, title)
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date-desc';

if(!in_array($sort, array('date', 'date-desc', 'name', 'name-desc', 'type', 'type-desc', 'price', 'price-desc' ))) {
	$sort = 'date-desc';
}

// page url used for pagination
$page_url = "$baseurl/admin/plans-trash?sort=$sort&page=";

// init
$plans_arr = array();

// get plans in trash
$query = "SELECT COUNT(*) AS c FROM plans WHERE plan_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = 'plan_id DESC';

	if($sort == 'date') {
		$order_by = "plan_id";
	}

	if($sort == 'date-desc') {
		$order_by = "plan_id DESC";
	}

	if($sort == 'name') {
		$order_by = "plan_name";
	}

	if($sort == 'name-desc') {
		$order_by = "plan_name DESC";
	}

	if($sort == 'type') {
		$order_by = "plan_type";
	}

	if($sort == 'type-desc') {
		$order_by = "plan_type DESC";
	}

	if($sort == 'price') {
		$order_by = "plan_price";
	}

	if($sort == 'price-desc') {
		$order_by = "plan_price DESC";
	}

	// the query
	$query = "SELECT * FROM plans WHERE plan_status = -1 ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$plan_id     = !empty($row['plan_id'    ]) ? $row['plan_id'    ] : '';
		$plan_type   = !empty($row['plan_type'  ]) ? $row['plan_type'  ] : '';
		$plan_name   = !empty($row['plan_name'  ]) ? $row['plan_name'  ] : '';
		$plan_price  = !empty($row['plan_price' ]) ? $row['plan_price' ] : '';
		$plan_status = !empty($row['plan_status']) ? $row['plan_status'] : '';

		// sanitize
		$plan_name = e($plan_name);

		$cur_arr = array(
				'plan_id'     => $plan_id,
				'plan_type'   => $plan_type,
				'plan_name'   => $plan_name,
				'plan_price'  => $plan_price,
				'plan_status' => $plan_status,
		);

		$plans_arr[] = $cur_arr;
	}
}