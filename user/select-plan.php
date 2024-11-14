<?php
require_once(__DIR__ . '/../inc/config.php');

if(empty($userid)) {
	$redir_url = $baseurl . '/user/sign-in';
	header("Location: $redir_url");
	die();
}

// get plans
$query = "SELECT * FROM plans WHERE plan_status = 1 ORDER BY plan_order";
$stmt = $conn->prepare($query);
$stmt->execute();

$plans_arr = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$plan_id     = $row['plan_id'];
	$plan_type   = !empty($row['plan_type'    ]) ? $row['plan_type'    ] : '';
	$plan_name   = !empty($row['plan_name'    ]) ? $row['plan_name'    ] : '';
	$plan_period = !empty($row['plan_period'  ]) ? $row['plan_period'  ] : 0;
	$plan_feat   = !empty($row['plan_features']) ? $row['plan_features'] : '';
	$plan_price  = !empty($row['plan_price'   ]) ? $row['plan_price'   ] : '0';

	// sanitize
	// ignored

	// has cents?
	if(!empty($cfg_cur_without_cents)) {
		$plan_price = floor($plan_price);
	}

	// plan description
	$plan_feat = explode("\n", $plan_feat);

	$cur_loop_arr = array(
		'plan_id'     => $plan_id,
		'plan_type'   => $plan_type,
		'plan_name'   => $plan_name,
		'plan_period' => $plan_period,
		'plan_feat'   => $plan_feat,
		'plan_price'  => $plan_price
	);

	$plans_arr[] = $cur_loop_arr;
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/select-plan';
