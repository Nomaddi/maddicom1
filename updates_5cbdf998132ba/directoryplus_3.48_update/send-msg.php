<?php
if(file_exists(__DIR__ . '/send-msg-child.php') && basename(__FILE__) != 'send-msg-child.php') {
	include_once('send-msg-child.php');
	return;
}

require_once(__DIR__ . '/inc/config.php');

// check csrf token
require_once(__DIR__ . '/_inc_request_with_ajax.php');

// sender ip
$sender_ip = get_ip();

// if sender ip already submitted less than 30 secs ago, return
$query = "SELECT TIMESTAMPDIFF(SECOND, created, NOW()) AS secs_ago FROM contact_msgs WHERE sender_ip = :sender_ip ORDER BY created DESC LIMIT 1";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':sender_ip', $sender_ip);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$secs_ago = !empty($row['secs_ago']) ? $row['secs_ago'] : $cgf_min_secs + 1;

if($secs_ago < $cgf_min_secs) {
	echo '<div class="alert alert-danger alert-dismissible">', $txt_please_wait, '</div>';
	return;
}

// get post data
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$from_page    = !empty($params['from_page'   ]) ? $params['from_page'   ] : 'listing';
$place_id     = !empty($params['place_id'    ]) ? $params['place_id'    ] : '';
$listing_url  = !empty($params['listing_url' ]) ? $params['listing_url' ] : '';
$recipient_id = !empty($params['recipient_id']) ? $params['recipient_id'] : '';
$sender_email = !empty($params['sender_email']) ? $params['sender_email'] : '';
$sender_name  = !empty($params['sender_name' ]) ? $params['sender_name' ] : '';
$sender_msg   = !empty($params['sender_msg'  ]) ? $params['sender_msg'  ] : '';

// if from listing page
if($from_page == 'listing') {
	// check vars
	if(empty($place_id) || empty($sender_email) || empty($sender_msg)) {
		$reason = '';

		if(empty($place_id)) $reason = ' place_id';
		if(empty($sender_email)) $reason = ' sender_email';
		if(empty($sender_msg)) $reason = ' sender_msg';
		echo '<div class="alert alert-danger alert-dismissible">Could not complete operation (01)</div>';
		echo $reason;
		return;
	}

	// append listing url
	$sender_msg .= "\n\n" . $listing_url;

	// get plugin settings from db
	$query = "SELECT * FROM config WHERE property = 'cfg_contact_business_subject'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$contact_business_subject = !empty($row['value']) ? $row['value'] : 'A message from ' . $site_name;

	// get listing owner email
	$query = "SELECT
				u.email
				FROM places p
				LEFT JOIN users u ON p.userid = u.id
				WHERE p.place_id = :place_id";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$listing_owner_email = $row['email'];

	// send message
	if(PHPMailer\PHPMailer\PHPMailer::validateAddress($sender_email) && PHPMailer\PHPMailer\PHPMailer::validateAddress($listing_owner_email)) {
		try {
			// mailer params
			$PHPMailer->ClearAllRecipients();
			$PHPMailer->setFrom($admin_email, $site_name);
			$PHPMailer->addAddress($listing_owner_email);
			$PHPMailer->addReplyTo($sender_email);
			$PHPMailer->isHTML(false);
			$PHPMailer->Subject = $contact_business_subject;
			$PHPMailer->Body = $sender_msg;

			// send
			if($PHPMailer->send()) {
				// write to db
				$query = "INSERT INTO contact_msgs(
							sender_email,
							sender_ip,
							place_id,
							msg)
					VALUES(
							:sender_email,
							:sender_ip,
							:place_id,
							:msg)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':sender_email', $sender_email);
				$stmt->bindValue(':sender_ip',    $sender_ip);
				$stmt->bindValue(':place_id',     $place_id);
				$stmt->bindValue(':msg',          $sender_msg);
				$stmt->execute();

				echo $txt_message_sent;
			} else {
				echo "Error sending message";
			}

		} catch (Exception $e) {
			echo "Error sending message";
		}
	}

	else {
		?>
		<div class="alert alert-success alert-dismissible contact-owner-alert">
			<?= $txt_invalid_email ?>
		</div>
		<?php
	}
}

// if from profile page
else if($from_page == 'profile') {
	// check vars
	if(empty($recipient_id) || empty($sender_email) || empty($sender_msg)) {
		echo '<div class="alert alert-danger alert-dismissible">Could not complete operation (02)</div>';
		echo $recipient_id . ',' . $sender_email . ',' . $sender_msg;
		return;
	}

	// get plugin settings from db
	$query = "SELECT * FROM config WHERE property = 'cfg_contact_user_subject'";
	$stmt  = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$contact_user_subject = !empty($row['value']) ? $row['value'] : '';

	// get user email
	$query = "SELECT email FROM users
				WHERE id = :recipient_id";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':recipient_id', $recipient_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$recipient_email = $row['email'];

	// send message
	if(PHPMailer\PHPMailer\PHPMailer::validateAddress($sender_email) && PHPMailer\PHPMailer\PHPMailer::validateAddress($recipient_email)) {
		try {
			// mailer params
			$PHPMailer->ClearAllRecipients();
			$PHPMailer->setFrom($admin_email, $site_name);
			$PHPMailer->addAddress($recipient_email);
			$PHPMailer->addReplyTo($sender_email);
			$PHPMailer->isHTML(false);
			$PHPMailer->Subject = $contact_user_subject;
			$PHPMailer->Body = $sender_msg;

			// send
			if($PHPMailer->send()) {
				// write to db
				$query = "INSERT INTO contact_msgs(
							sender_email,
							sender_ip,
							recipient_id,
							msg)
					VALUES(
							:sender_email,
							:sender_ip,
							:recipient_id,
							:msg)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':sender_email', $sender_email);
				$stmt->bindValue(':sender_ip'   , $sender_ip);
				$stmt->bindValue(':recipient_id', $recipient_id);
				$stmt->bindValue(':msg'         , $sender_msg);
				$stmt->execute();

				echo $txt_message_sent;
			} else {
				echo "Error sending message";
			}

		} catch (Exception $e) {
			echo "Error sending message";
		}
	}

	else {
		?>
		<div class="alert alert-success alert-dismissible contact-owner-alert">
			<?= $txt_invalid_email ?> <?= $sender_email ?>
		</div>
		<?php
	}
}

else {
		?>
		<div class="alert alert-info alert-dismissible contact-owner-alert">
			Could not complete operation
		</div>
		<?php
}