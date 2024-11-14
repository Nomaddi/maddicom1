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

// sort order
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date-desc';

if(!in_array($sort, array('date', 'date-desc', 'title', 'title-desc', 'expire', 'expire-desc'))) {
	$sort = 'expire';
}

// page url
$page_url = "$baseurl/admin/coupons?sort=$sort&page=";

// init
$coupons_arr = array();

// get coupons
$query = "SELECT COUNT(*) AS c FROM coupons WHERE coupon_status > -1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = 'expire';

	if($sort == 'expire-desc') {
		$order_by = "expire DESC";
	}

	if($sort == 'date') {
		$order_by = "id";
	}

	if($sort == 'date-desc') {
		$order_by = "id DESC";
	}

	if($sort == 'title') {
		$order_by = "title";
	}

	if($sort == 'title-desc') {
		$order_by = "title DESC";
	}

	$query = "SELECT c.*,
				p.place_name
				FROM coupons c
				LEFT JOIN places p ON c.place_id = p.place_id
				WHERE coupon_status > -1 ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	// if this user has coupons
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_coupon_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$this_coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
		$this_coupon_description = !empty($row['description']) ? $row['description'] : '';
		$this_coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$this_coupon_created     = !empty($row['created'    ]) ? $row['created'    ] : '';
		$this_coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : '';
		$this_coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';
		$this_place_name         = !empty($row['place_name' ]) ? $row['place_name' ] : '';

		// sanitize
		$this_coupon_id          = e($this_coupon_id         );
		$this_coupon_title       = e($this_coupon_title      );
		$this_coupon_description = e($this_coupon_description);
		$this_coupon_place_id    = e($this_coupon_place_id   );
		$this_coupon_created     = e($this_coupon_created    );
		$this_coupon_expire      = e($this_coupon_expire     );
		$this_coupon_img         = e($this_coupon_img        );
		$this_place_name         = e($this_place_name        );

		// format datetime to date
		$this_coupon_expire = new DateTime($this_coupon_expire);
		$this_coupon_expire = $this_coupon_expire->format("Y-m-d");
		$this_coupon_created = new DateTime($this_coupon_created);
		$this_coupon_created = $this_coupon_created->format("Y-m-d");

		// photo_url
		$this_coupon_img_url = '';
		if(!empty($this_coupon_img)) {
			$this_coupon_img_url = $baseurl . '/pictures/coupons/' . substr($this_coupon_img, 0, 2) . '/' . $this_coupon_img;
		}

		else {
			$this_coupon_img_url = $baseurl . '/assets/imgs/blank.png';
		}

		// description
		if(!empty($this_coupon_description)) {
			$this_coupon_description = mb_substr($this_coupon_description, 0, 75) . '...';
		}

		// link
		$this_coupon_link = $baseurl . '/coupon/' . $this_coupon_id;

		$cur_loop_arr = array(
						'coupon_id'          => $this_coupon_id,
						'coupon_title'       => $this_coupon_title,
						'coupon_description' => $this_coupon_description,
						'coupon_place_id'    => $this_coupon_place_id,
						'coupon_created'     => $this_coupon_created,
						'coupon_expire'      => $this_coupon_expire,
						'coupon_img'         => $this_coupon_img_url,
						'coupon_link'        => $this_coupon_link,
						'place_name'         => $this_place_name,
						);

		// add cur loop to places array
		$coupons_arr[] = $cur_loop_arr;
	}
}
