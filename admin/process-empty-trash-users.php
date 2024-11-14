<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// quick check
$from = !empty($_POST['from_check']) ? $_POST['from_check'] : '';

if($from == 'admin-users-trash') {
	// delete profile pics
	$query = "SELECT id FROM users WHERE status = 'trashed'";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_user_id = $row['id'];

		// remove profile picture
		$folder = floor($this_user_id / 1000) + 1;

		if(strlen($folder) < 1) {
			$folder = '999';
		}

		// profile pic path
		$full_pic_path  = $pic_basepath . '/' . $profile_full_folder . '/' . $folder . '/' . $this_user_id;
		$thumb_pic_path = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder . '/' . $this_user_id;

		// check if file exists
		$full_pic_arr = glob("$full_pic_path.*");
		$thumb_pic_arr = glob("$thumb_pic_path.*");

		if(!empty($full_pic_arr)) {
			foreach($full_pic_arr as $k => $v) {
				if(is_file($v)) {
					unlink($v);
				}
			}
		}
		if(!empty($thumb_pic_arr)) {
			foreach($thumb_pic_arr as $k => $v) {
				unlink($v);
			}
		}
	}

	// delete from database
	$query = "DELETE FROM users WHERE status = 'trashed'";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	echo '1';
}

else {
	echo 'Invalid from_check';
}