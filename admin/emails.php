<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

$query = "SELECT * FROM email_templates";
$stmt = $conn->prepare($query);
$stmt->execute();

$email_templates_arr = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$template_id          = !empty($row['id'            ]) ? $row['id'            ] : '';
	$template_type        = !empty($row['type'          ]) ? $row['type'          ] : '';
	$template_subject     = !empty($row['subject'       ]) ? $row['subject'       ] : '';
	$template_description = !empty($row['description'   ]) ? $row['description'   ] : '';
	$available_vars       = !empty($row['available_vars']) ? $row['available_vars'] : '';

	$cur_arr = array(
				'template_id'          => $template_id,
				'template_type'        => $template_type,
				'template_subject'     => $template_subject,
				'template_description' => $template_description,
				'available_vars'       => $available_vars,
				);
	$templates_arr[] = $cur_arr;
}
