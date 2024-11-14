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

			<!-- Show -->
			<div class="mb-3">
				<strong><?= $txt_show ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/custom-fields?show=fields" class="btn btn-sm btn-light <?= $show == 'fields' ? 'font-weight-bold' : '' ?>"><?= $txt_fields ?></a>
				<a href="<?= $baseurl ?>/admin/custom-fields?show=groups" class="btn btn-sm btn-light <?= $show == 'groups' ? 'font-weight-bold' : '' ?>"><?= $txt_groups ?></a>
			</div>

			<!-- Action -->
			<div class="mb-3">
				<strong><?= $txt_action ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/create-custom-field" class="btn btn-light btn-sm"><?= $txt_create_field ?></a>
				<a href="" class="btn btn-light btn-sm" data-toggle="modal"
					data-target="#modal-create-group"><?= $txt_create_group ?></a>
			</div>

			<!-- Filter -->
			<?php
			if($show == 'fields') {
				?>
				<div class="mb-3">
					<strong><?= $txt_filter ?>:</strong><br>
					<form action="<?= $baseurl ?>/admin/custom-fields" method="get">
						<div class="row no-gutters">
							<div class="col-sm-6 col-md-4 col-lg-3">
								<select id="select-category" name="filter-cat" class="form-control form-control-sm">
									<option value="0"><?= $txt_all_categories ?></option>
									<?php get_children(0, $filter_cat, 0, $conn) ?>
								</select>
							</div>

							<div class="col-sm-6 col-md-4 col-lg-3 ml-1">
								<?php
								if(!empty($custom_fields_groups)) {
									?>
									<select id="select-group" name="filter-group" class="form-control form-control-sm">
										<option value="0"><?= $txt_all_groups ?></option>
										<?php
										foreach($custom_fields_groups as $v) {
											?>
											<option value="<?= $v['group_id'] ?>" <?= $v['group_id'] == $filter_group ? 'selected' : '' ?>><?= $v['group_name'] ?></option>
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
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>"><?= $txt_trash ?></a></div>
					</div>

					<ul id="sortable-fields" class="list-group">
						<?php
						foreach($custom_fields as $k => $v) {
							?>
							<li id="li-field-<?= $v['field_id'] ?>" class="list-group-item" data-id="<?= $v['field_id'] ?>">
								<div class="d-flex">
									<div class="cursor-grab"><i class="las la-grip-vertical"></i>&nbsp;&nbsp;</div>
									<div class="bd-highlight text-nowrap mr-3"><?= $v['field_name'] ?></div>
									<div class="flex-grow-1 text-nowrap"><small><?= $v['group_name'] ?></small></div>
									<div class="text-nowrap">
										<span data-toggle="tooltip" title="<?= $txt_edit_field ?>">
											<a href="<?= $baseurl ?>/admin/edit-custom-field?id=<?= $v['field_id'] ?>" class="btn btn-light btn-sm edit-field-btn">
												<i class="las la-pen"></i>
											</a>
										</span>

										<span data-toggle="tooltip" title="<?= $txt_remove_field ?>">
											<button class="btn btn-light btn-sm remove-field"
												data-field-id="<?= $v['field_id'] ?>">
												<i class="lar la-trash-alt" aria-hidden="true"></i>
											</button>
										</span>
									</div>
								</div>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}

				else {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"></div>
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>"><?= $txt_trash ?></a></div>
					</div>
					<div><?= $txt_no_results ?></div>
					<?php
				}
			}

			if($show == 'groups') {
				if(!empty($custom_fields_groups)) {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows_groups ?></strong></span></div>
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>"><?= $txt_trash ?></a></div>
					</div>

					<ul id="sortable-groups" class="list-group">
						<?php
						foreach($custom_fields_groups as $k => $v) {
							?>
							<li id="li-group-<?= $v['group_id'] ?>" class="list-group-item" data-id="<?= $v['group_id'] ?>">
								<div class="d-flex">
									<div class="cursor-grab"><i class="las la-grip-vertical"></i>&nbsp;&nbsp;</div>
									<div class="flex-grow-1 bd-highlight"><?= $v['group_name'] ?> <span class="badge badge-secondary"><?= $v['group_id'] == 1 ? $txt_default_group : '' ?></span></div>
									<div class="text-nowrap">
										<!-- edit group -->
										<span data-toggle="tooltip" title="<?= $txt_edit_group ?>">
											<button class="btn btn-light btn-sm"
												data-group-id="<?= $v['group_id'] ?>"
												data-toggle="modal"
												data-target="#edit-group-modal">
												<i class="las la-pen"></i>
											</button>
										</span>

										<span data-toggle="tooltip" title="<?= $txt_remove_group ?>">
											<button class="btn btn-light btn-sm remove-group"
												data-group-id="<?= $v['group_id'] ?>" <?= $v['group_id'] == 1 ? 'disabled' : '' ?>>
												<i class="lar la-trash-alt" aria-hidden="true"></i>
											</button>
										</span>
									</div>
								</div>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}

				else {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"></div>
						<div><a href="<?= $baseurl ?>/admin/custom-fields-trash?show=<?= $show ?>"><?= $txt_trash ?></a></div>
					</div>
					<div><?= $txt_no_results ?></div>
					<?php
				}
			}
			?>
		</div>
	</div>
</div>

<!-- Create group modal -->
<div class="modal fade" id="modal-create-group" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_create_group ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form id="form-create-group" class="form-create-group" method="post">
					<div class="form-group">
						<label class="label" for="group-name"><?= $txt_group_name ?></label>
						<input type="text" id="group-name" class="form-control" name="group_name" required>
					</div>

					<?php
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							?>
							<div class="form-group">
								<label class="label" for="group-name-<?= $v ?>">
									<?= $txt_group_name ?>:
									<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
								</label>
								<input type="text" id="group-name-<?= $v ?>" class="form-control" name="tr_group_name[<?= $v ?>]">
							</div>
							<?php
						}
					}
					?>

					<div class="form-group">
						<label class="label" for="group-order"><?= $txt_group_order ?></label>
						<input type="text" id="group-order" class="form-control" name="group_order" required>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm create-cancel" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm modal-create-submit"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Edit group modal -->
<div class="modal fade" id="edit-group-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_edit_group ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form class="edit-group-form" method="post">
					<input type="hidden" id="edit-group-id" name="group_id">

					<div class="form-group">
						<label class="label" for="group-name"><?= $txt_group_name ?></label>
						<input type="text" id="edit-group-name" class="form-control" name="group_name" required>
					</div>

					<?php
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							?>
							<div class="form-group">
								<label class="label" for="edit-group-name-<?= $v ?>">
									<?= $txt_group_name ?>:
									<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
								</label>
								<input type="text" id="edit-group-name-<?= $v ?>" class="form-control" name="tr_group_name[<?= $v ?>]">
							</div>
							<?php
						}
					}
					?>

					<div class="form-group">
						<label class="label" for="group-order"><?= $txt_group_order ?></label>
						<input type="text" id="edit-group-order" class="form-control" name="group_order" required>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm edit-cancel" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm edit-group-submit"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- Javascript -->
