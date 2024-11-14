<?php
// prevent direct access
if (!isset($version)) {
	http_response_code(403);
	exit;
}

// get installed languages
$lang_arr = array();

$query = "SELECT DISTINCT lang FROM language";
$stmt  = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$lang_arr[] = $row['lang'];
}

/*--------------------------------------------------
v.3.12 update
--------------------------------------------------*/

// if 'rel_favorites' table doesn't exist, update to v.3.12
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'rel_favorites')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.12_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.13 update
--------------------------------------------------*/

// if 'plan_types' table doesn't exist, update to v.3.13
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'plan_types')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.13_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.21 update
--------------------------------------------------*/

// if 'language' table doesn't exist, update to v.3.21
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'language')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.21_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.22 update
--------------------------------------------------*/

$query = "UPDATE language SET translated='Please submit the form again' WHERE var_name='txt_submit_again' AND translated LIKE '%//localhost%'";
$stmt  = $conn->prepare($query);
$stmt->execute();

$query = "DELETE FROM language WHERE var_name='baseurl'";
$stmt  = $conn->prepare($query);
$stmt->execute();

/*--------------------------------------------------
v.3.25 update
--------------------------------------------------*/

// if 'translation_cf' table doesn't exist, update to v.3.25
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'translation_cf')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.25_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.27 update
--------------------------------------------------*/

$query = "SELECT count(*) AS c FROM language WHERE var_name = 'txt_permalink_struct'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.27_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// add new txt strings for other languages
		foreach($lang_arr as $v) {
			$query = "INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
	('$v', 'admin', 'language', 'txt_create_string', 'Create String'),
	('$v', 'admin', 'language', 'txt_var_name', 'Variable Name (starts with txt_ e.g. txt_var_name)'),
	('$v', 'admin', 'language', 'txt_string_value', 'String Value'),
	('$v', 'admin', 'language', 'txt_string_created', 'String Created'),
	('$v', 'admin', 'admin-global', 'txt_maps', 'Maps'),
	('$v', 'admin', 'settings', 'txt_permalink_struct', 'Permalink Structure (*regenerate sitemap after change)'),
	('$v', 'admin', 'settings', 'txt_permalink_struct_explain', 'Available tags(use / as separator): %category%/%region%/%city%/%title%');";
			$stmt  = $conn->prepare($query);
			$stmt->execute();
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.29 update
--------------------------------------------------*/

// new language strings
$query = "SELECT count(*) AS c FROM language WHERE var_name = 'txt_primary_category'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	try {
		// begin transaction
		$conn->beginTransaction();

		// add strings to each language
		foreach($lang_arr as $v) {
			$query = "INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
	('$v', 'user', 'create-listing', 'txt_primary_category', 'Primary Category'),
	('$v', 'user', 'create-listing', 'txt_additional_categories', 'Additional Categories'),
	('$v', 'user', 'edit-listing', 'txt_primary_category', 'Primary Category'),
	('$v', 'user', 'edit-listing', 'txt_additional_categories', 'Additional Categories')";
			$stmt  = $conn->prepare($query);
			$stmt->execute();
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

// import the 3.29 update sql file
$query = "SELECT COUNT(COLUMN_NAME) AS total_count FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'rel_place_cat' AND table_schema = '$db_name' AND column_name = 'is_main'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['total_count'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.29_update.sql');
	$sql = explode(";\n", $sql);

	foreach($sql as $k => $v) {
		$stmt = $conn->prepare($v);
		$stmt->execute();
	}
}

/*--------------------------------------------------
v.3.31
--------------------------------------------------*/

// new language strings
$query = "SELECT count(*) AS c FROM language WHERE template='results' AND var_name='txt_latest_listings'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	try {
		// begin transaction
		$conn->beginTransaction();

		// add strings to each language
		foreach($lang_arr as $v) {
			$query = "INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
	('$v', 'public', 'results', 'txt_latest_listings', 'Latest Listings')";
			$stmt  = $conn->prepare($query);
			$stmt->execute();
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.32 update
--------------------------------------------------*/

/*
NEW TEXT STRINGS FOR v.3.32
also must include in
	directoryplus.sql,
	directoryplus_varchar190.sql,
	lang_xx.sql.
DO NOT INCLUDE IN directoryplus_3.32_update.sql
*/
// add strings to each language
// new language strings
$query = "SELECT count(*) AS c FROM language WHERE template='settings' AND var_name='txt_nearby_filter_values'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	foreach($lang_arr as $v) {
		$query = "INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
					('$v', 'public', 'global', 'txt_nearby', 'Nearby'),
					('$v', 'public', 'global', 'txt_enable_geo', 'Geolocation is not enabled. Please enable to use this feature'),
					('$v', 'admin', 'settings', 'txt_nearby_filter_values', 'Nearby filter values'),
					('$v', 'admin', 'settings', 'txt_distance_unit', 'Distance unit'),
					('$v', 'public', 'global', 'txt_filters', 'Filters'),
					('$v', 'public', 'global', 'txt_from', 'From'),
					('$v', 'public', 'global', 'txt_to', 'To')";
		$stmt  = $conn->prepare($query);
		$stmt->execute();
	}

	// make txt_send_email var global
	$query = "UPDATE `language` SET template = 'global' WHERE var_name = 'txt_send_email'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();
}

