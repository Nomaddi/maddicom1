<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>
</head>
<body class="tpl-user-edit-pass">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<div class="row mb-5">
				<div class="col-sm-7">
					<?php
					if($hybridauth_provider_name == 'local' || $is_admin) {
						?>
						<form  method="post" action="<?= $baseurl ?>/user/process-edit-pass">
							<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

							<div class="form-group">
								<label for="cur_pass"><?= $txt_label_cur_pass ?></label>
								<input type="password" class="form-control" id="cur_pass" name="cur_pass">
							</div>

							<div class="form-group">
									<label for="new_pass"><?= $txt_label_new_pass ?></label>
									<input type="password" class="form-control" id="new_pass" name="new_pass" class="form-control">
							</div>

							<div class="form-group">
								<input type="submit" id="submit" name="submit" class="btn btn-primary px-4">
							</div>
						</form>
						<?php
					}

					else {
						// echo $txt_social_user;
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>