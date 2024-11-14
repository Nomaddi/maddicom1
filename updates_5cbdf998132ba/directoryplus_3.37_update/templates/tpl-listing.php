<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $place_name ?> - <?= $site_name ?></title>
<meta name="description" content="<?= $short_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

</head>
<body class="tpl-<?= $route[0] ?>">
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
					<div class="business-phone text-md-right text-dark text-nowrap" style="font-size:2rem">
						<a href="tel:<?= $area_code ?><?= $phone ?>"><i class="fas fa-phone"></i>
							<?php
							if($cfg_show_country_calling_code) {
								?>
								+<?= $country_calling_code ?>
								<?php
							}
							?>
							<?= $area_code ?>
							<?= $phone ?>
						</a>
					</div>
				<?php
				}
				?>

				<!-- Social Links -->
				<div class="text-md-right social">
					<?php
					// Website Url
					if(!empty($website)) {
						?>
						<a href="<?= $website_url ?>" class="mr-2"><i class="fas fa-globe"></i> </a>
					<?php
					}

					// Facebook Page
					if(!empty($facebook)) {
						?>
						<a href="https://facebook.com/<?= $facebook ?>" class="mr-2" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
					<?php
					}

					// Twitter Page
					if(!empty($twitter)) {
						?>
						<a href="https://twitter.com/<?= $twitter ?>" class="mr-2" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a>
					<?php
					}
					?>

					<a href="#" id="shareDropdown" class="mr-2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-share-alt"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right zoomIn animated" aria-labelledby="shareDropdown">
						<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical) ?>&src=sdkpreparse" class="dropdown-item"><i class="fab fa-facebook-f"></i> Facebook</a>

						<div class="dropdown-divider"></div>

						<a class="dropdown-item" href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical) ?>&text=<?= urlencode($place_name) ?>"><i class="fab fa-twitter"></i> Twitter</a>

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
				<div class="mb-5" style="position:relative;">
					<a class="slide-btn slidePrev text-dark shadow-1">
						<i class="fas fa-chevron-left"></i>
					</a>
					<a class="slide-btn slideNext text-dark shadow-1">
						<i class="fas fa-chevron-right"></i>
					</a>

					<div class="owl-carousel owl-theme">
						<?php
						foreach($photos as $k => $v) {
							?>
							<div class="" style="width:100%;height:320px"
								data-dot=
									"
									<img src='<?= $v['img_url'] ?>' width='120' class='rounded m-2'>
									">
								<a href="<?= $v['img_url'] ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?= $v['data_title'] ?>">
									<img src="<?= $v['img_url'] ?>" style="height:100%;object-fit:contain" alt="<?= $v['data_title'] ?>">
								</a>
							</div>
							<?php
						}

						foreach($videos as $v) {
							?>
							<div style="width:100%;height:320px"
								data-dot="<img src='<?= $v['thumb'] ?>' width='120' class='rounded m-2'>">
								<a href="<?= $v['url'] ?>" class="owl-video" data-toggle="lightbox" data-gallery="multiimages"></a>
							</div>
							<?php
						}
						?>
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
									<i class="text-green far fa-check-square"></i>
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
			// Custom Fields
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
													<strong><?= ee($v['icon']) ?> <?= $v['field_name'] ?>:</strong>
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
															$values = explode(':::', $v['field_value']);

															foreach($values as $v2) {
																echo $v2 . ' ';
															}
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

															echo $v2['field_value'];
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
									<?= nl2p(ucfirst($v['text'])) ?>
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
				<button type="button" class="add-to-favorites btn btn-block btn-outline-dark" data-listing-id="<?= $place_id ?>"><i class="<?= $is_fave ? 'fas' : 'far' ?> fa-heart"></i> <?= $txt_add_to_favorites ?></button>
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
	  font-size:30px;
		box-shadow: 2px 2px 3px #999;
	  z-index:100;
	}

	.my-float{
		margin-top:16px;
	}
	</style>
	<a href="https://wa.me/<?= $wa_country_code . $wa_area_code . $wa_phone ?>" class="float" target="_blank">
	<i class="fab fa-whatsapp my-float"></i>
	</a>
<?php
}
?>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>