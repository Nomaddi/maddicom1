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

			<!-- search -->
			<div class="mb-3">
				<form class="form-search-place form-inline d-flex flex-nowrap" action="<?= $baseurl ?>/admin/listings" method="get">
					<div class="form-group mr-1">
						<input type="text" name="s" class="form-control form-control-sm"
						value="<?= !empty($_GET['s']) ? e($_GET['s']) : '' ?>">
					</div>

					<div class="form-group mr-1">
						<select name="cat" class="form-control form-control-sm">
							<option value=""><?= $txt_category ?></option>
							<?php get_children(0, $cat_id, 0, $conn) ?>
						</select>
					</div>

					<div class="form-group">
						<button class="btn btn-sm btn-primary"><?= $txt_search ?></button>
					</div>
				</form>
			</div>

			<?php
			$s_param = '';
			if(!empty($_GET['s'])) {
				$s_param = "&s=" . $_GET['s'];
				?>
				<p><?= $txt_search_results ?> <em>'<?= e($_GET['s']) ?>'</em></p>
				<?php
			}
			?>

			<!-- Show buttons -->
			<div class="mb-3">
				<strong><?= $txt_show ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/listings?show=approved<?= $s_param ?>" class="btn btn-light btn-sm"><?= $txt_approved ?></a>
				<a href="<?= $baseurl ?>/admin/listings?show=pending<?= $s_param ?>" class="btn btn-light btn-sm"><?= $txt_pending ?></a>
				<a href="<?= $baseurl ?>/admin/listings?show=feat<?= $s_param ?>" class="btn btn-light btn-sm"><?= $txt_featured ?></a>
				<a href="<?= $baseurl ?>/admin/listings?show=feat-home<?= $s_param ?>" class="btn btn-light btn-sm"><?= $txt_featured_home ?></a>
				<a href="<?= $baseurl ?>/admin/listings?show=all<?= $s_param ?>" class="btn btn-light btn-sm"><?= $txt_all ?></a>
			</div>

			<!-- results -->
			<?php
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></div>
					<div class=""><a href="<?= $baseurl ?>/admin/listings-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';

								$cat_param = '';
								if(!empty($cat_id)) $cat_param = '&cat=' . $cat_id;
								?>
								<a href="<?= $baseurl ?>/admin/listings?show=<?= $show ?>&sort=<?= $sort_param ?><?= $cat_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">&nbsp;</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'title';
								if($sort == 'title') $sort_param = 'title-desc';
								if($sort == 'title-desc') $sort_param = 'title';

								$cat_param = '';
								if(!empty($cat_id)) $cat_param = '&cat=' . $cat_id;
								?>
								<a href="<?= $baseurl ?>/admin/listings?show=<?= $show ?>&sort=<?= $sort_param ?><?= $cat_param ?>">
								<?= $txt_place_name ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'title') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'title-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_city ?>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';

								$cat_param = '';
								if(!empty($cat_id)) $cat_param = '&cat=' . $cat_id;
								?>
								<a href="<?= $baseurl ?>/admin/listings?show=<?= $show ?>&sort=<?= $sort_param ?><?= $cat_param ?>">
								<?= $txt_date ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_action ?>
							</th>
						</tr>
						<?php
						foreach($places_arr as $k => $v) {
							?>
							<tr id="tr-place-id-<?= $v['place_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['place_id'] ?></td>

								<td class="text-nowrap min-w-60"><a href="<?= $v['link_url'] ?>" title="<?= $v['place_name'] ?>"><img src="<?= $v['logo_url'] ?>"></a></td>

								<td><a href="<?= $v['link_url'] ?>" title="<?= $v['place_name'] ?>"><?= $v['place_name'] ?></a>
								<br><small class="badge badge-pill badge-light"><?= $v['cat_name'] ?></small></td>

								<td class="text-nowrap shrink">
									<?= !empty($v['city_name']) ? $v['city_name'] . ', ' . $v['state_abbr'] : '' ?>
								</td>
								<td class="text-nowrap shrink"><?= $v['date_formatted'] ?></td>
								<td class="text-nowrap shrink">
									<!-- status btn -->
									<?php
									if($v['status'] == 'pending') {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_approved ?>">
											<button class="btn btn-light btn-sm approve-place"
												id="status-place-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-place_slug="<?= $v['place_slug'] ?>"
												data-cat_id="<?= $v['cat_id'] ?>"
												data-cat_slug="<?= $v['cat_slug'] ?>"
												data-city_id="<?= $v['city_id'] ?>"
												data-city_slug="<?= $v['city_slug'] ?>"
												data-state_slug="<?= $v['state_slug'] ?>"
												data-status="pending">
												<i class="las la-toggle-off"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_approved ?>">
											<button class="btn btn-success btn-sm approve-place"
												id="status-place-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-place_slug="<?= $v['place_slug'] ?>"
												data-cat_id="<?= $v['cat_id'] ?>"
												data-cat_slug="<?= $v['cat_slug'] ?>"
												data-city_id="<?= $v['city_id'] ?>"
												data-city_slug="<?= $v['city_slug'] ?>"
												data-state_slug="<?= $v['state_slug'] ?>"
												data-status="approved">
												<i class="las la-toggle-on"></i>
											</button>
										</span>
										<?php
									}
									?>

									<!-- paid btn -->
									<?php
									if($v['paid'] == 0) {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_paid ?>">
											<button class="btn btn-light btn-sm paid-place"
												id="paid-place-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-place_slug="<?= $v['place_slug'] ?>"
												data-cat_id="<?= $v['cat_id'] ?>"
												data-cat_slug="<?= $v['cat_slug'] ?>"
												data-city_id="<?= $v['city_id'] ?>"
												data-city_slug="<?= $v['city_slug'] ?>"
												data-state_slug="<?= $v['state_slug'] ?>"
												data-paid="unpaid">
												<i class="las la-dollar-sign"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_paid ?>">
											<button class="btn btn-success btn-sm paid-place"
												id="paid-place-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-place_slug="<?= $v['place_slug'] ?>"
												data-cat_id="<?= $v['cat_id'] ?>"
												data-cat_slug="<?= $v['cat_slug'] ?>"
												data-city_id="<?= $v['city_id'] ?>"
												data-city_slug="<?= $v['city_slug'] ?>"
												data-state_slug="<?= $v['state_slug'] ?>"
												data-paid="paid">
												<i class="las la-dollar-sign"></i>
											</button>
										</span>
										<?php
									}
									?>

									<!-- featured_home toggle -->
									<?php
									if($v['feat_home'] == 0) {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_featured ?>">
											<button class="btn btn-light btn-sm featured-home"
												id="featured-home-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-featured-home="not_featured">
												<i class="las la-home"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_featured ?>">
											<button class="btn btn-success btn-sm featured-home"
												id="featured-home-<?= $v['place_id'] ?>"
												data-place-id="<?= $v['place_id'] ?>"
												data-featured-home="featured">
												<i class="las la-home"></i>
											</button>
										</span>
										<?php
									}
									?>

									<!-- edit btn -->
									<span data-toggle="tooltip"	title="<?= $txt_edit_place ?>">
										<a href="<?= $baseurl ?>/user/edit-listing/<?= $v['place_id'] ?>"
											class="btn btn-light btn-sm edit-place"
											data-id="<?= $v['place_id'] ?>">
											<i class="las la-pen"></i>
										</a>
									</span>

									<!-- expand btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand ?>">
										<button class="btn btn-light btn-sm expand-details"
											data-place-id="<?= $v['place_id'] ?>">
											<i class="las la-angle-down"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove_place ?>">
										<button class="btn btn-light btn-sm remove-place"
											data-place-id="<?= $v['place_id'] ?>"
											data-place_slug="<?= $v['place_slug'] ?>"
											data-cat_id="<?= $v['cat_id'] ?>"
											data-cat_slug="<?= $v['cat_slug'] ?>"
											data-city_id="<?= $v['city_id'] ?>"
											data-city_slug="<?= $v['city_slug'] ?>"
											data-state_slug="<?= $v['state_slug'] ?>">
											<i class="lar la-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<tr id="expand-details-<?= $v['place_id'] ?>" class="details-row" style="display:none">
								<td colspan="6" class="wrap">
									<div class="details-block">
										<div class="">
											<strong><?= $txt_listing_owner ?>:</strong>
											<span class="owner-email"><?= $v['place_email'] ?></span>

											<strong><?= $txt_transfer_owner ?></strong>

											<span class="btn btn-sm btn-light" id="activator-owner-<?= $v['place_id'] ?>">
												<i class="las la-pen"></i>
											</span>
											<div class="editable"
												data-url="<?= $baseurl ?>/admin/process-edit-owner.php"
												data-activator="#activator-owner-<?= $v['place_id'] ?>"
												data-attribute="owner"
												data-object="<?= $v['place_id'] ?>">
												<?= $v['place_owner'] ?>
											</div>
										</div>

										<div class="">
											<strong><?= $txt_city ?>:</strong>
											<?= !empty($v['city_name']) ? $v['city_name'] . ', ' . $v['state_abbr'] : '' ?>
										</div>

										<div class="">
											<strong><?= $txt_plan_name ?>:</strong>
											<?= $v['plan_name'] ?>
										</div>
										<div class="">
											<strong><?= $txt_category ?>:</strong>
											<?= $v['cat_name'] ?>
										</div>
									</div>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>

				<nav>
					<ul class="pagination flex-wrap">
						<?php
						if($total_rows > 0) {
							include_once(__DIR__ . '/../../inc/pagination.php');
						}
						?>
					</ul>
				</nav>
			<?php
			}

			else {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"></div>
					<div><a href="<?= $baseurl ?>/admin/listings-trash"><?= $txt_trash ?></a></div>
				</div>
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

// page size configuration
var page_size = '<?= intval($items_per_page) ?>';

// initial number of items
var num_items = <?= count($places_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

// hide all details
$('.details-row').hide();

/*--------------------------------------------------
Remove listing
--------------------------------------------------*/
(function(){
	$('.remove-place').on('click', function(e){
		e.preventDefault();

		// listing data
		var place_id   = $(this).data('place-id');
		var place_slug = $(this).data('place_slug');
		var cat_id     = $(this).data('cat_id');
		var cat_slug   = $(this).data('cat_slug');
		var city_id    = $(this).data('city_id');
		var city_slug  = $(this).data('city_slug');
		var state_slug = $(this).data('state_slug');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-listing.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-place-id-' + place_id;

		// object to send in post data
		var post_data = {
			place_id  : place_id,
			place_slug: place_slug,
			cat_id    : cat_id,
			cat_slug  : cat_slug,
			city_id   : city_id,
			city_slug : city_slug,
			state_slug: state_slug
		};

		// post
		$.post(post_url, post_data, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// page size
					page_size = page_size - 1;

					// hide row
					if (new_total > 0 && page_size > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						window.location.href = '<?= $page_url ?><?= $page - 1 > 0 ? $page - 1 : 1 ?>';
					}
				} else {
					$(wrapper).empty();
					var removed_row = $('<td colspan="5"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});
}());

/*--------------------------------------------------
Toggle listing status
--------------------------------------------------*/
(function(){
	$('.approve-place').on('click', function(e) {
		e.preventDefault();

		// listing data
		var place_id   = $(this).data('place-id');
		var place_slug = $(this).data('place_slug');
		var cat_id     = $(this).data('cat_id');
		var cat_slug   = $(this).data('cat_slug');
		var city_id    = $(this).data('city_id');
		var city_slug  = $(this).data('city_slug');
		var state_slug = $(this).data('state_slug');
		var status     = $(this).data('status');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-approve-place.php';

		// object to send in post data
		var post_data = {
			place_id  : place_id,
			place_slug: place_slug,
			cat_id    : cat_id,
			cat_slug  : cat_slug,
			city_id   : city_id,
			city_slug : city_slug,
			state_slug: state_slug,
			status    : status
		};

		// post
		$.post(post_url, post_data,	function(data) {
				// parse json
				var data = JSON.parse(data);

				// toggle button
				if(data.status == 'approved') {
					$('#status-place-' + place_id).removeClass('btn-light');
					$('#status-place-' + place_id).addClass('btn-success');
					$('#status-place-' + place_id + ' i').removeClass('la-toggle-off');
					$('#status-place-' + place_id + ' i').addClass('la-toggle-on');
					$('#status-place-' + place_id).data('status', 'approved');
				}

				if(data.status == 'pending') {
					$('#status-place-' + place_id).removeClass('btn-success');
					$('#status-place-' + place_id).addClass('btn-light');
					$('#status-place-' + place_id + ' i').removeClass('la-toggle-on');
					$('#status-place-' + place_id + ' i').addClass('la-toggle-off');
					$('#status-place-' + place_id).data('status', 'pending');
				}
			}
		);
	});
}());

/*--------------------------------------------------
Toggle paid status
--------------------------------------------------*/
(function(){
	$('.paid-place').on('click', function(e) {
		e.preventDefault();

		// listing data
		var place_id   = $(this).data('place-id');
		var place_slug = $(this).data('place_slug');
		var cat_id     = $(this).data('cat_id');
		var cat_slug   = $(this).data('cat_slug');
		var city_id    = $(this).data('city_id');
		var city_slug  = $(this).data('city_slug');
		var state_slug = $(this).data('state_slug');
		var paid       = $(this).data('paid');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-toggle-paid.php';

		// object to send in post data
		var post_data = {
			place_id  : place_id,
			place_slug: place_slug,
			cat_id    : cat_id,
			cat_slug  : cat_slug,
			city_id   : city_id,
			city_slug : city_slug,
			state_slug: state_slug,
			paid      : paid
		};

		// post
		$.post(post_url, post_data, function(data) {
				// parse json
				var data = JSON.parse(data);

				// process response
				if(data.paid == 'unpaid') {
					$('#paid-place-' + place_id).removeClass('btn-success');
					$('#paid-place-' + place_id).addClass('btn-light');
					$('#paid-place-' + place_id).data('paid', 'unpaid');
				}

				if(data.paid == 'paid') {
					$('#paid-place-' + place_id).removeClass('btn-light');
					$('#paid-place-' + place_id).addClass('btn-success');
					$('#paid-place-' + place_id).data('paid', 'paid');
				}
			}
		);
	});
}());

/*--------------------------------------------------
Featured home switch
--------------------------------------------------*/
(function(){
	$('.featured-home').on('click', function(e) {
		e.preventDefault();

		// listing data
		var place_id = $(this).data('place-id');
		var featured_home = $(this).data('featured-home');

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-toggle-featured-home.php';

		// object to send in post data
		var post_data = {
			place_id  : place_id,
			featured_home: featured_home
		};

		// post
		$.post(post_url, post_data, function(data) {
				if(data == 'not_featured') {
					$('#featured-home-' + place_id).removeClass('btn-success');
					$('#featured-home-' + place_id).addClass('btn-light');
					$('#featured-home-' + place_id).data('featured-home', 'not_featured');
				}

				if(data == 'featured') {
					$('#featured-home-' + place_id).removeClass('btn-light');
					$('#featured-home-' + place_id).addClass('btn-success');
					$('#featured-home-' + place_id).data('featured-home', 'featured');
				}
			}
		);
	});
}());

/*--------------------------------------------------
Expand details
--------------------------------------------------*/
(function(){
	$('.expand-details').on('click', function(e) {
		e.preventDefault();

		var place_id = $(this).data('place-id');
		$('#expand-details-' + place_id).toggle();
	});

	// initialize edit in place
	$('.editable').jinplace()
		.on('jinplace:done', function(ev, data) {
			var post_url = '<?= $baseurl ?>' + '/admin/process-edit-owner.php';
			$.post(post_url, { owner: data, attribute: 'update-email' }, function(data) {
					$('.owner-email').html(data);
				}
			);
    });
}());
</script>

</body>
</html>