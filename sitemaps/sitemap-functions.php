<?php
//include('../inc/config.php');

function sitemap_add_to_index($sitemap_file) {
	global $baseurl;

	// first remove from index
	sitemap_remove_from_index($sitemap_file);

	// init vars
	$lastmod_date = date('c',time());
	$sitemap_url = $baseurl . '/sitemaps/' . $sitemap_file;

	// init DOMDocument
	$dom = new DomDocument('1.0', 'UTF-8');

	// format output
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;

	// load file
	$dom->load(__DIR__ . '/sitemap-index.xml');

	// urlset
	$sitemapindex = $dom->getElementsByTagName('sitemapindex')[0];

	// create url element
	$sitemap = $dom->createElement('sitemap');
	$sitemapindex->appendChild($sitemap);

	// create loc element
	$loc = $dom->createElement('loc');
	$sitemap->appendChild($loc);
	$loc_text = $dom->createTextNode($sitemap_url);
	$loc->appendChild($loc_text);

	$dom->save(__DIR__ . '/sitemap-index.xml');
}

function sitemap_remove_from_index($sitemap_file) {
	global $baseurl;

	// init vars
	$lastmod_date = date('c',time());
	$sitemap_url = $baseurl . '/sitemaps/' . $sitemap_file;

	// init DOMDocument
	$dom = new DomDocument('1.0', 'UTF-8');

	// format (https://stackoverflow.com/questions/746238/indentation-with-domdocument-in-php)
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;

	// load file
	$dom->load(__DIR__ . '/sitemap-index.xml');

	// init DOMXpath
	$xpath = new DOMXpath($dom);

	// register namespace with a shortcut
	$xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

	// xpath query
	$elements = $xpath->query('/sm:sitemapindex/sm:sitemap[sm:loc = "' . $sitemap_url . '"]');

	foreach($elements as $v){
		// This is a hint from the manual comments
		$v->parentNode->removeChild($v);
	}

	$dom->save(__DIR__ . '/sitemap-index.xml');
}

function sitemap_add_url($loc_url, $check = true) {
	global $baseurl;
	global $route_listing;
	global $route_listings;

	// init vars
	$lastmod_date = date('c',time());

	// get all sitemaps
	$sitemap_files = glob(__DIR__ . "/sitemap0*.xml");

	// loop through all sitemap files, remove the url from all and add to one only
	$added = false;

	foreach($sitemap_files as $sitemap_file) {
		// init DOMDocument
		$dom = new DomDocument('1.0', 'UTF-8');

		// format (https://stackoverflow.com/questions/746238/indentation-with-domdocument-in-php)
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		// load file
		$dom->load($sitemap_file);

		// first remove if exists
		if($check) {
			// init DOMXpath
			$xpath = new DOMXpath($dom);

			// register namespace with a shortcut
			$xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

			// xpath to find the node containing $loc_url
			$elements = $xpath->query('/sm:urlset/sm:url[sm:loc = "' . $loc_url . '"]');

			// remove
			foreach($elements as $v){
				// This is a hint from the manual comments
				$v->parentNode->removeChild($v);
			}
		}

		// add url
		if(!$added) {
			if($dom->getElementsByTagName('url')->length < 50000) {
				// urlset
				$urlset = $dom->getElementsByTagName('urlset')[0];

				// create url element
				$url = $dom->createElement('url');
				$urlset->appendChild($url);

				// create loc element
				$loc = $dom->createElement('loc');
				$url->appendChild($loc);
				$loc_text = $dom->createTextNode($loc_url);
				$loc->appendChild($loc_text);

				// create lastmod element
				$lastmod = $dom->createElement('lastmod');
				$url->appendChild($lastmod);
				$lastmod_text = $dom->createTextNode($lastmod_date);
				$lastmod->appendChild($lastmod_text);

				$added = true;
			}
		}

		$dom->save($sitemap_file);
	}

	// if none of the existing sitemap files have space, then create a new one and add it there
	if(!$added) {
		$new_file = '';

		for($i = 1; $i < 50000; $i++) {
			$j = str_pad($i, 5, '0', STR_PAD_LEFT);

			if(!in_array(__DIR__ . '/sitemap' . $j . '.xml', $sitemap_files)) {
				$new_file = 'sitemap' . $j . '.xml';
				break;
			}
		}

		// create from a copy of sitemap-sample.xml
		if(!copy(__DIR__ . '/sitemap-sample.xml', __DIR__ . '/' . $new_file)) {
			die('failed to copy sitemap-sample.xml');
		}

		// init DOMDocument
		$dom = new DomDocument('1.0', 'UTF-8');

		// format (https://stackoverflow.com/questions/746238/indentation-with-domdocument-in-php)
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		// load file
		$dom->load(__DIR__ . '/' . $new_file);

		// urlset
		$urlset = $dom->getElementsByTagName('urlset')[0];

		// create url element
		$url = $dom->createElement('url');
		$urlset->appendChild($url);

		// create loc element
		$loc = $dom->createElement('loc');
		$url->appendChild($loc);
		$loc_text = $dom->createTextNode($loc_url);
		$loc->appendChild($loc_text);

		// create lastmod element
		$lastmod = $dom->createElement('lastmod');
		$url->appendChild($lastmod);
		$lastmod_text = $dom->createTextNode($lastmod_date);
		$lastmod->appendChild($lastmod_text);

		$dom->save(__DIR__ . '/' . $new_file);

		// since this is a new file, add it to the sitemap index
		sitemap_add_to_index($new_file);

		$added = true;
	}
}

