<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// search term
$s = !empty($_GET['s']) ? $_GET['s'] : '';
$s = e($s);

// category
$cat_id = !empty($_GET['cat']) ? $_GET['cat'] : 0;
$cat_id = e($cat_id);

// sort order
$sort = '';
if(isset($_GET['sort'])) {
	$sort = in_array($_GET['sort'], array('date', 'date-desc', 'title', 'title-desc')) ? $_GET['sort'] :  'date-desc';
}

// show filter
$show = '';
if(isset($_GET['show'])) {
	$show = in_array($_GET['show'], array('approved', 'pending', 'feat', 'feat-home', 'all')) ? $_GET['show'] :  'all';
}

/*--------------------------------------------------
Pagination
--------------------------------------------------*/

$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

/*--------------------------------------------------
Page url
--------------------------------------------------*/

$page_url = "$baseurl/admin/listings?";

if(!empty($s)) {
	$page_url .= "s=$s&";
}

$page_url .= "show=$show&sort=$sort&cat=$cat_id&page=";

/*--------------------------------------------------
Count query
--------------------------------------------------*/

// default WHERE clause
$where = "WHERE p.status <> 'trashed'";

// WHERE clause by $show value
if($show == 'approved') {
	$where = "WHERE p.status = 'approved'";
}

if($show == 'pending') {
	$where = "WHERE p.status = 'pending'";
}

if($show == 'feat') {
	$where = "WHERE p.feat = 1 AND p.status <> 'trashed'";
}

if($show == 'feat-home') {
	$where = "WHERE p.feat_home = 1 AND p.status <> 'trashed'";
}

// append search term to WHERE clause
if(!empty($s)) {
	$where .= " AND MATCH(place_name, description, short_desc) AGAINST (:s)";
}

// append category id to WHERE clause if exists
if(!empty($cat_id)) {
	$where .= " AND rel.cat_id = :cat_id";
}

// the count query
$query = "SELECT COUNT(*) AS total_rows FROM places p $where";

// if cat id
if(!empty($cat_id)) {
	$query = "SELECT COUNT(*) AS total_rows
				FROM places p
				LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
				$where";
}

// execute count query
$stmt = $conn->prepare($query);

if(!empty($s)) {
	$stmt->bindValue(':s', $s);
}

if(!empty($cat_id)) {
	$stmt->bindValue(':cat_id', $cat_id);
}

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];

/*--------------------------------------------------
Main query
--------------------------------------------------*/

// init results array
$places_arr = array();

