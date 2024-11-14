<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/img-exts.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id

/*--------------------------------------------------
MySQL version
--------------------------------------------------*/

$query = "SHOW VARIABLES LIKE 'version'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$mysql_version = $row['Value'];

/*--------------------------------------------------
Counts totals
--------------------------------------------------*/

// count pending ads
$query = "SELECT COUNT(*) AS total_ads_pending FROM places WHERE status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_ads_pending = $row['total_ads_pending'];

// count total listings
$query = "SELECT COUNT(*) AS total_ads FROM places WHERE status <> 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_ads = $row['total_ads'];

// count total users
$query = "SELECT COUNT(*) AS total_users FROM users WHERE status <> 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_users = $row['total_users'];

// count total reviews
$query = "SELECT COUNT(*) AS total_reviews FROM reviews WHERE status <> 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_reviews = $row['total_reviews'];

if(empty($cfg_admin_home_disable_charts)) {
	/*--------------------------------------------------
	Counts per interval: listings
	--------------------------------------------------*/

	// listings
	$listings_per_period = array();

	// set defaults
	$listings_per_period['m1'] = 0;
	$listings_per_period['m2'] = 0;
	$listings_per_period['m3'] = 0;
	$listings_per_period['m4'] = 0;
	$listings_per_period['m5'] = 0;
	$listings_per_period['m6'] = 0;

	$query = "SELECT
		CASE
			WHEN submission_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() THEN 'm1'
			WHEN submission_date BETWEEN (NOW() - INTERVAL 60 DAY) AND (NOW() - INTERVAL 31 DAY) THEN 'm2'
			WHEN submission_date BETWEEN (NOW() - INTERVAL 90 DAY) AND (NOW() - INTERVAL 61 DAY) THEN 'm3'
			WHEN submission_date BETWEEN (NOW() - INTERVAL 120 DAY) AND (NOW() - INTERVAL 91 DAY) THEN 'm4'
			WHEN submission_date BETWEEN (NOW() - INTERVAL 150 DAY) AND (NOW() - INTERVAL 121 DAY) THEN 'm5'
			WHEN submission_date BETWEEN (NOW() - INTERVAL 180 DAY) AND (NOW() - INTERVAL 151 DAY) THEN 'm6'
			ELSE 'm0'
		END AS period,
		COUNT(1) AS total_num
	FROM places
	GROUP BY period";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$listings_per_period[$row['period']] = $row['total_num'];
	}

	// variation
	$listings_variation = 0;
	if($listings_per_period['m1'] > 0 && $listings_per_period['m2'] > 0 ) {
		$listings_variation = ($listings_per_period['m1'] / $listings_per_period['m2'] - 1) * 100;
	}

	$listings_variation = round($listings_variation, 2);

	// reverse array
	$listings_per_period = array_reverse($listings_per_period);

	/*--------------------------------------------------
	Counts per interval: signups
	--------------------------------------------------*/

	// signups
	$signups_per_period = array();

	// set defaults
	$signups_per_period['m1'] = 0;
	$signups_per_period['m2'] = 0;
	$signups_per_period['m3'] = 0;
	$signups_per_period['m4'] = 0;
	$signups_per_period['m5'] = 0;
	$signups_per_period['m6'] = 0;

	$query = "SELECT
		CASE
			WHEN created BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() THEN 'm1'
			WHEN created BETWEEN (NOW() - INTERVAL 60 DAY) AND (NOW() - INTERVAL 31 DAY) THEN 'm2'
			WHEN created BETWEEN (NOW() - INTERVAL 90 DAY) AND (NOW() - INTERVAL 61 DAY) THEN 'm3'
			WHEN created BETWEEN (NOW() - INTERVAL 120 DAY) AND (NOW() - INTERVAL 91 DAY) THEN 'm4'
			WHEN created BETWEEN (NOW() - INTERVAL 150 DAY) AND (NOW() - INTERVAL 121 DAY) THEN 'm5'
			WHEN created BETWEEN (NOW() - INTERVAL 180 DAY) AND (NOW() - INTERVAL 151 DAY) THEN 'm6'
			ELSE 'm0'
		END AS period,
		COUNT(1) AS total_num
	FROM users
	GROUP BY period";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$signups_per_period[$row['period']] = $row['total_num'];
	}

	// variation
	$signups_variation = 0;
	if($signups_per_period['m1'] > 0 && $signups_per_period['m2'] > 0 ) {
		$signups_variation = ($signups_per_period['m1'] / $signups_per_period['m2'] - 1) * 100;
	}

	$signups_variation = round($signups_variation, 2);

	// reverse array
	$signups_per_period = array_reverse($signups_per_period);

	/*--------------------------------------------------
	Counts per interval: reviews
	--------------------------------------------------*/

	// reviews
	$reviews_per_period = array();

	// set defaults
	$reviews_per_period['m1'] = 0;
	$reviews_per_period['m2'] = 0;
	$reviews_per_period['m3'] = 0;
	$reviews_per_period['m4'] = 0;
	$reviews_per_period['m5'] = 0;
	$reviews_per_period['m6'] = 0;

	$query = "SELECT
		CASE
			WHEN pubdate BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() THEN 'm1'
			WHEN pubdate BETWEEN (NOW() - INTERVAL 60 DAY) AND (NOW() - INTERVAL 31 DAY) THEN 'm2'
			WHEN pubdate BETWEEN (NOW() - INTERVAL 90 DAY) AND (NOW() - INTERVAL 61 DAY) THEN 'm3'
			WHEN pubdate BETWEEN (NOW() - INTERVAL 120 DAY) AND (NOW() - INTERVAL 91 DAY) THEN 'm4'
			WHEN pubdate BETWEEN (NOW() - INTERVAL 150 DAY) AND (NOW() - INTERVAL 121 DAY) THEN 'm5'
			WHEN pubdate BETWEEN (NOW() - INTERVAL 180 DAY) AND (NOW() - INTERVAL 151 DAY) THEN 'm6'
			ELSE 'm0'
		END AS period,
		COUNT(1) AS total_num
	FROM reviews
	GROUP BY period";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$reviews_per_period[$row['period']] = $row['total_num'];
	}

	// variation
	$reviews_variation = 0;
	if($reviews_per_period['m1'] > 0 && $reviews_per_period['m2'] > 0 ) {
		$reviews_variation = ($reviews_per_period['m1'] / $reviews_per_period['m2'] - 1) * 100;
	}

	$reviews_variation = round($reviews_variation, 2);

	// reverse array
	$reviews_per_period = array_reverse($reviews_per_period);
}

