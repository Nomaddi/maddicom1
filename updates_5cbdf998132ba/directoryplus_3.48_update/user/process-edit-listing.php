<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
//require_once(__DIR__ . '/_user_inc_request_with_php.php');

if(empty($_POST['place_id'])) {
	header("Location: $baseurl/user");
}

$errors = array();

/*--------------------------------------------------
Post vars
--------------------------------------------------*/

// POST vars
$place_id             = $_POST['place_id'];
$address              = !empty($_POST['address'             ]) ? $_POST['address'             ] : '';
$area_code            = !empty($_POST['area_code'           ]) ? $_POST['area_code'           ] : 0;
$country_code         = !empty($_POST['country_code'        ]) ? $_POST['country_code'        ] : '';
$business_hours       = !empty($_POST['business_hours'      ]) ? $_POST['business_hours'      ] : '';
$cat_id               = !empty($_POST['category_id'         ]) ? $_POST['category_id'         ] : '';
$city_id              = !empty($_POST['city_id'             ]) ? $_POST['city_id'             ] : 0;
$contact_email        = !empty($_POST['contact_email'       ]) ? $_POST['contact_email'       ] : '';
$cross_street         = !empty($_POST['cross_street'        ]) ? $_POST['cross_street'        ] : '';
$custom_fields_ids    = !empty($_POST['custom_fields_ids'   ]) ? $_POST['custom_fields_ids'   ] : '';
$delete_existing_pics = !empty($_POST['delete_existing_pics']) ? $_POST['delete_existing_pics'] : array();
$delete_temp_pics     = !empty($_POST['delete_temp_pics'    ]) ? $_POST['delete_temp_pics'    ] : array();
$short_desc           = !empty($_POST['short_desc'          ]) ? $_POST['short_desc'          ] : '';
$description          = !empty($_POST['description'         ]) ? $_POST['description'         ] : '';
$existing_pics        = !empty($_POST['existing_pics'       ]) ? $_POST['existing_pics'       ] : array();
$facebook             = !empty($_POST['facebook'            ]) ? $_POST['facebook'            ] : '';
$inside               = !empty($_POST['inside'              ]) ? $_POST['inside'              ] : '';
$instagram            = !empty($_POST['instagram'           ]) ? $_POST['instagram'           ] : '';
$latlng               = !empty($_POST['latlng'              ]) ? $_POST['latlng'              ] : '';
$logo                 = !empty($_POST['uploaded_logo'       ]) ? $_POST['uploaded_logo'       ] : '';
$neighborhood         = !empty($_POST['neighborhood'        ]) ? $_POST['neighborhood'        ] : '';
$phone                = !empty($_POST['phone'               ]) ? $_POST['phone'               ] : '';
$place_name           = !empty($_POST['place_name'          ]) ? $_POST['place_name'          ] : '';
$postal_code          = !empty($_POST['postal_code'         ]) ? $_POST['postal_code'         ] : '';
$twitter              = !empty($_POST['twitter'             ]) ? $_POST['twitter'             ] : '';
$uploads              = !empty($_POST['uploads'             ]) ? $_POST['uploads'             ] : array();
$videos               = !empty($_POST['videos'              ]) ? $_POST['videos'              ] : array();
$website              = !empty($_POST['website'             ]) ? $_POST['website'             ] : '';
$wa_area_code         = !empty($_POST['wa_area_code'        ]) ? $_POST['wa_area_code'        ] : '';
$wa_country_code      = !empty($_POST['wa_country_code'     ]) ? $_POST['wa_country_code'     ] : '';
$wa_phone             = !empty($_POST['wa_phone'            ]) ? $_POST['wa_phone'            ] : '';
$cats_arr             = !empty($_POST['cats'                ]) ? $_POST['cats'                ] : array();

// old main cat id (used to update sitemap)
$orig_cat_id = !empty($_POST['orig_cat_id']) ? $_POST['orig_cat_id'] : '';
$orig_cat_slug = !empty($_POST['orig_cat_slug']) ? $_POST['orig_cat_slug'] : '';

// remove non numeric chars from whatsapp number
$wa_area_code = preg_replace("/[^0-9]/", "", $wa_area_code);
$wa_country_code = preg_replace("/[^0-9]/", "", $wa_country_code);
$wa_phone = preg_replace("/[^0-9]/", "", $wa_phone);