function sitemap_remove_url($loc_url) {
	global $baseurl;
	global $route_listing;
	global $route_listings;

	// get all sitemaps
	$sitemap_files = glob(__DIR__ . "/sitemap0*.xml");

	foreach($sitemap_files as $sitemap_file) {
		// init DOMDocument
		$dom = new DOMDocument();

		// format (https://stackoverflow.com/questions/746238/indentation-with-domdocument-in-php)
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		// load file
		$dom->load($sitemap_file);

		// init DOMXpath
		$xpath = new DOMXpath($dom);

		// register namespace with a shortcut
		$xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

		// xpath query
		$elements = $xpath->query('/sm:urlset/sm:url[sm:loc = "' . $loc_url . '"]');

		foreach($elements as $v){
			// This is a hint from the manual comments
			$v->parentNode->removeChild($v);
		}

		$result = $dom->saveXML();
		$dom->save($sitemap_file);
	}
}

function sitemap_update_lastmod($loc_url) {
	global $baseurl;

	// get all sitemaps
	$sitemap_files = glob(__DIR__ . "/sitemap0*.xml");

	foreach($sitemap_files as $sitemap_file) {
		// init DOMDocument
		$dom = new DOMDocument();

		// format (https://stackoverflow.com/questions/746238/indentation-with-domdocument-in-php)
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		// load file
		try {
			$dom->load($sitemap_file);
		}

		catch(Exception $e) {
			echo $e->getMessage();
		}

		// init DOMXpath
		$xpath = new DOMXpath($dom);

		// register namespace with a shortcut
		$xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

		// xpath query
		$elements = $xpath->query('/sm:urlset/sm:url[sm:loc = "' . $loc_url . '"]');

		// remove old lastmod
		if(!empty($elements[0])) {
			$el = $elements[0]->getElementsByTagName('lastmod')->item(0);
			$elements[0]->removeChild($el);

			// create lastmod element
			$lastmod = $dom->createElement('lastmod');
			$elements[0]->appendChild($lastmod);
			$lastmod_text = $dom->createTextNode(date('c',time()));
			$lastmod->appendChild($lastmod_text);

			//$result = $dom->saveXML();
			$dom->save($sitemap_file);
		}
	}
}

