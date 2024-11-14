<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// category details
$cat_id = !empty($_POST['cat_id']) ? $_POST['cat_id'] : 0;

if(!empty($cat_id)) {
	$query = "DELETE FROM cats WHERE id = :cat_id AND cat_status = 0";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);

	if($stmt->execute()) {
		// delete category image
		$this_cat_img = glob("$pic_basepath/category/cat-$cat_id.*");

		foreach($this_cat_img as $v) {
			unlink($v);
		}

		echo '1';
	}
}

else {
	echo "Empty category id";
}