// trim
$address         = is_string($address)         ? trim($address)         : $address;
$area_code       = is_string($area_code)       ? trim($area_code)       : $area_code;
$country_code    = is_string($country_code)    ? trim($country_code)    : $country_code;
$business_hours  = is_string($business_hours)  ? trim($business_hours)  : $area_code;
$contact_email   = is_string($contact_email)   ? trim($contact_email)   : $contact_email;
$cross_street    = is_string($cross_street)    ? trim($cross_street)    : $cross_street;
$description     = is_string($description)     ? trim($description)     : $description;
$short_desc      = is_string($short_desc)      ? trim($short_desc)      : $short_desc;
$facebook        = is_string($facebook)        ? trim($facebook)        : $facebook;
$inside          = is_string($inside)          ? trim($inside)          : $inside;
$instagram       = is_string($instagram)       ? trim($instagram)       : $instagram;
$latlng          = is_string($latlng)          ? trim($latlng)          : $latlng;
$logo            = is_string($logo)            ? trim($logo)            : $logo;
$neighborhood    = is_string($neighborhood)    ? trim($neighborhood)    : $neighborhood;
$phone           = is_string($phone)           ? trim($phone)           : $phone;
$place_name      = is_string($place_name)      ? trim($place_name)      : $place_name;
$postal_code     = is_string($postal_code)     ? trim($postal_code)     : $postal_code;
$twitter         = is_string($twitter)         ? trim($twitter)         : $twitter;
$uploads         = is_string($uploads)         ? trim($uploads)         : $uploads;
$website         = is_string($website)         ? trim($website)         : $website;
$wa_area_code    = is_string($wa_area_code)    ? trim($wa_area_code)    : $wa_area_code;
$wa_country_code = is_string($wa_country_code) ? trim($wa_country_code) : $$wa_country_code;
$wa_phone        = is_string($wa_phone)        ? trim($wa_phone)        : $wa_phone;

// check user id who submitted this place (also get original logo)
$query = "SELECT userid, logo FROM places WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$place_userid = $row['userid'];
$original_logo = !empty($row['logo']) ? $row['logo'] : '';

// check if user has permission to edit this place
if($place_userid != $userid) {
	// logged in userid is different from this place's userid
	// maybe it's an admin
	if(!$is_admin) {
		die('No permission to edit this listing:' . $place_name);
	}
}

/*--------------------------------------------------
prepare vars
--------------------------------------------------*/

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

// normalize instagram url
$instagram = instagram_url(trim($instagram));

// clean and normalize website url
$website  = site_url(trim($website));

// short_desc length
$short_desc = mb_substr($short_desc, 0, $short_desc_length);

// find region id
if($city_id > 0) {
	$query = "SELECT state_id FROM cities WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$state_id = $row['state_id'];
}

else {
	$state_id = 0;
}

// status
$status = "pending";

// is auto approve enabled
if($cfg_auto_approve_listing) {
	$status = "approved";
}

if($is_admin == 1) {
	$status = "approved";
}

// get array of existing photos associated with this place, so it's possible to check ownership later
$existing_pics_in_db = array();
$query = "SELECT * FROM photos WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$existing_pics_in_db[] = array('dir' => $row['dir'], 'filename' => $row['filename']);
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

$result_message = '';

