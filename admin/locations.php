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

// sort
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'cities';

$page_url = "$baseurl/admin/locations?sort=$sort&page=";

if(isset($_GET['s'])) {
	$s = urlencode($_GET['s']);
	$page_url = "$baseurl/admin/locations?s=$s&sort=$sort&page=";
}

// keyword
$keyword = !empty($_GET['s']) ? $_GET['s'] : '';
$keyword = '%' . $keyword . '%';

// init total_rows
$total_rows_cities    = 0;
$total_rows_states    = 0;
$total_rows_countries = 0;

$total_rows = $total_rows_cities;

if($sort == 'cities') {
	// count how many cities
	$query = "SELECT COUNT(*) AS total_rows FROM cities";

	if(!empty($keyword)) {
		$query = "SELECT COUNT(*) AS total_rows FROM cities WHERE city_name LIKE :keyword";
	}

	$stmt = $conn->prepare($query);

	if(!empty($keyword)) {
		$stmt->bindValue(':keyword', $keyword);
	}

	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows_cities = $row['total_rows'];
	$total_rows = $total_rows_cities;

	$cities_arr = array();

	if($total_rows_cities > 0) {
		$pager = new DirectoryPlus\PageIterator($limit, $total_rows_cities, $page);
		$start = $pager->getStartRow();

		// select all cities information and put in an array
		$query = "SELECT cities.*, cities_feat.city_id AS feat FROM cities
		LEFT JOIN cities_feat ON cities.city_id = cities_feat.city_id
		ORDER BY city_name LIMIT :start, :limit";

		if(!empty($keyword)) {
			$query = "SELECT cities.*, cities_feat.city_id AS feat FROM cities
			LEFT JOIN cities_feat ON cities.city_id = cities_feat.city_id
			WHERE city_name LIKE :keyword
			ORDER BY city_name LIMIT :start, :limit";
		}

		$stmt = $conn->prepare($query);

		if(!empty($keyword)) {
			$stmt->bindValue(':keyword', $keyword);
		}

		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':limit', $limit);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$city_id    = !empty($row['city_id'  ]) ? $row['city_id'  ] : '';
			$city_name  = !empty($row['city_name']) ? $row['city_name'] : '';
			$state_abbr = !empty($row['state'    ]) ? $row['state'    ] : '';
			$state_id   = !empty($row['state_id' ]) ? $row['state_id' ] : '';
			$city_slug  = !empty($row['slug'     ]) ? $row['slug'     ] : '';
			$is_feat    = !empty($row['feat'     ]) ? $row['feat'     ] : '';

			$cur_loop_arr = array(
				'city_id'    => $city_id,
				'city_name'  => $city_name,
				'state_abbr' => $state_abbr,
				'state_id'   => $state_id,
				'city_slug'  => $city_slug,
				'is_feat'    => $is_feat,
			);

			$cities_arr[] = $cur_loop_arr;
		}
	}
}

else if($sort == 'states') {
	// count how many states
	$query = "SELECT COUNT(*) AS total_rows FROM states";

	if(!empty($keyword)) {
		$query = "SELECT COUNT(*) AS total_rows FROM states WHERE state_name LIKE :keyword";
	}

	$stmt = $conn->prepare($query);

	if(!empty($keyword)) {
		$stmt->bindValue(':keyword', $keyword);
	}

	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows_states = $row['total_rows'];
	$total_rows = $total_rows_states;

	$states_arr = array();

	if($total_rows_states > 0) {
		$pager = new DirectoryPlus\PageIterator($limit, $total_rows_states, $page);
		$start = $pager->getStartRow();

		// select all states information and put in an array
		$query = "SELECT * FROM states ORDER BY state_name LIMIT :start, :limit";

		if(!empty($keyword)) {
			$query = "SELECT * FROM states
			WHERE state_name LIKE :keyword
			ORDER BY state_name LIMIT :start, :limit";
		}

		$stmt = $conn->prepare($query);

		if(!empty($keyword)) {
			$stmt->bindValue(':keyword', $keyword);
		}

		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':limit', $limit);
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$cur_loop_arr = array(
				'state_id'     => $row['state_id'],
				'state_name'   => $row['state_name'],
				'state_abbr'   => $row['state_abbr'],
				'state_slug'   => $row['slug'],
				'country_abbr' => $row['country_abbr'],
				'country_id'   => $row['country_id']
			);
			$states_arr[] = $cur_loop_arr;
		}
	}
}

// else show countries
else {
	// count how many countries
	$query = "SELECT COUNT(*) AS total_rows FROM countries";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows_countries = $row['total_rows'];
	$total_rows = $total_rows_countries;

	$countries_arr = array();

	if($total_rows_countries > 0) {
		$pager = new DirectoryPlus\PageIterator($limit, $total_rows_countries, $page);
		$start = $pager->getStartRow();

		// select all states information and put in an array
		$query = "SELECT * FROM countries ORDER BY country_name LIMIT :start, :limit";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':limit', $limit);
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$cur_loop_arr = array(
				'country_id'   => $row['country_id'],
				'country_name' => $row['country_name'],
				'country_abbr' => $row['country_abbr'],
				'country_slug' => $row['slug']
			);
			$countries_arr[] = $cur_loop_arr;
		}
	}
}
