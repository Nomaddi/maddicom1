<?php
require_once(__DIR__ . '/../inc/config.php');

// check csrf token
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// init vars
$valid_email    = true;
$ip             = '';
$form_submitted = 0;
$mailer_problem = 0;

// get post data
$params = array();
parse_str($_POST['params'], $params);
$email = !empty($params['email']) ? $params['email'] : '';

// validate email
if(PHPMailer\PHPMailer\PHPMailer::validateAddress($email) == false) {
	//if email is not valid
	$valid_email = false;
	$response = 'invalid';
	$form_submitted = 'form_submitted';
}

// if the email address is valid
if($valid_email) {
	// checks if email is registered
	$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$count = $stmt->fetchColumn();

	// if email is registered
	if($count > 0) {
		$user_exists = 1;

		// get user id
		$stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$id = $row['id'];

		// generate token
		$token = generatePassword(16);

		// insert token into db
		$stmt = $conn->prepare('INSERT INTO pass_request(user_id, token) VALUES(:userid, :token)');
		$stmt->bindValue(':userid', $id);
		$stmt->bindValue(':token', $token);
		$stmt->execute();

		// get reset password email template
		$query = "SELECT * FROM email_templates WHERE type = 'reset_pass'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body    = $row['body'];

		$reset_link = $baseurl . "/user/password-reset/" . $id . "," . $token;

		// string replacements
		$email_subject = str_replace('%site_name%', $site_name, $email_subject);
		$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
		$email_body = str_replace('%site_name%', $site_name, $email_body);
		$email_body = str_replace('%site_url%', $baseurl, $email_body);
		$email_body = str_replace('%reset_link%', $reset_link, $email_body);

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
			$response = 'success';
		} catch (Exception $e) {
			$mailer_problem = 1;
			$response = 'smtp_error';
		}
	}

	// else email is not registered
	else {
		$response = 'invalid';
	}
}

// response
$response = array(
	'response' => $response,
	'form_submitted' => $form_submitted,
	'email' => $email
);

// json encode response
echo json_encode($response);
