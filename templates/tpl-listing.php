<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $place_name ?> - <?= $site_name ?></title>

<!-- SplideJS CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/splide_2.4.21/splide.min.css">

<?php
if(!empty($videos)) {
	?>
<!-- SplideJs Video Extension CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/splide-extension-video_0.4.6/splide-extension-video.min.css">
	<?php
}
?>

<!-- Bootstrap Lightbox -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/lightbox-master/dist/ekko-lightbox.css">

<?php require_once('head.php') ?>

<!-- Meta data -->
<meta name="description" content="<?= $short_desc ?>">

<!-- Canonical URL -->
<link rel="canonical" href="<?= $canonical ?>">

<!-- Open Graph data -->
<meta property="og:title" content="<?= $place_name ?> - <?= $site_name ?>">
<meta property="og:url" content="<?= $canonical ?>">
<meta property="og:type" content="place">
<meta property="og:description" content="<?= $short_desc ?>">
<?php
if(!empty($photos[0]['img_url'])) {
	?><meta property="og:image" content="<?= $photos[0]['img_url'] ?>"><?php
}
?>
</head>
<body class="tpl-listing">
<?php require_once(__DIR__ . '/../inc/inc-social-media.php') ?>
<?php require_once('header.php') ?>

<!-- Business Header -->
<div id="business-header" class="container-fluid bg-default p-3 mb-5">
	<!-- Breadcrumbs -->
	<div class="container breadcrumbs">
		<?php
		// breadcrumbs: home ?>
		<a href="<?= $baseurl ?>/"><?= $txt_home ?></a>

		<?php
		// breadcrumbs: state
		if(!empty($state_name)) {
			?> > <a href="<?= $baseurl ?>/listings/<?= $state_slug ?>"><?= $state_name ?></a>
			<?php
		}

		// breadcrumbs: city
		if(!empty($city_slug)) {
			?> > <a href="<?= $baseurl ?>/listings/<?= $state_slug ?>/<?= $city_slug ?>"><?= $city_name ?></a>
			<?php
		}

		// breadcrumbs: categories
		if(!empty($cats_path_details)) {
			foreach($cats_path_details as $k => $v) {
				?>
				 > <a href="<?= $baseurl ?>/listings/<?= $state_slug ?>/<?= $city_slug ?>/<?= $v['cat_slug'] ?>"><?= $v['cat_name'] ?></a>
				<?php
			}
		}
		?>
	</div>

	<!-- Business Title Area -->
	<div class="container mt-3">
		<div class="row">
			<div class="col-12 col-md-7">
				<div class="row">
					<?php
					if(!empty($logo)) {
						?>
						<div class="col-4 col-lg-3">
							<img class="rounded" src="<?= $logo_url ?>" alt="<?= $place_name ?>" title="<?= $place_name ?>">
						</div>
						<?php
					}
					?>

					<div class="col-8 col-lg-9">
						<h1 id="place-id-<?= $place_id ?>"><?= $place_name ?> <?= $feat ? "<span class='featured-badge'>$txt_featured</span>" : '' ?></h1>

						<div class="item-rating" data-rating="<?= $rating ?>">
							<!-- raty plugin placeholder -->
						</div>

						<div>
							<?php
							foreach($secondary_cats as $v) {
								?>
								<a href="<?= $baseurl ?>/listings/<?= $state_slug ?>/<?= $city_slug ?>/<?= $v['cat_slug'] ?>" class="badge badge-pill badge-light" style="border: 1px solid #212529"><?= $v['cat_name'] ?></a>
								<?php
							}
							?>
							<a href="<?= $baseurl ?>/listings/<?= $state_slug ?>/<?= $city_slug ?>/<?= $main_cat_slug ?>" class="badge badge-pill badge-light" style="border: 1px solid #212529"><?= $main_cat_name ?></a><br>
							<?= $address ?><br>
							<?= $city_name ?>, <?= $state_abbr ?>
						</div>

						<?php
						if($place_userid == 1) {
							?>
							<a href="<?= $baseurl ?>/claim?id=<?= $place_id ?>" class=""><?= $txt_claim ?></a>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-5 py-2">
				<!-- Phone -->
				<?php
				if(!empty($phone)) {
					?>
					<div class="business-phone text-md-right text-dark text-nowrap">
						<a href="tel:<?= $area_code ?><?= $phone ?>">
							<i class="las la-mobile-alt la-lg" style="vertical-align:middle"></i><?php
							if($cfg_show_country_calling_code) {
								?>
								+<?= $country_calling_code ?>
								<?php
							}
							?><?= $area_code ?><?= $phone ?>
						</a>
					</div>
				<?php
				}
				?>

				<!-- Social Links -->
				<div class="text-md-right">
					<?php
					// Website Url
					if(!empty($website)) {
						?>
						<a href="<?= $website_url ?>" class="mr-2" target="_blank"><i class="las la-external-link-alt" style="font-size:2rem"></i></a>
					<?php
					}

					// Facebook Page
					if(!empty($facebook)) {
						?>
						<a href="https://facebook.com/<?= $facebook ?>" class="mr-2" target="_blank"><i class="lab la-facebook" aria-hidden="true" style="font-size:2rem"></i></a>
					<?php
					}

					// Instagram Page
					if(!empty($instagram)) {
						?>
						<a href="https://instagram.com/<?= $instagram ?>" class="mr-2" target="_blank"><i class="lab la-instagram" aria-hidden="true" style="font-size:2rem"></i></a>
					<?php
					}

					// Twitter Page
					if(!empty($twitter)) {
						?>
						<a href="https://twitter.com/<?= $twitter ?>" class="mr-2" target="_blank"><i class="lab la-twitter" aria-hidden="true" style="font-size:2rem"></i></a>
					<?php
					}
					?>

					<a href="#" id="shareDropdown" class="mr-2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-share-alt" style="font-size:2rem"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right zoomIn animated" aria-labelledby="shareDropdown">
						<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical) ?>&src=sdkpreparse" class="dropdown-item"><i class="lab la-facebook"></i> Facebook</a>

						<div class="dropdown-divider"></div>

						<a class="dropdown-item" href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical) ?>&text=<?= urlencode($place_name) ?>"><i class="lab la-twitter"></i> Twitter</a>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Main Information -->
