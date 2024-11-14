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

if(!in_array($sort, array('date', 'date-desc', 'title', 'title-desc', ))) {
	$sort = 'date-desc';
}

// page url used for pagination
$page_url = "$baseurl/admin/pages?sort=$sort&page=";

// init results array
$pages_arr = array();

// count query
$query = "SELECT COUNT(*) AS c FROM pages WHERE page_status >= 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = 'page_id DESC';

	if($sort == 'date') {
		$order_by = "page_id";
	}

	if($sort == 'title') {
		$order_by = "page_title";
	}

	if($sort == 'title-desc') {
		$order_by = "page_title DESC";
	}

	$query = "SELECT page_id, page_title, page_slug, page_group, page_order FROM pages WHERE page_status >= 0 ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$page_id    = !empty($row['page_id'   ]) ? $row['page_id'   ] : '';
		$page_title = !empty($row['page_title']) ? $row['page_title'] : '';
		$page_slug  = !empty($row['page_slug' ]) ? $row['page_slug' ] : '';
		$page_group = !empty($row['page_group']) ? $row['page_group'] : '';
		$page_order = !empty($row['page_order']) ? $row['page_order'] : 0;

		// sanitize
		$page_title = e($page_title);
		$page_slug  = e($page_slug);
		$page_group = e($page_group);
		$page_order = e($page_order);

		$page_link = "$baseurl/post/$page_slug";

		$cur_lop_arr = array(
			'page_id'    => $page_id,
			'page_title' => $page_title,
			'page_link'  => $page_link,
			'page_group' => $page_group,
			'page_order' => $page_order,
		);

		$pages_arr[] = $cur_lop_arr;
	}
}
