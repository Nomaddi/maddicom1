<?php
require_once(__DIR__ . '/inc/config.php');
require_once(__DIR__ . '/inc/img-exts.php');

// check if user has city saved in cookie
if(!empty($_COOKIE['city_id'])) {
	$loc_id     = !empty($_COOKIE['city_id']) ? $_COOKIE['city_id'] : '';
	$loc_type   = 'c';
	$loc_name   = !empty($_COOKIE['city_name' ]) ? $_COOKIE['city_name' ] : '';
	$loc_slug   = !empty($_COOKIE['city_slug' ]) ? $_COOKIE['city_slug' ] : '';
	$state_abbr = !empty($_COOKIE['state_abbr']) ? $_COOKIE['state_abbr'] : '';
	$near_query = urlencode("$loc_name,$state_abbr");
}

else {
	$loc_id     = 0;
	$loc_type   = 'n';
	$loc_name   = '';
	$state_abbr = '';
	$loc_slug   = $default_country_code;
	$near_query = $default_country_code;
}

/*--------------------------------------------------
categories
--------------------------------------------------*/

// init
$cats = array();

// query
$query = "SELECT * FROM cats WHERE cat_status = 1 AND parent_id = 0 ORDER BY cat_order";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : '';
	$this_cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
	$this_cat_order   = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';

	// sanitize
	$this_cat_id      = e($this_cat_id);
	$this_cat_name    = e($this_cat_name);
	$this_cat_slug    = e($this_cat_slug);
	$this_plural_name = e($this_plural_name);
	// $this_cat_icon = e($this_cat_icon);
	$this_cat_order   = e($this_cat_order);

	// img path
	$cat_img_path = $pic_basepath . '/category/cat-' . $this_cat_id;

	// check if file exists
	$arr = glob("$cat_img_path.*");

	if(!empty($arr)) {
		$cat_img_filename = basename($arr[0]);
		$cat_img_filename_url = $pic_baseurl . '/category/' . $cat_img_filename;
	}

	else {
		$cat_img_filename = '';
		$cat_img_filename_url = $baseurl . '/assets/imgs/blank.png';
	}

	$cur_loop = array(
		'cat_id'      => $this_cat_id,
		'cat_name'    => $this_cat_name,
		'cat_slug'    => $this_cat_slug,
		'plural_name' => $this_plural_name,
		'cat_icon'    => $this_cat_icon,
		'cat_order'   => $this_cat_order,
		'cat_img'     => $cat_img_filename_url,
		);

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cur_loop['cat_name'] = cat_name_transl($this_cat_id, $user_cookie_lang, 'singular', $this_cat_name);
		$cur_loop['plural_name'] = cat_name_transl($this_cat_id, $user_cookie_lang, 'plural', $this_plural_name);
	}

	$cats[] = $cur_loop;
}

/*--------------------------------------------------
Featured listings
--------------------------------------------------*/
$featured_listings = array();

// array of listings ids in this result set
$featured_listings_ids = array();

