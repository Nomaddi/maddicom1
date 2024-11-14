<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'locations');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// show form
$loc_type = $_POST['loc_type'];

if($loc_type == 'city') {
	?>
	<form class="form-create-loc" method="post">
		<input type="hidden" id="loc_type" name="loc_type" value="city">

		<div class="form-group">
			<label class="label" for="city_name"><?= $txt_city_name ?></label>
			<input type="text" id="city_name" class="form-control" name="city_name" required>
		</div>

		<div class="form-group">
			<label class="label" for="state"><?= $txt_select_state ?></label>
			<select id="state" class="form-control" name="state" required>
				<?php
				// count states
				$query = "SELECT COUNT(*) AS total_rows FROM states";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$total_rows = $row['total_rows'];

				if($total_rows > 0) {
					// select all states
					$query = "SELECT * FROM states ORDER BY state_name";
					$stmt = $conn->prepare($query);
					$stmt->execute();

					while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$state_id     = $row['state_id'];
						$state_name   = $row['state_name'];
						$state_abbr   = e($row['state_abbr']);

						$value = "$state_id,$state_abbr";
						?>
						<option value="<?= $value ?>"><?= $state_name ?></option>
						<?php
					}
				}
				else {
					?>
					<option value=""><?= $txt_msg_no_state ?></option>
					<?php
				}
				?>
			</select>
		</div>

		<div class="form-group">
			<label class="label" for="lat"><?= $txt_lat ?></label>
			<input type="text" id="lat" class="form-control" name="lat" value="0.000" required>
		</div>

		<div class="form-group">
			<label class="label" for="lng"><?= $txt_lng ?></label>
			<input type="text" id="lng" class="form-control" name="lng" value="0.000" required>
		</div>

		<div class="form-group" id="city-img-row">
			<input type="file" id="city_img" name="city_img" style="display:block;visibility:hidden;width:0;height:0;">
			<input type="hidden" id="uploaded_img" name="uploaded_img" value="">

			<strong><?= $txt_city_photo ?></strong>
			<div class="mb-3">
				<div id="city-img-wrapper"></div>
			</div>

			<div class="mb-3">
				<span id="upload-city-img" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> <?= $txt_upload ?></span>
			</div>
		</div>
	</form>
	<?php
}

if($loc_type == 'state') {
	?>
	<form class="form-create-loc" method="post">
		<input type="hidden" id="loc_type" name="loc_type" value="state">

		<div class="form-group">
			<label class="label" for="state_name"><?= $txt_state_name ?></label>
			<input type="text" id="state_name" class="form-control" name="state_name" required>
		</div>

		<div class="form-group">
			<label class="label" for="state_abbr"><?= $txt_state_abbr ?></label>
			<input type="text" id="state_abbr" class="form-control" name="state_abbr" required>
		</div>

		<div class="form-group">
			<label class="label" for="state"><?= $txt_select_country ?></label>
			<select id="country" class="form-control" name="country" required>
				<?php
				// count states
				$query = "SELECT COUNT(*) AS total_rows FROM countries";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$total_rows = $row['total_rows'];

				if($total_rows > 0) {
					// select all states
					$query = "SELECT * FROM countries ORDER BY country_name";
					$stmt = $conn->prepare($query);
					$stmt->execute();

					while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$country_id     = $row['country_id'];
						$country_name   = $row['country_name'];
						$country_abbr   = $row['country_abbr'];

						$value = "$country_id,$country_abbr";
						?>
						<option value="<?= $value ?>"><?= $country_name ?></option>
						<?php
					}
				}
				else {
					?>
					<option value=""><?= $txt_msg_no_country ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</form>
	<?php
}

if($loc_type == 'country') {
	?>
	<form class="form-create-loc" method="post">
		<input type="hidden" id="loc_type" name="loc_type" value="country">
		<div class="form-group">
			<label class="label" for="country_name"><?= $txt_country_name ?></label>
			<input type="text" id="country_name" class="form-control" name="country_name" required>
		</div>

		<div class="form-group">
			<label class="label" for="country_abbr"><?= $txt_country_abbr ?></label>
			<input type="text" id="country_abbr" class="form-control" name="country_abbr" required>
		</div>
	</form>
	<?php
}