/*--------------------------------------------------
v.3.37 update
--------------------------------------------------*/

/*
NEW TEXT STRINGS FOR v.3.37
also must include in
	directoryplus.sql,
	directoryplus_varchar190.sql,
	lang_xx.sql.
DO NOT INCLUDE IN directoryplus_3.37_update.sql
*/

// check if a new txt_ string for v.3.37 exists
$query = "SELECT count(*) AS c FROM language WHERE template='settings' AND var_name='txt_example'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	// removed vars in v.3.37
	$query = "DELETE FROM `language`
				WHERE `section`='admin'
				AND (var_name='txt_show' OR var_name='txt_link' OR var_name='txt_view')";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	// txt vars that had var_name modified
	$query = "UPDATE `language` SET var_name='txt_html_title' WHERE var_name='txt_show_html_title'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	$query = "UPDATE `language` SET var_name='txt_main_title' WHERE var_name='txt_show_main_title'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	$query = "UPDATE `language` SET var_name='txt_create_field' WHERE var_name='txt_show_create_field'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	$query = "UPDATE `language` SET var_name='txt_edit_field' WHERE var_name='txt_show_edit_field'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	$query = "UPDATE `language` SET var_name='txt_remove_field' WHERE var_name='txt_show_remove_field'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();

	// now add new strings
	foreach($lang_arr as $v) {
		$query = "INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
		('$v', 'admin', 'admin-global', 'txt_filter', 'Filter'),
		('$v', 'admin', 'admin-global', 'txt_show', 'Show'),
		('$v', 'admin', 'admin-global', 'txt_all', 'All'),
		('$v', 'admin', 'custom-fields', 'txt_all_categories', 'All categories'),
		('$v', 'admin', 'custom-fields', 'txt_all_groups', 'All groups'),
		('$v', 'admin', 'custom-fields', 'txt_create_group', 'Create group'),
		('$v', 'admin', 'custom-fields', 'txt_default_group', 'Default group'),
		('$v', 'admin', 'custom-fields', 'txt_edit_group', 'Edit group'),
		('$v', 'admin', 'custom-fields', 'txt_field_group', 'Field group'),
		('$v', 'admin', 'custom-fields', 'txt_fields', 'Fields'),
		('$v', 'admin', 'custom-fields', 'txt_fields_removed', 'Fields removed successfully'),
		('$v', 'admin', 'custom-fields', 'txt_group', 'Group'),
		('$v', 'admin', 'custom-fields', 'txt_group_created', 'Group created'),
		('$v', 'admin', 'custom-fields', 'txt_group_name', 'Group name'),
		('$v', 'admin', 'custom-fields', 'txt_group_order', 'Group order'),
		('$v', 'admin', 'custom-fields', 'txt_group_restored', 'Group restored successfully'),
		('$v', 'admin', 'custom-fields', 'txt_groups', 'Groups'),
		('$v', 'admin', 'custom-fields', 'txt_groups_removed', 'Groups removed successfully'),
		('$v', 'admin', 'custom-fields', 'txt_remove_fields_sure', 'Are you sure you want to remove these fields permanently? This action cannot be undone.'),
		('$v', 'admin', 'custom-fields', 'txt_remove_group', 'Remove group'),
		('$v', 'admin', 'custom-fields', 'txt_remove_group_sure', 'Are you sure you want to remove this group permanently? This action cannot be undone.'),
		('$v', 'admin', 'custom-fields', 'txt_remove_groups_sure', 'Are you sure you want to remove these groups permanently? This action cannot be undone.'),
		('$v', 'admin', 'custom-fields', 'txt_show_icon', 'Show field icon'),
		('$v', 'admin', 'custom-fields', 'txt_show_in_results', 'Show in results'),
		('$v', 'admin', 'custom-fields', 'txt_show_name', 'Show field name'),
		('$v', 'admin', 'custom-fields', 'txt_show_name_icon', 'Show field name and icon'),
		('$v', 'admin', 'custom-fields', 'txt_value_unit', 'Unit of measurement'),
		('$v', 'admin', 'listings', 'txt_featured_home', 'Featured homepage'),
		('$v', 'admin', 'locations', 'txt_city_photo', 'City Photo'),
		('$v', 'admin', 'settings', 'txt_date_format', 'Date Format'),
		('$v', 'admin', 'settings', 'txt_decimal_separator', 'Decimal separator'),
		('$v', 'admin', 'settings', 'txt_example', 'example'),
		('$v', 'admin', 'settings', 'txt_from_email', 'From Email'),
		('$v', 'admin', 'settings', 'txt_languages', 'Languages (Ex: en,es,de)'),
		('$v', 'admin', 'settings', 'txt_latest_listings_count', 'Latest listings count'),
		('$v', 'admin', 'settings', 'txt_logo_width', 'Logo width'),
		('$v', 'admin', 'settings', 'txt_near_listings_radius', 'Near Listings Radius'),
		('$v', 'admin', 'settings', 'txt_new_sign_up_notification', 'New sign up notification'),
		('$v', 'admin', 'settings', 'txt_use_select2', 'Use Select2'),
		('$v', 'admin', 'users', 'txt_toggle_active', 'Toggle active/inactive'),
		('$v', 'user', 'my-profile', 'txt_email_already_in_use', 'This email is already in use.')
		;";
		$stmt  = $conn->prepare($query);
		$stmt->execute();
	}
}

