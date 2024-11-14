<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_show_html_title ?></title>
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
			<h2 class="mb-5"><?= $txt_show_html_title ?></h2>

			<!-- Search form -->
			<div class="mb-3">
				<strong><?= $txt_category ?>:</strong><br>
				<form class="form-inline" action="<?= $baseurl ?>/admin/custom-fields" method="get">
					<select id="select-category" name="cat" class="form-control form-control-sm">
						<option value="0"><?= $txt_view_all ?></option>
						<?php get_children(0, $cat_id, 0, $conn) ?>
					</select>
				</form>
			</div>

			<div class="mb-3">
				<strong><?= $txt_action ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/create-custom-field" class="btn btn-light btn-sm">
					<?= $txt_show_create_field ?>
				</a>
			</div>

			<?php
			if(!empty($custom_fields)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/custom-fields-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_field_name ?></th>
							<th><?= $txt_field_type ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($custom_fields as $k => $v) {
							$field_id   = $v['field_id'];
							$field_name = $v['field_name'];
							$field_type = $v['field_type'];
							?>
							<tr id="field-<?= $field_id ?>">
								<td><?= $field_id ?></td>
								<td><?= $field_name ?></td>
								<td><?= $field_type ?></td>
								<td class="text-nowrap">
									<span data-toggle="tooltip" title="<?= $txt_show_edit_field ?>">
										<a href="<?= $baseurl ?>/admin/edit-custom-field?id=<?= $field_id ?>" class="btn btn-light btn-sm edit-field-btn">
											<i class="fas fa-pencil-alt"></i>
										</a>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_show_remove_field ?>">
										<button class="btn btn-light btn-sm remove-field"
											data-field-id="<?= $field_id ?>">
											<i class="far fa-trash-alt" aria-hidden="true"></i>
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
				<div class="mt-5 mb-3">
					<?= $txt_no_results ?>
				</div>
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
(function(){
	// remove field
	$('.remove-field').on('click', function(e){
		e.preventDefault();
		var field_id = $(this).data('field-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field.php';
		var wrapper = '#field-' + field_id;
		$.post(post_url, {
			field_id: field_id
			},
			function(data) {
				if(data) {
					$(wrapper).empty();

					// subtract from total rows count
					var total = $('.total-rows').html();

					$('.total-rows').html(total-1);
				}
			}
		);
	});

	// select category change
	$('#select-category').on('change', function(e){
		$(this).closest('form').submit();
	});
}());
</script>

</body>
</html>