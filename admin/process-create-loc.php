<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

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

// get params
$loc_type = !empty($params['loc_type']) ? $params['loc_type'] : '';

if($loc_type == 'city') {
	$city_name    = !empty($params['city_name'   ]) ? $params['city_name' ] : '';
	$state        = !empty($params['state'       ]) ? $params['state'     ] : ''; // $value = "$state_id,$state_abbr";
	$city_lat     = !empty($params['lat'         ]) ? $params['lat'       ] : '';
	$city_lng     = !empty($params['lng'         ]) ? $params['lng'       ] : '';
	$uploaded_img = !empty($params['uploaded_img']) ? $params['uploaded_img'] : '';

	$slug       = to_slug($city_name);
	$state_id   = '';
	$state_abbr = '';

	if(!preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $city_lat)) {
		$city_lat = '';
		die('Invalid latitude');
	}

	if(!preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $city_lng)) {
		$city_lng = '';
		die('Invalid longitude');
	}

	if(!empty($state)) {
		$state      = explode(',', $state);
		$state_id   = $state[0];
		$state_abbr = $state[1];
	}

	// trim
	$city_name  = trim($city_name);
	$slug       = trim($slug);
	$state_id   = trim($state_id);
	$state_abbr = trim($state_abbr);
	$uploaded_img = trim($uploaded_img);

	$slug = $slug . '-' . $state_id;

	if(!empty($city_name)) {
		if(!empty($state)) {
			// insert into db
			$query = "INSERT INTO cities(city_name, state, state_id, slug, lat, lng)
				VALUES(:city_name, :state, :state_id, :slug, :lat, :lng)";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':city_name', $city_name);
			$stmt->bindValue(':state', $state_abbr);
			$stmt->bindValue(':state_id', $state_id);
			$stmt->bindValue(':slug', $slug);
			$stmt->bindValue(':lat', $city_lat);
			$stmt->bindValue(':lng', $city_lng);

			if($stmt->execute()) {
				echo $txt_city_created;
			}

			$city_id = $conn->lastInsertId();

			/*--------------------------------------------------
			Uploaded image
			--------------------------------------------------*/

			// get extension of uploaded image
			if(!empty($uploaded_img)) {
				$img_tmp = $pic_basepath . '/city-tmp/' . $uploaded_img;
				$path_parts = pathinfo($img_tmp);
				$img_ext = $path_parts['extension'];

				// final destination
				$img_final = $pic_basepath . '/city/' . $city_id . '.' . $img_ext;

				if(is_file($img_tmp)) {
					if(copy($img_tmp, $img_final)) {
						unlink($img_tmp);
					}
				}
			}
		}

		else {
			echo $txt_pls_create_state;
		}
	}

	else {
		echo $txt_city_name_empty;
	}
}

elseif($loc_type == 'state') {
	$state_name   = !empty($params['state_name']) ? $params['state_name'] : '';
	$state_abbr   = !empty($params['state_abbr']) ? $params['state_abbr'] : '';
	$slug         = to_slug($state_name);
	$country      = !empty($params['country']) ? $params['country'] : ''; // $value = "$country_id,$country_abbr";
	$country_id   = '';
	$country_abbr = '';

	if(!empty($country)) {
		$country      = explode(',', $country);
		$country_id   = $country[0];
		$country_abbr = $country[1];
	}

	// trim
	$state_name   = trim($state_name);
	$state_abbr   = trim($state_abbr);
	$slug         = trim($slug);
	$country_id   = trim($country_id);
	$country_abbr = trim($country_abbr);

	if(!empty($state_name) && !empty($state_abbr)) {
		if(!empty($country)) {
			// insert into db
			$query = "INSERT INTO states(state_name, state_abbr, slug, country_abbr, country_id)
				VALUES(:state_name, :state_abbr, :slug, :country_abbr, :country_id)";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':state_name', $state_name);
			$stmt->bindValue(':state_abbr', $state_abbr);
			$stmt->bindValue(':slug', $slug);
			$stmt->bindValue(':country_abbr', $country_abbr);
			$stmt->bindValue(':country_id', $country_id);

			if($stmt->execute()) {
				echo $txt_state_created;
			}
		}

		else {
			echo $txt_pls_create_country;
		}
	}

	else {
		echo $txt_state_name_empty;
	}
}

elseif($loc_type == 'country') {
	$country_name = !empty($params['country_name']) ? $params['country_name'] : '';
	$country_abbr = !empty($params['country_abbr']) ? $params['country_abbr'] : '';

	// trim
	$country_name = trim($country_name);
	$country_abbr = trim($country_abbr);

	// slug
	$slug = to_slug($country_name);

	if(!empty($country_name) && !empty($country_abbr)) {
		// insert into db
		$query = "INSERT INTO countries(country_name, country_abbr, slug)
			VALUES(:country_name, :country_abbr, :slug)";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':country_name', $country_name);
		$stmt->bindValue(':country_abbr', $country_abbr);
		$stmt->bindValue(':slug', $slug);

		if($stmt->execute()) {
			echo $txt_country_created;
		}
	}

	else {
		echo $txt_country_name_empty;
	}
}

else {
	// do nothing
}