<?php
require_once(__DIR__ . '/../inc/config.php');

// init vars
$user_exists    = 0;
$user_created   = 0;
$form_submitted = 0;
$empty_fields   = 1;
$invalid_email  = 0;

// check if form submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$form_submitted = 1;
	$fname          = !empty($_POST['fname'   ]) ? $_POST['fname'   ] : '';
	$lname          = !empty($_POST['lname'   ]) ? $_POST['lname'   ] : '';
	$email          = !empty($_POST['email'   ]) ? $_POST['email'   ] : '';
	$password       = !empty($_POST['password']) ? $_POST['password'] : '';

	// validate email
	if(PHPMailer\PHPMailer\PHPMailer::validateAddress($email) == false) {
		$invalid_email = 1;
	}

	// honeypot
	$honeypot = false;

	if(!empty($_REQUEST['password2']) && (bool) $_REQUEST['password2'] == TRUE) {
		die();
	}
}

// if all fields submitted
if(!empty($fname) && !empty($email) && !empty($password) && !$invalid_email) {
	$empty_fields = 0;

	// user ip
	$ip = get_ip();

	// check to see if email already exists
	$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$count = $stmt->fetchColumn();

	// user exists?
	if($count > 0) {
		$user_exists = 1;
	}

	// else user doesn't exist, so create entry in db
	else {
		// hash
		$password_hashed = password_hash($password, PASSWORD_BCRYPT);

		// insert into db
		$stmt = $conn->prepare('
		INSERT INTO users(
			first_name,
			last_name,
			email,
			password,
			created,
			hybridauth_provider_name,
			ip_addr,
			status
			)
		VALUES(
			:first_name,
			:last_name,
			:email,
			:password,
			NOW(),
			:hybridauth_provider_name,
			:ip,
			:status
			)
		');

		$stmt->bindValue(':first_name'              , $fname);
		$stmt->bindValue(':last_name'               , $lname);
		$stmt->bindValue(':email'                   , $email);
		$stmt->bindValue(':password'                , $password_hashed);
		$stmt->bindValue(':hybridauth_provider_name', 'local');
		$stmt->bindValue(':ip'                      , $ip);
		$stmt->bindValue(':status'                  , 'pending');

		// if query executed successfully
		if($stmt->execute()) {
			$user_created = 1;
			$confirm_str = generatePassword(16);
			$user_id = $conn->lastInsertId();

			// insert confirmation string into db
			$stmt2 = $conn->prepare('
			INSERT INTO signup_confirm(
				user_id,
				confirm_str,
				created
				)
			VALUES(
				:user_id,
				:confirm_str,
				NOW()
				)
			');

			$stmt2->bindValue(':user_id'    , $user_id);
			$stmt2->bindValue(':confirm_str', $confirm_str);

			if($stmt2->execute()) { // if insert into signup confirm table executed...
				// email user
				$query = "SELECT * FROM email_templates WHERE type = 'signup_confirm'";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$email_subject = $row['subject'];
				$email_body    = $row['body'];

				$confirm_link = $baseurl . "/user/register-confirm/" . $user_id . "," . $confirm_str;
				$email_body = str_replace('%confirm_link%', $confirm_link, $email_body);

				// string replacements
				$email_subject = str_replace('%site_name%', $site_name, $email_subject);
				$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
				$email_body = str_replace('%site_name%', $site_name, $email_body);
				$email_body = str_replace('%site_url%', $baseurl, $email_body);

				// phpmailer
				try {
					// mailer params
					$PHPMailer->ClearAllRecipients();
					$PHPMailer->setFrom($admin_email, $site_name);
					$PHPMailer->addAddress($email);
					$PHPMailer->addReplyTo($admin_email);
					$PHPMailer->isHTML(false);
					$PHPMailer->Subject = $email_subject;
					$PHPMailer->Body = $email_body;

					// send
					$PHPMailer->send();

					// result
					$mailer_problem = 0;
				} catch (Exception $e) {
					$exception[] = utf8_encode($PHPMailer->ErrorInfo);
					$mailer_problem = 1;
				}
			}

			// admin notification
			if($user_created_notify) {
				$query = "SELECT * FROM email_templates WHERE type = 'user_signup'";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$email_subject = $row['subject'];
				$email_body    = $row['body'];

				// string replacements
				$email_body = str_replace('%signup_email%', $email, $email_body);

				// phpmailer
				try {
					// mailer params
					$PHPMailer->ClearAllRecipients();
					$PHPMailer->setFrom($admin_email, $site_name);
					$PHPMailer->addAddress($admin_email);
					$PHPMailer->addReplyTo($admin_email);
					$PHPMailer->isHTML(false);
					$PHPMailer->Subject = $email_subject;
					$PHPMailer->Body = $email_body;

					// send
					$PHPMailer->send();
				} catch (Exception $e) {
					$exception[] = utf8_encode($PHPMailer->ErrorInfo);
					$mailer_problem = 1;
				}
			}
		}
	}
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/register';
