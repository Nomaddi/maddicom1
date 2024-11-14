<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// cat details
$params = array();
parse_str($_POST['params'], $params);

$cat_name     = !empty($params['cat_name'    ]) ? $params['cat_name'    ] : '';
$plural_name  = !empty($params['plural_name' ]) ? $params['plural_name' ] : '';
$cat_slug     = !empty($params['cat_slug'    ]) ? $params['cat_slug'    ] : '';
$cat_icon     = !empty($params['cat_icon'    ]) ? $params['cat_icon'    ] : '';
$cat_bg       = !empty($params['cat_bg'      ]) ? $params['cat_bg'      ] : '';
$cat_order    = !empty($params['cat_order'   ]) ? $params['cat_order'   ] : 0;
$cat_parent   = !empty($params['cat_parent'  ]) ? $params['cat_parent'  ] : 0;
$uploaded_img = !empty($params['uploaded_img']) ? $params['uploaded_img'] : '';

// trim
$cat_name     = trim($cat_name);
$plural_name  = trim($plural_name);
$cat_slug     = trim($cat_slug);
$cat_icon     = trim($cat_icon);
$cat_bg       = trim($cat_bg);
$cat_order    = trim($cat_order);
$cat_parent   = trim($cat_parent);
$uploaded_img = trim($uploaded_img);

// prepare vars
$cat_order  = is_numeric($cat_order)  ? $cat_order  : 0;
$cat_parent = is_numeric($cat_parent) ? $cat_parent : 0;

// cat slug
if(empty($cat_slug)) {
	$cat_slug = to_slug($cat_name);
}

if(!empty($cat_slug)) {
	// make sure cat_slug is unique
	$is_slug_unique = false;
	$count = 2;
	$new_cat_slug = $cat_slug;

	while(!$is_slug_unique) {
		$query = "SELECT COUNT(*) AS total_rows FROM cats WHERE cat_slug = :cat_slug";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':cat_slug', $new_cat_slug);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($row['total_rows'] == 0) {
			$is_slug_unique = true;
		}

		else {
			$new_cat_slug = $cat_slug . '-' . $count;
		}

		$count++;
	}

	// insert into db
	$query = "INSERT INTO cats(
				name,
				plural_name,
				cat_slug,
				parent_id,
				cat_icon,
				cat_bg,
				cat_order)
			VALUES(
				:cat_name,
				:plural_name,
				:cat_slug,
				:cat_parent,
				:cat_icon,
				:cat_bg,
				:cat_order)";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_name'   , $cat_name);
	$stmt->bindValue(':plural_name', $plural_name);
	$stmt->bindValue(':cat_slug'   , $new_cat_slug);
	$stmt->bindValue(':cat_parent' , $cat_parent);
	$stmt->bindValue(':cat_icon'   , $cat_icon);
	$stmt->bindValue(':cat_bg'     , $cat_bg);
	$stmt->bindValue(':cat_order'  , $cat_order);

	if($stmt->execute()) {
		// add to sitemap
		if($cfg_enable_sitemaps) {
			$cat_link = $baseurl . '/'. $route_listings . '/' . $new_cat_slug;
			sitemap_add_url($cat_link);
		}

		$cat_id = $conn->lastInsertId();
	}

	/*--------------------------------------------------
	Category image
	--------------------------------------------------*/

	// get extension of uploaded image
	if(!empty($uploaded_img)) {
		$img_tmp = $pic_basepath . '/category-tmp/' . $uploaded_img;
		$path_parts = pathinfo($img_tmp);
		$img_ext = $path_parts['extension'];

		// final destination
		$img_final = $pic_basepath . '/category/cat-' . $cat_id . '.' . $img_ext;

		if(is_file($img_tmp)) {
			if(copy($img_tmp, $img_final)) {
				unlink($img_tmp);
			}
		}
	}
}

echo '1';