$query = "SELECT
	p.place_id, p.userid, p.place_name, p.city_id, p.description, p.short_desc, p.address, p.feat, p.slug AS place_slug,
	ph.dir, ph.filename,
	c.city_name, c.slug,
	s.slug AS state_slug, s.state_abbr,
	cats.cat_slug, cats.id AS cat_id, cats.name AS cat_name, cats.cat_icon, cats.cat_bg,
	pt.plan_priority
	FROM places p
	LEFT JOIN photos ph ON p.place_id = ph.place_id
	LEFT JOIN cities c ON c.city_id = p.city_id
	LEFT JOIN states s ON c.state_id = s.state_id
	LEFT JOIN rel_place_cat rpc ON rpc.place_id = p.place_id AND rpc.is_main = 1
	LEFT JOIN cats ON cats.id = rpc.cat_id
	LEFT JOIN plans pl ON p.plan = pl.plan_id
	LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
	WHERE (p.feat_home = 1 OR (p.feat = 1 AND p.city_id = :city_id)) AND p.status = 'approved'
	GROUP BY p.place_id
	ORDER BY pt.plan_priority DESC";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $loc_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// assign vars from query result
	$feat_place_id       = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
	$feat_userid         = !empty($row['userid'     ]) ? $row['userid'     ] : '';
	$feat_place_name     = !empty($row['place_name' ]) ? $row['place_name' ] : '';
	$feat_place_slug     = !empty($row['place_slug' ]) ? $row['place_slug' ] : $feat_place_id ;
	$feat_place_desc     = !empty($row['description']) ? $row['description'] : '';
	$feat_place_spec     = !empty($row['short_desc' ]) ? $row['short_desc' ] : '';
	$feat_place_addr     = !empty($row['address'    ]) ? $row['address'    ] : '';
	$feat_cat_id         = !empty($row['cat_id'     ]) ? $row['cat_id'     ] : '';
	$feat_cat_name       = !empty($row['cat_name'   ]) ? $row['cat_name'   ] : $feat_cat_id;
	$feat_cat_slug       = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : $feat_cat_id;
	$feat_cat_icon       = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : $cfg_default_cat_icon;
	$feat_cat_bg         = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : $cfg_default_cat_bg;
	$feat_city_name      = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
	$feat_city_slug      = !empty($row['slug'       ]) ? $row['slug'       ] : '';
	$feat_state_slug     = !empty($row['state_slug' ]) ? $row['state_slug' ] : '';
	$feat_state_abbr     = !empty($row['state_abbr' ]) ? $row['state_abbr' ] : '';
	$feat_photo_dir      = !empty($row['dir'        ]) ? $row['dir'        ] : '';
	$feat_photo_filename = !empty($row['filename'   ]) ? $row['filename'   ] : '';

	// sanitize
	$feat_place_id       = e($feat_place_id      );
	$feat_userid         = e($feat_userid        );
	$feat_place_name     = e($feat_place_name    );
	$feat_place_slug     = e($feat_place_slug    );
	$feat_place_desc     = e($feat_place_desc    );
	$feat_place_spec     = e($feat_place_spec    );
	$feat_place_addr     = e($feat_place_addr    );
	$feat_cat_id         = e($feat_cat_id        );
	$feat_cat_name       = e($feat_cat_name      );
	$feat_cat_slug       = e($feat_cat_slug      );
	//$feat_cat_icon     = e($feat_cat_icon      );
	//$feat_cat_bg       = e($feat_cat_bg        );
	$feat_city_name      = e($feat_city_name     );
	$feat_city_slug      = e($feat_city_slug     );
	$feat_state_slug     = e($feat_state_slug    );
	$feat_state_abbr     = e($feat_state_abbr    );
	$feat_photo_dir      = e($feat_photo_dir     );
	$feat_photo_filename = e($feat_photo_filename);

	// place name
	$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
	$feat_place_name = str_replace($endash, "-", $feat_place_name);

	// limit description text length
	if(!empty($feat_place_desc)) {
		$feat_place_desc = mb_substr($feat_place_desc, 0, 96);
	}

	// limit short_desc text length
	if(!empty($feat_place_spec)) {
		$feat_place_spec = mb_substr($feat_place_spec, 0, 96);
	}

	// feat_photo_url
	$feat_photo_url = $baseurl . '/assets/imgs/blank.png';

	if(!empty($feat_photo_filename)) {
		if(is_file($pic_basepath . '/' . $place_thumb_folder . '/' . $feat_photo_dir . '/' . $feat_photo_filename)) {
			$feat_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $feat_photo_dir . '/' . $feat_photo_filename;
		}
	}

	// owner profile pic
	$feat_profile_pic = $baseurl . '/assets/imgs/blank.png';
	$folder = floor($feat_userid / 1000) + 1;

	if(strlen($folder) < 1) {
		$folder = '999';
	}

	// get profile pic filename
	$profile_pic_path = $profile_thumb_folder . '/' . $folder . '/' . $feat_userid;

	foreach($img_exts as $v) {
		if(file_exists($pic_basepath . '/' . $profile_pic_path . '.' . $v)) {
			$feat_profile_pic = $pic_baseurl . '/' . $profile_pic_path . '.' . $v;
			break;
		}
	}

	// link
	$feat_place_link = get_listing_link($feat_place_id, $feat_place_slug, $feat_cat_id, $feat_cat_slug, '', $feat_city_slug, $feat_state_slug, $cfg_permalink_struct);

	// populate array
	$cur_loop = array(
		'place_id'    => $feat_place_id,
		'profile_pic' => $feat_profile_pic,
		'place_name'  => $feat_place_name,
		'place_desc'  => $feat_place_desc,
		'place_spec'  => $feat_place_spec,
		'place_addr'  => $feat_place_addr,
		'place_slug'  => $feat_place_slug,
		'place_link'  => $feat_place_link,
		'photo_url'   => $feat_photo_url,
		'city_name'   => $feat_city_name,
		'city_slug'   => $feat_city_slug,
		'state_slug'  => $feat_state_slug,
		'state_abbr'  => $feat_state_abbr,
		'cat_name'    => $feat_cat_name,
		'cat_slug'    => $feat_cat_slug,
		'cat_icon'    => $feat_cat_icon,
		'cat_bg'      => $feat_cat_bg,
		'avg_rating'  => 0,
		);

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cur_loop['cat_name'] = cat_name_transl($feat_cat_id , $user_cookie_lang, 'singular', $feat_cat_name);
	}

	$featured_listings[$feat_place_id] = $cur_loop;

	// populate array of listings ids for this results set
	$featured_listings_ids[$feat_place_id] = $feat_place_id;
}

