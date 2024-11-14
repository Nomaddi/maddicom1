<?php
require_once(__DIR__ . '/inc/config.php');
require_once(__DIR__ . '/inc/img-exts.php');

/*--------------------------------------------------
Valid routes (below starting at index[1]

profile/id
--------------------------------------------------*/

if(empty($route[1]) || !ctype_digit($route[1])) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

$profile_id = $route[1];

/*--------------------------------------------------
Pagination
--------------------------------------------------*/

$page = !empty($route[2]) ? $route[2] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

$page_url = "$baseurl/favorites/$profile_id/";

/*--------------------------------------------------
Profile details
--------------------------------------------------*/
$query = "SELECT * FROM users WHERE id = :profile_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':profile_id', $profile_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// rows
$prof_first_name    = !empty($row['first_name'        ]) ? $row['first_name'        ] : '';
$prof_last_name     = !empty($row['last_name'         ]) ? $row['last_name'         ] : '';
$prof_city_name     = !empty($row['city_name'         ]) ? $row['city_name'         ] : '';
$prof_country_name  = !empty($row['country_name'      ]) ? $row['country_name'      ] : '';
$created            = !empty($row['created'           ]) ? $row['created'           ] : '';
$prof_email         = !empty($row['email'             ]) ? $row['email'             ] : '';
$profile_pic_status = !empty($row['profile_pic_status']) ? $row['profile_pic_status'] : '';

// sanitize
$prof_first_name    = e($prof_first_name   );
$prof_last_name     = e($prof_last_name    );
$prof_city_name     = e($prof_city_name    );
$prof_country_name  = e($prof_country_name );
$created            = e($created           );
$prof_email         = e($prof_email        );
$profile_pic_status = e($profile_pic_status);

// prepare vars
$email_frags = explode('@', $prof_email);
$email_prefix = $email_frags[0];

if(!empty($prof_first_name) && !empty($prof_last_name)) {
	$profile_display_name = $prof_first_name . ' ' . $prof_last_name;
}
elseif(!empty($prof_first_name) && empty($prof_last_name)) {
	$profile_display_name = $prof_first_name;
}
elseif(empty($prof_first_name) && !empty($prof_last_name)) {
	$profile_display_name = $prof_last_name;
}
else {
	$profile_display_name = $email_prefix;
}

$join_date = date("F j, Y", strtotime($created));

/*--------------------------------------------------
Count favorites
--------------------------------------------------*/
$query = "SELECT COUNT(*) AS c FROM rel_favorites WHERE userid = :profile_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':profile_id', $profile_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

