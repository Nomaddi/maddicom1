<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_php.php');

$email        = !empty($_POST['email'          ]) ? $_POST['email'          ] : '';
$first_name   = !empty($_POST['first_name'     ]) ? $_POST['first_name'     ] : '';
$last_name    = !empty($_POST['last_name'      ]) ? $_POST['last_name'      ] : '';
$city_name    = !empty($_POST['profile_city'   ]) ? $_POST['profile_city'   ] : '';
$country_name = !empty($_POST['profile_country']) ? $_POST['profile_country'] : '';

// prevent demo account from being edited
$stmt = $conn->prepare('SELECT email FROM users WHERE id = :id');
$stmt->bindValue(':id', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!empty($row['email']) && $row['email'] == 'user@example.com') {
	$email = 'user@example.com';
}

// check if email is already used by another user
$stmt = $conn->prepare('SELECT COUNT(*) AS totalcount FROM users WHERE email = :email AND id != :userid');
$stmt->bindValue(':email', $email);
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['totalcount'] > 0) {
	header("Location: $baseurl/user/my-profile?e=email-in-use");
	die();
}

// get ip
$ip = get_ip();

$stmt = $conn->prepare('
	UPDATE users SET
		email        = :email,
		first_name   = :first_name,
		last_name    = :last_name,
		city_name    = :city_name,
		country_name = :country_name,
		ip_addr      = :ip
	WHERE id = :id
	');

$stmt->bindValue(':email', $email);
$stmt->bindValue(':first_name', $first_name);
$stmt->bindValue(':last_name', $last_name);
$stmt->bindValue(':city_name', $city_name);
$stmt->bindValue(':country_name', $country_name);
$stmt->bindValue(':ip', $ip);
$stmt->bindValue(':id', $userid);

if($stmt->execute()) {
	header("Location: $baseurl/user/my-profile");
	die();
}
