<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// cat details
$cat_id = $_POST['cat_id'];

$query = "UPDATE cats SET cat_status = 0 WHERE id = :cat_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();

// sitemap
if($cfg_enable_sitemaps) {
	$query = "SELECT cat_slug FROM cats WHERE id = :cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : '';

	$cat_url = $baseurl . '/' . $route_listings . '/' . $cat_slug;
	sitemap_remove_url($cat_url);
}

echo '1';