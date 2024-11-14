<?php
require_once(__DIR__ . '/inc/config.php');
include(__DIR__ . '/inc/country-calling-codes.php');

// debug switch
if(!isset($debug)) {
	$debug = false;
}

if($debug) {
	// pad from navbar
	echo '<br><br><br><br><br><br>';
}

// sanitize $_GET
foreach($_GET as $k => $v) {
	if(!is_array($v)) {
		$_GET[$k] = e($v);
	}

	else {
		foreach($v as $k2 => $v2) {
			$_GET[$k][$k2] = e($v2);
		}
	}
}

// debug
if($debug) {
	echo '<h2>$_GET (after sanitizing)</h2>';
	print_r2($_GET);
}

/*--------------------------------------------------
URL vars
--------------------------------------------------*/
$cat_id     = !empty($_GET['cat_id' ]) ? $_GET['cat_id' ] : 0;
$page       = !empty($_GET['page'   ]) ? $_GET['page'   ] : 1;
$user_query = !empty($_GET['s'      ]) ? $_GET['s'      ] : '';
$city_id    = !empty($_GET['city'   ]) ? $_GET['city'   ] : 0;
$state_id   = !empty($_GET['state'  ]) ? $_GET['state'  ] : 0;
$country_id = !empty($_GET['country']) ? $_GET['country'] : 0;

// define loc type
$loc_type = 'n';

if(!empty($state_id)) {
	$loc_type = 's';
}

if(!empty($city_id)) {
	$loc_type = 'c';
}

// clear values depending on loc type
$loc_id = 0;

if($loc_type == 'c') {
	$state_id = '';
	$country_id = '';
	$loc_id = $city_id;
}

if($loc_type == 's') {
	$city_id = '';
	$country_id = '';
	$loc_id = $state_id;
}

if($loc_type == 'n') {
	$city_id = '';
	$state_id = '';
	$loc_id = $country_id;
}

// show loc type debug
if($debug) {
	echo '<h2>$loc_type</h2>';
	echo $loc_type;

	echo '<h2>$loc_id</h2>';
	echo $loc_id;
}

// check if keyword is *
if($user_query == '*') {
	$user_query = '';
}

// append *
$query_query = explode(' ', $user_query);
$new_query = '';

foreach($query_query as $v) {
	if(!empty($v)) {
		$new_query .= "$v* ";
	}
}

$q = $new_query;

/*--------------------------------------------------
validate vars
--------------------------------------------------*/
if(!is_numeric($cat_id) || !is_numeric($page)) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

/*--------------------------------------------------
Sort
--------------------------------------------------*/
$sort = '';

if(!empty($_GET['sort'])) {
	if($_GET['sort'] == 'date-asc') {
		$sort = 'p.place_id ASC,';
	}

	if($_GET['sort'] == 'date-desc') {
		$sort = 'p.place_id DESC,';
	}
}

/*--------------------------------------------------
city details
--------------------------------------------------*/
$query_city_id = $city_id;
$query_city_name = '';
$query_state_abbr = '';

if(!empty($query_city_id)) {
	$query = "SELECT city_name, state FROM cities WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $query_city_id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$query_city_name = $row['city_name'];
	$query_state_abbr = $row['state'];
}

if(!empty($query_city_id) && !empty($query_city_name) && !empty($query_state_abbr)) {
	$_SESSION['search_city_id'] = $query_city_id;
	$_SESSION['search_state_abbr'] = $query_state_abbr;
	$_SESSION['search_city_name'] = $query_city_name;
}

else {
	unset($_SESSION['search_city_id']);
	unset($_SESSION['search_state_abbr']);
	unset($_SESSION['search_city_name']);
}

// set $city_name and $state_abbr (because tpl-results uses these vars)
$city_name = $query_city_name;
$state_abbr = $query_state_abbr;

/*--------------------------------------------------
Cats path array (an array of category ids used for breadcrumbs, etc)
--------------------------------------------------*/

if($cat_id != 0) {
	$cats_path = get_parent($cat_id, array(), $conn);
	$cats_path = array_reverse($cats_path);
}

else {
	$cats_path = array();
}

/*--------------------------------------------------
Current category info
--------------------------------------------------*/

// current cat parent id
$cur_cat_parent_id = 0;

if($cat_id != 0) {
	$query = "SELECT * FROM cats WHERE id = :cat_id";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$cur_cat_name        = !empty($row['name'       ]) ? $row['name'       ] : '';
	$cur_cat_slug        = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$cur_cat_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $cur_cat_name;
	$cur_cat_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
	$cur_cat_icon        = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
	$cur_cat_order       = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
	$cur_cat_bg          = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cur_cat_name = cat_name_transl($cat_id , $user_cookie_lang, 'singular', $cur_cat_name);
		$cur_cat_plural_name = cat_name_transl($cat_id , $user_cookie_lang, 'plural', $cur_cat_plural_name);
	}
}

/*--------------------------------------------------
Top level cats and all cats
--------------------------------------------------*/

// init cats
$all_cats = array();
$top_level_cats = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY cat_order";
$stmt  = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name        = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_cat_slug        = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$this_cat_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $this_cat_name ;
	$this_cat_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
	$this_cat_icon        = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
	$this_cat_order       = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
	$this_cat_bg          = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
		$this_cat_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_cat_plural_name);
	}

	// cat link
	$this_cat_link = $baseurl . '/results?cat_id=' . $this_cat_id;

	// if city id exists
	if(!empty($city_id)) {
		$this_cat_link .= '&city=' . $city_id;
	}

	// all cats array
	$all_cats[$this_cat_id] = array(
		'cat_id'      => $this_cat_id,
		'cat_name'    => $this_cat_name,
		'cat_plural'  => $this_cat_plural_name,
		'cat_slug'    => $this_cat_slug,
		'cat_icon'    => $this_cat_icon,
		'cat_order'   => $this_cat_order,
		'cat_bg'      => $this_cat_bg,
		'cat_link'    => $this_cat_link,
		'parent_id'   => $this_cat_parent_id,
	);

	// top level cats array
	if($this_cat_parent_id == 0) {
		$top_level_cats[$this_cat_id] = array(
			'cat_id'     => $this_cat_id,
			'cat_name'   => $this_cat_name,
			'cat_plural' => $this_cat_plural_name,
			'cat_slug'   => $this_cat_slug,
			'cat_icon'   => $this_cat_icon,
			'cat_order'  => $this_cat_order,
			'cat_bg'     => $this_cat_bg,
			'cat_link'   => $this_cat_link,
		);
	}
}

/*--------------------------------------------------
Current category's siblings
--------------------------------------------------*/

$cur_cat_siblings = array();

if($cur_cat_parent_id != 0) {
	$query = "SELECT * FROM cats WHERE parent_id = :parent_id AND cat_status = 1";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':parent_id', $cur_cat_parent_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$this_cat_name        = !empty($row['name'       ]) ? $row['name'       ] : '';
		$this_cat_slug        = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
		$this_cat_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $this_cat_name ;
		$this_cat_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
		$this_cat_icon        = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
		$this_cat_order       = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
		$this_cat_bg          = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
			$this_cat_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_cat_plural_name);
		}

		// cat link
		$this_cat_link = $baseurl . '/results?cat_id=' . $this_cat_id;

		// if city id exists
		if(!empty($city_id)) {
			$this_cat_link .= '&city=' . $city_id;
		}

		$cur_cat_siblings[] = array(
			'cat_id'     => $this_cat_id,
			'cat_name'   => $this_cat_name,
			'cat_plural' => $this_cat_plural_name,
			'cat_slug'   => $this_cat_slug,
			'cat_icon'   => $this_cat_icon,
			'cat_order'  => $this_cat_order,
			'cat_bg'     => $this_cat_bg,
			'cat_link'   => $this_cat_link,
		);
	}
}

