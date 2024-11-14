<?php
require_once(__DIR__ . '/inc/config.php');
include(__DIR__ . '/inc/country-calling-codes.php');

/*--------------------------------------------------
Valid routes (below starting at index[1]

cat (1)
state (1)

state/cat (2)
state/city (2)

cat/page/2  (3)
state/city/cat (3)
state/page/2 (3)

state/cat/page/2 (4)
state/city/page/2 (4)

state/city/cat/page/2 (5)
--------------------------------------------------*/

// init vars
$cat_id     = 0;
$loc_id     = 0;
$loc_type   = 'n';
$state_slug = '';
$city_slug  = '';
$page       = 1;
$canonical  = $baseurl . '/listings/';
$country_code = '';

// count frags
$frags_count = count($route) - 1;

if($frags_count == 0) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

/*--------------------------------------------------
frags = 1
--------------------------------------------------*/
if($frags_count == 1) {
	/*
	cat (1)
	state (1)
	*/

	// if by cat
	$query = "SELECT * FROM cats WHERE cat_slug = :cat_slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_slug', $route[1]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$cat_id = $row['id'];
		$loc_type = 'n';
	}

	// else it's by state
	else {
		$query = "SELECT * FROM states WHERE slug = :slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':slug', $route[1]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$loc_id = $row['state_id'];
			$loc_type = 's';
			$state_slug = $row['slug'];
		}

		// else it's neither by cat nor by state
		else {
			http_response_code(404);
			include($install_dir . '/templates/404.php');
			die();
		}
	}

	// only set canonical if previous conditions confirmed
	if(!empty($loc_type)) {
		$canonical = $baseurl . '/' . $route[0] . '/' . $route[1];
	}
}

/*--------------------------------------------------
frags = 2
--------------------------------------------------*/
if($frags_count == 2) {
	/*
	state/cat (2)
	state/city (2)
	*/

	// get state id
	$query = "SELECT * FROM states WHERE slug = :slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':slug', $route[1]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$loc_type = 's';
		$loc_id = $row['state_id'];
		$state_slug = $row['slug'];
	}

	// else state not found
	else {
		http_response_code(404);
		include($install_dir . '/templates/404.php');
		die();
	}

	// if index[2] is cat
	$query = "SELECT * FROM cats WHERE cat_slug = :cat_slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_slug', $route[2]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$cat_id = $row['id'];
	}

	// else index[2] is city
	else {
		$query = "SELECT * FROM cities WHERE slug = :slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':slug', $route[2]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$loc_type = 'c';
			$loc_id = $row['city_id'];
			$city_slug = $row['slug'];
		}

		// else neither cat nor city found
		else {
			http_response_code(404);
			include($install_dir . '/templates/404.php');
			die();
		}
	}

	// only set canonical if previous conditions confirmed
	if(!empty($loc_type)) {
		$canonical = $baseurl . '/' . $route[0] . '/' . $route[1] . '/' . $route[2];
	}
}

/*--------------------------------------------------
frags = 3
--------------------------------------------------*/
if($frags_count == 3) {
	/*
	cat/page/2  (3)
	state/city/cat (3)
	state/page/2 (3)
	*/

	// check if route[2] is 'page'
	if($route[2] == 'page') {
		$page = (int)$route[3];

		// if index[1] is cat
		$query = "SELECT * FROM cats WHERE cat_slug = :cat_slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':cat_slug', $route[1]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$cat_id = $row['id'];
			$loc_type = 'n';
		}

		// else route[1] is state
		else {
			$query = "SELECT * FROM states WHERE slug = :slug";
			$stmt  = $conn->prepare($query);
			$stmt->bindValue(':slug', $route[1]);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if(!empty($row)) {
				$loc_type = 's';
				$loc_id = $row['state_id'];
				$state_slug = $row['slug'];
			}

			// else neither cat nor state found
			else {
				http_response_code(404);
				include($install_dir . '/templates/404.php');
				die();
			}
		}
	}

	// else means it's state/city/cat
	else {
		// get loc info
		$query = "SELECT * FROM cities WHERE slug = :slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':slug', $route[2]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$loc_type = 'c';
			$loc_id = $row['city_id'];
			$city_slug = $row['slug'];
			$state_slug = $route[1];
		}

		// else city not found
		else {
			http_response_code(404);
			include($install_dir . '/templates/404.php');
			die();
		}

		// get cat info
		$query = "SELECT * FROM cats WHERE cat_slug = :cat_slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':cat_slug', $route[3]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$cat_id = $row['id'];
		}

		// else cat not found
		else {
			http_response_code(404);
			include($install_dir . '/templates/404.php');
			die();
		}
	}

	// only set canonical if previous conditions confirmed
	if(!empty($loc_type)) {
		$canonical = $baseurl . '/' . $route[0] . '/' . $route[1] . '/' . $route[2] . '/' . $route[3];
	}
}

/*--------------------------------------------------
frags = 4
--------------------------------------------------*/
if($frags_count == 4) {
	/*
	state/cat/page/2 (4)
	state/city/page/2 (4)
	*/
	$page = (int)$route[4];

	// check if route[2] is cat
	$query = "SELECT * FROM cats WHERE cat_slug = :cat_slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_slug', $route[2]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$loc_type = 's';
		$cat_id = $row['id'];
		$state_slug = '';

		// find loc_id
		$query = "SELECT * FROM states WHERE slug = :slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':slug', $route[1]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$loc_id = $row['state_id'];
			$state_slug = $row['slug'];
		}
	}

	// else route[2] is city
	else {
		// get city info
		$query = "SELECT c.*, s.slug AS state_slug FROM cities c LEFT JOIN states s ON c.state_id = s.state_id WHERE c.slug = :slug";
		$stmt  = $conn->prepare($query);
		$stmt->bindValue(':slug', $route[2]);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row)) {
			$loc_type = 'c';
			$loc_id = $row['city_id'];
			$city_slug = $row['slug'];
			$state_slug = $row['state_slug'];
		}

		// else neither cat nor city found
		else {
			http_response_code(404);
			include($install_dir . '/templates/404.php');
			die();
		}
	}

	// only set canonical if previous conditions confirmed
	if(!empty($loc_type)) {
		$canonical = $baseurl . '/' . $route[0] . '/' . $route[1] . '/' . $route[2] . '/' . $route[3] . '/' . $route[4];
	}
}

