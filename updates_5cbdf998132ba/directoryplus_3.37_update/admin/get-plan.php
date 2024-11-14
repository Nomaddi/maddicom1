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
$stmt->bindValue(':template', 'plans');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// plan details
$plan_id = $_POST['plan_id'];

$query = "SELECT * FROM plans WHERE plan_id = :plan_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':plan_id', $plan_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$plan_type     = $row['plan_type'];
$plan_name     = $row['plan_name'];
$plan_features = $row['plan_features'];
$plan_period   = $row['plan_period'];
$plan_price    = $row['plan_price'];
$plan_order    = $row['plan_order'];
$plan_status   = $row['plan_status'];

$plan_name     = e($plan_name);
$plan_features = e($plan_features);
?>
<form class="form-edit-plan" method="post">
	<input type="hidden" id="plan_id" name="plan_id" value="<?= $plan_id ?>">
	<input type="hidden" id="plan_type" name="plan_type" value="<?= $plan_type ?>">
	<div class="form-group">
		<strong><?= $txt_plan_type ?>: </strong> <span id="plan_type"><?= $plan_type ?></span>
	</div>

	<div class="form-group">
		<label class="label" for="plan_name"><?= $txt_plan_name ?></label>
		<input type="text" id="plan_name" name="plan_name" class="form-control" value="<?= $plan_name ?>">
	</div>

	<div class="form-group">
		<label class="label" for="plan_features"><?= $txt_features ?></label>
		<textarea id="plan_features" name="plan_features" class="form-control" rows="5"><?= $plan_features ?></textarea>
	</div>

	<?php
	// plan period is 0 if plan type is monthly
	if($plan_type != 'monthly' && $plan_type != 'monthly_feat') {
		?>
		<div class="form-group">
			<label class="label" for="plan_period"><?= $txt_plan_period ?></label>
			<input type="number" id="plan_period" name="plan_period" class="form-control" value="<?= $plan_period ?>" required>
		</div>
		<?php
	}
	?>

	<div class="form-group">
		<label class="label" for="plan_order"><?= $txt_plan_order ?></label>
		<input type="number" id="plan_order" name="plan_order" class="form-control" value="<?= $plan_order ?>">
	</div>

	<div class="form-group">
		<label class="label" for="plan_price"><?= $txt_plan_price ?></label><br>
		<?php
		if($plan_type != 'free' && $plan_type != 'free_feat') {
			?>
			<input type="number" id="plan_price" name="plan_price" class="form-control" value="<?= $plan_price ?>">
			<?php
		}
		else {
			?>
			<input type="hidden" id="plan_price" name="plan_price" class="form-control" value="<?= $plan_price ?>">
			<?= $plan_price ?> <em><?= $txt_change_price ?></em>
			<?php
		}
		?>
	</div>

	<?= $txt_plan_status ?>
	<div class="form-check">
		<input type="radio" id="plan_status_yes" class="form-check-input" name="plan_status" value="1" <?= $plan_status == 1 ? 'checked' : '' ?>>
		<label class="label" for="plan_status_yes"><?= $txt_yes ?></label>
	</div>

	<div class="form-check">
		<input type="radio" id="plan_status_no" class="form-check-input" name="plan_status" value="0" <?= $plan_status == 0 ? 'checked' : '' ?>>
		<label class="label" for="plan_status_no"><?= $txt_no ?> </label>
	</div>
</form>