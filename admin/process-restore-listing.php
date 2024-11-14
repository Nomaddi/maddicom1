<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$place_id = $_POST['place_id'];

// update status
if(!empty($place_id)) {
	$query = "UPDATE places SET status = 'approved' WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	/*--------------------------------------------------
	Add back to sitemap
	--------------------------------------------------*/
	if($cfg_enable_sitemaps) {
		// add back to sitemap only if status = approved and paid = 1
		$query = "SELECT * FROM places WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$status     = $row['status'];
		$paid       = $row['paid'];
		$place_slug = $row['slug'];
		$city_id    = $row['city_id'];
		$state_id   = $row['state_id'];

		if($status == 'approved' && $paid == 1) {
			$listing_link = get_listing_link($place_id, $place_slug, '', '', $city_id, '', '', $cfg_permalink_struct);
			sitemap_add_url($listing_link);
		}
	}
}

echo '1';