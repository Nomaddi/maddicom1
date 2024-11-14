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
				<a href="" class="create-cat-btn btn btn-light btn-sm"
					data-loc-type="city"
					data-modal-title="<?= $txt_create_cat ?>"
					data-toggle="modal"
					data-target="#create-cat-modal"
					><?= $txt_create_cat ?></a>
			</div>

			<?php
			if(!empty($cats_arr)) {
				?>
				<div class="d-flex rows-line">
					<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></div>
					<div class=""><a href="<?= $baseurl ?>/admin/categories-trash"><?= $txt_trash ?></a></div>
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
								<a href="<?= $baseurl ?>/admin/categories?sort=<?= $sort_param ?>">
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
								<a href="<?= $baseurl ?>/admin/categories?sort=<?= $sort_param ?>">
								<?= $txt_name ?>
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
								$sort_param = 'parent';
								if($sort == 'parent') $sort_param = 'parent-desc';
								if($sort == 'parent-desc') $sort_param = 'parent';
								?>
								<a href="<?= $baseurl ?>/admin/categories?sort=<?= $sort_param ?>">
								<?= $txt_parent_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'parent') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'parent-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'order';
								if($sort == 'order') $sort_param = 'order-desc';
								if($sort == 'order-desc') $sort_param = 'order';
								?>
								<a href="<?= $baseurl ?>/admin/categories?sort=<?= $sort_param ?>">
								<?= $txt_order ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'order') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'order-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($cats_arr as $k => $v) {
							?>
							<tr id="tr-cat-<?= $v['cat_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['cat_id'] ?></td>
								<td class="text-nowrap">
									<a href="<?= $baseurl ?>/listings/<?= $v['cat_slug'] ?>"><?= $v['cat_name'] ?></a>
								</td>
								<td class="text-nowrap shrink">
									<?= $v['cat_parent_id'] ?>
								</td>
								<td class="text-nowrap shrink">
									<?= $v['cat_order'] ?>
								</td>
								<td class="text-nowrap shrink">
									<span id="edit-cat-<?= $v['cat_id'] ?>" data-toggle="tooltip" title="<?= $txt_edit_cat ?>">
										<button type="button" class="btn btn-light btn-sm edit-cat-btn"
											data-cat-id="<?= $v['cat_id'] ?>"
											data-toggle="modal"
											data-target="#edit-cat-modal">
											<i class="las la-pen"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove_cat ?>">
										<button type="button" class="btn btn-light btn-sm remove-cat"
											data-remove-cat-id="<?= $v['cat_id'] ?>">
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
				<div class="d-flex">
					<div class="flex-grow-1"></div>
					<div><a href="<?= $baseurl ?>/admin/categories-trash"><?= $txt_trash ?></a></div>
				</div>
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Create category modal -->
<div id="create-cat-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_create_cat ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-create-cat" method="post">
					<div class="form-group">
						<strong><?= $txt_cat_name ?></strong>
						<input type="text" id="cat_name" class="form-control" name="cat_name" required>
					</div>

					<div class="form-group">
						<strong><?= $txt_plural_name ?></strong>
						<input type="text" id="plural_name" class="form-control" name="plural_name" required>
					</div>

					<div class="form-group">
						<strong><?= $txt_cat_slug ?></strong>
						<input type="text" id="cat_slug" class="form-control" name="cat_slug">
					</div>

					<div class="form-group">
						<strong><?= $txt_cat_icon ?></strong> <?= $txt_optional ?>
						<input type="text" id="cat_icon" class="form-control" name="cat_icon">
					</div>

					<div class="form-group">
						<strong><?= $txt_bg_color ?></strong> <?= $txt_optional ?>
						<input type="text" id="cat_bg" class="form-control" name="cat_bg">
					</div>

					<div class="form-group">
						<strong><?= $txt_cat_order ?></strong> <?= $txt_optional ?>
						<input type="text" id="cat_order" class="form-control" name="cat_order">
					</div>

					<div class="form-group">
						<strong><?= $txt_parent_cat ?></strong>
						<?= $txt_parent_explain ?><br>
						<select id="cat_parent" class="form-control" name="cat_parent">
							<option value="0"><?= $txt_no_parent ?></option>
							<?php
							// select only first 2 levels (parent = o or parent whose parent = 0)
							$modal_cats_arr = array();
							$level_0_ids = array();

							$query = "SELECT * FROM cats WHERE parent_id = 0 AND cat_status = 1 ORDER BY name";
							$stmt = $conn->prepare($query);
							$stmt->execute();

							while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$cur_loop_array = array( 'id' => $row['id'], 'name' => $row['name'] );
								$modal_cats_arr[] = $cur_loop_array;
								$level_0_ids[] = $row['id'];
							}

							$in = '';
							foreach($level_0_ids as $k => $v) {
								if($k != 0) {
									$in .= ',';
								}
								$in .= "$v";
							}

							if(!empty($in)) {
								$query = "SELECT * FROM cats WHERE parent_id IN($in) AND cat_status = 1 ORDER BY name";
								$stmt = $conn->prepare($query);
								$stmt->execute();

								while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									$modal_cats_arr[] = array('id' => $row['id'], 'name' => $row['name']);
								}

								function cmp($a, $b) {
									return strcasecmp ($a['name'], $b['name']);
								}
								usort($modal_cats_arr, 'cmp');

								foreach($modal_cats_arr as $k => $v) {
									?>
									<option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>

					<div class="form-group" id="cat-img-row">
						<input type="file" id="cat_img" name="cat_img" style="display:block;visibility:hidden;width:0;height:0;">
						<input type="hidden" id="uploaded_img" name="uploaded_img" value="">

						<strong><?= $txt_cat_img ?></strong>
						<div class="mb-3">
							<div id="cat-img-wrapper" class="pt-3"></div>
						</div>

						<div class="mb-3">
							<span id="upload-cat-img" class="btn btn-light btn-sm" role="button" style="cursor:pointer"><i class="las la-upload"></i> <?= $txt_upload ?></span>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" id="create-cat-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="submit" id="create-cat-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Edit category modal -->
<div id="edit-cat-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_edit_cat ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button type="button" id="edit-cat-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="submit" id="edit-cat-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
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
var num_items = <?= count($cats_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Create category
--------------------------------------------------*/
(function(){
	// cache selectors
	var btn_submit = $('#create-cat-submit');

	// only reload if form is submitted
	var do_reload = false;

	// on hide modal
	$('#create-cat-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// on show modal
	$('#create-cat-modal').on('show.bs.modal', function (e) {
		// reset submit button
		btn_submit.html('<?= $txt_submit ?>');
		btn_submit.prop('disabled', false);
	});

	// on submit create cat
    btn_submit.on('click', function(e){
		e.preventDefault();

		// check validity
		if($('#form-create-cat')[0].checkValidity()) {
			// show submit spinner
			btn_submit.prepend('<i class="las la-circle-notch la-spin"></i> ');
			btn_submit.prop('disabled', true);

			// vars
			var post_url = '<?= $baseurl ?>' + '/admin/process-create-cat.php';

			// post
			var request = $.post(post_url, { params: $('#form-create-cat').serialize() });

			// done
			request.done(function(data) {
				// set reload to true
				do_reload = true;

				// hide submit button
				btn_submit.hide();

				// define response message to show
				var response = data == '1' ? '<?= $txt_cat_created ?>' : data;

				// change cancel button to ok
				$('#create-cat-cancel').html('<?= $txt_ok ?>');

				// show response
				$('#create-cat-modal .modal-body').html(response);
			});
		}

		else {
			$('#form-create-cat')[0].reportValidity();
		}
	});

	// upload category image
	$('#upload-cat-img').on('click', function(e){
		e.preventDefault();

		// trigger click
		$('#cat_img').trigger('click');
	});

	// upload img
	$('#cat_img').on('change', function(e) {
		// vars
		var fileInput = document.getElementById('cat_img');
		var file = fileInput.files[0];

		// append file input to form data
		var formData = new FormData();
		formData.append('cat_img', file);

		// post
		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-cat-img.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// show loader
				$('#cat-img-wrapper').html('<i class="las la-circle-notch la-spin"></i>');
				$('#upload-cat-img').prop('disabled', true);
			},
			success: function(data) {
				// parse json string
				var data = JSON.parse(data);

				// reenable the upload button
				$('#upload-cat-img').prop('disabled', false);

				// check if previous upload failed
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// remove current category img
				$('#cat-img-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var cat_img = '<img src="' + data.message + '" width="180" class="rounded">';

					// display uploaded pic's thumb
					$('#cat-img-wrapper').append(cat_img);

					// add hidden input field
					$('#uploaded_img').val(data.filename);
				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#cat-img-wrapper').text(data.message);
				}
			},
			error: function(e) {
				// show error
				$('<div id="upload-failed"></div>').appendTo('#cat-img-wrapper').text(e);

				// reenable the upload button
				$('#upload-cat-img').prop('disabled', false);
			}
		});
    });
}());