/*--------------------------------------------------
frags = 5
--------------------------------------------------*/
if($frags_count == 5) {
	/*
	state/city/cat/page/2 (5)
	*/
	$page = (int)$route[5];

	// find city id
	$query = "SELECT c.*, s.slug AS state_slug FROM cities c LEFT JOIN states s ON c.state_id = s.state_id WHERE c.slug = :slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':slug', $route[2]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$loc_type = 'c';
		$loc_id = $row['city_id'];
		$city_slug = $row['slug'];
		$state_slug = $row['state_slug'];
	}

	// else city not found
	else {
		http_response_code(404);
		include($install_dir . '/templates/404.php');
		die();
	}

	// find cat id
	$query = "SELECT * FROM cats WHERE cat_slug = :slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':slug', $route[3]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$cat_id = $row['id'];
	}

	// else cat not found
	else {
		http_response_code(404);
		include($install_dir . '/templates/404.php');
		die();
	}

	// only set canonical if previous conditions confirmed
	if(!empty($loc_type)) {
		$canonical = $baseurl . '/' . $route[0] . '/' . $route[1] . '/' . $route[2] . '/' . $route[3] . '/' . $route[4] . '/' . $route[5];
	}
}

/*--------------------------------------------------
Cats path array (used for breadcrumbs, etc)
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

// init parent id
$parent_id = 0;

if($cat_id != 0) {
	$query = "SELECT * FROM cats WHERE id = :cat_id";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
	$cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $cat_name ;
	$parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
	$cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$cat_name = cat_name_transl($cat_id , $user_cookie_lang, 'singular', $cat_name);
		$plural_name = cat_name_transl($cat_id , $user_cookie_lang, 'plural', $plural_name);
	}
}

/*--------------------------------------------------
Top level cats and all cats
--------------------------------------------------*/

/*--------------------------------------------------
Valid routes (below starting at index[1]

cat (1)
state (1)

state/cat (2)
state/city (2)

cat/page/2  (3)
state/city/cat (3)
state/page/2 (3)

state/cat/page/2 (4)
state/city/page/2 (4)

state/city/cat/page/2 (5)
--------------------------------------------------*/

// init cats
$all_cats = array();
$top_level_cats = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY cat_order";
$stmt  = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
	$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $this_cat_name ;
	$this_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
	$this_cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
	$this_cat_order   = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
	$this_cat_bg      = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
		$this_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_plural_name);
	}

	// default cat link
	$this_cat_link = $baseurl . '/listings/' . $this_cat_slug;

	if($frags_count == 1) {
		/*
		state
		cat
		*/
		$this_cat_link = "$baseurl/listings/$this_cat_slug";

		if($loc_type == 's') {
			$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
		}
	}

	if($frags_count == 2) {
		/*
		state/cat
		state/city
		*/
		if($loc_type == 'c') {
			$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
		}

		else {
			$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
		}
	}

	if($frags_count == 3) {
		/*
		cat/page/2
		state/city/cat
		state/page/2
		*/

		if($loc_type == 'n') {
			$this_cat_link = "$baseurl/listings/$this_cat_slug";
			//$this_cat_link = $this_cat_slug;
		}

		else if($loc_type == 's') {
			$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
		}

		else {
			$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
		}
	}

	if($frags_count == 4) {
		/*
		state/cat/page/2
		state/city/page/2
		*/
		if($loc_type == 'c') {
			$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
		}

		else {
			$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
		}
	}

	if($frags_count == 5) {
		/*
		state/city/cat/page/2 (5)
		*/

		$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
	}

	$all_cats[$this_cat_id] = array(
		'frags_count' => $frags_count,
		'loc_type'    => $loc_type,
		'cat_id'      => $this_cat_id,
		'cat_name'    => $this_cat_name,
		'cat_plural'  => $this_plural_name,
		'cat_slug'    => $this_cat_slug,
		'cat_icon'    => $this_cat_icon,
		'cat_order'   => $this_cat_order,
		'cat_bg'      => $this_cat_bg,
		'cat_link'    => $this_cat_link,
		'parent_id'   => $this_parent_id,
	);

	if($this_parent_id == 0) {
		$top_level_cats[$this_cat_id] = array(
			'frags_count' => $frags_count,
			'loc_type'    => $loc_type,
			'cat_id'      => $this_cat_id,
			'cat_name'    => $this_cat_name,
			'cat_plural'  => $this_plural_name,
			'cat_slug'    => $this_cat_slug,
			'cat_icon'    => $this_cat_icon,
			'cat_order'   => $this_cat_order,
			'cat_bg'      => $this_cat_bg,
			'cat_link'    => $this_cat_link,
			'parent_id'   => $this_parent_id,
		);
	}
}

/*--------------------------------------------------
Current category's siblings
--------------------------------------------------*/

$cur_cat_siblings = array();

