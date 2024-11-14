<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_php.php');

/*--------------------------------------------------
init vars
--------------------------------------------------*/
$errors = array();
$amount = 0;

// has errors
$has_errors = false;

// default assume place submitted successfully
$result_message = '';

/*--------------------------------------------------
Post vars
--------------------------------------------------*/
$address           = !empty($_POST['address'          ]) ? $_POST['address'          ] : '';
$area_code         = !empty($_POST['area_code'        ]) ? $_POST['area_code'        ] : null;
$cat_id            = !empty($_POST['category_id'      ]) ? $_POST['category_id'      ] : '';
$city_id           = !empty($_POST['city_id'          ]) ? $_POST['city_id'          ] : '';
$contact_email     = !empty($_POST['contact_email'    ]) ? $_POST['contact_email'    ] : '';
$country_code      = !empty($_POST['country_code'     ]) ? $_POST['country_code'     ] : '';
$cross_street      = !empty($_POST['cross_street'     ]) ? $_POST['cross_street'     ] : '';
$custom_fields_ids = !empty($_POST['custom_fields_ids']) ? $_POST['custom_fields_ids'] : '';
$delete_temp_pics  = !empty($_POST['delete_temp_pics' ]) ? $_POST['delete_temp_pics' ] : array();
$description       = !empty($_POST['description'      ]) ? $_POST['description'      ] : '';
$short_desc        = !empty($_POST['short_desc'       ]) ? $_POST['short_desc'       ] : '';
$facebook          = !empty($_POST['facebook'         ]) ? $_POST['facebook'         ] : '';
$hours             = !empty($_POST['hours'            ]) ? $_POST['hours'            ] : '';
$inside            = !empty($_POST['inside'           ]) ? $_POST['inside'           ] : '';
$latlng            = !empty($_POST['latlng'           ]) ? $_POST['latlng'           ] : '';
$logo              = !empty($_POST['uploaded_logo'    ]) ? $_POST['uploaded_logo'    ] : '';
$neighborhood      = !empty($_POST['neighborhood'     ]) ? $_POST['neighborhood'     ] : null;
$phone             = !empty($_POST['phone'            ]) ? $_POST['phone'            ] : '';
$place_name        = !empty($_POST['place_name'       ]) ? $_POST['place_name'       ] : '';
$plan_id           = !empty($_POST['plan_id'          ]) ? $_POST['plan_id'          ] : null;
$postal_code       = !empty($_POST['postal_code'      ]) ? $_POST['postal_code'      ] : '';
$twitter           = !empty($_POST['twitter'          ]) ? $_POST['twitter'          ] : '';
$uploads           = !empty($_POST['uploads'          ]) ? $_POST['uploads'          ] : array();
$videos            = !empty($_POST['videos'           ]) ? $_POST['videos'           ] : array();
$wa_area_code      = !empty($_POST['wa_area_code'     ]) ? $_POST['wa_area_code'     ] : '';
$wa_country_code   = !empty($_POST['wa_country_code'  ]) ? $_POST['wa_country_code'  ] : '';
$wa_phone          = !empty($_POST['wa_phone'         ]) ? $_POST['wa_phone'         ] : '';
$website           = !empty($_POST['website'          ]) ? $_POST['website'          ] : '';
$cats_arr          = !empty($_POST['cats'             ]) ? $_POST['cats'             ] : array();

