<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$lang = !empty($_POST['lang']) ? $_POST['lang'] : '';

// delete existing lang if exists
$query = "DELETE FROM language WHERE lang = :lang";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $lang);
$stmt->execute();

// insert into db
$query = "INSERT INTO language(
			lang,
			section,
			template,
			var_name,
			translated)
		SELECT '$lang',
			section,
			template,
			var_name,
			translated FROM language WHERE lang = 'en'";

$stmt = $conn->prepare($query);
$stmt->execute();

// if created language is english, the the insert select will not work, so import again from lang_en.sql
if($lang == 'en') {
	$sql = file_get_contents(__DIR__ . "/../sql/lang_en.sql");

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

echo 'ok';