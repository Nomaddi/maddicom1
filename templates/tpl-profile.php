<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once('header.php') ?>

<div class="container mt-5">
	<div class="d-sm-flex mb-5">
		<div class="profile-pic mr-sm-3 text-center mb-3 mb-sm-0">
			<img src="<?= $profile_pic ?>" class="cover main-profile-pic rounded-circle">
		</div>

		<div class="profile-info">
			<div class="profile-info-name mb-3 text-center text-sm-left">
				<h1 class="profile-name mb-0" style="font-size:1.6rem;font-weight:600"><?= $profile_display_name ?></h1>
				<small><?= $txt_joined_on ?></small>
			</div>

			<div class="profile-info-details text-center text-sm-left">
				<button type="button" class="btn btn-light" data-toggle="modal" data-target="#contact-user-modal">
					<small class="text-dark"><strong><i class="lar la-envelope"></i> <?= $txt_contact_user ?></strong></small>
				</button>
			</div>
		</div>
	</div>

	<div class="mb-3">
		<ul class="nav nav-tabs" id="nav-tab" role="tablist">
			<li class="nav-item mr-1">
				<a href="<?= $baseurl ?>/profile/<?= $profile_id ?>" class="nav-link active text-dark" role="tab" aria-controls="listings" aria-selected="true"><?= $txt_listings ?></a>
			</li>
			<?php
			if($cfg_enable_reviews) {
				?>
				<li class="nav-item mr-1">
					<a href="<?= $baseurl ?>/reviews/<?= $profile_id ?>" class="nav-link text-dark" role="tab" aria-controls="reviews"><?= $txt_reviews ?></a>
				</li>
				<?php
			}
			?>
			<li class="nav-item mr-1">
				<a href="<?= $baseurl ?>/favorites/<?= $profile_id ?>" class="nav-link text-dark" role="tab" aria-controls="favorites"><?= $txt_favorites ?></a>
			</li>
		</ul>
	</div>

	<div class="row mb-5">
		<?php
		if(!empty($items)) {
			?>
			<div class="container">
				<div class="row">
					<?php
					foreach($items as $v) {
						$feat_class = $v['is_feat'] ? 'featured' : '';
						$feat_badge = $v['is_feat'] ? '<span class="featured-badge">' . $txt_featured . '</span>' : '';
						?>
						<div class="col-lg-3 col-md-4 col-sm-6 mb-5">
							<div class="card h-100 border-0 <?= $feat_class ?>">
								<div class="card-img-container mb-2">
									<a href="<?= $v['place_link'] ?>" class="mb-2">
										<img class="rounded" alt="<?= $v['place_name'] ?>" src="<?= $v['thumb_url'] ?>">
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
														<?= $v['place_city_name'] ?>, <?= $v['place_state_abbr'] ?>
													</div>

													<div class="item-rating" data-rating="<?= $v['rating'] ?>"></div>
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

		else {
			echo $txt_no_results;
		}
		?>

		<nav>
			<ul class="pagination flex-wrap">
				<?php
				if($total_rows > 0) {
					include_once(__DIR__ . '/../inc/pagination.php');
				}
				?>
			</ul>
		</nav>
	</div>
</div>

<?php
include_once(__DIR__ . '/modal-contact-user.php');
?>

<?php require_once('footer.php') ?>

</body>
</html>