/*--------------------------------------------------
prepare vars
--------------------------------------------------*/
// trim
$address          = is_string($address)          ? trim($address)          : $address;
$area_code        = is_string($area_code)        ? trim($area_code)        : $area_code;
$contact_email    = is_string($contact_email)    ? trim($contact_email)    : $contact_email;
$country_code     = is_string($country_code)     ? trim($country_code)     : $country_code;
$cross_street     = is_string($cross_street)     ? trim($cross_street)     : $cross_street;
$delete_temp_pics = is_string($delete_temp_pics) ? trim($delete_temp_pics) : $delete_temp_pics;
$description      = is_string($description)      ? trim($description)      : $description;
$short_desc       = is_string($short_desc)       ? trim($short_desc)       : $short_desc;
$facebook         = is_string($facebook)         ? trim($facebook)         : $facebook;
$hours            = is_string($hours)            ? trim($hours)            : $hours;
$inside           = is_string($inside)           ? trim($inside)           : $inside;
$latlng           = is_string($latlng)           ? trim($latlng)           : $latlng;
$logo             = is_string($logo)             ? trim($logo)             : $logo;
$neighborhood     = is_string($neighborhood)     ? trim($neighborhood)     : $neighborhood;
$phone            = is_string($phone)            ? trim($phone)            : $phone;
$place_name       = is_string($place_name)       ? trim($place_name)       : $place_name;
$plan_id          = is_string($plan_id)          ? trim($plan_id)          : $plan_id ;
$postal_code      = is_string($postal_code)      ? trim($postal_code)      : $postal_code;
$twitter          = is_string($twitter)          ? trim($twitter)          : $twitter;
$uploads          = is_string($uploads)          ? trim($uploads)          : $uploads;
$wa_area_code     = is_string($wa_area_code)     ? trim($wa_area_code)     : $wa_area_code;
$wa_country_code  = is_string($wa_country_code)  ? trim($wa_country_code)  : $wa_country_code;
$wa_phone         = is_string($wa_phone)         ? trim($wa_phone)         : $wa_phone;
$website          = is_string($website)          ? trim($website)          : $website;

// check plan id selection
if(empty($plan_id)) {
	trigger_error("Invalid plan selection", E_USER_ERROR);
	die();
}

// get plan details
/* plan types
free
free_feat
one_time
one_time_feat
monthly
monthly_feat
annual
annual_feat
*/
$query = "SELECT plan_type, plan_name, plan_period, plan_price, plan_status FROM plans WHERE plan_id = :plan_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_id', $plan_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$plan_type   = !empty($row['plan_type'  ]) ? $row['plan_type'  ] : '';
$plan_name   = !empty($row['plan_name'  ]) ? $row['plan_name'  ] : '';
$plan_period = !empty($row['plan_period']) ? $row['plan_period'] : 0;
$plan_price  = !empty($row['plan_price' ]) ? $row['plan_price' ] : 0;
$plan_status = !empty($row['plan_status']) ? $row['plan_status'] : '';

// check plan id selection
if(empty($plan_type)) {
	trigger_error("Invalid plan selection", E_USER_ERROR);
}

// if not a free plan
if($plan_type != 'free' && $plan_type != 'free_feat') {
	// if it's a monthly plan
	if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
		// init vars
		$cmd = "_xclick-subscriptions";
		$p3  = '1';
		$t3  = 'M';
		$src = '1';
		$srt = '52';
		$a3 = $plan_price;
		$amount = $plan_price;
	}

	// if it's an annual plan
	if($plan_type == 'annual' || $plan_type == 'annual_feat') {
		// init vars
		$cmd = "_xclick-subscriptions";
		$p3  = '1';
		$t3  = 'Y';
		$src = '1';
		$srt = '52';
		$a3 = $plan_price;
		$amount = $plan_price;
	}

	// if it's a one time plan
	if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
		// init vars
		$cmd = "_xclick";
		$amount = $plan_price;
	}

	// bn (<Company>_<Service>_<Product>_<Country>)
	$bn = $paypal_bn . '_Subscribe_WPS_' . $default_country_code;
}

// check if is featured;
$feat = 0;
if(	   $plan_type == 'free_feat'
	|| $plan_type == 'monthly_feat'
	|| $plan_type == 'one_time_feat'
	|| $plan_type == 'annual_feat') {
	$feat = 1;
}

// create unique slug
$is_slug_unique = false;
$count = 2;
$place_slug = to_slug($place_name);
$new_slug = $place_slug;

while(!$is_slug_unique) {
	$query = "SELECT COUNT(*) AS total_rows FROM places WHERE slug = :place_slug";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_slug', $new_slug);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row['total_rows'] == 0) {
		$is_slug_unique = true;
	}

	else {
		$new_slug = $place_slug . '-' . $count;
		$count++;
	}
}

