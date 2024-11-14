<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// get post id
if(!ctype_digit($route[2])) {
	header("HTTP/1.0 404 Not Found");
	die('404 Not Found');
}

$place_id = $route[2];

/*--------------------------------------------------
Listing info
--------------------------------------------------*/
$query = "SELECT * FROM places WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(empty($row)) {
	header("HTTP/1.0 404 Not Found");
	die('404 Not Found');
}

$address         = !empty($row['address'        ]) ? $row['address'        ] : '';
$area_code       = !empty($row['area_code'      ]) ? $row['area_code'      ] : '';
$country_code    = !empty($row['country_code'   ]) ? $row['country_code'   ] : '';
$business_hours  = !empty($row['business_hours' ]) ? $row['business_hours' ] : '';
$city_id         = !empty($row['city_id'        ]) ? $row['city_id'        ] : 0;
$contact_email   = !empty($row['contact_email'  ]) ? $row['contact_email'  ] : '';
$cross_street    = !empty($row['cross_street'   ]) ? $row['cross_street'   ] : '';
$short_desc      = !empty($row['short_desc'     ]) ? $row['short_desc'     ] : '';
$description     = !empty($row['description'    ]) ? $row['description'    ] : '';
$facebook        = !empty($row['facebook'       ]) ? $row['facebook'       ] : '';
$instagram       = !empty($row['instagram'      ]) ? $row['instagram'      ] : '';
$inside          = !empty($row['inside'         ]) ? $row['inside'         ] : '';
$lat             = !empty($row['lat'            ]) ? $row['lat'            ] : '';
$lng             = !empty($row['lng'            ]) ? $row['lng'            ] : '';
$logo            = !empty($row['logo'           ]) ? $row['logo'           ] : '';
$neighborhood    = !empty($row['neighborhood'   ]) ? $row['neighborhood'   ] : 0;
$paid            = !empty($row['paid'           ]) ? $row['paid'           ] : 0;
$phone           = !empty($row['phone'          ]) ? $row['phone'          ] : '';
$place_name      = !empty($row['place_name'     ]) ? $row['place_name'     ] : '';
$place_userid    = !empty($row['userid'         ]) ? $row['userid'         ] : 1;
$postal_code     = !empty($row['postal_code'    ]) ? $row['postal_code'    ] : '';
$slug            = !empty($row['slug'           ]) ? $row['slug'           ] : '';
$state_id        = !empty($row['state_id'       ]) ? $row['state_id'       ] : 0;
$status          = !empty($row['status'         ]) ? $row['status'         ] : '';
$submission_date = !empty($row['submission_date']) ? $row['submission_date'] : '';
$twitter         = !empty($row['twitter'        ]) ? $row['twitter'        ] : '';
$website         = !empty($row['website'        ]) ? $row['website'        ] : '';
$wa_country_code = !empty($row['wa_country_code']) ? $row['wa_country_code'] : '';
$wa_area_code    = !empty($row['wa_area_code'   ]) ? $row['wa_area_code'   ] : '';
$wa_phone        = !empty($row['wa_phone'       ]) ? $row['wa_phone'       ] : '';

// sanitize
$address         = e($address        );
$area_code       = e($area_code      );
$country_code    = e($country_code   );
$business_hours  = e($business_hours );
$city_id         = e($city_id        );
$contact_email   = e($contact_email  );
$cross_street    = e($cross_street   );
$short_desc      = e($short_desc     );
$description     = e($description    );
$facebook        = e($facebook       );
$inside          = e($inside         );
$instagram       = e($instagram      );
$lat             = e($lat            );
$lng             = e($lng            );
$logo            = e($logo           );
$neighborhood    = e($neighborhood   );
$paid            = e($paid           );
$phone           = e($phone          );
$place_name      = e($place_name     );
$place_userid    = e($place_userid   );
$postal_code     = e($postal_code    );
$slug            = e($slug           );
$state_id        = e($state_id       );
$status          = e($status         );
$submission_date = e($submission_date);
$twitter         = e($twitter        );
$website         = e($website        );
$wa_country_code = e($wa_country_code);
$wa_area_code    = e($wa_area_code   );
$wa_phone        = e($wa_phone       );

