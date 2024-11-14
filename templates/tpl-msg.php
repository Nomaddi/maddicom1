<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_main_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once('header.php') ?>

<div class="container mt-5">
	<h2 class=""><?= $txt_main_title ?></h2>
</div>

<div class="container mt-5">
	<?= $txt_msg ?>
</div>

<?php require_once('footer.php') ?>

</body>
</html>