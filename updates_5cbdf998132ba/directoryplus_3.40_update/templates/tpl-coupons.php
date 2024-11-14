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
	<h2 class=""><?= $txt_html_title ?></h2>
</div>

<div class="container mt-5">
	<?php
	if(!empty($coupons_arr)) {
		foreach($coupons_arr as $k => $v) {
			?>
			<div class="row mb-3" id="coupon-<?= $v['coupon_id'] ?>">
				<div class="col-md-3 mb-3 coupon-img" id="<?= $v['coupon_id'] ?>">
					<a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>"><img src="<?= $v['coupon_img'] ?>"></a>
				</div>

				<div class="col-md-9 mb-3 coupon-body">
					<div class="item-title-row mb-3">
						<h3><a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>"><?= e($v['coupon_title']) ?></a></h3>
					</div>

					<div class="mb-3"><?= e($v['coupon_description']) ?></div>

					<div class="mb-3">
						<a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>" class="btn btn-dark"><strong><?= $txt_view_details ?></strong></a>
					</div>

					<div>
						<!-- tweet button -->
						<a href="<?= $v['twitter_link'] ?>"
						class="btn btn-light">
						<i class="lab la-twitter" aria-hidden="true"></i> <?= $txt_tweet ?></a>

						<!-- facebook share -->
						<a href="#"
						class="btn btn-light"
						onclick="window.open('<?= $v['facebook_link'] ?>','facebook-share-dialog', 'width=626,height=436'); return false;">
						<i class="lab la-facebook" aria-hidden="true"></i> <?= $txt_share ?></a>

						<!-- mail -->
						<a href="<?= $v['mailto_link'] ?>"
						class="btn btn-light">
						<i class="las la-envelope" aria-hidden="true"></i> <?= $txt_mail ?></a>

						<!-- print -->
						<a href="<?= $v['coupon_img'] ?>" target="_blank"
						class="btn btn-light">
						<i class="las la-print" aria-hidden="true"></i> <?= $txt_print ?></a>
					</div>
				</div>
			</div>

			<hr class="mb-3">
			<?php
		}
		?>
		<nav>
			<ul class="pagination flex-wrap">
				<?php
				if($total_rows > 0) {
					include_once(__DIR__ . '/../inc/pagination.php');
				}
				?>
			</ul>
		</nav>
		<?php
	}

	else {
		?>
		<div class="mt-5">
			<?= $txt_no_results ?>
		</div>
		<?php
	}
	?>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>