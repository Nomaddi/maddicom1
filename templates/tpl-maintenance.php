<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title>Maintenance Mode</title>
<?php require_once('head.php') ?>
</head>
<body class="tpl-maintenance">

<div class="container mt-5">
	<div class="text-center">
		<h4>Maintenance Mode</h4>

		<p>Please come back in a few minutes</p>

		<?php
		if($is_admin) {
			?>
			<p><a href="<?= $baseurl ?>/admin/home">Admin Area</a></p>
			<?php
		}
		?>
	</div>
</div>

</body>
</html>