/*--------------------------------------------------
Get favorites
--------------------------------------------------*/
$items = array();

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT
				p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
				p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
				c.city_name, c.slug, c.state,
				s.state_name, s.slug AS state_slug, s.state_abbr,
				cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
				ph.dir, ph.filename,
				rev_table.text, rev_table.avg_rating
			FROM rel_favorites f
			LEFT JOIN places p ON f.place_id = p.place_id
			LEFT JOIN rel_place_cat r ON f.place_id = r.place_id AND r.is_main = 1
			LEFT JOIN cities c ON c.city_id = p.city_id
			LEFT JOIN states s ON c.state_id = s.state_id
			LEFT JOIN cats ON r.cat_id = cats.id
			LEFT JOIN photos ph ON f.place_id = ph.place_id
			LEFT JOIN (
				SELECT *,
					AVG(rev.rating) AS avg_rating
					FROM reviews rev
					GROUP BY place_id
				) rev_table ON f.place_id = rev_table.place_id
			WHERE p.status = 'approved' AND p.paid = 1 AND f.userid = :userid
			GROUP BY f.place_id
			ORDER BY p.feat DESC, p.submission_date DESC
			LIMIT :start, :limit";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $profile_id);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_address          = !empty($row['address'     ]) ? $row['address'     ] : '';
		$this_area_code        = !empty($row['area_code'   ]) ? $row['area_code'   ] : '';
		$this_cat_icon         = !empty($row['cat_icon'    ]) ? $row['cat_icon'    ] : '';
		$this_cat_id           = !empty($row['cat_id'      ]) ? $row['cat_id'      ] : '';
		$this_cat_name         = !empty($row['cat_name'    ]) ? $row['cat_name'    ] : '';
		$this_cat_slug         = !empty($row['cat_slug'    ]) ? $row['cat_slug'    ] : '';
		$this_cross_street     = !empty($row['cross_street']) ? $row['cross_street'] : '';
		$this_dir              = !empty($row['dir'         ]) ? $row['dir'         ] : '';
		$this_filename         = !empty($row['filename'    ]) ? $row['filename'    ] : '';
		$this_is_feat          = !empty($row['feat'        ]) ? $row['feat'        ] : '';
		$this_lat              = !empty($row['lat'         ]) ? $row['lat'         ] : '';
		$this_lng              = !empty($row['lng'         ]) ? $row['lng'         ] : '';
		$this_logo             = !empty($row['logo'        ]) ? $row['logo'        ] : '';
		$this_phone            = !empty($row['phone'       ]) ? $row['phone'       ] : '';
		$this_place_city_name  = !empty($row['city_name'   ]) ? $row['city_name'   ] : '';
		$this_place_city_slug  = !empty($row['slug'        ]) ? $row['slug'        ] : 'city';
		$this_place_id         = !empty($row['place_id'    ]) ? $row['place_id'    ] : '';
		$this_place_name       = !empty($row['place_name'  ]) ? $row['place_name'  ] : '';
		$this_place_slug       = !empty($row['place_slug'  ]) ? $row['place_slug'  ] : $this_place_id;
		$this_place_state_abbr = !empty($row['state'       ]) ? $row['state'       ] : '';
		$this_place_state_id   = !empty($row['state_id'    ]) ? $row['state_id'    ] : '';
		$this_place_state_slug = !empty($row['state_slug'  ]) ? $row['state_slug'  ] : '';
		$this_postal_code      = !empty($row['postal_code' ]) ? $row['postal_code' ] : '';
		$this_rating           = !empty($row['avg_rating'  ]) ? $row['avg_rating'  ] : 5;
		$this_short_desc       = !empty($row['short_desc'  ]) ? $row['short_desc'  ] : '';

		// sanitize
		$this_address          = e($this_address         );
		$this_area_code        = e($this_area_code       );
		$this_cat_name         = e($this_cat_name        );
		$this_cat_slug         = e($this_cat_slug        );
		$this_cross_street     = e($this_cross_street    );
		$this_dir              = e($this_dir             );
		$this_filename         = e($this_filename        );
		$this_is_feat          = e($this_is_feat         );
		$this_lat              = e($this_lat             );
		$this_lng              = e($this_lng             );
		$this_logo             = e($this_logo            );
		$this_phone            = e($this_phone           );
		$this_place_city_name  = e($this_place_city_name );
		$this_place_city_slug  = e($this_place_city_slug );
		$this_place_id         = e($this_place_id        );
		$this_place_name       = e($this_place_name      );
		$this_place_slug       = e($this_place_slug      );
		$this_place_state_abbr = e($this_place_state_abbr);
		$this_place_state_id   = e($this_place_state_id  );
		$this_place_state_slug = e($this_place_state_slug);
		$this_postal_code      = e($this_postal_code     );
		$this_rating           = e($this_rating          );
		$this_short_desc       = e($this_short_desc      );

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
		}

		// thumb
		$this_thumb_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($row['filename'])) {
			$this_thumb_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $this_dir . '/' . $this_filename;
		}

		// clean place_name
		$this_endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$this_place_name = str_replace($this_endash, "-", $this_place_name);

		// rating
		$this_rating = number_format((float)$this_rating, 2, $cfg_decimal_separator, '');

		// link to the place's page
		$this_place_link = get_listing_link($this_place_id, $this_place_slug, $this_cat_id, $this_cat_slug, '', $this_place_city_slug, $this_place_state_slug, $cfg_permalink_struct);

		$items[] = array(
			'address'          => $this_address,
			'area_code'        => $this_area_code,
			'cat_name'         => $this_cat_name,
			'cat_slug'         => $this_cat_slug,
			'cross_street'     => $this_cross_street,
			'dir'              => $this_dir,
			'filename'         => $this_filename,
			'is_feat'          => $this_is_feat,
			'lat'              => $this_lat,
			'lng'              => $this_lng,
			'logo'             => $this_logo,
			'phone'            => $this_phone,
			'place_city_name'  => $this_place_city_name,
			'place_city_slug'  => $this_place_city_slug,
			'place_id'         => $this_place_id,
			'place_link'       => $this_place_link,
			'place_name'       => $this_place_name,
			'place_slug'       => $this_place_slug,
			'place_state_abbr' => $this_place_state_abbr,
			'place_state_id'   => $this_place_state_id,
			'place_state_slug' => $this_place_state_slug,
			'postal_code'      => $this_postal_code,
			'rating'           => $this_rating,
			'short_desc'       => $this_short_desc,
			'thumb_url'        => $this_thumb_url,
		);
	}
}

/*--------------------------------------------------
Current visitor favorites array
--------------------------------------------------*/
$place_ids = array();
$favorites = array();

if(!empty($userid) && !empty($items)) {
	foreach($items as $v) {
		$place_ids[] = $v['place_id'];
	}

	$place_ids = implode(',', $place_ids);

	if(!empty($place_ids)) {
		$query = "SELECT * FROM rel_favorites WHERE place_id IN ($place_ids) AND userid = :userid";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':userid', $userid);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$favorites[] = $row['place_id'];
		}
	}
}

/*--------------------------------------------------
Profile pic
--------------------------------------------------*/
$profile_pic = '';
$folder = floor($profile_id / 1000) + 1;

if(strlen($folder) < 1) {
	$folder = '999';
}

// get profile pic filename
$profile_pic_path = $profile_full_folder . '/' . $folder . '/' . $profile_id;

foreach($img_exts as $v) {
	if(file_exists($pic_basepath . '/' . $profile_pic_path . '.' . $v)) {
		$profile_pic = $pic_baseurl . '/' . $profile_pic_path . '.' . $v;
		break;
	}
}

if(!empty($profile_pic)) {
	$profile_pic_tag = '<div class="dummy container-img rounded" style="background-image:url(\'' . $profile_pic . '?' . uniqid() . '\');"></div>';
}

else {
	$profile_pic_tag = '<img src="' . $baseurl . '/assets/imgs/blank.png" width="150" height="150">';
}

/*--------------------------------------------------
language var substitution
--------------------------------------------------*/
$txt_joined_on = str_replace('%join_date%', $join_date, $txt_joined_on);
$txt_html_title = str_replace('%profile_display_name%', $profile_display_name, $txt_html_title);
$txt_meta_desc = str_replace('%profile_display_name%', $profile_display_name, $txt_meta_desc);

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/profile/' . $profile_id;