// check if user owns this place
if($place_userid != $userid) {
	// logged in userid is different from this place's userid
	// maybe it's an admin
	if(!$is_admin) {
		die('no permission to edit this listing');
	}
}

/*--------------------------------------------------
City details
--------------------------------------------------*/
$query = "SELECT * FROM cities WHERE city_id = :city_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $city_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$city_name = $row['city_name'];
$state_abbr = $row['state'];

// get neighborhood details
$query = "SELECT * FROM neighborhoods WHERE neighborhood_id = :neighborhood_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':neighborhood_id', $neighborhood);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$neighborhood_slug = !empty($row['neighborhood_slug']) ? $row['neighborhood_slug'] : '';
$neighborhood_name = !empty($row['neighborhood_name']) ? $row['neighborhood_name'] : '';

// sanitize
$neighborhood_slug = e($neighborhood_slug);
$neighborhood_name = e($neighborhood_name);

/*--------------------------------------------------
Category details
--------------------------------------------------*/

// init
$place_cats  = array();
$cats_ids    = array();
$primary_cat = 0;

$query = "SELECT * FROM rel_place_cat
			INNER JOIN cats ON rel_place_cat.cat_id = cats.id
			WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id   = !empty($row['cat_id']) ? $row['cat_id'] : '';
	$this_cat_name = !empty($row['name'  ]) ? $row['name'  ] : '';
	$this_cat_slug = !empty($row['slug'  ]) ? $row['slug'  ] : '';

	$cur_loop_arr = array(
		'cat_id' => $this_cat_id,
		'name'   => $this_cat_name,
		'slug'   => $this_cat_slug,
	);

	if($row['is_main'] == 1) {
		$primary_cat = $this_cat_id;
		$primary_slug = $this_cat_slug;
	}

	$place_cats[] = $cur_loop_arr;
	$cats_ids[] = $this_cat_id;
}

/*--------------------------------------------------
Logo
--------------------------------------------------*/
$logo_url = $baseurl . '/assets/imgs/blank.png';

if(!empty($logo)) {
	if(file_exists($pic_basepath . '/logo/' . substr($logo, 0, 2) . '/' . $logo)) {
		$logo_url = $pic_baseurl . '/logo/' . substr($logo, 0, 2) . '/' . $logo;
	}
}


/*--------------------------------------------------
Photos
--------------------------------------------------*/
$query = "SELECT * FROM photos WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$place_photos = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_photo_id = !empty($row['photo_id']) ? $row['photo_id'] : '';
	$this_dir      = !empty($row['dir'     ]) ? $row['dir'     ] : '';
	$this_filename = !empty($row['filename']) ? $row['filename'] : '';

	// sanitize
	$this_dir      = e($this_dir     );
	$this_filename = e($this_filename);

	$place_photos[] = array(
		'photo_id' => $this_photo_id,
		'dir'      => $this_dir,
		'filename' => $this_filename,
	);
}

/*--------------------------------------------------
Videos
--------------------------------------------------*/
$place_videos = array();

$query = "SELECT * FROM videos WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$video_id = !empty($row['video_id']) ? $row['video_id'] : '';
	$video_url = !empty($row['video_url']) ? $row['video_url'] : '';

	// sanitize
	$video_url = valid_url($video_url);

	if(!empty($video_url)) {
		$place_videos[] = array(
			'video_id' => $video_id,
			'video_url' => $video_url,
		);
	}
}

/*--------------------------------------------------
Custom fields: Global fields
--------------------------------------------------*/

// init arrays
$custom_fields = array();
$custom_fields_ids = array();