if($parent_id != 0) {
	$query = "SELECT * FROM cats WHERE parent_id = :parent_id AND cat_status = 1";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':parent_id', $parent_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
		$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
		$this_cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
		$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $cat_name ;
		$this_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
		$this_cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
		$this_cat_order   = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
		$this_cat_bg      = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
			$this_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_plural_name);
		}

		// default cat link
		$this_cat_link = $baseurl . '/listings/' . $this_cat_slug;

		if($frags_count == 1) {
			/*
			state
			cat
			*/
			$this_cat_link = "$baseurl/listings/$this_cat_slug";

			if($loc_type == 's') {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 2) {
			/*
			state/cat
			state/city
			*/
			if($loc_type == 'c') {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 3) {
			/*
			cat/page/2
			state/city/cat
			state/page/2
			*/

			if($loc_type == 'n') {
				$this_cat_link = "$baseurl/listings/$this_cat_slug";
			}

			if($loc_type == 's') {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}
		}

		if($frags_count == 4) {
			/*
			state/cat/page/2
			state/city/page/2
			*/
			if($loc_type == 'c') {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 5) {
			/*
			state/city/cat/page/2 (5)
			*/

			$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
		}

		$cur_cat_siblings[] = array(
			'cat_id'     => $this_cat_id,
			'cat_name'   => $this_cat_name,
			'cat_plural' => $this_plural_name,
			'cat_slug'   => $this_cat_slug,
			'cat_icon'   => $this_cat_icon,
			'cat_order'  => $this_cat_order,
			'cat_bg'     => $this_cat_bg,
			'cat_link'   => $this_cat_link,
			'parent_id'  => $this_parent_id,
		);
	}
}

// get top level category for current cat
$cur_cat_top_level_parent = isset($cats_path[0]) ? $cats_path[0] : '';

/*--------------------------------------------------
Current category's children
--------------------------------------------------*/

$cur_cat_children = array();

	$query = "SELECT * FROM cats WHERE parent_id = :cat_id AND cat_status = 1 ORDER BY cat_order";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
		$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
		$this_cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
		$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : $this_cat_name ;
		$this_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;
		$this_cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
		$this_cat_order   = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : '';
		$this_cat_bg      = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
			$this_plural_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'plural', $this_plural_name);
		}

		// default cat link
		$this_cat_link = $baseurl . '/listings/' . $this_cat_slug;

		if($frags_count == 1) {
			/*
			state
			cat
			*/
			$this_cat_link = "$baseurl/listings/$this_cat_slug";

			if($loc_type == 's') {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 2) {
			/*
			state/cat
			state/city
			*/
			if($loc_type == 'c') {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 3) {
			/*
			cat/page/2
			state/city/cat
			state/page/2
			*/

			if($loc_type == 'n') {
				$this_cat_link = "$baseurl/listings/$this_cat_slug";
			}

			else if($loc_type == 's') {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}
		}

		if($frags_count == 4) {
			/*
			state/cat/page/2
			state/city/page/2
			*/
			if($loc_type == 'c') {
				$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
			}

			else {
				$this_cat_link = "$baseurl/listings/$state_slug/$this_cat_slug";
			}
		}

		if($frags_count == 5) {
			/*
			state/city/cat/page/2 (5)
			*/

			$this_cat_link = "$baseurl/listings/$state_slug/$city_slug/$this_cat_slug";
		}

		$cur_cat_children[] = array(
			'cat_id'     => $this_cat_id,
			'cat_name'   => $this_cat_name,
			'cat_plural' => $this_plural_name,
			'cat_slug'   => $this_cat_slug,
			'cat_icon'   => $this_cat_icon,
			'cat_order'  => $this_cat_order,
			'cat_bg'     => $this_cat_bg,
			'cat_link'   => $this_cat_link,
		);
	}

/*--------------------------------------------------
get location info
--------------------------------------------------*/
//
$loc_slug = '';

// get loc info
if($loc_id != 0) {
	if($loc_type == 's') {
		$query = "SELECT * FROM states WHERE state_id = :loc_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':loc_id', $loc_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$state_name = $row['state_name'];
		$state_abbr = $row['state_abbr'];
		$state_slug = $row['slug'];
		$state_id   = $loc_id;
		$region     = $state_name;
		$loc_slug   = $state_slug;
	}

	if($loc_type == 'c') {
		$query = "SELECT
			cities.city_name, cities.slug AS city_slug, cities.lat, cities.lng,
			states.state_id, states.state_name, states.slug AS state_slug, states.state_abbr
			FROM cities
			LEFT JOIN states ON cities.state_id = states.state_id
			WHERE cities.city_id = :city_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':city_id', $loc_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$city_id    = $loc_id;
		$city_name  = $row['city_name'];
		$city_slug  = $row['city_slug'];
		$city_lat   = $row['lat'];
		$city_lng   = $row['lng'];
		$state_name = $row['state_name'];
		$state_abbr = $row['state_abbr'];
		$state_id   = $row['state_id'];
		$state_slug = $row['state_slug'];
		$region     = $city_name . ', ' . $state_abbr;
		$loc_slug   = $city_slug;
	}
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
	// another double check
	if(is_numeric($max_dist)) {
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

		$max_dist_having = "HAVING distance < $max_dist";
	}
}

/*--------------------------------------------------
Main query
--------------------------------------------------*/

$list_items = array();

// get all children for current cat
if($cat_id != 0) {
	$in = array();
	$in[] = $cat_id;

	$children = get_children_cats_ids($cat_id, $conn);

	if(!empty($children)) {
		foreach($children as $v) {
			$in[] = $v;
		}
	}

	$in_str = '';

	foreach($in as $k => $v) {
		if($k == 0) {
			$in_str .= $v;
		}
		else {
			$in_str .= ",$v";
		}
	}
}

// define counters
$total_rows = 0;
$start      = 0;

/*--------------------------------------------------
Count $loc_type == 'n'
--------------------------------------------------*/

if($loc_type == 'n') {
	// if all cats and no specific location
	if($cat_id == 0) {
		// doesn't happen?
	} // end if all cats and no specific location

	// specific cat and no location
	else {
		$query = "SELECT COUNT(place_id) AS total_rows
					FROM
						(
						SELECT p.place_id $max_dist_select
						FROM places p
						INNER JOIN rel_place_cat r ON p.place_id = r.place_id
						WHERE r.cat_id IN ($in_str) AND p.status = 'approved' AND p.paid = 1
						$max_dist_where
						GROUP BY p.place_id
						$max_dist_having
						) subq";

		// execute
		$stmt = $conn->prepare($query);
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

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
						p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
						p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
						p.website,
						c.city_name, c.slug, c.state,
						s.state_name, s.slug AS state_slug, s.state_abbr,
						co.country_name, co.country_abbr,
						cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
						ph.dir, ph.filename,
						pt.plan_priority,
						rev_table.text, rev_table.avg_rating,
						sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
						$max_dist_select
					FROM places p
					LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
					LEFT JOIN cities c ON c.city_id = p.city_id
					LEFT JOIN states s ON c.state_id = s.state_id
					LEFT JOIN countries co ON co.country_id = s.country_id
					LEFT JOIN cats ON r.cat_id = cats.id
					LEFT JOIN photos ph ON p.place_id = ph.place_id
					LEFT JOIN plans pl ON p.plan = pl.plan_id
					LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
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
					WHERE r.cat_id IN ($in_str) AND p.status = 'approved' AND p.paid = 1
					$max_dist_where
					GROUP BY p.place_id
					$max_dist_having
					ORDER BY p.feat DESC, pt.plan_priority DESC, p.submission_date DESC
					LIMIT :start, :items_per_page";
			$stmt = $conn->prepare($query);
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
			$stmt->bindValue(':start', $start);
			$stmt->bindValue(':items_per_page', $items_per_page);
		}
	}
}

