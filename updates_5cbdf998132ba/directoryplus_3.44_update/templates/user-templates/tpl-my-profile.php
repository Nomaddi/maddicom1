<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>
</head>
<body class="tpl-user-my-profile">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<div class="d-flex">
				<div class="flex-grow-1">
					<h2 class="mb-3"><?= $txt_main_title ?></h2>
				</div>

				<?php
				if($cfg_gdpr_on) {
					?>
					<div>
					<a href="download-data.php" class="btn btn-sm btn-primary"><?= $txt_download_data ?></a>
					</div>
					<?php
				}
				?>
			</div>

			<!-- Notices -->
			<?php
			if(!empty($_GET['e']) && $_GET['e'] == 'email-in-use') {
				?>
				<div class="alert alert-warning alert-dismissible fade show mb-2" role="alert">
					<?= $txt_email_already_in_use ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php
			}
			?>

			<form method="post" action="process-edit-profile.php">
				<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

				<input type="file" id="profile_pic" name="profile_pic" style="display:block;visibility:hidden;width:0;height:0;">
				<input type="hidden" id="uploaded_pic" name="uploaded_pic" value="">

				<div id="profile-pic-fail" class="alert alert-danger" style="display:none"></div>

				<div id="profile-pic-wrapper" class="mb-3">
					<img src="<?= $profile_pic_tag ?>" class="cover main-profile-pic rounded-circle">
				</div>

				<div class="mb-3">
					<button type="button" id="upload-profile-pic" class="btn btn-light btn-sm"><i class="las la-upload"></i> <?= $txt_change ?></button>
					<button type="button" id="delete-profile-pic" class="btn btn-light btn-sm"><i class="lar la-trash-alt"></i> <?= $txt_delete ?></button>
				</div>

				<div class="form-group">
					<label for="first_name"><?= $txt_fname ?></label>
					<input type="text" id="first_name" class="form-control" name="first_name" value="<?= $first_name ?>">
				</div>

				<div class="form-group">
					<label for="last_name"><?= $txt_lname ?></label>
					<input type="text" id="last_name" class="form-control" name="last_name" value="<?= $last_name ?>">
				</div>

				<div class="form-group">
					<label for="email"><?= $txt_email ?></label>
					<input type="text" id="email" class="form-control" name="email" value="<?= $email ?>">
				</div>

				<div class="form-group">
					<label for="profile_city"><?= $txt_city ?></label>
					<input type="text" id="profile_city" class="form-control" name="profile_city" value="<?= $profile_city ?>">
				</div>

				<div class="form-group">
					<label for="profile_city"><?= $txt_country ?></label>
					<input type="text" id="profile_country" class="form-control" name="profile_country" value="<?= $profile_country ?>">
				</div>

				<div class="form-group">
					<button type="submit" id="submit" class="btn btn-primary px-4"><?= $txt_save ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>