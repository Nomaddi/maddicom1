<?php
require_once(__DIR__ . '/inc/config.php');

/*--------------------------------------------------
Setup
--------------------------------------------------*/
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$term = !empty($_GET['term']) ? $_GET['term'] : '';
$total_rows = 0;
$start = '';

// sanitize
if(!is_numeric($page)) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

$term = e($term);

// page url
$page_url = "$baseurl/posts?page=";

// canonical
$canonical = $baseurl . '/posts?page=' . $page;

// if is search
if(!empty($term)) {
	$page_url = "$baseurl/posts?term=$term&page=";
	$canonical = "$baseurl/posts?term=$term&page=$page";
}


/*--------------------------------------------------
Get list of posts for this page
--------------------------------------------------*/

// init
$posts_arr = array();

// count total posts
if(empty($term)) {
	$query = "SELECT COUNT(*) AS c FROM pages WHERE page_status > 0";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
}

else {
	$query = "SELECT COUNT(page_id) AS c FROM pages WHERE page_status > 0 AND MATCH(page_title, meta_desc, page_contents) AGAINST(:term IN BOOLEAN MODE)";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':term', $term);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
}

$total_rows = $row['c'];

// if there are posts
if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
	$start = $pager->getStartRow();

	if(empty($term)) {
		// using offset without covering index is slow so we use the seek method in two steps
		// seek method, get the last id from the immediate previous page
		$query = "SELECT page_id FROM pages WHERE page_status > 0 ORDER BY page_id DESC LIMIT :start, 1";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':start', $start);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$last_previous_id = !empty($row['page_id']) ? $row['page_id'] : 0;

		// seek method, use last id in WHERE clause
		$query = "SELECT * FROM pages WHERE page_status > 0 AND page_id <= :last_previous_id ORDER BY page_id DESC LIMIT :items_per_page";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':last_previous_id', $last_previous_id);
		$stmt->bindValue(':items_per_page', $items_per_page);
		$stmt->execute();
	}

	else {
		// using offset without covering index is slow so we use the seek method in two steps
		// seek method, get the last id from the immediate previous page
		$query = "SELECT page_id FROM pages WHERE page_status > 0 AND MATCH(page_title, meta_desc, page_contents) AGAINST(:term IN BOOLEAN MODE) ORDER BY page_id DESC LIMIT :start, 1";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':term', $term);
		$stmt->bindValue(':start', $start);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$last_previous_id = !empty($row['page_id']) ? $row['page_id'] : 0;

		// seek method, use last id in WHERE clause
		$query = "SELECT * FROM pages WHERE page_status > 0 AND page_id <= :last_previous_id AND MATCH(page_title, meta_desc, page_contents) AGAINST(:term IN BOOLEAN MODE) ORDER BY page_id DESC LIMIT :items_per_page";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':term', $term);
		$stmt->bindValue(':last_previous_id', $last_previous_id);
		$stmt->bindValue(':items_per_page', $items_per_page);
		$stmt->execute();
	}

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$page_id       = !empty($row['page_id'      ]) ? $row['page_id'      ] : '';
		$page_title    = !empty($row['page_title'   ]) ? $row['page_title'   ] : '';
		$page_slug     = !empty($row['page_slug'    ]) ? $row['page_slug'    ] : '';
		$meta_desc     = !empty($row['meta_desc'    ]) ? $row['meta_desc'    ] : '';
		$page_contents = !empty($row['page_contents']) ? $row['page_contents'] : '';
		$page_group    = !empty($row['page_group'   ]) ? $row['page_group'   ] : '';
		$page_order    = !empty($row['page_order'   ]) ? $row['page_order'   ] : '';
		$page_date     = !empty($row['page_date'    ]) ? $row['page_date'    ] : '';

		// sanitize
		// don't sanitize posts

		// check if thumb exists
		$page_thumb_path = $pic_basepath . '/page-thumb/page-' . $page_id;

		$arr = glob("$page_thumb_path.*");

		if(!empty($arr)) {
			$page_thumb_filename = basename($arr[0]);
			$page_thumb_filename_url = $pic_baseurl . '/page-thumb/' . $page_thumb_filename;
		}

		else {
			$page_thumb_filename_url = '';
		}

		// prepare vars
		$page_contents = limit_text($page_contents, 20) . '...';

		// page date
		$page_date = new DateTime($page_date);
		$page_date = $page_date->format($cfg_date_format);

		$page_date = str_replace('%date%', $page_date, $txt_posted_on);

		$cur_loop_arr = array(
						'page_id'       => $page_id       ,
						'page_title'    => $page_title    ,
						'page_slug'     => $page_slug     ,
						'meta_desc'     => $meta_desc     ,
						'page_contents' => $page_contents ,
						'page_group'    => $page_group    ,
						'page_order'    => $page_order    ,
						'page_thumb'    => $page_thumb_filename_url,
						'page_date'     => $page_date,
						);

		// add cur loop to places array
		$posts_arr[] = $cur_loop_arr;
	}

	// reverse
	//$posts_arr = array_reverse($posts_arr);
}

/*--------------------------------------------------
fix canonical and inexisting pages
--------------------------------------------------*/
if(isset($pager) && $page > $pager->getTotalPages()) {
	header("Location: $baseurl/posts");
}
