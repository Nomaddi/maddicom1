<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<meta name="robots" content="noindex">
<?php require_once('head.php') ?>

<!-- Page CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">
</head>
<body class="tpl-<?= $route[0] ?>">

<?php include('header.php') ?>

<div id="public-listings" class="container-fluid">
	<!-- dummy -->
	<div id="dummy" style="position:absolute;top:0;left:0;z-index:20000"></div>

	<!-- Sidebar -->
	<div id="the-sidebar" class="sidebar bg-light">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times"></i></a>

		<?php
		if(!empty($top_level_cats)) {
			?>
			<div class="mb-4 p-2">
				<p><strong><i class="fas fa-th-large"></i> <?= $txt_categories ?></strong></p>
				<hr>

				<?php
				foreach($top_level_cats as $v) {
					?>
					<div class="mb-0">
						<?php
						if($cat_id == $v['cat_id']) echo '<strong>';
						?>
						<a href="<?= $v['cat_link'] ?>&s=<?= $user_query ?>" title="<?= $v['cat_name'] ?>" class="mb-0"><?= $v['cat_name'] ?></a>
						<?php
						if($cat_id == $v['cat_id']) echo '</strong>';
						?>

						<?php
						if($cur_cat_top_level_parent == $v['cat_id']) {
							if(count($cats_path) == 1) {
								foreach($cur_cat_siblings as $v2) {
									?>
									<div class="mb-0 pl-4">
										<?php
										if($cat_id == $v2['cat_id']) echo '<strong>';
										?>
										<a href="<?= $v2['cat_link'] ?>&s=<?= $user_query ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
										<?php
										if($cat_id == $v2['cat_id']) echo '</strong>';
										?>
									</div>
									<?php
									if($cat_id == $v2['cat_id']) {
										if(!empty($cur_cat_children)) {
											foreach ($cur_cat_children as $v3) {
												?>
												<div class="mb-0 pl-5">
													<a href="<?= $v3['cat_link'] ?>&s=<?= $user_query ?>" title="" class="mb-0"><?= $v3['cat_name'] ?></a>
												</div>
												<?php
											}
										}
									}
								}
							}

							if(count($cats_path) == 2) {
								// show parent cat
								?>
								<div class="mb-0 pl-4">
									<a href="<?= $all_cats[$cats_path[1]]['cat_link'] ?>&s=<?= $user_query ?>" title="" class="mb-0"><?= $all_cats[$cats_path[1]]['cat_name'] ?></a>
								</div>
								<?php
								// show subcats and its siblings
								foreach($cur_cat_siblings as $v2) {
									?>
									<div class="mb-0 pl-5">
										<?php
										if($cat_id == $v2['cat_id']) echo '<strong>';
										?>
										<a href="<?= $v2['cat_link'] ?>&s=<?= $user_query ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
										<?php
										if($cat_id == $v2['cat_id']) echo '</strong>';
										?>
									</div>
									<?php
								}
							}
						}

						// show children of top parent cats
						if($cat_id == $v['cat_id']) {
							if(!empty($cur_cat_children)) {
								foreach ($cur_cat_children as $v2) {
									?>
									<div class="mb-0 pl-4">
										<a href="<?= $v2['cat_link'] ?>&s=<?= $user_query ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
									</div>
									<?php
								}
							}
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		<?php
		}
		?>

		<form id="sidebar-filter" class="p-2" method="get" action="<?= $baseurl ?>/results">
			<input type="hidden" name="cat_id" value="<?= $cat_id ?>">
			<input type="hidden" name="s" value="<?= $user_query ?>">

			<!-- location -->
			<h6><i class="fas fa-map-marker-alt"></i> <strong><?= $txt_location ?></strong></h6>
			<hr>
			<div id="select2-sidebar" class="form-row mb-3">
				<select id="city-input-sidebar" class="form-control form-control-lg" name="city">
					<option value="0"><?= $txt_city ?></option>

					<?php
					if(isset($city_id) && isset($city_name)) {
						?>
						<option value="<?= $city_id ?>" selected><?= $city_name ?>, <?= $state_abbr ?></option>
						<?php
					}
					?>

					<?php
					if(!$cfg_use_select2) {
						$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
						$stmt->execute();

						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							?>
							<option value="<?= e($row['city_id']) ?>"><?= e($row['city_name']) ?>, <?= e($row['state']) ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>

			<!-- custom fields -->
			<?php
			foreach($custom_fields_sidebar as $k => $v) {
				if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
					$values_arr = explode(';', $v['values_list']);
					$tr_values_arr = explode(';', $v['tr_values_list']);

					// check if translated values exist
					foreach($values_arr as $k2 => $v2) {
						if(empty($tr_values_arr[$k2])) {
							$tr_values_arr[$k2] = $values_arr[$k2];
						}
					}
				}

				// var name (used to define if values are selected)
				$field_name = 'field_' . $v['field_id'];
				?>
				<div class="mb-3" id="li-field-<?= $v['field_id'] ?>">
					<p><strong><?= $v['tr_field_name'] ?></strong></p>

					<?php
					if($v['filter_display'] == 'radio') {
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// selected value
							$checked = '';

							if(isset($_GET[$field_name]) && in_array($v2, $_GET[$field_name])) {
								$checked = 'checked';
							}
							?>
							<div class="custom-control custom-radio">
								<input type="radio" id="val_<?= $k ?><?= $k2 ?>" class="custom-control-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?>>
								<label class="custom-control-label" for="val_<?= $k ?><?= $k2 ?>"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
						}
					}

					elseif($v['filter_display'] == 'select') {
						?>
						<div class="form-group">
							<select class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>">
								<option value=""></option>
								<?php
								foreach($values_arr as $k2 => $v2) {
									$v2 = e(trim($v2));

									// selected value
									$selected = '';

									if(isset($_GET[$field_name]) && $_GET[$field_name] == $v2) {
										$selected = 'selected';
									}
									?>
									<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'checkbox') {
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// selected value
							$checked = '';

							if(isset($_GET[$field_name]) && in_array($v2, $_GET[$field_name])) {
								$checked = 'checked';
							}
							?>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="val_<?= $k ?><?= $k2 ?>" class="custom-control-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?> data-checked='<?= empty($checked) ?  'false' : 'true' ?>'>
								<label class="custom-control-label" for="val_<?= $k ?><?= $k2 ?>"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
						}
					}

					elseif($v['filter_display'] == 'range_text') {
						$val_from = isset($_GET[$field_name]['from']) ? $_GET[$field_name]['from'] : '';
						$val_to = isset($_GET[$field_name]['to']) ? $_GET[$field_name]['to'] : '';
						?>
						<div class="form-group">
							<input type="text" id="val_<?= $k ?><?= $k2 ?>" class="form-control form-control-sm mb-1" name="field_<?= $v['field_id'] ?>[from]" value="<?= $val_from ?>">
							<input type="text" id="val_<?= $k ?><?= $k2 ?>" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[to]" value="<?= $val_to ?>">
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'range_number') {
						$val_from = isset($_GET[$field_name]['from']) ? $_GET[$field_name]['from'] : '';
						$val_to = isset($_GET[$field_name]['to']) ? $_GET[$field_name]['to'] : '';
						?>
						<div class="form-group">
							<input type="number" id="val_<?= $k ?><?= $k2 ?>" class="form-control form-control-sm mb-1" name="field_<?= $v['field_id'] ?>[from]" placeholder="<?= $txt_from ?>" value="<?= $val_from ?>">
							<input type="number" id="val_<?= $k ?><?= $k2 ?>" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[to]" placeholder="<?= $txt_to ?>" value="<?= $val_to ?>">
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'range_select') {
						$val_from = isset($_GET[$field_name]['from']) ? $_GET[$field_name]['from'] : '';
						$val_to = isset($_GET[$field_name]['to']) ? $_GET[$field_name]['to'] : '';
						?>
						<div class="form-group">
							<select class="form-control form-control-sm mb-1" name="field_<?= $v['field_id'] ?>[from]">
								<option value=""><?= $txt_from ?></option>
								<?php
								foreach($values_arr as $k2 => $v2) {
									$v2 = e(trim($v2));

									$selected = '';

									if($v2 == $val_from) {
										$selected = 'selected';
									}
									?>
									<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?> <?= $v['value_unit'] ?></option>
									<?php
								}
								?>
							</select>

							<select class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[to]">
								<option value=""><?= $txt_to ?></option>
								<?php
								foreach($values_arr as $k2 => $v2) {
									$v2 = e(trim($v2));

									$selected = '';

									if($v2 == $val_to) {
										$selected = 'selected';
									}
									?>
									<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<?php
					}

					// else assume it's text
					else {
						?>
						<div class="form-group">
							<input type="text" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>" value="<?= isset($custom_fields[$v['field_id']]['field_value']) ? $custom_fields[$v['field_id']]['field_value'] : '' ?>">
						</div>
						<?php
					}
					?>
				</div>
			<?php
			}
			?>

			<!-- submit -->
			<button class="btn btn-block btn-primary mb-4"><?= $txt_submit ?></button>
		</form>
	</div>

    <div class="row" id="content">
		<!-- Map -->
        <div id="map-col" class="col-lg-5 h-100 fixed-top">
			<?php
			if(!empty($list_items)) {
				?>
				<div class="map-wrapper sidebar-map" id="sticker" style="z-index:998;width:100%; height:100%">
					<div id="map-canvas" style="width:100%; height:100%"></div>
				</div>
				<?php
			}
			?>
        </div>

		<!-- Scrollable content -->
		<div class="col-lg-7">
			<!-- Breadcrumbs and filter button -->
			<div class="container-fluid mt-3 mb-4">
				<div class="d-flex">
					<div class="flex-grow-1 breadcrumbs"><?= $breadcrumbs ?></div>
				</div>
			</div>

			<div class="container-fluid mb-4">
				<!-- sort, nearby, filters -->
				<ul class="nav justify-content-end mb-2">
					<!-- filters -->
					<li class="nav-item">
						<button class="btn btn-sm btn-light mr-1" type="button" onclick="openNav()"><i class="fas fa-sliders-h"></i> <?= $txt_filters ?></button>
					</li>

					<?php
					if(!empty($cgf_max_dist_values)) {
						?>
						<li class="nav-item">
							<div class="dropdown">
								<button class="btn btn-sm btn-light dropdown-toggle mr-1" type="button" id="dropdown-menu-nearby" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?= $txt_nearby ?>
								</button>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-menu-nearby">
									<?php
									if(empty($user_lat) || empty($user_lng)) {
										?>
										<a class="dropdown-item" href="#"><?= $txt_enable_geo ?></a>
										<?php
									}

									else {
										?>
										<a class="dropdown-item" href="<?= $page_url_without_page ?><?= !empty($sort) ? '?sort=' . e($_GET['sort']) : '' ?>"><?= $txt_clear ?></a>
										<?php
										foreach($max_dist_values as $v) {
											?>
											<a class="dropdown-item <?= $v == $_GET['dist'] ? 'active' : '' ?>" href="<?= $page_url_without_page ?>&<?= !empty($sort) ? 'sort=' . e($_GET['sort']) . '&' : '' ?>dist=<?= $v ?>"><?= $v ?> <?= $cgf_max_dist_unit ?> </a>
											<?php
										}
									}
									?>
								</div>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div>

			<div class="container-fluid item-list">
				<?php
				// if no listings
				if(empty($list_items)) {
					echo $txt_no_results;
				}

				foreach($list_items as $k => $v) {
					$feat_class = $v['is_feat'] ? 'featured' : '';
					$feat_badge = $v['is_feat'] ? '<span class="badge badge-success">' . $txt_featured . '</span>' : '';
					?>
					<div class="row list-item mb-4 mx-3 mx-sm-0 <?= $feat_class ?>" data-listing-id="<?= $v['place_id'] ?>">
						<div class="col-sm-5 position-relative">
							<a href="<?= $v['listing_link'] ?>"><img src="<?= $v['logo_url'] ?>" class="rounded" style="max-height: 240px"></a>
							<span class="cat-name-figure rounded p-2"><?= $v['main_cat_name'] ?></span>
						</div>

						<div class="col-sm-7 p-3 pl-4">
							<div class="d-flex mb-3">
								<div class="flex-grow-1">
									<h4 class="mb-2"><a href="<?= $v['listing_link'] ?>"><?= $v['place_name'] ?></a>
										<?= $feat_badge ?>
									</h4>
									<div class="item-rating" data-rating="<?= $v['rating'] ?>">
										<!-- raty plugin placeholder -->
									</div>
								</div>
							</div>

							<div class="card-text mb-2">
								<?= $v['short_desc'] ?>
							</div>

							<?php
							if($cfg_show_website && !empty($v['website'])) {
								?>
								<a href="<?= $v['website'] ?>" target="_blank"><?= $v['website'] ?></a>
								<?php
							}
							?>

							<hr>

							<div class="d-flex flex-wrap mb-2">
								<?php
								if(!empty($custom_fields_values[$v['place_id']])) {
									foreach($custom_fields_values[$v['place_id']] as $k2 => $v2) {
										?>
										<div class="mr-1">
											<?php
											if(in_array($listings_custom_fields[$k2]['show_in_res'], array('icon', 'name-icon'))) {
												echo $listings_custom_fields[$k2]['icon'];
											}

											if(in_array($listings_custom_fields[$k2]['show_in_res'], array('name', 'name-icon'))) {
												?>
												<small><strong><?= $listings_custom_fields[$k2]['field_name'] ?></strong></small><?php
											}
											?>:
											<small><?= $v2 ?> <?= $listings_custom_fields[$k2]['value_unit'] ?></small>
										</div>
										<?php
									}
								}
								?>
							</div>

							<div class="d-flex">
								<div class="address flex-grow-1">
									<strong>
										<i class="fas fa-map-marker-alt"></i>
										<?= !empty($v['address']) ? $v['address'] : '' ?>
										<?= !empty($v['city_name']) ? " - " . $v['city_name'] . ", " : '' ?>
										<?= !empty($v['state_abbr']) ? $v['state_abbr'] : '' ?>
										<?= !empty($v['postal_code']) ? $v['postal_code'] : '' ?>
									</strong>

									<?php
									if(!empty($v['area_code']) && !empty($v['phone'])) {
										?>
										<div class="tel">
											<a href="tel:+<?= $v['country_call'] ?><?= $v['area_code'] ?><?= $v['phone'] ?>">
												<strong><i class="fas fa-mobile-alt"></i>
												<?php
												if($cfg_show_country_calling_code) {
													?>
													+<?= $v['country_call'] ?>
													<?php
												}
												?>
												<?= $v['area_code'] ?>-<?= $v['phone'] ?>
												</strong>
											</a>
										</div>
										<?php
									}
									?>
								</div>

								<div class="btn pointer">
									<span class="add-to-favorites" data-listing-id=<?= $v['place_id'] ?>><i class="<?= in_array($v['place_id'], $favorites) ? 'fas' : 'far' ?> fa-heart"></i></span>
								</div>
							</div>
						</div>
					</div>
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
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>
