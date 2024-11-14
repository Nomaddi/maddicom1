<script>
/*--------------------------------------------------
Distance filter
--------------------------------------------------*/
<?php
if(empty($_COOKIE['user_lat']) && empty($_COOKIE['user_lng'])) {
	?>
	(function(){
		$('#dropdown-menu-nearby').on('click', function(e){
			if(cookieEnabled() === true) {
				if ('geolocation' in navigator) {
					navigator.geolocation.getCurrentPosition(function(position) {
						// user coords
						var user_lat = position.coords.latitude;
						var user_lng = position.coords.longitude;

						// send request, response is json object containing city id, name, state, state id
						var url = '<?= $baseurl ?>/inc/nearest-location.php';

						// ajax post
						$.post(url, {
							lat: user_lat,
							lng: user_lng
						}, function(data) {
							// parse json response
							data = JSON.parse(data);
							console.log(data);

							// create cookie
							createCookie('geo_city_id', data.city_id, 14);
							createCookie('geo_city_name', data.city_name, 14);
							createCookie('geo_city_slug', data.city_slug, 14);
							createCookie('geo_state', data.state_abbr, 14);
							createCookie('user_lat', user_lat, 14);
							createCookie('user_lng', user_lng, 14);

							// reload
							location.reload(true);
						});
					});
				}
			}
		});
	}());
	<?php
}
?>

/*--------------------------------------------------
Add to Favorites
--------------------------------------------------*/
$(function() {
    addToFavorites();
});

/*--------------------------------------------------
Map column setup
--------------------------------------------------*/
(function(){
	if($(window).width() > 992) {
		var height = $('#header-nav').outerHeight(true);
		var heightpx = height + 'px';
		var heightcalc = 'calc(100% - ' + heightpx + ')';

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

	if($(window).width() < 577) {
		$('.thumb').removeClass('rounded-left');
		$('.thumb').addClass('rounded-top');
	}
}());

/*--------------------------------------------------
On window resize
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
Map setup
--------------------------------------------------*/
<?php
if(!empty($results_arr)) {
	if($map_provider !== "Google") {
		?>
		var resultsObj = <?= json_encode($results_arr) ?>;

		<!-- Set Map -->
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
		var markerArray = [];

		for (var k in resultsObj) {
			var p = resultsObj[k];

			marker = L.marker([p.ad_lat, p.ad_lng])
			.bindPopup('<a href="' + p.ad_link + '" target="_blank">' + p.ad_title + '</a>');

			markerArray.push(marker);
		}

		var group = L.featureGroup(markerArray).addTo(mymap);
		mymap.fitBounds(group.getBounds());

		// events
		$(".item-list .list-item").on('mouseover', function() {
			//marker = markers[this.getAttribute("data-ad_id")];

			var ad_id = this.getAttribute("data-listing-id");

			var result = resultsObj.filter(function( obj ) {
				return obj.ad_id == ad_id;
			});

			var tooltipPopup = L.popup({ offset: L.point(0, -20)});
			tooltipPopup.setContent(result[0].ad_title);
			tooltipPopup.setContent('<a href="' + result[0].ad_link + '" target="_blank">' + result[0].ad_title + '</a>');
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
			var markers = {};
			var infoboxcontents = {};

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

/*--------------------------------------------------
Sidebar collapse
--------------------------------------------------*/
function openNav() {
	document.getElementById("the-sidebar").style.width = "264px";
	//document.getElementById("main").style.marginLeft = "264px";

	// set the select2 input to same width as other fields
	$('#the-sidebar .select2-container').css('width', '231.2px');
	$('#the-sidebar .select2-container').css('margin', 'auto');
}

function closeNav() {
	document.getElementById("the-sidebar").style.width = "0";
	//document.getElementById("main").style.marginLeft= "0";
}

/*--------------------------------------------------
Sidebar select2
--------------------------------------------------*/
<?php
if($cfg_use_select2) {
	?>
	// select2 config
	$('#city-input-sidebar').select2({
		ajax: {
			url: '<?= $baseurl ?>/_return_cities_select2.php',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					query: params.term,
					page: params.page
				};
			}
		},
		escapeMarkup: function (markup) { return markup; },
		minimumInputLength: 1,
		dropdownAutoWidth : true,
		placeholder: "<?= $txt_city ?>",
		allowClear: true,
		dropdownParent: $('#dummy'),
		language: "<?= $html_lang ?>"
	});

	$("#city-input-sidebar").on("select2:unselecting", function(e) {
		delete_cookie('city_id');
	});

	$("#city-input-sidebar").on('results:message', function(params){
		this.dropdown._resizeDropdown();
		this.dropdown._positionDropdown();
	});
<?php
}
?>

/*--------------------------------------------------
Ratings
--------------------------------------------------*/
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});
}());
</script>
