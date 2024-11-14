<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = 'admin' AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':template', 'emails');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// template details
$template_id = $_POST['template_id'];

$query = "SELECT * FROM email_templates WHERE id = :template_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':template_id', $template_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$template_type    = !empty($row['type'          ]) ? $row['type'          ] : '';
$template_subject = !empty($row['subject'       ]) ? $row['subject'       ] : '';
$template_body    = !empty($row['body'          ]) ? $row['body'          ] : '';
$available_vars   = !empty($row['available_vars']) ? $row['available_vars'] : '';

// instructions
$instruct = '';
if($template_type == 'reset_pass') {
	$instruct = $txt_instruct_reset;
}

if($template_type == 'signup_confirm') {
	$instruct = $txt_instruct_signup;
}

?>
<form class="form-edit-email-template" method="post">
	<input type="hidden" id="template_id" name="template_id" value="<?= $template_id ?>">

	<div class="form-group">
		<p><strong><?= $txt_available_vars_header ?></strong></p>
		<pre><?= $available_vars ?></pre>
	</div>

	<div class="form-group">
		<strong><?= $txt_email_subject ?></strong>
		<input type="text" id="template_subject" class="form-control" name="template_subject" value="<?= $template_subject ?>">
	</div>

	<div class="form-group">
		<strong><?= $txt_email_body ?></strong>
		<?= $instruct ?><br>
		<textarea id="template_body" class="form-control" name="template_body"><?= $template_body ?></textarea>
	</div>
</form>