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
<body class="tpl-user-select-plan">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<h1 class="text-center mb-5"><?= $txt_main_title ?></h1>

	<?php
	/*
	if(count($plans_arr) == 1) {
		?>
		<div class="row">
			<div class="col-sm"></div>

			<div class="col-sm">
		<?php
	}

	if(count($plans_arr) == 2) {
		?>
		<div class="row">
			<div class="col-md-2"></div>

			<div class="col-md-8">
		<?php
	}
	*/
	?>

	<div class="row justify-content-center">
		<?php
		if(!empty($plans_arr)) {
			$i = 1;
			foreach($plans_arr as $k => $v) {
				?>
				<div class="col-md-4 col-sm-6 d-flex align-items-stretch">
					<div class="card text-center px-3 mb-4 w-100">
						<span class="w-60 mx-auto mb-4 px-4 py-1 rounded-bottom bg-primary text-white shadow-sm"><?= $v['plan_name'] ?></span>

						<div class="bg-transparent mb-4 border-0">
							<h1 class="font-weight-normal text-dark text-center mb-0">
								<?= $cfg_cur_symbol_pos == 'left' ? $currency_symbol : '' ?><span class="price"><?= $v['plan_price'] ?><?= $cfg_cur_symbol_pos == 'right' ? $currency_symbol : '' ?></span>
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
							<a href="<?= $baseurl ?>/user/create-listing/<?= $v['plan_id'] ?>" class="btn btn-outline-primary btn-block">
							<?= $txt_buy_now ?></a>
						</div>
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
	/*
	if(count($plans_arr) == 1) {
		?>
			</div>

			<div class="col-sm"></div>
		</div>
		<?php
	}

	if(count($plans_arr) == 2) {
		?>
			</div>

			<div class="col-md-2"></div>
		</div>
		<?php
	}
	*/
	?>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>