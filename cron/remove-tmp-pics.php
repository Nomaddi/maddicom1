<?php
require_once(__DIR__ . '/../inc/config.php');

// period
$period = 60*60 * 1;

/*--------------------------------------------------
listing-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/' . $place_tmp_folder;

// delete tmp main pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}
		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> listing-tmp removed </li>
	<?php
}

// delete from database table
$query = "DELETE FROM tmp_photos WHERE created < (NOW() - INTERVAL 24 HOUR)";
$stmt = $conn->prepare($query);
$stmt->execute();

/*--------------------------------------------------
coupons-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/coupons-tmp';

// delete coupons tmp pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}

		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> coupons-tmp removed </li>
	<?php
}

/*--------------------------------------------------
logo-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/logo-tmp';

// delete tmp main pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}

		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> logo-tmp removed </li>
	<?php
}

/*--------------------------------------------------
profile-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/' . $profile_tmp_folder;

// delete tmp main pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}

		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> profile-tmp removed </li>
	<?php
}

/*--------------------------------------------------
category-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/category-tmp';

// delete tmp main pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}

		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> category-tmp removed </li>
	<?php
}


/*--------------------------------------------------
page-thumb-tmp
--------------------------------------------------*/
$temp_pic_path = $pic_basepath . '/page-thumb-tmp';

// delete tmp main pics
if(file_exists($temp_pic_path)) {
	$counter = 0;

	foreach (new DirectoryIterator($temp_pic_path) as $v) {
		if ($v->isDot() || $v == 'index.php') {
			continue;
		}

		if ($v->isFile() && time() - $v->getCTime() >= $period) {
			if(unlink($v->getRealPath())) {
				$counter++;
			}
		}
	}
}

if($counter > 0) {
	?>
	<li><?= $counter ?> category-tmp removed </li>
	<?php
}
?>
<?= $txt_ok ?>
