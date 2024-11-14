<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>
</head>
<body class="tpl-user-sign-out">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<h1><?= $txt_main_title ?></h1>

	<p><?= $txt_message ?></p>

	<script>
		window.location.href = "<?= $baseurl ?>";
	</script>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>