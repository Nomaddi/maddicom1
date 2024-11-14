<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder@1.7.0/dist/Control.Geocoder.js"></script>

<script>
/*--------------------------------------------------
Maps
--------------------------------------------------*/
<?php
if($map_provider !== "Google") {
	if($map_provider == 'Tomtom') {
		?>
		var mymap = new L.Map("map-canvas", map_provider_options).setView([<?= $lat ?>, <?= $lng ?>], 5);
		<?php
	}

	else {
		?>
		var mymap = L.map("map-canvas").setView([<?= $lat ?>, <?= $lng ?>], 12);

		L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
		<?php
	}
	?>

	// set marker
	var marker = L.marker([<?= $lat ?>, <?= $lng ?>]).addTo(mymap);

	// validate latlng field
	$(function() {
		var latlngInput = document.getElementById("latlng");
		latlngInput.setCustomValidity("<?= $txt_click_map  ?>");
	});

	var new_marker;

	mymap.on('click', function(e) {
		// remove custom validate message
		var latlngInput = document.getElementById("latlng");
		latlngInput.setCustomValidity("");

		if(marker) {
			mymap.removeLayer(marker);
		}

		if(new_marker) {
			mymap.removeLayer(new_marker);
		}

		new_marker = new L.marker(e.latlng).addTo(mymap);
		$("#latlng").val(e.latlng.lat + ", " + e.latlng.lng);
	});

	// Leaflet geocoder
	var geocoder = L.Control.geocoder({
			defaultMarkGeocode: false
		})
		.on('markgeocode', function(e) {
			var bbox = e.geocode.bbox;
			var poly = L.polygon([
			bbox.getSouthEast(),
			bbox.getNorthEast(),
			bbox.getNorthWest(),
			bbox.getSouthWest()
			]);

			mymap.fitBounds(poly.getBounds());
		}).addTo(mymap);
	<?php
}

else {
	?>
	// Google Maps API -->
	// init vars
	var map            = null;
	var marker         = null;
	var markers        = [];
	var update_timeout = null;
	var geocoder;

	// place latitude and longitude
	var myLatlng = new google.maps.LatLng(<?= $lat ?>, <?= $lng ?>);

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

			marker = createMarker(event.latLng, "name", "<b><?= $txt_location ?></b><br>" + event.latLng);
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
	// End Google Maps API -->
	<?php
}
?>

/*--------------------------------------------------
Upload Logo
--------------------------------------------------*/
(function() {
	// generate a click on the hidden input file field
	$('#upload-logo-btn').on('click', function(e){
		e.preventDefault();
		$('#logo_img').val("");
		$('#logo_img').trigger('click');
	});

	// upload logo img
	$('#logo_img').on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('logo_img');
		var file = fileInput.files[0];
		var existing_logo = $('#existing_logo').val();

		var formData = new FormData();
		formData.append('logo_img', file);
		formData.append('existing_logo', existing_logo);

		$.ajax({
			url: "<?= $baseurl ?>/user/process-upload-logo.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// remove current logo pic
				$('#logo-img').empty();

				// Add preloader
				$('<div class="thumbs-preloader" id="logo-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#logo-img');
			},
			success: function(data) {
				console.log(data);

				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed because of non allowed ext
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#logo-preloader').remove();

				// remove current logo pic
				$('#logo-img').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var logo_img = '<img src="' + data.message + '" class="rounded" width="132">';

					// display uploaded pic's thumb
					$('#logo-img').append(logo_img);

					// add hidden input field
					$('#uploaded_logo').val(data.filename);
				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#logo-img').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="upload-failed"></div>').appendTo('#logo-img').text(e);
			}
		});
	}));
}());

/*--------------------------------------------------
Delete Logo
--------------------------------------------------*/
(function() {
	$('#delete-logo-btn').on('click', function(e){
		e.preventDefault();

		var logo_img = $('#uploaded_logo').val();
		var existing_logo = $('#existing_logo').val();
		var post_url = '<?= $baseurl ?>/user/process-remove-logo.php';

		$.post(post_url, {
			logo_img: logo_img,
			existing_logo: existing_logo
			},
			function(data) {
				console.log(data);
				$('#logo-img').empty();
			}
		);
	});
}());

