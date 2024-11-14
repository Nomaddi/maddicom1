<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../inc/iso-639-1-native-names.php');

// $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	foreach($_POST as $k => $v) {
		$this_id = str_replace('txt_', '', $k);

		$query = "UPDATE language SET translated = :translated WHERE id = :id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':id', $this_id);
		$stmt->bindValue(':translated', $v);
		$stmt->execute();
	}
}

// sanitize $_GET
foreach($_GET as $k => $v) {
	$_GET[$k] = e($v);
}

// $_GET vars
$this_lang = !empty($_GET['lang']) ? $_GET['lang'] : 'en';
$this_tpl = !empty($_GET['tpl']) ? $_GET['tpl'] : 'public/global';

// extract section and tpl
$this_section = explode('/', $this_tpl)[0];
$this_tpl = explode('/', $this_tpl)[1];

// get available langs
$available_langs = array();

$query = "SELECT DISTINCT lang FROM language";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if(in_array($row['lang'], array_keys($iso_639_1_native_names))) {
		$available_langs[] = $row['lang'];
	}
}

// available templates
$available_tpls = array();

$query = "SELECT section, template FROM language GROUP BY section, template";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$available_tpls[] = $row['section'] . '/' . $row['template'];
}

// get tpl txt vars
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template ORDER BY translated";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $this_lang);
$stmt->bindValue(':section', $this_section);
$stmt->bindValue(':template', $this_tpl);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_id         = !empty($row['id'        ]) ? $row['id'        ] : '';
	$this_template   = !empty($row['template'  ]) ? $row['template'  ] : '';
	$this_var_name   = !empty($row['var_name'  ]) ? $row['var_name'  ] : '';
	$this_translated = !empty($row['translated']) ? $row['translated'] : '';

	// sanitize
	$this_id         = e($this_id        );
	$this_template   = e($this_template  );
	$this_var_name   = e($this_var_name  );
	$this_translated = e($this_translated);

	// add to array
	$cur_loop_arr = array(
		'id'         => $this_id,
		'lang'       => $this_lang,
		'section'    => $this_section,
		'template'   => $this_template,
		'var_name'   => $this_var_name,
		'translated' => $this_translated,
	);

	$phrases_arr[] = $cur_loop_arr;
}

// get available language sql files
$available_sqls = glob(__DIR__ . "/../sql/lang_*.sql");

foreach($available_sqls as $k => $v) {
	$available_sqls[$k] = basename($v);
}