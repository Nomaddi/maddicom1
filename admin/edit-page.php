<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

$page_id = !empty($_GET['id']) ? $_GET['id'] : 0;

if(empty($page_id)) {
	throw new Exception('Page id cannot be empty');
}

$query = "SELECT * FROM pages WHERE page_id = :page_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':page_id', $page_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$page_id         = $row['page_id'];
$page_title      = $row['page_title'];
$page_slug       = $row['page_slug'];
$meta_desc       = $row['meta_desc'];
$page_contents   = $row['page_contents'];
$page_date       = $row['page_date'];
$page_status     = $row['page_status'];
$enable_comments = $row['enable_comments'];

// sanitize
$page_title    = e($page_title);
$page_slug     = e($page_slug);
$meta_desc     = e($meta_desc);
$page_contents = e($page_contents);

// format date to populate input field
$date = new DateTime($page_date);
$page_date = $date->format('Y-m-d');

// thumbnail
$thumb_path = $pic_basepath . '/page-thumb/page-' . $page_id;

// check if file exists
$arr = glob("$thumb_path.*");

if(!empty($arr)) {
	$thumb_filename = basename($arr[0]);
	$thumb_filename_url = $pic_baseurl . '/page-thumb/' . $thumb_filename;
}

else {
	$thumb_filename = '';
	$thumb_filename_url = '';
}