/*--------------------------------------------------
Count $loc_type == 's'
--------------------------------------------------*/

if($loc_type == 's') {
	// if all cats and by state
	if($cat_id == 0) {
		$query = "SELECT COUNT(*) AS total_rows
					FROM
						(
						SELECT place_id $max_dist_select
						FROM places p
						WHERE state_id = :state_id
						AND status = 'approved'
						AND paid = 1
						$max_dist_where
						$max_dist_having
						) subq";

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':state_id', $state_id);

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

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		if($total_rows > 0) { // we only know the state, so we have to query cities table
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
						p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
						p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
						p.website,
						c.city_name, c.slug, c.state,
						s.state_name, s.slug AS state_slug, s.state_abbr,
						co.country_name, co.country_abbr,
						cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
						ph.dir, ph.filename,
						pt.plan_priority,
						rev_table.text, rev_table.avg_rating,
						sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
						$max_dist_select
					FROM places p
					LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
					LEFT JOIN cities c ON p.city_id = c.city_id
					LEFT JOIN states s ON c.state_id = s.state_id
					LEFT JOIN countries co ON co.country_id = s.country_id
					LEFT JOIN cats ON r.cat_id = cats.id
					LEFT JOIN photos ph ON p.place_id = ph.place_id
					LEFT JOIN plans pl ON p.plan = pl.plan_id
					LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
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
					WHERE p.state_id = :state_id AND p.status = 'approved' AND p.paid = 1
					$max_dist_where
					GROUP BY p.place_id
					$max_dist_having
					ORDER BY p.feat DESC, pt.plan_priority DESC, p.submission_date DESC
					LIMIT :start, :items_per_page";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':state_id', $state_id);
			$stmt->bindValue(':start', $start);
			$stmt->bindValue(':items_per_page', $items_per_page);
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
		}
	}

	// if specific cat and by state
	else {
		$query = "SELECT COUNT(*) AS total_rows
			FROM
				(
				SELECT p.place_id $max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				WHERE r.cat_id IN ($in_str)
				AND p.state_id = :state_id
				AND p.status = 'approved'
				AND p.paid = 1
				$max_dist_where
				$max_dist_having
				) subq";

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':state_id', $state_id);
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
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
					ph.dir, ph.filename,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN cats ON r.cat_id = cats.id
				LEFT JOIN photos ph ON p.place_id = ph.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
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
				WHERE r.cat_id IN ($in_str) AND p.state_id = :state_id AND p.status = 'approved' AND p.paid = 1
				$max_dist_where
				GROUP BY p.place_id
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, p.submission_date DESC
				LIMIT :start, :items_per_page";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':state_id', $state_id);
			$stmt->bindValue(':start', $start);
			$stmt->bindValue(':items_per_page', $items_per_page);
		}
	}
}

/*--------------------------------------------------
Count $loc_type == 'c'
--------------------------------------------------*/

