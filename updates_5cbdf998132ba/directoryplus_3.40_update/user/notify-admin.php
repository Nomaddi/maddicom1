<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// initialize swiftmailer
$transport_smtp = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port, $cfg_smtp_encryption)
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = Swift_Mailer::newInstance($transport_smtp);

/*
$place_id
$place_slug
$cat_id
$city_id
*/

if(!empty($mail_after_post)) {
	$place_id   = !empty($_POST['place_id'  ]) ? $_POST['place_id'  ] : '';
	$place_slug = !empty($_POST['place_slug']) ? $_POST['place_slug'] : '';
	$cat_id     = !empty($_POST['cat_id'    ]) ? $_POST['cat_id'    ] : '';
	$city_id    = !empty($_POST['city_id'   ]) ? $_POST['city_id'   ] : '';
	$from       = !empty($_POST['from'      ]) ? $_POST['from'      ] : '';

	if($from == 'create') {
		// listing link
		// function get_listing_link($place_id, $place_slug = '', $cat_id = '', $cat_slug = '', $city_id = '', $city_slug = '', $state_slug = '', $cfg_permalink_struct = 'listing')
		$this_listing_link = get_listing_link($place_id, $place_slug, $cat_id, '', $city_id, '', '', $cfg_permalink_struct);

		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'process_add_listing'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$email_subject = !empty($row['subject']) ? $row['subject'] : '';
		$email_body = !empty($row['body']) ? $row['body'] : '';

		$email_body .= "\n" . $baseurl . '/admin';

		// replace %new_listing_url%
		$email_body = str_replace('%new_listing_url%', $this_listing_link, $email_body);
	}

	if($from == 'edit') {
		// listing link
		// function get_listing_link($place_id, $place_slug = '', $cat_id = '', $cat_slug = '', $city_id = '', $city_slug = '', $state_slug = '', $cfg_permalink_struct = 'listing')
		$edited_listing_url = get_listing_link($place_id, $place_slug, $cat_id, '', $city_id, '', '', $cfg_permalink_struct);

		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'process_edit_listing'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];

		// string replacements listing_slug
		$email_body = str_replace('%edited_listing_url%', $edited_listing_url, $email_body);
	}

	// send email
	if(!empty($email_subject)) {
		$message = Swift_Message::newInstance()
			->setSubject($email_subject)
			->setFrom(array($admin_email => $site_name))
			->setTo($admin_email)
			->setBody($email_body)
			->setReplyTo($admin_email)
			->setReturnPath($admin_email)
			;

		$mailer->send($message);
	}

	else {
		echo "Empty email subject";
	}
}