/*--------------------------------------------------
Upload Pics
--------------------------------------------------*/
(function() {
	// generate a click on the hidden input file field
	$('#upload-button').on('click', function(e){
		e.preventDefault();
		$('#item_img').trigger('click');
	});

	// upload img
	$('#item_img').on('change', function(e) {
		var formData = new FormData();
		var files = $('#item_img').prop('files');

		$.each(files, function(i, file) {
			// generate a random div id
			var random_id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

			// append file
			formData.append('item_img', file);

			$.ajax({
				type: 'POST',
				url: '<?= $baseurl ?>/user/process-upload.php',
				cache: false,
				contentType: false,
				processData: false,
				data : formData,
				beforeSend : function() {
					// remove previous preloader
					$('#upload_failed').remove();

					// Add preloader
					$('<div id="' + random_id + '" class="thumbs-preloader mr-3"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#uploaded');
				},
				success: function(response){
					console.log(response);

					// check if previous upload failed because of non allowed ext
					// #upload_failed div created by onSumit function above
					if ($('#upload_failed').length) {
						$('#upload_failed').remove();
					}

					// delete preloader spinner
					$('#' + random_id).remove();

					if(response == 1) {
						// Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_file_size ?>');
						// cancel upload
						return false;
					}

					else if(response == 10) {
						// Value: 10; custom error code, failed to move file
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload ?>');
						// cancel upload
						return false;
					}

					else if(response == 11) {
						// Value: 11; custom error code, no submit token
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload ?>');
						// cancel upload
						return false;
					}

					else if(response == 12) {
						// Value: 12; custom error code, more than max num pics
						// $('<div id="upload_failed"></div>').appendTo('#uploaded').text('Error: number of uploads exceeded (max <?= $max_pics ?>)');
						// cancel upload
						return false;
					}

					// upload success
					else {
						if($('.thumbs').length < <?= $max_pics ?>) {
							var thumb = '<?= $pic_baseurl ?>/<?= $place_tmp_folder ?>/' + response;

							// check file exists
							$.get(thumb).done(function() {
								// store thumb container div in memory
								var temp_thumb_div = $('<div class="thumbs position-relative mr-3"></div>');

								// display uploaded pic's thumb
								$('<img>').addClass("rounded").attr('src', thumb).attr('width', '132').appendTo(temp_thumb_div);
								$('<div id="delete-' + random_id + '" class="btn-light delete_pic"><small><?= $txt_delete ?></small></div>').appendTo(temp_thumb_div);
								$('<input type="hidden" name="uploads[]">').attr('value', response).appendTo(temp_thumb_div);
								$('#uploaded').append(temp_thumb_div);

								// count pics and enable/disable upload button
								switchUploadButton($('.thumbs').length);

								// unbind click event to previous .delete_pic links and attach again so that the click event is not assigned twice to the same .delete_pic link
								//$('.delete_pic').unbind('click');

								// make delete link work
								$('#delete-' + random_id).on('click', function() {
									// get pic filename from hidden input
									var pic = $(this).next().attr('value');

									// remove div.thumbs
									$(this).parent().fadeOut("fast", function() {
										$(this).remove();

										// re-enable upload button
										switchUploadButton($('.thumbs').length);
									});

									//
									$('<input type="hidden" name="delete_temp_pics[]" />').attr('value', pic).appendTo('#uploaded');

									// delete from tmp_photos table
									var post_url = '<?= $baseurl ?>/user/process-remove-tmp.php';

									$.post(post_url, {
										tmp_filename: response
										},
										function(data) {
											console.log(data);
										}
									);
								});
							}).fail(function() {
								// thumb does not exist
							})
						}
					}
				},
				error: function(err){
					console.log(err);
				}
			})
		});
	});
}());

/*--------------------------------------------------
Delete Pics
--------------------------------------------------*/
(function(){
	$('.delete_existing_pic').on('click', function() {
		// get pic filename from hidden input
		var pic = $(this).next().attr('value');

		// remove div.thumbs
		$(this).parent().fadeOut("fast", function() {
			$(this).remove();
			switchUploadButton($('.thumbs').length);
		});

		// add deleted pic to array
		$('<input type="hidden" name="delete_existing_pics[]">').attr('value', pic).appendTo('#uploaded');

		// re-enable upload button
		$('#upload_button').text('<?= $txt_upload ?>');
	});
}());

/*--------------------------------------------------
Max allowed pics check
--------------------------------------------------*/
(function(){
	switchUploadButton($('.thumbs').length);
}());

// Function to disable upload button if equal max
function switchUploadButton(count) {
	if(count >= <?= $max_pics ?>) {
		$('#upload-button').addClass('disabled').text("<?= $txt_upload_limit ?>");
	} else {
		$('#upload-button').removeClass('disabled').text("<?= $txt_upload_btn ?>");
	}
}

/*--------------------------------------------------
Categories
--------------------------------------------------*/
(function(){
	$('#category_id').select2({
		placeholder: '<?= $txt_select_cat ?>'
	});

	// Copy values to hidden inputs
	$('#category_id').on('change', function() {
		$('#category_id_hidden').val($(this).val());
	});
}());

/*--------------------------------------------------
Cities
--------------------------------------------------*/
(function(){
	<?php
	if($cfg_use_select2) {
		?>
		// init select2 for cities
		$('#city_id').select2({
			ajax: {
				url: '<?= $baseurl ?>/_return_cities_select2.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						query: params.term, // search term
						page: params.page
					};
				}
			},
			escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 1
		});
		<?php
	}
	?>

	// Copy values to hidden inputs
	$('#city_id').on('change', function() {
		$('#city_id_hidden').val($(this).val());
	});
}());

/*--------------------------------------------------
Tooltips
--------------------------------------------------*/
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})