if($loc_type == 'c') {
	// if all cats and by city
	if($cat_id == 0) {
		$query = "SELECT COUNT(*) AS total_rows
			FROM
				(
				SELECT place_id $max_dist_select
				FROM places p
				WHERE city_id = :city_id
				AND p.status = 'approved'
				AND p.paid = 1
				$max_dist_where
				$max_dist_having
				) subq";


		$stmt = $conn->prepare($query);
		$stmt->bindValue(':city_id', $city_id);
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
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
					ph.dir, ph.filename,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN cats ON r.cat_id = cats.id
				LEFT JOIN photos ph ON p.place_id = ph.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
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
				WHERE p.city_id = :city_id AND p.status = 'approved' AND p.paid = 1
				$max_dist_where
				GROUP BY p.place_id
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, p.submission_date DESC
				LIMIT :start, :items_per_page";
			$stmt = $conn->prepare($query);
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
			$stmt->bindValue(':city_id', $city_id);
			$stmt->bindValue(':start', $start);
			$stmt->bindValue(':items_per_page', $items_per_page);
		}
	}
	// end if all cats and by city

	// if specific category and by city
	else {
		$query = "SELECT COUNT(*) AS total_rows
		FROM
			(
			SELECT p.place_id $max_dist_select
			FROM places p
			INNER JOIN rel_place_cat r ON p.place_id = r.place_id
			WHERE r.cat_id IN ($in_str)
			AND p.city_id = :city_id
			AND p.status = 'approved'
			AND p.paid = 1
			$max_dist_where
			$max_dist_having
			) subq";

		$stmt = $conn->prepare($query);
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
		$stmt->bindValue(':city_id', $city_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		if($total_rows > 0) {
			$pager = new DirectoryPlus\PageIterator($items_per_page, $total_rows, $page);
			$start = $pager->getStartRow();

			$query = "SELECT
					p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
					p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
					p.website,
					c.city_name, c.slug, c.state,
					s.state_name, s.slug AS state_slug, s.state_abbr,
					co.country_name, co.country_abbr,
					cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
					ph.dir, ph.filename,
					pt.plan_priority,
					rev_table.text, rev_table.avg_rating,
					sub1.cat_id AS main_cat_id, sub1.cat_slug AS main_cat_slug
					$max_dist_select
				FROM places p
				LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN states s ON c.state_id = s.state_id
				LEFT JOIN countries co ON co.country_id = s.country_id
				LEFT JOIN cats ON r.cat_id = cats.id
				LEFT JOIN photos ph ON p.place_id = ph.place_id
				LEFT JOIN plans pl ON p.plan = pl.plan_id
				LEFT JOIN plan_types pt ON pl.plan_type = pt.plan_type
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
				WHERE r.cat_id IN ($in_str) AND p.status = 'approved' AND p.paid = 1
				$max_dist_where
				AND p.city_id = :city_id
				GROUP BY p.place_id
				$max_dist_having
				ORDER BY p.feat DESC, pt.plan_priority DESC, p.submission_date DESC
				LIMIT :start, :items_per_page";
			$stmt = $conn->prepare($query);
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
			$stmt->bindValue(':city_id', $city_id);
			$stmt->bindValue(':start', $start);
			$stmt->bindValue(':items_per_page', $items_per_page);
		}
	}
	// end if specific category and by city
} // end if list by city

/*--------------------------------------------------
Execute query
--------------------------------------------------*/
$stmt->execute();

// get vars from query and build listings arr
$count = 0;

