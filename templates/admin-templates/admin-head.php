<?php
if(file_exists(__DIR__ . '/admin-head-child.php') && basename(__FILE__) != 'admin-head-child.php') {
	include_once('admin-head-child.php');
	return;
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="robots" content="noindex">

<!-- favicon -->
<link rel="apple-touch-icon-precomposed" href="<?= $baseurl ?>/favicon/favicon-152.png">
<link rel="icon" type="image/png" href="<?= $baseurl ?>/assets/favicon/favicon.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?= $baseurl ?>/favicon/favicon-144.png">

<!-- css -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/line-awesome/css/line-awesome.min.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/styles.css">

<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jinplace/1.2.1/jinplace.min.js"></script>

<script>
var baseurl = '<?= $baseurl ?>/admin';

// add CSRF token in the headers of all requests
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': '<?= session_id() ?>',
		'X-AJAX-Setup': 1
    }
});
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
				console.log(data);

				if(data == 'added') {
					el.empty().html('<i class="las la-heart"></i>');
				}

				if(data == 'removed') {
					el.empty().html('<i class="lar la-heart"></i>');
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
