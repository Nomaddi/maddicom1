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
		<div class="col-md-3 mb-3">
			<img src="<?= $coupon_img_url ?>" width="240" class="rounded">
		</div>

		<div class="col-md-9 mb-3">
			<h2 class="mb-1"><?= $coupon_title ?></h2>
			<p class="mb-5"><?= $txt_created_by ?>: <a href="<?= $place_link ?>" class="text-primary"><?= $place_name ?></a></p>

			<div class="mb-5"><?= nl2p($coupon_description) ?></div>

			<!-- print -->
			<?php
			if($coupon_valid == 'valid') {
				?>
				<a href="<?= $coupon_img_url ?>" target="_blank"
				class="btn btn-primary">
					<i class="las la-print" aria-hidden="true"></i> <?= $txt_print ?>
				</a>

				<span class="btn btn-light">
					<i class="lar la-clock" aria-hidden="true"></i> <?= $txt_expires ?>: <?= $coupon_expire ?>
				</span>
				<?php
			}

			else {
				?>
				<span class="btn btn-light"><?= $txt_expired ?></span>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>