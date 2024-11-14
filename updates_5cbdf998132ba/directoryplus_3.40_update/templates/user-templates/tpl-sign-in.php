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
<body class="tpl-user-sign-in">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_main_title ?></strong></h4>
				</div>

				<div class="card-body">
					<?php
					if($wrong_pass) {
						?>
						<div class="alert alert-danger" role="alert"><?= $txt_wrong_pass ?></div>
						<?php
					}

					if($email_already_used) {
						?>
						<div class="alert alert-danger" role="alert"><?= $txt_email_used ?></div>
						<?php
					}

					if(!$user_registered) {
						?>
						<div class="alert alert-danger" role="alert"><?= $txt_not_registered ?></div>
						<?php
					}

					if($account_pending) {
						?>
						<div class="alert alert-light" role="alert"> <?= $txt_pending_account ?>
							<a href="<?= $baseurl ?>/user/resend-confirmation"> <?= $txt_resend_confirmation ?></a>
						</div>
						<?php
					}

					if($show_form) {
						?>
						<form method="post" action="<?= $baseurl ?>/user/sign-in">
							<input type="hidden" id="referrer" name="referrer" value="<?= $referrer ?>">

							<div class="form-group">
								<input id="email" class="form-control" name="email" type="text" tabindex="1" placeholder="<?= $txt_email ?>">
							</div>

							<div class="form-group">
								<input id="password" class="form-control" name="password" type="password" placeholder="<?= $txt_password ?>" tabindex="1" >
							</div>

							<div class="form-group">
								<button id="submit" class="btn btn-primary btn-block" name="submit" tabindex="1" ><?= $txt_signin ?></button>
								<a href="<?= $baseurl ?>/user/forgot-password"><?= $txt_forgot_pass ?></a>
							</div>
						</form>
						<?php
					}
					?>

					<?php
					if(!empty($facebook_key) || !empty($twitter_key)) {
						?>
						<hr>

						<div class="row">
							<?php
							if(!empty($facebook_key) && !empty($twitter_key)) {
								?>
								<div class="col-md-6 mb-3 mb-md-0">
									<a href="<?= $baseurl ?>/user/sign-in?provider=facebook" class="btn btn-secondary btn-block"><i class="fab fa-facebook-f"></i> Facebook</a>
								</div>

								<div class="col-md-6 mb-3 mb-md-0">
									<a href="<?= $baseurl ?>/user/sign-in?provider=twitter" class="btn btn-secondary btn-block"><i class="fab fa-twitter"></i> Twitter</a>
								</div>
								<?php
							}

							else {
								if(!empty($facebook_key)) {
									?>
									<div class="col-md-12 mb-3 mb-md-0">
										<a href="<?= $baseurl ?>/user/sign-in?provider=facebook" class="btn btn-secondary btn-block"><i class="fab fa-facebook-f"></i> Facebook</a>
									</div>
									<?php
								}

								if(!empty($twitter_key)) {
									?>
									<div class="col-md-12 mb-3 mb-md-0">
										<a href="<?= $baseurl ?>/user/sign-in?provider=twitter" class="btn btn-secondary btn-block"><i class="fab fa-twitter"></i> Twitter</a>
									</div>
									<?php
								}
							}
							?>
						</div>
						<?php
					}
					?>
				</div>

				<div class="card-footer text-muted">
					<?= $txt_new_to_site ?> <a href="<?= $baseurl ?>/user/register"><?= $txt_register ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>