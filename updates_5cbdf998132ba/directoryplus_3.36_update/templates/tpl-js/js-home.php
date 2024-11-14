<script>
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
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		starType: 'i'
	});
}());

/*--------------------------------------------------
Owlcarousel
--------------------------------------------------*/
(function(){
	// get carousel element
	var owl = $('.owl-carousel');

	// carousel options
	owl.owlCarousel({
		loop: true,
		margin: 30,
		autoplay: true,
		autoplayTimeout: 3000,
		autoplaySpeed: 1000,
		responsive: {
			0: {
				items:1
			},
			577:{
				items:2
			},
			769:{
				items:3
			},
			1000:{
				items:4,
			}
		}
	});

	// move slide buttons to .owl-stage-outer so that buttons are correctly positioned
	$('.slideNext').appendTo('.owl-stage-outer');
	$('.slidePrev').appendTo('.owl-stage-outer');

	// carousel navigation buttons
	$('.slideNext').on('click', function() {
		owl.trigger('next.owl.carousel');
	})

	$('.slidePrev').on('click', function() {
		// With optional speed parameter
		// Parameters has to be in square bracket '[]'
		owl.trigger('prev.owl.carousel', [300]);
	})

	// adjust cards height
	$(window).on('load resize', function() {
		var maxHeight = 0;

		// find tallest card
		$('.owl-carousel .card').each(function(){
		   var thisH = $(this).height();
		   if (thisH > maxHeight) { maxHeight = thisH; }
		});

		// apply same height to all cards
		$('.owl-carousel .card').each(function(){
		   $(this).height(maxHeight);
		});
	});
}());

$(document).ready(function() {
	var maxHeight = 0;

	// find tallest card
	$('.owl-carousel .card').each(function(){
	   var thisH = $(this).height();
	   if (thisH > maxHeight) { maxHeight = thisH; }
	});

	// apply same height to all cards
	$('.owl-carousel .card').each(function(){
	   $(this).height(maxHeight);
	});
});
</script>
