<?php
require_once(__DIR__ . '/../inc/config.php');
//require_once(__DIR__ . '/_admin_inc.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'tools');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// deactivate listings
$query = "UPDATE places SET paid = 0 WHERE valid_until < CURRENT_TIMESTAMP";
$stmt = $conn->prepare($query);

if($stmt->execute()) {
	?>
	<p><?= $txt_deactivate_success ?></p>
<?php
}
else {
	?>
	<p><?= $txt_deactivate_fail ?></p>
<?php
}
