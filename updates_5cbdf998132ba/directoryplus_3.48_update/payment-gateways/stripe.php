<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// debug
if($debug == 'stripe') {
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($dev_email);
		$PHPMailer->addReplyTo($admin_email);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = 'Stripe debug mail 1';
		$PHPMailer->AllowEmpty = true;
		$PHPMailer->Body = '';

		// send
		$PHPMailer->send();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
	}
}

/*--------------------------------------------------
API config
--------------------------------------------------*/

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

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");

// extract json str into assoc array
//$event_json = json_decode($input); --> object
$event_arr = json_decode($input, true); // --> array

// Check against Stripe to confirm that the ID is valid
if(!empty($event_arr['id'])) {
	$event_obj = \Stripe\Event::retrieve($event_arr['id']);

	// convert $event into assoc array
	$event = $event_obj->toArray(true);
}

// check if it's proper event
if(!isset($event)) {
	die();
}

/*--------------------------------------------------
Transaction table vars
--------------------------------------------------*/
$parent_txn_id   = '';
$ipn_vars        = $input;
$ipn_response    = $input;
$ipn_description = !empty($event['data']['object']['object']) ? $event['data']['object']['object'] : '';
$txn_type        = !empty($event['type']                    ) ? $event['type']                     : '';
$payment_status  = !empty($event['data']['object']['status']) ? $event['data']['object']['status'] : '';
$txn_id          = !empty($event['id']                      ) ? $event['id']                       : '';
$txn_date        = !empty($event['created']                 ) ? $event['created']                  : '';

/*--------------------------------------------------
Event types
--------------------------------------------------*/
$events = array(
	'charge.succeeded',
	'charge.refunded',
	'charge.failed',
	'customer.subscription.created',
	'customer.subscription.deleted',
	'invoice.payment_succeeded',
	'invoice.payment_failed'
);

// act only on the event types above
if(!in_array($txn_type, $events)) {
	die();
}

/*--------------------------------------------------
Check if it's subscription
--------------------------------------------------*/
$is_subscription = null;

if($txn_type == 'charge.succeeded') {
	if(empty($event['data']['object']['invoice'])) {
		$is_subscription = false;
	}

	else {
		$is_subscription = true;
	}
}

if( $txn_type == 'customer.subscription.created' ||
	$txn_type == 'customer.subscription.deleted' ||
	$txn_type == 'invoice.payment_succeeded'
	) {
	$is_subscription = true;
}

if($txn_type == 'charge.failed') {
	// if charge failed, it could be either subscription or buy now
	// but if metadata is empty, it means it's subscription, if contains metadata, it's buy now
	if(!empty($event['data']['object']['metadata'])) {
		$is_subscription = false;
	}

	else {
		$is_subscription = true;
	}
}

/*--------------------------------------------------
Metadata
--------------------------------------------------*/
// for 'buy now', metadata data is sent with \Stripe\Charge::create and received in webhook of type 'charge.succeeded'
// for 'subscriptions', metadata data is sent with \Stripe\Subscription::create and received in webhook type 'customer.subscription.created' and 'customer.subscription.deleted', also in 'invoice.payment_succeeded', 'invoice.created'
$plan_type = 'undefined plan';
$plan_id   = 0;
$place_id  = 0;
$payer_id  = ''; // payer_id only when from claim listing

if(in_array($txn_type, array('charge.succeeded', 'charge.failed', 'charge.refunded'))) {
	if(!$is_subscription) {
		$plan_id   = !empty($event['data']['object']['metadata']['plan_id']  ) ? $event['data']['object']['metadata']['plan_id']   : 0;
		$place_id  = !empty($event['data']['object']['metadata']['place_id'] ) ? $event['data']['object']['metadata']['place_id']  : 0;
		$payer_id  = !empty($event['data']['object']['metadata']['payer_id'] ) ? $event['data']['object']['metadata']['payer_id']  : 0;
	}
}

