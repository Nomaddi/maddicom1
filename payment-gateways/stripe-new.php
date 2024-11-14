<?php
require_once(__DIR__ . '/../inc/config.php');

// debug
$debug = '';

/*--------------------------------------------------
Setup
--------------------------------------------------*/

// if stripe live mode
if($stripe_mode == 1) {
	$stripe_key = $stripe_live_secret_key;
}

// else is stripe test mode
else {
	$stripe_key = $stripe_test_secret_key;
}

// init stripe object
$stripe = new \Stripe\StripeClient($stripe_key);

// set stripe_data_currency to lowercase
$stripe_data_currency = strtolower($stripe_data_currency);

/*--------------------------------------------------
Retrieve event
--------------------------------------------------*/

// input or payload
$input = @file_get_contents('php://input');

// event init
$event = array();

// get event id
$event_id = json_decode($input, true)['id'];

// use the event id to get the full event directly from Stripe instead of trusting the webhook data
if(!empty($event_id)) {
	$event = $stripe->events->retrieve($event_id, []);

	// convert to array
	$event = $event->toArray(true);
}

// debug
if($debug == 'stripe') {
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($dev_email);
		$PHPMailer->addReplyTo($admin_email);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = 'Stripe Debug';
		$PHPMailer->AllowEmpty = true;
		$PHPMailer->Body = print_r($event, true);

		// send
		$PHPMailer->send();
	} catch (Exception $e) {
		echo "Message could not be sent.";
	}
}

if(empty($event)) {
	throw new Exception('Event cannot be empty.');
}

/*--------------------------------------------------
Webhook vars
--------------------------------------------------*/

// vars
$ipn_description = !empty($event['data']['object']['object']) ? $event['data']['object']['object'] : '';
$event_type      = !empty($event['type']                    ) ? $event['type']                     : '';
$payment_status  = !empty($event['data']['object']['status']) ? $event['data']['object']['status'] : '';
$event_id        = !empty($event['id']                      ) ? $event['id']                       : '';
$event_date      = !empty($event['created']                 ) ? $event['created']                  : '';

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

// handle only event types listed above
if(!in_array($event_type, $events)) {
	die();
}

/*--------------------------------------------------
Subscription check
--------------------------------------------------*/

$is_subscription = null;

if($event_type == 'charge.succeeded') {
	if(empty($event['data']['object']['invoice'])) {
		$is_subscription = false;
	}

	else {
		$is_subscription = true;
	}
}

if( $event_type == 'customer.subscription.created' ||
	$event_type == 'customer.subscription.deleted' ||
	$event_type == 'invoice.payment_succeeded'
	) {
	$is_subscription = true;
}

