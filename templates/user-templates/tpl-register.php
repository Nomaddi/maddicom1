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
<body class="tpl-user-register">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_html_title ?></strong></h4>
				</div>

				<div class="card-body">
					<?php
					if(!empty($user_exists)) {
						?>
						<div class="msg alert alert-danger">
							<p><strong><?= $txt_email_exists ?></strong></p>

							<p><?= $txt_email_exists_explain ?></p>
						</div>
						<?php
					}

					if($invalid_email) {
						?>
						<div class="msg alert alert-danger">
							<p><strong><?= $txt_invalid_email ?></strong></p>

							<p><?= $txt_invalid_email_explain ?></p>
						</div>
						<?php
					}

					if($empty_fields == 1 && $form_submitted) {
						?>
						<div class="msg alert alert-danger">
							<p><strong><?= $txt_missing_fields ?></strong></p>

							<p><?= $txt_missing_fields_explain ?></p>

						</div>
						<?php
					}

					if($user_created == 1) {
						?>
						<div class="msg">
							<p><strong><?= $txt_acct_created ?></strong></p>

							<p><?= $txt_acct_created_explain ?></p>
						</div>
						<?php
					}

					if($form_submitted && ($empty_fields == 1 || $invalid_email || !empty($user_exists))) {
						?>
						<div class="msg">
							<p><?= $txt_submit_again ?></p>
						</div>
						<?php
					}

					if(empty($form_submitted)) {
						?>
						<form method="post" action="<?= $baseurl ?>/user/register">
							<?php
							$referrer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
							?>
							<input type="hidden" id="referrer" name="referrer" value="<?php echo $referrer ?>">

							<div class="form-group">
								<input type="text" id="fname" class="form-control" name="fname" placeholder="<?= $txt_fname ?>" tabindex="1" >
							</div>

							<div class="form-group">
								<input type="text" id="lname" class="form-control" name="lname" placeholder="<?= $txt_lname ?>" tabindex="1" >
							</div>

							<div class="form-group">
								<input type="email" id="email" class="form-control" name="email" placeholder="<?= $txt_email ?>" tabindex="1" >
							</div>

							<div class="form-group">
								<input type="password" id="password" class="form-control" name="password" autocomplete="false" placeholder="<?= $txt_password ?>" tabindex="1" >

								<input type="checkbox" id="password2" class="form-control" name="password2" value="1">
							</div>

							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-block" value="<?= $txt_register ?>" tabindex="1" >
							</div>

							<div class="form-group">
								<?= $txt_has_account ?> <a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a>
							</div>
						</form>
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