// import the 3.37 update sql file
// if 'custom_fields_groups' table doesn't exist, update to v.3.37
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'custom_fields_groups')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.37_update.sql');
	$sql = explode(";\n", $sql);

	try {
		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			$v = trim($v);

			if(!empty($v)) {
				$stmt = $conn->prepare($v);
				$stmt->execute();
			}
		}

		// commit
		if($conn->inTransaction()) {
			$conn->commit();
		}
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
v.3.39 update
--------------------------------------------------*/

// removed vars in v.3.39
$query = "DELETE FROM `language`
			WHERE
			(var_name='txt_icon' AND template='create-custom-field')
			OR (var_name='txt_icon' AND template='edit-custom-field')
			OR (var_name='txt_icon' AND template='custom-fields-trash')
			OR (var_name='txt_categories' AND template='create-custom-field')
			OR (var_name='txt_field_created' AND template='create-custom-field')
			OR (var_name='txt_categories' AND template='custom-fields-trash')
			OR (var_name='txt_create_another' AND template='custom-fields-trash')
			OR (var_name='txt_create_field' AND template='custom-fields-trash')
			OR (var_name='txt_edit_field' AND template='custom-fields-trash')
			OR (var_name='txt_field_created' AND template='custom-fields-trash')
			OR (var_name='txt_field_name' AND template='custom-fields-trash')
			OR (var_name='txt_field_order' AND template='custom-fields-trash')
			OR (var_name='txt_field_removed' AND template='custom-fields-trash')

			OR (var_name='txt_asearch')
			";
$stmt  = $conn->prepare($query);
$stmt->execute();

/*--------------------------------------------------
v.3.40 update
--------------------------------------------------*/

// import the 3.40 update sql file
$query = "SELECT COUNT(COLUMN_NAME) AS total_count FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'places' AND table_schema = '$db_name' AND column_name = 'instagram'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['total_count'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.40_update.sql');
	$sql = explode(";\n", $sql);

	foreach($sql as $k => $v) {
		$stmt = $conn->prepare($v);
		$stmt->execute();
	}
}

/*--------------------------------------------------
v.3.46 update
--------------------------------------------------*/

try {
    $conn->query('ALTER TABLE `reviews` ADD INDEX `status` (`status`);');
} catch (PDOException $ex) {
	// do nothing
}

try {
    $conn->query('ALTER TABLE `users` ADD INDEX `status` (`status`);');
} catch (PDOException $ex) {
	// do nothing
}

try {
    $conn->query('ALTER TABLE `places` ADD INDEX `state_id` (`state_id`);');
} catch (PDOException $ex) {
	// do nothing
}

try {
    $conn->query('ALTER TABLE `places` ADD INDEX `feat` (`feat`);');
} catch (PDOException $ex) {
	// do nothing
}