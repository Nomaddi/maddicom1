<?php
if(file_exists(__DIR__ . '/head-child.php') && basename(__FILE__) != "head-child.php") {
	include_once('head-child.php');
	return;
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- favicon -->
<link rel="apple-touch-icon-precomposed" href="<?= $baseurl ?>/assets/favicon/favicon-152.png">
<link rel="icon" type="image/png" href="<?= $baseurl ?>/assets/favicon/favicon.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?= $baseurl ?>/assets/favicon/favicon-144.png">

<!-- css -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/styles.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">

<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<?php
if($maintenance_mode == 1 && !$is_admin) return;
?>

<!-- CSRF -->
<script>
// add CSRF token in the headers of all requests
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': '<?= session_id() ?>',
		'X-Ajax-Setup': 1
    }
});
</script>

<!-- baseurl -->
<script>
var baseurl = '<?= $baseurl ?>';
</script>

<!-- custom functions -->
<script>
// test if cookie is enabled
function cookieEnabled() {
	// Quick test if browser has cookieEnabled host property
	if (navigator.cookieEnabled) {
		return true;
	}

	// Create cookie
	document.cookie = "cookietest=1";
	var ret = document.cookie.indexOf("cookietest=") != -1;

	// Delete cookie
	document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

	return ret;
}

// test if localstorage is available
function lsTest(){
    var test = 'test';
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
}

// createCookie
function createCookie(name, value, days) {
    var expires;
    var cookie_path;
	var path = "<?= $install_path ?>";

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    } else {
        expires = "";
    }

	if (path != '') {
		cookie_path = "; path=" + path;
	} else {
		cookie_path = "";
	}

    document.cookie = name + "=" + value + expires + cookie_path;
}

// delete_cookie
function delete_cookie(name) {
	createCookie(name, "", -100);
}

// getCookie
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');

	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}

	return null;
}

// check if string is JSON
function IsJsonString(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}

	return true;
}

// add to Favorites
function addToFavorites() {
	$('.add-to-favorites').on('click', function(e){
		<?php
		if(!empty($_SESSION['user_connected'])) {
			?>
			var el = $(this);
			var listing_id = $(this).data('listing-id');
			var post_url = '<?= $baseurl ?>/user/process-add-to-favorites.php';

			// ajax post
			$.post(post_url, {
				listing_id: listing_id,
			}, function(data) {
				if(data == 'added') {
					el.empty().html('<i class="fas fa-heart"></i>');
				}

				if(data == 'removed') {
					el.empty().html('<i class="far fa-heart"></i>');
				}
			});
			<?php
		}

		else {
			?>
			window.location.href = '<?= $baseurl ?>/user/sign-in';
			<?php
		}
		?>
	});
}
</script>

<!-- Maps -->
<?php
if(in_array($route[0], array('listings', 'listing', 'search', 'results'))
	||
	(isset($route[1]) && in_array($route[1], array('create-listing', 'edit-listing')))
	||
	$is_tpl_listing
	) {
	include_once(__DIR__ . '/../inc/map-provider-options.php');
	?>
	<!-- CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css">
	<?php
	if($map_provider == 'Tomtom') {
		?>
		<link rel='stylesheet' type='text/css' href='<?= $baseurl ?>/assets/js/sdk-tomtom/map.css'/>
		<?php
	}
	?>

	<!-- Javascript -->
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