$place_slug = $new_slug;

// lat/lng
if(!empty($latlng)) {
	$latlng = str_replace('(', '', $latlng);
	$latlng = str_replace(')', '', $latlng);
	$latlng = explode(',', $latlng);
	$lat    = trim($latlng[0]);
	$lng    = trim($latlng[1]);

	settype($lat, 'float');
	settype($lng, 'float');
}

else {
	$lat = $default_lat;
	$lng = $default_lng;
}

// normalize twitter url
$twitter  = twitter_url(trim($twitter));

// normalize facebook url
$facebook = facebook_url(trim($facebook));

// clean and normalize website url
$website  = site_url(trim($website));

// remove non numeric chars from whatsapp number
$wa_area_code = preg_replace("/[^0-9]/", "", $wa_area_code);
$wa_country_code = preg_replace("/[^0-9]/", "", $wa_country_code);
$wa_phone = preg_replace("/[^0-9]/", "", $wa_phone);

// short_desc length
$short_desc = mb_substr($short_desc, 0, $short_desc_length);

// if city id is empty, try to guess
// use function to get city name using lat lng coords
$state_id = 0;
if(empty($city_id)) {
	if(is_float($lat) && is_float($lng)) {
		$query = "SELECT * , (3959 * ACOS(COS(RADIANS($lat)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS($lng)) + SIN(RADIANS($lat)) * SIN( RADIANS(lat)))) AS distance FROM cities ORDER BY distance ASC LIMIT 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$city_id  = $row['city_id'];
		$state_id = $row['state_id'];
	}

	else {
		$has_errors = true;
		$result_message .= "<br>- Wrong lat/lng value type";
	}
}

// if state_id empty
if(empty($state_id) && !empty($city_id)) {
	$query = "SELECT state_id FROM cities WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$state_id = $row['state_id'];
}

// initial status
$status = "pending";
$paid   = 0;

// is auto approve enabled
if($cfg_auto_approve_listing) {
	$status = "approved";
}

// is this user the admin?
if($is_admin == 1) {
	$status = "approved";
	$paid = 1;
}

// if free plan types, set paid to 1
if($plan_type == 'free' || $plan_type == 'free_feat') {
	$paid = 1;
}

/*--------------------------------------------------
Custom fields 1
--------------------------------------------------*/
$custom_fields_ids = explode(',', $custom_fields_ids);
$custom_fields = array();
foreach($custom_fields_ids as $v) {
	$field_key = 'field_' . $v;

	if(!empty($_POST[$field_key])) {
		if(!is_array($_POST[$field_key])) {
			$this_field_value = !empty($_POST[$field_key]) ? $_POST[$field_key] : '';
		}

		else {
			$this_field_value = !empty($_POST[$field_key]) ? $_POST[$field_key] : array();
		}

		$custom_fields[] = array(
			'field_id'    => $v,
			'field_value' => $this_field_value);
	}
}

/*--------------------------------------------------
Submit routine
--------------------------------------------------*/
// check if this page is refreshed/reloaded
// if $_SESSION['submit_token'] and submitted $_POST['submit_token'] match
// it means that the page has not been reloaded,
// process insert, then unset $_SESSION['submit_token'],
// so that if user reloads this page, it doesn't match, so it's not inserted
$post_token    = !empty($_POST['submit_token'  ]) ? $_POST['submit_token'   ] : 'aaa';
$session_token = isset($_SESSION['submit_token']) ? $_SESSION['submit_token'] : '';
$cookie_token  = isset($_COOKIE['submit_token' ]) ? $_COOKIE['submit_token' ] : '';

