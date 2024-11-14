<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once(__DIR__ . '/admin-head.php') ?>
</head>
<body class="tpl-admin-<?= $route[1] ?>">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('admin-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>
			<!-- Tabs -->
			<div class="mb-3">
				<ul id="settings-tab" class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a href="#general-panel" id="general-tab" class="nav-link" data-toggle="tab"><?= $txt_tab_general ?></a>
					</li>

					<li class="nav-item">
						<a href="#maps-panel" id="email-tab" class="nav-link" data-toggle="tab"><?= $txt_maps ?></a>
					</li>

					<li class="nav-item">
						<a href="#email-panel" id="email-tab" class="nav-link" data-toggle="tab"><?= $txt_tab_email ?></a>
					</li>

					<li class="nav-item">
						<a href="#apis-panel" id="apis-tab" class="nav-link" data-toggle="tab"><?= $txt_tab_apis ?></a>
					</li>

					<li class="nav-item dropdown">
						<a href="#" id="payment-dropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $txt_tab_payment ?></a>
						<div class="dropdown-menu">
							<a href="#paypal-panel" class="dropdown-item" data-toggle="tab">Paypal</a>
							<a href="#stripe-panel" class="dropdown-item" data-toggle="tab">Stripe</a>
							<!--<a href="#mercadopago-panel" class="dropdown-item" data-toggle="tab">MercadoPago</a>-->
						</div>
					</li>
				</ul>
			</div>

			<!-- Tab contents -->
			<form method="post" action="<?= $baseurl ?>/admin/process-settings">
				<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

				<div class="tab-content" id="tab-content">

					<!-- General pane -->
					<div id="general-panel" class="tab-pane fade show active" role="tabpanel">
						<div class="form-group">
							<?= $txt_site_name ?>
							<input type="text" id="site_name" name="site_name" class="form-control" value="<?= $site_name ?>">
						</div>

						<div class="form-group">
							<?= $txt_html_lang ?><br>
							<?= $txt_html_lang_explain ?>

							<select id="html_lang" name="html_lang" class="form-control">
								<?php
								foreach($iso_639_1 as $v) {
									$selected = '';
									if($v == $html_lang) $selected = 'selected'
									?>
									<option value="<?= $v ?>" <?= $selected ?>><?= $v ?></option>
									<?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_items_per_page ?><br>
							<?= $txt_items_per_page_explain ?>
							<input type="number" id="items_per_page" name="items_per_page" class="form-control" value="<?= $items_per_page ?>">
						</div>

						<div class="form-group">
							<?= $txt_permalink_struct ?><br>
							<?= $txt_permalink_struct_explain ?>
							<input type="text" id="cfg_permalink_struct" name="cfg_permalink_struct" class="form-control" value="<?= $cfg_permalink_struct == 'listing' ? '' : $cfg_permalink_struct ?>">
						</div>

						<div class="form-group">
							<?= $txt_max_pics ?><br>
							<?= $txt_max_pics_explain ?>
							<input type="number" id="max_pics" name="max_pics" class="form-control" value="<?= $max_pics ?>">
						</div>

						<div class="form-group">
							<?= $txt_mail_after_post ?><br>
							<select id="mail_after_post" name="mail_after_post" class="form-control">
								<option value="0" <?php if ($mail_after_post == 0) echo 'selected' ?>><?= $txt_no ?></option>
								<option value="1" <?php if ($mail_after_post == 1) echo 'selected' ?>><?= $txt_yes ?></option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_maintenance_mode ?><br>
							<select id="maintenance_mode" name="maintenance_mode" class="form-control">
								<option value="0" <?php if ($maintenance_mode == 0) echo 'selected' ?>><?= $txt_no ?></option>
								<option value="1" <?php if ($maintenance_mode == 1) echo 'selected' ?>><?= $txt_yes ?></option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_timezone ?><br>
							<?= $txt_timezone_explain ?>
							<select id="timezone" name="timezone" class="form-control">
								<?php
								foreach($timezone_identifiers as $v) {
									$selected = '';
									if($v == $timezone) $selected = 'selected'
									?>
									<option value="<?= $v ?>" <?= $selected ?>><?= $v ?></option>
									<?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_languages ?><br>
							<input type="text" id="cfg_languages" name="cfg_languages" class="form-control" value="<?= implode(',',$cfg_languages) ?>">
						</div>

						<div class="form-group">
							<?= $txt_date_format ?> (<a href="http://php.net/manual/en/datetime.formats.date.php"><?= $txt_example ?></a>)<br>
							<input type="text" id="cfg_date_format" name="cfg_date_format" class="form-control" value="<?= $cfg_date_format ?>">
						</div>

						<div class="form-group">
							<?= $txt_latest_listings_count ?><br>
							<input type="number" id="cfg_latest_listings_count" name="cfg_latest_listings_count" class="form-control" value="<?= $cfg_latest_listings_count ?>">
						</div>

						<div class="form-group">
							<?= $txt_new_sign_up_notification ?><br>
							<select id="user_created_notify" name="user_created_notify" class="form-control">
								<option value="0" <?php if ($user_created_notify == 0) echo 'selected' ?>><?= $txt_no ?></option>
								<option value="1" <?php if ($user_created_notify == 1) echo 'selected' ?>><?= $txt_yes ?></option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_logo_width ?><br>
							<input type="number" id="site_logo_width" name="site_logo_width" class="form-control" value="<?= $site_logo_width ?>">
						</div>

						<div class="form-group">
							<?= $txt_decimal_separator ?><br>
							<input type="text" id="cfg_decimal_separator" name="cfg_decimal_separator" class="form-control" value="<?= $cfg_decimal_separator ?>">
						</div>

						<div class="form-group">
							<?= $txt_use_select2 ?><br>
							<select id="cfg_use_select2" name="cfg_use_select2" class="form-control">
								<option value="0" <?php if ($cfg_use_select2 == 0) echo 'selected' ?>><?= $txt_disabled ?></option>
								<option value="1" <?php if ($cfg_use_select2 == 1) echo 'selected' ?>><?= $txt_enabled ?></option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_reviews ?><br>
							<select id="cfg_enable_reviews" name="cfg_enable_reviews" class="form-control">
								<option value="0" <?php if ($cfg_enable_reviews == 0) echo 'selected' ?>><?= $txt_disabled ?></option>
								<option value="1" <?php if ($cfg_enable_reviews == 1) echo 'selected' ?>><?= $txt_enabled ?></option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_coupons ?><br>
							<select id="cfg_enable_coupons" name="cfg_enable_coupons" class="form-control">
								<option value="0" <?php if ($cfg_enable_coupons == 0) echo 'selected' ?>><?= $txt_disabled ?></option>
								<option value="1" <?php if ($cfg_enable_coupons == 1) echo 'selected' ?>><?= $txt_enabled ?></option>
							</select>
						</div>
					</div>

					<!-- Maps pane -->
					<div id="maps-panel" class="tab-pane fade" role="tabpanel">
						<div class="form-group">
							<?= $txt_map_provider ?><br>
							<input type="checkbox" name="map_provider[]" <?= $mapbox_checked ?> value="MapBox"> MapBox<br>
							<input type="checkbox" name="map_provider[]" <?= $wikimedia_checked ?> value="Wikimedia"> Wikimedia<br>
							<input type="checkbox" name="map_provider[]" <?= $osm_checked ?> value="OpenStreetMap"> OpenStreetMap<br>
							<input type="checkbox" name="map_provider[]" <?= $tomtom_checked ?> value="Tomtom"> Tomtom<br>
							<input type="checkbox" name="map_provider[]" <?= $here_checked ?> value="HERE"> HERE<br>
							<input type="checkbox" name="map_provider[]" <?= $google_checked ?> value="Google"> Google<br>
							<input type="checkbox" name="map_provider[]" <?= $cartov_checked ?> value="CartoDB.Voyager"> CartoDB.Voyager<br>
							<input type="checkbox" name="map_provider[]" <?= $cartop_checked ?> value="CartoDB.Positron"> CartoDB.Positron<br>
							<input type="checkbox" name="map_provider[]" <?= $stamen_checked ?> value="Stamen.Terrain"> Stamen.Terrain<br>
						</div>

						<div class="form-group">
							<?= $txt_default_lat ?><br>
							<input type="text" id="default_lat" name="default_lat" class="form-control" value="<?= $default_lat ?>">
						</div>

						<div class="form-group">
							<?= $txt_default_lng ?><br>
							<input type="text" id="default_lng" name="default_lng" class="form-control" value="<?= $default_lng ?>">
						</div>

						<div class="form-group">
							<strong><?= $txt_nearby_filter_values ?></strong><br>
							<input type="text" id="cgf_max_dist_values" name="cgf_max_dist_values" class="form-control" value="<?= $cgf_max_dist_values ?>">
						</div>

						<div class="form-group">
							<strong><?= $txt_distance_unit ?></strong><br>
							<select id="cgf_max_dist_unit" name="cgf_max_dist_unit" class="form-control">
								<option value="km" <?= $cgf_max_dist_unit == 'km' ? 'selected' : '' ?>>km</option>
								<option value="miles" <?= $cgf_max_dist_unit == 'miles' ? 'selected' : '' ?>>miles</option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_near_listings_radius ?><br>
							<input type="number" id="cgf_near_listings_radius" name="cgf_near_listings_radius" class="form-control" value="<?= $cgf_near_listings_radius ?>">
						</div>

						<div class="form-group">
							<?= $txt_country_name ?><br>
							<input type="text" id="country_name" name="country_name" class="form-control" value="<?= $country_name ?>">
						</div>

						<div class="form-group">
							<?= $txt_country_code ?><br>
							<?= $txt_country_code_explain ?>

							<select id="default_country_code" name="default_country_code" class="form-control">
								<?php
								foreach($iso_3166_1 as $v) {
									$selected = '';
									if($v == $default_country_code) $selected = 'selected'
									?>
									<option value="<?= $v ?>" <?= $selected ?>><?= $v ?></option>
									<?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_default_city_id ?><br>
							<?= $txt_default_city_id_explain ?>
							<input type="number" id="default_loc_id" name="default_loc_id" class="form-control" value="<?= $default_loc_id ?>">
						</div>

						<div class="form-group">
							<?= $txt_default_city_slug ?><br>
							<?= $txt_default_city_slug_explain ?>
							<input type="text" id="default_city_slug" name="default_city_slug" class="form-control" value="<?= $default_city_slug ?>">
						</div>
					</div>

					<!-- Email pane -->
					<div id="email-panel" class="tab-pane fade" role="tabpanel">
						<div class="form-group">
							<?= $txt_from_email ?>
							<input type="text" id="admin_email" name="admin_email" class="form-control" value="<?= $admin_email ?>">
						</div>

						<div class="form-group">
							<?= $txt_dev_email ?>
							<input type="text" id="dev_email" name="dev_email" class="form-control" value="<?= $dev_email ?>">
						</div>

						<div class="form-group">
							<?= $txt_smtp_server ?>
							<input type="text" id="smtp_server" name="smtp_server" class="form-control" value="<?= $smtp_server ?>">
						</div>

						<div class="form-group">
							<?= $txt_smtp_user ?>
							<input type="text" id="smtp_user" name="smtp_user" class="form-control" value="<?= $smtp_user ?>">
						</div>

						<div class="form-group">
							<?= $txt_smtp_pass ?>
							<input type="<?= $input_password ?>" id="smtp_pass" name="smtp_pass" class="form-control" value="<?= $smtp_pass ?>">
						</div>

						<div class="form-group">
							<?= $txt_smtp_port ?>
							<input type="text" id="smtp_port" name="smtp_port" class="form-control" value="<?= $smtp_port ?>">
						</div>

						<div class="form-group">
							SMTP encryption
							<select name="cfg_smtp_encryption" class="form-control">
								<option value="tls" <?= $cfg_smtp_encryption == 'tls' ? 'selected' : '' ?>>TLS</option>
								<option value="ssl" <?= $cfg_smtp_encryption == 'ssl' ? 'selected' : '' ?>>SSL</option>
								<option value="" <?= $cfg_smtp_encryption == '' ? 'selected' : '' ?>>none</option>
							</select>
						</div>

						<div class="form-group">
							<?= $txt_contact_business_subject ?>
							<input type="text" name="cfg_contact_business_subject" class="form-control" value="<?= $cfg_contact_business_subject ?>">
						</div>

						<div class="form-group">
							<?= $txt_contact_user_subject ?>
							<input type="text" name="cfg_contact_user_subject" class="form-control" value="<?= $cfg_contact_user_subject ?>">
						</div>
					</div>

					<!-- Apis pane -->
					<div id="apis-panel" class="tab-pane fade" role="tabpanel">
						<div class="form-group">
							<?= $txt_gmaps_key ?><br>
							<?= $txt_gmaps_key_explain ?>
							<input type="<?= $input_password ?>" id="google_key" name="google_key" class="form-control" value="<?= $google_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_facebook_key ?><br>
							<?= $txt_facebook_key_explain ?>
							<input type="text" id="facebook_key" name="facebook_key" class="form-control" value="<?= $facebook_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_facebook_secret ?><br>
							<input type="<?= $input_password ?>" id="facebook_secret" name="facebook_secret" class="form-control" value="<?= $facebook_secret ?>">
						</div>

						<div class="form-group">
							<?= $txt_twitter_key ?><br>
							<?= $txt_twitter_key_explain ?>
							<input type="text" id="twitter_key" name="twitter_key" class="form-control" value="<?= $twitter_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_twitter_secret ?><br>
							<input type="<?= $input_password ?>" id="twitter_secret" name="twitter_secret" class="form-control" value="<?= $twitter_secret ?>">
						</div>

						<div class="form-group">
							<?= $txt_disqus_shortname ?><br>
							<input type="text" id="disqus_shortname" name="disqus_shortname" class="form-control" value="<?= $disqus_shortname ?>">
						</div>

						<div class="form-group">
							<?= $txt_mapbox_secret ?><br>
							<input type="<?= $input_password ?>" id="mapbox_secret" name="mapbox_secret" class="form-control" value="<?= $mapbox_secret ?>">
						</div>

						<div class="form-group">
							<?= $txt_tomtom_secret ?><br>
							<input type="<?= $input_password ?>" id="tomtom_secret" name="tomtom_secret" class="form-control" value="<?= $tomtom_secret ?>">
						</div>

						<div class="form-group">
							<?= $txt_here_key ?><br>
							<input type="<?= $input_password ?>" id="here_key" name="here_key" class="form-control" value="<?= $here_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_here_secret ?><br>
							<input type="<?= $input_password ?>" id="here_secret" name="here_secret" class="form-control" value="<?= $here_secret ?>">
						</div>
					</div><!-- .tab-pane -->

					<!-- Payments -->
					<!-- Paypal -->
					<div id="paypal-panel" class="tab-pane fade" role="tabpanel">
						<?= $txt_paypal_header ?>
						<div class="form-group">
							<?= $txt_paypal_mode ?><br>
							<input type="radio" id="paypal_mode_live" name="paypal_mode" value="1" <?= $checked_live ?>>
							<label for="paypal_mode_live"><?= $txt_live ?></label>
							<br>
							<input type="radio" id="paypal_mode_sandbox" name="paypal_mode" value="0" <?= $checked_sandbox ?>>
							<label for="paypal_mode_sandbox"><?= $txt_sandbox ?></label>
							<br>
							<input type="radio" id="paypal_mode_disabled" name="paypal_mode" value="-1" <?= $checked_disabled ?>>
							<label for="paypal_mode_disabled"><?= ucfirst($txt_disabled) ?></label>
						</div>

						<div class="form-group">
							<?= $txt_paypal_merchant_id ?><br>
							<input type="text" id="paypal_merchant_id" name="paypal_merchant_id" class="form-control" value="<?= $paypal_merchant_id ?>">
						</div>

						<div class="form-group">
							<?= $txt_paypal_sandbox_merch_id ?><br>
							<input type="text" id="paypal_sandbox_merch_id" name="paypal_sandbox_merch_id" class="form-control" value="<?= $paypal_sandbox_merch_id ?>">
						</div>

						<input type="hidden" id="paypal_bn" name="paypal_bn" class="form-control" value="DirectoryPlus">

						<div class="form-group">
							<?= $txt_paypal_checkout_logo_url ?><br>
							<?= $txt_paypal_checkout_logo_url_explain ?>
							<input type="text" id="paypal_checkout_logo_url" name="paypal_checkout_logo_url" class="form-control" value="<?= $paypal_checkout_logo_url ?>">
						</div>

						<div class="form-group">
							<?= $txt_currency_code ?><br>
							<?= $txt_currency_code_explain ?>
							<input type="text" id="currency_code" name="currency_code" class="form-control" value="<?= $currency_code ?>">
						</div>

						<div class="form-group">
							<?= $txt_currency_symbol ?><br>
							<input type="text" id="currency_symbol" name="currency_symbol" class="form-control" value="<?= $currency_symbol ?>">
						</div>

						<div class="form-group">
							<?= $txt_paypal_locale ?><br>
							<select id="paypal_locale" name="paypal_locale" class="form-control">
								<?php
								foreach($paypal_locale_identifiers as $v) {
									$selected = '';
									if($v == $paypal_locale) $selected = 'selected'
									?>
									<option value="<?= $v ?>" <?= $selected ?>><?= $v ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>

					<!-- Stripe pane -->
					<div id="stripe-panel" class="tab-pane fade" role="tabpanel">
						<p><?= $txt_stripe_header ?></p>

						<div class="form-group">
							<?= $txt_gateway_mode ?><br>
							<input type="radio" id="stripe_mode_live" name="stripe_mode" value="1" <?= $stripe_checked_live ?>>
							<label for="stripe_mode_live"><?= $txt_live ?></label><br>
							<input type="radio" id="stripe_mode_test" name="stripe_mode" value="0" <?= $stripe_checked_sandbox ?>>
							<label for="stripe_mode_test"><?= $txt_stripe_test_mode ?></label><br>
							<input type="radio" id="stripe_mode_disabled" name="stripe_mode" value="-1" <?= $stripe_checked_disabled ?>>
							<label for="stripe_mode_disabled"><?= ucfirst($txt_disabled) ?></label>
						</div>

						<div class="form-group">
							<?= $txt_test_secret_key ?><br>
							<input type="text" id="stripe_test_secret_key" name="stripe_test_secret_key" class="form-control" value="<?= $stripe_test_secret_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_test_publishable_key ?><br>
							<input type="text" id="stripe_test_publishable_key" name="stripe_test_publishable_key" class="form-control" value="<?= $stripe_test_publishable_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_live_secret_key ?><br>
							<input type="text" id="stripe_live_secret_key" name="stripe_live_secret_key" class="form-control" value="<?= $stripe_live_secret_key ?>">
						</div>

						<div class="form-group">
							<?= $txt_live_publishable_key ?><br>
							<input type="text" id="stripe_live_publishable_key" name="stripe_live_publishable_key" class="form-control" value="<?= $stripe_live_publishable_key ?>">
						</div>

						<div class="form-group">
							Create Stripe Webhooks<br>
							<div id="stripe_create_webhook_error_msg"></div>
							<button type="button" id="stripe_create_test_webhook" class="btn btn-outline-dark">Create Test Webhook</button>
							<button type="button" id="stripe_create_live_webhook" class="btn btn-outline-dark">Create Live Webhook</button>
						</div>

						<div class="form-group">
							(data-currency) [<a href="https://support.stripe.com/questions/which-currencies-does-stripe-support" target="_blank"><?= $txt_stripe_currency_code ?></a>]<br>
							<input type="text" id="stripe_data_currency" name="stripe_data_currency" class="form-control" value="<?= $stripe_data_currency ?>">
						</div>

						<div class="form-group">
							<?= $txt_currency_symbol ?><br>
							<input type="text" id="stripe_currency_symbol" name="stripe_currency_symbol" class="form-control" value="<?= $stripe_currency_symbol ?>">
						</div>

						<div class="form-group">
							(data-image)<br>
							<input type="text" id="stripe_data_image" name="stripe_data_image" class="form-control" value="<?= $stripe_data_image ?>">
						</div>

						<div class="form-group">
							(data-description)<br>
							<input type="text" id="stripe_data_description" name="stripe_data_description" class="form-control" value="<?= $stripe_data_description ?>">
						</div>
					</div>
				</div>

				<div class="form-group submit-row">
					<button id="submit" name="submit" class="btn btn-primary"><?= $txt_save ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

/*--------------------------------------------------
Tabs
--------------------------------------------------*/
(function() {
	$('#settingsTabs a:first').on('click', function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
}());

/*--------------------------------------------------
Create Stripe webhooks
--------------------------------------------------*/
(function() {
	// test webhook
    $('#stripe_create_test_webhook').click(function(e){
		e.preventDefault();

		// empty previous error message if any
		$('#stripe_create_webhook_error_msg').empty();

		// show spinner
		$('#stripe_create_test_webhook').empty();
		$('#stripe_create_test_webhook').html('<i class="las la-spinner la-spin"></i> Contacting Stripe...');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-stripe-webhook.php';
		var test_key = $('#stripe_test_secret_key').val();

		// post
		$.post(post_url, { mode: 'test', stripe_key: test_key }, function(data) {
				// debug
				console.log(data);

				// restore button
				$('#stripe_create_test_webhook').html('Create Test Webhook');

				// parse json string from response
				var data = JSON.parse(data);

				// show result message
				if(jQuery.isEmptyObject(data.error)) {
					$('#stripe_create_webhook_error_msg').html('<div class="py-2">Test webhook created successfully</div>');
				}

				else {
					$('#stripe_create_webhook_error_msg').html('<div class="alert alert-danger" role="alert">' + data.error.message + '</div>');
				}
			}
		);
    });

	// live webhook
    $('#stripe_create_live_webhook').click(function(e){
		e.preventDefault();

		// empty previous error message if any
		$('#stripe_create_webhook_error_msg').empty();

		// show spinner
		$('#stripe_create_live_webhook').empty();
		$('#stripe_create_live_webhook').html('<i class="las la-spinner la-spin"></i> Contacting Stripe...');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-stripe-webhook.php';
		var live_key = $('#stripe_live_secret_key').val();

		// post
		$.post(post_url, { mode: 'live', stripe_key: live_key }, function(data) {
				// debug
				console.log(data);

				// restore button
				$('#stripe_create_live_webhook').html('Create Live Webhook');

				// parse json string from response
				var data = JSON.parse(data);

				// show result message
				if(jQuery.isEmptyObject(data.error)) {
					$('#stripe_create_webhook_error_msg').html('<div class="py-2">Live webhook created successfully</div>');
				}

				else {
					$('#stripe_create_webhook_error_msg').html('<div class="alert alert-danger" role="alert">' + data.error.message + '</div>');
				}
			}
		);
    });
}());
</script>

</body>
</html>