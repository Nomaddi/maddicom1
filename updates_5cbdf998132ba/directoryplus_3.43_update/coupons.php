<?php
require_once(__DIR__ . '/inc/config.php');

// coupons enabled check
if(!$cfg_enable_coupons) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

$page_url = "$baseurl/coupons?page=";

/*--------------------------------------------------
Get list of coupons for this page
--------------------------------------------------*/

// init
$coupons_arr = array();
$coupon_create = false;

// count total coupons
$query = "SELECT COUNT(*) AS c FROM coupons WHERE CURDATE() < expire AND coupon_status > 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT * FROM coupons WHERE CURDATE() < expire AND coupon_status > 0 LIMIT :start, :items_per_page";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':items_per_page', $items_per_page);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$coupon_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
		$coupon_description = !empty($row['description']) ? $row['description'] : '';
		$coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : '2100-01-01 00:00:00';
		$coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';

		// sanitize
		$coupon_id          = e($coupon_id         );
		$coupon_title       = e($coupon_title      );
		$coupon_description = e($coupon_description);
		$coupon_place_id    = e($coupon_place_id   );
		$coupon_expire      = e($coupon_expire     );
		$coupon_img         = e($coupon_img        );

		// format expire date
		$coupon_expire = new DateTime($coupon_expire);
		$coupon_expire = $coupon_expire->format($cfg_date_format);

		// social media links
		$twitter_link = 'https://twitter.com/intent/tweet';
		$twitter_link.= '?text=' . rawurlencode($coupon_title);
		$twitter_link.= '&url=' . rawurlencode("$baseurl/coupons/$coupon_id");

		$mail_body =  rawurlencode($coupon_description) . '%0D%0A' . rawurlencode("$baseurl/coupons/$coupon_id");
		$mailto_link = 'mailto:?subject=' . rawurlencode($coupon_title) . '&body=' . $mail_body;

		$facebook_link = 'https://www.facebook.com/sharer/sharer.php';
		$facebook_link.= '?u=' . rawurlencode("$baseurl/coupons/$coupon_id");

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

		// sanitize
		$coupon_title = e($coupon_title);
		$coupon_description = e($coupon_description);

		$cur_loop_arr = array(
						'coupon_id'          => $coupon_id,
						'coupon_title'       => $coupon_title,
						'coupon_description' => $coupon_description,
						'coupon_place_id'    => $coupon_place_id,
						'coupon_expire'      => $coupon_expire,
						'coupon_img'         => $coupon_img_url,
						'twitter_link'       => $twitter_link,
						'facebook_link'      => $facebook_link,
						'mailto_link'        => $mailto_link,
						);

		// add cur loop to places array
		$coupons_arr[] = $cur_loop_arr;
	}
}

/*--------------------------------------------------
Canonical
--------------------------------------------------*/
if(isset($pager) && $page > $pager->getTotalPages()) {
	header("Location: $baseurl/coupons");
}

if(isset($route[1])) {
	if($route[1] == 'page' && isset($route[2]) && ctype_digit($route[2])) {
		$canonical = $baseurl . '/coupons/page/' . $route[2];
	}
}

else {
	$canonical = $baseurl . '/coupons';
}