/*--------------------------------------------------
latest listings
--------------------------------------------------*/
$latest_listings = array();
$cfg_latest_listings_count = isset($cfg_latest_listings_count) ? $cfg_latest_listings_count : 10;

$query = "SELECT
	p.place_id, p.userid, p.place_name, p.city_id, p.description, p.short_desc, p.address, p.feat, p.slug AS place_slug,
	c.city_name, c.slug,
	s.slug AS state_slug, s.state_abbr,
	cats.cat_slug, cats.id AS cat_id, cats.name AS cat_name
	FROM places p
	LEFT JOIN cities c ON c.city_id = p.city_id
	LEFT JOIN states s ON c.state_id = s.state_id
	LEFT JOIN rel_place_cat rpc ON rpc.place_id = p.place_id AND rpc.is_main = 1
	LEFT JOIN cats ON cats.id = rpc.cat_id
	ORDER BY p.place_id DESC LIMIT :limit";
$stmt = $conn->prepare($query);
$stmt->bindValue(':limit', $cfg_latest_listings_count);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// assign vars from query result
	$latest_place_id       = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
	$latest_userid         = !empty($row['userid'     ]) ? $row['userid'     ] : '';
	$latest_place_name     = !empty($row['place_name' ]) ? $row['place_name' ] : '';
	$latest_place_slug     = !empty($row['place_slug' ]) ? $row['place_slug' ] : $latest_place_id ;
	$latest_place_desc     = !empty($row['description']) ? $row['description'] : '';
	$latest_place_spec     = !empty($row['short_desc' ]) ? $row['short_desc' ] : '';
	$latest_place_addr     = !empty($row['address'    ]) ? $row['address'    ] : '';
	$latest_cat_id         = !empty($row['cat_id'     ]) ? $row['cat_id'     ] : '';
	$latest_cat_name       = !empty($row['cat_name'   ]) ? $row['cat_name'   ] : $latest_cat_id;
	$latest_cat_slug       = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : $latest_cat_id;
	$latest_city_name      = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
	$latest_city_slug      = !empty($row['slug'       ]) ? $row['slug'       ] : '';
	$latest_state_slug     = !empty($row['state_slug' ]) ? $row['state_slug' ] : '';
	$latest_state_abbr     = !empty($row['state_abbr' ]) ? $row['state_abbr' ] : '';

	// sanitize
	$latest_place_id       = e($latest_place_id      );
	$latest_userid         = e($latest_userid        );
	$latest_place_name     = e($latest_place_name    );
	$latest_place_slug     = e($latest_place_slug    );
	$latest_place_desc     = e($latest_place_desc    );
	$latest_place_spec     = e($latest_place_spec    );
	$latest_place_addr     = e($latest_place_addr    );
	$latest_cat_id         = e($latest_cat_id        );
	$latest_cat_name       = e($latest_cat_name      );
	$latest_cat_slug       = e($latest_cat_slug      );
	$latest_city_name      = e($latest_city_name     );
	$latest_city_slug      = e($latest_city_slug     );
	$latest_state_slug     = e($latest_state_slug    );
	$latest_state_abbr     = e($latest_state_abbr    );

	// place name
	$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
	$latest_place_name = str_replace($endash, "-", $latest_place_name);

	// link
	$latest_place_link = get_listing_link(
							$latest_place_id,
							$latest_place_slug,
							$latest_cat_id,
							$latest_cat_slug,
							'',
							$latest_city_slug,
							$latest_state_slug,
							$cfg_permalink_struct);

	// populate array
	$cur_loop = array(
		'place_id'   => $latest_place_id,
		'place_name' => $latest_place_name,
		'place_desc' => $latest_place_desc,
		'place_spec' => $latest_place_desc,
		'place_addr' => $latest_place_addr,
		'place_slug' => $latest_place_slug,
		'place_link' => $latest_place_link,
		'city_name'  => $latest_city_name,
		'city_slug'  => $latest_city_slug,
		'state_slug' => $latest_state_slug,
		'state_abbr' => $latest_state_abbr,
		'cat_name'   => $latest_cat_name,
		'cat_slug'   => $latest_cat_slug,
		);

	$latest_listings[] = $cur_loop;
}

/*--------------------------------------------------
latest users
--------------------------------------------------*/
$latest_users = array();

$query = "SELECT * FROM users
	WHERE status = 'approved'
	ORDER BY id DESC LIMIT :limit";
$stmt = $conn->prepare($query);
$stmt->bindValue(':limit', $cfg_latest_listings_count);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// assign vars from query result
	$this_id    = !empty($row['id'   ]) ? $row['id'   ] : '';
	$this_email = !empty($row['email']) ? $row['email'] : '';

	// populate array
	$cur_loop = array(
		'user_id'    => $this_id,
		'user_email' => $this_email,
		);

	$latest_users[] = $cur_loop;
}
