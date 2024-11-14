<?php
if(file_exists(__DIR__ . '/user-head-child.php') && basename(__FILE__) != 'user-head-child.php') {
	include_once('user-head-child.php');
	return;
}

if(in_array($route[1], array('add-place.php', 'edit-place.php'))) {
	include_once(__DIR__ . '/../../inc/map-provider-options.php');
	?>
	<!-- Maps css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css">
	<?php
	if($map_provider == 'Tomtom') {
		?>
		<link rel='stylesheet' type='text/css' href='<?= $baseurl ?>/assets/js/sdk-tomtom/map.css'>
		<?php
	}
	?>

	<!-- Maps -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js"></script>
	<script src="<?= $baseurl ?>/assets/js/leaflet-providers.js"></script>

	<?php
	if($map_provider == 'Tomtom') {
		?>
		<script src="<?= $baseurl ?>/assets/js/sdk-tomtom/tomtom.min.js"></script>
		<?php
	}

	if($map_provider == 'Google') {
		?>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_key ?>"></script>
		<?php
	}

}