if($txn_type == 'customer.subscription.created' || $txn_type == 'customer.subscription.deleted') {
	$plan_id   = !empty($event['data']['object']['metadata']['plan_id']  ) ? $event['data']['object']['metadata']['plan_id']   : 0;
	$place_id  = !empty($event['data']['object']['metadata']['place_id'] ) ? $event['data']['object']['metadata']['place_id']  : 0;
	$payer_id  = !empty($event['data']['object']['metadata']['payer_id'] ) ? $event['data']['object']['metadata']['payer_id']  : 0;
}

if($txn_type == 'invoice.payment_succeeded') {
	$plan_id   = !empty($event['data']['object']['lines']['data'][0]['metadata']['plan_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['plan_id'] : 0;
	$place_id  = !empty($event['data']['object']['lines']['data'][0]['metadata']['place_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['place_id'] : 0;
	$payer_id  = !empty($event['data']['object']['lines']['data'][0]['metadata']['payer_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['payer_id'] : 0;
}

/*--------------------------------------------------
Customer info
--------------------------------------------------*/
$customer_obj   = \Stripe\Customer::retrieve($event['data']['object']['customer']);
$customer       = $customer_obj->toArray(true);
$customer_email = $customer['email'];
$customer_id    = $customer['id'];

/*--------------------------------------------------
Plan details
--------------------------------------------------*/
$query = "SELECT * FROM plans WHERE plan_id = :plan_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_id', $plan_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$plan_price   = $row['plan_price'];
$plan_type    = $row['plan_type'];

$plan_price = str_replace(',', '', $plan_price);
$plan_price = str_replace('.', '', $plan_price);

// stripe always uses non-decimal amount in minimum currency unit (e.g. cents for USD and yen for JPY)
// plan_price is always decimal so if $stripe_min_unit_is_cent is false(for eg. JPY), then divide amount by 100
if(!$stripe_min_unit_is_cent) {
	$plan_price = $plan_price / 100;
}

// stripe currency
$query = "SELECT * FROM config WHERE property = 'stripe_data_currency'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stripe_currency = !empty($row['value']) ? $row['value'] : '';
$stripe_currency = mb_strtolower($stripe_currency);

/*--------------------------------------------------
Listing details
--------------------------------------------------*/
$query = "SELECT userid FROM places WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$place_userid = !empty($row['userid']) ? $row['userid'] : 0;

// listing link to use in emails
$listing_link = '';

if(!empty($place_id)) {
	$listing_link = get_listing_link($place_id, '', '', '', '', '', '', $cfg_permalink_struct);
}

/*--------------------------------------------------
User details
--------------------------------------------------*/
$query = "SELECT email, first_name FROM users WHERE id = :place_userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_userid', $place_userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$place_user_email = !empty($row['email']) ? $row['email'] : $customer_email;
$place_user_firstname = !empty($row['first_name']) ? $row['first_name'] : '';

/*--------------------------------------------------
subscr_id
--------------------------------------------------*/
$subscr_id = '';

if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
	$subscr_id = '';
}

else {
	$subscr_id = !empty($event['data']['object']['id']) ? $event['data']['object']['id'] : '';
}

/*--------------------------------------------------
Amount
--------------------------------------------------*/

// init
$amount = '';
$amount_currency = '';

// get values from object data
if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
	$amount = !empty($event['data']['object']['amount']) ? $event['data']['object']['amount'] : '';
	$amount_currency = !empty($event['data']['object']['currency']) ? $event['data']['object']['currency'] : '';
}

else {
	$amount = !empty($event['data']['object']['plan']['amount']) ? $event['data']['object']['plan']['amount'] : '';
	$amount_currency = !empty($event['data']['object']['plan']['currency']) ? $event['data']['object']['plan']['currency'] : '';
}

if(empty($amount) && empty($amount_currency)) {
	$amount = !empty($event['data']['object']['lines']['data'][0]['amount']) ? $event['data']['object']['lines']['data'][0]['amount'] : '';
	$amount_currency = !empty($event['data']['object']['lines']['data'][0]['currency']) ? $event['data']['object']['lines']['data'][0]['currency'] : '';
}

if(empty($amount) && empty($amount_currency)) {
	$amount = !empty($event['data']['object']['amount']) ? $event['data']['object']['amount'] : '';
	$amount_currency = !empty($event['data']['object']['currency']) ? $event['data']['object']['currency'] : '';
}

$amount_currency = mb_strtolower($amount_currency);

/*--------------------------------------------------
Debug
--------------------------------------------------*/
$debug_msg = "
is_subscription = $is_subscription
ipn_description = $ipn_description
plan_id         = $plan_id
place_id        = $place_id
plan_type       = $plan_type
payer_email     = $customer_email
txn_type        = $txn_type
payment_status  = $payment_status
amount          = $amount
txn_id          = $txn_id
parent_txn_id   = $parent_txn_id
subscr_id       = $subscr_id
ipn_vars        = $ipn_vars
txn_date        = $txn_date
customer_id     = $customer_id;
ipn_response    = $ipn_response
";

if($debug == 'stripe') {
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($dev_email);
		$PHPMailer->addReplyTo($admin_email);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = 'Stripe debug mail 2';
		$PHPMailer->AllowEmpty = true;
		$PHPMailer->Body = '';

		// send
		$PHPMailer->send();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
	}
}

/*--------------------------------------------------
HANDLE EVENT
--------------------------------------------------*/

// if buy now
if(!$is_subscription) {
	if($txn_type == 'charge.succeeded') {
		// verify amount
		if($plan_price == $amount && $stripe_currency == $amount_currency) {
			// get email template
			$query = "SELECT * FROM email_templates WHERE type = 'web_accept'";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$email_subject = $row['subject'];
			$email_body = $row['body'];

			// if not a claim listing
			if(empty($payer_id)) {
				// update paid column in places table
				$query = 'UPDATE places SET paid = 1 WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->execute();

				// add to sitemap
				if($cfg_enable_sitemaps) {
					$query = 'SELECT status FROM places WHERE place_id = :place_id';
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':place_id', $place_id);
					$stmt->execute();

					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$status = $row['status'];

					// if status approved, add url to sitemap
					if($status == 'approved') {
						sitemap_add_url($listing_link);
					}
				}
			}

			// else is a claim listing
			else {
				// update paid column in places table
				$query = 'UPDATE places SET userid = :userid WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->bindValue(':userid', $payer_id);
				$stmt->execute();

				// no need to add to sitemap
			}
		}
	}

	else if($txn_type == 'charge.failed') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'web_accept_fail'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];
	}

	else if($txn_type == 'charge.refunded') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'web_accept_fail'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];

		// if not a claim listing
		if(empty($payer_id)) {
			// update paid column in places table
			$query = 'UPDATE places SET paid = 0 WHERE place_id = :place_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			// remove from sitemap
			if($cfg_enable_sitemaps) {
				$query = 'SELECT status FROM places WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->execute();

				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$status = $row['status'];

				// if status approved, remove url from sitemap
				if($status == 'approved') {
					sitemap_remove_url($listing_link);
				}
			}
		}

		// else it's a claim listing
		else {
			// if listing was claimed and refunded, set ownership to admin again
			$query = 'UPDATE places SET userid = 1 WHERE place_id = :place_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			// no need to remove from sitemap
		}
	}

	else {
		// log transaction
		error_log("Webhook data is a one-off purchase but charge neither succeeded nor failed/refunded");
	}
}

