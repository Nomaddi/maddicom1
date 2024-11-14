<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once('header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_contact ?></strong></h5>
				</div>

				<div class="card-body">
					<div id="contact-result"></div>
					<form id="contact-form">
						<div class="form-group">
							<label for="name"><?= $txt_name ?></label>
							<input id="name" name="name" type="text" class="form-control">
						</div>

						<div class="form-group">
							<label for="email"><?= $txt_email ?></label>
							<input type="email" id="email" class="form-control" name="email" required>
						</div>

						<div class="form-group">
							<label for="subject"><?= $txt_subject ?></label>
							<input type="text" id="subject" class="form-control" name="subject" required>
						</div>

						<div class="form-group">
							<label for="message"><?= $txt_message ?></label>
							<textarea id="message" class="form-control" name="message" rows="10" required></textarea>
						</div>

						<button id="submit-contact" class="btn btn-primary btn-block mb-2"><?= $txt_send_message ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>