<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

/*--------------------------------------------------------------
RECEIVE POST FROM PAYPAL
--------------------------------------------------------------*/
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data  = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$paypal_post    = array();

foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2) {
		$paypal_post[$keyval[0]] = urldecode($keyval[1]);
	}
}

//extract vars
extract($paypal_post, EXTR_OVERWRITE);

// build request
$req = 'cmd=' . urlencode('_notify-validate');
$ipn_vars = 'cmd=' . urlencode('_notify-validate');
foreach ($paypal_post as $k => $v) {
	$v = urlencode($v);
	$req .= "&$k=$v";
}

// sort array keys (only after building $req var which will be used to send curl to paypal)
ksort($paypal_post);
foreach ($paypal_post as $k => $v) {
	$ipn_vars .= "\n$k=" . mb_convert_encoding(urldecode($v), "Windows-1252", "UTF-8") . '<br>';
}

/*--------------------------------------------------------------
SEND POST BACK TO PAYPAL
--------------------------------------------------------------*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paypal_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);

// if curl error
if(curl_errno($ch)) {
	$ipn_response = curl_error($ch);

	// send message to dev
	try {
		// mailer params
		$PHPMailer->ClearAllRecipients();
		$PHPMailer->setFrom($admin_email, $site_name);
		$PHPMailer->addAddress($dev_email);
		$PHPMailer->addReplyTo($admin_email);
		$PHPMailer->isHTML(false);
		$PHPMailer->Subject = 'Curl error';
		$PHPMailer->Body = curl_error($ch);

		// send
		$PHPMailer->send();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
	}

	die('Curl error');
}

// else no curl error
else {
	if(strcmp($res, "VERIFIED") == 0) {
		$ipn_response = "VERIFIED";

		// ipn vars
		$business       = isset($_POST['business'      ]) ? $_POST['business'      ] : '';
		$first_name     = isset($_POST['first_name'    ]) ? $_POST['first_name'    ] : '';
		$item_name      = isset($_POST['item_name'     ]) ? $_POST['item_name'     ] : '';
		$item_number    = isset($_POST['item_number'   ]) ? $_POST['item_number'   ] : '';
		$mc_amount3     = isset($_POST['mc_amount3'    ]) ? $_POST['mc_amount3'    ] : '';
		$mc_gross       = isset($_POST['mc_gross'      ]) ? $_POST['mc_gross'      ] : '';
		$payer_email    = isset($_POST['payer_email'   ]) ? $_POST['payer_email'   ] : '';
		$payment_amount = isset($_POST['mc_gross'      ]) ? $_POST['mc_gross'      ] : '';
		$mc_currency    = isset($_POST['mc_currency'   ]) ? $_POST['mc_currency'   ] : '';
		$payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
		$place_id       = isset($_POST['custom'        ]) ? $_POST['custom'        ] : '';
		$receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
		$subscr_id      = isset($_POST['subscr_id'     ]) ? $_POST['subscr_id'     ] : '';
		$txn_id         = isset($_POST['txn_id'        ]) ? $_POST['txn_id'        ] : '';
		$txn_type       = isset($_POST['txn_type'      ]) ? $_POST['txn_type'      ] : '';
		$period1        = isset($_POST['period1'       ]) ? $_POST['period1'       ] : '';
		$period3        = isset($_POST['period3'       ]) ? $_POST['period3'       ] : '';

		// listing link to use in emails
		$listing_link = get_listing_link($place_id, '', '', '', '', '', '', $cfg_permalink_struct);

		// get user id
		$query = "SELECT userid FROM places	WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$place_userid = !empty($row['userid']) ? $row['userid'] : 1;

		// get plan details associated with this place
		$query = "SELECT plans.* FROM places
			RIGHT JOIN plans ON places.plan = plans.plan_id
			WHERE places.place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$plan_id     = $row['plan_id'];
		$plan_type   = !empty($row['plan_type'  ]) ? $row['plan_type'  ] : 'free';
		$plan_price  = !empty($row['plan_price' ]) ? $row['plan_price' ] : '0.00';
		$plan_period = !empty($row['plan_period']) ? $row['plan_period'] : '36500';

		$one_letter_period = '';
		if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
			$one_letter_period = '1 M';
		}

		if($plan_type == 'annual' || $plan_type == 'annual_feat') {
			$one_letter_period = '1 Y';
		}

		// get user details
		$query = "SELECT email, first_name FROM users WHERE id = :place_userid";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_userid', $place_userid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$place_user_email     = !empty($row['email'     ]) ? $row['email'     ] : '';
		$place_user_firstname = !empty($row['first_name']) ? $row['first_name'] : $first_name;

		/*--------------------------------------------------
		SWITCH IPN TRANSACTION TYPES
		--------------------------------------------------*/

		switch($txn_type) {
			/* paypal transaction types for subscriptions
			subscr_signup	Subscription started
			subscr_payment	Subscription payment received
			subscr_cancel	Subscription canceled
			subscr_eot	    Subscription expired
			subscr_failed	Subscription payment failed
			subscr_modify	Subscription modified
			*/

			case 'subscr_signup':
				if(!empty($mc_amount3) && $mc_amount3 == $plan_price && $mc_currency == $currency_code && $period3 == $one_letter_period) {
					$ipn_description = 'subscr_signup:success';

					// email user informing subscr_signup success
					$query = "SELECT * FROM email_templates WHERE type = 'subscr_signup'";
					$stmt = $conn->prepare($query);
					$stmt->execute();
					$row = $stmt->fetch(PDO::FETCH_ASSOC);

					$email_subject = $row['subject'];
					$email_body = $row['body'];

					// string replacements
					$email_subject = str_replace('%site_name%', $site_name, $email_subject);
					$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
					$email_body = str_replace('%site_name%', $site_name, $email_body);
					$email_body = str_replace('%site_url%', $baseurl, $email_body);
					$email_body = str_replace('%place_link%', $listing_link, $email_body);
					$email_body = str_replace('%listing_link%', $listing_link, $email_body);

					// send subscr_signup email
					try {
						// mailer params
						$PHPMailer->ClearAllRecipients();
						$PHPMailer->setFrom($admin_email, $site_name);
						$PHPMailer->addAddress($payer_email);
						$PHPMailer->addReplyTo($admin_email);
						$PHPMailer->isHTML(false);
						$PHPMailer->Subject = $email_subject;
						$PHPMailer->Body = $email_body;

						// send
						$PHPMailer->send();
					} catch (Exception $e) {
						echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
					}

					// update paid column in places table
					$query = 'UPDATE places SET paid = 1 WHERE place_id = :place_id';
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':place_id', $place_id);
					$stmt->execute();

					// sitemaps
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

				else {
					// else problem with amount
					$ipn_description = 'subscr_signup:wrong amount';
				}

				// insert into 'transactions' table
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
						:user,
						:paym_email,
						:gateway,
						:amount,
						:txn_data
					)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':txn_type'  , $ipn_description);
				$stmt->bindValue(':place_id'  , $place_id);
				$stmt->bindValue(':user'      , $place_userid);
				$stmt->bindValue(':paym_email', $payer_email);
				$stmt->bindValue(':gateway'   , 'paypal');
				$stmt->bindValue(':amount'    , $mc_amount3);
				$stmt->bindValue(':txn_data'  , $ipn_vars);
				$stmt->execute();

			break;

			case 'subscr_payment':
				$ipn_description = 'subscr_payment:success';

				// for subscr_payment, just insert transaction into db
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
						:user,
						:paym_email,
						:gateway,
						:amount,
						:txn_data
					)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':txn_type'  , $txn_type);
				$stmt->bindValue(':place_id'  , $place_id);
				$stmt->bindValue(':user'      , $place_userid);
				$stmt->bindValue(':paym_email', $payer_email);
				$stmt->bindValue(':gateway'   , 'paypal');
				$stmt->bindValue(':amount'    , $mc_gross);
				$stmt->bindValue(':txn_data'  , $ipn_vars);
				$stmt->execute();
			break;

			case 'subscr_cancel':
				$ipn_description = 'subscr_cancel';

				// just insert cancel transaction, no need to update place paid field, do on subscr_eot
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
						:user,
						:paym_email,
						:gateway,
						:amount,
						:txn_data
					)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':txn_type'  , $ipn_description);
				$stmt->bindValue(':place_id'  , $place_id);
				$stmt->bindValue(':user'      , $place_userid);
				$stmt->bindValue(':paym_email', $payer_email);
				$stmt->bindValue(':gateway'   , 'paypal');
				$stmt->bindValue(':amount'    , $mc_gross);
				$stmt->bindValue(':txn_data'  , $ipn_vars);
				$stmt->execute();
			break;

			case 'subscr_eot':
				$ipn_description = 'subscr_eot';

				// for subscr_eot, insert transaction into db and update place paid to 0
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
						:user,
						:paym_email,
						:gateway,
						:amount,
						:txn_data
					)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':txn_type'  , $ipn_description);
				$stmt->bindValue(':place_id'  , $place_id);
				$stmt->bindValue(':user'      , $place_userid);
				$stmt->bindValue(':paym_email', $payer_email);
				$stmt->bindValue(':gateway'   , 'paypal');
				$stmt->bindValue(':amount'    , $mc_gross);
				$stmt->bindValue(':txn_data'  , $ipn_vars);
				$stmt->execute();

				// update places, set paid to 0
				$query = 'UPDATE places SET paid = 0 WHERE place_id = :place_id';
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':place_id', $place_id);
				$stmt->execute();

				// remove from sitemap if necessary
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

				// email user informing subscr_eot
				$query = "SELECT * FROM email_templates WHERE type = 'subscr_eot'";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$email_subject = $row['subject'];
				$email_body    = $row['body'];

				// string replacements
				$email_subject = str_replace('%site_name%', $site_name, $email_subject);
				$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
				$email_body = str_replace('%site_name%', $site_name, $email_body);
				$email_body = str_replace('%site_url%', $baseurl, $email_body);
				$email_body = str_replace('%place_link%', $listing_link, $email_body); // legacy
				$email_body = str_replace('%listing_link%', $listing_link, $email_body);

				// send subscr_eot email
				try {
					// mailer params
					$PHPMailer->ClearAllRecipients();
					$PHPMailer->setFrom($admin_email, $site_name);
					$PHPMailer->addAddress($payer_email);
					$PHPMailer->addReplyTo($admin_email);
					$PHPMailer->isHTML(false);
					$PHPMailer->Subject = $email_subject;
					$PHPMailer->Body = $email_body;

					// send
					$PHPMailer->send();
				} catch (Exception $e) {
					echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
				}

			break;

			case 'subscr_failed':
				$ipn_description = 'subscr_failed';

				// insert into transactions table
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
						:user,
						:paym_email,
						:gateway,
						:amount,
						:txn_data
					)";

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':txn_type'  , $txn_type);
				$stmt->bindValue(':place_id'  , $place_id);
				$stmt->bindValue(':user'      , $place_userid);
				$stmt->bindValue(':paym_email', $payer_email);
				$stmt->bindValue(':gateway'   , 'paypal');
				$stmt->bindValue(':amount'    , $mc_gross);
				$stmt->bindValue(':txn_data'  , $ipn_vars);
				$stmt->execute();

				// send email to user telling that his subscription payment failed
				$query = "SELECT * FROM email_templates WHERE type = 'subscr_failed'";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$email_subject = $row['subject'];
				$email_body    = $row['body'];

				// string replacements
				$email_subject = str_replace('%site_name%', $site_name, $email_subject);
				$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
				$email_body = str_replace('%site_name%', $site_name, $email_body);
				$email_body = str_replace('%site_url%', $baseurl, $email_body);
				$email_body = str_replace('%place_link%', $listing_link, $email_body); // legacy
				$email_body = str_replace('%listing_link%', $listing_link, $email_body);

				// send subscr_failed email
				try {
					// mailer params
					$PHPMailer->ClearAllRecipients();
					$PHPMailer->setFrom($admin_email, $site_name);
					$PHPMailer->addAddress($payer_email);
					$PHPMailer->addReplyTo($admin_email);
					$PHPMailer->isHTML(false);
					$PHPMailer->Subject = $email_subject;
					$PHPMailer->Body = $email_body;

					// send
					$PHPMailer->send();
				} catch (Exception $e) {
					echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
				}

			break;

			case 'subscr_modify':
				//
			break;

			// case = web_accept when plan is of type one_time or one_time_feat
			case 'web_accept':
				if(!empty($mc_gross) && $mc_gross == $plan_price && $mc_currency == $currency_code) {
					$ipn_description = 'web_accept: success';

					// email user informing web_accept success
					$query = "SELECT * FROM email_templates WHERE type = 'web_accept'";
					$stmt = $conn->prepare($query);
					$stmt->execute();
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$email_subject = $row['subject'];
					$email_body = $row['body'];

					// string replacements
					$email_subject = str_replace('%site_name%', $site_name, $email_subject);
					$email_subject = str_replace('%site_url%', $baseurl, $email_subject);
					$email_body = str_replace('%site_name%', $site_name, $email_body);
					$email_body = str_replace('%site_url%', $baseurl, $email_body);
					$email_body = str_replace('%place_link%', $listing_link, $email_body); // legacy
					$email_body = str_replace('%listing_link%', $listing_link, $email_body);

					// send subscr_failed email
					try {
						// mailer params
						$PHPMailer->ClearAllRecipients();
						$PHPMailer->setFrom($admin_email, $site_name);
						$PHPMailer->addAddress($payer_email);
						$PHPMailer->addReplyTo($admin_email);
						$PHPMailer->isHTML(false);
						$PHPMailer->Subject = $email_subject;
						$PHPMailer->Body = $email_body;

						// send
						$PHPMailer->send();
					} catch (Exception $e) {
						echo "Message could not be sent. Mailer Error: {$PHPMailer->ErrorInfo}";
					}

					//
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
							:user,
							:paym_email,
							:gateway,
							:amount,
							:txn_data
						)";

					$stmt = $conn->prepare($query);
					$stmt->bindValue(':txn_type'  , $ipn_description);
					$stmt->bindValue(':place_id'  , $place_id);
					$stmt->bindValue(':user'      , $place_userid);
					$stmt->bindValue(':paym_email', $payer_email);
					$stmt->bindValue(':gateway'   , 'paypal');
					$stmt->bindValue(':amount'    , $mc_gross);
					$stmt->bindValue(':txn_data'  , $ipn_vars);
					$stmt->execute();

					// update paid column in places table
					if($payment_status != 'Reversed') {
						$query = 'UPDATE places SET
									paid = 1,
									valid_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :valid_until DAY)
								WHERE place_id = :place_id';
						$stmt = $conn->prepare($query);
						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':valid_until', $plan_period);
						$stmt->execute();
					}

					// add to sitemap if necessary
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

					// if it's a reversal
					if($payment_status == 'Reversed' || $payment_status == 'Refunded' ) {
						$query = 'UPDATE places SET
									paid = 0,
									valid_until = CURRENT_TIMESTAMP
								WHERE place_id = :place_id';
						$stmt = $conn->prepare($query);
						$stmt->bindValue(':place_id', $place_id);
						$stmt->execute();

						// remove sitemap if necessary
						if($cfg_enable_sitemaps) {
							$query = 'SELECT status FROM places WHERE place_id = :place_id';
							$stmt = $conn->prepare($query);
							$stmt->bindValue(':place_id', $place_id);
							$stmt->execute();

							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$status = $row['status'];

							// if status approved, add url to sitemap
							if($status == 'approved') {
								sitemap_remove_url($listing_link);
							}
						}
					}
				}

				// else mc_gross != $plan_price
				else {
					$ipn_description = 'web_accept:mc_gross fail';

					// transactions table
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
							:user,
							:paym_email,
							:gateway,
							:amount,
							:txn_data
						)";

					$stmt = $conn->prepare($query);
					$stmt->bindValue(':txn_type'  , $ipn_description);
					$stmt->bindValue(':place_id'  , $place_id);
					$stmt->bindValue(':user'      , $place_userid);
					$stmt->bindValue(':paym_email', $payer_email);
					$stmt->bindValue(':gateway'   , 'paypal');
					$stmt->bindValue(':amount'    , $mc_gross);
					$stmt->bindValue(':txn_data'  , $ipn_vars);
					$stmt->execute();
				}
			break;
		}
	}
}