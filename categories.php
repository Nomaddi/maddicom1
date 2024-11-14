<?php
require_once(__DIR__ . '/inc/config.php');

/*--------------------------------------------------
$cats_arr - flat array with all cats
--------------------------------------------------*/
$cats_arr = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY parent_id, cat_order, name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if(!empty($city_id) && !empty($city_slug) && !empty($state_slug)) {
		$cat_link = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $row['cat_slug'];
	}

	else {
		$cat_link = $baseurl . '/listings/' . $row['cat_slug'];
	}

	// get translated cat name if user language cookie is set
	$cat_name = $row['name'];

	if(!empty($user_cookie_lang)) {
		$cat_name = cat_name_transl($row['id'], $user_cookie_lang, 'singular', $cat_name);
		$cat_plural_name = cat_name_transl($row['id'], $user_cookie_lang, 'plural', $cat_name);
	}

	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $cat_name,
		'cat_slug'    => $row['cat_slug'],
		'plural_name' => $cat_plural_name,
		'parent_id'   => $row['parent_id'],
		'cat_icon'    => $row['cat_icon'],
		'cat_link'    => $cat_link,
	);

	$cats_arr[$row['id']] = $cur_loop_arr;
}

/*--------------------------------------------------
$cat_tree
--------------------------------------------------*/

function buildTree($items) {
    $childs = array();

    foreach($items as &$item) {
		$childs[$item['parent_id']][] = &$item;
	}

    unset($item);

    foreach($items as &$item) {
		if (isset($childs[$item['cat_id']])) {
			$item['childs'] = $childs[$item['cat_id']];
		}
	}

    return $childs[0];
}

$cat_tree = buildTree($cats_arr);

/*--------------------------------------------------
get in str for $cat_tree
--------------------------------------------------*/

foreach($cat_tree as $k => $v) {
	// level
	$v['level'] = 0;

	// in str
	$v['in_str'] = $v['cat_id'];

	// add back to cat tree
	$cat_tree[$k] = $v;

	// loop children
	if(!empty($v['childs'])) {
		foreach($v['childs'] as $k2 => $v2) {
			// level 1
			$v2['level'] = 1;

			// in str
			$v['in_str'] .= ',' . $v2['cat_id'];
			$v2['in_str'] = $v2['cat_id'];

			// add to cat tree (order is important, first inner most, last top level)
			$v['childs'][$k2] = $v2;
			$cat_tree[$k] = $v;

			// loop children
			if(!empty($v2['childs'])) {
				foreach($v2['childs'] as $k3 => $v3) {
					// level 2
					$v3['level'] = 2;

					// in str
					$v['in_str'] .= ',' . $v3['cat_id'];
					$v2['in_str'] .= ',' . $v3['cat_id'];
					$v3['in_str'] = $v3['cat_id'];

					// add back to cat tree (order is important, first inner most, last top level)
					$v2['childs'][$k3] = $v3;
					$v['childs'][$k2] = $v2;
					$cat_tree[$k] = $v;
				}
			}
		}
	}
}

/*--------------------------------------------------
Get category listings count
--------------------------------------------------*/

$first_level_cats = array();
$second_level_cats = array();
$third_level_cats = array();

foreach($cat_tree as $k => $v) {
	$first_level_cats[$v['cat_id']] = array(
		'cat_id' => $v['cat_id'],
		'in_str' => $v['in_str'],
		);

	// loop children
	if(!empty($v['childs'])) {
		foreach($v['childs'] as $k2 => $v2) {
			$second_level_cats[$v2['cat_id']] = array(
				'cat_id' => $v2['cat_id'],
				'in_str' => $v2['in_str'],
				);

			// loop children
			if(!empty($v2['childs'])) {
				foreach($v2['childs'] as $k3 => $v3) {
					$third_level_cats[$v2['cat_id']] = array(
						'cat_id' => $v3['cat_id'],
						'in_str' => $v3['in_str'],
						);
				}
			}
		}
	}
}

// now separate categories between having children or no children
$cats_with_children = array();
$cats_without_children = array();

foreach($first_level_cats as $v) {
	if (strpos($v['in_str'], ',') !== false) {
		$cats_with_children[] = $v;
	}

	else {
		$cats_without_children[] = $v;
	}
}

foreach($second_level_cats as $v) {
	if (strpos($v['in_str'], ',') !== false) {
		$cats_with_children[] = $v;
	}

	else {
		$cats_without_children[] = $v;
	}
}

foreach($third_level_cats as $v) {
	$cats_without_children[] = $v;
}

// get category count for cats_without_children
$in_str = implode(',', array_column($cats_without_children, 'in_str'));

$query = "SELECT r.cat_id, COUNT(*) AS cat_count
		FROM rel_place_cat r
		INNER JOIN places p ON r.place_id = p.place_id
		WHERE p.status = 'approved' AND p.paid = 1 AND r.cat_id IN($in_str)
		GROUP BY cat_id";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if(!empty($cats_arr[$row['cat_id']])) {
		$cats_arr[$row['cat_id']]['cat_count'] = !empty($row['cat_count']) ? $row['cat_count'] : 0;
	}
}

// get category count for cats_with_children
foreach($cats_with_children as $v) {
	$in_str = $v['in_str'];
	$cat_id = $v['cat_id'];

	$query = "SELECT COUNT(*) AS cat_count
			FROM
				(SELECT r.cat_id, r.place_id
				FROM rel_place_cat r
				INNER JOIN places p ON r.place_id = p.place_id
				WHERE p.status = 'approved' AND p.paid = 1 AND r.cat_id IN($in_str)
				GROUP BY r.place_id) subq";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($cats_arr[$cat_id])) {
		$cats_arr[$cat_id]['cat_count'] = !empty($row['cat_count']) ? $row['cat_count'] : 0;
	}
}

/*--------------------------------------------------
Get total listings count
--------------------------------------------------*/

$query = "SELECT COUNT(*) AS c FROM places WHERE status = 'approved' AND paid = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$total_ads = $row['c'];

/*--------------------------------------------------
translation replacements
--------------------------------------------------*/
if(!empty($city_id)) {
	// case: city defined
	$txt_html_title_1  = str_replace('%city_name%' , $city_name , $txt_html_title_1);
	$txt_html_title_1  = str_replace('%state_abbr%', $state_abbr, $txt_html_title_1);
	$txt_meta_desc_1   = str_replace('%city_name%' , $city_name , $txt_meta_desc_1);
	$txt_meta_desc_1   = str_replace('%state_abbr%', $state_abbr, $txt_meta_desc_1);
	$txt_main_title_1  = str_replace('%city_name%' , $city_name , $txt_main_title_1);
	$txt_main_title_1  = str_replace('%state_abbr%', $state_abbr, $txt_main_title_1);
	$txt_all_cats_city = str_replace('%city_name%', $city_name, $txt_all_cats_city);

	$txt_html_title = $txt_html_title_1;
	$txt_meta_desc  = $txt_meta_desc_1;
	$txt_main_title = $txt_main_title_1;
	$txt_all_cats   = $txt_all_cats_city;
}

else {
	$txt_html_title = $txt_html_title_2;
	$txt_meta_desc  = $txt_meta_desc_2;
	$txt_main_title = $txt_main_title_2;
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/categories';

if(!empty($city_slug) && !empty($state_slug)) {
	$canonical .= '/' . $state_slug . '/' . $city_slug;
}
