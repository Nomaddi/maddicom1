<?php
if(file_exists(__DIR__ . "/footer-child-$html_lang.php") && basename(__FILE__) != "footer-child-$html_lang.php") {
	include_once("footer-child-$html_lang.php");
	return;
}

if(file_exists(__DIR__ . '/footer-child.php') && basename(__FILE__) != 'footer-child.php') {
	include_once('footer-child.php');
	return;
}

// show footer
if($route[0] != 'listings' && $route[0] != 'results') {
	?>
	<div class="container-fluid">
		<footer class="pt-4 my-md-5 pt-md-5 border-top">
			<div class="row">
				<div class="col-12 col-md">
					<small class="d-block mb-3 text-muted">© 2018-2019</small>

					<?php include(__DIR__ . '/../inc/widget-language-selector.php') ?>
				</div>

				<div class="col-6 col-md">
					<h5>Features</h5>
					<ul class="list-unstyled text-small">
						<li><a href="#">Fast Loading</a></li>
						<li><a href="#">Custom Fields</a></li>
						<li><a href="#">SEO Optimized</a></li>
						<li><a href="#">Coupons</a></li>
						<li><a href="#">Paypal/Stripe</a></li>
					</ul>
				</div>

				<div class="col-6 col-md">
					<h5>Credits</h5>
					<ul class="list-unstyled text-small">
						<li><a href="https://unsplash.com/">Unsplash</a></li>
						<li><a href="https://www.freepik.com/">Freepik</a></li>
						<li><a href="https://fontawesome.com/">Fontawesome 5</a></li>
						<li><a href="https://getbootstrap.com/">Bootstrap 4</a></li>
						<li><a href="https://www.tiny.cloud/">TinyMCE</a></li>
					</ul>
				</div>

				<div class="col-6 col-md">
					<h5>About</h5>
					<ul class="list-unstyled text-small">
						<li><a href="<?= $baseurl ?>/contact">Contact</a></li>
						<li><a href="https://codecanyon.net/item/directoryplus-business-directory/22658605">DirectoryPlus</a></li>
						<li><a href="<?= $baseurl ?>/post/privacy-policy">Privacy</a></li>
						<li><a href="<?= $baseurl ?>/post/tou">Terms</a></li>
					</ul>
				</div>
			</div>
		</footer>
	</div>
<?php
}
?>

<!-- css -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">

<!-- external javascript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="<?= $baseurl ?>/templates/js/raty/jquery.raty.js"></script>
<script src="<?= $baseurl ?>/assets/js/jquery-autocomplete/jquery.autocomplete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/i18n/<?= $html_lang ?>.js"></script>

<?php
// include tpl-js
if($route[0] != 'user' && $route[0] != 'admin') {
	$js_inc = __DIR__ . '/tpl-js/js-' . $route[0] . '.php';
}

// if in the 'user' folder
if($route[0] == 'user') {
	$js_inc = __DIR__ . '/tpl-js/user-js/js-' . $route[1] . '.php';
}

// if in the 'admin' folder
if($route[0] == 'admin') {
	$js_inc = __DIR__ . '/tpl-js/admin-js/js-' . $route[1] . '.php';
}

if(file_exists($js_inc)) {
	include_once($js_inc);
}

// global js-footer
if(file_exists(__DIR__ . '/tpl-js/js-footer.php')) {
	include_once(__DIR__ . '/tpl-js/js-footer.php');
}