<?php
require_once(__DIR__ . '/inc/config.php');

// check csrf token
require_once(__DIR__ . '/_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'public');
$stmt->bindValue(':template', 'listing');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// initialize swiftmailer
$transport_smtp = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port, $cfg_smtp_encryption)
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = Swift_Mailer::newInstance($transport_smtp);

// get post data
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$contact_name    = !empty($params['name'   ]) ? $params['name'   ] : '';
$contact_email   = !empty($params['email'  ]) ? $params['email'  ] : '';
$contact_subject = !empty($params['subject']) ? $params['subject'] : '';
$contact_message = !empty($params['message']) ? $params['message'] : '';

// sender ip
$sender_ip = get_ip();

// check if sender ip already submitted less than 30 secs ago
$query = "SELECT TIMESTAMPDIFF(SECOND, created, NOW()) AS secs_ago FROM contact_msgs WHERE sender_ip = :sender_ip ORDER BY created DESC LIMIT 1";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':sender_ip', $sender_ip);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$secs_ago = !empty($row['secs_ago']) ? $row['secs_ago'] : $cgf_min_secs + 1;

if($secs_ago < $cgf_min_secs) {
	echo '<div class="alert alert-danger alert-dismissible contact-owner-alert">', $txt_please_wait, '</div>';
	return;
}

// send message
if(Swift_Validate::email($contact_email)) {
	$message = Swift_Message::newInstance()
		->setSubject($contact_subject)
		->setFrom(array($admin_email => $site_name))
		->setTo($admin_email)
		->setBody($contact_message)
		->setReplyTo($contact_email)
		->setReturnPath($admin_email)
		;

	// Send the message
	if($mailer->send($message)) {
		echo $txt_message_sent;
	}

	else {
		?>
		error sending message
		<?php
	}
}

else {
	?>
	Invalid email address.
	<?php
}