// if($post_token == $session_token || $post_token == $cookie_token) {
// todo: prevent reloading
if(true) {
	try {
		$conn->beginTransaction();

		/*--------------------------------------------------
		Insert listing
		--------------------------------------------------*/
		$query = "INSERT INTO places(
			address,
			area_code,
			business_hours,
			city_id,
			contact_email,
			country_code,
			cross_street,
			description,
			short_desc,
			facebook,
			feat,
			inside,
			lat,
			lng,
			logo,
			neighborhood,
			paid,
			phone,
			place_name,
			plan,
			postal_code,
			slug,
			state_id,
			status,
			twitter,
			userid,
			valid_until,
			wa_area_code,
			wa_country_code,
			wa_phone,
			website
		)
		VALUES(
			:address,
			:area_code,
			:business_hours,
			:city_id,
			:contact_email,
			:country_code,
			:cross_street,
			:description,
			:short_desc,
			:facebook,
			:feat,
			:inside,
			:lat,
			:lng,
			:logo,
			:neighborhood,
			:paid,
			:phone,
			:place_name,
			:plan,
			:postal_code,
			:slug,
			:state_id,
			:status,
			:twitter,
			:userid,
			DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :valid_until DAY),
			:wa_area_code,
			:wa_country_code,
			:wa_phone,
			:website
		)";

		// set valid until value which is just the number of days of the period
		$valid_until = ($plan_period == 0 || $plan_period > 9999) ? 9999 : $plan_period;

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':address'        , $address);
		$stmt->bindValue(':area_code'      , $area_code);
		$stmt->bindValue(':business_hours' , $hours);
		$stmt->bindValue(':city_id'        , $city_id);
		$stmt->bindValue(':contact_email'  , $contact_email);
		$stmt->bindValue(':country_code'   , $country_code);
		$stmt->bindValue(':cross_street'   , $cross_street);
		$stmt->bindValue(':description'    , $description);
		$stmt->bindValue(':short_desc'     , $short_desc);
		$stmt->bindValue(':facebook'       , $facebook);
		$stmt->bindValue(':feat'           , $feat);
		$stmt->bindValue(':inside'         , $inside);
		$stmt->bindValue(':lat'            , $lat);
		$stmt->bindValue(':lng'            , $lng);
		$stmt->bindValue(':logo'           , $logo);
		$stmt->bindValue(':neighborhood'   , $neighborhood);
		$stmt->bindValue(':paid'           , $paid);
		$stmt->bindValue(':phone'          , $phone);
		$stmt->bindValue(':place_name'     , $place_name);
		$stmt->bindValue(':plan'           , $plan_id);
		$stmt->bindValue(':postal_code'    , $postal_code);
		$stmt->bindValue(':slug'           , $place_slug);
		$stmt->bindValue(':state_id'       , $state_id);
		$stmt->bindValue(':status'         , $status);
		$stmt->bindValue(':twitter'        , $twitter);
		$stmt->bindValue(':userid'         , $userid);
		$stmt->bindValue(':valid_until'    , $valid_until);
		$stmt->bindValue(':wa_area_code'   , $wa_area_code);
		$stmt->bindValue(':wa_country_code', $wa_country_code);
		$stmt->bindValue(':wa_phone'       , $wa_phone);
		$stmt->bindValue(':website'        , $website);
		$stmt->execute();

		$place_id = $conn->lastInsertId();
		$_SESSION['last_submitted_place_id'] = $place_id;

		/*--------------------------------------------------
		rel_place_cat
		--------------------------------------------------*/

		// $cat_id is the primary category
		// $cats_arr = $_POST['cats'] is an array of secondary categories
		// modify $cats_arr structure to include is_main info and add $cat_id
		foreach($cats_arr as $k => $v) {
			if($v != $cat_id) {
				$cats_arr[$k] = array($v, 0);
			}

			else {
				unset($cats_arr[$k]);
			}
		}

		$cats_arr[] = array($cat_id, 1);

		if(!empty($cats_arr)) {
			// build query
			$query = "INSERT IGNORE INTO rel_place_cat(place_id, cat_id, city_id, is_main) VALUES";

			$i = 1;
			foreach($cats_arr as $v) {
				$is_main = $v[1];

				if(is_numeric($v[0])) {
					if($i > 1) {
						$query .= ", ";
					}

					$query .= "(:place_id_$i, :cat_id_$i, :city_id_$i, :is_main_$i)";
					$i++;
				}
			}

			// prepare
			$stmt = $conn->prepare($query);

			// bind
			$i = 1;
			foreach($cats_arr as $v) {
				if(is_numeric($v[0])) {
					$stmt->bindValue(":place_id_$i", $place_id);
					$stmt->bindValue(":cat_id_$i", $v[0]);
					$stmt->bindValue(":city_id_$i", $city_id);
					$stmt->bindValue(":is_main_$i", $v[1]);
					$i++;
				}
			}

			// execute
			$stmt->execute();
		}

		/*--------------------------------------------------
		Logo
		--------------------------------------------------*/
		// folder
		$folder_path = $pic_basepath . '/logo/' . substr($logo, 0, 2);

		if (!is_dir($folder_path)) {
			if(!mkdir($folder_path, 0755, true)) {
				$has_errors = true;
				$result_message = 'Error creating logo directory';
			}

			// create empty index file in the folder
			touch($folder_path . '/index.php');
		}

		// paths and folders
		$path_tmp   = $pic_basepath . '/logo-tmp/' . $logo;
		$path_final = $folder_path . '/' . $logo;

		if(is_file($path_tmp)) {
			if(copy($path_tmp, $path_final)) {
				unlink($path_tmp);
			}
		}

		/*--------------------------------------------------
		Photos
		--------------------------------------------------*/

		// delete pics from temp folder that were deleted by user while posting
		if(!empty($delete_temp_pics)) {
			foreach($delete_temp_pics as $v) {
				$temp_pic_path = $pic_basepath . '/' . $place_tmp_folder . '/' . $v;
				if(is_file($temp_pic_path)) {
					unlink($temp_pic_path);
				}
			}
		}

		// uploaded images
		if(!empty($uploads)) {
			// define dirs
			$query = "SELECT photo_id FROM photos ORDER BY photo_id DESC LIMIT 1";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$last_photo_id = !empty($row['photo_id']) ? $row['photo_id'] : 1;
			$dir_num = floor($last_photo_id / 1000) + 1;

			$dir_full  = $pic_basepath . '/' . $place_full_folder . '/' . $dir_num;
			$dir_thumb = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir_num;

			if (!is_dir($dir_full)) {
				mkdir($dir_full, 0777, true);
			}

			if (!is_dir($dir_thumb)) {
				mkdir($dir_thumb, 0777, true);
			}

			// tmp folder
			$tmp_folder = $pic_basepath . '/' . $place_tmp_folder;

			if(!isset($global_thumb_width)) {
				$global_thumb_width = 250;
			}

			if(!isset($global_thumb_height)) {
				$global_thumb_height = 250;
			}

			// uploads counter
			$pic_count = 1;

			foreach($uploads as $v) {
				// only insert into db if the move from temp to final destination folder is successful,
				// otherwise user could send custom uploads[] value and replace original(thus deleting) previous pics
				// from other ads
				$tmp_file = $tmp_folder . '/' . $v;

				// if total pics <= max_pics
				if($pic_count < $max_pics + 1) {
					if(copy($tmp_file, $dir_full . '/' . $v)) {
						// insert into photos table
						$stmt = $conn->prepare('
						INSERT INTO photos(place_id, dir, filename)
						VALUES(:place_id, :dir, :filename)');

						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':dir'     , $dir_num);
						$stmt->bindValue(':filename', $v);
						$stmt->execute();
					}

					// thumb
					smart_resize_image($tmp_file, null, $global_thumb_width, $global_thumb_height, false, $dir_thumb . '/' . $v, true, false, 85);

					// delete pic from tmp_photos table
					$query = "DELETE FROM tmp_photos WHERE filename = :filename";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();

					// if user uploaded more than max_pics, ignore further uploads
					$pic_count++;

					if($pic_count > $max_pics) {
						break;
					}
				}

				// else delete uploaded pic from tmp folder
				else {
					if(is_file($tmp_file)) {
						unlink($tmp_file);
					}
				}
			}
		}

		/*--------------------------------------------------
		Custom fields 2
		--------------------------------------------------*/

		// remove duplicates
		$custom_fields = array_unique($custom_fields, SORT_REGULAR);

		foreach($custom_fields as $v) {
			if(!is_array($v['field_value'])) {
				if(!empty($v['field_value'])) {
					$query = "INSERT INTO rel_place_custom_fields(place_id, field_id, field_value)
						VALUES(:place_id, :field_id, :field_value)";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':place_id', $place_id);
					$stmt->bindValue(':field_id', $v['field_id']);
					$stmt->bindValue(':field_value', $v['field_value']);
					$stmt->execute();
				}
			}

			else {
				foreach($v['field_value'] as $v2) {
					if(!empty($v2)) {
						$query = "INSERT INTO rel_place_custom_fields(place_id, field_id, field_value)
							VALUES(:place_id, :field_id, :field_value)";
						$stmt = $conn->prepare($query);
						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':field_id', $v['field_id']);
						$stmt->bindValue(':field_value', $v2);
						$stmt->execute();
					}
				}
			}
		}

		/*--------------------------------------------------
		Videos
		--------------------------------------------------*/
		if(!empty($videos)) {
			foreach($videos as $v) {
				$query = "INSERT INTO videos
							(place_id, video_url)
						VALUES
							(:place_id, :video_url)";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->bindValue(':video_url', $v);
				$stmt->execute();
			}
		}

		/*--------------------------------------------------
		Commit
		--------------------------------------------------*/
		$conn->commit();
		$has_errors = false;
		$txt_main_title = $txt_main_title_success;
		$result_message = $txt_checkout_msg;
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$has_errors = true;
		$txt_main_title = $txt_main_title_error;
		$result_message = $e->getMessage();

		echo $result_message;
		die('<br>Listing was not created');
	}

	// empty session submit token
	unset($_SESSION['submit_token']);

	/*--------------------------------------------------
	sitemap
	--------------------------------------------------*/
	if($cfg_enable_sitemaps && $status == 'approved' && $paid == 1) {
		// listing link
		// function get_listing_link($place_id, $place_slug = '', $cat_id = '', $cat_slug = '', $city_id = '', $city_slug = '', $state_slug = '', $cfg_permalink_struct = 'listing')
		$this_listing_link = get_listing_link($place_id, $place_slug, $cat_id, '', $city_id, '', '', $cfg_permalink_struct);

		// add to sitemap
		sitemap_add_url($this_listing_link);
	}
}