<div class="container mt-3">
	<div class="row">
		<div class="col-md-6 col-lg-8">
			<!-- Gallery -->
			<?php
			if(!empty($photos) || !empty($videos)) {
				?>
				<div id="gallery" class="mb-5">
					<div id="primary-slider" class="splide mb-2">
						<div class="splide__arrows">
							<button class="splide__arrow splide__arrow--prev">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
							</button>
							<button class="splide__arrow splide__arrow--next">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
							</button>
						</div>

						<div class="splide__track">
							<div class="splide__list">
								<?php
								foreach($photos as $k => $v) {
									?>
									<div class="splide__slide">
										<div class="">
										<a href="<?= $v['img_url'] ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?= $v['data_title'] ?>"><img src="<?= $v['img_url'] ?>"></a></div>
									</div>
									<?php
								}

								foreach($videos as $v) {
									if($v['source'] == 'youtube' || $v['source'] == 'youtube') {
										?>
										<div class="splide__slide d-flex justify-content-center" data-splide-<?= $v['source'] ?>="<?= $v['url'] ?>">
											<div class="">
											<a href="<?= $v['url'] ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?= $v['title'] ?>"><img src="<?= $v['thumb'] ?>"></a></div>
										</div>
										<?php
									}

									else {
										?>
										<div class="splide__slide d-flex justify-content-center" data-splide-<?= $v['source'] ?>="<?= $v['url'] ?>">
											<div class="">
											<a href="<?= $v['url'] ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?= $v['title'] ?>" data-remote="http://player.vimeo.com/video/<?= $v['data']['video_id'] ?>?autopause=1"><img src="<?= $v['thumb'] ?>"></a></div>
										</div>
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>

					<!-- thumbnail slider -->
					<div id="secondary-slider" class="splide">
						<div class="splide__arrows">
							<button class="splide__arrow splide-arrow-small splide__arrow--prev">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
							</button>
							<button class="splide__arrow splide-arrow-small splide__arrow--next">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
							</button>
						</div>

						<div class="splide__track">
							<div class="splide__list">
								<?php
								foreach($photos as $k => $v) {
									?>
									<div class="splide__slide rounded pointer">
										<img src="<?= $v['thumb_url'] ?>" class="rounded">
									</div>
									<?php
								}

								foreach($videos as $v) {
									?>
									<div class="splide__slide rounded pointer">
										<img src="<?= $v['thumb'] ?>" class="rounded">
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<!-- Short Description -->
			<?php
			if(!empty($short_desc)) {
				?>
				<div class="description mb-5">
					<?= $short_desc ?>
				</div>
			<?php
			}
			?>

			<!-- Description -->
			<?php
			if(!empty($description)) {
				?>
				<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_description ?></p>
				<hr>

				<div class="description mb-5">
					<?= $description ?>
				</div>
			<?php
			}
			?>

			<!-- Features -->
			<?php
			if(!empty($custom_fields)) {
				$i = 0;

				foreach($custom_fields as $k => $v) {
					if(($v['field_type'] == 'radio' || $v['field_type'] == 'select') && $v['values_list'] == $cfg_custom_field_toggle_values) {
						// add the heading if first loop
						if($i == 0) {
							?>
							<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_features ?></p>
							<hr>

							<div class="d-flex mb-5 flex-wrap">
							<?php
						}

						// if toggle value is 'yes' then echo feature
						if($v['field_value'] == explode(';', $cfg_custom_field_toggle_values)[0]) {
							?>
							<div class="mr-5 text-nowrap mb-2">
								<?php
								if(!$cfg_show_custom_fields_icons) {
									?>
									<i class="text-green las la-check"></i>
									<?php
								}

								else {
									echo ee($v['icon']);
								}

								echo !empty($v['tr_field_name']) ? $v['tr_field_name'] : $v['field_name'];
								?>
							</div>
							<?php
						}

						$i++;

						// unset this item from array
						unset($custom_fields[$v['field_id']]);
					}
				}

				// close div (only close if there was actually custom fields of type toggle)
				if($i > 0) {
					?>
					</div>
					<?php
				}
			}

			// Other features
			if(!empty($custom_fields) && !empty($fields_groups)) {
				$i = 0;

				foreach($fields_groups as $g) {
					if(in_array($g['group_id'], array_column($custom_fields, 'field_group'))) {
						?>
						<p class="text-dark text-uppercase" style="font-weight:600"><?= $g['group_name'] ?></p>
						<hr>

						<div class="container-fluid mb-5 p-0">
							<?php
							foreach($custom_fields as $k => $v) {
								if($v['field_group'] == $g['group_id']) {
									if(!(($v['field_type'] == 'radio' || $v['field_type'] == 'select') && $v['values_list'] == $cfg_custom_field_toggle_values)) {
										?>
										<div class="row no-gutters <?= $i % 2 == 0 ? 'bg-light' : '' ?> p-3">
											<div class="col-md-6">
												<div class="field-name">
													<strong><?= ee($v['icon']) ?> <?= !empty($v['tr_field_name']) ? $v['tr_field_name'] : $v['field_name'] ?>:</strong>
												</div>
											</div>

											<div class="col-md-6 field-value">
												<div class="field-value">
													<?php
													if(!empty($v['field_value'])) {
														if($v['field_type'] == 'url' && filter_var($v['field_value'], FILTER_VALIDATE_URL)) {
															?>
															<a href="<?= $v['field_value'] ?>" target="_blank"><?= $v['field_value'] ?></a>
															<?php
														}

														else {
															$values = explode(':::', $v['tr_field_value']);
															echo implode(', ', $values);
														}
													}

													$j = 1;

													foreach($v as $k2 => $v2) {
														if(!empty($v2['field_value'])) {
															if($v2['field_type'] == 'url' && filter_var($v2['field_value'], FILTER_VALIDATE_URL)) {
																?>
																<a href="<?= $v2['field_value'] ?>" target="_blank">
																<?php
															}

															if($j > 1) {
																echo ', ';
															}

															echo $v2['tr_field_value'];
															$j++;

															if($v2['field_type'] == 'url' && filter_var($v2['field_value'], FILTER_VALIDATE_URL)) {
																echo "</a>";
															}
														}
													}
													?>
												</div>
											</div>
										</div>
										<?php
										$i++;
									}
								}
							}
							?>
						</div>
						<?php
					}
				}
			}
			?>

			<!-- Coupons -->
			<?php
			if($cfg_enable_coupons) {
				if(!empty($coupons_arr)) {
					?>
					<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_coupons ?></p>
					<hr>

					<div id="coupons-wrapper" class="mb-5">
						<?php
						$i = 0;
						foreach($coupons_arr as $k => $v) {
							if($i > 0) echo '<hr>'; $i++;
							?>
							<div class="row mb-5" id="coupon-<?= $v['coupon_id'] ?>">
								<div class="col-4 col-sm-4" id="<?= $v['coupon_id'] ?>">
									<a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>"><img src="<?= $v['coupon_img'] ?>" class="rounded" alt="<?= $v['coupon_title'] ?>"></a>
								</div>

								<div class="col-8 col-sm-8">
									<div class="mb-3">
										<h4><strong><a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>" class="text-dark"><?= $v['coupon_title'] ?></a></strong></h4>
									</div>

									<div class="mb-3"><?= $v['coupon_description'] ?></div>

									<div class="">
										<a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>" class="btn btn-outline-dark btn-sm"><strong><?= $txt_view_details ?></strong></a>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
			}
			?>

			<?php
			if($cfg_enable_reviews) {
				?>
				<!-- Reviews -->
				<div id="reviews" class="tab-pane" role="tabpanel">
					<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_reviews ?></p>
					<hr>

					<?php
					if(!empty($reviews)) {
						foreach($reviews as $k => $v) {
							?>
							<div class="d-flex mb-3">
								<div class="mr-3">
									<a href="<?= $v['profile_link'] ?>">
										<img src="<?= $v['profile_pic_url'] ?>" alt="<?= $v['user_display_name'] ?>" class=" profile-thumb rounded-circle">
									</a>
								</div>

								<div class="flex-grow-1">
									<div class="mb-3">
										<div style="line-height:1">
											<a href="<?= $v['profile_link'] ?>"><?= $v['user_display_name'] ?></a>
										</div>

										<div style="line-height:1">
											<span class="smallest text-muted"><?= date("F j, Y", $v['pubdate']) ?></span>
										</div>

										<?php
										if($v['rating'] != 0) {
											?>
											<div class="review-rating" data-rating="<?= $v['rating'] ?>">
												<!-- .review-rating placeholder -->
											</div>
											<?php
										}
										?>
									</div>

									<div>
										<?= nl2p(($v['text'])) ?>
									</div>
								</div>
							</div>

							<hr>
						<?php
						}
					}
					?>

					<div id="review-form-wrapper" class="mb-5">
						<?php
						if(!empty($_SESSION['user_connected']) && !empty($_SESSION['userid'])) {
							?>
							<form method="post" id="review-form">
								<input type="hidden" name="place_id" id="place_id" value="<?= $place_id ?>">

								<div class="form-group">
									<label for="email"><?= $txt_please_rate ?></label>
									<div class="raty"></div>
									<div id="hint">&nbsp;&nbsp;</div>
								</div>

								<div class="form-group">
									<label for="review"><?= $txt_review_txtarea_label ?></label>
									<textarea id="review" class="form-control" name="review"></textarea>
								</div>

								<div class="form-row submit-row">
									<input type="button" id="submit-review" name="submit" value="<?= $txt_submit ?>" class="btn btn-dark">
								</div>
							</form>
							<?php
						}

						else {
							?>
							<p><?= $txt_review_login_req ?></p>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>

			<!-- Similar Listings -->
			<?php
			if(!empty($similar_items)) {
				?>
				<p class="text-dark text-uppercase" style="font-weight:600">  <?= $txt_similar_listings ?></p>
				<hr>

				<div id="" class="row mb-5">
					<?php
					$i = 0;
					foreach($similar_items as $v) {
						?>
						<div class="col-lg-3 col-md-4 col-sm-6 mb-5">
							<div class="card text-white text-center">
								<a href="<?= $v['place_link'] ?>" title="<?= $v['place_name'] ?>" class="text-white">
									<img class="card-img" src="<?= $v['photo_url'] ?>" alt="<?= $v['place_name'] ?>">
								</a>
							</div>
							<div class="">
								<p class="text-dark"><?= $v['place_name'] ?></p>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>

		</div>

		<!-- Sidebar -->
		<div class="col-md-6 col-lg-4">
			<!-- Contact and Favorites -->
			<div class="mb-4">
				<button type="button" class="btn btn-block btn-dark" data-toggle="modal" data-target="#contact-user-modal"><?= $txt_contact_business ?></button>
				<button type="button" class="add-to-favorites btn btn-block btn-outline-dark" data-listing-id="<?= $place_id ?>"><i class="<?= $is_fave ? 'las' : 'lar' ?> la-heart"></i> <?= $txt_add_to_favorites ?></button>
			</div>

			<!-- Hours -->
			<?php
			if(!empty($business_hours)) {
				?>
				<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_hours ?></p>
				<hr>

				<div class="mb-5"><?= $business_hours ?></div>
			<?php
			}
			?>

			<!-- Map -->
			<?php
			if (!empty($lat) && !empty($address)) {
				?>
				<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_location ?></p>
				<hr>

				<div id="place-map-wrapper" class="mb-5">
					<div id="place-map-canvas" style="width:100%; height:100%"></div>
				</div>
				<?php
			}
			?>

			<!-- Manager -->
			<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_manager ?></p>
			<hr>

			<div class="d-flex mb-4">
				<?php
				if(!empty($manager_profile_pic)) {
					?>
					<div class="mr-2">
						<img src="<?= $manager_profile_pic ?>" class="listing-manager rounded-circle profile-thumb">
					</div>
					<?php
				}
				?>

				<div class="flex-grow-1">
					<span class="text-dark mb-0"><strong><a href="<?= $baseurl ?>/profile/<?= $place_userid ?>"><?= $manager_display_name ?></a></strong><br></span>

					<div class="smallest">
						<?php
						if(!empty($manager_city)) {
							?>
							<span class="text-muted mb-0"><?= $manager_city ?></span>
						<?php
						}

						if(!empty($manager_city) && !empty($manager_country)) {
							?>
							<span class="text-muted mb-0">, <?= $manager_country ?></span>
						<?php
						}

						if(empty($manager_city) && !empty($manager_country)) {
							?>
							<span class="text-muted mb-0"><?= $manager_country ?></span>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include_once(__DIR__ . '/modal-contact-user.php');
?>

<?php
if(!empty($wa_country_code) && !empty($wa_area_code) && !empty($wa_phone)) {
	?>
	<!-- whatsapp floating button -->
	<style>
	.float{
		position:fixed;
		width:60px;
		height:60px;
		bottom:40px;
		right:40px;
		background-color:#25d366;
		color:#FFF;
		border-radius:50px;
		text-align:center;
		box-shadow: 2px 2px 3px #999;
		z-index:100;
	}

	.my-float{
		margin-top:10px;
		font-size: 2.5rem;
		vertical-align:middle;
	}
	</style>
	<a href="https://wa.me/<?= $wa_country_code . $wa_area_code . $wa_phone ?>" class="float" target="_blank">
	<i class="lab la-whatsapp my-float"></i>
	</a>
<?php
}
?>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>