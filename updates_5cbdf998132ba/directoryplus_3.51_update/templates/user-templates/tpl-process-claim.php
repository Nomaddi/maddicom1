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
<body class="tpl-user-process-claim">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
		<h1><?= $txt_main_title ?></h1>

		<div class="mb-3">
			<p><?= $txt_confirm_claim ?></p>
			<?= $txt_selected_plan ?>: <strong><?= $plan_name ?></strong><br>
			<?= $txt_plan_price ?>:
			<strong><?= $cfg_cur_symbol_pos == 'left' ? $currency_symbol : '' ?><?= $plan_price ?><?= $cfg_cur_symbol_pos == 'left' ? $currency_symbol : '' ?>
			<?php
			if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
				echo ' / ' . $txt_month;
			}

			if($plan_type == 'annual' || $plan_type == 'annual_feat') {
				echo ' / ' . $txt_year;
			}
			?>
			</strong>
		</div>

		<?php
		// paypal form
		if($paypal_mode != -1) {
			?>
			<h3><?= $txt_pay_paypal ?></h3>

			<div class="mb-3">
				<form action="<?= $paypal_url ?>" method="post" id="<?= $plan_type ?>-<?= $plan_id ?>">
					<input type="hidden" name="cmd"           value="<?= $cmd ?>">
					<input type="hidden" name="notify_url"    value="<?= $claim_notify_url ?>">
					<input type="hidden" name="bn"            value="<?= $bn ?>">
					<input type="hidden" name="business"      value="<?= $paypal_merchant_id ?>">
					<input type="hidden" name="item_name"     value="<?= $plan_name ?> - <?= $site_name ?>">
					<input type="hidden" name="currency_code" value="<?= $currency_code ?>">
					<input type="hidden" name="custom"        value="<?= $place_id ?>,<?= $plan_id ?>,<?= $userid ?>">
					<input type="hidden" name="image_url"     value="<?= $paypal_checkout_logo_url ?>">
					<input type="hidden" name="lc"            value="<?= $paypal_locale ?>">
					<input type="hidden" name="return"        value="<?= $baseurl ?>/user/thanks">
					<input type="hidden" name="charset"       value="utf-8">

					<!-- amounts -->
					<?php
					if($plan_type == 'monthly' || $plan_type == 'monthly_feat' || $plan_type == 'annual' || $plan_type == 'annual_feat') {
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
					?>

					<!-- the submit button -->
					<input type="submit" id="submit" name="submit" value="<?= $txt_pay_paypal ?>" class="btn btn-primary">
					<div class="form-row">
						<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg">
					</div>
				</form>
			</div>
		<?php
		} // end paypal

		// stripe form
		if($stripe_mode != -1) {
			?>
			<div class="py-3">
				<a href="<?= $checkout_session_url ?>" class="btn btn-primary"><?= $txt_checkout ?></a>
			</div>
			<?php
		}
		?>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>