/*--------------------------------------------------
Edit category
--------------------------------------------------*/
(function(){
	// cache selectors
	var btn_submit = $('#edit-cat-submit');

	// only reload if form is submitted
	var do_reload = false;

	// on hide modal
	$('#edit-cat-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// on show modal
	$('#edit-cat-modal').on('show.bs.modal', function (e) {
		// vars
		var button = $(e.relatedTarget);
		var cat_id = button.data('cat-id');
		var post_url = '<?= $baseurl ?>' + '/admin/get-cat.php';

		// reset submit button
		btn_submit.show();
		btn_submit.html('<?= $txt_submit ?>');
		btn_submit.prop('disabled', false);

		// reset cancel button
		$('#edit-cat-cancel').html('<?= $txt_cancel ?>');

		// post
		var request = $.post(post_url, { cat_id: cat_id });

		// done
		request.done(function(data) {
			$('#edit-cat-modal .modal-body').html(data);

			// bind events to img upload button
			bindEvents();
		});
	});

	// process edit category
    btn_submit.on('click', function(e){
		e.preventDefault();

		// check validity
		if($('.form-edit-cat')[0].checkValidity()) {
			// show submit spinner
			btn_submit.prepend('<i class="las la-circle-notch la-spin"></i> ');
			btn_submit.prop('disabled', true);

			// vars
			var post_url = '<?= $baseurl ?>' + '/admin/process-edit-cat.php';

			// post
			var request = $.post(post_url, { params: $('form.form-edit-cat').serialize() });

			request.done(function(data) {
				// change cancel button to ok
				$('#edit-cat-cancel').html('<?= $txt_ok ?>');

				// hide submit button
				btn_submit.hide();

				if(data == '1') {
					// set reload to true for on hide modal event
					do_reload = true;

					// show response
					$('#edit-cat-modal .modal-body').html('<?= $txt_cat_edited ?>');
				} else {
					// show response
					$('#edit-cat-modal .modal-body').html(data);
				}
			});
		}
    });

	// function that will bind events to the cat img upload button in the edit category modal
	function bindEvents() {
		// generate a click on the hidden input file field
		$('#edit-upload-cat-img').on('click', function(e){
			e.preventDefault();
			$('#edit_cat_img').trigger('click');
		});

		$('#edit_cat_img').on('change', function(e) {
			// vars
			var fileInput = document.getElementById('edit_cat_img');
			var file = fileInput.files[0];
			var formData = new FormData();
			formData.append('cat_img', file);

			// post
			$.ajax({
				url: "<?= $baseurl ?>/admin/process-upload-cat-img.php",
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData:false,
				beforeSend : function() {
					// show spinner
					$('#edit-cat-img-wrapper').html('<i class="las la-circle-notch la-spin"></i> ');
					$('#edit-upload-cat-img').prop('disabled', true);
				},
				success: function(data) {
					// parse json string
					var data = JSON.parse(data);

					// reenable the upload button
					$('#edit-upload-cat-img').prop('disabled', false);

					// check if previous upload failed
					// #upload_failed div created by onSumit function above
					if ($('#edit-upload-failed').length){
						$('#edit-upload-failed').remove();
					}

					// remove current profile pic
					$('#edit-cat-img-wrapper').empty();

					if(data.result == 'success') {
						// create thumbnail src
						var cat_img = '<img src="' + data.message + '" width="180" class="rounded">';

						// display uploaded pic's thumb
						$('#edit-cat-img-wrapper').append(cat_img);

						// add hidden input field
						$('#edit_uploaded_img').val(data.filename);
					}

					else {
						$('<div id="edit-upload-failed"></div>').appendTo('#edit-cat-img-wrapper').html(data.message);
					}
				},
				error: function(e) {
						$('<div id="edit-upload-failed"></div>').appendTo('#edit-cat-img-wrapper').html(e);
				}
			});
		});
	}
}());

/*--------------------------------------------------
Remove category
--------------------------------------------------*/
(function(){
	$('.remove-cat').on('click', function(e) {
		e.preventDefault();

		// vars
		var cat_id = $(this).data('remove-cat-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-cat.php';
		var num_rows = $('.total-rows').text();
		var wrapper = '#tr-cat-' + cat_id;

		// post
		var request = $.post(post_url, { cat_id: cat_id });

		// done
		request.done(function(data) {
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
</script>
</body>
</html>