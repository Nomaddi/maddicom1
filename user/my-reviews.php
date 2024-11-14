<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// Reviews enabled check

if(!$cfg_enable_reviews) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

$page_url = "$baseurl/user/my-reviews?page=";

// query reviews, count total reviews for current user
$query = "SELECT COUNT(*) AS c FROM reviews WHERE user_id = :userid AND status != 'trashed'";
$stmt = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT
		r.review_id, r.place_id, r.pubdate, r.rating, r.text,
		p.place_name, p.slug AS place_slug,
		c.city_id AS review_city_id, c.slug AS city_slug, c.city_name,
		s.slug AS state_slug,
		rel.cat_id,
		cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
		ph.dir, ph.filename
		FROM reviews r
		LEFT JOIN places p ON r.place_id = p.place_id
		LEFT JOIN cities c ON p.city_id = c.city_id
		LEFT JOIN states s ON s.state_id = c.state_id
		LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id AND rel.is_main = 1
		LEFT JOIN cats ON rel.cat_id = cats.id
		LEFT JOIN (SELECT * FROM photos GROUP BY place_id) ph ON ph.place_id = r.place_id
		WHERE r.user_id = :user_id AND (r.status = 'approved' OR r.status = 'pending')
		ORDER BY r.pubdate DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':user_id', $userid);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	$reviews = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_review_id  = $row['review_id'];
		$this_cat_id     = !empty($row['cat_id'        ]) ? $row['cat_id'        ] : '';
		$this_cat_slug   = !empty($row['cat_slug'      ]) ? $row['cat_slug'      ] : '';
		$this_city_id    = !empty($row['city_id'       ]) ? $row['city_id'       ] : '';
		$this_city_id    = !empty($row['review_city_id']) ? $row['review_city_id'] : '';
		$this_city_name  = !empty($row['city_name'     ]) ? $row['city_name'     ] : '';
		$this_city_slug  = !empty($row['city_slug'     ]) ? $row['city_slug'     ] : '';
		$this_city_slug  = !empty($row['city_slug'     ]) ? $row['city_slug'     ] : '';
		$this_dir        = !empty($row['dir'           ]) ? $row['dir'           ] : '';
		$this_filename   = !empty($row['filename'      ]) ? $row['filename'      ] : '';
		$this_place_id   = !empty($row['place_id'      ]) ? $row['place_id'      ] : '';
		$this_place_name = !empty($row['place_name'    ]) ? $row['place_name'    ] : '';
		$this_place_slug = !empty($row['place_slug'    ]) ? $row['place_slug'    ] : '';
		$this_pubdate    = !empty($row['pubdate'       ]) ? $row['pubdate'       ] : '';
		$this_rating     = !empty($row['rating'        ]) ? $row['rating'        ] : 0;
		$this_state_slug = !empty($row['state_slug'    ]) ? $row['state_slug'    ] : '';
		$this_text       = !empty($row['text'          ]) ? $row['text'          ] : '';

		// sanitize
		$this_cat_id     = e($this_cat_id);
		$this_cat_slug   = e($this_cat_slug);
		$this_city_id    = e($this_city_id);
		$this_city_name  = e($this_city_name);
		$this_city_slug  = e($this_city_slug);
		$this_dir        = e($this_dir);
		$this_filename   = e($this_filename);
		$this_place_id   = e($this_place_id);
		$this_place_name = e($this_place_name);
		$this_place_slug = e($this_place_slug);
		$this_pubdate    = e($this_pubdate);
		$this_rating     = e($this_rating);
		$this_state_slug = e($this_state_slug);
		$this_text       = e($this_text);

		// link to the place page
		$this_listing_link = get_listing_link($this_place_id, $this_place_slug, $this_cat_id, $this_cat_slug, $this_city_id, $this_city_slug, $this_state_slug, $cfg_permalink_struct);

		// thumb
		$this_thumb_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($this_filename)) {
			if(file_exists("$pic_basepath/$place_thumb_folder/$this_dir/$this_filename")) {
				$this_thumb_url = "$pic_baseurl/$place_thumb_folder/$this_dir/$this_filename";
			}
		}

		// add to array
		$reviews[] = array(
			'review_id'  => $this_review_id,
			'city_id'    => $this_city_id,
			'city_name'  => $this_city_name,
			'city_slug'  => $this_city_slug,
			'link_url'   => $this_listing_link,
			'place_id'   => $this_place_id,
			'place_name' => $this_place_name,
			'pubdate'    => $this_pubdate,
			'rating'     => $this_rating,
			'text'       => $this_text,
			'thumb_url'  => $this_thumb_url,
		);
	}
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/my-reviews';
