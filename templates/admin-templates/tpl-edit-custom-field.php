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

			<div class="edit-custom-field">
				<form class="form-edit-custom-field" method="post">
					<input type="hidden" id="field_id" name="field_id" value="<?= $field_id ?>">

					<div class="form-group">
						<label class="label" for="field_name"><strong><?= $txt_field_name ?></strong></label>
						<input type="text" id="field_name" class="form-control" name="field_name" value="<?= $field_name ?>" required>
					</div>

					<?php
					// custom field name
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							$field_name = '';
							if(!empty($custom_field_lang[$v])) {
								$field_name = isset($custom_field_lang[$v]['field_name']) ? $custom_field_lang[$v]['field_name'] : '';
							}
							?>
							<div class="form-group">
								<label class="label" for="custom_field_lang_<?= $v ?>">
									<?= $txt_field_name ?>:
									<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
								</label>

								<input type="text" id="custom_field_lang_<?= $v ?>" class="form-control" name="custom_field_lang[<?= $v ?>]" value="<?= $field_name ?>">
							</div>
							<?php
						}
					}
					?>

					<div class="form-group">
						<label class="label" for="field_type"><strong><?= $txt_field_type ?></strong></label>
						<select id="field_type" name="field_type" class="form-control" required>
							<option value="text" <?= ($field_type == 'text') ? 'selected' : '' ?>>text</option>
							<option value="radio" <?= ($field_type == 'radio') ? 'selected' : '' ?>>radio</option>
							<option value="select" <?= ($field_type == 'select') ? 'selected' : '' ?>>select</option>
							<option value="checkbox" <?= ($field_type == 'checkbox') ? 'selected' : '' ?>>checkbox</option>
							<option value="multiline" <?= ($field_type == 'multiline') ? 'selected' : '' ?>>textarea</option>
							<option value="url" <?= ($field_type == 'url') ? 'selected' : '' ?>>url</option>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="field_type"><strong><?= $txt_filter_display ?></strong></label>
						<select id="filter_display" class="form-control" name="filter_display">
							<option value="text" <?= ($filter_display == 'text') ? 'selected' : '' ?>>text</option>
							<option value="radio" <?= ($filter_display == 'radio') ? 'selected' : '' ?>>radio</option>
							<option value="select" <?= ($filter_display == 'select') ? 'selected' : '' ?>>select</option>
							<option value="checkbox" <?= ($filter_display == 'checkbox') ? 'selected' : '' ?>>checkbox</option>
							<option value="range_text" <?= ($filter_display == 'range_text') ? 'selected' : '' ?>>range text</option>
							<option value="range_select" <?= ($filter_display == 'range_select') ? 'selected' : '' ?>>range select</option>
							<option value="range_number" <?= ($filter_display == 'range_number') ? 'selected' : '' ?>>range number</option>
							<option value="range_decimal" <?= ($filter_display == 'range_decimal') ? 'selected' : '' ?>>range decimal</option>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="tooltip"><strong><?= $txt_tooltip ?></strong> (<?= $txt_optional ?>)</label>
						<input type="text" id="tooltip" class="form-control" name="tooltip" value="<?= $tooltip ?>">
					</div>

					<?php
					// custom field tooltip
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							if(!empty($custom_field_lang[$v])) {
								$tooltip = isset($custom_field_lang[$v]['tooltip']) ? $custom_field_lang[$v]['tooltip'] : $tooltip;
							}
							?>
							<div class="form-group">
								<label class="label" for="tooltip_lang_<?= $v ?>">
									<?= $txt_tooltip ?>:
									<strong><span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span></strong>
								</label>

								<input type="text" id="tooltip_lang_<?= $v ?>" class="form-control" name="tooltip_lang[<?= $v ?>]" value="<?= $tooltip ?>">
							</div>
							<?php
						}
					}
					?>

					<div class="form-group">
						<label class="label" for="icon"><strong><?= $txt_icon ?></strong> (<?= $txt_optional ?>)</label>
						<input type="text" id="icon" class="form-control" name="icon" value="<?= $icon ?>">
					</div>

					<div class="form-group">
						<label class="label" for="values_list"><strong><?= $txt_values_list ?></strong></label>
						<input type="text" id="values_list" class="form-control" name="values_list" value="<?= $values_list ?>">
					</div>

					<?php
					// custom field values
					if(!empty($cfg_languages) && is_array($cfg_languages)) {
						foreach($cfg_languages as $v) {
							?>
							<div class="form-group">
								<label class="label" for="values_list">
									<?= $txt_values_list ?>:
									<span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span>
								</label>

								<input type="text" id="values_list_<?= $v ?>" class="form-control" name="values_list_lang[<?= $v ?>]" value="<?= isset($custom_field_lang[$v]['values_list']) ? $custom_field_lang[$v]['values_list'] : '' ?>">
							</div>
							<?php
						}
					}
					?>

					<div class="form-group">
						<label class="label" for="value_unit"><strong><?= $txt_value_unit ?></strong>  (<?= $txt_optional ?>)</label>
						<input type="text" id="value_unit" class="form-control" name="value_unit" value="<?= $value_unit ?>">
					</div>

					<div class="form-group">
						<div><strong><?= $txt_options ?></strong></div>
						<label class="mr-2"><input type="checkbox" id="required" name="required" value="1" <?= $required ?>> <?= $txt_required ?></label>
						<label><input type="checkbox" id="searchable" name="searchable" value="1" <?= $searchable ?>> <?= $txt_searchable ?></label>
					</div>

					<div class="form-group">
						<label class="label" for="values_list"><strong><?= $txt_show_in_results ?></strong></label>
						<select id="show-in-results" name="show_in_results" class="form-control">
							<option value="no" <?= $show_in_results == '' ? 'selected' : '' ?>><?= $txt_no ?></option>
							<option value="name" <?= $show_in_results == 'name' ? 'selected' : '' ?>><?= $txt_show_name ?></option>
							<option value="icon" <?= $show_in_results == 'icon' ? 'selected' : '' ?>><?= $txt_show_icon ?></option>
							<option value="name-icon" <?= $show_in_results == 'name-icon' ? 'selected' : '' ?>><?= $txt_show_name_icon ?></option>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="custom-field-group"><strong><?= $txt_field_group ?></strong></label>
						<select id="custom-field-group" name="field_group" class="form-control">
							<?php
							if(!empty($custom_fields_groups)) {
								foreach($custom_fields_groups as $v) {
									?>
									<option value="<?= $v['group_id'] ?>" <?= $v['group_id'] == $field_group ? 'selected' : '' ?>><?= $v['group_name'] ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="field_order"><strong><?= $txt_field_order ?></strong></label>
						<input type="number" id="field_order" class="form-control" name="field_order" value="<?= $field_order ?>">
					</div>

					<div class="form-group">
						<div><strong><?= $txt_categories ?></strong></div>

						<input type="checkbox" id="select_all" name="select_all"> <label for="select_all"><?= $txt_select_all ?></label>

						<?php
						show_cats($cats_grouped_by_parent, 0, $checked_cats, 1);
						?>
					</div>

					<div class="form-group">
						<input type="submit" id="submit" name="submit" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

(function(){
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
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-custom-field.php';
		var response;

		// post
		$.post(post_url, { params: $('form.form-edit-custom-field').serialize() }, function(data) {
				response = data == '1' ? '<?= $txt_field_updated ?>' : data;
				$('.edit-custom-field').empty().html(response);
				$('html, body').animate({scrollTop : 0},360);
			}
		);
    });
}());
</script>
</body>
</html>