// get top level category for current cat
$cur_cat_top_level_parent = isset($cats_path[0]) ? $cats_path[0] : '';

/*--------------------------------------------------
Current category's children
--------------------------------------------------*/

$cur_cat_children = array();

$query = "SELECT * FROM cats WHERE parent_id = :cat_id AND cat_status = 1";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name        = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_cat_slug        = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$this_cat_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $this_cat_name ;
	$this_cat_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
	$this_cat_icon        = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
	$this_cat_order       = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
	$this_cat_bg          = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
		$this_cat_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_cat_plural_name);
	}

	// cat link
	$this_cat_link = $baseurl . '/results?cat_id=' . $this_cat_id;

	// if city id exists
	if(!empty($city_id)) {
		$this_cat_link .= '&city=' . $city_id;
	}

	$cur_cat_children[] = array(
		'cat_id'     => $this_cat_id,
		'cat_name'   => $this_cat_name,
		'cat_plural' => $this_cat_plural_name,
		'cat_slug'   => $this_cat_slug,
		'cat_icon'   => $this_cat_icon,
		'cat_order'  => $this_cat_order,
		'cat_bg'     => $this_cat_bg,
		'cat_link'   => $this_cat_link,
	);
}

/*--------------------------------------------------
Create custom fields array from $_GET vars
--------------------------------------------------*/

$custom_fields = array();

foreach($_GET as $k => $v) {
	// if needle 'field_' is found
	if(strpos($k, 'field_') !== false && !empty($v)) {
		// sanitize
		if(is_numeric(str_replace('field_', '', $k))) {
			$this_id    = str_replace('field_', '', $k);
			$this_value = '';

			// if $v is an array, remove empty elements (this is needed because when building WHERE clause for custom fields, empty values can generate SQL syntax error
			if(!is_array($v)) {
				$this_value = e($v);
			}

			else {
				foreach($v as $k2 => $v2) {
					if(empty($v2)) {
						unset($v[$k2]);
					}
				}

				$this_value = $v;
			}

			if(!empty($this_value)) {
				$this_arr   = array(
					'field_id'    => (int)$this_id,
					'field_value' => $this_value,
				);

				$custom_fields[$this_id] = $this_arr;
			}
		}
	}
}

// get custom fields type
$fields_ids = array_column($custom_fields, 'field_id');
$fields_ids = implode(',', $fields_ids);

if(!empty($fields_ids)) {
	$query = "SELECT * FROM custom_fields WHERE field_id IN($fields_ids)";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_id = $row['field_id'];
		$this_field_type = !empty($row['field_type']) ? $row['field_type'] : '';
		$this_field_name = !empty($row['field_name']) ? $row['field_name'] : '';

		foreach($custom_fields as $k => $v) {
			if($this_field_id == $v['field_id']) {
				$custom_fields[$k]['field_type'] = $this_field_type;
				// field name is not necessary or used but could be useful for debugging
				$custom_fields[$k]['field_name'] = $this_field_name;
			}
		}
	}
}

if($debug) {
	echo '<h2>$_GET</h2>';
	print_r2($_GET);

	echo '<h2>$_SERVER[\'QUERY_STRING\']</h2>';
	echo urldecode($_SERVER['QUERY_STRING']);

	echo '<h2>$custom_fields</h2>';
	print_r2($custom_fields);
}

/*--------------------------------------------------
Build JOIN and WHERE clauses for custom fields
--------------------------------------------------*/

/*
$custom_fields is Custom fields from $_GET vars (note that field_type and field_name indices were added to the $custom_fields arr later by querying the database)
print_r2($custom_fields);

$custom_fields[0] => Array(
            [field_id] => 28
            [field_value] => 1999 and older
            [field_type] => select
            [field_name] => Model Year)
$custom_fields[1] => Array(
            [field_id] => 27
            [field_value] => Audi
            [field_type] => select
            [field_name] => Brand)
$custom_fields[2] => Array(
            [field_id] => 36
            [field_value] => Array(
                    [from] =>
                    [to] => )
            [field_type] => number
            [field_name] => Mileage)
$custom_fields[3] => Array(
            [field_id] => 26
            [field_value] => Array(
                    [0] => a
                    [1] => b))

Possible custom fields types

checkbox
decimal
multiline
number
radio
select
text
url

*/

$query_join  = '';
$query_where = '';

/*
Example of WHERE clause for custom fields

WHERE r.cat_id IN (171)
	AND p.status = 'approved'
	AND p.paid = 1
	AND
	(
		(rpcf.field_id = 27 AND rpcf.field_value = 'Toyota')
		OR
		(rpcf.field_id = 30 AND rpcf.field_value = 'Automatic')
		OR
		(rpcf.field_id = 28 AND rpcf.field_value < 2017 AND rpcf.field_id = 28 AND rpcf.field_value > 2015)
	)
*/

if(!empty($custom_fields)) {
	$query_join = " LEFT JOIN rel_place_custom_fields rpcf ON p.place_id = rpcf.place_id ";

	// start custom fields counter
	$field_counter = 1;

	// open AND
	$query_where = ' AND ( ';

	// loop through custom fields array
	foreach($custom_fields as $k => $v) {
		$field_id    = $v['field_id'];
		$field_type  = $v['field_type'];
		$field_value = $v['field_value'];

		// is it AND or OR?
		if($field_counter > 1) {
			$query_where .= " OR ";
		}

		/*--------------------------------------------------
		Field value from $_GET is not an array
		--------------------------------------------------*/
		if(!is_array($field_value)) {
			$field_value_param = 'value_' . $field_id;

			// if custom field of type multiline or text, use FULLTEXT SEARCH
			if($field_type == 'text' || $field_type == 'multiline') {
				//$query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value = :$field_value_param) ";
				$query_where .= " (rpcf.field_id = $field_id AND MATCH(rpcf.field_value) AGAINST(:$field_value_param IN BOOLEAN MODE)) ";
			}

			// else use equality comparison
			else {
				$query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value = :$field_value_param) ";
			}
		}

		/*--------------------------------------------------
		Field value from $_GET is array
		--------------------------------------------------*/
		/*
		Example:
		$custom_fields[2] => Array(
					[field_id] => 36
					[field_value] => Array(
							[from] =>
							[to] => )
					[field_type] => number
					[field_name] => Mileage)
		$custom_fields[3] => Array(
					[field_id] => 26
					[field_value] => Array(
							[0] => a
							[1] => b))
		*/

		/*
		Example of WHERE clause for custom fields

		WHERE r.cat_id IN (171)
			AND p.status = 'approved'
			AND p.paid = 1
			AND
			(
				(rpcf.field_id = 27 AND rpcf.field_value = 'Toyota')
				OR
				(rpcf.field_id = 30 AND rpcf.field_value = 'Automatic')
				OR
				(rpcf.field_id = 28 AND rpcf.field_value < 2017 AND rpcf.field_id = 28 AND rpcf.field_value > 2015)
			)
		*/

		else {
			// field_value counter
			$field_val_counter = 1;

			// open parenthesis
			if((!empty($field_value['from']) && !empty($field_value['to'])) || count($field_value) > 2) {
				$query_where .= "(";
			}

			// loop through this field's values array
			foreach($field_value as $k2 => $v2) {
				if(!empty($v2)) {
					/*--------------------------------------------------
					Non-range type array of values (eg. checkbox)
					--------------------------------------------------*/
					if(is_numeric($k2)) {
						if($field_val_counter > 1) {
							$query_where .= " OR ";
						}

						$field_value_param = 'value_' . $field_id;
						$field_value_param .= '_' . $k2;

						$query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value = :$field_value_param) ";
					}

					/*--------------------------------------------------
					Range type array of values (eg. 'from' and 'to')
					--------------------------------------------------*/
					else {
						if($k2 == 'from') {
							$field_value_param_from = 'value_' . $field_id;
							$field_value_param_from .= '_from';
							if(is_numeric($v2)) {
								$query_where .= " (rpcf.field_id = $field_id AND CAST(rpcf.field_value AS SIGNED) >= :$field_value_param_from)";
							}

							else {
								$query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value >= :$field_value_param_from)";
							}
						}

						if(isset($field_value['from']) && isset($field_value['to']) && $k2 == 'to') {
							$query_where .= " AND ";
						}

						if($k2 == 'to') {
							$field_value_param_to = 'value_' . $field_id;
							$field_value_param_to .= '_to';

							if(is_numeric($v2)) {
								$query_where .= " (rpcf.field_id = $field_id AND CAST(rpcf.field_value AS SIGNED) <= :$field_value_param_to)";
							}

							else {
								$query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value <= :$field_value_param_to)";
							}
						}
					}
				}

				$field_val_counter++;
			}

			// close parenthesis
			if((!empty($field_value['from']) && !empty($field_value['to']))  || count($field_value) > 2) {
				$query_where .= ")";
			}
		}

		// add to custom fields counter
		$field_counter++;
	}

	// close AND
	$query_where .= ' ) ';
}

