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
if(PHPMailer\PHPMailer\PHPMailer::validateAddress($contact_email)) {
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($admin_email);
		$PHPMailer->addReplyTo($contact_email, $contact_name);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = $contact_subject;
		$PHPMailer->Body = $contact_message;

		// send
		$PHPMailer->send();

		// result
		echo $txt_message_sent;
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
	}
}

else {
	?>
	Invalid email address. <a href="<?= $baseurl ?>/contact" class="text-primary">Try again.</a>
	<?php
}