//
if(!empty($place_id)) {
	// the first subquery (s1) gets all global custom fields
	// the second subquery (s2) gets the values for this place for each custom field from s1
	$query = "SELECT s1.*, s2.field_value
			FROM
				(
				SELECT cf.*, g.*, tr.field_name AS tr_field_name, tr.tooltip AS tr_tooltip, tr.values_list AS tr_values_list,
					trg.group_name AS tr_group_name
					FROM custom_fields cf
					LEFT JOIN custom_fields_groups g ON cf.field_group = g.group_id
					LEFT JOIN rel_cat_custom_fields ON cf.field_id = rel_cat_custom_fields.field_id
					LEFT JOIN translation_cf tr ON cf.field_id = tr.field_id AND tr.lang = :html_lang
					LEFT JOIN translation_cf_groups trg ON g.group_id = trg.group_id AND trg.lang = :html_lang_2
					WHERE rel_cat_custom_fields.rel_id IS NULL AND cf.field_status = 1
					ORDER BY cf.field_id
				) s1
			LEFT JOIN
				(
				SELECT place_id, field_id AS field_id2, GROUP_CONCAT(field_value SEPARATOR ':::') AS field_value
					FROM rel_place_custom_fields
					WHERE place_id = :place_id
					GROUP BY field_id
				) s2
			ON s2.field_id2 = s1.field_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->bindValue(':html_lang', $html_lang);
	$stmt->bindValue(':html_lang_2', $html_lang);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_group = !empty($row['field_group']) ? $row['field_group'] : 1;
		$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : 0;
		$this_field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
		$this_field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : 'text';
		$this_field_value = !empty($row['field_value']) ? $row['field_value'] : '';
		$this_group_name  = !empty($row['group_name' ]) ? $row['group_name' ] : '';
		$this_group_order = !empty($row['group_order']) ? $row['group_order'] : 0;
		$this_icon        = !empty($row['icon'       ]) ? $row['icon'       ] : '';
		$this_required    = !empty($row['required'   ]) ? $row['required'   ] : 0;
		$this_searchable  = !empty($row['searchable' ]) ? $row['searchable' ] : 0;
		$this_tooltip     = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
		$this_values_list = !empty($row['values_list']) ? $row['values_list'] : '';

		// required
		$this_required = !empty($this_required) ? 'required' : '';

		// field translation values
		$this_tr_field_name  = !empty($row['tr_field_name' ]) ? $row['tr_field_name' ] : $this_field_name;
		$this_tr_group_name  = !empty($row['tr_group_name' ]) ? $row['tr_group_name' ] : $this_group_name;
		$this_tr_tooltip     = !empty($row['tr_tooltip'    ]) ? $row['tr_tooltip'    ] : $this_tooltip;
		$this_tr_values_list = !empty($row['tr_values_list']) ? $row['tr_values_list'] : $this_values_list;

		// escape
		$this_field_name     = e($this_field_name    );
		$this_field_value    = e($this_field_value   );
		$this_group_name     = e($this_group_name    );
		$this_tooltip        = e($this_tooltip       );
		$this_tr_field_name  = e($this_tr_field_name );
		$this_tr_group_name  = e($this_tr_group_name );
		$this_tr_tooltip     = e($this_tr_tooltip    );
		$this_tr_values_list = e($this_tr_values_list);
		$this_values_list    = e($this_values_list   );

		// add to global custom fields array
		$custom_fields[$this_field_id] = array(
			'field_group'    => $this_field_group,
			'field_id'       => $this_field_id,
			'field_name'     => $this_field_name,
			'field_type'     => $this_field_type,
			'field_value'    => $this_field_value,
			'group_name'     => $this_group_name,
			'group_order'    => $this_group_order,
			'icon'           => $this_icon,
			'required'       => $this_required,
			'searchable'     => $this_searchable,
			'tooltip'        => $this_tooltip,
			'tr_field_name'  => $this_tr_field_name,
			'tr_group_name'  => $this_tr_group_name,
			'tr_tooltip'     => $this_tr_tooltip,
			'tr_values_list' => $this_tr_values_list,
			'values_list'    => $this_values_list,
		);

		$custom_fields_ids[] = $this_field_id;
	}
}