if($debug) {
	echo '<h2>$query_where</h2>';
	echo $query_where;
	echo '<br><br><br><br>';
}

/*--------------------------------------------------
BUILD HAVING CLAUSE COUNT
--------------------------------------------------*/
$having = '';

// always confirm alias used is actually 'total_count'
if(count($custom_fields) > 0) {
	$having_count = count($custom_fields) - 1;
	$having = 'HAVING total_count > ' . $having_count;
}

/*--------------------------------------------------
Initialize combined response
--------------------------------------------------*/
$list_items = array();

/*--------------------------------------------------
get cat ids to use in $in_str
--------------------------------------------------*/
// string which will hold comma separated list of cats ids to be used in the mysql query
$in_str = array();

// get all children for current $cat_id if exists
if(!empty($cat_id)) {
	$in_str = get_children_cats_ids($cat_id, $conn);
	$in_str[] = $cat_id;
	$in_str = implode(',', $in_str);
}

/*--------------------------------------------------
MATCH clause
--------------------------------------------------*/

$query_match_q = '';
if(strlen($user_query) > 2) {
	$query_match_q = " AND MATCH(p.place_name, p.description, p.short_desc) AGAINST(:q IN BOOLEAN MODE) ";
}

/*--------------------------------------------------
Nearby filter query parts (select, where, having)
--------------------------------------------------*/

// query parts
$max_dist_select = '';
$max_dist_where = '';
$max_dist_having = '';

// distance values
$max_dist_values = array_map('trim', explode(';', $cgf_max_dist_values));
$max_dist = isset($_GET['dist']) ? $_GET['dist'] : '';

// user geolocation
$user_lat = !empty($_COOKIE['user_lat']) ? $_COOKIE['user_lat'] : '';
$user_lng = !empty($_COOKIE['user_lng']) ? $_COOKIE['user_lng'] : '';

// check if max_dist is in array of allowed values
if(!empty($max_dist) && in_array($max_dist, $max_dist_values) && !empty($user_lat) && !empty($user_lng)) {
	// convert km to miles
	if($cgf_max_dist_unit == 'km') {
		$max_dist = $max_dist * 0.621371;
	}

	// query parts
	// part of select statement
	$max_dist_select = ", 3956 * 2 *
				ASIN(SQRT( POWER(SIN((:user_lat1 - p.lat) * pi() / 180 / 2), 2)
				+ COS(:user_lat2 * pi() / 180 ) * COS(p.lat * pi() / 180)
				*POWER(SIN((:user_lng1 - p.lng) * pi() / 180 / 2), 2))) AS distance";

	// part of where condition
	$max_dist_where = "AND p.lng BETWEEN (:user_lng2 - $max_dist / COS(RADIANS(:user_lat3)) * 69)
				AND (:user_lng3 + $max_dist / COS(RADIANS(:user_lat4)) * 69)
				AND p.lat BETWEEN (:user_lat5 - ($max_dist / 69))
				AND (:user_lat6 + ($max_dist / 69))";

	// check if $having is not empty
	if(!empty($having)) {
		$max_dist_having = "AND distance < $max_dist";
	}

	else {
		$max_dist_having = "HAVING distance < $max_dist";
	}
}

/*--------------------------------------------------
COUNT QUERIES
--------------------------------------------------*/

// total_rows = full query count, total_count = inner query count used in HAVING clause
$total_rows = 0;

if($loc_type == 'n') {
	// if all cats and no specific location
	if(empty($in_str)) {
		// doesn't happen?
		// in search it happens
		$query = "
			SELECT COUNT(*) AS total_rows FROM
				(
				SELECT COUNT(*) AS total_count $max_dist_select
					FROM places p
					$query_join
					WHERE p.status = 'approved'
						AND p.paid = 1
						$query_match_q
						$query_where
						$max_dist_where
					GROUP BY p.place_id
					$having
					$max_dist_having
				) temp
		";

		if($debug) {
			echo '<h2>1 COUNT $query</h2>';
			echo $query;
			echo '<br><br><br><br>';
		}

		$stmt = $conn->prepare($query);
	}

	// specific cat and no location
	else {
		$query = "
			SELECT COUNT(*) AS total_rows FROM
				(
				SELECT COUNT(*) AS total_count $max_dist_select
					FROM places p
					LEFT JOIN rel_place_cat r
						ON p.place_id = r.place_id
					$query_join
					WHERE r.cat_id IN ($in_str)
						AND p.status = 'approved'
						AND p.paid = 1
						$query_match_q
						$query_where
						$max_dist_where
					GROUP BY p.place_id
					$having
					$max_dist_having
				) temp
		";

		if($debug) {
			echo '<h2>2 COUNT $query</h2>';
			echo $query;
			echo '<br><br><br><br>';
		}

		$stmt = $conn->prepare($query);
	}
}

// if list by city
if($loc_type == 'c') {
	// if all cats and by city
	if(empty($in_str)) {
		$query = "
			SELECT COUNT(*) AS total_rows FROM
				(
				SELECT COUNT(*) AS total_count $max_dist_select
					FROM places p
					$query_join
					WHERE p.city_id = :loc_id
						AND p.status = 'approved'
						AND paid = 1
						$query_match_q
						$query_where
						$max_dist_where
					GROUP BY p.place_id
					$having
					$max_dist_having
				) temp
			";

		if($debug) {
			echo '<h2>3 COUNT $query</h2>';
			echo $query;
			echo '<br><br><br><br>';
		}

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':loc_id', $loc_id);
	}

	// if specific category and by city
	else {
		$query = "
			SELECT COUNT(*) AS total_rows FROM
				(
				SELECT COUNT(*) AS total_count $max_dist_select
					FROM places p
					LEFT JOIN rel_place_cat r
						ON p.place_id = r.place_id
					$query_join
					WHERE p.city_id = :loc_id
						AND p.status = 'approved'
						AND p.paid = 1
						AND r.cat_id IN ($in_str)
						$query_match_q
						$query_where
						$max_dist_where
					GROUP BY p.place_id
					$having
					$max_dist_having
				) temp
			";

		if($debug) {
			echo '<h2>4 COUNT $query</h2>';
			echo $query;
			echo '<br><br><br><br>';
		}

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':loc_id', $loc_id);
	}
}

/*--------------------------------------------------
Bind custom fields params
--------------------------------------------------*/

