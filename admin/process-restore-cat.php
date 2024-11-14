<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$cat_id = !empty($_POST['cat_id']) ? $_POST['cat_id'] : 0;

// update status
if(!empty($cat_id)) {
	$query = "UPDATE cats SET cat_status = 1 WHERE id = :cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);

	if($stmt->execute()) {
		// sitemap
		if($cfg_enable_sitemaps) {
			// rebuild sitemap
			sitemap_build_sitemap($cfg_permalink_struct);
		}
	}

	echo '1';
}

else {
	echo "Empty category id";
}