/*--------------------------------------------------
Featured listings ratings
--------------------------------------------------*/

if(!empty($featured_listings)) {
	$featured_listings_ids_str = implode(",", $featured_listings_ids);

	$query = "SELECT place_id, AVG(reviews.rating) AS avg_rating
				FROM reviews
				WHERE place_id IN($featured_listings_ids_str)
				GROUP BY place_id";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$place_id = $row['place_id'];
		$avg_rating = $row['avg_rating'];
		$avg_rating = number_format((float)$avg_rating, 2, isset($cfg_decimal_separator) ? $cfg_decimal_separator : '.', '');

		$featured_listings[$place_id]['avg_rating'] = $avg_rating;
	}
}

/*--------------------------------------------------
Latest listings
--------------------------------------------------*/
$latest_listings = array();

// array of listings ids in this result set
$latest_listings_ids = array();

$query = "SELECT
	p.place_id, p.userid, p.place_name, p.city_id, p.description, p.short_desc, p.address, p.feat, p.slug AS place_slug,
	ph.dir, ph.filename,
	c.city_name, c.slug,
	s.slug AS state_slug, s.state_abbr,
	cats.cat_slug, cats.id AS cat_id, cats.name AS cat_name, cats.cat_icon, cats.cat_bg
	FROM places p
	LEFT JOIN photos ph ON p.place_id = ph.place_id
	LEFT JOIN cities c ON c.city_id = p.city_id
	LEFT JOIN states s ON c.state_id = s.state_id
	LEFT JOIN rel_place_cat rpc ON rpc.place_id = p.place_id AND rpc.is_main = 1
	LEFT JOIN cats ON cats.id = rpc.cat_id
	WHERE p.status = 'approved' AND p.paid = 1
	GROUP BY p.place_id
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
	$latest_cat_icon       = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : $cfg_default_cat_icon;
	$latest_cat_bg         = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : $cfg_default_cat_bg;
	$latest_city_name      = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
	$latest_city_slug      = !empty($row['slug'       ]) ? $row['slug'       ] : '';
	$latest_state_slug     = !empty($row['state_slug' ]) ? $row['state_slug' ] : '';
	$latest_state_abbr     = !empty($row['state_abbr' ]) ? $row['state_abbr' ] : '';
	$latest_photo_dir      = !empty($row['dir'        ]) ? $row['dir'        ] : '';
	$latest_photo_filename = !empty($row['filename'   ]) ? $row['filename'   ] : '';
	$latest_is_feat        = !empty($row['feat'       ]) ? $row['feat'       ] : false;

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
	//$latest_cat_icon     = e($latest_cat_icon      );
	//$latest_cat_bg       = e($latest_cat_bg        );
	$latest_city_name      = e($latest_city_name     );
	$latest_city_slug      = e($latest_city_slug     );
	$latest_state_slug     = e($latest_state_slug    );
	$latest_state_abbr     = e($latest_state_abbr    );
	$latest_photo_dir      = e($latest_photo_dir     );
	$latest_photo_filename = e($latest_photo_filename);

	// place name
	$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
	$latest_place_name = str_replace($endash, "-", $latest_place_name);

	// limit description text length
	if(!empty($latest_place_desc)) {
		$latest_place_desc = mb_substr($latest_place_desc, 0, 96);
	}

	// limit short_desc text length
	if(!empty($latest_place_spec)) {
		$latest_place_spec = mb_substr($latest_place_spec, 0, 96);
	}

	// latest_photo_url
	$latest_photo_url = $baseurl . '/assets/imgs/blank.png';

	if(!empty($latest_photo_filename)) {
		if(is_file($pic_basepath . '/' . $place_thumb_folder . '/' . $latest_photo_dir . '/' . $latest_photo_filename)) {
			$latest_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $latest_photo_dir . '/' . $latest_photo_filename;
		}
	}

	// owner profile pic
	$latest_profile_pic = $baseurl . '/assets/imgs/blank.png';
	$folder = floor($latest_userid / 1000) + 1;

	if(strlen($folder) < 1) {
		$folder = '999';
	}

	// get profile pic filename
	$profile_pic_path = $profile_thumb_folder . '/' . $folder . '/' . $latest_userid;

	foreach($img_exts as $v) {
		if(file_exists($pic_basepath . '/' . $profile_pic_path . '.' . $v)) {
			$latest_profile_pic = $pic_baseurl . '/' . $profile_pic_path . '.' . $v;
			break;
		}
	}

	// link
	$latest_place_link = get_listing_link($latest_place_id, $latest_place_slug, $latest_cat_id, $latest_cat_slug, '', $latest_city_slug, $latest_state_slug, $cfg_permalink_struct);

	// populate array
	$cur_loop = array(
		'place_id'    => $latest_place_id,
		'profile_pic' => $latest_profile_pic,
		'place_name'  => $latest_place_name,
		'place_desc'  => $latest_place_desc,
		'place_spec'  => $latest_place_desc,
		'place_addr'  => $latest_place_addr,
		'place_slug'  => $latest_place_slug,
		'place_link'  => $latest_place_link,
		'photo_url'   => $latest_photo_url,
		'city_name'   => $latest_city_name,
		'city_slug'   => $latest_city_slug,
		'state_slug'  => $latest_state_slug,
		'state_abbr'  => $latest_state_abbr,
		'cat_name'    => $latest_cat_name,
		'cat_slug'    => $latest_cat_slug,
		'cat_icon'    => $latest_cat_icon,
		'cat_bg'      => $latest_cat_bg,
		'is_feat'     => $latest_is_feat,
		'avg_rating'  => 0,
		);

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cur_loop['cat_name'] = cat_name_transl($latest_cat_id , $user_cookie_lang, 'singular', $latest_cat_name);
	}

	$latest_listings[$latest_place_id] = $cur_loop;

	// populate array of listings ids for this results set
	$latest_listings_ids[$latest_place_id] = $latest_place_id;
}

