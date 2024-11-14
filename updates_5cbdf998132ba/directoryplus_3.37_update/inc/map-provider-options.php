<script>
<?php
if($map_provider == 'MapBox') {
	?>
	// MapBox Maps options
	var map_provider_options = {
		accessToken: "<?= $mapbox_secret ?>"
	}
	<?php
}

if($map_provider == 'OpenStreetMap') {
	?>
	// OpenStreetMap Maps options
	var map_provider_options = {

	}
	<?php
}

if($map_provider == 'Tomtom') {
	?>
	// Tomtom Maps options
	var map_provider_options = {
		"key": "<?= $tomtom_secret ?>"
	}
	<?php
}

if($map_provider == 'HERE') {
	?>
	// Here Maps options
	var map_provider_options = {
		"app_id": "<?= $here_key ?>",
		"app_code": "<?= $here_secret
		?>"
	}
	<?php
}

if($map_provider == 'Wikimedia') {
	?>
	// Wikimedia Maps options
	var map_provider_options = {
		"maxZoom": 13
	}
	<?php
}

if($map_provider == 'CartoDB.Voyager') {
	?>
	// Wikimedia Maps options
	var map_provider_options = {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
		maxZoom: 13
	}
	<?php
}

if($map_provider == 'CartoDB.Positron') {
	?>
	// Wikimedia Maps options
	var map_provider_options = {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
		maxZoom: 13
	}
	<?php
}

if($map_provider == 'Stamen.Terrain') {
	?>
	// Wikimedia Maps options
	var map_provider_options = {
		attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		subdomains: 'abcd',
		minZoom: 0,
		maxZoom: 13,
		ext: 'png'
	}
	<?php
}
?>
</script>