<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// plan details
$params = array();
parse_str($_POST['params'], $params);

// get params
$plan_name     = !empty($params['plan_name'    ]) ? $params['plan_name'    ] : '';
$plan_type     = !empty($params['plan_type'    ]) ? $params['plan_type'    ] : '';
$plan_features = !empty($params['plan_features']) ? $params['plan_features'] : '';
$plan_period   = !empty($params['plan_period'  ]) ? $params['plan_period'  ] : 0;
$plan_order    = !empty($params['plan_order'   ]) ? $params['plan_order'   ] : 0;
$plan_price    = !empty($params['plan_price'   ]) ? $params['plan_price'   ] : 0;
$plan_status   = !empty($params['plan_status'  ]) ? $params['plan_status'  ] : 0;

// trim
$plan_name     = trim($plan_name);
$plan_features = trim($plan_features);
$plan_period   = trim($plan_period);
$plan_order    = trim($plan_order);
$plan_price    = trim($plan_price);

// set types
$plan_period   = intval($plan_period);
$plan_status   = intval($plan_status);
$plan_order    = intval($plan_order);

// plan period is 0 if plan type is monthly or annual
if($plan_type == 'monthly' || $plan_type == 'monthly_feat' || $plan_type == 'annual' || $plan_type == 'annual_feat') {
	$plan_period = 0;
}

// check vars
if(empty($plan_name)) {
	echo "plan name cannot be empty";
	die();
}

// check if plan type is valid
$valid_types = array('free', 'free_feat', 'one_time', 'one_time_feat', 'monthly', 'monthly_feat', 'annual', 'annual_feat');
if(!in_array($plan_type, $valid_types)) {
	echo "wrong plan type";
	die();
}

// set price to 0 if plan types are free
if($plan_type == 'free' || $plan_type == 'free_feat') {
	$plan_price = 0;
}

// query
$query = "INSERT INTO plans(
			plan_type,
			plan_name,
			plan_features,
			plan_period,
			plan_price,
			plan_order,
			plan_status)
	VALUES(
			:plan_type,
			:plan_name,
			:plan_features,
			:plan_period,
			:plan_price,
			:plan_order,
			:plan_status)";

$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_name'     , $plan_name);
$stmt->bindValue(':plan_type'     , $plan_type);
$stmt->bindValue(':plan_features' , $plan_features);
$stmt->bindValue(':plan_period'   , $plan_period);
$stmt->bindValue(':plan_price'    , $plan_price);
$stmt->bindValue(':plan_order'    , $plan_order);
$stmt->bindValue(':plan_status'   , $plan_status);

if($stmt->execute()) {
	echo '1';
}