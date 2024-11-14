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

			<div class="mb-3">
				<strong><?= $txt_sort ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/listings-trash?sort=name" class="btn btn-light btn-sm"><?= $txt_by_name ?></a>
				<a href="<?= $baseurl ?>/admin/listings-trash" class="btn btn-light btn-sm"><?= $txt_by_date ?></a>
			</div>

			<?php
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';
								?>
								<a href="<?= $baseurl ?>/admin/listings-trash?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"></th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'title';
								if($sort == 'title') $sort_param = 'title-desc';
								if($sort == 'title-desc') $sort_param = 'title';
								?>
								<a href="<?= $baseurl ?>/admin/listings-trash?sort=<?= $sort_param ?>">
								<?= $txt_place_name ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'title') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'title-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"><?= $txt_city ?></th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';
								?>
								<a href="<?= $baseurl ?>/admin/listings-trash?sort=<?= $sort_param ?>">
								<?= $txt_date ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($places_arr as $k => $v) {
							?>
							<tr id="tr-place-<?= $v['place_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['place_id'] ?></td>
								<td class="text-nowrap"><a href="<?= $v['link_url'] ?>" title="<?= $v['place_name'] ?>"><img src="<?= $v['logo_url'] ?>" class="min-w-60"></a></td>
								<td class="w-100"><a href="<?= $v['link_url'] ?>" target="_blank"><?= $v['place_name'] ?></a></td>
								<td class="text-nowrap shrink">
									<?= !empty($v['city_name']) ? $v['city_name'] . ', ' . $v['state_abbr'] : '' ?>
								</td>
								<td class="text-nowrap shrink"><?= $v['date_formatted'] ?></td>
								<td class="text-nowrap shrink">
									<!-- expand btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand ?>">
										<button class="btn btn-light btn-sm expand-details"
											data-place-id="<?= $v['place_id'] ?>">
											<i class="las la-angle-down"></i>
										</button>
									</span>

									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
										<button class="btn btn-light btn-sm restore-place"
											data-place-id="<?= $v['place_id'] ?>">
											<i class="las la-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_tooltip_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-place-modal"
											data-place-id="<?= $v['place_id'] ?>">
											<i class="lar la-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<tr id="expand-details-<?= $v['place_id'] ?>" class="details-row">
								<td colspan="5" class="wrap">
									<div class="details-block">
										<div class="">
											<strong><?= $txt_listing_owner ?>:</strong>
											<?= $v['user_email'] ?>
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
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Remove Place Modal -->
<div id="remove-place-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_remove_perm ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-place" data-dismiss="modal"><?= $txt_remove_perm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty trash modal -->
<div id="empty-trash-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_empty ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm"><?= $txt_confirm ?></button>
			</div>
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
Restore listing
--------------------------------------------------*/
(function(){
    $('.restore-place').on('click', function(e){
		e.preventDefault();

		// vars
		var place_id = $(this).data('place-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-listing.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-place-' + place_id;

		// post
		$.post(post_url, { place_id: place_id }, function(data) {
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
					var removed_row = $('<td colspan="6"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
    });
}());

/*--------------------------------------------------
Remove listing permanently
--------------------------------------------------*/
(function(){
	// on show modal
	$('#remove-place-modal').on('show.bs.modal', function(e) {
		// vars
		var button = $(e.relatedTarget);
		var place_id = button.data('place-id');

		// add place id value to button
		$('#remove-place-modal .remove-place').attr('data-place-id', place_id);
	});

	// remove button in modal clicked
    $('.remove-place').on('click', function(e){
		e.preventDefault();

		// vars
		var place_id = $('.remove-place').attr('data-place-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-listing-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-place-' + place_id;

		// post
		var request = $.post(post_url, { place_id: place_id });

		request.done( function(data) {
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
		});
    });
}());

/*--------------------------------------------------
Empty trash
--------------------------------------------------*/
(function(){
	// only reload if form is submitted
	var do_reload = false;

	// on show modal
	$('#empty-trash-modal').on('show.bs.modal', function(e) {
		// show default message
		$('#empty-trash-modal .modal-body').empty();
		$('#empty-trash-modal .modal-body').html('<?= $txt_remove_perm_sure_all ?>').fadeIn();
	});

	// on hide modal
	$('#empty-trash-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-listings.php';

		// post
		var request = $.post(post_url, {});

		request.done(function(data) {
			// set reload to true for on hide modal event
			do_reload = true;

			if(data == '1') {
				// reload
				location.reload(true);
			} else {
				// show response
				$('#empty-trash-modal .modal-body').empty();
				$('#empty-trash-modal .modal-body').html(data).fadeIn();
			}
		});
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
}());
</script>

</body>
</html>