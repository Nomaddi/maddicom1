<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/iso-639-1-native-names.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'categories');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// cat details
$cat_id = $_POST['cat_id'];

$query = "SELECT * FROM cats WHERE id = :cat_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$cat_id      = !empty($row['id'         ]) ? $row['id'         ] : '';
$cat_name    = !empty($row['name'       ]) ? $row['name'       ] : '';
$plural_name = !empty($row['plural_name']) ? $row['plural_name'] : '';
$cat_slug    = !empty($row['cat_slug'   ]) ? $row['cat_slug'   ] : '';
$cat_icon    = !empty($row['cat_icon'   ]) ? $row['cat_icon'   ] : '';
$cat_bg      = !empty($row['cat_bg'     ]) ? $row['cat_bg'     ] : '';
$cat_order   = !empty($row['cat_order'  ]) ? $row['cat_order'  ] : 0;
$cat_parent  = !empty($row['parent_id'  ]) ? $row['parent_id'  ] : 0;

// sanitize
$cat_name    = e(trim($cat_name   ));
$plural_name = e(trim($plural_name));
$cat_icon    = e(trim($cat_icon   ));

// category name translation
/*
type ---------- property ---------- value
cat-lang ------ 1 ------ ---------- en;café;cafés
*/
$cat_lang = array();

$query = "SELECT * FROM config WHERE type = 'cat-lang' AND property = :cat_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$arr = explode(";", $row['value']);
	$cat_lang[$arr[0]] = array($arr[1], $arr[2]);
}
?>
<form class="form-edit-cat" method="post">
	<input type="hidden" name="cat_id" value="<?= $cat_id ?>">

	<div class="form-group">
		<strong><?= $txt_cat_name ?></strong>
		<input type="text" class="form-control" name="cat_name" value="<?= $cat_name ?>" required>
	</div>

	<div class="form-group">
		<strong><?= $txt_plural_name ?></strong>
		<input type="text" class="form-control" name="plural_name" value="<?= $plural_name ?>" required>
	</div>

	<div class="form-group">
		<strong><?= $txt_cat_slug ?></strong>
		<input type="text" class="form-control" name="cat_slug"  value="<?= $cat_slug ?>" required>
	</div>

	<?php
	if(!empty($cfg_languages) && is_array($cfg_languages)) {
		foreach($cfg_languages as $v) {
			$this_val = $cat_name;
			$this_plural_val = $plural_name;

			if(!empty($cat_lang[$v])) {
				if(!empty($cat_lang[$v][0])) {
					$this_val = $cat_lang[$v][0];
				}

				if(!empty($cat_lang[$v][1])) {
					$this_plural_val = $cat_lang[$v][1];
				}
			}
			?>
			<div class="form-group">
				<strong><?= $iso_639_1_native_names[$v] ?></strong>
				<input type="text" class="form-control" name="cat_lang[<?= $v ?>]" value="<?= $this_val ?>">
			</div>

			<div class="form-group">
				<strong><?= $iso_639_1_native_names[$v] ?>: <?= $txt_plural_name ?></strong>
				<input type="text" class="form-control" name="cat_lang[<?= $v ?>_plural]" value="<?= $this_plural_val ?>">
			</div>
			<?php
		}
	}
	?>

	<div class="form-group">
		<strong><?= $txt_cat_icon ?></strong> <?= $txt_optional ?>
		<input type="text" class="form-control" name="cat_icon" value="<?= $cat_icon ?>">
	</div>

	<div class="form-group">
		<strong><?= $txt_bg_color ?></strong> <?= $txt_optional ?>
		<input type="text" id="cat_bg" class="form-control" name="cat_bg" value="<?= $cat_bg ?>">
	</div>

	<div class="form-group">
		<strong><?= $txt_cat_order ?></strong> <?= $txt_optional ?>
		<input type="text" class="form-control" name="cat_order" value="<?= $cat_order ?>">
	</div>

	<div class="form-group">
		<label class="label" for="cat_parent"><?= $txt_parent_cat ?></label>
		<?= $txt_parent_explain ?><br>
		<select class="form-control" name="cat_parent">
			<option value="0"><?= $txt_no_parent ?></option>
			<?php
			// select only first 2 levels (parent = 0 or parent whose parent = 0)
			$modal_cats_arr = array();
			$level_0_ids    = array();

			$query = "SELECT * FROM cats WHERE cat_status = 1 AND parent_id = 0 AND id != :id ORDER BY name";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':id', $cat_id);
			$stmt->execute();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$cur_loop_array = array( 'id' => $row['id'], 'cat_name' => $row['name'] );
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
				$query = "SELECT * FROM cats WHERE cat_status = 1 AND parent_id IN($in) ORDER BY name";
				$stmt = $conn->prepare($query);
				$stmt->execute();

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$modal_cats_arr[] = array('id' => $row['id'], 'cat_name' => $row['name']);
				}
			}

			function cmp($a, $b) {
				return strcasecmp ($a['cat_name'], $b['cat_name']);
			}
			usort($modal_cats_arr, 'cmp');

			$selected = '';
			foreach($modal_cats_arr as $k => $v) {
				if($v['id'] == $cat_parent) {
					$selected = 'selected';
				}

				else {
					$selected = '';
				}
				?>
				<option value="<?= $v['id'] ?>" <?= $selected ?>><?= $v['cat_name'] ?></option>
				<?php
			}
			?>
		</select>
	</div>

	<div class="form-group" id="cat-img-row">
		<input type="file" id="edit_cat_img" name="cat_img" style="display:block;visibility:hidden;width:0;height:0;">
		<input type="hidden" id="edit_uploaded_img" name="uploaded_img" value="">

		<strong><?= $txt_cat_img ?></strong>
		<div class="mb-3">
			<div id="edit-cat-img-wrapper" style="width:180;height:180">
				<?php
				// img path
				$cat_img_path = $pic_basepath . '/category/cat-' . $cat_id;

				// check if file exists
				$arr = glob("$cat_img_path.*");

				if(!empty($arr)) {
					$cat_img_filename = basename($arr[0]);
					$cat_img_filename_url = $pic_baseurl . '/category/' . $cat_img_filename;
				}

				else {
					$cat_img_filename = '';
					$cat_img_filename_url = '';
				}

				if(!empty($cat_img_filename_url)) {
					?>
					<img src="<?= $cat_img_filename_url ?>?<?= uniqid() ?>">
					<?php
				}
				?>
			</div>
		</div>

		<div class="mb-3">
			<span id="edit-upload-cat-img" class="btn btn-light" role="button" style="cursor:pointer"><i class="fas fa-plus"></i> <?= $txt_upload ?></span>
		</div>
	</div>
</form>