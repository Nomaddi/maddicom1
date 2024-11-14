<?php
require_once(__DIR__ . '/inc/config.php');
require_once(__DIR__ . '/inc/functions.php');

// if 'config' table doesn't exist, return (being called from the install script)
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'config')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] > 0) {
	echo "Directoryplus is already installed";
	return;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// init vars
	$valid_email = 0;
	$email = '';
	$password = '';
	$errors = '';

	// params
	$params = array();
	parse_str($_POST['params'], $params);

	$email    = !empty($params['email'   ]) ? $params['email'   ] : 'admin@example.com';
	$password = !empty($params['password']) ? $params['password'] : '1234';

	// validate email
	if(PHPMailer\PHPMailer\PHPMailer::validateAddress($email)) {
		$valid_email = 1;
	}

	if($valid_email) {
		// hash pass
		$password = password_hash($password, PASSWORD_BCRYPT);

		// check mysql version
		$query = "SELECT version() AS v";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$version = $row['v'];

		// load the sql file
		if($version >= 5.7) {
			$sql = file_get_contents('directoryplus.sql');
		}

		else {
			$sql = file_get_contents('directoryplus_varchar190.sql');
		}

		$sql = explode(";\n", $sql);

		try {

			// begin transaction
			$conn->beginTransaction();

			foreach($sql as $k => $v) {
				try {
					$v = trim($v);

					if(!empty($v)) {
						$stmt = $conn->prepare(trim($v));
						$stmt->execute();
					}
				}

				catch (PDOException $e) {
					echo $e->getMessage();
					die();
				}
			}

			// update password
			$query = "UPDATE users SET email = :email, password = :password WHERE id = 1";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':email', $email);
			$stmt->bindValue(':password', $password);
			$stmt->execute();

			// commit
			if($conn->inTransaction()) {
				$conn->commit();
			}

			echo 'Install successful. Please <a href="' . $baseurl . '/user/sign-in">Sign in</a>';
		}

		catch(PDOException $e) {
			$conn->rollBack();
			$result_message =  $e->getMessage();

			echo $result_message;
		}

	}

	else {
		echo 'Invalid email. Please <a href="' . $baseurl . '/install.php">try again</a>';
	}
}