// else assume user reloaded page
else {
	$result_message = '';

	if($post_token != $session_token) {
		$result_message .= "<br>post_token <> session_token<br>post_token = $post_token<br>session_token = $session_token<br>";
	}

	if($post_token != $cookie_token) {
		$result_message .= "<br>post_token <> cookie_token<br>post_token = $post_token<br>cookie_token = $cookie_token<br>";
	}

	$has_errors = false; // false so the paypal button is shown
	$txt_main_title = $txt_main_title_success;
	$result_message .= $txt_checkout_msg;
}

// thanks messages
$thanks = !$is_admin ? $txt_thanks_msg : $txt_thanks_admin;

// payment gateway vars
// if paypal live
if($paypal_mode == 1) {
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
}

// else is paypal sandbox
else {
	$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	$paypal_merchant_id = $paypal_sandbox_merch_id;
}

// place id, in case page is refreshed, $conn->lastInsertId() is lost, so get place_id from SESSION
if(empty($place_id) && isset($_SESSION['last_submitted_place_id'])) {
	$place_id = $_SESSION['last_submitted_place_id'];
}

/*--------------------------------------------------
stripe vars
--------------------------------------------------*/
// if stripe live mode
if($stripe_mode == 1) {
	$stripe_key = $stripe_live_publishable_key;
}

// else is stripe test mode
else {
	$stripe_key = $stripe_test_publishable_key;
}

$stripe_amount = str_replace('.', '', $plan_price);
$stripe_amount = str_replace(',', '', $stripe_amount);

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/process-create-listing';
