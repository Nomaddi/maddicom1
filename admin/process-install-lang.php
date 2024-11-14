<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$file = !empty($_POST['file']) ? $_POST['file'] : '';

$sql = file_get_contents(__DIR__ . "/../sql/$file");

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