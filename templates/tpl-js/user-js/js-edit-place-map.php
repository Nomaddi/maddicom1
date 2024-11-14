<script>
<!-- Map -->
<?php
if($map_provider !== "Google") {
	if($map_provider == 'Tomtom') {
		?>
		var mymap = new L.Map("map-canvas", map_provider_options).setView([<?= $lat; ?>, <?= $lng; ?>], 5);
		<?php
	}

	else {
		?>
		var mymap = L.map("map-canvas").setView([<?= $lat; ?>, <?= $lng; ?>], 5);

		L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
		<?php
	}
	?>

	// set marker
	var marker = L.marker([<?= $lat; ?>, <?= $lng; ?>]).addTo(mymap);

	var new_marker;

	mymap.on('click', function(e) {
		if(marker) {
			mymap.removeLayer(marker);
		}

		if(new_marker) {
			mymap.removeLayer(new_marker);
		}

		new_marker = new L.marker(e.latlng).addTo(mymap);
		$("#latlng").val(e.latlng.lat + ", " + e.latlng.lng);
	});
	<?php
}

else {
	?>
	// init vars
	var map            = null;
	var marker         = null;
	var markers        = [];
	var update_timeout = null;
	var geocoder;

	// place latitude and longitude
	var myLatlng = new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>);

	// global infowindow object
	var infowindow = new google.maps.InfoWindow( {
		size: new google.maps.Size(150,50)
	});

	// map options
	var mapOptions = {
		zoom: 12,
		center: myLatlng,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	// init map
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

	// init geocoder service
	geocoder = new google.maps.Geocoder();

	// create marker based on this place lat lng
	var marker = new google.maps.Marker({
		position: myLatlng,
		title: ""
	});

	// To add the marker to the map, call setMap();
	marker.setMap(map);

	// when click on map, add marker
	google.maps.event.addListener(map, 'click', function(event){
		deleteMarkers();
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			marker = createMarker(event.latLng, "name", "<b><?= $txt_map_marker_location; ?></b><br>" + event.latLng);
			$("#latlng").val(event.latLng);
		}, 200);
	});

	// on double click
	google.maps.event.addListener(map, 'dblclick', function(event) {
		clearTimeout(update_timeout);
	});

	// address input on blur listener
	document.getElementById('address').addEventListener('blur', function(e){
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			codeAddress();
		}, 200);
	});

	// postal_code input on blur listener
	document.getElementById('postal_code').addEventListener('blur', function(e){
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			codeAddress();
		}, 200);
	});
	<!-- End Google Maps API -->
<?php
}
?>
</script>