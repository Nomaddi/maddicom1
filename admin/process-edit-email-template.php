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
$stmt->bindValue(':template', 'emails');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// email template details
$params = array();
parse_str($_POST['params'], $params);

$template_id      = !empty($params['template_id'     ]) ? $params['template_id'     ] : '';
$template_subject = !empty($params['template_subject']) ? $params['template_subject'] : '';
$template_body    = !empty($params['template_body'   ]) ? $params['template_body'   ] : '';

// trim
$template_subject = trim($template_subject);
$template_body    = trim($template_body);

// check vars
if(empty($template_id)) {
	echo "Undefined template";
	die();
}

$query = "UPDATE email_templates SET
	subject = :template_subject,
	body    = :template_body
	WHERE id = :template_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':template_subject', $template_subject);
$stmt->bindValue(':template_body', $template_body);
$stmt->bindValue(':template_id', $template_id);

if($stmt->execute()) {
	echo $txt_email_template_updated;
}