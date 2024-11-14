<!-- Bootstrap Lightbox -->
<script src="<?= $baseurl ?>/templates/js/lightbox-master/dist/ekko-lightbox.min.js"></script>

<!-- SplideJS -->
<script src="<?= $baseurl ?>/templates/js/splide_2.4.21/splide.min.js"></script>

<!-- SplideJs Video Extension -->
<script src="<?= $baseurl ?>/templates/js/splide-extension-video_0.4.6/splide-extension-video.min.js"></script>

<script>
/*--------------------------------------------------
Add to Favorites
--------------------------------------------------*/
(function(){
	$('.add-to-favorites').on('click', function(e){
		<?php
		if(!empty($_SESSION['user_connected'])) {
			?>
			var el = $(this);
			var listing_id = $(this).data('listing-id');
			var post_url = '<?= $baseurl ?>/user/process-add-to-favorites.php';

			// ajax post
			$.post(post_url, { listing_id: listing_id }, function(data) {
				console.log(data);

				if(data == 'added') {
					el.empty().html('<i class="fas fa-heart"></i> <?= $txt_add_to_favorites ?>');
				}

				if(data == 'removed') {
					el.empty().html('<i class="far fa-heart"></i> <?= $txt_add_to_favorites ?>');
				}
			},
			{ headers: { 'X-CSRF-Token': '<?= session_id() ?>', 'X-Ajax-Setup': 1 }});
			<?php
		}

		else {
			?>
			window.location.href = '<?= $baseurl ?>/user/sign-in';
			<?php
		}
		?>
	});
}());

/*--------------------------------------------------
Lightbox
--------------------------------------------------*/
(function(){
	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
}());

/*--------------------------------------------------
Carousel
--------------------------------------------------*/
<?php
if(!empty($photos) || !empty($videos)) {
	?>
	document.addEventListener('DOMContentLoaded', function() {
		var primarySlider = new Splide( '#primary-slider', {
			type       : 'fade',
			heightRatio: 0.5,
			pagination : false,
			arrows     : true,
			cover      : true,
			video: {
				autoplay: false,
				mute    : true,
			},
		} ); // do not call mount() here.

		var secondarySlider = new Splide( '#secondary-slider', {
			type        : 'loop',
			perPage     : 6,
			gap         : 5,
			height      : 72,
			rewind      : true,
			cover       : true,
			isNavigation: true,
			pagination  : false,
			focus       : 'center',
			breakpoints : {
				'600': {
					fixedWidth: 66,
					height    : 40,
				}
			}
		}).mount();

		primarySlider.sync( secondarySlider ).mount(window.splide.Extensions);
	});
	<?php
}
?>
/*--------------------------------------------------
Rating
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

	$('.review-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});

	$('.raty').raty({
		scoreName: 'review_score',
		target : '#hint',
		targetKeep : true,
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});
}());

/*--------------------------------------------------
Review
--------------------------------------------------*/
(function(){
	$('#submit-review').on('click', function() {
		var place_id        = $('#place_id').val();
		var place_name      = $('#place_name').val();
		var place_slug      = $('#place_slug').val();
		var place_city_slug = $('#place_city_slug').val();
		var place_city_id   = $('#place_city_id').val();
		var review_score    = $('input[name=review_score]').val();
		var review          = $('#review').val();
		var url             = '<?= $baseurl ?>/process-review.php';

		// ajax post
		$.post(url, {
			place_id:        place_id,
			place_name:      place_name,
			place_slug:      place_slug,
			place_city_slug: place_city_slug,
			place_city_id:   place_city_id,
			review_score:    review_score,
			review:          review
		}, function(data) {
			$('#review-form').fadeOut();
			// alert(data);
			var form_wrapper = $('#review-form-wrapper');
			var alert_response = $('<div class="alert alert-success"></div>');
			$(alert_response).text(data);
			$(alert_response).hide().appendTo(form_wrapper).fadeIn();
		},
		{ headers: { 'X-CSRF-Token': '<?= session_id() ?>', 'X-Ajax-Setup': 1 }});
	});
}());

/*--------------------------------------------------
Map
--------------------------------------------------*/
<?php
if($lat != '0.00000000') {
	if($map_provider !== "Google") {
		if($map_provider == 'Tomtom') {
			?>
			var mymap = new L.Map("place-map-canvas", map_provider_options).setView([<?= $lat ?>, <?= $lng ?>], 13);
			<?php
		}

		else {
			?>
			var mymap = L.map("place-map-canvas").setView([<?= $lat ?>, <?= $lng ?>], 13);

			L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
			<?php
		}
		?>

		var marker = L.marker([<?= $lat ?>, <?= $lng ?>]).addTo(mymap);
		<?php
	}

	if($map_provider == "Google") {
		?>
		var myLatlng = new google.maps.LatLng(<?= $lat ?>, <?= $lng ?>);
		var mapOptions = {
		  zoom: 12,
		  center: myLatlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		}
		var map = new google.maps.Map(document.getElementById("place-map-canvas"), mapOptions);

		var marker = new google.maps.Marker({
			position: myLatlng,
			title:""
		});

		// To add the marker to the map, call setMap();
		marker.setMap(map);
	<?php
	}
}
else {
	?>
	$('#place-map-wrapper').hide();
	<?php
}
?>

/*--------------------------------------------------
Contact form
--------------------------------------------------*/
(function(){
	// on show modal
	$('#contact-user-modal').on('show.bs.modal', function (e) {
		$('#contact-user-submit').show();
		$('#contact-user-close').hide();
		$('#contact-user-cancel').show();
	});

	// on hide modal
	$('#contact-user-modal').on('hide.bs.modal', function (e) {
		$('#contact-user-form').show(120);
		$('#contact-user-result').empty();
	});

	// on submit
	$('#contact-user-submit').on('click', function(e) {
		e.preventDefault();

		// check validity
		if($('#contact-user-form')[0].checkValidity()) {
			// vars
			var modal = $('#contact-user-modal');
			var post_url = '<?= $baseurl ?>/send-msg.php';
			var spinner = '<i class="fas fa-spinner fa-spin"></i> <?= $txt_wait ?>';

			// buttons and spinner
			$('#contact-user-result').show();
			$('#contact-user-form').hide(120);
			$('#contact-user-result').html(spinner);
			$('#contact-user-submit').hide();
			$('#contact-user-cancel').hide();
			$('#contact-user-close').show();

			// post
			$.post(post_url, { params: $('#contact-user-form').serialize() }, function(data) {
				$('#contact-user-result').empty().html(data).fadeIn();
			},
			{ headers: { 'X-CSRF-Token': '<?= session_id() ?>', 'X-Ajax-Setup': 1 }});
		}

		else {
			$('#contact-user-form')[0].reportValidity();
		}
	});
}());
</script>