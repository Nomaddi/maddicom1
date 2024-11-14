<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $txt_trash ?></title>
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
			<h2 class="mb-5"><?= $txt_main_title ?> - <?= $txt_trash ?></h2>

			<!-- Show -->
			<div class="mb-3">
				<strong><?= $txt_show ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=fields" class="btn btn-sm btn-light <?= $show == 'fields' ? 'font-weight-bold' : '' ?>"><?= $txt_fields ?></a>
				<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=groups" class="btn btn-sm btn-light <?= $show == 'groups' ? 'font-weight-bold' : '' ?>"><?= $txt_groups ?></a>
			</div>

			<?php
			if($show == 'fields') {
				?>
				<!-- Filter -->
				<div class="mb-3">
					<strong><?= $txt_filter ?>:</strong><br>
					<form action="<?= $baseurl ?>/admin/custom-fields" method="get">
						<div class="row no-gutters">
							<div class="col-sm-6 col-md-4 col-lg-3">
								<select id="select-category" name="filter-category" class="form-control form-control-sm">
									<option value="0"><?= $txt_category ?></option>
									<?php get_children(0, $cat_id, 0, $conn) ?>
								</select>
							</div>

							<div class="col-sm-6 col-md-4 col-lg-3 ml-1">
								<?php
								if(!empty($custom_fields_groups)) {
									?>
									<select id="select-group" name="filter-group" class="form-control form-control-sm">
										<option value="0"><?= $txt_group ?></option>
										<?php
										foreach($custom_fields_groups as $v) {
											?>
											<option value="<?= $v['group_id'] ?>"><?= $v['group_name'] ?></option>
											<?php
										}
										?>
									</select>
									<?php
								}
								?>
							</div>
							<div class="col-sm-6 col-md-2 col-lg-3"></div>
							<div class="col-sm-6 col-md-2 col-lg-3"></div>
						</div>
					</form>
				</div>
				<?php
			}
			?>

			<?php
			if($show == 'fields') {
				if(!empty($custom_fields)) {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows_fields ?></strong></span></div>
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
					</div>

					<div class="table-responsive">
						<table class="table">
							<tr>
								<th class="text-nowrap">
									<?php
									$sort_param = 'date';
									if($sort == 'date') $sort_param = 'date-desc';
									if($sort == 'date-desc') $sort_param = 'date';
									?>
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_id ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'date') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

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
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_field_name ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'name') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'name-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

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
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_field_type ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'type') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'type-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

									echo $sort_icon;
									?>
									</a>
								</th>
								<th class="text-nowrap">
									<?php
									$sort_param = 'group';
									if($sort == 'group') $sort_param = 'group-desc';
									if($sort == 'group-desc') $sort_param = 'group';
									?>
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_group ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'group') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'group-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

									echo $sort_icon;
									?>
									</a>
								</th>
								<th class="text-nowrap"><?= $txt_action ?></th>
							</tr>
							<?php
							foreach($custom_fields as $k => $v) {
								?>
								<tr id="tr-field-<?= $v['field_id'] ?>">
									<td class="text-nowrap shrink"><?= $v['field_id'] ?></td>
									<td class="text-nowrap"><?= $v['field_name'] ?></td>
									<td class="text-nowrap shrink"><?= $v['field_type'] ?></td>
									<td class="text-nowrap shrink"><?= $v['group_name'] ?></td>
									<td class="text-nowrap shrink">
										<!-- restore btn -->
										<span data-toggle="tooltip" title="<?= $txt_restore ?>">
											<button class="btn btn-light btn-sm restore-field"
												data-field-id="<?= $v['field_id'] ?>">
												<i class="fas fa-undo-alt"></i>
											</button>
										</span>

										<!-- remove btn -->
										<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
											<button class="btn btn-light btn-sm"
												data-toggle="modal"
												data-target="#remove-field-modal"
												data-field-id="<?= $v['field_id'] ?>">
												<i class="far fa-trash-alt"></i>
											</button>
										</span>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
					<?php
				}

				else {
					?>
					<div><?= $txt_no_results ?></div>
					<?php
				}
			}

			if($show == 'groups') {
				if(!empty($custom_fields_groups)) {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows_groups ?></strong></span></div>
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&show=<?= $show ?>" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
					</div>

					<div class="table-responsive">
						<table class="table groups">
							<tr>
								<th class="text-nowrap">
									<?php
									$sort_param = 'date';
									if($sort == 'date') $sort_param = 'date-desc';
									if($sort == 'date-desc') $sort_param = 'date';
									?>
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_id ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'date') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

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
									<a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>&sort=<?= $sort_param ?>">
									<?= $txt_group_name ?>
									<?php
									$sort_icon = '<i class="fas fa-sort"></i>';
									if($sort == 'name') $sort_icon = '<i class="fas fa-sort-up"></i>';
									if($sort == 'name-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

									echo $sort_icon;
									?>
									</a>
								</th>
								<th class="text-nowrap"><?= $txt_action ?></th>
							</tr>
							<?php
							foreach($custom_fields_groups as $k => $v) {
								?>
								<tr id="tr-group-<?= $v['group_id'] ?>">
									<td class="text-nowrap shrink"><?= $v['group_id'] ?></td>
									<td class="text-nowrap"><?= $v['group_name'] ?></td>
									<td class="text-nowrap shrink">
										<!-- restore btn -->
										<span data-toggle="tooltip" title="<?= $txt_restore ?>">
											<button class="btn btn-light btn-sm restore-group"
												data-group-id="<?= $v['group_id'] ?>">
												<i class="fas fa-undo-alt"></i>
											</button>
										</span>

										<!-- remove btn -->
										<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
											<button class="btn btn-light btn-sm"
												data-toggle="modal"
												data-target="#remove-group-modal"
												data-group-id="<?= $v['group_id'] ?>">
												<i class="far fa-trash-alt"></i>
											</button>
										</span>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
					<?php
				}

				else {
					?>
					<div><?= $txt_no_results ?></div>
					<?php
				}
			}
			?>
		</div>
	</div>
</div>

<!-- Remove field modal -->
<div id="remove-field-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_remove_field ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="button" class="btn btn-primary btn-sm remove-field" data-field-id data-dismiss="modal"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Remove group modal -->
<div id="remove-group-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title2"><?= $txt_remove_group ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_group_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-group" data-group-id data-dismiss="modal"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty field trash modal -->
<div id="empty-trash-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-3" class="modal-title"><?= $txt_empty ?></h5>
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

<!-- Admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<script>
'use strict';

/*--------------------------------------------------
Restore field
--------------------------------------------------*/
(function(){
    $('.restore-field').on('click', function(e){
		e.preventDefault();

		// vars
		var field_id = $(this).data('field-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-custom-field.php';
		var num_rows  = $('.total-rows').text();
		var wrapper  = '#tr-field-' + field_id;

		// post
		$.post(post_url, { type: 'field', field_id: field_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					if (new_total > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						location.reload(true);
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
Restore group
--------------------------------------------------*/
(function(){
    $('.restore-group').on('click', function(e){
		e.preventDefault();

		// vars
		var group_id   = $(this).data('group-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-custom-field.php';
		var num_rows  = $('.total-rows').text();
		var wrapper  = '#tr-group-' + group_id;

		// post
		$.post(post_url, { type: 'group', group_id: group_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					if (new_total > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						location.reload(true);
					}
				} else {
					$(wrapper).empty();
					var removed_row = $('<td colspan="3"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
    });
}());

/*--------------------------------------------------
Remove field permanently
--------------------------------------------------*/
(function(){
	// when remove field modal pops up
	$('#remove-field-modal').on('show.bs.modal', function(e) {
		// vars
		var button = $(e.relatedTarget);
		var field_id = button.data('field-id');

		// add data attribute
		$('#remove-field-modal .remove-field').attr('data-field-id', field_id);
	});

	// remove button in modal clicked
    $('.remove-field').on('click', function(e){
		e.preventDefault();

		// vars
		var field_id = $('.remove-field').attr('data-field-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-field-' + field_id;

		// post
		$.post(post_url, { type: 'field', field_id: field_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					if (new_total > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						location.reload(true);
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
Remove group permanently
--------------------------------------------------*/
(function(){
	// when remove group modal pops up
	$('#remove-group-modal').on('show.bs.modal', function(event) {
		// vars
		var button = $(event.relatedTarget);
		var group_id = button.data('group-id');

		// add data attribute
		$('#remove-group-modal .remove-group').attr('data-group-id', group_id);
	});

	// remove group button in modal clicked
    $('.remove-group').on('click', function(e){
		e.preventDefault();

		// vars
		var group_id = $('.remove-group').attr('data-group-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-group-' + group_id;

		// post
		$.post(post_url, { type: 'group', group_id: group_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					if (new_total > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						location.reload(true);
					}
				} else {
					$(wrapper).empty();
					var removed_row = $('<td colspan="3"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
    });
}());

/*--------------------------------------------------
Empty trash fields and groups
--------------------------------------------------*/
(function(){
	// only reload if form is submitted
	var do_reload = false;

	// on show modal
	$('#empty-trash-modal').on('show.bs.modal', function (e) {
		// show default message
		$('#empty-trash-modal .modal-body').empty();
		<?php
		if($show == 'fields') {
			?>
			$('#empty-trash-modal .modal-body').html('<?= $txt_remove_fields_sure ?>').fadeIn();
			<?php
		}

		if($show == 'groups') {
			?>
			$('#empty-trash-modal .modal-body').html('<?= $txt_remove_groups_sure ?>').fadeIn();
			<?php
		}
		?>
	});

	// on hide modal
	$('#empty-trash-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-custom-fields.php';

		// post
		$.post(post_url, { type: "<?= $show ?>" }, function(data) {
			console.log(data);
				if(data == 1) {
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