/*--------------------------------------------------
Latest listings ratings
--------------------------------------------------*/

if(!empty($latest_listings)) {
	$latest_listings_ids_str = implode(",", $latest_listings_ids);

	$query = "SELECT place_id, AVG(reviews.rating) AS avg_rating
				FROM reviews
				WHERE place_id IN($latest_listings_ids_str)
				GROUP BY place_id";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$place_id = $row['place_id'];
		$avg_rating = $row['avg_rating'];
		$avg_rating = number_format((float)$avg_rating, 2, isset($cfg_decimal_separator) ? $cfg_decimal_separator : '.', '');

		$latest_listings[$place_id]['avg_rating'] = $avg_rating;
	}
}

/*--------------------------------------------------
featured cities
--------------------------------------------------*/
$featured_cities = array();

$query = "SELECT
	c.*, s.slug AS state_slug, x.num_listings
	FROM cities c
	LEFT JOIN states s ON c.state_id = s.state_id
	LEFT JOIN (SELECT city_id, COUNT(*) AS num_listings FROM `places` GROUP BY city_id) x ON x.city_id = c.city_id
	RIGHT JOIN cities_feat f ON c.city_id = f.city_id
	ORDER BY c.city_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// default city pic
	$city_pic = $baseurl . '/assets/imgs/dark.jpg';

	// city image if exists
	foreach($img_exts as $v) {
		if(file_exists($pic_basepath . '/city/' . $row['city_id'] . '.' . $v)) {
			$city_pic = $pic_baseurl . '/city/' . $row['city_id'] . '.' . $v;
			break;
		}
	}

	$num_listings = empty($row['num_listings']) ? 0 : $row['num_listings'];

	$cur_loop = array(
		'city_id'      => $row['city_id'],
		'city_name'    => $row['city_name'],
		'state_slug'   => $row['state_slug'],
		'state_id'     => $row['state_id'],
		'city_slug'    => $row['slug'],
		'num_listings' => $num_listings,
		'city_pic'     => $city_pic
	);

	$featured_cities[] = $cur_loop;
}

