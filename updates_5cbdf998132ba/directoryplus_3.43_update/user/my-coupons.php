<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// coupons enabled check
if(!$cfg_enable_coupons) {
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

$page_url = "$baseurl/user/my-coupons?page=";

// get array of places owned by this user
$query = "SELECT place_id, place_name FROM places WHERE userid = :userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();

$user_places = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$place_id   = !empty($row['place_id'  ]) ? $row['place_id'  ] : '';
	$place_name = !empty($row['place_name']) ? $row['place_name'] : '';

	// sanitize
	$place_name = e($place_name);

	if(!empty($place_id) && !empty($place_name)) {
		$cur_loop_arr = array(
						'place_id' => $place_id,
						'place_name' => $place_name,
						);

		$user_places[] = $cur_loop_arr;
	}
}

// get coupons
$query = "SELECT COUNT(*) AS c FROM coupons WHERE userid = :userid AND coupon_status > -1";
$stmt = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// cur date
	$now = new DateTime();
	$now = $now->format($cfg_date_format);

	$query = "SELECT * FROM coupons WHERE userid = :userid AND coupon_status > -1 ORDER BY id DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	$coupons_arr = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$coupon_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
		$coupon_description = !empty($row['description']) ? $row['description'] : '';
		$coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : $now;
		$coupon_created     = !empty($row['created'    ]) ? $row['created'    ] : $now;
		$coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';

		// sanitize
		$coupon_id          = e($coupon_id         );
		$coupon_title       = e($coupon_title      );
		$coupon_description = e($coupon_description);
		$coupon_place_id    = e($coupon_place_id   );
		$coupon_expire      = e($coupon_expire     );
		$coupon_created     = e($coupon_created    );
		$coupon_img         = e($coupon_img        );

		// check if coupon expired
		if($now > $coupon_expire) {
			$coupon_expire = 'Expired';
		}

		else {
			// format coupon expire
			$date = new DateTime($coupon_expire);
			$coupon_expire = $date->format('Y-m-d');
		}

		// photo_url
		$coupon_img_url = '';
		if(!empty($coupon_img)) {
			$coupon_img_url = $baseurl . '/pictures/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;
		}

		else {
			$coupon_img_url = $baseurl . '/assets/imgs/blank.png';
		}

		// description
		if(!empty($coupon_description)) {
			$coupon_description = mb_substr($coupon_description, 0, 75) . '...';
		}

		$cur_loop_arr = array(
						'coupon_id'          => $coupon_id,
						'coupon_title'       => $coupon_title,
						'coupon_description' => $coupon_description,
						'coupon_place_id'    => $coupon_place_id,
						'coupon_expire'      => $coupon_expire,
						'coupon_created'     => $coupon_created,
						'coupon_img'         => $coupon_img_url
						);

		// add cur loop to places array
		$coupons_arr[] = $cur_loop_arr;
	}
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/my-coupons';