<?php
require_once(__DIR__ . '/../inc/config.php');

// set referrer
$referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $baseurl;

// define referrer
$allow_referrer = array(
	'advanced-results',
	'advanced-search',
	'categories',
	'claim',
	'contact',
	'coupon',
	'coupons',
	'home',
	'listing',
	'listings',
	'msg',
	'post',
	'posts',
	'post',
	'posts',
	'profile',
	'search',
);

foreach($allow_referrer as $v) {
	$pos = strpos($referrer, $v);

	if($pos == false) {
		$referrer = $baseurl;
	}
}

/*

FORM SUBMITTED AND SHOW FORM
	wrong pass
	login with social but email previously used (email already used)
	user not registered

FORM SUBMITTED AND DON'T SHOW FORM
	pending account (resend confirmation)

FORM NOT SUBMITTED AND SHOW FORM
*/

// init vars
$wrong_pass = 0;
$email_already_used = 0;
$user_registered = true;
$account_pending = 0;
$show_form = true;

// if page requested by submitting login form
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email    = !empty($_POST['email'   ]) ? $_POST['email'   ] : '';
	$password = !empty($_POST['password']) ? $_POST['password'] : '';

	// trim
	$email    = trim($email);
	$password = trim($password);

	$stmt = $conn->prepare("SELECT id, password, status FROM users WHERE email = :email");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$userid          = !empty($row['id'      ]) ? $row['id'      ] : '';
	$password_hashed = !empty($row['password']) ? $row['password'] : '';
	$status          = !empty($row['status'  ]) ? $row['status'  ] : '';

	// if email is not registered
	if(empty($row)) {
		$user_registered = false;
	}

	if($status == 'approved') {
		// verify password
		if(password_verify($password, $password_hashed)) {
			// generate random token
			$token = bin2hex(openssl_random_pseudo_bytes(16));

			// set cookie, provider is current site
			// signature: setcookie(name, value, expire, path, domain, secure, httponly);
			$cookie_val = "$userid-localhost-$token";
			setcookie('loggedin', $cookie_val, time()+86400*30, $install_path, '', '', true);

			// record tokens in db
			record_tokens($userid, 'localhost', $token);

			// start session
			$_SESSION['user_connected'] = true;
			$_SESSION['userid'] = $userid;
			$_SESSION['loggedin_token'] = $token;

			// redirect
			header("Location: $referrer");
		}

		// wrong email or password?
		else {
			$wrong_pass = 1;
		}
	}

	if($status == 'pending') {
		$show_form = false;
		$account_pending = true;
	}
}

// else, if login page request by clicking a provider button
if(isset($_GET['provider'])) {
	// vars
	$email_already_used = 0;

	// the selected provider
	$provider_name = $_GET['provider'];
	$referrer      = (isset($_GET['referrer'])) ? $_GET['referrer'] : '';

	// include HybridAuth library
	$config = __DIR__ . '/../inc/hybridauth-config.php';

	try {
		// initialize Hybrid_Auth class with the config file
		$hybridauth = new Hybrid_Auth($config);

		// try to authenticate with the selected provider
		$adapter = $hybridauth->authenticate($provider_name);

		// then grab the user profile
		$user_profile = $adapter->getUserProfile();
	}

	catch( Exception $e ) {
		$exception = $e->getMessage();
		die($exception);
	}

	$provider_user_id = $user_profile->identifier;
	$first_name = !empty($user_profile->firstName) ? $user_profile->firstName : '';
	$last_name  = !empty($user_profile->lastName ) ? $user_profile->lastName  : '';
	$email      = isset($user_profile->email     ) ? $user_profile->email     : $provider_user_id;

	// check if social email + local provider exists
	$count = 0;
	$stmt = $conn->prepare("SELECT COUNT(*) AS total_rows FROM users WHERE email = :email AND hybridauth_provider_name = 'local'");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$count = $stmt->fetchColumn();

	if($count > 0) {
		$email_already_used = 1;
	}

	// if social email + local provider doesn't exist
	if($email_already_used == 0) {
		// check if social id + social provider exists
		$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE hybridauth_provider_name = :provider_name AND hybridauth_provider_uid = :provider_user_id");
		$stmt->bindValue(':provider_name', $provider_name);
		$stmt->bindValue(':provider_user_id', $provider_user_id);
		$stmt->execute();
		$count = $stmt->fetchColumn();

		// if social id + social provider doesn't exist
		if($count < 1) {
			// check if email already exists (case when signed up with a different provider)
			$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			$count2 = $stmt->fetchColumn();

			// if email doesn't exist
			if($count2 < 1) {
				// generate a random password
				$password        = generatePassword();
				$password_hashed = password_hash($password, PASSWORD_BCRYPT);

				$stmt = $conn->prepare('
				INSERT INTO users(
					email,
					password,
					first_name,
					last_name,
					hybridauth_provider_name,
					hybridauth_provider_uid,
					created,
					status
					)
				VALUES(
					:email,
					:password,
					:first_name,
					:last_name,
					:hybridauth_provider_name,
					:hybridauth_provider_uid,
					NOW(),
					:status
					)
				');

				$stmt->bindValue(':email'                   , $email);
				$stmt->bindValue(':password'                , $password_hashed);
				$stmt->bindValue(':first_name'              , $first_name);
				$stmt->bindValue(':last_name'               , $last_name);
				$stmt->bindValue(':hybridauth_provider_name', $provider_name);
				$stmt->bindValue(':hybridauth_provider_uid' , $provider_user_id);
				$stmt->bindValue(':status'                  , 'approved');
				$stmt->execute();

				// get the id of the user that we've just created
				$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND password = :password");
				$stmt->bindValue(':email', $email);
				$stmt->bindValue(':password', $password_hashed);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$userid = $row['id'];

				// save profile picture if exists
				// check if there is a picture
				$photo_url = (isset($user_profile->photoURL)) ? $user_profile->photoURL : '';
			}

			// else user registered with another provider which has the same email
			else {
				$email_already_used = 1;
			}
		}

		// else user have already authenticated with this provider and is already registered. Get userid
		else {
			$stmt = $conn->prepare("SELECT id FROM users WHERE hybridauth_provider_name = :provider_name AND hybridauth_provider_uid = :provider_user_id");
			$stmt->bindValue(':provider_name', $provider_name);
			$stmt->bindValue(':provider_user_id', $provider_user_id);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$userid = $row['id'];
		}

		if(!empty($userid)) {
			// create token
			$token = bin2hex(openssl_random_pseudo_bytes(16));

			// record tokens
			record_tokens($userid, $provider_name, $token);

			// set session vars
			$_SESSION['user_connected'] = true;
			$_SESSION['userid'] = $userid;
			$_SESSION['loggedin_token'] = $token;

			// set cookie
			$cookie_val = "$userid-$provider_name-$token";
			setcookie('loggedin', $cookie_val, time()+86400*30, $install_path, '', '', true);

			// redirect
			header("Location: $baseurl");
		}
	}
}

/*--------------------------------------------------
Canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/sign-in';