<script src="<?= $baseurl ?>/templates/js/SortableJS/Sortable.min.js"></script>

<script>
'use strict';

<?php
if($show == 'fields' && !empty($custom_fields)) {
?>
/*--------------------------------------------------
Sort fields
--------------------------------------------------*/
(function(){
	var el = document.getElementById("sortable-fields");
	var sortable = new Sortable(el, {
		animation: 150,
		onEnd: function (evt) {
			var order = sortable.toArray().toString();

			// post
			var post_url = '<?= $baseurl ?>' + '/admin/process-order-custom-fields.php';

			$.post(post_url, { show: 'fields', fields_order: order }, function(data) {
					// log
					console.log(data);
				}
			);
		}
	});
}());
<?php
}

if($show == 'groups' && !empty($custom_fields_groups)) {
?>
/*--------------------------------------------------
Sort groups
--------------------------------------------------*/
(function(){
	var el = document.getElementById("sortable-groups");
	var sortable = new Sortable(el, {
		animation: 150,
		onEnd: function (e) {
			var order = sortable.toArray().toString();

			// post
			var post_url = '<?= $baseurl ?>' + '/admin/process-order-custom-fields.php';

			$.post(post_url, { show: 'groups', groups_order: order }, function(data) {
					// log
					console.log(data);
				}
			);
		}
	});
}());
<?php
}
?>

