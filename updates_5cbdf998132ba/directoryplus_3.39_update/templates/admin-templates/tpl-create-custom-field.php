<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once(__DIR__ . '/admin-head.php') ?>

<style>
.show-cats {
	margin-left: 0;
	padding-left: 0;
	list-style-type: none;
	-webkit-column-count: 2;
	-moz-column-count: 2;
	column-count: 2;
	-webkit-column-gap: 20px;
	column-gap: 20px;
	-moz-column-gap: 20px;
}

#cat-checkboxes ul {
	list-style-type: none;
}
</style>
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

			<div class="result"></div>

			<form class="form-create-custom-field" method="post">
				<div class="form-group">
					<label class="label" for="field_name"><strong><?= $txt_field_name ?></strong></label>
					<input type="text" id="field_name" class="form-control" name="field_name" required>
				</div>

				<?php
				if(!empty($cfg_languages) && is_array($cfg_languages)) {
					foreach($cfg_languages as $v) {
						?>
						<div class="form-group">
							<label class="label" for="custom_field_lang_<?= $v ?>">
								<?= $txt_field_name ?>:
								<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
							</label>
							<input type="text" id="custom_field_lang_<?= $v ?>" class="form-control" name="custom_field_lang[<?= $v ?>]">
						</div>
						<?php
					}
				}
				?>

				<div class="form-group">
					<label class="label" for="field_type"><strong><?= $txt_field_type ?></strong></label>
					<select id="field_type" class="form-control" name="field_type" required>
						<option value="checkbox"><?= $txt_type_check ?></option>
						<option value="multiline"><?= $txt_type_multiline ?></option>
						<option value="radio"><?= $txt_type_radio ?></option>
						<option value="select"><?= $txt_type_select ?></option>
						<option value="text"><?= $txt_type_text ?></option>
						<option value="url"><?= $txt_type_url ?></option>
					</select>
				</div>

				<div class="form-group">
					<label class="label" for="field_type"><strong><?= $txt_filter_display ?></strong></label>
					<select id="filter_display" class="form-control" name="filter_display">
						<option value="text"><?= $txt_type_text ?></option>
						<option value="radio"><?= $txt_type_radio ?></option>
						<option value="select"><?= $txt_type_select ?></option>
						<option value="checkbox"><?= $txt_type_check ?></option>
						<option value="range_text"><?= $txt_range_text ?></option>
						<option value="range_select"><?= $txt_range_select ?></option>
						<option value="range_number"><?= $txt_range_number ?></option>
					</select>
				</div>

				<div class="form-group">
					<label class="label" for="tooltip"><strong><?= ee($txt_tooltip) ?></strong></label>
					<input type="text" id="tooltip" class="form-control" name="tooltip">
				</div>

				<?php
				if(!empty($cfg_languages) && is_array($cfg_languages)) {
					foreach($cfg_languages as $v) {
						?>
						<div class="form-group">
							<label class="label" for="tooltip_lang_<?= $v ?>">
								<?= $txt_tooltip ?>:
								<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
							</label>
							<input type="text" id="tooltip_lang_<?= $v ?>" class="form-control" name="tooltip_lang[<?= $v ?>]">
						</div>
						<?php
					}
				}
				?>

				<div class="form-group">
					<label class="label" for="icon"><strong><?= $txt_icon ?></strong> (<?= $txt_optional ?>)</label>
					<input type="text" id="icon" class="form-control" name="icon">
				</div>

				<div class="form-group">
					<label class="label" for="values_list"><?= ee($txt_values_list) ?></label>
					<input type="text" id="values_list" class="form-control" name="values_list">
				</div>

				<?php
				if(!empty($cfg_languages) && is_array($cfg_languages)) {
					foreach($cfg_languages as $v) {
						?>
						<div class="form-group">
							<label class="label" for="values_list">
								<?= $txt_values_list ?>:
								<span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span>
							</label>

							<input type="text" id="values_list" class="form-control" name="values_list_lang[<?= $v ?>]">
						</div>
						<?php
					}
				}
				?>

				<div class="form-group">
					<label class="label" for="value_unit"><strong><?= $txt_value_unit ?></strong></label>
					<input type="text" id="value_unit" class="form-control" name="value_unit">
				</div>

				<div class="form-group">
					<p><strong><?= $txt_options ?></strong></p>
					<label class="mr-2"><input type="checkbox" id="required" name="required" value="1"> <?= $txt_required ?></label>
					<label><input type="checkbox" id="searchable" name="searchable" value="1"> <?= $txt_searchable ?></label>
				</div>

				<div class="form-group">
					<label class="label" for="values_list"><strong><?= $txt_show_in_results ?></strong></label>
					<select id="custom-field-group" name="show_in_results" class="form-control">
						<option value="no"><?= $txt_no ?></option>
						<option value="name"><?= $txt_show_name ?></option>
						<option value="icon"><?= $txt_show_icon ?></option>
						<option value="name-icon"><?= $txt_show_name_icon ?></option>
					</select>
				</div>

				<div class="form-group">
					<label class="label" for="field_type"><strong><?= $txt_field_group ?></strong></label>
					<select id="custom-field-group" name="field_group" class="form-control">
						<?php
						if(!empty($custom_fields_groups)) {
							foreach($custom_fields_groups as $v) {
								?>
								<option value="<?= $v['group_id'] ?>"><?= $v['group_name'] ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>

				<div class="form-group">
					<label class="label" for="field_order"><strong><?= $txt_field_order ?></strong></label>
					<input type="number" id="field_order" class="form-control" name="field_order">
				</div>

				<div class="form-group">
					<div><strong><?= $txt_categories ?></strong></div>

					<input type="checkbox" id="select_all" name="select_all"> <label for="select_all"><?= $txt_select_all ?></label>

					<?php
					// group by parents
					$cats_grouped_by_parent = group_cats_by_parent($cats_arr);
					// send bogus non empty array so that the show_cats() function returns checkboxes not checked
					$empty_arr = array('bogus');
					show_cats($cats_grouped_by_parent, 0, $empty_arr, 1);
					?>
				</div>

				<div class="form-group">
					<input type="submit" id="submit" name="submit" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

(function() {
	// toggle categories checkboxes
	$('#select_all').on('click', function(e){
		var checkedStatus = this.checked;
		$('#cat-checkboxes').find(':checkbox').each(function() {
			$(this).prop('checked', checkedStatus);
		});
	});

	// submit form
    $('#submit').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-custom-field.php';

		// post
		$.post(post_url, { params: $('form.form-create-custom-field').serialize() }, function(data) {
				// define response message to show
				var response = data == '1' ? '<?= $txt_field_created ?>' : data;

				$('html, body').animate({scrollTop : 0},360);
				$('.form-create-custom-field').empty();
				$('.result').empty().html(response);
			}
		);
    });
}());
</script>
</body>
</html>