<?php
if(isset($cfg_languages) && is_array($cfg_languages)) {
	if(count($cfg_languages) > 1) {
		?>
		<div class="custom-select-wrapper rounded mb-5" id="language-selector" style="width: 180px;">
			<select name="language">
				<?php
				foreach ($cfg_languages as $v) {
					$selected = '';
					if(isset($_COOKIE['user_language']) && $v == $_COOKIE['user_language']) {
						$selected = 'selected';
					}
					echo '<option value="' . $v . '" ' . $selected . '>' . "($v) " . $iso_639_1_native_names[$v] . '</option>';
				}
				?>
			</select>
		</div>
		<?php
	}
}
