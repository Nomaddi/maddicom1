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
<body class="tpl-user-resend-confirmation">
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
					if(!$form_submitted) {
						?>
						<form method="post" action="<?= $baseurl ?>/user/resend-confirmation">
							<div class="form-group">
								<label for="email"><?= $txt_email ?></label>
								<input type="text" id="email" class="form-control" name="email">
							</div>

							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-block" name="submit">
							</div>
						</form>
						<?php
					}

					if($request_sent) {
						?>
						<?= $txt_confirmation_sent ?>

						<p><a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a></p>
						<?php
					}

					if(($invalid_email || !$user_exists) && $form_submitted) {
						?>
						<div class="msg-block">
							<?= $txt_invalid_email ?>

							<p><a href="<?= $baseurl ?>/user/resend-confirmation"><?= $txt_try_again ?></a></p>
						</div>
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