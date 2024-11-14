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
Reviews enabled check
--------------------------------------------------*/

if(!$cfg_enable_reviews) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

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

$page_url = "$baseurl/reviews/$profile_id/";

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
Count reviews
--------------------------------------------------*/
$query = "SELECT COUNT(*) AS c FROM reviews WHERE user_id = :profile_id AND status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->bindValue(':profile_id', $profile_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

/*--------------------------------------------------
Get reviews
--------------------------------------------------*/
$reviews = array();

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// query reviews
	$query = "SELECT
			r.review_id, r.place_id, r.pubdate, r.rating, r.text,
			p.place_name, p.slug AS place_slug,
			ci.city_id AS city_id, ci.slug AS city_slug, ci.city_name,
			s.slug AS state_slug,
			cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
			ph.dir, ph.filename
		FROM reviews r
		LEFT JOIN places p ON r.place_id = p.place_id
		LEFT JOIN cities ci ON p.city_id = ci.city_id
		LEFT JOIN states s ON s.state_id = ci.state_id
		LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id AND rel.is_main = 1
		LEFT JOIN cats ON rel.cat_id = cats.id
		LEFT JOIN (SELECT * FROM photos GROUP BY place_id) ph ON ph.place_id = r.place_id
		WHERE r.user_id = :user_id AND r.status = 'approved'
		GROUP BY review_id
		ORDER BY r.pubdate DESC LIMIT :start, :limit";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':user_id', $profile_id);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// save id first
		$this_review_id = $row['review_id'];

		// then other review info
		$this_cat_slug   = !empty($row['cat_slug'  ]) ? $row['cat_slug'  ] : '';
		$this_dir        = !empty($row['dir'       ]) ? $row['dir'       ] : '';
		$this_filename   = !empty($row['filename'  ]) ? $row['filename'  ] : '';
		$this_place_id   = !empty($row['place_id'  ]) ? $row['place_id'  ] : '';
		$this_place_name = !empty($row['place_name']) ? $row['place_name'] : '';
		$this_place_slug = !empty($row['place_slug']) ? $row['place_slug'] : '';
		$this_pubdate    = !empty($row['pubdate'   ]) ? $row['pubdate'   ] : '';
		$this_rating     = !empty($row['rating'    ]) ? $row['rating'    ] : 0;
		$this_city_id    = !empty($row['city_id'   ]) ? $row['city_id'   ] : '';
		$this_city_name  = !empty($row['city_name' ]) ? $row['city_name' ] : '';
		$this_city_slug  = !empty($row['city_slug' ]) ? $row['city_slug' ] : '';
		$this_state_slug = !empty($row['state_slug']) ? $row['state_slug'] : '';
		$this_text       = !empty($row['text'      ]) ? $row['text'      ] : '';

		// sanitize
		$this_cat_slug   = e($this_cat_slug  );
		$this_dir        = e($this_dir       );
		$this_filename   = e($this_filename  );
		$this_place_name = e($this_place_name);
		$this_place_slug = e($this_place_slug);
		$this_pubdate    = e($this_pubdate   );
		$this_rating     = e($this_rating    );
		$this_city_id    = e($this_city_id   );
		$this_city_name  = e($this_city_name );
		$this_city_slug  = e($this_city_slug );
		$this_state_slug = e($this_state_slug);
		$this_text       = e($this_text      );

		// link to the place's page
		$this_link_url = get_listing_link($this_place_id, $this_place_slug, '', $this_cat_slug, $this_city_id, $this_city_slug, $this_state_slug, $cfg_permalink_struct);

		// thumb
		if(!empty($row['filename'])) {
			$this_thumb_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $this_dir . '/' . $this_filename;
		}

		else {
			$this_thumb_url = $baseurl . '/assets/imgs/blank.png';
		}

		$reviews[] = array(
			'link_url'         => $this_link_url,
			'place_id'         => $this_place_id,
			'place_name'       => $this_place_name,
			'pubdate'          => $this_pubdate,
			'rating'           => $this_rating,
			'city_id'          => $this_city_id,
			'review_city_name' => $this_city_name,
			'review_city_slug' => $this_city_slug,
			'review_id'        => $this_review_id,
			'text'             => $this_text,
			'thumb_url'        => $this_thumb_url,
			// legacy compatibility
			'review_city_id'   => $this_city_id,
		);
	}
}

/*--------------------------------------------------
Current visitor favorites array
--------------------------------------------------*/
$place_ids = array();
$favorites = array();

if(!empty($userid) && !empty($reviews)) {
	foreach($reviews as $v) {
		$place_ids[] = $v['place_id'];
	}

	$place_ids = implode(',', $place_ids);

	if(!empty($places_ids)) {
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

// init default
$profile_pic = $baseurl . '/assets/imgs/blank.png';

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