if($event_type == 'charge.failed') {
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

/*
buy now:
metadata data is sent with \Stripe\Charge::create and received in webhook event type 'charge.succeeded'

subscriptions:
metadata data is sent with \Stripe\Subscription::create and received in webhook event type 'customer.subscription.created' and 'customer.subscription.deleted', also in 'invoice.payment_succeeded', 'invoice.created'
*/

$plan_type  = null;
$plan_id    = null;
$listing_id = null;

// user_id only when from claim listing
$claim_userid = null;

if(in_array($event_type, array('charge.succeeded', 'charge.failed', 'charge.refunded'))) {
	if(!$is_subscription) {
		$plan_id = !empty($event['data']['object']['metadata']['plan_id']) ? $event['data']['object']['metadata']['plan_id']   : null;
		$listing_id = !empty($event['data']['object']['metadata']['listing_id']) ? $event['data']['object']['metadata']['listing_id'] : null;
		$claim_userid = !empty($event['data']['object']['metadata']['claim_user_id']) ? $event['data']['object']['metadata']['claim_user_id'] : null;
	}
}

if($event_type == 'customer.subscription.created' || $event_type == 'customer.subscription.deleted') {
	$plan_id = !empty($event['data']['object']['metadata']['plan_id']) ? $event['data']['object']['metadata']['plan_id'] : null;
	$listing_id = !empty($event['data']['object']['metadata']['listing_id']) ? $event['data']['object']['metadata']['listing_id'] : null;
	$claim_userid = !empty($event['data']['object']['metadata']['claim_user_id']) ? $event['data']['object']['metadata']['claim_user_id'] : null;
}

if($event_type == 'invoice.payment_succeeded') {
	$plan_id = !empty($event['data']['object']['lines']['data'][0]['metadata']['plan_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['plan_id'] : null;
	$listing_id = !empty($event['data']['object']['lines']['data'][0]['metadata']['listing_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['listing_id'] : null;
	$claim_userid = !empty($event['data']['object']['lines']['data'][0]['metadata']['claim_user_id']) ? $event['data']['object']['lines']['data'][0]['metadata']['claim_user_id'] : null;
}

/*--------------------------------------------------
Customer info
--------------------------------------------------*/

// customer from webhook
$customer = $event['data']['object']['customer'];

if(!empty($customer)) {
	$customer_obj   = $stripe->customers->retrieve($customer, []);
	$customer       = $customer_obj->toArray(true);
	$customer_email = $customer['email'];
	$customer_id    = $customer['id'];
}

else {
	$customer_email = null;
	$customer_id    = null;
}

/*--------------------------------------------------
Plan details
--------------------------------------------------*/

$query = "SELECT * FROM plans WHERE plan_id = :plan_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_id', $plan_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$plan_price = !empty($row['plan_price']) ? $row['plan_price']  : null;
$plan_type  = !empty($row['plan_type' ]) ? $row['plan_type' ]  : null;

// convert plan price to smallest unit
if(!empty($plan_price)) {
	$plan_price = filter_var($plan_price, FILTER_SANITIZE_NUMBER_FLOAT);
}

// stripe always uses non-decimal amount in minimum currency unit (e.g. cents for USD and yen for JPY)
// plan_price is always decimal so if $stripe_min_unit_is_cent is false(for eg. JPY), then divide amount by 100
if(!$stripe_min_unit_is_cent) {
	$plan_price = $plan_price / 100;
}

/*--------------------------------------------------
Listing details
--------------------------------------------------*/

$query = "SELECT userid FROM places WHERE place_id = :listing_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':listing_id', $listing_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$listing_userid = !empty($row['userid']) ? $row['userid'] : null;

// listing link to use in emails
$listing_link = '';

if(!empty($listing_id)) {
	$listing_link = get_listing_link($listing_id, '', '', '', '', '', '', $cfg_permalink_struct);
}

/*--------------------------------------------------
User details
--------------------------------------------------*/

$query = "SELECT email, first_name FROM users WHERE id = :listing_userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':listing_userid', $listing_userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$listing_user_email = !empty($row['email']) ? $row['email'] : $customer_email;
$listing_user_firstname = !empty($row['first_name']) ? $row['first_name'] : '';

/*--------------------------------------------------
Subscription id from Stripe
--------------------------------------------------*/

if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
	$stripe_subscription_id = null;
}

else {
	$stripe_subscription_id = !empty($event['data']['object']['id']) ? $event['data']['object']['id'] : null;
}

/*--------------------------------------------------
Amount
--------------------------------------------------*/

// init
$amount = null;
$amount_currency = null;

// get values from object data
if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
	$amount = !empty($event['data']['object']['amount']) ? $event['data']['object']['amount'] : null;
	$amount_currency = !empty($event['data']['object']['currency']) ? $event['data']['object']['currency'] : null;
}

else {
	$amount = !empty($event['data']['object']['plan']['amount']) ? $event['data']['object']['plan']['amount'] : null;
	$amount_currency = !empty($event['data']['object']['plan']['currency']) ? $event['data']['object']['plan']['currency'] : null;
}

if(empty($amount) && empty($amount_currency)) {
	$amount = !empty($event['data']['object']['lines']['data'][0]['amount']) ? $event['data']['object']['lines']['data'][0]['amount'] : null;
	$amount_currency = !empty($event['data']['object']['lines']['data'][0]['currency']) ? $event['data']['object']['lines']['data'][0]['currency'] : null;
}

if(empty($amount) && empty($amount_currency)) {
	$amount = !empty($event['data']['object']['amount']) ? $event['data']['object']['amount'] : null;
	$amount_currency = !empty($event['data']['object']['currency']) ? $event['data']['object']['currency'] : null;
}

$amount_currency = mb_strtolower($amount_currency);

/*--------------------------------------------------
Handle event
--------------------------------------------------*/

// if mode is 'payment'
if(!$is_subscription) {
	if($event_type == 'charge.succeeded') {
		// verify amount
		if($plan_price == $amount && $stripe_data_currency == $amount_currency) {
			// get email template
			$query = "SELECT * FROM email_templates WHERE type = 'web_accept'";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$email_subject = !empty($row['subject']) ? $row['subject'] : '';
			$email_body = !empty($row['body']) ? $row['body'] : '';

			// if not a claim listing
			if(empty($claim_userid)) {
				// update paid column in places table
				$query = 'UPDATE places SET paid = 1 WHERE place_id = :listing_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':listing_id', $listing_id);
				$stmt->execute();
			}

			// else is a claim listing
			else {
				// update paid column in places table
				$query = 'UPDATE places SET userid = :userid WHERE place_id = :listing_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':listing_id', $listing_id);
				$stmt->bindValue(':userid', $claim_userid);
				$stmt->execute();
			}
		}

		// else amount mismatch
		else {
			if($debug == 'stripe') {
				$body = "plan_price: $plan_price\namount: $amount\nstripe_data_currency: $stripe_data_currency\namount_currency:$amount_currency\nlisting_id: $listing_id";

				try {
					// mailer params
					$PHPMailer->ClearAllRecipients();
					$PHPMailer->setFrom($admin_email, $site_name);
					$PHPMailer->addAddress($dev_email);
					$PHPMailer->addReplyTo($admin_email);
					$PHPMailer->isHTML(false);
					$PHPMailer->Subject = 'Stripe debug Payment amount';
					$PHPMailer->AllowEmpty = true;
					$PHPMailer->Body = $body;

					// send
					$PHPMailer->send();
				} catch (Exception $e) {
					echo "Message could not be sent.";
				}
			}
		}
	}

	else if($event_type == 'charge.failed') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'web_accept_fail'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = !empty($row['subject']) ? $row['subject'] : '';
		$email_body = !empty($row['body']) ? $row['body'] : '';
	}

	else if($event_type == 'charge.refunded') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'web_accept_fail'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = !empty($row['subject']) ? $row['subject'] : '';
		$email_body = !empty($row['body']) ? $row['body'] : '';

		// if not a claim listing
		if(empty($claim_userid)) {
			// update paid column in places table
			$query = 'UPDATE places SET paid = 0 WHERE place_id = :listing_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':listing_id', $listing_id);
			$stmt->execute();
		}

		// else it's a claim listing
		else {
			// if listing was claimed and refunded, set ownership to admin again
			$query = 'UPDATE places SET userid = 1 WHERE place_id = :listing_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':listing_id', $listing_id);
			$stmt->execute();
		}
	}

	else {
		// log transaction
		error_log("Webhook data is a one-off purchase but charge neither succeeded nor failed/refunded");
	}
}

// else if mode is 'subscription'
else {
	// if subscription succeeded
	if($event_type == 'customer.subscription.created') {
		if($plan_price == $amount && $stripe_data_currency == $amount_currency) {
			// get email template
			$query = "SELECT * FROM email_templates WHERE type = 'subscr_signup'";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$email_subject = $row['subject'];
			$email_body = $row['body'];

			// update paid column in places table
			$query = 'UPDATE places SET paid = 1 WHERE place_id = :listing_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':listing_id', $listing_id);
			$stmt->execute();

			// if claim listing
			if(!empty($claim_userid)) {
				// update paid column in places table
				$query = 'UPDATE places SET userid = :userid WHERE place_id = :listing_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':listing_id', $listing_id);
				$stmt->bindValue(':userid', $claim_userid);
				$stmt->execute();
			}
		}
	}

	else if($event_type == 'charge.succeeded') {
		// log transaction
	}

	else if($event_type == 'invoice.payment_succeeded') {
		// log transaction
	}

	else if($event_type == 'invoice.payment_failed') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'subscr_failed'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];
	}

	else if($event_type == 'customer.subscription.deleted') {
		// get email template
		$query = "SELECT * FROM email_templates WHERE type = 'subscr_eot'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email_subject = $row['subject'];
		$email_body = $row['body'];

		// if not a claim listing
		if(empty($claim_userid)) {
			// update paid column in places table
			$query = 'UPDATE places SET paid = 0 WHERE place_id = :listing_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':listing_id', $listing_id);
			$stmt->execute();
		}

		// else it's a claim listing
		else {
			// if listing was claimed and refunded, set ownership to admin again
			$query = 'UPDATE places SET userid = 1 WHERE place_id = :listing_id';
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':listing_id', $listing_id);
			$stmt->execute();
		}
	}

	else {
		// log transaction
	}
}