// else is subscription
else {
	// webhook types to act on:
		// 'customer.subscription.created'
		// 'customer.subscription.deleted'
		// 'invoice.payment_succeeded'
		// 'invoice.payment_failed'
	// if subscription succeeded
	if($txn_type == 'customer.subscription.created') {
		if($plan_price == $amount && $stripe_currency == $amount_currency) {
			// get email template
			$query = "SELECT * FROM email_templates WHERE type = 'subscr_signup'";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$email_subject = $row['subject'];
			$email_body = $row['body'];

			// if not a claim listing
			if(empty($payer_id)) {
				// update paid column in places table
				$query = 'UPDATE places SET paid = 1 WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->execute();

				// add to sitemap
				if($cfg_enable_sitemaps) {
					$query = 'SELECT status FROM places WHERE place_id = :place_id';
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':place_id', $place_id);
					$stmt->execute();

					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$status = $row['status'];

					// if status approved, add url to sitemap
					if($status == 'approved') {
						sitemap_add_url($listing_link);
					}
				}
			}

			// else is a claim listing
			else {
				// update paid column in places table
				$query = 'UPDATE places SET userid = :userid WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->bindValue(':userid', $payer_id);
				$stmt->execute();

				// no need to add to sitemap
			}
		}
	}

	else if($txn_type == 'charge.succeeded') {
		// log transaction
	}

	else if($txn_type == 'invoice.payment_succeeded') {
		// log transaction
	}

	else if($txn_type == 'invoice.payment_failed') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'subscr_failed'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];
	}

	else if($txn_type == 'customer.subscription.deleted') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'subscr_eot'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];

		// if not a claim listing
		if(empty($payer_id)) {
			// update paid column in places table
			$query = 'UPDATE places SET paid = 0 WHERE place_id = :place_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			// remove from sitemap
			if($cfg_enable_sitemaps) {
				$query = 'SELECT status FROM places WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->execute();

				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$status = $row['status'];

				// if status approved, remove url from sitemap
				if($status == 'approved') {
					sitemap_remove_url($listing_link);
				}
			}
		}

		// else it's a claim listing
		else {
			// if listing was claimed and refunded, set ownership to admin again
			$query = 'UPDATE places SET userid = 1 WHERE place_id = :place_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			// no need to remove from sitemap
		}


	}

	else {
		// log transaction
	}
}

