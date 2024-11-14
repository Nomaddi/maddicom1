<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$user_id = (!empty($_POST['user_id'])) ? $_POST['user_id'] : 0;

if(!empty($user_id)) {
	try {
		$conn->beginTransaction();

		// mark user as trashed
		$query = "UPDATE users SET status = 'trashed' WHERE id = :user_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();

		// sign out user
		$query = "DELETE FROM loggedin WHERE userid = :user_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();

		$conn->commit();

		echo '1';
	}

	catch(PDOException $e) {
		$conn->rollBack();
		echo $e->getMessage();
	}
}

else {
	echo "Invalid user_id";
}