<?php
require_once(__DIR__ . '/../inc/config.php');

if(empty($userid)) {
	$redir_url = $baseurl . '/user/sign-in';
	header("Location: $redir_url");
	die();
}

$place_id = !empty($_GET['id']) ? $_GET['id'] : '';
$plan_id = !empty($_GET['plan']) ? $_GET['plan'] : '';

// check if place id is numeric
if(!is_numeric($place_id) || !is_numeric($plan_id)) {
	throw new Exception('Invalid query string');
}

// check plan id selection
if(empty($plan_id)) {
	throw new Exception('Invalid plan selection');
}

// query db for place details
$query = "SELECT * FROM places WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$place_name   = !empty($row['place_name'  ]) ? $row['place_name'  ] : '';
	$address      = !empty($row['address'     ]) ? $row['address'     ] : '';
	$postal_code  = !empty($row['postal_code' ]) ? $row['postal_code' ] : '';
	$cross_street = !empty($row['cross_street']) ? $row['cross_street'] : '';
	$neighborhood = !empty($row['neighborhood']) ? $row['neighborhood'] : 0;
	$city_id      = !empty($row['city_id'     ]) ? $row['city_id'     ] : 0;
	$inside       = !empty($row['inside'      ]) ? $row['inside'      ] : '';
	$area_code    = !empty($row['area_code'   ]) ? $row['area_code'   ] : '';
	$phone        = !empty($row['phone'       ]) ? $row['phone'       ] : '';
	$description  = !empty($row['description' ]) ? $row['description' ] : '';
	$place_userid = !empty($row['userid'      ]) ? $row['userid'      ] : '1';

	// sanitize
	$place_name   = e($place_name  );
	$address      = e($address     );
	$postal_code  = e($postal_code );
	$cross_street = e($cross_street);
	$neighborhood = e($neighborhood);
	$city_id      = e($city_id     );
	$inside       = e($inside      );
	$area_code    = e($area_code   );
	$phone        = e($phone       );
	$description  = e($description );
	$place_userid = e($place_userid);
}

// only allow claiming if place_userid == 1, that is, created by admin
if($place_userid != 1) {
	throw new Exception('This place has already been claimed');
}

// city and state details
$query = "SELECT
		c.city_name, c.slug AS city_slug,
		s.state_id, s.state_name, s.state_abbr, s.slug AS state_slug
		FROM cities c
		LEFT JOIN states s ON c.state_id = s.state_id
		WHERE city_id = :city_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $city_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$city_name  = !empty($row['city_name' ]) ? $row['city_name' ] : '';
$city_slug  = !empty($row['city_slug' ]) ? $row['city_slug' ] : '';
$state_id   = !empty($row['state_id'  ]) ? $row['state_id'  ] : '';
$state_name = !empty($row['state_name']) ? $row['state_name'] : '';
$state_abbr = !empty($row['state_abbr']) ? $row['state_abbr'] : '';
$state_slug = !empty($row['state_slug']) ? $row['state_slug'] : '';

// get plan details
/* plan types
free
free_feat
one_time
one_time_feat
monthly
monthly_feat
*/
$query = "SELECT plan_type, plan_name, plan_period, plan_price, plan_status FROM plans WHERE plan_id = :plan_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_id', $plan_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$plan_type   = !empty($row['plan_type'  ]) ? $row['plan_type'  ] : '';
$plan_name   = !empty($row['plan_name'  ]) ? $row['plan_name'  ] : '';
$plan_period = !empty($row['plan_period']) ? $row['plan_period'] : 0;
$plan_price  = !empty($row['plan_price' ]) ? $row['plan_price' ] : 0;
$plan_status = !empty($row['plan_status']) ? $row['plan_status'] : '';

// if not a free plan
if($plan_type != 'free' && $plan_type != 'free_feat') {
	// if it's a monthly plan
	if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
		// init vars
		$cmd = "_xclick-subscriptions";
		$p3  = '1';
		$t3  = 'M';
		$src = '1';
		$srt = '52';
		$a3  = $plan_price;
	}

	// if it's an annual plan
	if($plan_type == 'annual' || $plan_type == 'annual_feat') {
		// init vars
		$cmd = "_xclick-subscriptions";
		$p3  = '1';
		$t3  = 'Y';
		$src = '1';
		$srt = '52';
		$a3  = $plan_price;
		$amount = $plan_price;
	}

	// if it's a one time plan
	if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
		// init vars
		$cmd = "_xclick";
		$amount = $plan_price;
	}

	// bn (<Company>_<Service>_<Product>_<Country>)
	$bn = $paypal_bn . '_Subscribe_WPS_' . $default_country_code;
} // end if($plan_type != 'free' && $plan_type != 'free_feat')

// paypal form vars
$claim_notify_url = $baseurl . '/payment-gateways/ipn-claim.php';

/*--------------------------------------------------
stripe vars
--------------------------------------------------*/
// if stripe live mode
if($stripe_mode == 1) {
	$stripe_key = $stripe_live_publishable_key;
}

// else is stripe test mode
else {
	$stripe_key = $stripe_test_publishable_key;
}

$stripe_amount = str_replace('.', '', $plan_price);
$stripe_amount = str_replace(',', '', $stripe_amount);

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/process-claim';
