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

			<?php
			if(!empty($plans_arr)) {
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
								<a href="<?= $baseurl ?>/admin/plans-trash?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'name';
								if($sort == 'name') $sort_param = 'name-desc';
								if($sort == 'name-desc') $sort_param = 'name';
								?>
								<a href="<?= $baseurl ?>/admin/plans-trash?sort=<?= $sort_param ?>">
								<?= $txt_plan_name ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'name') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'name-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'type';
								if($sort == 'type') $sort_param = 'type-desc';
								if($sort == 'type-desc') $sort_param = 'type';
								?>
								<a href="<?= $baseurl ?>/admin/plans-trash?sort=<?= $sort_param ?>">
								<?= $txt_plan_type ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'type') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'type-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'price';
								if($sort == 'price') $sort_param = 'price-desc';
								if($sort == 'price-desc') $sort_param = 'price';
								?>
								<a href="<?= $baseurl ?>/admin/plans-trash?sort=<?= $sort_param ?>">
								<?= $txt_price ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'price') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'price-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($plans_arr as $k => $v) {
							?>
							<tr id="tr-plan-<?= $v['plan_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['plan_id'] ?></td>
								<td><?= $v['plan_name'] ?></td>
								<td class="text-nowrap shrink"><?= $v['plan_type'] ?></td>
								<td class="text-nowrap shrink"><?= $v['plan_price'] ?></td>
								<td class="text-nowrap shrink">
									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_restore ?>">
										<button class="btn btn-light btn-sm restore-plan"
											data-plan-id="<?= $v['plan_id'] ?>">
											<i class="las la-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-plan-modal"
											data-plan-id="<?= $v['plan_id'] ?>">
											<i class="lar la-trash-alt"></i>
										</button>
									</span>
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

<!-- Remove plan modal -->
<div id="remove-plan-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_remove ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-plan" data-dismiss="modal" data-plan-id><?= $txt_confirm ?></button>
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
				<?= $txt_remove_all_sure ?>
			</div>
			<div class="modal-footer">
				<button class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
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
var num_items = <?= count($plans_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Restore plan
--------------------------------------------------*/
(function(){
    $('.restore-plan').on('click', function(e){
		e.preventDefault();

		// vars
		var plan_id = $(this).data('plan-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-plan.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-plan-' + plan_id;

		// post
		$.post(post_url, { plan_id: plan_id }, function(data) {
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
Remove plan permanently
--------------------------------------------------*/
(function(){
	// on show modal
	$('#remove-plan-modal').on('show.bs.modal', function(e) {
		// vars
		var button = $(e.relatedTarget);
		var plan_id = button.data('plan-id');

		// add plan_id value to button
		$('#remove-plan-modal .remove-plan').attr('data-plan-id', plan_id);
	});

	// remove button in modal clicked
    $('.remove-plan').on('click', function(e){
		e.preventDefault();

		// vars
		var plan_id = $('.remove-plan').attr('data-plan-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-plan-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-plan-' + plan_id;

		// post
		$.post(post_url, { plan_id: plan_id }, function(data) {
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
Empty trash
--------------------------------------------------*/
(function(){
	// only reload if form is submitted
	var do_reload = false;

	// on show modal
	$('#empty-trash-modal').on('show.bs.modal', function (e) {
		// show default message
		$('#empty-trash-modal .modal-body').empty();
		$('#empty-trash-modal .modal-body').html('<?= $txt_remove_all_sure ?>').fadeIn();
	});

	// on hide modal
	$('#empty-trash-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-plans.php';

		// post
		$.post(post_url, {}, function(data) {
				if(data == '1') {
					// set reload to true for on hide modal event
					do_reload = true;

					// reload
					location.reload(true);
				} else {
					// show error message
					$('#empty-trash-modal .modal-body').empty();
					$('#empty-trash-modal .modal-body').html(data).fadeIn();
				}
			}
		);
    });
}());
</script>

</body>
</html>