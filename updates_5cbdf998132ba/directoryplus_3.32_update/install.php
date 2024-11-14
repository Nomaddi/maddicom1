<?php
if(file_exists('db-updater.php')) {
	unlink('db-updater.php');
}

require_once(__DIR__ . '/inc/config.php');
require_once(__DIR__ . '/inc/functions.php');

// redirect to $baseurl version, need to create dummy get variable to prevent infinite loop
if(!isset($_GET['cors'])) {
	header("Location: $baseurl/install.php?cors=1");
}
?>
<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html lang="en" >
<head>
<title>Install DirectoryPlus</title>

<!-- meta -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- favicon -->
<link rel="apple-touch-icon-precomposed" href="<?= $baseurl; ?>/assets/favicon/favicon-152.png">
<meta name="msapplication-TileColor" content="#f0f0f0">
<meta name="msapplication-TileImage" content="<?= $baseurl; ?>/assets/favicon/favicon-144.png">
<link rel="icon" type="image/png" href="<?= $baseurl; ?>/assets/favicon/favicon.png">

<!-- CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

<!-- Javascript -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

</head>
<body class="bg-light">
<div class="container">
	<div class="py-5 text-center">
		<img src="<?= $baseurl ?>/assets/imgs/logo.png" width="180">
	</div>

	<div class="row">
		<div class="col-md-12 order-md-1">
			<div class="row">
				<div class="col-md-12 mb-3">
					<p class="h4">Install DirectoryPlus</p>
				</div>
			</div>

			<hr>

			<div class="install-form">
				<form method="post" action="<?= $baseurl ?>/install.php" class="install">
					<div class="mb-3">
						<p>To install first enter an email and password</p>
					</div>

					<div class="mb-3">
						<p class="h6">Admin email</p>
						<input type="email" id="email" name="email" class="form-control" placeholder="you@example.com">
					</div>

					<div class="mb-3">
						<p class="h6">Admin password</p>
						<input type="password" id="password" name="password" spellcheck="false" class="form-control">
					</div>

					<hr class="mb-4">
					<button id="submit" name="submit" class="btn btn-primary btn-lg btn-block">Install</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
    $('#submit').click(function(e){
		e.preventDefault();

		// show spinner
		$('#submit').empty();
		$('#submit').html('<i class="fas fa-spinner fa-spin"></i> Installing...');

		// request install
		var post_url = '<?= $baseurl ?>' + '/process-install.php';

		$.post(post_url, {
			params: $('form.install').serialize(),
			},
			function(data) {
				$('.install-form').empty();
				$('.install-form').html(data).fadeIn();
				console.log(data);
			}
		);
    });
});
</script>
</body>
</html>