/*
$custom_fields is Custom fields from $_GET vars
print_r2($custom_fields);

$custom_fields[0] => Array(
            [field_id] => 28
            [field_value] => 1999 and older
            [field_type] => select
            [field_name] => Model Year)
$custom_fields[1] => Array(
            [field_id] => 27
            [field_value] => Audi
            [field_type] => select
            [field_name] => Brand)
$custom_fields[2] => Array(
            [field_id] => 36
            [field_value] => Array(
                    [from] =>
                    [to] => )
            [field_type] => number
            [field_name] => Mileage)
$custom_fields[3] => Array(
            [field_id] => 26
            [field_value] => Array(
                    [0] => a
                    [1] => b))

Possible custom fields types

checkbox
decimal
multiline
number
radio
select
text
url

*/

if(!empty($custom_fields)) {
	foreach($custom_fields as $k => $v) {
		$field_id    = $v['field_id'];
		$field_type  = $v['field_type'];
		$field_value = $v['field_value'];

		// if this field value is not an array
		if(!is_array($field_value)) {
			$field_value_param = 'value_' . $field_id;
			// keep line below for reference purposes
			// $query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value = :$field_value_param) ";

			// bind values
			$stmt->bindValue(":$field_value_param", $field_value);
		}

		// else field_value is array
		else {
			foreach($field_value as $k2 => $v2) {
				$field_value_param = 'value_' . $field_id;
				$field_value_param .= '_' . $k2;

				// bind values
				$stmt->bindValue(":$field_value_param", $v2);
			}
		}
	}
}

/*--------------------------------------------------
Bind distance filter params
--------------------------------------------------*/

if(!empty($max_dist_select) && !empty($user_lat) && !empty($user_lng)) {
	$stmt->bindValue(':user_lat1', $user_lat);
	$stmt->bindValue(':user_lat2', $user_lat);
	$stmt->bindValue(':user_lat3', $user_lat);
	$stmt->bindValue(':user_lat4', $user_lat);
	$stmt->bindValue(':user_lat5', $user_lat);
	$stmt->bindValue(':user_lat6', $user_lat);
	$stmt->bindValue(':user_lng1', $user_lng);
	$stmt->bindValue(':user_lng2', $user_lng);
	$stmt->bindValue(':user_lng3', $user_lng);
}

/*--------------------------------------------------
EXECUTE COUNT QUERY
--------------------------------------------------*/

if(strlen($user_query) > 2) {
	$stmt->bindValue(":q", $q);
}

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = !empty($row['total_rows']) ? $row['total_rows'] : 0;

$count_query = $query;

/*--------------------------------------------------
FINAL QUERY
--------------------------------------------------*/
$start = 0;

if($loc_type == 'n') {
	// if all cats and no specific location
	if(empty($in_str)) {
		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					COUNT(*) AS total_count,
					p.place_id, p.place_name, p.slug AS listing_slug, p.logo, p.address, p.feat,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug, sub1.name AS main_cat_name
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON c.city_id = p.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN (
					SELECT rel_place_cat.*, cats2.cat_slug, cats2.name
					FROM rel_place_cat
					LEFT JOIN cats cats2 ON rel_place_cat.cat_id = cats2.id
					WHERE is_main = 1
					) sub1
					ON sub1.place_id = p.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
				LEFT JOIN (
					SELECT *,
						AVG(rev.rating) AS avg_rating
						FROM reviews rev
						GROUP BY place_id
					) rev_table
					ON p.place_id = rev_table.place_id
				$query_join
				WHERE
					p.status = 'approved'
					AND p.paid = 1
					$query_match_q
					$query_where
					$max_dist_where
				GROUP BY p.place_id
				$having
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, $sort p.submission_date DESC
				LIMIT :start, :items_per_page";

			$stmt = $conn->prepare($query);
		}
	}

	// specific cat and no location
	else {
		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					COUNT(*) AS total_count,
					p.place_id, p.place_name, p.slug AS listing_slug, p.logo, p.address, p.feat,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug, sub1.name AS main_cat_name
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON c.city_id = p.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN (
					SELECT rel_place_cat.*, cats2.cat_slug, cats2.name
					FROM rel_place_cat
					LEFT JOIN cats cats2 ON rel_place_cat.cat_id = cats2.id
					WHERE is_main = 1
					) sub1
					ON sub1.place_id = p.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
				LEFT JOIN (
					SELECT *,
						AVG(rev.rating) AS avg_rating
						FROM reviews rev
						GROUP BY place_id
					) rev_table
					ON p.place_id = rev_table.place_id
				$query_join
				WHERE
					r.cat_id IN ($in_str)
					AND p.status = 'approved'
					AND p.paid = 1
					$query_match_q
					$query_where
					$max_dist_where
				GROUP BY p.place_id
				$having
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, $sort p.submission_date DESC
				LIMIT :start, :items_per_page";

			$stmt = $conn->prepare($query);
		}
	}
}

// if list by city
if($loc_type == 'c') {
	// if all cats and by city
	if(empty($in_str)) {
		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					COUNT(*) AS total_count,
					p.place_id, p.place_name, p.slug AS listing_slug, p.logo, p.address, p.feat,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug, sub1.name AS main_cat_name
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN (
					SELECT rel_place_cat.*, cats2.cat_slug, cats2.name
					FROM rel_place_cat
					LEFT JOIN cats cats2 ON rel_place_cat.cat_id = cats2.id
					WHERE is_main = 1
					) sub1
					ON sub1.place_id = p.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
				LEFT JOIN (
					SELECT *,
						AVG(rev.rating) AS avg_rating
						FROM reviews rev
						GROUP BY place_id
					) rev_table
					ON p.place_id = rev_table.place_id
				$query_join
				WHERE
					p.city_id = :loc_id
					AND p.status = 'approved'
					AND p.paid = 1
					$query_match_q
					$query_where
					$max_dist_where
				GROUP BY p.place_id
				$having
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, $sort p.submission_date DESC
				LIMIT :start, :items_per_page";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(':loc_id', $loc_id);
		}
	}

	// if specific category and by city
	else {
		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					COUNT(*) AS total_count,
					p.place_id, p.place_name, p.slug AS listing_slug, p.logo, p.address, p.feat,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug, sub1.name AS main_cat_name
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN (
					SELECT rel_place_cat.*, cats2.cat_slug, cats2.name
					FROM rel_place_cat
					LEFT JOIN cats cats2 ON rel_place_cat.cat_id = cats2.id
					WHERE is_main = 1
					) sub1
					ON sub1.place_id = p.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
				LEFT JOIN (
					SELECT *,
						AVG(rev.rating) AS avg_rating
						FROM reviews rev
						GROUP BY place_id
					) rev_table
					ON p.place_id = rev_table.place_id
				$query_join
				WHERE
					r.cat_id IN ($in_str)
					AND p.status = 'approved'
					AND p.paid = 1
					AND p.city_id = :loc_id
					$query_match_q
					$query_where
					$max_dist_where
				GROUP BY p.place_id
				$having
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, $sort p.submission_date DESC
				LIMIT :start, :items_per_page";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(':loc_id', $loc_id);
		}
	}
	// end if specific category and by city
} // end if list by city

$final_query = $query;

/*--------------------------------------------------
Bind custom fields params II
--------------------------------------------------*/
if($total_rows > 0) {
	if(!empty($custom_fields)) {
		foreach($custom_fields as $k => $v) {
			$field_id    = $v['field_id'];
			$field_value = $v['field_value'];

			// bind field_value
			if(!is_array($field_value)) {
				$field_value_param = 'value_' . $field_id;
				// keep line below for reference purposes
				// $query_where .= " (rpcf.field_id = $field_id AND rpcf.field_value = :$field_value_param) ";

				// bind values
				$stmt->bindValue(":$field_value_param", $field_value);
			}

			// else field_value is array
			else {
				foreach($field_value as $k2 => $v2) {
					$field_value_param = 'value_' . $field_id;
					$field_value_param .= '_' . $k2;

					// $query_where .= " (rpcf.field_id = :$field_id AND rpcf.field_value = :$field_value_param)";

					// bind values
					$stmt->bindValue(":$field_value_param", $v2);
				}
			}
		}
	}

	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':items_per_page', $items_per_page);

	if(strlen($user_query) > 2) {
		$stmt->bindValue(':q', $q);
	}
}

