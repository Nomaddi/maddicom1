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
				<strong><?= $txt_action ?>:</strong><br>
				<button class="btn btn-light btn-sm"
					data-loc-type="city"
					data-modal-title="<?= $txt_create ?>"
					data-toggle="modal"
					data-target="#create-plan-modal"
					><?= $txt_create ?></button>
			</div>

			<?php
			if(!empty($plans_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/plans-trash"><?= $txt_trash ?></a></div>
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
								<a href="<?= $baseurl ?>/admin/plans?sort=<?= $sort_param ?>">
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
								<a href="<?= $baseurl ?>/admin/plans?sort=<?= $sort_param ?>">
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
								<a href="<?= $baseurl ?>/admin/plans?sort=<?= $sort_param ?>">
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
								<a href="<?= $baseurl ?>/admin/plans?sort=<?= $sort_param ?>">
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
								<td class="text-nowrap"><?= $v['plan_name'] ?></td>
								<td class="text-nowrap shrink"><?= $v['plan_type'] ?></td>
								<td class="text-nowrap shrink"><?= $v['plan_price'] ?></td>
								<td class="text-nowrap shrink">
									<?php
									if($v['plan_status'] == 0) {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-light btn-sm toggle-plan-status"
												id="toggle-plan-<?= $v['plan_id'] ?>"
												data-plan-id="<?= $v['plan_id'] ?>"
												data-plan-status="off">
												<i class="las la-toggle-off" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-success btn-sm toggle-plan-status"
												id="toggle-plan-<?= $v['plan_id'] ?>"
												data-plan-id="<?= $v['plan_id'] ?>"
												data-plan-status="on">
												<i class="las la-toggle-on" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									?>
									<span id="edit-plan-<?= $v['plan_id'] ?>" data-toggle="tooltip" title="<?= $txt_edit_plan ?>">
										<button class="btn btn-light btn-sm edit-plan-btn"
											data-plan-id="<?= $v['plan_id'] ?>"
											data-toggle="modal"
											data-target="#edit-plan-modal">
											<i class="las la-pen"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_remove_plan ?>">
										<button class="btn btn-light btn-sm remove-plan"
											data-plan-id="<?= $v['plan_id'] ?>">
											<i class="lar la-trash-alt" aria-hidden="true"></i>
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
				<div class="d-flex">
					<div class="flex-grow-1"></div>
					<div><a href="<?= $baseurl ?>/admin/plans-trash"><?= $txt_trash ?></a></div>
				</div>
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Create plan modal -->
<div id="create-plan-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_create ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-create-plan" method="post">
					<div class="form-group">
						<label class="label" for="plan_name"><strong><?= $txt_plan_name ?></strong></label>
						<input type="text" id="plan_name" name="plan_name" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_type"><?= $txt_plan_type ?></label>
						<select id="plan_type" name="plan_type" class="form-control">
							<option value="free"><?= $txt_free ?></option>
							<option value="free_feat"><?= $txt_free_featured ?></option>
							<option value="one_time"><?= $txt_one_time ?></option>
							<option value="one_time_feat"><?= $txt_one_time_f ?></option>
							<option value="monthly"><?= $txt_monthly ?></option>
							<option value="monthly_feat"><?= $txt_monthly_f ?></option>
							<option value="annual"><?= $txt_annual ?></option>
							<option value="annual_feat"><?= $txt_annual_f ?></option>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="plan_period"><?= $txt_period ?></label>
						<input type="number" id="plan_period" name="plan_period" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_order"><?= $txt_order ?></label>
						<input type="number" id="plan_order" name="plan_order" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_price"><?= $txt_plan_price ?></label>
						<input type="number" id="plan_price" name="plan_price" class="form-control">
					</div>

					<div class="form-group">
						<label class="label" for="plan_features"><?= $txt_features ?></label>
						<textarea id="plan_features" name="plan_features" class="form-control" rows="5"></textarea>
					</div>

					<div class=""><?= $txt_plan_status ?></div>
					<div class="form-check form-check-inline">
						<input type="radio" id="plan_status1" class="form-check-input" name="plan_status" value="1">
						<label class="form-check-label" for="plan_status1"><?= $txt_enabled ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="plan_status2" class="form-check-input" name="plan_status" value="0">
						<label class="form-check-label" for="plan_status2"><?= $txt_disabled ?></label>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="create-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="create-plan-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Edit plan modal -->
<div id="edit-plan-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_edit_plan ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button id="edit-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="edit-plan-submit" class="btn btn-primary btn-sm"><?= $txt_save ?></button>
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
Create plan
--------------------------------------------------*/
(function(){
	var do_reload = false;

	// on hide modal
	$('#create-plan-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// process create plan
    $('#create-plan-submit').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-plan.php';

		// post
		$.post(post_url, { params: $('#form-create-plan').serialize() }, function(data) {
				// define response message to show
				var response = data == '1' ? '<?= $txt_plan_created ?>' : data;

				if(data == '1') {
					// set reload to true
					do_reload = true;

					// remove submit button
					$('#create-plan-submit').remove();

					// change cancel button to ok
					$('#create-cancel').empty().text('<?= $txt_ok ?>');
				}

				// show response
				$('#create-plan-modal .modal-body').html(response);
			}
		);
    });
}());

/*--------------------------------------------------
Edit plan
--------------------------------------------------*/
(function(){
	// only reload if form is submitted
	var do_reload = false;

	// on hide modal
	$('#edit-plan-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// get plan details
	$('#edit-plan-modal').on('show.bs.modal', function (e) {
		// vars
		var button = $(e.relatedTarget);
		var plan_id = button.data('plan-id');
		var post_url = '<?= $baseurl ?>' + '/admin/get-plan.php';

		// post
		$.post(post_url, { plan_id: plan_id }, function(data) {
				$('#edit-plan-modal .modal-body').html(data);
			}
		);
	});

	// submit edit plan modal
    $('#edit-plan-submit').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-plan.php';
		var response;

		// post
		$.post(post_url, { params: $('form.form-edit-plan').serialize() }, function(data) {
				// define response message to show
				response = data == '1' ? '<?= $txt_plan_updated ?>' : data;

				if(data == '1') {
					// set reload to true for on hide modal event
					do_reload = true;

					// remove submit button
					$('#edit-plan-submit').remove();

					// change cancel button to ok
					$('#edit-cancel').empty().text('<?= $txt_ok ?>');
				}

				// show response
				$('#edit-plan-modal .modal-body').html(response);
			}
		);
    });
}());

/*--------------------------------------------------
Plan status
--------------------------------------------------*/
(function(){
	$('.toggle-plan-status').on('click', function(e) {
		e.preventDefault();

		// vars
		var plan_id     = $(this).data('plan-id');
		var post_url    = '<?= $baseurl ?>' + '/admin/process-toggle-plan-status.php';
		var plan_status = $(this).data('plan-status');

		// post
		$.post(post_url, { plan_id: plan_id, plan_status: plan_status }, function(data) {
				if(data == 'on') {
					$('#toggle-plan-' + plan_id).removeClass('btn-light');
					$('#toggle-plan-' + plan_id).addClass('btn-success');
					$('#toggle-plan-' + plan_id + ' i').removeClass('la-toggle-off');
					$('#toggle-plan-' + plan_id + ' i').addClass('la-toggle-on');
					$('#toggle-plan-' + plan_id).data('plan-status', 'on');
				}

				if(data == 'off') {
					$('#toggle-plan-' + plan_id).removeClass('btn-success');
					$('#toggle-plan-' + plan_id).addClass('btn-light');
					$('#toggle-plan-' + plan_id + ' i').removeClass('la-toggle-on');
					$('#toggle-plan-' + plan_id + ' i').addClass('la-toggle-off');
					$('#toggle-plan-' + plan_id).data('plan-status', 'off');
				}
			}
		);
	});
}());

/*--------------------------------------------------
Remove plan
--------------------------------------------------*/
(function(){
	$('.remove-plan').on('click', function(e){
		e.preventDefault();

		// vars
		var plan_id = $(this).data('plan-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-plan.php';
		var num_rows = $('.total-rows').text();
		var wrapper = '#tr-plan-' + plan_id;

		// post
		$.post(post_url, { plan_id: plan_id	},	function(data) {
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
</script>

</body>
</html>