/*--------------------------------------------------
Check if subscription payment and place deleted
--------------------------------------------------*/
// if this is a subscription payment and the place has been deleted, then cancel the subscription
if($txn_type == 'invoice.payment_succeeded') {
	$query = "SELECT * FROM places WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(empty($row['place_id'])) {
		// place has been deleted so cancel subscription
		// set api key
		// if stripe live mode
		if($stripe_mode == 1) {
			$stripe_key = $stripe_live_secret_key;
		}

		// else is stripe test mode
		else {
			$stripe_key = $stripe_test_secret_key;
		}

		$subscription = \Stripe\Subscription::retrieve($subscr_id);
		$subscription->cancel();
	}
}

/*--------------------------------------------------
Send the email
--------------------------------------------------*/
if(!empty($email_body)) {
	// string replacements
	$email_subject = str_replace('%site_name%', $site_name, $email_subject);
	$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
	$email_subject = str_replace('%listing_link%', $listing_link, $email_subject);
	$email_subject = str_replace('%place_link%', $listing_link, $email_subject);
	$email_subject = str_replace('%username%', '', $email_subject);
	$email_body    = str_replace('%site_name%', $site_name, $email_body);
	$email_body    = str_replace('%site_url%', $baseurl, $email_body);
	$email_body    = str_replace('%listing_link%', $listing_link, $email_body);
	$email_body    = str_replace('%place_link%', $listing_link, $email_body);
	$email_body    = str_replace('%username%', '', $email_body);

	// send
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($place_user_email);
		$PHPMailer->addReplyTo($admin_email);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = $email_subject;
		$PHPMailer->Body = $email_body;

		// send
		$PHPMailer->send();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
	}
}

/*--------------------------------------------------
INSERT INTO TRANSACTIONS TABLE
--------------------------------------------------*/
// convert amount to decimal
$amount = $amount / 100;

$query = "INSERT INTO transactions(
		txn_type,
		place_id,
		user,
		paym_email,
		gateway,
		amount,
		txn_data
	)
	VALUES(
		:txn_type,
		:place_id,
		:user_id,
		:paym_email,
		:gateway,
		:amount,
		:txn_data
	)";

$stmt = $conn->prepare($query);
$stmt->bindValue(':txn_type'   , $txn_type);
$stmt->bindValue(':place_id'   , $place_id);
$stmt->bindValue(':user_id'    , $place_userid);
$stmt->bindValue(':paym_email' , $customer_email);
$stmt->bindValue(':gateway'    , 'stripe');
$stmt->bindValue(':amount'     , $amount);
$stmt->bindValue(':txn_data'   , $ipn_vars);
$stmt->execute();
