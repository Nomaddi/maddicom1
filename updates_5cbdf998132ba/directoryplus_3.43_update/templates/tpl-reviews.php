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
				<a href="<?= $baseurl ?>/profile/<?= $profile_id ?>" class="nav-link text-dark" role="tab" aria-controls="listings" aria-selected="true"><?= $txt_listings ?></a>
			</li>
			<?php
			if($cfg_enable_reviews) {
				?>
				<li class="nav-item mr-1">
					<a href="<?= $baseurl ?>/reviews/<?= $profile_id ?>" class="nav-link active text-dark" role="tab" aria-controls="reviews"><?= $txt_reviews ?></a>
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
		if(!empty($reviews)) {
			?>
			<div class="container">
				<?php
				foreach($reviews as $k => $v) {
					if(!(empty($v['place_name']))) {
						?>
						<div class="row mb-3">
							<div class="col-sm-2" id="<?= $v['place_id'] ?>">
								<a href="<?= $v['link_url'] ?>">
									<img class="rounded" alt="<?= $v['place_name'] ?>" src="<?= $v['thumb_url'] ?>">
								</a>
							</div>

							<div class="col-sm-7">
								<h4><a href="<?= $v['link_url'] ?>"><?= $v['place_name'] ?></a></h4>

								<div class="item-rating" data-rating="<?= $v['rating'] ?>">
									<!-- raty plugin placeholder -->
								</div>

								<div class="review-pubdate"><?= $v['pubdate'] ?></div>

								<p><?php echo nl2p(ucfirst($v['text'])) ?></p>
							</div>
						</div>
						<?php
					}
				}
				?>
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