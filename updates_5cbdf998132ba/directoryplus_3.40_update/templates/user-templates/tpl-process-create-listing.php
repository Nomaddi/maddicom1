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
<body class="tpl-user-process-create-listing">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php require_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<?php
			if($plan_type != 'free' && $plan_type != 'free_feat' && !$is_admin) {
				?>
				<h1><?= $txt_main_title ?></h1>

				<div class="checkout-box-wrapper clearfix">
					<div class="checkout-box">
						<p><?= $result_message ?></p>

						<?php
						if(!$has_errors) {
							?>
							<h2><?= $txt_order_details ?></h2>

							<p><?= $txt_selected_plan ?>: <strong><?= $plan_name ?></strong><br>
							<?= $txt_plan_value ?>:

							<strong><?= $cfg_cur_symbol_pos == 'left' ? $currency_symbol : '' ?><?= $plan_price ?><?= $cfg_cur_symbol_pos == 'right' ? $currency_symbol : '' ?>
							<?php
							if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
								echo ' / ' . $txt_month;
							}

							if($plan_type == 'annual' || $plan_type == 'annual_feat') {
								echo ' / ' . $txt_year;
							}
							?>
							</strong>

							<?php
							if($paypal_mode != -1) {
								?>
								<div class="py-3">
									<form action="<?= $paypal_url ?>" method="post" id="<?= $plan_type ?>-<?= $plan_id ?>">
										<input type="hidden" name="cmd"           value="<?= $cmd ?>">
										<input type="hidden" name="notify_url"    value="<?= $notify_url ?>">
										<input type="hidden" name="bn"            value="<?= $bn ?>">
										<input type="hidden" name="business"      value="<?= $paypal_merchant_id ?>">
										<input type="hidden" name="item_name"     value="<?= $plan_name ?> - <?= $site_name ?>">
										<input type="hidden" name="currency_code" value="<?= $currency_code ?>">
										<input type="hidden" name="custom"        value="<?= $place_id ?>">
										<input type="hidden" name="image_url"     value="<?= $paypal_checkout_logo_url ?>">
										<input type="hidden" name="lc"            value="<?= $paypal_locale ?>">
										<input type="hidden" name="return"        value="<?= $baseurl ?>/user/thanks">
										<input type="hidden" name="charset"       value="utf-8">

										<!-- amounts -->
										<?php
										if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
											?>
											<input type="hidden" name="a3"  value="<?= $a3 ?>">
											<input type="hidden" name="p3"  value="<?= $p3 ?>">
											<input type="hidden" name="t3"  value="<?= $t3 ?>">
											<input type="hidden" name="src" value="1">
											<?php
										}

										if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
											?>
											<input type="hidden" name="amount" value="<?= $amount ?>">
											<?php
										}

										if($plan_type == 'annual' || $plan_type == 'annual_feat') {
											?>
											<input type="hidden" name="a3"  value="<?= $a3 ?>">
											<input type="hidden" name="p3"  value="<?= $p3 ?>">
											<input type="hidden" name="t3"  value="<?= $t3 ?>">
											<input type="hidden" name="src" value="1">
											<?php
										}
										?>

										<!-- the submit button -->
										<input type="submit" id="submit" name="submit" value="<?= $txt_pay_paypal ?>" class="btn btn-primary">
										<div class="form-row">
											<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" width="120" />
										</div>
									</form>
								</div>
							<?php
							} // end paypal

							// stripe
							if($stripe_mode != -1) {
								?>
								<div class="py-3">
									<form action="<?= $baseurl ?>/msg" method="POST">
										<input type="hidden" name="plan_type" value="<?= $plan_type ?>">
										<input type="hidden" name="plan_id" value="<?= $plan_id ?>">
										<input type="hidden" name="place_id" value="<?= $place_id ?>">
										<input type="hidden" name="ref" value="stripe">
										<script
											src="https://checkout.stripe.com/checkout.js" class="stripe-button"
											data-key         = "<?= $stripe_key ?>"
											data-amount      = "<?= $stripe_amount ?>"
											data-currency    = "<?= $stripe_data_currency ?>"
											data-name        = "<?= $plan_name ?>"
											data-description = "<?= $stripe_data_description ?>"
											data-image       = "<?= $stripe_data_image ?>"
											data-locale      = "auto">
										</script>
									</form>
								</div>
								<?php
							}
						}
					?>
					</div>
				</div>
			<?php
			}

			else {
				?>
				<h1 class="mb-5"><?= $txt_main_title_free ?></h1>

				<p><?= $thanks ?><!-- <?= $result_message ?> --></p>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>