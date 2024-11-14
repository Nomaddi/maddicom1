<?php
require_once(__DIR__ . '/inc/config.php');

// validate url
if(!ctype_digit($route[1])) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

// coupon id
$coupon_id = $route[1];

// get coupon details
$query = "SELECT *, IF(CURDATE() < expire, 'valid', 'expired') AS valid FROM coupons WHERE id = :coupon_id AND coupon_status > 0";
$stmt = $conn->prepare($query);
$stmt->bindValue(':coupon_id', $coupon_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(empty($row)) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

$coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
$coupon_description = !empty($row['description']) ? $row['description'] : '';
$coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
$coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : '';
$coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';
$coupon_valid       = !empty($row['valid'      ]) ? $row['valid'      ] : 'expired';

// sanitize
$coupon_title       = e($coupon_title      );
$coupon_description = e($coupon_description);
$coupon_place_id    = e($coupon_place_id   );
$coupon_expire      = e($coupon_expire     );
$coupon_img         = e($coupon_img        );
$coupon_valid       = e($coupon_valid      );

// format expire data
$coupon_expire = new DateTime($coupon_expire);
$coupon_expire = $coupon_expire->format("Y-m-d");

// coupon image
$coupon_img_url = $baseurl . '/assets/imgs/blank.png';

if(!empty($coupon_img)) {
	$coupon_folder = substr($coupon_img, 0, 2);
	$coupon_img_url = $baseurl . '/pictures/coupons/' . $coupon_folder . '/' . $coupon_img;
}

// get city details to build place link
$query = "SELECT  p.slug AS place_slug, p.place_name, p.place_id, p.status, p.paid,
			c.slug AS city_slug,
			s.slug AS state_slug,
			cats.cat_slug, cats.id AS cat_id
			FROM places p
			LEFT JOIN cities c ON p.city_id = c.city_id
			LEFT JOIN states s ON c.state_id = s.state_id
			LEFT JOIN rel_place_cat r ON r.place_id = p.place_id AND r.is_main = 1
			LEFT JOIN cats ON cats.id = r.cat_id
			WHERE p.place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $coupon_place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$city_slug    = !empty($row['city_slug' ]) ? $row['city_slug' ] : 'location';
$state_slug   = !empty($row['state_slug']) ? $row['state_slug'] : '';
$cat_id       = !empty($row['cat_id'    ]) ? $row['cat_id'    ] : '';
$cat_slug     = !empty($row['cat_slug'  ]) ? $row['cat_slug'  ] : $cat_id;
$place_id     = !empty($row['place_id'  ]) ? $row['place_id'  ] : '';
$place_slug   = !empty($row['place_slug']) ? $row['place_slug'] : 'business-name';
$place_name   = !empty($row['place_name']) ? $row['place_name'] : $place_id;
$place_status = !empty($row['status'    ]) ? $row['status'    ] : '';
$place_paid   = !empty($row['paid'      ]) ? $row['paid'      ] : '';

if(!empty($place_id) && $place_paid == 1 && $place_status == 'approved') {
	$place_link = get_listing_link($place_id, $place_slug, $cat_id, $cat_slug, '', $city_slug, $state_slug, $cfg_permalink_struct);
}

else {
	$place_link = '';
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/coupon/' . $coupon_id;
