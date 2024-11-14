<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// load language strings manually
$query = "SELECT * FROM language WHERE lang = :lang AND section = 'admin' AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':template', 'language');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// parse post data
$params = array();
parse_str($_POST['params'], $params);

$template = !empty($params['template']) ? $params['template'] : '';
$var_name = !empty($params['var_name']) ? $params['var_name'] : '';

// string value is an array
$string_value = !empty($params['string_value']) ? $params['string_value'] : array();

// trim
$template = trim($template);
$var_name = trim($var_name);

// slugify var name
$var_name = str_replace('_', '-', $var_name);
$var_name = to_slug($var_name);
$var_name = str_replace('-', '_', $var_name);

// prepend txt_ if it doesn't already start with
if(substr($var_name, 0, 4) != 'txt_') {
	$var_name = 'txt_' . $var_name;
}

// extract section and tpl
$section = explode('/', $template)[0];
$template = explode('/', $template)[1];

// get available langs
$available_langs = array();

$query = "SELECT DISTINCT lang FROM language";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if(in_array($row['lang'], array_keys($iso_639_1_native_names))) {
		$available_langs[] = $row['lang'];
	}
}

// process submitted values
if(!empty($available_langs) && is_array($available_langs)) {
	try {
		$conn->beginTransaction();

		foreach($available_langs as $v) {
			if(isset($string_value[$v])) {
				$query = "INSERT INTO language(lang, section, template, var_name, translated) VALUES (:lang, :section, :template, :var_name, :translated)";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':lang', $v);
				$stmt->bindValue(':section', $section);
				$stmt->bindValue(':template', $template);
				$stmt->bindValue(':var_name', $var_name);
				$stmt->bindValue(':translated', $string_value[$v]);
				$stmt->execute();
			}
		}

		$conn->commit();
		$result_message = $txt_string_created;
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message = $e->getMessage();
	}
}

echo $result_message;