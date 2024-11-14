<?php
include_once(__DIR__ . '/../inc/config.php');
?>
<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title>Page Not Found</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- favicon -->
<link rel="apple-touch-icon-precomposed" href="<?= $baseurl ?>/assets/favicon/favicon-152.png">
<link rel="icon" type="image/png" href="<?= $baseurl ?>/assets/favicon/favicon.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?= $baseurl ?>/assets/favicon/favicon-144.png">

<!-- css -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/styles.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">

<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<!-- Page CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">
</head>
<body class="tpl-404">

<div class="vw-95 vh-100 d-flex align-items-center not-found">
	<div class="text-center w-100 pb-5">
		<h3>Oops! Page not found</h3>
		<h3>404</h3>
		<p class="text-dark">The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
		<form action="<?= $baseurl ?>" method="post"><button class="btn btn-outline-dark">Go to the homepage</button></form>
	</div>
</div>

<!-- footer -->
<?php
include_once(__DIR__ . '/footer.php');
?>
</body>
</html>