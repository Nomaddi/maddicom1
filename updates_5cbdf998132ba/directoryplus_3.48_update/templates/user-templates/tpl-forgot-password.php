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
<body class="tpl-user-forgot-password">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_forgot_pass ?></strong></h4>
				</div>

				<div class="card-body">
					<div id="result-spinner">
						<i class="las la-circle-notch la-spin"></i><span class="sr-only"><?= $txt_loading ?></span>
					</div>

					<div id="success-result">
						<?= $txt_request_sent ?>

						<p><a href="<?= $baseurl ?>/user/sign-in" class="text-primary"><?= $txt_signin ?></a></p>
					</div>

					<div id="invalid-email-result">
						<?= $txt_invalid_email ?>

						<p><a href="<?= $baseurl ?>/user/forgot-password" class="text-primary"><?= $txt_try_again ?></a></p>
					</div>

					<div id="smtp-error-result">
						<div class="alert alert-danger" role="alert">
							<?= $txt_mailer_problem ?>

							<p><a href="<?= $baseurl ?>/user/forgot-password" class="text-primary"><?= $txt_try_again ?></a></p>
						</div>
					</div>

					<form id="forgot-password-form">
						<div class="form-group">
							<label for="email"><?= $txt_email ?></label>
							<input type="text" id="email" class="form-control" name="email" aria-describedby="emailHelp"required>
							<small id="emailHelp" class="form-text text-muted"><?= $txt_enter_email ?></small>
						</div>

						<div class="form-group">
							<button id="forgot-password-submit" class="btn btn-primary btn-block"><?= $txt_submit ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>