/*--------------------------------------------------
Bind max dist user_lat and user_lng
--------------------------------------------------*/
if(!empty($max_dist_select) && !empty($user_lat) && !empty($user_lng)) {
	$stmt->bindValue(':user_lat1', $user_lat);
	$stmt->bindValue(':user_lat2', $user_lat);
	$stmt->bindValue(':user_lat3', $user_lat);
	$stmt->bindValue(':user_lat4', $user_lat);
	$stmt->bindValue(':user_lat5', $user_lat);
	$stmt->bindValue(':user_lat6', $user_lat);
	$stmt->bindValue(':user_lng1', $user_lng);
	$stmt->bindValue(':user_lng2', $user_lng);
	$stmt->bindValue(':user_lng3', $user_lng);
}

/*--------------------------------------------------
EXECUTE FINAL QUERY
--------------------------------------------------*/

if(strlen($user_query) > 2) {
	$stmt->bindValue(":q", $q);
}

$stmt->execute();

/*--------------------------------------------------
Create list_items array
--------------------------------------------------*/
$count = 0;

if($total_rows > 0) {
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_address       = !empty($row['address'      ]) ? $row['address'      ] : '';
		$this_area_code     = !empty($row['area_code'    ]) ? $row['area_code'    ] : '';
		$this_country_abbr  = !empty($row['country_abbr' ]) ? $row['country_abbr' ] : '';
		$this_country_name  = !empty($row['country_name' ]) ? $row['country_name' ] : '';
		$this_is_feat       = !empty($row['feat'         ]) ? $row['feat'         ] : '';
		$this_lat           = !empty($row['lat'          ]) ? $row['lat'          ] : '';
		$this_city_name     = !empty($row['city_name'    ]) ? $row['city_name'    ] : '';
		$this_city_slug     = !empty($row['slug'         ]) ? $row['slug'         ] : '';
		$this_listing_slug  = !empty($row['listing_slug' ]) ? $row['listing_slug' ] : '';
		$this_state_abbr    = !empty($row['state'        ]) ? $row['state'        ] : '';
		$this_state_id      = !empty($row['state_id'     ]) ? $row['state_id'     ] : '';
		$this_lng           = !empty($row['lng'          ]) ? $row['lng'          ] : '';
		$this_logo          = !empty($row['logo'         ]) ? $row['logo'         ] : '';
		$this_phone         = !empty($row['phone'        ]) ? $row['phone'        ] : '';
		$this_place_id      = !empty($row['place_id'     ]) ? $row['place_id'     ] : '';
		$this_place_name    = !empty($row['place_name'   ]) ? $row['place_name'   ] : '';
		$this_postal_code   = !empty($row['postal_code'  ]) ? $row['postal_code'  ] : '';
		$this_rating        = !empty($row['avg_rating'   ]) ? $row['avg_rating'   ] : 5;
		$this_short_desc    = !empty($row['short_desc'   ]) ? $row['short_desc'   ] : '';
		$this_state_slug    = !empty($row['state_slug'   ]) ? $row['state_slug'   ] : '';
		$this_website       = !empty($row['website'      ]) ? $row['website'      ] : '';
		$this_main_cat_id   = !empty($row['main_cat_id'  ]) ? $row['main_cat_id'  ] : '';
		$this_main_cat_name = !empty($row['main_cat_name']) ? $row['main_cat_name'] : 'undefined';
		$this_main_cat_slug = !empty($row['main_cat_slug']) ? $row['main_cat_slug'] : 'undefined';

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_main_cat_name = cat_name_transl($this_main_cat_id, $user_cookie_lang, 'singular', $this_main_cat_name);
		}

		// sanitize
		$this_address       = e($this_address      );
		$this_area_code     = e($this_area_code    );
		$this_city_name     = e($this_city_name    );
		$this_city_slug     = e($this_city_slug    );
		$this_country_abbr  = e($this_country_abbr );
		$this_country_name  = e($this_country_name );
		$this_lat           = e($this_lat          );
		$this_listing_slug  = e($this_listing_slug );
		$this_lng           = e($this_lng          );
		$this_logo          = e($this_logo         );
		$this_main_cat_id   = e($this_main_cat_id  );
		$this_main_cat_name = e($this_main_cat_name);
		$this_main_cat_slug = e($this_main_cat_slug);
		$this_phone         = e($this_phone        );
		$this_place_id      = e($this_place_id     );
		$this_place_name    = e($this_place_name   );
		$this_postal_code   = e($this_postal_code  );
		$this_rating        = e($this_rating       );
		$this_short_desc    = e($this_short_desc   );
		$this_state_abbr    = e($this_state_abbr   );
		$this_state_id      = e($this_state_id     );
		$this_state_slug    = e($this_state_slug   );
		$this_website       = e($this_website      );

		// logo
		$this_logo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($this_photo_url)) {
			$this_logo_url = $this_photo_url;
		}

		if(!empty($this_logo) && file_exists($pic_basepath . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo)) {
			$this_logo_url = $pic_baseurl . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo;
		}

		// get one tip
		$this_tip_text = $row['text'];

		if(!empty($this_tip_text)) {
			$this_tip_text = get_snippet($this_tip_text) . '...';
		}

		// clean listing title
		$this_endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$this_place_name = str_replace($this_endash, "-", $this_place_name);

		// rating
		$this_rating = number_format((float)$this_rating, 2, $cfg_decimal_separator, '');

		// country calling code
		$this_country_calling_code = '';

		if(isset($country_calling_codes[$this_country_abbr])) {
			$this_country_calling_code = $country_calling_codes[$this_country_abbr]['value'];
		}

		// link
		$this_listing_link = get_listing_link($this_place_id, $this_listing_slug, $this_main_cat_id, $this_main_cat_slug, '', $this_city_slug, $this_state_slug, $cfg_permalink_struct);

		$list_items[] = array(
			'address'       => $this_address,
			'area_code'     => $this_area_code,
			'city_name'     => $this_city_name,
			'city_slug'     => $this_city_slug,
			'country_abbr'  => $this_country_abbr,
			'country_call'  => $this_country_calling_code,
			'country_name'  => $this_country_name,
			'is_feat'       => $this_is_feat,
			'lat'           => $this_lat,
			'listing_link'  => $this_listing_link,
			'listing_slug'  => $this_listing_slug,
			'lng'           => $this_lng,
			'logo_url'      => $this_logo_url,
			'main_cat_id'   => $this_main_cat_id,
			'main_cat_name' => $this_main_cat_name,
			'main_cat_slug' => $this_main_cat_slug,
			'phone'         => $this_phone,
			'place_id'      => $this_place_id,
			'place_name'    => $this_place_name,
			'postal_code'   => $this_postal_code,
			'rating'        => $this_rating,
			'short_desc'    => $this_short_desc,
			'specialties'   => $this_short_desc,
			'state_abbr'    => $this_state_abbr,
			'state_slug'    => $this_state_slug,
			'tip_text'      => $this_tip_text,
			'website'       => $this_website,
			// legacy compatibility
			'cat_name'      => $this_main_cat_name,
			'cat_slug'      => $this_main_cat_slug,
			'photo_url'     => $this_logo_url,
		);
	}
}

$stmt->closeCursor();

/*--------------------------------------------------
Favorites array
--------------------------------------------------*/
$listings_ids = array();
$favorites = array();