/*--------------------------------------------------
near listings
--------------------------------------------------*/
$near_listings = array();

// array of listings ids in this result set
$near_listings_ids = array();

$geo_city_id = !empty($_COOKIE['geo_city_id']) ? $_COOKIE['geo_city_id'] : '';

if(!empty($geo_city_id)) {
	$query = "SELECT
		p.place_id, p.userid, p.place_name, p.city_id, p.description, p.short_desc, p.address, p.feat, p.slug AS place_slug,
		ph.dir, ph.filename,
		c.city_name, c.slug,
		s.slug AS state_slug, s.state_abbr,
		cats.cat_slug, cats.id AS cat_id, cats.name AS cat_name, cats.cat_icon, cats.cat_bg
		FROM places p
		LEFT JOIN photos ph ON p.place_id = ph.place_id
		LEFT JOIN cities c ON c.city_id = p.city_id
		LEFT JOIN states s ON c.state_id = s.state_id
		LEFT JOIN rel_place_cat rpc ON rpc.place_id = p.place_id AND rpc.is_main = 1
		LEFT JOIN cats ON cats.id = rpc.cat_id
		WHERE p.city_id = :city_id AND p.status = 'approved' AND p.paid = 1
		GROUP BY p.place_id
		ORDER BY p.place_id DESC LIMIT :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $geo_city_id);
	$stmt->bindValue(':limit', $cfg_latest_listings_count);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// assign vars from query result
		$near_place_id       = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$near_userid         = !empty($row['userid'     ]) ? $row['userid'     ] : '';
		$near_place_name     = !empty($row['place_name' ]) ? $row['place_name' ] : '';
		$near_place_slug     = !empty($row['place_slug' ]) ? $row['place_slug' ] : $near_place_id ;
		$near_place_desc     = !empty($row['description']) ? $row['description'] : '';
		$near_place_spec     = !empty($row['short_desc' ]) ? $row['short_desc' ] : '';
		$near_place_addr     = !empty($row['address'    ]) ? $row['address'    ] : '';
		$near_cat_id         = !empty($row['cat_id'     ]) ? $row['cat_id'     ] : '';
		$near_cat_name       = !empty($row['cat_name'   ]) ? $row['cat_name'   ] : $near_cat_id;
		$near_cat_slug       = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : $near_cat_id;
		$near_cat_icon       = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : $cfg_default_cat_icon;
		$near_cat_bg         = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : $cfg_default_cat_bg;
		$near_city_name      = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
		$near_city_slug      = !empty($row['slug'       ]) ? $row['slug'       ] : '';
		$near_state_slug     = !empty($row['state_slug' ]) ? $row['state_slug' ] : '';
		$near_state_abbr     = !empty($row['state_abbr' ]) ? $row['state_abbr' ] : '';
		$near_photo_dir      = !empty($row['dir'        ]) ? $row['dir'        ] : '';
		$near_photo_filename = !empty($row['filename'   ]) ? $row['filename'   ] : '';

		// sanitize
		$near_place_id       = e($near_place_id      );
		$near_userid         = e($near_userid        );
		$near_place_name     = e($near_place_name    );
		$near_place_slug     = e($near_place_slug    );
		$near_place_desc     = e($near_place_desc    );
		$near_place_spec     = e($near_place_spec    );
		$near_place_addr     = e($near_place_addr    );
		$near_cat_id         = e($near_cat_id        );
		$near_cat_name       = e($near_cat_name      );
		$near_cat_slug       = e($near_cat_slug      );
		//$near_cat_icon     = e($near_cat_icon      );
		//$near_cat_bg       = e($near_cat_bg        );
		$near_city_name      = e($near_city_name     );
		$near_city_slug      = e($near_city_slug     );
		$near_state_slug     = e($near_state_slug    );
		$near_state_abbr     = e($near_state_abbr    );
		$near_photo_dir      = e($near_photo_dir     );
		$near_photo_filename = e($near_photo_filename);

		// place name
		$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$near_place_name = str_replace($endash, "-", $near_place_name);

		// limit description text length
		if(!empty($near_place_desc)) {
			$near_place_desc = mb_substr($near_place_desc, 0, 96);
		}

		// limit short_desc text length
		if(!empty($near_place_spec)) {
			$near_place_spec = mb_substr($near_place_spec, 0, 96);
		}

		// near_photo_url
		$near_photo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($near_photo_filename)) {
			if(is_file($pic_basepath . '/' . $place_thumb_folder . '/' . $near_photo_dir . '/' . $near_photo_filename)) {
				$near_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $near_photo_dir . '/' . $near_photo_filename;
			}
		}

		// owner profile pic
		$near_profile_pic = $baseurl . '/assets/imgs/blank.png';
		$folder = floor($near_userid / 1000) + 1;

		if(strlen($folder) < 1) {
			$folder = '999';
		}

		// get profile pic filename
		$profile_pic_path = $profile_thumb_folder . '/' . $folder . '/' . $near_userid;

		foreach($img_exts as $v) {
			if(file_exists($pic_basepath . '/' . $profile_pic_path . '.' . $v)) {
				$near_profile_pic = $pic_baseurl . '/' . $profile_pic_path . '.' . $v;
				break;
			}
		}

		// link
		$near_place_link = get_listing_link($near_place_id, $near_place_slug, $near_cat_id, $near_cat_slug, '', $near_city_slug, $near_state_slug, $cfg_permalink_struct);

		// populate array
		$cur_loop = array(
			'place_id'    => $near_place_id,
			'profile_pic' => $near_profile_pic,
			'place_name'  => $near_place_name,
			'place_desc'  => $near_place_desc,
			'place_spec'  => $near_place_spec,
			'place_addr'  => $near_place_addr,
			'place_slug'  => $near_place_slug,
			'place_link'  => $near_place_link,
			'photo_url'   => $near_photo_url,
			'city_name'   => $near_city_name,
			'city_slug'   => $near_city_slug,
			'state_slug'  => $near_state_slug,
			'state_abbr'  => $near_state_abbr,
			'cat_name'    => $near_cat_name,
			'cat_slug'    => $near_cat_slug,
			'cat_icon'    => $near_cat_icon,
			'cat_bg'      => $near_cat_bg,
			'avg_rating'  => 0,
			);

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$cur_loop['cat_name'] = cat_name_transl($near_cat_id , $user_cookie_lang, 'singular', $near_cat_name);
		}

		$near_listings[$near_place_id] = $cur_loop;

		// populate array of listings ids for this results set
		$near_listings_ids[$near_place_id] = $near_place_id;
	}
}