/*--------------------------------------------------
Bootstrap modifications
--------------------------------------------------*/
// Allow Bootstrap dropdown menus to have forms/checkboxes inside,
// and when clicking on a dropdown item, the menu doesn't disappear.
$(document).on('click', '.dropdown-menu.dropdown-menu-form', function(e) {
	e.stopPropagation();
});

/*--------------------------------------------------
Custom Fields
--------------------------------------------------*/
var ajax_request;

(function(){
	// on primary category change
	$('#category_id').on('change', function(e) {
		e.preventDefault();

		// cancel previous ajax_request
		if(typeof(ajax_request) != "undefined"){
			ajax_request.abort();
		}

		// init array
		var cat_ids = [];

		// add primary cat
		cat_ids.push(parseInt($('#category_id').val()));

		// add secondary cats
		$("input[name='cats[]']:checked").each(function (){
			cat_ids.push(parseInt($(this).val()));
		});

		// vars
		var place_id = <?= !empty($place_id) ? $place_id : '0' ?>;
		var post_url = baseurl + '/user/get-custom-fields.php';

		// post
		$.post(post_url, {
			cat_id: cat_ids,
			place_id: place_id,
			from: 'edit',
			custom_fields_ids: '<?= $custom_fields_ids ?>'
			},
			function(data) {
				// remove #custom_fields_ids hidden input
				$('#custom_fields_ids').remove();

				// remove previous #cat-fields
				$('#cat-fields').fadeOut(300, function() { $(this).remove(); });

				// append html response
				$('#custom-fields').append(data).hide().fadeIn();
			}
		);
	});

	// on secondary categories change
	$('.cat-tree-item').on('change', function(e) {
		// cancel previous ajax_request
		if(typeof(ajax_request) != "undefined"){
			ajax_request.abort();
		}

		// init array
		var cat_ids = [];

		// add primary cat
		cat_ids.push(parseInt($('#category_id').val()));

		// add secondary cats
		$("input[name='cats[]']:checked").each(function (){
			cat_ids.push(parseInt($(this).val()));
		});

		// vars
		var place_id = <?= !empty($place_id) ? $place_id : '0' ?>;
		var post_url = baseurl + '/user/get-custom-fields.php';

		// post
		$.post(post_url, {
			cat_id: cat_ids,
			place_id: place_id,
			from: 'edit',
			custom_fields_ids: '<?= $custom_fields_ids ?>'
			},
			function(data) {
				console.log(data);

				// remove #custom_fields_ids hidden input
				$('#custom_fields_ids').remove();

				// remove previous #cat-fields
				$('#cat-fields').fadeOut(300, function() { $(this).remove(); });

				// append html response
				$('#custom-fields').append(data);
			}
		);
	});
}());

/*--------------------------------------------------
Textarea char counter
--------------------------------------------------*/
(function(){
	var text_max = <?= $short_desc_length ?>;
	$('#count_message').html(text_max + ' <?= $txt_remaining ?>');

	$('#short_desc, #specialties').keyup(function() {
	  var text_length = $('#short_desc, #specialties').val().length;
	  var text_remaining = text_max - text_length;

	  $('#count_message').html(text_remaining + ' <?= $txt_remaining ?>');
	});
}());

/*--------------------------------------------------
Videos
--------------------------------------------------*/
(function(){
	$('#add-video').on('click', function(){
		var video_url = $('#video-url').val();
		$('#video-url').val('');
		$('#added-videos').append('<div class="form-row"><div class="col"><input type="text" class="form-control mb-2" name="videos[]" value="' + video_url + '" readonly></div><div class="col-auto"><button type="button" class="btn btn-dark delete-video"><i class="far fa-trash-alt"></i></button></div></div>');

		$('.delete-video').on('click', function(){
			$(this).parent().parent().remove();
		});
	});

	// delete button
	$('.delete-video').on('click', function(){
		$(this).parent().parent().remove();
	});
}());

/*--------------------------------------------------
Custom Functions
--------------------------------------------------*/
// A function to create the marker and set up the event window function
function createMarker(latlng, name, html) {
	var contentString = html;
	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		zIndex: Math.round(latlng.lat()*-100000)<<5
		});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(contentString);
		infowindow.open(map,marker);
		});

	google.maps.event.trigger(marker, 'click');
	return marker;
}

// address input, on blur, set marker
function codeAddress() {
	deleteMarkers();
	var address     = document.getElementById("address").value;
	var postal_code = document.getElementById("postal_code").value;
	var address_postal_code = address + ' ' + postal_code;
	geocoder.geocode( { 'address': address_postal_code}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			map.setZoom(12);
			var marker = new google.maps.Marker({
				map     : map,
				position: results[0].geometry.location
			});

			// add this marker to the array
			markers.push(marker);

			// update hiddent latlng input field
			$("#latlng").val(results[0].geometry.location);

		} else {
			//alert("Geocode was not successful for the following reason: " + status);
		}
	});
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
	setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
	setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
	clearMarkers();
	markers = [];
}
</script>