/*--------------------------------------------------
Check if subscription payment and listing deleted
--------------------------------------------------*/

// if this is a subscription payment and the listing has been deleted, then cancel the subscription
if($event_type == 'invoice.payment_succeeded') {
	/*
	Perhaps it's better to build logic to notify admin instead

	$query = "SELECT * FROM places WHERE place_id = :listing_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':listing_id', $listing_id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(empty($row['place_id'])) {
		$stripe->subscriptions->cancel(
			$stripe_subscription_id,
			[]
		);
	}
	*/
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
		$PHPMailer->addAddress($listing_user_email);
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

// charge object doesn't contain metadata, so skip this event
if(!in_array($event_type, array('charge.succeeded', 'charge.refunded', 'charge.failed'))) {
	// convert amount to decimal
	$amount = $amount / 100;

	// event_data
	$event_data = print_r($event, true);

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
			:event_type,
			:listing_id,
			:user_id,
			:paym_email,
			:gateway,
			:amount,
			:event_data
		)";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':event_type' , $event_type);
	$stmt->bindValue(':listing_id' , $listing_id);
	$stmt->bindValue(':user_id'    , $listing_userid);
	$stmt->bindValue(':paym_email' , $customer_email);
	$stmt->bindValue(':gateway'    , 'stripe');
	$stmt->bindValue(':amount'     , $amount);
	$stmt->bindValue(':event_data' , $event_data);
	$stmt->execute();
}

// end with http response code
http_response_code(200);