if($total_rows > 0) {
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_place_id      = !empty($row['place_id'     ]) ? $row['place_id'     ] : '';
		$this_address       = !empty($row['address'      ]) ? $row['address'      ] : '';
		$this_area_code     = !empty($row['area_code'    ]) ? $row['area_code'    ] : '';
		$this_cat_id        = !empty($row['cat_id'       ]) ? $row['cat_id'       ] : '';
		$this_cat_icon      = !empty($row['cat_icon'     ]) ? $row['cat_icon'     ] : '';
		$this_cat_name      = !empty($row['cat_name'     ]) ? $row['cat_name'     ] : '';
		$this_cat_slug      = !empty($row['cat_slug'     ]) ? $row['cat_slug'     ] : '';
		$this_cross_street  = !empty($row['cross_street' ]) ? $row['cross_street' ] : '';
		$this_is_feat       = !empty($row['feat'         ]) ? $row['feat'         ] : '';
		$this_lat           = !empty($row['lat'          ]) ? $row['lat'          ] : '';
		$this_lng           = !empty($row['lng'          ]) ? $row['lng'          ] : '';
		$this_logo          = !empty($row['logo'         ]) ? $row['logo'         ] : '';
		$this_phone         = !empty($row['phone'        ]) ? $row['phone'        ] : '';
		$this_city_name     = !empty($row['city_name'    ]) ? $row['city_name'    ] : '';
		$this_city_slug     = !empty($row['slug'         ]) ? $row['slug'         ] : 'city';
		$this_place_name    = !empty($row['place_name'   ]) ? $row['place_name'   ] : '';
		$this_place_slug    = !empty($row['place_slug'   ]) ? $row['place_slug'   ] : $this_place_id;
		$this_state_abbr    = !empty($row['state'        ]) ? $row['state'        ] : '';
		$this_state_id      = !empty($row['state_id'     ]) ? $row['state_id'     ] : '';
		$this_state_slug    = !empty($row['state_slug'   ]) ? $row['state_slug'   ] : '';
		$this_postal_code   = !empty($row['postal_code'  ]) ? $row['postal_code'  ] : '';
		$this_rating        = !empty($row['avg_rating'   ]) ? $row['avg_rating'   ] : 5;
		$this_short_desc    = !empty($row['short_desc'   ]) ? $row['short_desc'   ] : '';
		$this_country_name  = !empty($row['country_name' ]) ? $row['country_name' ] : '';
		$this_country_abbr  = !empty($row['country_abbr' ]) ? $row['country_abbr' ] : '';
		$this_website       = !empty($row['website'      ]) ? $row['website'      ] : '';
		$this_main_cat_id   = !empty($row['main_cat_id'  ]) ? $row['main_cat_id'  ] : '';
		$this_main_cat_slug = !empty($row['main_cat_slug']) ? $row['main_cat_slug'] : 'undefined';

		// sanitize
		$this_place_id      = e($this_place_id     );
		$this_address       = e($this_address      );
		$this_area_code     = e($this_area_code    );
		$this_cat_name      = e($this_cat_name     );
		$this_cat_slug      = e($this_cat_slug     );
		$this_cross_street  = e($this_cross_street );
		$this_is_feat       = e($this_is_feat      );
		$this_lat           = e($this_lat          );
		$this_lng           = e($this_lng          );
		$this_logo          = e($this_logo         );
		$this_phone         = e($this_phone        );
		$this_city_name     = e($this_city_name    );
		$this_city_slug     = e($this_city_slug    );
		$this_place_name    = e($this_place_name   );
		$this_place_slug    = e($this_place_slug   );
		$this_state_abbr    = e($this_state_abbr   );
		$this_state_id      = e($this_state_id     );
		$this_state_slug    = e($this_state_slug   );
		$this_postal_code   = e($this_postal_code  );
		$this_rating        = e($this_rating       );
		$this_short_desc    = e($this_short_desc   );
		$this_country_name  = e($this_country_name );
		$this_country_abbr  = e($this_country_abbr );
		$this_website       = e($this_website      );
		$this_main_cat_id   = e($this_main_cat_id  );
		$this_main_cat_slug = e($this_main_cat_slug);

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
		}

		// thumb
		$this_photo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($row['filename'])) {
			$this_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $row['dir'] . '/' . $row['filename'];
		}

		// logo
		$this_logo_url = $baseurl . '/assets/imgs/blank.png';

		if(!empty($this_photo_url)) {
			$this_logo_url = $this_photo_url;
		}

		if(!empty($this_logo) && file_exists($pic_basepath . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo)) {
			$this_logo_url = $pic_baseurl . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo;
		}

		// clean place name
		$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$this_place_name = str_replace($endash, "-", $this_place_name);

		// rating
		$this_rating = number_format((float)$this_rating, 2, isset($cfg_decimal_separator) ? $cfg_decimal_separator : '.', '');

		// country calling code
		$this_country_calling_code = '';

		if(isset($country_calling_codes[$this_country_abbr])) {
			$this_country_calling_code = $country_calling_codes[$this_country_abbr]['value'];
		}

		// link
		$this_listing_link = get_listing_link($this_place_id, $this_place_slug, $this_main_cat_id, $this_main_cat_slug, '', $this_city_slug, $this_state_slug, $cfg_permalink_struct);

		// items array
		$list_items[] = array(
			'place_id'      => $this_place_id,
			'address'       => $this_address,
			'area_code'     => $this_area_code,
			'cat_icon'      => $this_cat_icon,
			'cat_name'      => $this_cat_name,
			'cat_slug'      => $this_cat_slug,
			'city_name'     => $this_city_name,
			'city_slug'     => $this_city_slug,
			'cross_street'  => $this_cross_street,
			'short_desc'    => $this_short_desc,
			'specialties'   => $this_short_desc,
			'is_feat'       => $this_is_feat,
			'lat'           => $this_lat,
			'listing_link'  => $this_listing_link,
			'lng'           => $this_lng,
			'logo_url'      => $this_logo_url,
			'phone'         => $this_phone,
			'photo_url'     => $this_photo_url,
			'place_name'    => $this_place_name,
			'place_slug'    => $this_place_slug,
			'postal_code'   => $this_postal_code,
			'rating'        => $this_rating,
			'state_abbr'    => $this_state_abbr,
			'state_slug'    => $this_state_slug,
			'country_name'  => $this_country_name,
			'country_abbr'  => $this_country_abbr,
			'country_call'  => $this_country_calling_code,
			'website'       => $this_website,
			'main_cat_id'   => $this_main_cat_id,
			'main_cat_slug' => $this_main_cat_slug,
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
Results array to be used by map markers
--------------------------------------------------*/
$count = ($page - 1) * $items_per_page;
$results_arr = array();

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
breadcrumbs
--------------------------------------------------*/
$breadcrumbs = "<a href='$baseurl'>$txt_home</a>";

if($loc_type == 'n') {
	// $cats_path is an array of category ids
	foreach($cats_path as $v) {
		$stmt = $conn->prepare('SELECT * FROM cats WHERE id = :id');
		$stmt->bindValue(':id', $v);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this_cat_name = !empty($row['plural_name']) ? $row['plural_name'] : $v;
		$this_cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : $this_cat_id;

		// get translated cat name if user language cookie is set
		if(!empty($user_cookie_lang)) {
			$this_cat_name = cat_name_transl($v , $user_cookie_lang, 'plural', $this_cat_name);
		}

		$this_url = $baseurl . '/listings/' . $this_cat_slug;

		$breadcrumbs .= " >  <a href='$this_url'>$this_cat_name</a>";
	}

	$breadcrumbs .= " > $plural_name";
}

if($loc_type == 's') {
	/*
	cat (1)
	state (1)

	state/cat (2)
	state/city (2)

	cat/page/2  (3)
	state/city/cat (3)
	state/page/2 (3)

	state/cat/page/2 (4)
	state/city/page/2 (4)

	state/city/cat/page/2 (5)
	*/

	$breadcrumb_state_url = $baseurl . '/listings/' . $state_slug;

	if($frags_count == 1) {
		/*
		cat (1) <-- ignore
		state (1)
		*/
		$breadcrumbs .= " &gt; $state_name";
	}

	if($frags_count == 2) {
		/*
		state/cat (2)
		state/city (2) <-- ignore
		*/
		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";

		// /cat could be a subcat so breadcrumb needs to include all cats hierarchically even if url shows just one cat
		foreach($cats_path as $v) { // $cats_path is an array of category ids
			$stmt = $conn->prepare('SELECT * FROM cats WHERE id = :id');
			$stmt->bindValue(':id', $v);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this_cat_name = !empty($row['plural_name']) ? $row['plural_name'] : $v;
			$this_cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : $this_cat_id;

			// get translated cat name if user language cookie is set
			if(!empty($user_cookie_lang)) {
				$this_cat_name = cat_name_transl($v , $user_cookie_lang, 'plural', $this_cat_name);
			}

			$this_url = $baseurl . '/listings/' . $state_slug . '/' . $this_cat_slug;

			$breadcrumbs .= " &gt; <a href='$this_url'>$this_cat_name</a>";
		}

		$breadcrumbs .= " &gt; $plural_name";
	}

	if($frags_count == 3) {
		/*
		cat/page/2  (3) <-- ignore
		state/city/cat (3) <-- ignore
		state/page/2 (3)
		*/
		$breadcrumbs .= " &gt; $state_name";
	}

	if($frags_count == 4) {
		/*
		state/cat/page/2 (4)
		state/city/page/2 (4) <-- ignore
		*/
		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";

		// /cat could be a subcat so breadcrumb needs to include all cats hierarchically even if url shows just one cat
		foreach($cats_path as $v) { // $cats_path is an array of category ids
			$stmt = $conn->prepare('SELECT * FROM cats WHERE id = :id');
			$stmt->bindValue(':id', $v);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this_cat_name = !empty($row['plural_name']) ? $row['plural_name'] : $v;
			$this_cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : $this_cat_id;

			// get translated cat name if user language cookie is set
			if(!empty($user_cookie_lang)) {
				$this_cat_name = cat_name_transl($v , $user_cookie_lang, 'plural', $this_cat_name);
			}

			$this_url = $baseurl . '/listings/' . $state_slug . '/' . $this_cat_slug;

			$breadcrumbs .= " &gt; <a href='$this_url'>$this_cat_name</a>";
		}

		$breadcrumbs .= " &gt; $plural_name";
	}
}

if($loc_type == 'c') {
	/*
	cat (1)
	state (1)

	state/cat (2)
	state/city (2)

	cat/page/2  (3)
	state/city/cat (3)
	state/page/2 (3)

	state/cat/page/2 (4)
	state/city/page/2 (4)

	state/city/cat/page/2 (5)
	*/

	$breadcrumb_state_url = $baseurl . '/listings/' . $state_slug;
	$breadcrumb_city_url = $baseurl . '/listings/' . $state_slug . '/' . $city_slug;

	if($frags_count == 2) {
		/*
		state/cat (2) <-- ignore
		state/city (2)
		*/
		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";
		$breadcrumbs .= " &gt; <a href='$breadcrumb_city_url'>$city_name</a>";
	}

	if($frags_count == 3) {
		/*
		cat/page/2  (3) <-- ignore
		state/city/cat (3)
		state/page/2 (3) <-- ignore
		*/
		$breadcrumb_cat_url = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $cat_slug;

		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";
		$breadcrumbs .= " &gt; <a href='$breadcrumb_city_url'>$city_name</a>";

		// /cat could be a subcat so breadcrumb needs to include all cats hierarchically even if url shows just one cat
		foreach($cats_path as $v) { // $cats_path is an array of category ids
			$stmt = $conn->prepare('SELECT * FROM cats WHERE id = :id');
			$stmt->bindValue(':id', $v);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this_cat_name = !empty($row['plural_name']) ? $row['plural_name'] : $v;
			$this_cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : $this_cat_id;

			// get translated cat name if user language cookie is set
			if(!empty($user_cookie_lang)) {
				$this_cat_name = cat_name_transl($v , $user_cookie_lang, 'plural', $this_cat_name);
			}

			$this_url = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $this_cat_slug;

			$breadcrumbs .= " &gt; <a href='$this_url'>$this_cat_name</a>";
		}

		$breadcrumbs .= " &gt; $plural_name";
	}

	if($frags_count == 4) {
		/*
		state/cat/page/2 (4) <-- ignore
		state/city/page/2 (4)
		*/
		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";
		$breadcrumbs .= " &gt; <a href='$breadcrumb_city_url'>$city_name</a>";
	}

	if($frags_count == 5) {
		/*
		state/city/cat/page/2 (5)
		*/
		$breadcrumb_cat_url = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $cat_slug;

		$breadcrumbs .= " &gt; <a href='$breadcrumb_state_url'>$state_name</a>";
		$breadcrumbs .= " &gt; <a href='$breadcrumb_city_url'>$city_name</a>";

		// /cat could be a subcat so breadcrumb needs to include all cats hierarchically even if url shows just one cat
		foreach($cats_path as $v) { // $cats_path is an array of category ids
			$stmt = $conn->prepare('SELECT * FROM cats WHERE id = :id');
			$stmt->bindValue(':id', $v);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this_cat_name = !empty($row['plural_name']) ? $row['plural_name'] : $v;
			$this_cat_slug = !empty($row['cat_slug']) ? $row['cat_slug'] : $this_cat_id;

			// get translated cat name if user language cookie is set
			if(!empty($user_cookie_lang)) {
				$this_cat_name = cat_name_transl($v , $user_cookie_lang, 'plural', $this_cat_name);
			}

			$this_url = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $this_cat_slug;

			$breadcrumbs .= " &gt; <a href='$this_url'>$this_cat_name</a>";
		}

		$breadcrumbs .= " &gt; $plural_name";
	}
}

/*--------------------------------------------------
sub categories
--------------------------------------------------*/
/*--------------------------------------------------
Valid routes (below starting at index[1]

cat (1)
state (1)

state/cat (2)
state/city (2)

cat/page/2  (3)
state/city/cat (3)
state/page/2 (3)

state/cat/page/2 (4)
state/city/page/2 (4)

state/city/cat/page/2 (5)
--------------------------------------------------*/

$subcats = array();
$query = "SELECT * FROM cats WHERE parent_id = :cat_id AND cat_status = 1 ORDER BY name";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$subcat_id   = $row['id'];
	$subcat_name = !empty($row['plural_name']) ? $row['plural_name'] : $row['name'];
	$subcat_slug = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : $row['id'];

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$subcat_name = cat_name_transl($subcat_id, $user_cookie_lang, 'plural', $subcat_name);
	}

	// default subcat link
	$subcat_link = $baseurl . '/listings/' . $subcat_slug;

	if($frags_count == 1) {
		/*
		state
		cat <-- ignore
		*/
		if($loc_type == 's') {
			$subcat_link = "$baseurl/listings/$state_slug/$subcat_slug";
		}
	}

	if($frags_count == 2) {
		/*
		state/cat
		state/city <-- ignore
		*/
		if($loc_type == 'c') {
			$subcat_link = "$baseurl/listings/$state_slug/$city_slug/$subcat_slug";
		}

		else {
			$subcat_link = "$baseurl/listings/$state_slug/$subcat_slug";
		}
	}

	if($frags_count == 3) {
		/*
		cat/page/2
		state/city/cat
		state/page/2 <-- ignore
		*/

		if($loc_type == 'n') {
			$subcat_link = "$baseurl/listings/$subcat_slug";
		}

		else {
			$subcat_link = "$baseurl/listings/$state_slug/$city_slug/$subcat_slug";
		}
	}

	if($frags_count == 4) {
		/*
		state/cat/page/2
		state/city/page/2
		*/
		if($loc_type == 'c') {
			$subcat_link = "$baseurl/listings/$state_slug/$city_slug/$subcat_slug";
		}

		else {
			$subcat_link = "$baseurl/listings/$state_slug/$subcat_slug";
		}
	}

	if($frags_count == 5) {
		/*
		state/city/cat/page/2 (5)
		*/

		$subcat_link = "$baseurl/listings/$state_slug/$city_slug/$subcat_slug";
	}

	// add to array
	$subcats[] = array(
					'subcat_id' => $subcat_id,
					'subcat_name' => $subcat_name,
					'subcat_slug' => $subcat_slug,
					'subcat_link' => $subcat_link,
				);
}

