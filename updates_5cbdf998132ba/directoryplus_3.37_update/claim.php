<?php
require_once(__DIR__ . '/inc/config.php');

// get place id
$place_id = !empty($_GET['id']) ? $_GET['id'] : '';

// check if place id is numeric
if(!ctype_digit($place_id) || empty($place_id)) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

/*--------------------------------------------------
Place info
--------------------------------------------------*/
$query = "SELECT p.*, c.name AS cat_name, c.id AS cat_id, rev_table.avg_rating
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cats c ON c.id = r.cat_id
				LEFT JOIN (
					SELECT *,
						AVG(rev.rating) AS avg_rating
						FROM reviews rev
						GROUP BY place_id
					) rev_table ON p.place_id = rev_table.place_id
				WHERE p.place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$address      = !empty($row['address'    ]) ? $row['address'    ] : '';
	$area_code    = !empty($row['area_code'  ]) ? $row['area_code'  ] : '';
	$cat_id       = !empty($row['cat_id'     ]) ? $row['cat_id'     ] : '';
	$cat_name     = !empty($row['cat_name'   ]) ? $row['cat_name'   ] : '';
	$city_id      = !empty($row['city_id'    ]) ? $row['city_id'    ] : 0;
	$phone        = !empty($row['phone'      ]) ? $row['phone'      ] : '';
	$place_name   = !empty($row['place_name' ]) ? $row['place_name' ] : 'Undefined';
	$place_userid = !empty($row['userid'     ]) ? $row['userid'     ] : '1';
	$postal_code  = !empty($row['postal_code']) ? $row['postal_code'] : '';
	$short_desc   = !empty($row['short_desc' ]) ? $row['short_desc' ] : '';
	$rating       = !empty($row['avg_rating' ]) ? $row['avg_rating' ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cat_name = cat_name_transl($cat_id, $user_cookie_lang, 'singular', $cat_name);
	}

	// sanitize
	$place_name   = e($place_name  );
	$address      = e($address     );
	$city_id      = e($city_id     );
	$place_userid = e($place_userid);
	$cat_name     = e($cat_name    );
	$short_desc   = e($short_desc  );
	$postal_code  = e($postal_code );
	$area_code    = e($area_code   );
	$phone        = e($phone       );
}

/*--------------------------------------------------
Location
--------------------------------------------------*/
$query = "SELECT
		c.city_name, c.slug AS city_slug,
		s.state_id, s.state_name, s.state_abbr, s.slug AS state_slug
		FROM cities c
		LEFT JOIN states s ON c.state_id = s.state_id
		WHERE city_id = :city_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $city_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$city_name  = !empty($row['city_name' ]) ? $row['city_name']  : '';
$city_slug  = !empty($row['city_slug' ]) ? $row['city_slug']  : $city_id;
$state_id   = !empty($row['state_id'  ]) ? $row['state_id']   : '';
$state_name = !empty($row['state_name']) ? $row['state_name'] : '';
$state_abbr = !empty($row['state_abbr']) ? $row['state_abbr'] : '';
$state_slug = !empty($row['state_slug']) ? $row['state_slug'] : $state_id;

/*--------------------------------------------------
Photo
--------------------------------------------------*/

$query = "SELECT * FROM photos WHERE place_id = :place_id ORDER BY photo_id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$dir      = !empty($row['dir']     ) ? $row['dir']      : '';
$filename = !empty($row['filename']) ? $row['filename'] : '';

if(!empty($filename) && !empty($dir)) {
	$photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $dir . '/' . $filename;
}

else {
	$photo_url = $baseurl . '/assets/imgs/blank.png';
}

/*--------------------------------------------------
Plans
--------------------------------------------------*/
$query = "SELECT * FROM plans WHERE plan_status = 1 ORDER BY plan_order";
$stmt = $conn->prepare($query);
$stmt->execute();

$plans_arr = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$plan_id     = $row['plan_id'];
	$plan_type   = !empty($row['plan_type'    ]) ? $row['plan_type'    ] : '';
	$plan_name   = !empty($row['plan_name'    ]) ? $row['plan_name'    ] : '';
	$plan_period = !empty($row['plan_period'  ]) ? $row['plan_period'  ] : 0;
	$plan_feat   = !empty($row['plan_features']) ? $row['plan_features'] : '';
	$plan_price  = !empty($row['plan_price'   ]) ? $row['plan_price'   ] : '0.00';

	// plan description
	$plan_feat = explode("\n", $plan_feat);

	$cur_loop_arr = array(
		'plan_id'     => $plan_id,
		'plan_type'   => $plan_type,
		'plan_name'   => $plan_name,
		'plan_period' => $plan_period,
		'plan_feat'   => $plan_feat,
		'plan_price'  => $plan_price
	);

	$plans_arr[] = $cur_loop_arr;
}

/*--------------------------------------------------
Legacy compatibility
--------------------------------------------------*/
$specialties  = $short_desc;

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/claim?id=' . $place_id;
