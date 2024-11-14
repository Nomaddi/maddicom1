<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $site_name ?></title>

<!-- SplideJS CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/splide_2.4.21/splide.min.css">

<?php require_once('head.php') ?>

<!-- Meta data -->
<meta name="description" content="<?= $txt_meta_desc ?>">

<!-- Canonical URL -->
<link rel="canonical" href="<?= $canonical ?>">
</head>
<body class="tpl-<?= $route[0] ?>">
<?php
if($maintenance_mode == 1 && $is_admin) {
	?>
	<div class="maintenance-mode-note badge badge-warning" style="position:fixed;top:0;right:0;z-index:10000">Maintenance mode is on</div>
	<?php
}
?>
<div class="preloader"></div>

<!-- Navbar -->
<nav id="header-nav" class="navbar navbar-expand-md fixed-top">
	<!-- Brand -->
	<a class="navbar-brand" href="<?= $baseurl ?>">
		<img class="logo" src="<?= $baseurl ?>/assets/imgs/logo-white.png" alt="<?= $site_name ?>" width="<?= $site_logo_width ?>">
	</a>

	<!-- Toggler button -->
	<button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<i class="las la-bars"></i>
	</button>

	<!-- Navbar collapse -->
	<div id="navbarSupportedContent" class="collapse navbar-collapse text-white mr-md-5">
		<ul class="navbar-nav ml-auto">
			<?php
			// user is logged in
			if(!empty($_SESSION['user_connected'])) {
				?>
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/user/select-plan" id="navbarBtnCreateListing" class="btn btn-block text-white"><i class="las la-pen"></i> <?= $txt_create_listing ?></a>
				</li>

				<li class="nav-item dropdown mr-md-3">
					<a href="#" id="navbarExploreDropdown" class="btn btn-block text-white" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-ellipsis-v"></i> <?= $txt_explore ?>
					</a>

					<div class="dropdown-menu dropdown-menu-right zoomIn animated" aria-labelledby="exploreDropdown">
						<a class="dropdown-item" href="<?= $baseurl ?>/categories/"><?= $txt_categories ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/coupons/"><?= $txt_coupons ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/posts"><?= $txt_blog ?></a>
					</div>
				</li>

				<li class="nav-item dropdown">
					<a href="#" id="navbarUserDropdown" class="btn btn-block btn-outline-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="lar la-user"></i> <?= $txt_user ?>
					</a>

					<div class="dropdown-menu dropdown-menu-right zoomIn animated" aria-labelledby="navbarUserDropdown">
						<a class="dropdown-item" href="<?= $baseurl ?>/user/"><?= $txt_dashboard ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/user/select-plan"><?= $txt_create_listing ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/user/sign-out"><?= $txt_signout ?></a>
						<?php
						if($is_admin) {
							?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $baseurl ?>/admin"><?= $txt_admin ?></a>
							<?php
						}
						?>
					</div>
				</li>
				<?php
			}

			// user is not logged in
			else {
				?>
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/user/sign-in" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="las la-sign-in-alt"></i> <?= $txt_signin ?></a>
				</li>

				<li class="nav-item dropdown mr-md-3">
					<a href="#" id="navbarExploreDropdown" class="btn btn-block text-white" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-ellipsis-v"></i> <?= $txt_explore ?>
					</a>

					<div class="dropdown-menu dropdown-menu-right zoomIn animated" aria-labelledby="navbarExploreDropdown">
						<a class="dropdown-item" href="<?= $baseurl ?>/categories/"><?= $txt_categories ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/coupons/"><?= $txt_coupons ?></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= $baseurl ?>/posts"><?= $txt_blog ?></a>
					</div>
				</li>

				<li class="nav-item">
					<a href="<?= $baseurl ?>/user/register" id="navbarBtnGetListed" class="btn btn-block btn-outline-light"><?= $txt_get_listed ?></a>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
</nav>

<!-- Jumbotron -->
<div class="jumbotron d-flex text-center" style="background-image: url('<?= $cfg_hero_img ?>');">
	<div class="container my-auto text-center">
		<h1 class="mb-2 text-white"><?= $txt_phrase_01 ?></h1>

		<p class="lead text-white"><?= $txt_phrase_02 ?></p>

		<form action="<?= $baseurl ?>/results" class="form-row" method="get">
			<div class="form-row container-fluid">
				<div class="col-md-5">
					<div class="input-group mb-2 mr-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text bg-white"><i class="las la-search"></i></span>
						</div>
						<input type="text" class="form-control form-control-lg" id="s" name="s" placeholder="<?= $txt_keyword ?>">
					</div>
				</div>

				<div class="col-md-5">
					<div class="input-group mb-2 mr-md-2 text-left">
						<select class="form-control form-control-lg" id="city-input" name="city">
							<?php
							if(!$cfg_use_select2) {
								?>
								<option disabled selected></option>
								<?php
								$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
								$stmt->execute();

								while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									?>
									<option value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?>, <?= $row['state'] ?></option>
									<?php
								}
							}

							else {
								?>
								<option value="" disabled selected><?= $txt_city ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<button type="submit" class="btn btn-lg btn-primary btn-block mb-2"><?= $txt_search ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Categories -->
<?php
if(!empty($cats)) {
	?>
	<div class="container my-5">
		<div class="d-flex justify-content-center">
			<h2><?= $txt_phrase_03 ?></h2>
		</div>
	</div>

	<div class="container mb-5">
		<div class="row">
			<?php
			foreach($cats as $v) {
				?>
				<div class="col-sm-6 col-lg-3 text-center mb-4">
					<?php
					if(!isset($cfg_cat_display) || $cfg_cat_display == 'image') {
						?>
						<div class="card text-white">
							<a href="<?= $baseurl ?>/listings/<?= $v['cat_slug'] ?>" title="<?= $v['plural_name'] ?>" class="text-white">
								<img class="card-img" src="<?= $v['cat_img'] ?>" alt="<?= $v['plural_name'] ?>">
								<div class="card-img-overlay">
									<h5 class="card-title text-white"><?= $v['plural_name'] ?></h5>
								</div>
							</a>
						</div>
						<?php
					}

					else {
						?>
						<div class="card">
							<a href="listings/<?= $v['cat_slug'] ?>" title="<?= $v['plural_name'] ?>">
								<div class="">
									<span style="font-size:48px"><?= $v['cat_icon'] ?></span>
									<p><span class="card-title"><?= $v['plural_name'] ?></span></p>
								</div>
							</a>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>

<!-- Featured Listings -->
<?php
if(!empty($featured_listings)) {
	?>
	<div class="container my-5">
		<div class="d-flex justify-content-center">
			<h2><?= $txt_phrase_04 ?></h2>
		</div>
	</div>

	<div class="container position-relative mb-2">
		<div id="featured-listings" class="splide">
			<div class="splide__arrows">
				<button class="splide__arrow splide__arrow--prev">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
				</button>
				<button class="splide__arrow splide__arrow--next">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66.91 122.88" style="enable-background:new 0 0 66.91 122.88" xml:space="preserve" class="filter-shadow"><g><path d="M1.95,111.2c-2.65,2.72-2.59,7.08,0.14,9.73c2.72,2.65,7.08,2.59,9.73-0.14L64.94,66l-4.93-4.79l4.95,4.8 c2.65-2.74,2.59-7.11-0.15-9.76c-0.08-0.08-0.16-0.15-0.24-0.22L11.81,2.09c-2.65-2.73-7-2.79-9.73-0.14 C-0.64,4.6-0.7,8.95,1.95,11.68l48.46,49.55L1.95,111.2L1.95,111.2L1.95,111.2z"/></g></svg>
				</button>
			</div>

			<div class="splide__track">
				<div class="splide__list">
					<?php
					foreach($featured_listings as $v) {
						?>
						<div class="splide__slide">
							<div class="card mb-4 bg-low-contrast-blue">
								<div class="card-img-container mb-2">
									<a href="<?= $v['place_link'] ?>">
										<img class="" alt="<?= $v['place_name'] ?>" src="<?= $v['photo_url'] ?>">
									</a>

									<div class="cat-name-figure rounded p-2"><?= $v['cat_name'] ?></div>
								</div>

								<div class="card-body">
									<div class="d-flex flex-column">
										<div class="flex-grow-1">
											<h5 class="mb-0"><a href="<?= $v['place_link'] ?>"><?= $v['place_name'] ?></a></h5>
											<span><?= $v['place_addr'] ?></span>
											<br><span><?= $v['city_name'] ?>, <?= $v['state_abbr'] ?></span>
											<br><div class="item-rating" data-rating="<?= $v['avg_rating'] ?>"></div>
										</div>

										<?= $v['place_spec'] ?>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>

<!-- Latest Listings -->
<?php
if(!empty($latest_listings)) {
	?>
	<div class="container my-5">
		<div class="d-flex justify-content-center">
			<h2><?= $txt_phrase_05 ?></h2>
		</div>
	</div>

	<div class="container mb-2">
		<div class="row">
			<?php
			foreach($latest_listings as $v) {
				$feat_class = $v['is_feat'] ? 'featured' : '';
				$feat_badge = $v['is_feat'] ? '<span class="featured-badge">' . $txt_featured . '</span>' : '';
				?>
				<div class="col-lg-3 col-md-4 col-sm-6 mb-5">
					<div class="card h-100 border-0 <?= $feat_class ?>">
						<div class="card-img-container mb-2">
							<a href="<?= $v['place_link'] ?>" class="mb-2">
								<img class="rounded" alt="<?= $v['place_name'] ?>" src="<?= $v['photo_url'] ?>">
							</a>

							<div class="cat-name-figure rounded p-2"><?= $v['cat_name'] ?></div>
						</div>

						<div class="card-body p-0">
							<div class="d-flex flex-column" style="height:100%;">
								<a href="<?= $v['place_link'] ?>" class="d-block">
									<div class="d-flex mb-2">
										<div class="flex-grow-1">
											<h5 class="mb-0"><?= $v['place_name'] ?> <?= $feat_badge ?></h5>
											<div class="latest-listings-address" style="font-size:0.8rem">
												<?= $v['city_name'] ?>, <?= $v['state_abbr'] ?>
											</div>

											<div class="item-rating" data-rating="<?= $v['avg_rating'] ?>"></div>
										</div>
									</div>
								</a>

								<div class="mt-auto text-right">
									<div class="btn pointer">
										<span class="add-to-favorites" data-listing-id=<?= $v['place_id'] ?>><i class="<?= in_array($v['place_id'], $favorites) ? 'las' : 'lar' ?> la-heart"></i></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>

<!-- Near Listings -->
<?php
if(!empty($near_listings)) {
	?>
	<div class="container my-5">
		<div class="d-flex justify-content-center">
			<h2><?= $txt_phrase_07 ?></h2>
		</div>
	</div>

	<div class="container mb-2">
		<div class="row">
			<?php
			foreach($near_listings as $v) {
				?>
				<div class="col-lg-3 col-md-4 col-sm-6 mb-5">
					<div class="card h-100 border-0">
						<div class="card-img-container mb-2">
							<a href="<?= $v['place_link'] ?>" class="mb-2">
								<img class=" rounded" alt="<?= $v['place_name'] ?>" src="<?= $v['photo_url'] ?>">
							</a>

							<div class="cat-name-figure rounded p-2"><?= $v['cat_name'] ?></div>
						</div>

						<div class="card-body p-0" style="height:100%;">
							<a href="<?= $v['place_link'] ?>" class="d-block">
								<div class="d-flex mb-2">
									<div class="flex-grow-1">
										<h5 class="mb-0"><?= $v['place_name'] ?></h5>
										<span><?= $v['place_addr'] ?></span>
										<br><span><?= $v['city_name'] ?>, <?= $v['state_abbr'] ?></span>
										<br><div class="item-rating" data-rating="<?= $v['avg_rating'] ?>"></div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>

<!-- Featured Cities -->
<?php
if(!empty($featured_cities)) {
	?>
	<div class="container my-5">
		<div class="d-flex justify-content-center">
			<h2><?= $txt_phrase_06 ?></h2>
		</div>
	</div>

	<div class="container mb-5">
		<div class="row">
			<?php
			foreach($featured_cities as $v) {
				?>
				<div class="col-lg-3 col-md-4 col-sm-6 text-center mb-4">
					<div class="card text-white">
						<a href="<?= $baseurl ?>/listings/<?= $v['state_slug'] ?>/<?= $v['city_slug'] ?>" class="text-white">
							<img class="card-img" src="<?= $v['city_pic'] ?>" alt="<?= $v['city_name'] ?>">
							<div class="card-img-overlay">
								<h5 class="card-title text-white"><?= $v['city_name'] ?></h5>
							</div>
						</a>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>

<!-- footer -->
<?php require_once('footer.php') ?>
</body>
</html>