/*--------------------------------------------------
$custom_fields array
--------------------------------------------------*/

// init array
$custom_fields = array();

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

		// add to array
		if(!empty($this_field_name) && !empty($this_field_type)) {
			$custom_fields[$this_field_id] = array(
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
		$custom_fields[$this_field_id] = array(
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
			'tr_field_name'  => $this_tr_field_name,
			'tr_values_list' => $this_tr_values_list,
			'show_in_res'    => $this_show_in_res,
		);
	}
}

// sort custom fields
uasort($custom_fields, function ($a, $b) {
    return $a['field_order'] - $b['field_order'];
});

/*--------------------------------------------------
Custom fields ids configured to show in results
--------------------------------------------------*/

$custom_fields_show_in_res = array();

foreach($custom_fields as $v) {
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

$location = $country_name;
$loc_separator = isset($loc_separator) ? $loc_separator : ', ';

// get first 2 or 3 place names and build string to use in meta descriptions
if(!empty($list_items)) {
	$meta_desc_str = '';

	for($i = 0; $i < 3; $i++) {
		if(!empty($list_items[$i]['place_name'])) {
			if($i != 0) {
				$meta_desc_str .= ', ';
			}

			$meta_desc_str .= $list_items[$i]['place_name'];
		}
	}
}

else {
	$meta_desc_str = '';
}

if($loc_type == 'c') {
	$location = $city_name . $loc_separator . $state_abbr;
}

if($loc_type == 's') {
	$location = $state_name;
}

if(!empty($plural_name)) {
	$txt_html_title = str_replace('%plural_name%', $plural_name, $txt_html_title);
	$txt_meta_desc = str_replace('%plural_name%', $plural_name, $txt_meta_desc);
}

else {
	$txt_html_title = str_replace('%plural_name%', $txt_businesses, $txt_html_title);
	$txt_meta_desc = str_replace('%plural_name%', $txt_businesses, $txt_meta_desc);
}

$txt_html_title = str_replace('%location%', $location, $txt_html_title);
$txt_meta_desc = str_replace('%location%', $location, $txt_meta_desc);
$txt_meta_desc = str_replace('%places_names%', $meta_desc_str, $txt_meta_desc);

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

/*--------------------------------------------------
Valid routes (below starting at index[1]

cat (1)
state (1)

state/cat (2)
state/city (2)

cat/page/1  (3)
state/city/cat (3)
state/page/1 (3)

state/cat/page/1 (4)
state/city/page/1 (4)

state/city/cat/page/1 (5)
--------------------------------------------------*/
$page_url = "$baseurl/listings/page/";
$page_url_without_page = "$baseurl/listings/";

if($frags_count == 1) {
	$page_url = "$baseurl/listings/" . $route[1] . '/page/';
	$page_url_without_page = "$baseurl/listings/" . $route[1];
}

if($frags_count == 2) {
	$page_url = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/page/';
	$page_url_without_page = "$baseurl/listings/" . $route[1] . '/' . $route[2];
}

if($frags_count == 3) {
	if($route[2] != 'page') {
		$page_url = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/' . $route[3] . '/page/';
		$page_url_without_page = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/' . $route[3];
	}

	else {
		$page_url = "$baseurl/listings/" . $route[1] . '/page/';
		$page_url_without_page = "$baseurl/listings/" . $route[1];
	}
}

if($frags_count == 4) {
	$page_url = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/page/';
	$page_url_without_page = "$baseurl/listings/" . $route[1] . '/' . $route[2];
}

if($frags_count == 5) {
	$page_url = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/' . $route[3] . '/page/';
	$page_url_without_page = "$baseurl/listings/" . $route[1] . '/' . $route[2] . '/' . $route[3];
}

/*--------------------------------------------------
results list counter
--------------------------------------------------*/
$count = ($page - 1) * $items_per_page;

/*--------------------------------------------------
Canonical
--------------------------------------------------*/
$canonical = preg_replace('|page/1$|', '', $canonical);
