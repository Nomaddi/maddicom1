<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// page details
$page_id = $_POST['page_id'];

$query = "UPDATE pages SET page_status = -1 WHERE page_id = :page_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':page_id', $page_id);
$stmt->execute();

// sitemap
if($cfg_enable_sitemaps) {
	$query = "SELECT page_slug FROM pages WHERE page_id = :page_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':page_id', $page_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$page_slug = !empty($row['page_slug']) ? $row['page_slug'] : '';

	$page_url = $baseurl . '/post/' . $page_slug;
	sitemap_remove_url($page_url);
}

echo '1';