// if(empty($s)) {
if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// order by
	$orderby = "p.place_id DESC";
	$where = "WHERE p.status <> 'trashed'";

	if($sort == 'date') {
		$orderby = "p.place_id";
	}

	if($sort == 'title') {
		$orderby = "p.place_name";
	}

	if($sort == 'title-desc') {
		$orderby = "p.place_name DESC";
	}

	// where
	if($show == 'approved') {
		$where = "WHERE p.status = 'approved'";
	}

	if($show == 'pending') {
		$where = "WHERE p.status = 'pending'";
	}

	if($show == 'feat') {
		$where = "WHERE p.feat = 1";
	}

	if($show == 'feat-home') {
		$where = "WHERE p.feat_home = 1";
	}

	if(!empty($s)) {
		$where .= " AND MATCH(place_name, description, short_desc) AGAINST (:s IN BOOLEAN MODE)";
	}

	if(!empty($cat_id)) {
		$where .= " AND rel.cat_id = :cat_id";
	}

	// the query
	$query = "SELECT
			p.place_id, p.place_name, p.submission_date, p.feat_home, p.status, p.paid, p.userid, p.slug AS place_slug, p.logo,
			c.city_name, c.slug, c.state, c.city_id,
			s.state_name, s.slug AS state_slug,
			cats.id AS cat_id, cats.name AS cat_name, cats.cat_slug,
			u.email,
			plans.plan_name
		FROM places p
			LEFT JOIN cities c ON p.city_id = c.city_id
			LEFT JOIN states s ON c.state_id = s.state_id
			LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id AND rel.is_main = 1
			LEFT JOIN cats ON rel.cat_id = cats.id
			LEFT JOIN users u ON u.id = p.userid
			LEFT JOIN plans ON plans.plan_id = p.plan
		$where
		ORDER BY $orderby LIMIT :start, :limit";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);

	if(!empty($cat_id)) {
		$stmt->bindValue(':cat_id', $cat_id);
	}

	if(!empty($s)) {
		$stmt->bindValue(':s', $s);
	}

	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id          = !empty($row['cat_id'         ]) ? $row['cat_id'         ] : '';
		$this_cat_name        = !empty($row['cat_name'       ]) ? $row['cat_name'       ] : '';
		$this_cat_slug        = !empty($row['cat_slug'       ]) ? $row['cat_slug'       ] : $this_cat_id;
		$this_city_id         = !empty($row['city_id'        ]) ? $row['city_id'        ] : '';
		$this_city_name       = !empty($row['city_name'      ]) ? $row['city_name'      ] : '';
		$this_city_slug       = !empty($row['slug'           ]) ? $row['slug'           ] : '';
		$this_feat_home       = !empty($row['feat_home'      ]) ? $row['feat_home'      ] : 0;
		$this_logo            = !empty($row['logo'           ]) ? $row['logo'           ] : '';
		$this_paid            = !empty($row['paid'           ]) ? $row['paid'           ] : 0;
		$this_place_email     = !empty($row['email'          ]) ? $row['email'          ] : '';
		$this_place_id        = !empty($row['place_id'       ]) ? $row['place_id'       ] : '';
		$this_place_name      = !empty($row['place_name'     ]) ? $row['place_name'     ] : '';
		$this_place_owner     = !empty($row['userid'         ]) ? $row['userid'         ] : '';
		$this_place_slug      = !empty($row['place_slug'     ]) ? $row['place_slug'     ] : $this_place_id;
		$this_plan_name       = !empty($row['plan_name'      ]) ? $row['plan_name'      ] : '';
		$this_state_abbr      = !empty($row['state'          ]) ? $row['state'          ] : '';
		$this_state_slug      = !empty($row['state_slug'     ]) ? $row['state_slug'     ] : '';
		$this_status          = !empty($row['status'         ]) ? $row['status'         ] : '';
		$this_submission_date = !empty($row['submission_date']) ? $row['submission_date'] : '';

		// sanitize
		$this_cat_id          = e($this_cat_id         );
		$this_cat_name        = e($this_cat_name       );
		$this_cat_slug        = e($this_cat_slug       );
		$this_city_id         = e($this_city_id        );
		$this_city_name       = e($this_city_name      );
		$this_city_slug       = e($this_city_slug      );
		$this_feat_home       = e($this_feat_home      );
		$this_logo            = e($this_logo           );
		$this_paid            = e($this_paid           );
		$this_place_email     = e($this_place_email    );
		$this_place_id        = e($this_place_id       );
		$this_place_name      = e($this_place_name     );
		$this_place_owner     = e($this_place_owner    );
		$this_place_slug      = e($this_place_slug     );
		$this_plan_name       = e($this_plan_name      );
		$this_state_abbr      = e($this_state_abbr     );
		$this_state_slug      = e($this_state_slug     );
		$this_status          = e($this_status         );
		$this_submission_date = e($this_submission_date);

		// simplify date
		$this_submission_date = strtotime($this_submission_date);
		$this_date_formatted  = date($cfg_date_format, $this_submission_date);

		// link to each place
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

		$cur_loop_arr = array(
			'cat_id'         => $this_cat_id,
			'cat_name'       => $this_cat_name,
			'cat_slug'       => $this_cat_slug,
			'city_id'        => $this_city_id,
			'city_name'      => $this_city_name,
			'city_slug'      => $this_city_slug,
			'date_formatted' => $this_date_formatted,
			'feat_home'      => $this_feat_home,
			'link_url'       => $this_link_url,
			'logo_url'       => $this_logo_url,
			'paid'           => $this_paid,
			'place_email'    => $this_place_email,
			'place_id'       => $this_place_id,
			'place_name'     => $this_place_name,
			'place_owner'    => $this_place_owner,
			'place_slug'     => $this_place_slug,
			'plan_name'      => $this_plan_name,
			'state_abbr'     => $this_state_abbr,
			'state_slug'     => $this_state_slug,
			'status'         => $this_status,
		);

		$places_arr[] = $cur_loop_arr;
	}
}