function sitemap_build_sitemap() {
	global $conn;
	global $baseurl;
	global $route_listing;
	global $route_listings;
	global $cfg_permalink_struct;

	// date to be used in lastmod node
	$lastmod_date = date('c',time());

	// counter
	$counter = 0;

	// unlink sitemap index file (sitemap-index.xml)
	if(file_exists(__DIR__ . '/sitemap-index.xml')) {
		unlink(__DIR__ . '/sitemap-index.xml');
		clearstatcache(TRUE, __DIR__ . '/sitemap-index.xml');
	}

	// unlink sitemap files (sitemap00001.xml, sitemap00002.xml, etc)
	$sitemaps = glob(__DIR__ . "/sitemap0*.xml");

	foreach($sitemaps as $v) {
		unlink($v);
		clearstatcache(TRUE, $v);
	}

	// create sitemap index file (sitemap-index.xml)
	if(!copy(__DIR__ . '/sitemap-index-sample.xml', __DIR__ . '/sitemap-index.xml')) {
		die('failed to copy sitemap-index-sample.xml');
	}

	// current sitemap file
	$sitemap_file = 'sitemap00001.xml';

	// add sitemap file to the sitemap index
	sitemap_add_to_index($sitemap_file);

	/*--------------------------------------------------
	Create xml string
	--------------------------------------------------*/
	$xml_str = '<?xml version="1.0" encoding="UTF-8"?>';
	$xml_str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	// add baseurl
	$xml_str .= '<url>';
	$xml_str .= '<loc>';
	$xml_str .= $baseurl;
	$xml_str .= '</loc>';
	$xml_str .= '<lastmod>';
	$xml_str .= $lastmod_date;
	$xml_str .= '</lastmod>';
	$xml_str .= '</url>';

	$counter++;

	// add categories page
	$xml_str .= '<url>';
	$xml_str .= '<loc>';
	$xml_str .= $baseurl . '/categories/';
	$xml_str .= '</loc>';
	$xml_str .= '<lastmod>';
	$xml_str .= $lastmod_date;
	$xml_str .= '</lastmod>';
	$xml_str .= '</url>';

	$counter++;

	// add categories
	$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY parent_id, cat_order, name";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if(!empty($row['cat_slug'])) {
			$cat_link = $baseurl . '/'. $route_listings . '/' . $row['cat_slug'];

			// add to xml string
			$xml_str .= '<url>';
			$xml_str .= '<loc>';
			$xml_str .= $cat_link;
			$xml_str .= '</loc>';
			$xml_str .= '<lastmod>';
			$xml_str .= $lastmod_date;
			$xml_str .= '</lastmod>';
			$xml_str .= '</url>';

			$counter++;
		}
	}

	// add pages
	$query = "SELECT page_id, page_slug FROM pages WHERE page_status >= 0 ORDER BY page_id DESC";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$page_slug  = $row['page_slug'];

		// sanitize
		$page_slug  = e($page_slug);

		// build link
		$page_link = "$baseurl/post/$page_slug";

		// add to xml string
		if($counter < 50000) {
			$xml_str .= '<url>';
			$xml_str .= '<loc>';
			$xml_str .= $page_link;
			$xml_str .= '</loc>';
			$xml_str .= '<lastmod>';
			$xml_str .= $lastmod_date;
			$xml_str .= '</lastmod>';
			$xml_str .= '</url>';
		}

		// else current sitemap file reached 50000 limit, save file and create another one
		else {
			// close urlset
			$xml_str .= '</urlset>';

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml_str);

			// save current sitemap
			$dom->save(__DIR__ . '/' . $sitemap_file);

			// reset counter
			$counter = 0;

			// recreate sitemaps array
			$sitemaps = glob(__DIR__ . "/sitemap0*.xml");

			// start new sitemap
			$sitemap_file = '';

			for($i = 1; $i < 50000; $i++) {
				$j = str_pad($i, 5, '0', STR_PAD_LEFT);

				if(!in_array(__DIR__ . '/sitemap' . $j . '.xml', $sitemaps)) {
					$sitemap_file = 'sitemap' . $j . '.xml';
					break;
				}
			}

			// add sitemap file to the sitemap index
			sitemap_add_to_index($sitemap_file);

			$xml_str = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml_str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			// add to xml string
			$xml_str .= '<url>';
			$xml_str .= '<loc>';
			$xml_str .= $page_link;
			$xml_str .= '</loc>';
			$xml_str .= '<lastmod>';
			$xml_str .= $lastmod_date;
			$xml_str .= '</lastmod>';
			$xml_str .= '</url>';
		}

		// increment counter
		$counter++;
	}

	// add listings
	$query = "SELECT
			p.place_id, p.slug AS place_slug,
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
		ORDER BY p.place_id";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$cat_id     = !empty($row['cat_id'    ]) ? $row['cat_id'    ] : '';
		$cat_slug   = !empty($row['cat_slug'  ]) ? $row['cat_slug'  ] : '';
		$city_slug  = !empty($row['slug'      ]) ? $row['slug'      ] : '';
		$place_id   = !empty($row['place_id'  ]) ? $row['place_id'  ] : '';
		$place_slug = !empty($row['place_slug']) ? $row['place_slug'] : '';
		$state_slug = !empty($row['state_slug']) ? $row['state_slug'] : '';

		// sanitize
		$place_slug = e($place_slug);

		// link to each place
		$listing_link = get_listing_link($place_id, $place_slug, $cat_id, $cat_slug, '', $city_slug, $state_slug, $cfg_permalink_struct);

		if($counter < 50000) {
			// add to xml string
			$xml_str .= '<url>';
			$xml_str .= '<loc>';
			$xml_str .= $listing_link;
			$xml_str .= '</loc>';
			$xml_str .= '<lastmod>';
			$xml_str .= $lastmod_date;
			$xml_str .= '</lastmod>';
			$xml_str .= '</url>';
		}

		// else current sitemap file reached 50000 limit, save file and create another one
		else {
			// close urlset
			$xml_str .= '</urlset>';

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml_str);

			// save current sitemap
			$dom->save(__DIR__ . '/' . $sitemap_file);

			// reset counter
			$counter = 0;

			// recreate sitemaps array
			$sitemaps = glob(__DIR__ . "/sitemap0*.xml");

			// start new sitemap
			$sitemap_file = '';

			for($i = 1; $i < 50000; $i++) {
				$j = str_pad($i, 5, '0', STR_PAD_LEFT);

				if(!in_array(__DIR__ . '/sitemap' . $j . '.xml', $sitemaps)) {
					$sitemap_file = 'sitemap' . $j . '.xml';
					break;
				}
			}

			// add sitemap file to the sitemap index
			sitemap_add_to_index($sitemap_file);

			$xml_str = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml_str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			// add to xml string
			$xml_str .= '<url>';
			$xml_str .= '<loc>';
			$xml_str .= $listing_link;
			$xml_str .= '</loc>';
			$xml_str .= '<lastmod>';
			$xml_str .= $lastmod_date;
			$xml_str .= '</lastmod>';
			$xml_str .= '</url>';
		}

		// increment counter
		$counter++;
	}

	// close urlset
	$xml_str .= '</urlset>';

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml_str);

	$dom->save(__DIR__ . '/' . $sitemap_file);
}
