<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php include_once(__DIR__ . '/../head.php') ?>
<?php include_once('user-head.php') ?>
</head>
<body class="tpl-user-register-confirm">
<?php include_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_main_title ?></strong></h4>
				</div>

				<div class="card-body">
					<?php
					if(!empty($user_confirmed)) {
						?>
						<?= $txt_confirmation_success ?>

						<?= $txt_sign_in ?>
						<?php
					}

					else {
						?>
						<?= $txt_confirmation_fail ?>

						<?= $txt_try_again ?>
						<?php
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