if($post_token == $session_token || $post_token == $cookie_token) {
	try {
		$conn->beginTransaction();

		// if new logo uploaded, delete original logo
		if(!empty($logo)) {
			if(!empty($original_logo)) {
				// delete old logo
				if(is_file($pic_basepath . '/logo/' . substr($original_logo, 0, 2) . '/' . $original_logo)) {
					unlink($pic_basepath . '/logo/' . substr($original_logo, 0, 2) . '/' . $original_logo);
				}

				// copy new logo
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
						/**
						* easy image resize function
						* @param  $file - file name to resize
						* @param  $string - The image data, as a string
						* @param  $width - new image width
						* @param  $height - new image height
						* @param  $proportional - keep image proportional, default is no
						* @param  $output - name of the new file (include path if needed)
						* @param  $delete_original - if true the original image will be deleted
						* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
						* @param  $quality - enter 1-100 (100 is best quality) default is 100
						* @return boolean|resource
						*/

						//smart_resize_image($path_final, null, $coupon_size[0], $coupon_size[1], false, 'file', true, false, 85);

						unlink($path_tmp);
					}
				}
			}
		}

		else {
			$logo = $original_logo;
		}

		// update places table
		$query = "UPDATE places SET
			address         = :address,
			area_code       = :area_code,
			country_code    = :country_code,
			business_hours  = :business_hours,
			city_id         = :city_id,
			cross_street    = :cross_street,
			contact_email   = :contact_email,
			short_desc      = :short_desc,
			description     = :description,
			facebook        = :facebook,
			inside          = :inside,
			instagram       = :instagram,
			lat             = :lat,
			lng             = :lng,
			logo            = :logo,
			neighborhood    = :neighborhood,
			phone           = :phone,
			place_name      = :place_name,
			postal_code     = :postal_code,
			state_id        = :state_id,
			status	        = :status,
			twitter         = :twitter,
			wa_area_code    = :wa_area_code,
			wa_country_code = :wa_country_code,
			wa_phone        = :wa_phone,
			website         = :website
			WHERE place_id  = :place_id";

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':address'        , $address);
		$stmt->bindValue(':area_code'      , $area_code);
		$stmt->bindValue(':country_code'   , $country_code);
		$stmt->bindValue(':business_hours' , $business_hours);
		$stmt->bindValue(':city_id'        , $city_id);
		$stmt->bindValue(':contact_email'  , $contact_email);
		$stmt->bindValue(':cross_street'   , $cross_street);
		$stmt->bindValue(':short_desc'     , $short_desc);
		$stmt->bindValue(':description'    , $description);
		$stmt->bindValue(':facebook'       , $facebook);
		$stmt->bindValue(':inside'         , $inside);
		$stmt->bindValue(':instagram'      , $instagram);
		$stmt->bindValue(':lat'            , $lat);
		$stmt->bindValue(':lng'            , $lng);
		$stmt->bindValue(':logo'           , $logo);
		$stmt->bindValue(':neighborhood'   , $neighborhood);
		$stmt->bindValue(':phone'          , $phone);
		$stmt->bindValue(':place_id'       , $place_id);
		$stmt->bindValue(':place_name'     , $place_name);
		$stmt->bindValue(':postal_code'    , $postal_code);
		$stmt->bindValue(':state_id'       , $state_id);
		$stmt->bindValue(':status'         , $status);
		$stmt->bindValue(':twitter'        , $twitter);
		$stmt->bindValue(':wa_area_code'   , $wa_area_code);
		$stmt->bindValue(':wa_country_code', $wa_country_code);
		$stmt->bindValue(':wa_phone'       , $wa_phone);
		$stmt->bindValue(':website'        , $website);
		$stmt->execute();

		/*--------------------------------------------------
		rel_place_cat
		--------------------------------------------------*/

		// first delete all categories for this place_id
		$query = "DELETE FROM rel_place_cat WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

		// $cat_id is the primary category
		// $_POST['cats'] is an array of secondary categories
		// modify $cats_arr structure to include is_main info and add $cat_id
		foreach($cats_arr as $k => $v) {
			if($v != $cat_id) {
				$cats_arr[$k] = array($v, 0);
			}

			// if same cat as primary cat, remove and add later
			else {
				unset($cats_arr[$k]);
			}
		}

		// add primary cat to the array
		$cats_arr[] = array($cat_id, 1);

		if(!empty($cats_arr)) {
			// build query
			$query = "INSERT IGNORE INTO rel_place_cat(place_id, cat_id, city_id, is_main) VALUES";

			$i = 1;
			foreach($cats_arr as $v) {
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

		// delete $delete_existing_pics
		// user deletes pictures that were previously submitted when adding or editing place before
		// $existing_pics_in_db[] = array('dir' => $row['dir'], 'filename' => $row['filename']);
		if(!empty($delete_existing_pics)) {
			$where_clause = '';
			foreach($delete_existing_pics as $k => $v) {
				if(in_array($v, array_column($existing_pics_in_db, 'filename'))) {
					$key = array_search($v, array_column($existing_pics_in_db, 'filename'));
					$dir = $existing_pics_in_db[$key]['dir'];
					$pic_full = $pic_basepath . '/' . $place_full_folder . '/' . $dir . '/' . $v;
					$pic_thumb = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir . '/' . $v;

					if(is_file($pic_full)) {
						unlink($pic_full);
					}

					if(is_file($pic_thumb)) {
						unlink($pic_thumb);
					}

					// delete existing pics from db
					$query = "DELETE FROM photos WHERE filename = :filename";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();
				}
			}
		}

		// check how many photos this listing have prior to the newly uploaded images and after deleting pics that were removed
		$query = "SELECT COUNT(*) AS num_pics FROM photos WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$num_pics_in_db = $row['num_pics'];

		// uploaded images
		if(!empty($uploads)) {
			// define dirs
			$query = "SELECT photo_id FROM photos ORDER BY photo_id DESC LIMIT 1";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$last_photo_id = $row['photo_id'];
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

			// uploads counter
			$pic_count = 1;

			foreach($uploads as $k => $v) {
				// this tmp file
				$tmp_file = $tmp_folder . '/' . $v;

				// if total pics <= max_pics
				if($pic_count + $num_pics_in_db < $max_pics + 1) {
					// only insert into db if the move from temp to final destination folder is successful,
					// otherwise user could send custom uploads[] value and replace original(thus deleting) previous pics
					// from other ads
					if(copy($tmp_file, $dir_full . '/' . $v)) {
						// insert into db
						$stmt = $conn->prepare('
						INSERT INTO photos(
							place_id,
							dir,
							filename
							)
						VALUES(
							:place_id,
							:dir,
							:filename
							)
						');

						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':dir', $dir_num);
						$stmt->bindValue(':filename', $v);
						$stmt->execute();
					}

					smart_resize_image($tmp_file, null, $global_thumb_width, $global_thumb_height, false, $dir_thumb . '/' . $v, true, false, 85);

					// now delete entries in tmp_photos table
					$query = "DELETE FROM tmp_photos WHERE filename = :filename";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();

					// if user uploaded more than max_pics, ignore further uploads
					$pic_count++;
				}

				// else delete uploaded pic from tmp folder
				else {
					if(is_file($tmp_file)) {
						unlink($tmp_file);
					}
				}
			}
		} // end if(!empty($uploads))

		/*--------------------------------------------------
		Custom fields 2
		--------------------------------------------------*/
		$query = "DELETE FROM rel_place_custom_fields WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

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
		// first delete existing videos
		$query = "DELETE FROM videos WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

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

		$conn->commit();
		$result_message = $txt_success;

		/*--------------------------------------------------
		sitemap
		--------------------------------------------------*/
		if($cfg_enable_sitemaps) {
			// ($place_id, $place_slug, $cat_id, $cat_slug, $city_id, $city_slug, $state_slug, $cfg_permalink_struct)
			// if category changed, it means that the permalink might have changed.
			if($orig_cat_id != $cat_id) {
				// $cfg_permalink_struct = '%region%/%city%/%category%/%title%';
				$permalink_arr = explode('/', $cfg_permalink_struct);

				if(in_array('%category%', $permalink_arr)) {
					$orig_listing_link = get_listing_link($place_id, '', $orig_cat_id, $orig_cat_slug, $city_id, '', '', $cfg_permalink_struct);
					sitemap_remove_url($orig_listing_link);

					$new_listing_link = get_listing_link($place_id, '', $cat_id, '', $city_id, '', '', $cfg_permalink_struct);
					sitemap_add_url($new_listing_link);
				}
			}

			else {
				$listing_link = get_listing_link($place_id, '', $cat_id, '', $city_id, '', '', $cfg_permalink_struct);
				sitemap_update_lastmod($listing_link);
			}
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();
	}

	// empty session submit token
	unset($_SESSION['submit_token']);
}

// translation replacements
$result_message = str_replace('%place_name%', $place_name, $result_message);
$txt_main_title = str_replace('%place_name%', $place_name, $txt_main_title);

/*--------------------------------------------------
Notify listing update
--------------------------------------------------*/

if($status == 'pending') {
	if($mail_after_post) {
		// listing link
		// function get_listing_link($place_id, $place_slug = '', $cat_id = '', $cat_slug = '', $city_id = '', $city_slug = '', $state_slug = '', $cfg_permalink_struct = 'listing')
		$listing_url = get_listing_link($place_id, '', $cat_id, '', $city_id, '', '', $cfg_permalink_struct);

		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'process_edit_listing'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];

		// string replacements listing_slug
		$email_body = str_replace('%edited_listing_url%', $listing_url, $email_body);

		try {
			// mailer params
			$PHPMailer->ClearAllRecipients();
			$PHPMailer->setFrom($admin_email, $site_name);
			$PHPMailer->addAddress($admin_email);
			$PHPMailer->isHTML(false);
			$PHPMailer->Subject = $email_subject;
			$PHPMailer->Body = $email_body;

			// send
			$PHPMailer->send();
		} catch (Exception $e) {
			echo '<!-- ' . "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}" . ' -->';
		}
	}
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/process-edit-listing';
