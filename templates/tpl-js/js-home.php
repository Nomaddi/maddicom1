<!-- SplideJS -->
<script src="<?= $baseurl ?>/templates/js/splide_2.4.21/splide.min.js"></script>

<script>
'use strict';

/*--------------------------------------------------
Add to Favorites
--------------------------------------------------*/
$(function() {
    addToFavorites();
});

/*--------------------------------------------------
Navbar initial setup responsiveness
--------------------------------------------------*/
(function(){
	if($(window).width() < 769) {
		$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo.png');
		$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-dark');
		$('#navbarBtnGetListed').removeClass().addClass('btn btn-block text-dark');
		$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-dark');
		$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-dark');
		$('#navbarUserDropdown').removeClass().addClass('btn btn-block text-dark');
		$('.navbar-toggler').removeClass().addClass('navbar-toggler text-dark');
	}

	else {
		$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo-white.png');
		$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-white');
		$('#navbarBtnGetListed').removeClass().addClass('btn btn-block btn-outline-light');
		$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-white');
		$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-white');
		$('#navbarUserDropdown').removeClass().addClass('btn btn-block btn-outline-light');
		$('.navbar-toggler').removeClass().addClass('navbar-toggler text-white');
	}
}());

/*--------------------------------------------------
Navbar On resize
--------------------------------------------------*/
function onResizeChange() {
	if($(window).width() < 769) {
		$('#header-nav').removeClass('transparent').addClass('solid');
		$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo.png');
		$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-dark');
		$('#navbarBtnGetListed').removeClass().addClass('btn btn-block text-dark');
		$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-dark');
		$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-dark');
		$('#navbarUserDropdown').removeClass().addClass('btn btn-block text-dark');
		$('.navbar-toggler').removeClass().addClass('navbar-toggler text-dark');
	}

	else {
		if(document.documentElement.scrollTop > 500) {
			$('#header-nav').removeClass('transparent').addClass('solid');
		}

		else {
			$('#header-nav').removeClass('solid').addClass('transparent');
		}

		$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo-white.png');
		$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-white');
		$('#navbarBtnGetListed').removeClass().addClass('btn btn-block btn-outline-light');
		$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-white');
		$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-white');
		$('#navbarUserDropdown').removeClass().addClass('btn btn-block btn-outline-light');
		$('.navbar-toggler').removeClass().addClass('navbar-toggler text-white');
	}
};

var resizeTimer;

$(window).on('resize', function() {
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(onResizeChange, 1);
});

/*--------------------------------------------------
Navbar On Scroll
--------------------------------------------------*/
function navbarSwitcher(el) {
	// cache the logo element
	const logo  = el.querySelector('.logo');

	// cache the magic words
	const SOLID  = 'solid';
	const TRANSPARENT = 'transparent';

	// define our state variables
	let scrolling = false;
	let theme = TRANSPARENT;

	// define our different sources for easy access later
	const sources = {
		solid:  "<?= $baseurl ?>/assets/imgs/logo.png",
		transparent: "<?= $baseurl ?>/assets/imgs/logo-white.png"
	};

	// preload the images to prevent jank
	document.body.insertAdjacentHTML('beforeend',
		'<div style="display: none!important">'	+
			'<img src="<?= $baseurl ?>/assets/imgs/logo.png">' +
			'<img src="<?= $baseurl ?>/assets/imgs/logo-white.png">' +
		'</div>'
	);

	// define our scroll handler
	const scrollHandler = _ => setTimeout(_ => {
		// if we are already handling a scroll event, we don't want to handle this one.
		if (scrolling) return;
		scrolling = true;

		// determine which theme should be shown based on scroll position
		//const new_theme = document.documentElement.scrollTop > 500 ? SOLID : TRANSPARENT;
		const new_theme = window.scrollY > 500 ? SOLID : TRANSPARENT;

		// if the current theme is the theme that should be shown, cancel execution
		if (new_theme === theme) {
			scrolling = false;
			return;
		}

		// change logo
		if($(window).width() > 768) {
			logo.src = sources[new_theme];
			el.classList.remove(theme);
			el.classList.add(new_theme);
		}

		// change menu text color
		if(new_theme == SOLID) {
			$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo.png');
			$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-dark');
			$('#navbarBtnGetListed').removeClass().addClass('btn btn-block btn-outline-dark');
			$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-dark');
			$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-dark');
			$('#navbarUserDropdown').removeClass().addClass('btn btn-block text-dark');
			$('.navbar-toggler').removeClass().addClass('navbar-toggler text-dark');
		}

		if(new_theme == TRANSPARENT) {
			if($(window).width() > 768) {
				$('.logo').attr('src', '<?= $baseurl ?>/assets/imgs/logo-white.png');
				$('#navbarBtnSignIn').removeClass().addClass('btn btn-block text-white');
				$('#navbarBtnGetListed').removeClass().addClass('btn btn-block btn-outline-light');
				$('#navbarBtnCreateListing').removeClass().addClass('btn btn-block text-white');
				$('#navbarExploreDropdown').removeClass().addClass('btn btn-block text-white');
				$('#navbarUserDropdown').removeClass().addClass('btn btn-block btn-outline-light');
				$('.navbar-toggler').removeClass().addClass('navbar-toggler text-white');
			}
		}

		// update the state variables with the current state
		theme = new_theme;
		scrolling = false;
	});

	// assign the event listener to the window
	window.addEventListener('scroll', scrollHandler);
	window.addEventListener('load', scrollHandler);
}

// attach the plugin to the element
navbarSwitcher(document.querySelector('#header-nav'));

/*--------------------------------------------------
Raty
--------------------------------------------------*/
<?php
if($cfg_enable_reviews) {
	?>
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		starType: 'i'
	});
}());
<?php
}
?>

/*--------------------------------------------------
Carousel
--------------------------------------------------*/

<?php
if(!empty($featured_listings)) {
	?>
	document.addEventListener('DOMContentLoaded', function() {
		var $carousel = new Splide('#featured-listings', {
			type: 'loop',
			loop: true,
			perPage: 3,
			perMove: 1,
			gap: 30,
			autoplay: true,
			interval: 1000,
			pagination: false,
			waitForTransition: false,
			speed: 1600,
			breakpoints: {
				0: {
					perPage: 1,
				},
				577: {
					perPage:1
				},
				769: {
					perPage:2
				},
				1000: {
					perPage:3,
				},
				1536: {
					perPage:4,
				},
				1920: {
					perPage:5,
				},
				2560: {
					perPage:6,
				}
			}
		});

		$carousel.mount();
	});

	// make all cards in the carousel have the equal height
	document.addEventListener('DOMContentLoaded', function() {
		var maxHeight = 0;

		// find tallest card
		$('.splide__list .card').each(function(){
		   var thisH = $(this).height();
		   if (thisH > maxHeight) { maxHeight = thisH; }
		});

		// apply height to all cards
		$('.splide .card').each(function(){
		   $(this).height(maxHeight);
		});
	});
	<?php
}
?>
</script>