if(!empty($userid) && !empty($list_items)) {
	foreach($list_items as $v) {
		$listings_ids[] = $v['place_id'];
	}

	$listings_ids = implode(',', $listings_ids);

	$query = "SELECT * FROM rel_favorites WHERE place_id IN ($listings_ids) AND userid = :userid";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$favorites[] = $row['place_id'];
	}
}

/*--------------------------------------------------
$listings_custom_fields array
--------------------------------------------------*/

// init array
$listings_custom_fields = array();

// find all custom fields for this cat
if(!empty($cat_id)) {
	$query = "SELECT f.*
				FROM rel_cat_custom_fields r
				LEFT JOIN custom_fields f ON r.field_id = f.field_id
				WHERE r.cat_id = :cat_id AND f.field_status = 1
				GROUP BY r.rel_id
				ORDER BY f.field_order DESC";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(":cat_id", $cat_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_id       = $row['field_id'];
		$this_field_name     = !empty($row['field_name'     ]) ? $row['field_name'     ] : '';
		$this_field_type     = !empty($row['field_type'     ]) ? $row['field_type'     ] : '';
		$this_filter_display = !empty($row['filter_display' ]) ? $row['filter_display' ] : '';
		$this_values_list    = !empty($row['values_list'    ]) ? $row['values_list'    ] : '';
		$this_value_unit     = !empty($row['value_unit'     ]) ? $row['value_unit'     ] : '';
		$this_tooltip        = !empty($row['tooltip'        ]) ? $row['tooltip'        ] : '';
		$this_icon           = !empty($row['icon'           ]) ? $row['icon'           ] : '';
		$this_required       = !empty($row['required'       ]) ? $row['required'       ] : '';
		$this_field_order    = !empty($row['field_order'    ]) ? $row['field_order'    ] : '';
		$this_show_in_res    = !empty($row['show_in_results']) ? $row['show_in_results'] : '';

		// sanitize
		$this_field_id       = e($this_field_id      );
		$this_field_name     = e($this_field_name    );
		$this_field_type     = e($this_field_type    );
		$this_filter_display = e($this_filter_display);
		$this_values_list    = e($this_values_list   );
		$this_value_unit     = e($this_value_unit    );
		$this_tooltip        = e($this_tooltip       );
		$this_required       = e($this_required      );
		$this_field_order    = e($this_field_order   );
		$this_show_in_res    = e($this_show_in_res   );

		// add to array
		if(!empty($this_field_name) && !empty($this_field_type)) {
			$listings_custom_fields[$this_field_id] = array(
				'field_id'       => $this_field_id,
				'field_name'     => $this_field_name,
				'field_type'     => $this_field_type,
				'filter_display' => $this_filter_display,
				'values_list'    => $this_values_list,
				'value_unit'     => $this_value_unit,
				'tooltip'        => $this_tooltip,
				'icon'           => $this_icon,
				'required'       => $this_required,
				'field_order'    => $this_field_order,
				'show_in_res'    => $this_show_in_res,
			);
		}
	}
}

// find all global custom fields
$query = "SELECT f.*
			FROM custom_fields f
			LEFT JOIN rel_cat_custom_fields rc ON f.field_id = rc.field_id
			WHERE rc.rel_id IS NULL AND field_status = 1
			ORDER BY f.field_order";

$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_field_id       = $row['field_id'];
	$this_field_name     = !empty($row['field_name'     ]) ? $row['field_name'     ] : '';
	$this_field_type     = !empty($row['field_type'     ]) ? $row['field_type'     ] : '';
	$this_filter_display = !empty($row['filter_display' ]) ? $row['filter_display' ] : '';
	$this_values_list    = !empty($row['values_list'    ]) ? $row['values_list'    ] : '';
	$this_value_unit     = !empty($row['value_unit'     ]) ? $row['value_unit'     ] : '';
	$this_tooltip        = !empty($row['tooltip'        ]) ? $row['tooltip'        ] : '';
	$this_icon           = !empty($row['icon'           ]) ? $row['icon'           ] : '';
	$this_required       = !empty($row['required'       ]) ? $row['required'       ] : '';
	$this_field_order    = !empty($row['field_order'    ]) ? $row['field_order'    ] : '';
	$this_show_in_res    = !empty($row['show_in_results']) ? $row['show_in_results'] : '';

	// sanitize
	$this_field_name     = e($this_field_name    );
	$this_field_type     = e($this_field_type    );
	$this_filter_display = e($this_filter_display);
	$this_values_list    = e($this_values_list   );
	$this_value_unit     = e($this_value_unit    );
	$this_tooltip        = e($this_tooltip       );
	$this_required       = e($this_required      );
	$this_field_order    = e($this_field_order   );
	$this_show_in_res    = e($this_show_in_res   );

	if(!empty($this_field_name) && !empty($this_field_type)) {
		$listings_custom_fields[$this_field_id] = array(
			'field_id'       => $this_field_id,
			'field_name'     => $this_field_name,
			'field_type'     => $this_field_type,
			'filter_display' => $this_filter_display,
			'values_list'    => $this_values_list,
			'value_unit'     => $this_value_unit,
			'tooltip'        => $this_tooltip,
			'icon'           => $this_icon,
			'required'       => $this_required,
			'field_order'    => $this_field_order,
			'show_in_res'    => $this_show_in_res,
		);
	}
}

/*--------------------------------------------------
Custom fields ids configured to show in results
--------------------------------------------------*/

$custom_fields_show_in_res = array();

foreach($listings_custom_fields as $v) {
	if(in_array($v['show_in_res'], array('name', 'icon', 'name-icon'))) {
		$custom_fields_show_in_res[] = $v['field_id'];
	}
}

// make sure all values are integer
$custom_fields_show_in_res = array_map('intval', $custom_fields_show_in_res);

// build IN str to use in sql query
$custom_fields_show_in_res_str = implode(',', $custom_fields_show_in_res);

/*--------------------------------------------------
Custom fields values for current results
--------------------------------------------------*/

// all listings ids in the current result set
$list_items_ids = array_column($list_items, 'place_id');

// make sure all values are integer
$list_items_ids = array_map('intval', $list_items_ids);

// build IN str
$list_items_ids_str = implode(',', $list_items_ids);

// get custom fields values from the rel_place_custom_fields table
$custom_fields_values = array();

if(!empty($list_items_ids_str) && !empty($custom_fields_show_in_res_str)) {
	$query = "SELECT r.*
				FROM rel_place_custom_fields r
				WHERE r.place_id IN($list_items_ids_str) AND r.field_id IN($custom_fields_show_in_res_str)";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : '';
		$this_field_value = !empty($row['field_value']) ? $row['field_value'] : '';
		$this_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';

		// sanitize
		$this_field_value =	e($this_field_value);

		// add to array
		$custom_fields_values[$this_place_id][$this_field_id] = $this_field_value;
	}
}

/*--------------------------------------------------
html title and meta descriptions
--------------------------------------------------*/
$total_items = '';

$txt_html_title   = $txt_search;
$txt_meta_desc    = '';

/*--------------------------------------------------
breadcrumbs
--------------------------------------------------*/
$breadcrumbs = '';

/*--------------------------------------------------
pagination
--------------------------------------------------*/
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

$page_url = "$baseurl/results";

$getvars_counter = 0;

foreach($_GET as $k => $v) {
	if($k != 'page' && $k != 'sort' && $k != 'dist') {
		if(!empty($v) || $k == 's') {
			if($getvars_counter == 0) {
				$page_url .= '?';
			}

			else {
				$page_url .= '&';
			}

			if(!is_array($v)) {
				$page_url .= $k . '=' . $v;
			}

			else {
				foreach($v as $v2) {
					$page_url .= $k . "[$k2]=" . $v2;
				}
			}

			$getvars_counter++;
		}
	}
}

$page_url_without_page = $page_url;

if(empty($_GET) || (count($_GET) == 1 && isset($_GET['page']))) {
	$page_url .= '?page=';
}

