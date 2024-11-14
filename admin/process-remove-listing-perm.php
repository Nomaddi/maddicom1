<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// listing details
if(empty($_POST['place_id'])) {
	die('Empty place id');
}

$place_id = $_POST['place_id'];

try {
	$conn->beginTransaction();

	// delete photos first, before cascading foreign key
	$query = "SELECT dir, filename FROM photos WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$dir = !empty($row['dir']) ? $row['dir'] : '';
		$filename = !empty($row['filename']) ? $row['filename'] : '';

		$full_pic = $pic_basepath . '/' . $place_full_folder . '/' . $dir . '/' . $filename;
		$thumb_pic = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir . '/' . $filename;

		if(is_file($full_pic)) {
			@unlink($full_pic);
		}
		if(is_file($thumb_pic)) {
			@unlink($thumb_pic);
		}
	}

	// delete listing
	$query = "DELETE FROM places WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	// commit
	$conn->commit();

	/*--------------------------------------------------
	Cancel Stripe
	--------------------------------------------------*/
	$query = "SELECT * FROM transactions WHERE place_id = :place_id AND txn_type = 'customer.subscription.created'";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$subscr_id = !empty($row['subscr_id']) ? $row['subscr_id'] : '';

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
		if(!$subscription->cancel()) {
			echo "Problem terminating Stripe subscription";
		}
	}

	echo '1';
}

catch(PDOException $e) {
	$conn->rollBack();
	$result_message =  $e->getMessage();

	echo $result_message;
}
