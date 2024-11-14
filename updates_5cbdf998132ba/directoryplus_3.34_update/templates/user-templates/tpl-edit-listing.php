<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $place_name ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>

</head>
<body class="tpl-user-edit-listing">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?> - <?= $place_name ?></h2>

			<form method="post" id="edit-listing-form" action="<?= $baseurl ?>/user/process-edit-listing">
				<input type="hidden" name="csrf_token" value="<?= session_id() ?>">
				<input type="hidden" id="submit_token" name="submit_token" value="<?= $submit_token ?>">
				<input type="hidden" id="latlng" name="latlng" value="(<?= $lat ?>, <?= $lng ?>)">
				<input type="hidden" name="place_id" value="<?= $place_id ?>">

				<p><?= $txt_click_map ?> (*)</p>

				<div id="map-wrapper" class="mb-3">
					<div id="map-canvas" style="width:100%; height:100%"></div>
				</div>

				<div class="form-group">
					<label for="place_name"><?= $txt_business_name ?></label>
					<input type="text" id="place_name" class="form-control" name="place_name" value="<?= $place_name ?>" required>
				</div>

				<!-- logo -->
				<div class="form-group block" id="logo-img-row">
					<input type="file" id="logo_img" name="logo_img" style="display:block;visibility:hidden;width:0;height:0;">
					<input type="hidden" name="uploaded_logo" id="uploaded_logo" value="">
					<input type="hidden" name="existing_logo" id="existing_logo" value="<?= $logo ?>">

					<label><?= $txt_logo ?></label>

					<div class="mb-3">
						<div id="logo-img">
							<img src="<?= $logo_url ?>" class="rounded" width="132">
						</div>
					</div>

					<div class="logo-img-controls mb-3">
						<span id="upload-logo-btn" class="btn btn-light btn-sm pointer"><i class="fas fa-plus"></i>
						<?= $txt_upload_logo ?></span>

						<span id="delete-logo-btn" class="btn btn-light btn-sm pointer"><i class="fas fa-trash-alt"></i>
						<?= $txt_delete ?></span>
					</div>
				</div>
				<!-- end logo -->

				<div class="form-group">
					<label for="address"><?= $txt_address ?></label>
					<input type="text" id="address" class="form-control" name="address" value="<?= $address ?>" required>
				</div>

				<div class="form-group">
					<label for="cross_street"><?= $txt_cross_street ?></label>
					<input type="text" id="cross_street" class="form-control" name="cross_street" value="<?= $cross_street ?>">
				</div>

				<div class="form-group">
					<label for="neighborhood"><?= $txt_neighborhood ?></label>
					<input type="text" id="neighborhood" class="form-control" name="neighborhood" value="<?= $neighborhood ?>">
				</div>

				<div class="form-group">
					<label for="city_id"><?= $txt_city ?></label>
					<select id="city_id" class="form-control" name="city_id">
						<option value="<?= $city_id ?>"><?= $city_name ?>, <?= $state_abbr ?></option>
						<?php
						if(!$cfg_use_select2) {
							$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
							$stmt->execute();

							while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								?>
								<option value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?>, <?= $row['state'] ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>

				<div class="form-group">
					<input type="text" id="city_id_hidden" class="form-control" name="city_id_hidden" style="height:0;padding:0;opacity:0" value="<?= $city_id ?>" required>
				</div>

				<div class="form-group">
					<label for="postal_code"><?= $txt_postal_code ?></label>
					<input type="text" id="postal_code" class="form-control" name="postal_code" value="<?= $postal_code ?>">
				</div>

				<!-- Contact Info -->
				<label><?= $txt_phone ?></label>
				<div class="form-row mb-3">
					<div class="col-3">
						<select class="custom-select" name="country_code">
							<?php
							include(__DIR__ . '/../../inc/country-calling-codes.php');
							foreach($country_calling_codes as $k => $v) {
								?>
								<option value="<?= $v['value'] ?>" <?= $v['value'] == $country_code ? 'selected' : '' ?>><?= $v['name'] ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="col-2">
						<input type="text" id="area_code" class="form-control" name="area_code" value="<?= $area_code ?>">
					</div>

					<div class="col-7">
						<input type="tel" id="phone" class="form-control" name="phone" value="<?= $phone ?>">
					</div>
				</div>

				<label>Whatsapp</label>
				<div class="form-row mb-5">
					<div class="col-3">
						<select class="custom-select" name="wa_country_code">
							<?php
							include(__DIR__ . '/../../inc/country-calling-codes.php');
							foreach($country_calling_codes as $k => $v) {
								?>
								<option value="<?= $v['value'] ?>" <?= $v['value'] == $wa_country_code ? 'selected' : '' ?>><?= $v['name'] ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="col-2">
						<input type="text" id="wa_area_code" class="form-control" name="wa_area_code" value="<?= $wa_area_code ?>">
					</div>

					<div class="col-7">
						<input type="tel" id="wa_phone" class="form-control" name="wa_phone" value="<?= $wa_phone ?>">
					</div>
				</div>

				<div class="form-group">
					<label for="short_desc"><?= $txt_short_desc ?></label>
					<textarea id="short_desc" class="form-control" name="short_desc" rows="2"><?= $short_desc ?></textarea>
					<div class="float-right"><small id="count_message"></small></div>
				</div>

				<div class="form-group">
					<label for="description"><?= $txt_description ?></label>
					<textarea id="description" class="form-control" name="description" rows="10"><?= $description ?></textarea>
				</div>

				<div class="form-group">
					<label for="contact_email"><?= $txt_email ?></label>
					<input type="email" id="contact_email" class="form-control" name="contact_email" value="<?= $contact_email ?>">
				</div>

				<div class="form-group">
					<label for="website"><?= $txt_website ?></label>
					<input type="url" id="website" class="form-control" name="website" value="<?= $website ?>">
				</div>

				<div class="form-group">
					<label for="twitter">Twitter</label>
					<input type="text" id="twitter" class="form-control" name="twitter" value="<?= $twitter ?>">
				</div>

				<div class="form-group">
					<label for="facebook">Facebook</label>
					<input type="text" id="facebook" class="form-control" name="facebook" value="<?= $facebook ?>">
				</div>

				<div class="form-group">
					<label for="category_id"><?= $txt_primary_category ?> (*)</label>
					<select id="category_id" name="category_id" class="form-control select2">
						<option value=""><?= $txt_select_cat ?></option>
						<?php show_cat_dropdown(0, 0, $primary_cat, $conn) ?>
					</select>
					<input type="hidden" name="orig_cat_id" value="<?= $primary_cat ?>">
					<input type="hidden" name="orig_cat_slug" value="<?= $primary_slug ?>">
				</div>

				<div class="form-group">
					<label><?= $txt_additional_categories ?></label>

					<?php
					// show the category checkbox tree
					show_cats($cats_grouped_by_parent, 0, $checked_cats, 1);
					?>
				</div>

				<div class="form-group">
					<label for="business_hours"><?= $txt_hours ?></label>
					<textarea id="business_hours" class="form-control" name="business_hours"><?= $business_hours ?></textarea>
				</div>

				<!-- custom fields -->
				<?php require_once(__DIR__ . '/tpl-custom-fields.php') ?>

				<!-- Photos Upload -->
				<p class="text-dark text-uppercase mt-5" style="font-weight:600"><?= $txt_photos ?></p>
				<hr>

				<input type="file" id="item_img" name="item_img" style="display:block;visibility:hidden;width:0;height:0;" multiple>

				<div class="form-group">
					<label><?= $txt_upload ?></label>
					<a id="upload-button" class="btn btn-outline-secondary btn-lg pointer"><?= $txt_upload_btn ?></a>
				</div>

				<div class="form-group">
					<div id="uploaded" class="d-flex flex-row flex-wrap">
						<!-- uploaded pics -->
						<?php
						foreach($place_photos as $v) {
							?>
							<div class="thumbs mr-3">
								<img src="<?= $pic_baseurl ?>/<?= $place_thumb_folder ?>/<?= $v['dir'] ?>/<?= $v['filename'] ?>" width="132" class="rounded">
								<div class="btn-light delete_existing_pic"><small><?= $txt_delete ?></small></div>
								<input type="hidden" name="existing_pics[]" value="<?= $v['filename'] ?>">
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<!-- videos -->
				<div id="videos-block" class="form-group mt-5">
					<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_videos ?></p>
					<hr>

					<div class="form-row">
						<div class="col">
							<input type="text" id="video-url" class="form-control mb-2" placeholder="https://www.youtube.com/watch?v=">
						</div>
						<div class="col-auto">
							<button type="button" id="add-video" class="btn btn-dark"><i class="fas fa-plus"></i></button>
						</div>
					</div>

					<div id="added-videos">
						<?php
						foreach($place_videos as $k => $v) {
							?>
							<div class="form-row">
								<div class="col">
									<input type="text" class="form-control mb-2" name="videos[]" value="<?= $v['video_url'] ?>" readonly>
								</div>
								<div class="col-auto">
									<button type="button" class="btn btn-dark delete-video"><i class="far fa-trash-alt"></i></button>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<!-- submit -->
				<div style="margin-top:100px">
					<button class="btn btn-primary"><?= $txt_save ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>