else {
	$page_url .= '&page=';
}

// remove double &&
$page_url = str_replace('&&', '&', $page_url);
$page_url_without_page = str_replace('&&', '&', $page_url_without_page);
$page_url_without_page = trim($page_url_without_page, '&');

/*--------------------------------------------------
$custom_fields_sidebar array for filter in sidebar
--------------------------------------------------*/

$custom_fields_sidebar = array();

// find all custom fields for this cat
if(!empty($cat_id)) {
	$query = "SELECT f.*, tr.field_name AS tr_field_name, tr.values_list AS tr_values_list
				FROM rel_cat_custom_fields r
				LEFT JOIN custom_fields f ON r.field_id = f.field_id
				LEFT JOIN translation_cf tr ON f.field_id = tr.field_id AND tr.lang = :html_lang
				WHERE r.cat_id = :cat_id AND f.field_status = 1 AND f.searchable = 1
				GROUP BY r.rel_id
				ORDER BY f.field_order DESC";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(":cat_id", $cat_id);
	$stmt->bindValue(":html_lang", $html_lang);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_id       = $row['field_id'];
		$this_field_name     = !empty($row['field_name'     ]) ? $row['field_name'     ] : '';
		$this_field_type     = !empty($row['field_type'     ]) ? $row['field_type'     ] : '';
		$this_filter_display = !empty($row['filter_display' ]) ? $row['filter_display' ] : '';
		$this_values_list    = !empty($row['values_list'    ]) ? $row['values_list'    ] : '';
		$this_value_unit     = !empty($row['value_unit'     ]) ? $row['value_unit'     ] : '';
		$this_tooltip        = !empty($row['tooltip'        ]) ? $row['tooltip'        ] : '';
		$this_icon           = !empty($row['icon'           ]) ? $row['icon'           ] : '';
		$this_required       = !empty($row['required'       ]) ? $row['required'       ] : '';
		$this_field_order    = !empty($row['field_order'    ]) ? $row['field_order'    ] : '';
		$this_tr_field_name  = !empty($row['tr_field_name'  ]) ? $row['tr_field_name'  ] : '';
		$this_tr_values_list = !empty($row['tr_values_list' ]) ? $row['tr_values_list' ] : '';
		$this_show_in_res    = !empty($row['show_in_results']) ? $row['show_in_results'] : '';

		// sanitize
		$this_field_name     = e($this_field_name    );
		$this_field_type     = e($this_field_type    );
		$this_filter_display = e($this_filter_display);
		$this_values_list    = e($this_values_list   );
		$this_value_unit     = e($this_value_unit    );
		$this_tooltip        = e($this_tooltip       );
		$this_required       = e($this_required      );
		$this_field_order    = e($this_field_order   );
		$this_show_in_res    = e($this_show_in_res   );

		// numeric values
		$this_field_order = intval($this_field_order);

		if(!empty($this_field_name) && !empty($this_field_type)) {
			$custom_fields_sidebar[] = array(
				'field_id'       => $this_field_id,
				'field_name'     => $this_field_name,
				'field_type'     => $this_field_type,
				'filter_display' => $this_filter_display,
				'values_list'    => $this_values_list,
				'tooltip'        => $this_tooltip,
				'icon'           => $this_icon,
				'required'       => $this_required,
				'field_order'    => $this_field_order,
				'tr_field_name'  => $this_tr_field_name,
				'tr_values_list' => $this_tr_values_list,
				'show_in_res'    => $this_show_in_res,
			);
		}
	}
}

// find all global custom fields
$query = "SELECT f.*, tr.field_name AS tr_field_name, tr.values_list AS tr_values_list
			FROM custom_fields f
			LEFT JOIN rel_cat_custom_fields rc ON f.field_id = rc.field_id
			LEFT JOIN translation_cf tr ON f.field_id = tr.field_id AND tr.lang = :html_lang
			WHERE rc.rel_id IS NULL AND field_status = 1 AND f.searchable = 1
			ORDER BY f.field_order";

$stmt = $conn->prepare($query);
$stmt->bindValue(":html_lang", $html_lang);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_field_id       = $row['field_id'];
	$this_field_name     = !empty($row['field_name'     ]) ? $row['field_name'     ] : '';
	$this_field_type     = !empty($row['field_type'     ]) ? $row['field_type'     ] : '';
	$this_filter_display = !empty($row['filter_display' ]) ? $row['filter_display' ] : '';
	$this_values_list    = !empty($row['values_list'    ]) ? $row['values_list'    ] : '';
	$this_value_unit     = !empty($row['value_unit'     ]) ? $row['value_unit'     ] : '';
	$this_tooltip        = !empty($row['tooltip'        ]) ? $row['tooltip'        ] : '';
	$this_icon           = !empty($row['icon'           ]) ? $row['icon'           ] : '';
	$this_required       = !empty($row['required'       ]) ? $row['required'       ] : '';
	$this_field_order    = !empty($row['field_order'    ]) ? $row['field_order'    ] : '';
	$this_tr_field_name  = !empty($row['tr_field_name'  ]) ? $row['tr_field_name'  ] : '';
	$this_tr_values_list = !empty($row['tr_values_list' ]) ? $row['tr_values_list' ] : '';
	$this_show_in_res    = !empty($row['show_in_results']) ? $row['show_in_results'] : '';

	// sanitize
	$this_field_name     = e($this_field_name    );
	$this_field_type     = e($this_field_type    );
	$this_filter_display = e($this_filter_display);
	$this_values_list    = e($this_values_list   );
	$this_value_unit     = e($this_value_unit    );
	$this_tooltip        = e($this_tooltip       );
	$this_required       = e($this_required      );
	$this_field_order    = e($this_field_order   );
	$this_tr_field_name  = e($this_tr_field_name );
	$this_tr_values_list = e($this_tr_values_list);
	$this_show_in_res    = e($this_show_in_res   );

	// numeric values
	$this_field_order = intval($this_field_order);

	if(!empty($this_field_name) && !empty($this_field_type)) {
		$custom_fields_sidebar[] = array(
			'field_id'       => $this_field_id,
			'field_name'     => $this_field_name,
			'field_type'     => $this_field_type,
			'filter_display' => $this_filter_display,
			'values_list'    => $this_values_list,
			'tooltip'        => $this_tooltip,
			'icon'           => $this_icon,
			'required'       => $this_required,
			'field_order'    => $this_field_order,
			'tr_field_name'  => $this_tr_field_name,
			'tr_values_list' => $this_tr_values_list,
			'show_in_res'    => $this_show_in_res,
		);
	}
}

// sort custom fields
uasort($custom_fields_sidebar, function ($a, $b) {
    return $a['field_order'] - $b['field_order'];
});

if($debug) {
	echo '<h2>$custom_fields_sidebar</h2>';
	print_r2($custom_fields_sidebar);
}

/*--------------------------------------------------
If no results, show latest listings
--------------------------------------------------*/
$latest_listings = array();

