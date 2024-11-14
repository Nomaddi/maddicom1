<script>
/*--------------------------------------------------
Add to Favorites
--------------------------------------------------*/
$(function() {
    addToFavorites();
});

/*--------------------------------------------------
Map column setup
--------------------------------------------------*/

// add margin top in full screen mode
(function(){
	if($(window).width() > 992) {
		let height = $('#header-nav').outerHeight(true);
		let heightpx = height + 'px';
		let heightcalc = 'calc(100% - ' + heightpx + ')';

		$('#map-col').removeAttr('style').css("margin-top", heightpx);
		$('#map-canvas').removeAttr('style').css({"width": "100%", "height": heightcalc});
	}

	else {
		$('#map-col').removeAttr('style').css("margin-top", 0);
	}

	if($(window).width() < 993) {
		$('#map-col').removeClass('fixed-top');
		$('#map-col').removeClass('h-100');
		$('#public-listings').removeClass('mt-4');
	}
}());

/*--------------------------------------------------
On resize
--------------------------------------------------*/

function onResizeChange() {
	if($(window).width() < 993) {
		$('#map-col').removeClass('fixed-top');
		$('#map-col').removeClass('h-100');
		$('#public-listings').removeClass('mt-4');
		$('#map-col').removeAttr('style').css("margin-top", 0);
	}

	else {
		let height = $('#header-nav').outerHeight(true);
		$('#map-col').addClass('fixed-top');
		$('#map-col').removeAttr('style').css("margin-top", height);
		$('#public-listings').removeClass('mt-4').addClass('mt-4');
	}
};

var resizeTimer;

$(window).on('resize', function() {
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(onResizeChange, 1);
});

/*--------------------------------------------------
Map markers
--------------------------------------------------*/

<?php
if(!empty($results_arr)) {
	if($map_provider !== "Google") {
		?>
		var resultsObj = <?= json_encode($results_arr) ?>;

		// Set Map
		<?php
		if($map_provider == 'Tomtom') {
			?>
			var mymap = new L.Map("map-canvas", map_provider_options).setView([0.000, 0.000], 13);
			<?php
		}

		else {
			?>
			var mymap = L.map("map-canvas").setView([0.000, 0.000], 13);

			L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
			<?php
		}
		?>

		// Markers
		// init marker array
		var markerArray = [];

		// set markers
		for (var k in resultsObj) {
			var p = resultsObj[k];

			L.marker([p.ad_lat, p.ad_lng])
			.bindPopup('<a href="' + '" target="_blank">' + p.ad_title + '</a>')
			.addTo(mymap);

			markerArray.push(L.marker([p.ad_lat, p.ad_lng]));
		}

		var group = L.featureGroup(markerArray).addTo(mymap);
		mymap.fitBounds(group.getBounds());

		// events -->
		$(".item-list .list-item").on('mouseover', function() {
			//marker = markers[this.getAttribute("data-ad_id")];

			var ad_id = this.getAttribute("data-listing-id");

			var result = resultsObj.filter(function( obj ) {
				return obj.ad_id == ad_id;
			});

			tooltipPopup = L.popup({ offset: L.point(0, -20)});
			tooltipPopup.setContent(result[0].ad_title);
			tooltipPopup.setLatLng([result[0].ad_lat, result[0].ad_lng]);
			tooltipPopup.openOn(mymap);
		});
		<?php
	}

	if($map_provider == "Google") {
		?>
		var results_obj = <?= json_encode($results_arr) ?>;
		var infowindow;
		var map;

		function initialize() {
			markers = {};
			infoboxcontents = {};

			// set map options
			var mapOptions = {
				zoom: 5,
				maxZoom: 15
			};

			// instantiate map
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var bounds = new google.maps.LatLngBounds();
			var infowindow = new google.maps.InfoWindow();

			// $results_arr[] = array("ad_id" => $place_id, "ad_lat" => $ad_lat, "ad_lng" => $ad_lng, "ad_title" => $ad_title, "count" => $count);

			// set markers
			for (var k in results_obj) {
				var p = results_obj[k];
				var latlng = new google.maps.LatLng(p.ad_lat, p.ad_lng);
				bounds.extend(latlng);

				var marker_icon = '<?= $baseurl ?>/imgs/marker1.png';

				// place markers
				var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					animation: google.maps.Animation.DROP,
					title: p.ad_title,
					//icon: marker_icon
				});

				markers[p.ad_id] = marker;
				infoboxcontents[p.ad_id] = p.ad_title;

				// click event on markers to show infowindow
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(this.title);
					infowindow.open(map, this);
					});
			} // end for (var k in results_obj)

			map.fitBounds(bounds);

			// events
			$(".item-list .list-item").on('mouseover', function() {
				marker = markers[this.getAttribute("data-listing-id")];
				// mycontent = infoboxcontents[this.getAttribute("data-ad_id")];

				mycontent =  '<div class="scrollFix">' + infoboxcontents[this.getAttribute("data-listing-id")] + '</div>';

				infowindow.setContent(mycontent);
				// infowindow.setOptions({maxWidth:300});
				infowindow.open(map, marker);
				marker.setZIndex(10000);
			});
		} //  end initialize()

		google.maps.event.addDomListener(window, 'load', initialize);
		<?php
	}
}
?>

// Rounded images
(function(){
	if($(window).width() < 577) {
		$('.thumb').removeClass('rounded-left');
		$('.thumb').addClass('rounded-top');
	}
}());

// raty (rating library) -->
(function(){
	$.fn.raty.defaults.path = '<?= $baseurl ?>/templates/js/raty/images';
	$('.item-rating').raty({
		readOnly: true,
		score: function(){
			return this.getAttribute('data-rating');
		}
	});
}());
</script>