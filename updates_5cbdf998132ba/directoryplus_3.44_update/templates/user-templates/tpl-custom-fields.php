<?php
/*
This file is included in the 'tpl-create-listing.php' and 'tpl-edit-listing.php' files

This file checks if there are global custom fields and generates the corresponding html code
It also inserts javascript which calls user-get-custom-fields when the category field is changed
*/
if(file_exists(__DIR__ . "/tpl-custom-fields-child-$html_lang.php") && basename(__FILE__) != "tpl-custom-fields-child-$html_lang.php") {
	include_once("tpl-custom-fields-child-$html_lang.php");
	return;
}

if(file_exists(__DIR__ . '/tpl-custom-fields-child.php') && basename(__FILE__) != 'tpl-custom-fields-child.php') {
	include_once('tpl-custom-fields-child.php');
	return;
}
?>
<div id="custom-fields" class="mt-5">
	<input type="hidden" name="custom_fields_ids" id="custom_fields_ids" value="<?= $custom_fields_ids; ?>">

	<?php
	/*--------------------------------------------------
	Global custom fields
	--------------------------------------------------*/
	?>
	<div id="global-fields">
		<?php
		if(!empty($custom_fields) && !empty($fields_groups)) {
			foreach($fields_groups as $g) {
				if(in_array($g['group_id'], array_column($custom_fields, 'field_group'))) {
					?>
					<div id="group-<?= $g['group_id'] ?>" class="mb-5">
						<p class="text-dark text-uppercase" style="font-weight:600"><?= $g['group_name'] ?></p>
						<hr>
						<?php
						foreach($custom_fields as $k => $v) {
							if($v['field_group'] == $g['group_id']) {
								if(!empty($v['tr_tooltip'])) {
									$v['tr_tooltip'] = '<a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="' . $v['tr_tooltip'] . '"><i class="lar la-question-circle"></i></a>';
								}

								// explode values
								if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
									$values_arr      = explode(';', $v['values_list']);
									$tr_values_arr   = explode(';', $v['tr_values_list']);
									$field_value_arr = explode(':::', $v['field_value']);
								}

								if($v['field_type'] == 'radio') {
									?>
									<div class="form-group">
										<div><label><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>

										<?php
										$i = 1;
										foreach($values_arr as $k2 => $v2) {
											$v2 = e(trim($v2));

											// check if translated values exist
											if(empty($tr_values_arr[$k2])) {
												$tr_values_arr[$k2] = $values_arr[$k2];
											}

											// is checked
											$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
											?>
											<div class="form-check form-check-inline">
												<input type="radio" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>" value="<?= $v2 ?>" <?= $checked ?> <?= $v['required'] ?>>
												<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal"><?= $tr_values_arr[$k2] ?></label>
											</div>
											<?php
											$i++;
										}
										?>
									</div>
									<?php
								}

								if($v['field_type'] == 'select') {
									?>
									<div class="form-group">
										<div>
											<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
										</div>

										<select id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>>
											<?php
											foreach($values_arr as $k2 => $v2) {
												$v2 = e(trim($v2));

												// check if translated values exist
												if(empty($tr_values_arr[$k2])) {
													$tr_values_arr[$k2] = $values_arr[$k2];
												}

												// is selected
												$selected = in_array($v2, $field_value_arr) ? 'selected' : '';
												?>
												<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<?php
								}

								if($v['field_type'] == 'checkbox') {
									?>
									<div class="form-group">
										<div><label><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>

										<?php
										$i = 1;

										foreach($values_arr as $k2 => $v2) {
											$v2 = e(trim($v2));

											// check if translated values exist
											if(empty($tr_values_arr[$k2])) {
												$tr_values_arr[$k2] = $values_arr[$k2];
											}

											// is checked
											$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
											?>
											<div class="form-check form-check-inline">
												<input type="checkbox" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?>>
												<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal"> <?= $tr_values_arr[$k2] ?></label>
											</div>
											<?php
											$i++;
										}
										?>
									</div>
									<?php
								}

								if($v['field_type'] == 'text') {
									?>
									<div class="form-group">
										<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
										<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" value="<?= $v['field_value'] ?>" <?= $v['required'] ?> >
									</div>
									<?php
								}

								if($v['field_type'] == 'multiline') {
									?>
									<div class="form-group">
										<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
										<textarea class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>><?= $v['field_value'] ?></textarea>
									</div>
									<?php
								}

								if($v['field_type'] == 'url') {
									?>
									<div class="form-group">
										<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
										<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
									</div>
									<?php
								}
							}
						}
						?>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
	<?php
	/*--------------------------------------------------
	Category Custom Fields (used on edit listing page)
	--------------------------------------------------*/
	?>
	<div id="cat-fields">
		<?php
		if(!empty($cat_fields) && !empty($fields_groups)) {
			foreach($fields_groups as $g) {
				if(in_array($g['group_id'], array_column($cat_fields, 'field_group'))) {
					?>
					<div id="group-<?= $g['group_id'] ?>" class="mb-5">
						<p class="text-dark text-uppercase" style="font-weight:600"><?= $g['group_name'] ?></p>
						<hr>
						<?php
						foreach($cat_fields as $k => $v) {
								if($v['field_group'] == $g['group_id']) {
									if(!empty($v['tr_tooltip'])) {
										$v['tr_tooltip'] = '<a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="' . $v['tr_tooltip'] . '"><i class="lar la-question-circle"></i></a>';
									}

									// explode values
									if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
										$values_arr      = explode(';', $v['values_list']);
										$tr_values_arr   = explode(';', $v['tr_values_list']);
										$field_value_arr = explode(':::', $v['field_value']);
									}

									if($v['field_type'] == 'radio') {
										?>
										<div class="form-group">
											<div><label><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>

											<?php
											$i = 1;
											foreach($values_arr as $k2 => $v2) {
												$v2 = e(trim($v2));

												// check if translated values exist
												if(empty($tr_values_arr[$k2])) {
													$tr_values_arr[$k2] = $values_arr[$k2];
												}

												// is checked
												$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
												?>
												<div class="form-check form-check-inline">
													<input type="radio" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>" value="<?= $v2 ?>" <?= $checked ?> <?= $v['required'] ?>>
													<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal"><?= $tr_values_arr[$k2] ?></label>
												</div>
												<?php
												$i++;
											}
											?>
										</div>
										<?php
									}

									if($v['field_type'] == 'select') {
										?>
										<div class="form-group">
											<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
											<select id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>>
												<?php
												foreach($values_arr as $k2 => $v2) {
													$v2 = e(trim($v2));

													// check if translated values exist
													if(empty($tr_values_arr[$k2])) {
														$tr_values_arr[$k2] = $values_arr[$k2];
													}

													// is selected
													$selected = in_array($v2, $field_value_arr) ? 'selected' : '';
													?>
													<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?>
													<?php
												}
												?>
											</select>
										</div>
										<?php
									}

									if($v['field_type'] == 'checkbox') {
										?>
										<div class="form-group">
											<div><label><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>

											<?php
											$i = 1;
											foreach($values_arr as $k2 => $v2) {
												$v2 = e(trim($v2));

												// check if translated values exist
												if(empty($tr_values_arr[$k2])) {
													$tr_values_arr[$k2] = $values_arr[$k2];
												}

												// is checked
												$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
												?>
												<div class="form-check form-check-inline">
													<input type="checkbox" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?>>
													<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal"><?= $tr_values_arr[$k2] ?></label>
												</div>
												<?php
												$i++;
											}
											?>
										</div>
										<?php
									}

									if($v['field_type'] == 'text') {
										?>
										<div class="form-group">
											<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
											<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
										</div>
										<?php
									}

									if($v['field_type'] == 'multiline') {
										?>
										<div class="form-group">
											<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
											<textarea class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>><?= $v['field_value'] ?></textarea>
										</div>
										<?php
									}

									if($v['field_type'] == 'url') {
										?>
										<div class="form-group">
											<div><label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label></div>
											<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
										</div>
										<?php
									}
								}
						}
						?>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
</div>