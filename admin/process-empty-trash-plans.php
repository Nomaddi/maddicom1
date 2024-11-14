<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// delete plans from db
$query = "UPDATE plans SET plan_status = -2 WHERE plan_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();

echo '1';