/*--------------------------------------------------
Create group modal
--------------------------------------------------*/
(function() {
	// on hide modal
	$('#modal-create-group').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// process create loc
    $('.modal-create-submit').on('click', function(e){
		e.preventDefault();

		// modal
		var modal = $('#modal-create-group');

		// post
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-custom-field-group.php';

		$.post(post_url, { params: $('#form-create-group').serialize() }, function(data) {
				if(data == '1') {
					modal.find('.modal-body').html('<?= $txt_group_created ?>');
				} else {
					modal.find('.modal-body').html('Error: Problem creating group');
				}

				modal.find('.modal-create-submit').remove();
				modal.find('.create-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });
}());

/*--------------------------------------------------
Edit group modal
--------------------------------------------------*/
(function(){
	$('#edit-group-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var group_id = button.data('group-id');
		var modal = $(this);

		// get custom field info
		var post_url = '<?= $baseurl ?>' + '/admin/get-custom-field-group.php';

		$.post(post_url, { group_id: group_id }, function(data) {
				try {
					var data = JSON.parse(data);

					$('#edit-group-id').val(data.group_id);
					$('#edit-group-name').val(data.group_name);
					$('#edit-group-order').val(data.group_order);

					// group name translations
					<?php
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							?>
							$('#edit-group-name-<?= $v ?>').val(data.group_lang.<?= $v ?>);
							<?php
						}
					}
					?>
				} catch {
					$('#edit-group-modal .modal-body').empty().html(data);
				}
			}
		);
	});

	// process edit group
    $('.edit-group-submit').on('click', function(e){
		e.preventDefault();

		var modal = $('#edit-group-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-custom-field-group.php';
		var response;

		$.post(post_url, { params: $('form.edit-group-form').serialize() }, function(data) {
				response = data == '1' ? 'Group edited' : data;
				modal.find('.modal-body').html(response);
				modal.find('.edit-group-submit').remove();
				modal.find('.edit-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// edit group modal on close
	$('#edit-group-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});
}());

/*--------------------------------------------------
Remove field
--------------------------------------------------*/
(function(){
	$('.remove-field').on('click', function(e){
		e.preventDefault();

		// define vars
		var field_id = $(this).data('field-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field.php';
		var num_rows  = $('.total-rows').text();
		var wrapper = '#li-field-' + field_id;

		// post
		$.post(post_url, { type: 'field', field_id: field_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					$(wrapper).fadeOut('fast');
				} else {
					$(wrapper).empty().append(data);
				}
			}
		);
	});
}());

/*--------------------------------------------------
Remove group
--------------------------------------------------*/
(function(){
	$('.remove-group').on('click', function(e){
		e.preventDefault();

		// define vars
		var group_id = $(this).data('group-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field.php';
		var num_rows  = $('.total-rows').text();
		var wrapper = '#li-group-' + group_id;

		// post
		$.post(post_url, { type: 'group', group_id: group_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// hide row
					$(wrapper).fadeOut('fast');
				} else {
					$(wrapper).empty().append(data);
				}
			}
		);
	});
}());

/*--------------------------------------------------
Filters
--------------------------------------------------*/
(function(){
	// category
	$('#select-category').on('change', function(e){
		$(this).closest('form').submit();
	});

	// group
	$('#select-group').on('change', function(e){
		$(this).closest('form').submit();
	});
}());
</script>

</body>
</html>