/*--------------------------------------------------
Near listings ratings
--------------------------------------------------*/

if(!empty($near_listings)) {
	$near_listings_ids_str = implode(",", $near_listings_ids);

	$query = "SELECT place_id, AVG(reviews.rating) AS avg_rating
				FROM reviews
				WHERE place_id IN($near_listings_ids_str)
				GROUP BY place_id";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$place_id = $row['place_id'];
		$avg_rating = $row['avg_rating'];
		$avg_rating = number_format((float)$avg_rating, 2, isset($cfg_decimal_separator) ? $cfg_decimal_separator : '.', '');

		$near_listings[$place_id]['avg_rating'] = $avg_rating;
	}
}

/*--------------------------------------------------
Favorites array
--------------------------------------------------*/
$places_ids = array();
$favorites = array();

if(!empty($userid)) {
	foreach($latest_listings as $v) {
		$places_ids[] = $v['place_id'];
	}

	foreach($near_listings as $v) {
		$places_ids[] = $v['place_id'];
	}

	if(!empty($places_ids)) {
		$places_ids = implode(',', $places_ids);

		$query = "SELECT * FROM rel_favorites WHERE place_id IN ($places_ids) AND userid = :userid";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':userid', $userid);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$favorites[] = $row['place_id'];
		}
	}
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl;
