<?php
if($is_tpl_listing) {
	include_once(__DIR__ . '/js-listing.php');
}
?>
<script>
/*--------------------------------------------------
Navbar
--------------------------------------------------*/
(function(){
	<?php
	if($cfg_use_select2) {
		?>
		// select2 config
		$('#city-input').select2({
			ajax: {
				url: '<?= $baseurl ?>/_return_cities_select2.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						query: params.term,
						page: params.page
					};
				},
				headers: {
					'X-CSRF-Token': '<?= session_id() ?>',
					'X-Ajax-Setup': 1
				}
			},
			escapeMarkup: function (markup) { return markup; },
			minimumInputLength: 1,
			dropdownAutoWidth : true,
			placeholder: "<?= $txt_city ?>",
			allowClear: true,
			language: "<?= $html_lang ?>"
		});

		// change x mark and add event handler to clear cookies
		$('#city-input').on("select2:unselect", function(e) {
			delete_cookie('city_id');
		});

		$('#city-input').on("select2:select", function(e) {
			$('.select2-selection__clear').empty().html('<i class="fas fa-times" aria-hidden="true"></i>');
		});
	<?php
	}
	?>

	// add margin top in full screen mode
	if($(window).width() > 768) {
		// get header height
		var height = $('#header-nav').outerHeight(true);

		// add margin equal to height
		$('#mainSearch').removeAttr('style').css({
			"margin-top": height,
			"display": "none"
		});
	}

	// in mobile view..
	else {
		// remove class fixed-top
		$('#mainSearch').removeClass().addClass('container-fluid p-2');

		// get header height
		var height = $('#header-nav').outerHeight(true);

		// add margin equal to height
		$('#mainSearch').removeAttr('style').css({
			"margin-top": height,
			"display": "none"
		});

		//$('#header-nav-dummy').hide();
	}

	// when clicking outside search form, hide search form
	$('#mainSearch').on('click', function(e) {
		e.stopPropagation();
	});

	$(window).on('click', function(e) {
		if(e.target.type != 'search' && e.target.type != '') {
			$('#mainSearch').slideUp('fast');
		}
	});

	// toggle search visibility
	$('#navbarBtnSearch').on('click', function(e) {
		// stop click propagation so that it doesn't bubble up and trigger a click on window which would hide the search
		e.stopPropagation();

		if($(window).width() < 769) {
			// add margin equal to height
			var height = $('#header-nav').outerHeight(true);
			var height_dummy = $('#header-nav-dummy').outerHeight(true);

			height = height - height_dummy;
			console.log($('#mainSearch').attr('style'));

			$('#mainSearch').removeAttr('style').css({
				"margin-top": height,
				"display": "none"
			});

			//$('#header-nav-dummy').hide();
		}

		// toggle proper
		$('#mainSearch').slideToggle('fast');
	});

	<?php
	if(!empty($_COOKIE['city_id'])) {
		$option_text = !empty($_COOKIE['city_name']) ? $_COOKIE['city_name'] : '';
		$option_text .= !empty($_COOKIE['state_abbr']) ? ', ' . $_COOKIE['state_abbr'] : '';
		?>
		// preselect city in search bar, if cookie exists
		// unselect any selected option
		$("#city-input option:selected").prop("selected", false);

		// create the option and append to select field
		var option = new Option('<?= $option_text ?>', "<?= $_COOKIE['city_id'] ?>", true, true);

		$('#city-input').prepend(option).trigger('change');

		<?php
		if($cfg_use_select2) {
			?>
			// manually trigger the `select2:select` event
			$('#city-input').trigger({
				type: 'select2:select',
				params: {
					city_id: <?= $_COOKIE['city_id'] ?>
				}
			});
			<?php
		}
	}
	?>

	<?php
	if(!empty($_SESSION['search_city_id'])) {
		$option_text = !empty($_SESSION['search_city_name']) ? $_SESSION['search_city_name'] : '';
		$option_text .= !empty($_SESSION['search_state_abbr']) ? ', ' . $_SESSION['search_state_abbr'] : '';
		?>
		// preselect city in search bar, if search session exists
		// unselect any selected option
		$("#city-input option:selected").prop("selected", false);

		// create the option and append to Select2
		var option = new Option('<?= $option_text ?>', "<?= $_SESSION['search_city_id'] ?>", true, true);

		$('#city-input').prepend(option).trigger('change');

		<?php
		if($cfg_use_select2) {
			?>
			// manually trigger the select2:select event
			$('#city-input').trigger({
				type: 'select2:select',
				params: {
					city_id: <?= $_SESSION['search_city_id'] ?>
				}
			});
			<?php
		}
	}
	?>
}());

/*--------------------------------------------------
Language selector
--------------------------------------------------*/
(function(){
	$('#language-selector select').on('change', function(e){
		createCookie('user_language', $('#language-selector select').val(), 365);
		location.reload(true);
	});
}());
</script>