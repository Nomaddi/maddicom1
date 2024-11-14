<?php
declare(strict_types=1);

include('../inc/config.php');

function build_rss(int $num_items = 100): bool {
	global $conn;
	global $baseurl;
	global $site_name;
	global $cfg_permalink_struct;
	global $route_listing;
	global $route_listings;

	// unlink existing rss
	if(file_exists(__DIR__ . '/rss.xml')) {
		unlink(__DIR__ . '/rss.xml');
		clearstatcache(TRUE, __DIR__ . '/rss.xml');
	}

	// start xml string
	$xml_str = '<?xml version="1.0" encoding="UTF-8" ?>';
	$xml_str .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';

	// start channel
	$xml_str .= '<channel>';
	$xml_str .= '<title>';
	$xml_str .= $site_name;
	$xml_str .= '</title>';
	$xml_str .= '<image>';
		$xml_str .= '<url>';
		$xml_str .= $baseurl . '/assets/logo.png';
		$xml_str .= '</url>';
		$xml_str .= '<title>';
		$xml_str .= $site_name;
		$xml_str .= '</title>';
		$xml_str .= '<link>';
		$xml_str .= $baseurl;
		$xml_str .= '</link>';
	$xml_str .= '</image>';
	$xml_str .= '<link>';
	$xml_str .= $baseurl;
	$xml_str .= '</link>';
	$xml_str .= '<description>';
	$xml_str .= $site_name;
	$xml_str .= '</description>';
	$xml_str .= '<atom:link href="' . $baseurl . '/sitemaps/rss.xml" rel="self" type="application/rss+xml" />';

	// add listings
	$query = "SELECT
			p.place_id, p.slug AS place_slug, p.place_name, p.description,
			c.slug, c.state,
			s.state_name, s.slug AS state_slug,
			rel.cat_id,
			cats.cat_slug
		FROM places p
			LEFT JOIN cities c ON p.city_id = c.city_id
			LEFT JOIN states s ON c.state_id = s.state_id
			LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id AND rel.is_main = 1
			LEFT JOIN cats ON rel.cat_id = cats.id
		WHERE p.status = 'approved' AND paid = 1
		GROUP BY p.place_id
		ORDER BY p.place_id
		LIMIT $num_items";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$cat_id      = !empty($row['cat_id'     ]) ? $row['cat_id'     ] : '';
		$cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
		$city_slug   = !empty($row['slug'       ]) ? $row['slug'       ] : '';
		$place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$place_name  = !empty($row['place_name' ]) ? $row['place_name' ] : '';
		$place_slug  = !empty($row['place_slug' ]) ? $row['place_slug' ] : '';
		$state_slug  = !empty($row['state_slug' ]) ? $row['state_slug' ] : '';
		$description = !empty($row['description']) ? $row['description'] : '';

		// sanitize
		$place_name  = e($place_name);
		$place_slug  = e($place_slug);
		$description = e($description);

		// link to each place
		$listing_link = get_listing_link($place_id, $place_slug, $cat_id, $cat_slug, '', $city_slug, $state_slug, $cfg_permalink_struct);


		// add to xml string
		$xml_str .= '<item>';
		$xml_str .= '<guid>';
		$xml_str .= $listing_link;
		$xml_str .= '</guid>';
		$xml_str .= '<title>';
		$xml_str .= $place_name;
		$xml_str .= '</title>';
		$xml_str .= '<link>';
		$xml_str .= $listing_link;
		$xml_str .= '</link>';
		$xml_str .= '<description>';
		$xml_str .= $description;
		$xml_str .= '</description>';
		$xml_str .= '</item>';
	}

	// close channel
	$xml_str .= '</channel>';

	// close rss
	$xml_str .= '</rss>';

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml_str);

	if($dom->save(__DIR__ . '/rss.xml')) {
		return true;
	}

	return false;
}

if(build_rss()) {
	echo 'RSS generated successfully';
} else {
	echo 'Error generating RSS';
}