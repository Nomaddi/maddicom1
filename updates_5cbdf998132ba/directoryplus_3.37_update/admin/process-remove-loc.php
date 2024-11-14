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

// loc details
$loc_id    = $_POST['loc_id'];
$loc_type = $_POST['loc_type'];

if($loc_type == 'city') {
	$table = 'cities';
	$id_col = 'city_id';
}

else if($loc_type == 'state') {
	$table = 'states';
	$id_col = 'state_id';
}

else if($loc_type == 'country') {
	$table = 'countries';
	$id_col = 'country_id';
}

else {
	$table = '';
}

if(!empty($table)) {
	$query = "DELETE FROM $table WHERE $id_col = :loc_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':loc_id', $loc_id);
	$stmt->execute();

	echo $txt_loc_removed;
}

else {
	echo $txt_loc_remove_problem;
}