/*--------------------------------------------------
Custom fields: Category fields
--------------------------------------------------*/

// init
$cat_fields = array();

// in string
$in = '';

foreach($cats_ids as $v) {
	if(is_numeric($v)) {
		$in .= "$v,";
	}
}

$in = rtrim($in, ',');

// query
if(!empty($place_id) && !empty($cats_ids)) {
	// the first subquery (s1) gets all category fields
	// the second subquery (s2) gets the values for this place for the custom fields from s1
	$query = "SELECT s1.*, s2.field_value FROM
				(
				SELECT cf.*, g.*, tr.field_name AS tr_field_name, tr.tooltip AS tr_tooltip, tr.values_list AS tr_values_list,
					trg.group_name AS tr_group_name
					FROM rel_cat_custom_fields rc
					LEFT JOIN custom_fields cf ON rc.field_id = cf.field_id
					LEFT JOIN custom_fields_groups g ON cf.field_group = g.group_id
					LEFT JOIN translation_cf tr ON cf.field_id = tr.field_id AND tr.lang = :html_lang
					LEFT JOIN translation_cf_groups trg ON g.group_id = trg.group_id AND trg.lang = :html_lang_2
					WHERE rc.cat_id IN($in) AND cf.field_status = 1
				) s1
			LEFT JOIN
				(
				SELECT place_id, field_id AS field_id2, GROUP_CONCAT(field_value SEPARATOR ':::') AS field_value
					FROM rel_place_custom_fields
					WHERE place_id = :place_id
					GROUP BY field_id
				) s2
			ON s2.field_id2 = s1.field_id";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->bindValue(':html_lang', $html_lang);
	$stmt->bindValue(':html_lang_2', $html_lang);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_field_group = !empty($row['field_group']) ? $row['field_group'] : 1;
		$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : 0;
		$this_field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
		$this_field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : 'text';
		$this_field_value = !empty($row['field_value']) ? $row['field_value'] : '';
		$this_group_name  = !empty($row['group_name' ]) ? $row['group_name' ] : '';
		$this_group_order = !empty($row['group_order']) ? $row['group_order'] : 0;
		$this_icon        = !empty($row['icon'       ]) ? $row['icon'       ] : '';
		$this_required    = !empty($row['required'   ]) ? $row['required'   ] : 0;
		$this_searchable  = !empty($row['searchable' ]) ? $row['searchable' ] : 0;
		$this_tooltip     = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
		$this_values_list = !empty($row['values_list']) ? $row['values_list'] : '';

		// required
		$this_required = !empty($this_required) ? 'required' : '';

		// field translation values
		$this_tr_field_name  = !empty($row['tr_field_name' ]) ? $row['tr_field_name' ] : $this_field_name;
		$this_tr_group_name  = !empty($row['tr_group_name' ]) ? $row['tr_group_name' ] : $this_group_name;
		$this_tr_tooltip     = !empty($row['tr_tooltip'    ]) ? $row['tr_tooltip'    ] : $this_tooltip;
		$this_tr_values_list = !empty($row['tr_values_list']) ? $row['tr_values_list'] : $this_values_list;

		// escape
		$this_field_name     = e($this_field_name    );
		$this_field_value    = e($this_field_value   );
		$this_group_name     = e($this_group_name    );
		$this_tooltip        = e($this_tooltip       );
		$this_tr_field_name  = e($this_tr_field_name );
		$this_tr_group_name  = e($this_tr_group_name );
		$this_tr_tooltip     = e($this_tr_tooltip    );
		$this_tr_values_list = e($this_tr_values_list);
		$this_values_list    = e($this_values_list   );

		// add to category custom fields array
		if(!empty($this_field_id)) {
			$cat_fields[$this_field_id] = array(
				'field_group'    => $this_field_group,
				'field_id'       => $this_field_id,
				'field_name'     => $this_field_name,
				'field_type'     => $this_field_type,
				'field_value'    => $this_field_value,
				'group_name'     => $this_group_name,
				'group_order'    => $this_group_order,
				'icon'           => $this_icon,
				'required'       => $this_required,
				'searchable'     => $this_searchable,
				'tooltip'        => $this_tooltip,
				'tr_field_name'  => $this_tr_field_name,
				'tr_group_name'  => $this_tr_group_name,
				'tr_tooltip'     => $this_tr_tooltip,
				'tr_values_list' => $this_tr_values_list,
				'values_list'    => $this_values_list,
			);

			$custom_fields_ids[] = $this_field_id;
		}
	}
}

