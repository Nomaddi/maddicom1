<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../inc/img-exts.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'locations');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// location details
$params = array();
parse_str($_POST['params'], $params);

$loc_type = !empty($params['loc_type']) ? $params['loc_type'] : '';
$loc_id   = !empty($params['loc_id'  ]) ? $params['loc_id'  ] : '';

if($loc_type == 'city') {
	$city_name    = !empty($params['city_name'   ]) ? $params['city_name'   ] : '';
	$city_state   = !empty($params['state'       ]) ? $params['state'       ] : ''; // $this_state_id,$this_state_abbr
	$city_lat     = !empty($params['lat'         ]) ? $params['lat'         ] : '';
	$city_lng     = !empty($params['lng'         ]) ? $params['lng'         ] : '';
	$uploaded_img = !empty($params['uploaded_img']) ? $params['uploaded_img'] : '';

	// trim
	$city_name    = trim($city_name);
	$city_state   = trim($city_state);
	$city_lat     = trim($city_lat);
	$city_lng     = trim($city_lng);
	$uploaded_img = trim($uploaded_img);

	// state info
	$state_id   = '';
	$state_abbr = '';

	if(!empty($city_state)) {
		$city_state = explode(',', $city_state);
		$state_id   = $city_state[0];
		$state_abbr = $city_state[1];
	}

	// create city slug
	$city_slug = to_slug($city_name);
	$city_slug = $city_slug . '-' . $state_id;

	// check valid lat/lng
	if(!preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $city_lat)) {
		$city_lat = '';
	}

	if(!preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $city_lng)) {
		$city_lng = '';
	}

	// update db
	$query = "UPDATE cities SET
		city_name = :city_name,
		state     = :state_abbr,
		state_id  = :state_id,
		slug      = :city_slug,
		lat       = :lat,
		lng       = :lng
		WHERE city_id = :city_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_name', $city_name);
	$stmt->bindValue(':state_abbr', $state_abbr);
	$stmt->bindValue(':state_id', $state_id);
	$stmt->bindValue(':city_slug', $city_slug);
	$stmt->bindValue(':lat', $city_lat);
	$stmt->bindValue(':lng', $city_lng);
	$stmt->bindValue(':city_id', $loc_id);

	/*--------------------------------------------------
	Category image
	--------------------------------------------------*/

	// get extension of uploaded image
	if(!empty($uploaded_img)) {
		$img_tmp = $pic_basepath . '/city-tmp/' . $uploaded_img;
		$path_parts = pathinfo($img_tmp);
		$img_ext = $path_parts['extension'];

		// unlink previous image(s)
		foreach($img_exts as $k => $v) {
			if(is_file($pic_basepath . '/city/' . $loc_id . '.' . $v)) {
				unlink($pic_basepath . '/city/' . $loc_id . '.' . $v);
			}
		}

		// final destination
		$img_final = $pic_basepath . '/city/' . $loc_id . '.' . $img_ext;

		if(is_file($img_tmp)) {
			if(copy($img_tmp, $img_final)) {
				unlink($img_tmp);
			}
		}
	}

	if($stmt->execute()) {
		echo $txt_city_edited;
	}
}

if($loc_type == 'state') {
	$state_name    = !empty($params['state_name']) ? $params['state_name'] : '';
	$state_abbr    = !empty($params['state_abbr']) ? $params['state_abbr'] : '';
	$state_country = !empty($params['country'   ]) ? $params['country'   ] : ''; // $country_id,$country_abbr

	// trim
	$state_name    = trim($state_name   );
	$state_abbr    = trim($state_abbr   );
	$state_country = trim($state_country);

	// slug
	$state_slug = to_slug($state_name);

	// country info
	$country_id   = '';
	$country_abbr = '';

	if(!empty($state_country)) {
		$state_country = explode(',', $state_country);
		$country_id   = $state_country[0];
		$country_abbr = $state_country[1];
	}

	// update db
	$query = "UPDATE states SET
		state_name   = :state_name,
		state_abbr   = :state_abbr,
		slug         = :state_slug,
		country_abbr = :country_abbr,
		country_id   = :country_id
		WHERE state_id = :state_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':state_name', $state_name);
	$stmt->bindValue(':state_abbr', $state_abbr);
	$stmt->bindValue(':state_slug', $state_slug);
	$stmt->bindValue(':country_id', $country_id);
	$stmt->bindValue(':country_abbr', $country_abbr);
	$stmt->bindValue(':state_id', $loc_id);

	if($stmt->execute()) {
		echo $txt_state_edited;
	}
}

if($loc_type == 'country') {
	$country_name = !empty($params['country_name']) ? $params['country_name'] : '';
	$country_abbr = !empty($params['country_abbr']) ? $params['country_abbr'] : '';

	// trim
	$country_name = trim($country_name);
	$country_abbr = trim($country_abbr);

	// slug
	$country_slug = to_slug($country_name);

	// update db
	$query = "UPDATE countries SET
		country_name = :country_name,
		country_abbr = :country_abbr,
		slug         = :country_slug
		WHERE country_id = :country_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':country_name', $country_name);
	$stmt->bindValue(':country_abbr', $country_abbr);
	$stmt->bindValue(':country_slug', $country_slug);
	$stmt->bindValue(':country_id', $loc_id);

	if($stmt->execute()) {
		echo $txt_country_edited;
	}
}