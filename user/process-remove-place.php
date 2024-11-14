<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// initialize swiftmailer
$transport_smtp = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port)
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = Swift_Mailer::newInstance($transport_smtp);

// post data
$place_id = $_POST['place_id'];

// check place
$stmt = $conn->prepare('SELECT * FROM places WHERE place_id = :place_id AND userid = :userid');
$stmt->bindValue(':place_id', $place_id);
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// check if user is owner of this place
if($row['userid'] == $userid) {
	// delete photos first, before cascading foreign key
	$query = "SELECT dir, filename FROM photos WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$dir = $row['dir'];
		$filename = $row['filename'];

		$full_pic = $pic_basepath . '/' . $place_full_folder . '/' . $dir . '/' . $filename;
		$thumb_pic = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir . '/' . $filename;

		if(is_file($full_pic)) {
			unlink($full_pic);
		}

		if(is_file($thumb_pic)) {
			unlink($thumb_pic);
		}
	}

	// delete logo
	$query = "SELECT logo FROM places WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$logo = $row['logo'];

	if(is_file($pic_basepath . '/logo/' . substr($logo, 0, 2) . '/' . $logo)) {
		unlink($pic_basepath . '/logo/' . substr($logo, 0, 2) . '/' . $logo);
	}

	// delete from db
	$query = "DELETE FROM places WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	// if it's a stripe subscription, cancel billing
	$query = "SELECT * FROM transactions WHERE place_id = :place_id AND txn_type = 'customer.subscription.created'";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$subscr_id = $row['subscr_id'];

	if(!empty($subscr_id) && $subscr_id != 'undefined') {
		// set api key
		// if stripe live mode
		if($stripe_mode == 1) {
			$stripe_key = $stripe_live_secret_key;
		}

		// else is stripe test mode
		else {
			$stripe_key = $stripe_test_secret_key;
		}

		\Stripe\Stripe::setApiKey($stripe_key);
		\Stripe\Stripe::setApiVersion("2016-10-19");

		$subscription = \Stripe\Subscription::retrieve($subscr_id);
		if($subscription->cancel()) {
			echo "Stripe subscription has been terminated";
		}

		else {
			echo "Problem terminating Stripe subscription";
		}
	}
}

else {
	echo "Problem removing place";
}