if(!empty($custom_fields_ids)) {
	$custom_fields_ids = implode(',', $custom_fields_ids);
}

else {
	$custom_fields_ids = '';
}

/*--------------------------------------------------
Custom fields groups
--------------------------------------------------*/

// init
$fields_groups = array();

// add to array of custom fields groups
foreach($custom_fields as $k => $v) {
	$fields_groups[$v['field_group']] = array(
			'group_id'    => $v['field_group'],
			'group_order' => $v['group_order'],
			'group_name'  => $v['group_name'],
		);
}

// add to array of custom fields groups
foreach($cat_fields as $k => $v) {
	$fields_groups[$v['field_group']] = array(
			'group_id'    => $v['field_group'],
			'group_order' => $v['group_order'],
			'group_name'  => $v['group_name'],
		);
}

// sort by group order
$keys = array_column($fields_groups, 'group_order');
array_multisort($keys, SORT_ASC, $fields_groups);

/*--------------------------------------------------
All categories array
--------------------------------------------------*/

// init cat name translations array
$cat_translations = array();

// get category name translations
$query = "SELECT * FROM config WHERE type = 'cat-lang'";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id = !empty($row['property' ]) ? $row['property'] : '';
	$this_value  = !empty($row['value'    ]) ? $row['value'   ] : '';

	$this_value = explode(';', $this_value);

	if($this_value[0] == $html_lang) {
		$cat_translations[$this_cat_id] = array($this_value[1], $this_value[2]);
	}
}

// all cats array
$cats_arr = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
	$this_cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
	$this_plural_name = !empty($row['plural_name']) ? $row['plural_name'] : '';
	$this_parent_id   = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;

	// cat name translation
	if(isset($cat_translations[$this_cat_id])) {
		if(isset($cat_translations[$this_cat_id][1])) {
			$this_cat_name = $cat_translations[$this_cat_id][1];
		}

		if(isset($cat_translations[$this_cat_id][2])) {
			$this_plural_name = $cat_translations[$this_cat_id][2];
		}
	}

	// final values
	$cur_loop_arr = array(
		'cat_id'      => $this_cat_id,
		'cat_name'    => $this_cat_name,
		'plural_name' => $this_plural_name,
		'parent_id'   => $this_parent_id,
	);

	$cats_arr[] = $cur_loop_arr;
}

// group by parents
$cats_grouped_by_parent = group_cats_by_parent($cats_arr);

// checked cats
$checked_cats = array();

$query = "SELECT cat_id FROM rel_place_cat WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$checked_cats[] = $row['cat_id'];
}

// set checked cats to an array of -1, so that it's not empty and is sure to not have a corresponding category, so that the function show_cats() doesn't return any checked checkbox
if(empty($checked_cats)) {
	$checked_cats[] = -1;
}

/*--------------------------------------------------
translation replacements
--------------------------------------------------*/
$txt_sub_header = str_replace('%place_name%', $place_name, $txt_sub_header);

/*--------------------------------------------------
session to prevent multiple form submissions
--------------------------------------------------*/
$submit_token = uniqid('', true);
$_SESSION['submit_token'] = $submit_token;

// also set cookie in case session expires
$_COOKIE['submit_token'] = $submit_token;

/*--------------------------------------------------
Legacy compatibility
--------------------------------------------------*/
$specialties = $short_desc;

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/edit-listing';