if(empty($list_items)) {
	$query = "SELECT
		p.place_id, p.userid, p.place_name, p.city_id, p.description, p.short_desc, p.address, p.feat, p.slug AS place_slug, p.lat, p.lng,
		ph.dir, ph.filename,
		c.city_name, c.slug, c.state,
		s.state_name, s.slug AS state_slug, s.state_abbr,
		cats.cat_slug, cats.id AS cat_id, cats.name AS cat_name, cats.cat_icon, cats.cat_bg,
		rev_table.avg_rating,
		sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
		FROM places p
		LEFT JOIN photos ph ON p.place_id = ph.place_id
		LEFT JOIN cities c ON c.city_id = p.city_id
		LEFT JOIN states s ON c.state_id = s.state_id
		LEFT JOIN rel_place_cat r ON r.place_id = p.place_id
		LEFT JOIN cats ON cats.id = r.cat_id
		LEFT JOIN (
			SELECT rel_place_cat.*, cats2.cat_slug
			FROM rel_place_cat
			LEFT JOIN cats cats2 ON rel_place_cat.cat_id = cats2.id
			WHERE is_main = 1
			) sub1
			ON sub1.place_id = p.place_id
		LEFT JOIN (
			SELECT *,
				AVG(rev.rating) AS avg_rating
				FROM reviews rev
				GROUP BY place_id
			) rev_table ON p.place_id = rev_table.place_id
		WHERE p.status = 'approved' AND p.paid = 1
		GROUP BY p.place_id
		ORDER BY p.place_id DESC LIMIT :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':limit', $cfg_latest_listings_count);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_place_id      = !empty($row['place_id'     ]) ? $row['place_id'     ] : '';
		$this_address       = !empty($row['address'      ]) ? $row['address'      ] : '';
		$this_area_code     = !empty($row['area_code'    ]) ? $row['area_code'    ] : '';
		$this_cat_id        = !empty($row['cat_id'       ]) ? $row['cat_id'       ] : '';
		$this_cat_name      = !empty($row['cat_name'     ]) ? $row['cat_name'     ] : '';
		$this_cat_slug      = !empty($row['cat_slug'     ]) ? $row['cat_slug'     ] : '';
		$this_is_feat       = !empty($row['feat'         ]) ? $row['feat'         ] : '';
		$this_lat           = !empty($row['lat'          ]) ? $row['lat'          ] : '';
		$this_lng           = !empty($row['lng'          ]) ? $row['lng'          ] : '';
		$this_logo          = !empty($row['logo'         ]) ? $row['logo'         ] : '';
		$this_phone         = !empty($row['phone'        ]) ? $row['phone'        ] : '';
		$this_city_name     = !empty($row['city_name'    ]) ? $row['city_name'    ] : '';
		$this_city_slug     = !empty($row['slug'         ]) ? $row['slug'         ] : '';
		$this_place_name    = !empty($row['place_name'   ]) ? $row['place_name'   ] : '';
		$this_listing_slug  = !empty($row['place_slug'   ]) ? $row['place_slug'   ] : '';
		$this_state_abbr    = !empty($row['state'        ]) ? $row['state'        ] : '';
		$this_state_id      = !empty($row['state_id'     ]) ? $row['state_id'     ] : '';
		$this_postal_code   = !empty($row['postal_code'  ]) ? $row['postal_code'  ] : '';
		$this_rating        = !empty($row['avg_rating'   ]) ? $row['avg_rating'   ] : 5;
		$this_short_desc    = !empty($row['short_desc'   ]) ? $row['short_desc'   ] : '';
		$this_state_slug    = !empty($row['state_slug'   ]) ? $row['state_slug'   ] : '';
		$this_main_cat_id   = !empty($row['main_cat_id'  ]) ? $row['main_cat_id'  ] : '';
		$this_main_cat_slug = !empty($row['main_cat_slug']) ? $row['main_cat_slug'] : 'undefined';

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id, $user_cookie_lang, 'singular', $this_cat_name);
		}

		// sanitize
		$this_place_id      = e($this_place_id     );
		$this_address       = e($this_address      );
		$this_area_code     = e($this_area_code    );
		$this_cat_name      = e($this_cat_name     );
		$this_cat_slug      = e($this_cat_slug     );
		$this_lat           = e($this_lat          );
		$this_lng           = e($this_lng          );
		$this_logo          = e($this_logo         );
		$this_phone         = e($this_phone        );
		$this_city_name     = e($this_city_name    );
		$this_city_slug     = e($this_city_slug    );
		$this_place_name    = e($this_place_name   );
		$this_listing_slug  = e($this_listing_slug );
		$this_state_abbr    = e($this_state_abbr   );
		$this_state_id      = e($this_state_id     );
		$this_postal_code   = e($this_postal_code  );
		$this_rating        = e($this_rating       );
		$this_short_desc    = e($this_short_desc   );
		$this_state_slug    = e($this_state_slug   );
		$this_main_cat_id   = e($this_main_cat_id  );
		$this_main_cat_slug = e($this_main_cat_slug);

		// logo
		$this_logo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($this_logo) && file_exists($pic_basepath . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo)) {
			$this_logo_url = $pic_baseurl . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo;
		}

		// thumb
		$this_photo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($row['filename'])) {
			$this_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $row['dir'] . '/' . $row['filename'];
		}

		// clean listing title
		$this_endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$this_place_name = str_replace($this_endash, "-", $this_place_name);

		// rating
		$this_rating = number_format((float)$this_rating, 2, $cfg_decimal_separator, '');

		// link
		$this_listing_link = get_listing_link($this_place_id, $this_listing_slug, $this_main_cat_id, $this_main_cat_slug, '', $this_city_slug, $this_state_slug, $cfg_permalink_struct);

		$latest_listings[] = array(
			'address'      => $this_address,
			'area_code'    => $this_area_code,
			'cat_name'     => $this_cat_name,
			'cat_slug'     => $this_cat_slug,
			'city_name'    => $this_city_name,
			'city_slug'    => $this_city_slug,
			'is_feat'      => $this_is_feat,
			'lat'          => $this_lat,
			'listing_link' => $this_listing_link,
			'listing_slug' => $this_listing_slug,
			'lng'          => $this_lng,
			'logo_url'     => $this_logo_url,
			'phone'        => $this_phone,
			'photo_url'    => $this_photo_url,
			'place_id'     => $this_place_id,
			'place_name'   => $this_place_name,
			'postal_code'  => $this_postal_code,
			'rating'       => $this_rating,
			'short_desc'   => $this_short_desc,
			'specialties'  => $this_short_desc,
			'state_abbr'   => $this_state_abbr,
			'state_slug'   => $this_state_slug,
		);
	}
}

/*--------------------------------------------------
Results array to be used by map markers
--------------------------------------------------*/
$count = ($page - 1) * $items_per_page;
$results_arr = array();
$places_names_arr = array();

foreach($list_items as $k => $v) {
	if(!empty($v['lat'])) {
		$count++;
		$results_arr[] = array(
			"ad_id"    => $v['place_id'],
			"ad_lat"   => $v['lat'],
			"ad_lng"   => $v['lng'],
			"ad_title" => $v['place_name'],
			"ad_link"  => $v['listing_link'],
			"count"    => $count
		);

		$places_names_arr[] = $v['place_name'];
	}
}

/*--------------------------------------------------
Session and cookies
--------------------------------------------------*/

if(!empty($query_city_id) && !empty($query_city_name) && !empty($query_state_abbr)) {
	$_SESSION['search_city_id'] = $query_city_id;
	$_SESSION['search_state_abbr'] = $query_state_abbr;
	$_SESSION['search_city_name'] = $query_city_name;
}

else {
	unset($_SESSION['search_city_id']);
	unset($_SESSION['search_state_abbr']);
	unset($_SESSION['search_city_name']);
}

/*--------------------------------------------------
results list counter
--------------------------------------------------*/
$count = ($page - 1) * $items_per_page;

/*--------------------------------------------------
canonical url
--------------------------------------------------*/
$canonical = "$baseurl/results";

$getvars_counter = 0;

foreach($_GET as $k => $v) {
	if($k != 'sort') {
		if($getvars_counter == 0) {
			$canonical .= '?';
		}

		else {
			$canonical .= '&';
		}

		if(!is_array($v)) {
			$canonical .= $k . '=' . $v;
		}

		else {
			foreach($v as $v2) {
				$canonical .= $k . '[]=' . $v2;
			}
		}

		$getvars_counter++;
	}
}

/*--------------------------------------------------
include template file
--------------------------------------------------*/
$dont_index  = true;
$breadcrumbs = '';
