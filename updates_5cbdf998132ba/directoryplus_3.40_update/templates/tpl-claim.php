<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>

<!-- Page CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once('header.php') ?>

<div class="container mt-5">
	<h2 class="mb-5 text-center"><?= $txt_html_title ?></h2>

	<div class="d-sm-flex list-item mb-5 mx-3 mx-sm-0 featured">
		<a href="">
			<div class="thumb rounded" style="background-image: url('<?= $photo_url ?>');">
				<span class="cat-name-figure rounded p-2"><?= $cat_name ?></span>
			</div>
		</a>

		<div class="flex-grow-1 p-3 pl-4">
			<div class="d-flex mb-3">
				<div class="flex-grow-1">
					<h4 class="mb-2"><a href=""><?= $place_name ?></a></h4>

					<div class="item-rating" data-rating="<?= $rating ?>">
						<!-- raty plugin placeholder -->
					</div>
				</div>
			</div>

			<div class="card-text mb-2">
				<?= $short_desc ?>
			</div>

			<hr>

			<div class="address">
				<?= !empty($address) ? $address : '' ?>
				<?= !empty($city_name) ? " - " . $city_name . ", " : '' ?>
				<?= !empty($state_abbr) ? $state_abbr : '' ?>
				<?= !empty($postal_code) ? $postal_code : '' ?></strong>
			</div>

			<?php
			if(!empty($v['area_code']) && !empty($v['phone'])) {
				?>
				<div class="tel">
					<a href="tel:<?= $area_code ?>-<?= $phone ?>"><strong><i class="las la-mobile-alt"></i> <?= $area_code ?>-<?= $phone ?></strong></a>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<?php
	// only allow claiming if place_userid == 1
	if($place_userid != 1) {
		?>
		<div class="alert alert-danger" role="alert"><?= $txt_claimed ?></div>
		<?php
	}

	else {
		// redirect if not logged in
		if(empty($userid)) {
			?>
			<div class="alert alert-danger" role="alert"><?= $txt_sign_in ?></div>
			<?php
		}

		else {
			?>
			<h2 class="mb-5 text-center"><?= $txt_select_plan ?></h2>

			<div class="card-deck flex-column flex-md-row mb-5">
				<?php
				if(!empty($plans_arr)) {
					foreach($plans_arr as $k => $v) {
						?>
						<div class="card text-center px-3 mb-4">
							<span class="w-60 mx-auto mb-4 px-4 py-1 rounded-bottom bg-primary text-white shadow-sm"><?= $v['plan_name'] ?></span>

							<div class="bg-transparent mb-4 border-0">
								<h1 class="font-weight-normal text-dark text-center mb-0">
									<?= $cfg_cur_symbol_pos == 'left' ? $currency_symbol : '' ?><span class="price"><?= $v['plan_price'] ?></span><?= $cfg_cur_symbol_pos == 'right' ? $currency_symbol : '' ?>
									<span class="h6 text-muted">
										<?php
										if($v['plan_type'] == 'monthly' || $v['plan_type'] == 'monthly_feat') {
											?>
											/ <?= $txt_month ?>
											<?php
										}

										if($v['plan_type'] == 'annual' || $v['plan_type'] == 'annual_feat') {
											?>
											/ <?= $txt_year ?>
											<?php
										}
										?>
									</span>
								</h1>
							</div>

							<div class="card-body p-0">
								<ul class="list-unstyled mb-4">
									<?php
									foreach($v['plan_feat'] as $v2) {
										?>
										<li><?= $v2 ?></li>
										<?php
									}
									?>
								</ul>
							</div>

							<div class="card-footer px-0">
								<?php
								if($v['plan_type'] == 'free' || $v['plan_type'] == 'free') {
									?>
									<a href="<?= $baseurl ?>/contact" class="btn btn-secondary btn-block"><?= $txt_contact ?></a>
									<?php
								}

								else {
									?>
									<a href="<?= $baseurl ?>/user/process-claim?id=<?= $place_id ?>&plan=<?= $v['plan_id'] ?>" class="btn btn-outline-primary btn-block"><?= $txt_buy_now ?></a>
									<?php
								}
								?>
							</div>
						</div>
						<?php
					}
				}

				else {
					?>
					<div class="card">
						<?= $txt_no_plans ?>
					</div>
					<?php
				}
				?>
			</div>
		<?php
		}
	}
	?>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>