<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// define vars
$webhook_url = $baseurl . '/payment-gateways/stripe.php';
$api_version = '2019-10-08';
$create_webhook_endpoint = "https://api.stripe.com/v1/webhook_endpoints";

// post data
$stripe_key = !empty($_POST['stripe_key']) ? $_POST['stripe_key'] : '';

// trim
$stripe_key = trim($stripe_key);

// curl function
function webhook_curl($url, $stripe_key, $webhook_url, $api_version) {
	// init curl
	$ch = curl_init();

	// headers
	$headers = array();

	// options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	curl_setopt($ch, CURLOPT_TIMEOUT, 180);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_ENCODING, '');

	// stripe key
	curl_setopt($ch, CURLOPT_USERPWD, $stripe_key . ':');

	// stripe options
	$data = array(
		'url' => $webhook_url,
		'api_version' => $api_version,
		'enabled_events' => array(
			'charge.succeeded',
			'charge.refunded',
			'charge.failed',
			'customer.subscription.created',
			'customer.subscription.deleted',
			'invoice.payment_succeeded',
			'invoice.payment_failed',
		),
	);

	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

	// exec
	$data = curl_exec($ch);

	// close
	curl_close($ch);

	// return value
	return $data;
}

// process request
if(!empty($stripe_key)) {
	$result = webhook_curl($create_webhook_endpoint, $stripe_key, $webhook_url, $api_version);

	echo $result;
}

else {
	echo '{ "error": { "message": "Empty API Key provided", "type": "invalid_request_error" } }';
}

