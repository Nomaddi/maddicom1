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
				<a href="<?= $baseurl ?>/admin/categories?sort=name" class="btn btn-light btn-sm"><?= $txt_by_name ?></a>
				<a href="<?= $baseurl ?>/admin/categories?sort=parent" class="btn btn-light btn-sm"><?= $txt_by_parent_id ?></a>
			</div>

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
				<div class="d-flex">
					<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></div>
					<div class=""><a href="<?= $baseurl ?>/admin/categories-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_name ?></th>
							<th><?= $txt_parent_id ?></th>
							<th><?= $txt_order ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($cats_arr as $k => $v) {
							$cat_id        = $v['cat_id'];
							$cat_name      = $v['cat_name'];
							$cat_parent_id = $v['cat_parent_id'];
							$cat_order     = $v['cat_order'];
							?>
							<tr id="cat-<?= $cat_id ?>">
								<td><?= $cat_id ?></td>
								<td class="text-nowrap">
									<?= $cat_name ?>
								</td>
								<td class="text-nowrap">
									<?= $cat_parent_id ?>
								</td>
								<td class="text-nowrap">
									<?= $cat_order ?>
								</td>
								<td class="text-nowrap">
									<span id="edit-cat-<?= $cat_id ?>" data-toggle="tooltip" title="<?= $txt_edit_cat ?>">
										<button class="btn btn-light btn-sm edit-cat-btn"
											data-cat-id="<?= $cat_id ?>"
											data-toggle="modal"
											data-target="#edit-cat-modal">
											<i class="fas fa-pencil-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove_cat ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-cat-modal"
											data-cat-id="<?= $cat_id ?>">
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
				<div class="mt-5 mb-3">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- modal edit category -->
<div class="modal fade" id="edit-cat-modal" tabindex="-1" role="dialog" aria-labelledby="Edit Category Modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $txt_edit_cat ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal" id="edit-cancel"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm" id="edit-cat-submit"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- modal remove category -->
<div class="modal fade" id="remove-cat-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove_cat ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_warn ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-cat" data-remove-id><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- modal create category -->
<div class="modal fade" id="create-cat-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_create_cat ?></h5>
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
						<input type="text" id="plural_name" class="form-control" name="plural_name">
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
							<div id="cat-img-wrapper"></div>
						</div>

						<div class="mb-3">
							<span id="upload-cat-img" class="btn btn-light btn-sm" role="button" style="cursor:pointer"><i class="fas fa-plus"></i> <?= $txt_upload ?></span>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" id="create-cat-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<input type="submit" id="create-cat-submit" class="btn btn-primary btn-sm">
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
/*--------------------------------------------------
Modal
--------------------------------------------------*/
(function(){
	// show edit cat modal
	$('#edit-cat-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget); // Button that triggered the modal
		var cat_id = button.data('cat-id'); // Extract info from data-* attributes
		var modal = $(this);

		var post_url = '<?= $baseurl ?>' + '/admin/get-cat.php';

		$.post(post_url, { cat_id: cat_id },
			function(data) {
				modal.find('.modal-body').html(data);
				onShowEdit();
			}
		);
	});

	// edit cat form submit
    $('#edit-cat-submit').on('click', function(e){
		e.preventDefault();
		var modal = $('#edit-cat-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-cat.php';

		$.post(post_url, {
			params: $('form.form-edit-cat').serialize(),
			},
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('#edit-cat-submit').remove();
				modal.find('#edit-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// edit cat modal on close
	$('#edit-cat-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// show remove category modal
	$('#remove-cat-modal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget); // Button that triggered the modal
		var cat_id = button.data('cat-id'); // Extract info from data-* attributes
		var modal = $(this);

		modal.find('.remove-cat').attr('data-remove-id', cat_id);
	});

	// remove category
	$('.remove-cat').on('click', function(e) {
		e.preventDefault();

		var cat_id = $(this).data('remove-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-cat.php';

		$.post(post_url, {
			cat_id: cat_id
			},
			function(data) {
				location.reload(true);
			}
		);
	});

	// create cat form submit
    $('#create-cat-submit').on('click', function(e){
		e.preventDefault();

		// check validity
		$('#form-create-cat')[0].checkValidity();
		$('#form-create-cat')[0].reportValidity();

		// if all required fields filled, process
		var modal = $('#create-cat-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-cat.php';

		$.post(post_url, {
			params: $('#form-create-cat').serialize(),
			},
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('#create-cat-submit').remove();
				modal.find('#create-cat-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// create cat modal on close
	$('#create-cat-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// initialize edit in place
	$('.editable').jinplace();
}());

/*--------------------------------------------------
Upload category image
--------------------------------------------------*/
(function() {
	// generate a click on the hidden input file field
	$('#upload-cat-img').on('click', function(e){
		e.preventDefault();
		$('#cat_img').trigger('click');
	});

	$("#cat_img").on('change', function(e) {
		// append file input to form data
		var fileInput = document.getElementById('cat_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('cat_img', file);

		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-cat-img.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="cat-img-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#cat-img-wrapper');
			},
			success: function(data) {
				// parse json string from response
				var data = JSON.parse(data);

				// check if previous upload failed
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#cat-img-preloader').remove();

				// remove current category img
				$('#cat-img-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var cat_img = '<img src="' + data.message + '" width="180">';

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
					$('<div id="upload-failed"></div>').appendTo('#cat-img-wrapper').text(e);
			}
		});
	});
}());

/*--------------------------------------------------
Upload category image on edit cat
--------------------------------------------------*/
function onShowEdit() {
	// generate a click on the hidden input file field
	$('#edit-upload-cat-img').on('click', function(e){
		e.preventDefault();
		$('#edit_cat_img').trigger('click');
	});

	$('#edit_cat_img').on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('edit_cat_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('cat_img', file);

		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-cat-img.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="edit-cat-img-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#edit-cat-img-wrapper');
			},
			success: function(data) {
				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed
				// #upload_failed div created by onSumit function above
				if ($('#edit-upload-failed').length){
					$('#edit-upload-failed').remove();
				}

				// delete preloader spinner
				$('#edit-cat-img-preloader').remove();

				// remove current profile pic
				$('#edit-cat-img-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var cat_img = '<img src="' + data.message + '" width="180">';

					// display uploaded pic's thumb
					$('#edit-cat-img-wrapper').append(cat_img);

					// add hidden input field
					$('#edit_uploaded_img').val(data.filename);
				}

				else {
					$('<div id="edit-upload-failed"></div>').appendTo('#edit-cat-img-wrapper').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="edit-upload-failed"></div>').appendTo('#edit-cat-img-wrapper').text(e);
			}
		});
	}));
}
</script>
</body>
</html>