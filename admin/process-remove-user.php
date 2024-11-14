<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$remove_userid = (!empty($_POST['user_id'])) ? $_POST['user_id'] : 0;

if(!empty($remove_userid)) {
	try {
		$conn->beginTransaction();

		// mark user as trashed
		$query = "UPDATE users SET status = 'trashed' WHERE id = :user_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':user_id', $remove_userid);
		$stmt->execute();

		// sign out user
		$query = "DELETE FROM loggedin WHERE userid = :user_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':user_id', $remove_userid);
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