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
<body class="tpl-user-password-reset">
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
					if(!$form_submitted && $valid_token) {
						?>
						<form method="post" action="<?= $baseurl ?>/user/password-reset">
							<input id="user_id" name="user_id" type="hidden" value="<?= $user_id ?>" />
							<input id="token" name="token" type="hidden" value="<?= $token ?>" />

							<div class="form-group">
								<label for="email"><?= $txt_enter_new_pass ?></label>
								<input type="password" id="new_pass" class="form-control" name="new_pass">
							</div>

							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-block" name="submit">
							</div>
						</form>

						<?= $txt_or_login ?>  <a href="sign-in"><?= $txt_signin ?></a></p>
						<?php
					}

					if(!$form_submitted && !$valid_token) {
						?>
						<div class="msg-block">
							<?= $txt_invalid_token ?>

							<p><a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a></p>
						</div>
						<?php
					}

					if($form_submitted && !$valid_token) {
						?>
						<div class="msg-block">
							<?= $txt_invalid_token ?>

							<p><a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a></p>
						</div>
						<?php
					}

					if($form_submitted && $update_success) {
						?>
						<div class="msg-block">
							<?= $txt_update_success ?>

							<p><a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a></p>
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