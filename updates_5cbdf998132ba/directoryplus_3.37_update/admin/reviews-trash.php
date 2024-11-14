<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

// show
$show = !empty($_GET['show']) ? $_GET['show'] : 'all';

if(!in_array($show, array('pending', 'all'))) {
	$show = 'all';
}

// sort order (date, user, title)
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date-desc';

if(!in_array($sort, array('date', 'date-desc', 'user', 'user-desc', 'title', 'title-desc', ))) {
	$sort = 'date-desc';
}

// page url
$page_url = "$baseurl/admin/reviews-trash?sort=$sort&page=";

// init results array
$reviews_arr = array();

// count query
$query = "SELECT COUNT(*) AS c FROM reviews WHERE status = 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = "review_id DESC";

	if($sort == 'date') {
		$order_by = "review_id";
	}

	if($sort == 'user') {
		$order_by = "first_name";
	}

	if($sort == 'user-desc') {
		$order_by = "first_name DESC";
	}

	if($sort == 'title') {
		$order_by = "place_name";
	}

	if($sort == 'title-desc') {
		$order_by = "place_name DESC";
	}

	$query = "SELECT
		r.review_id, r.place_id, r.pubdate, r.rating, r.text, r.user_id, r.status,
		p.place_name, p.slug AS place_slug, p.logo,
		rel.cat_id,
		cats.id AS cat_id, cats.cat_slug,
		c.city_id, c.slug AS city_slug, c.city_name,
		s.slug AS state_slug,
		ph.dir, ph.filename,
		u.first_name, u.last_name
		FROM reviews r
		LEFT JOIN places p ON r.place_id = p.place_id
		LEFT JOIN rel_place_cat rel ON rel.place_id = r.place_id AND is_main = 1
		LEFT JOIN cats ON cats.id = rel.cat_id
		LEFT JOIN cities c ON p.city_id = c.city_id
		LEFT JOIN states s ON c.state_id = s.state_id
		LEFT JOIN (SELECT * FROM photos GROUP BY place_id) ph ON ph.place_id = r.place_id
		LEFT JOIN users u ON r.user_id = u.id
		WHERE r.status = 'trashed'
		GROUP BY review_id
		ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_review_id  = $row['review_id'];
		$this_cat_id     = !empty($row['cat_id'    ]) ? $row['cat_id'    ] : '';
		$this_cat_slug   = !empty($row['cat_slug'  ]) ? $row['cat_slug'  ] : $this_cat_id;
		$this_dir        = !empty($row['dir'       ]) ? $row['dir'       ] : null;
		$this_filename   = !empty($row['filename'  ]) ? $row['filename'  ] : null;
		$this_first_name = !empty($row['first_name']) ? $row['first_name'] : null;
		$this_last_name  = !empty($row['last_name' ]) ? $row['last_name' ] : null;
		$this_logo       = !empty($row['logo'      ]) ? $row['logo'      ] : '';
		$this_place_id   = !empty($row['place_id'  ]) ? $row['place_id'  ] : null;
		$this_place_name = !empty($row['place_name']) ? $row['place_name'] : '-';
		$this_place_slug = !empty($row['place_slug']) ? $row['place_slug'] : $this_place_id;
		$this_pubdate    = !empty($row['pubdate'   ]) ? $row['pubdate'   ] : '2016-03-18';
		$this_rating     = !empty($row['rating'    ]) ? $row['rating'    ] : 0;
		$this_city_id    = !empty($row['city_id'   ]) ? $row['city_id'   ] : null;
		$this_city_name  = !empty($row['city_name' ]) ? $row['city_name' ] : null;
		$this_city_slug  = !empty($row['city_slug' ]) ? $row['city_slug' ] : null;
		$this_state_slug = !empty($row['state_slug']) ? $row['state_slug'] : null;
		$this_status     = !empty($row['status'    ]) ? $row['status'    ] : null;
		$this_text       = !empty($row['text'      ]) ? $row['text'      ] : '';
		$this_user_id    = !empty($row['user_id'   ]) ? $row['user_id'   ] : '';

		// sanitize
		$this_cat_slug   = e($this_cat_slug  );
		$this_city_name  = e($this_city_name );
		$this_city_slug  = e($this_city_slug );
		$this_first_name = e($this_first_name);
		$this_last_name  = e($this_last_name );
		$this_logo       = e($this_logo      );
		$this_place_name = e($this_place_name);
		$this_place_slug = e($this_place_slug);
		$this_pubdate    = e($this_pubdate   );
		$this_rating     = e($this_rating    );
		$this_state_slug = e($this_state_slug);
		$this_status     = e($this_status    );
		$this_text       = e($this_text      );

		// simplify date
		$this_pubdate = strtotime($this_pubdate);
		$this_pubdate = date('Y-m-d', $this_pubdate);

		// author_name
		$this_author_name = "$this_first_name $this_last_name";

		// limit strings
		if (strlen($this_place_name) > 20) {
			$this_place_name = mb_substr($this_place_name, 0, 20) . '...';
		}

		if (mb_strlen($this_author_name) > 10) {
			$this_author_name = mb_substr($this_author_name, 0, 10) . '...';
		}

		// link to the place's page
		$this_link_url = get_listing_link(
							$this_place_id,
							$this_place_slug,
							$this_cat_id,
							$this_cat_slug,
							'',
							$this_city_slug,
							$this_state_slug,
							$cfg_permalink_struct);

		// logo url
		$this_logo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($this_logo)) {
			if(is_file($pic_basepath . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo)) {
				$this_logo_url = $pic_baseurl . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo;
			}
		}

		// user profile pic
		$folder = floor($this_user_id / 1000) + 1;

		if(strlen($folder) < 1) {
			$folder = '999';
		}

		// profile pic path
		$this_pic_path = $pic_basepath . '/' . $profile_full_folder . '/' . $folder . '/' . $this_user_id;

		// check if file exists
		$this_pic_glob_arr = glob("$this_pic_path.*");

		if(!empty($this_pic_glob_arr)) {
			$this_prof_pic_filename = basename($this_pic_glob_arr[0]);

			// set first match as profile pic
			$this_prof_pic_url = $pic_baseurl . '/' . $profile_full_folder . '/' . $folder . '/' . $this_prof_pic_filename;

			// set first match as profile pic
			$this_prof_thumb_url = $pic_baseurl . '/' . $profile_thumb_folder . '/' . $folder . '/' . $this_prof_pic_filename;
		}

		else {
			$this_prof_pic_url = $baseurl . '/assets/imgs/blank.png';
			$this_prof_thumb_url = $baseurl . '/assets/imgs/blank.png';
		}

		$cur_arr = array(
					'review_id'        => $this_review_id,
					'author_name'      => $this_author_name,
					'author_pic_url'   => $this_prof_thumb_url,
					'link_url'         => $this_link_url,
					'logo_url'         => $this_logo_url,
					'place_id'         => $this_place_id,
					'place_name'       => $this_place_name,
					'pubdate'          => $this_pubdate,
					'rating'           => $this_rating,
					'review_city_id'   => $this_city_id,
					'review_city_name' => $this_city_name,
					'review_city_slug' => $this_city_slug,
					'status'           => $this_status,
					'text'             => $this_text,
					);

		$reviews_arr[] = $cur_arr;
	}
}