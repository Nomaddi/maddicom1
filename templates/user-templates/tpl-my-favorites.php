<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>
</head>
<body class="tpl-user-my-listings">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<?php
			if($total_rows > 0) {
				foreach($list_items as $k => $v) {
					?>
					<div class="row mb-3 item" data-listing-id="<?= $v['place_id'] ?>">
						<div class="col-sm-3 mb-3 mb-md-0">
							<a href="<?= $v['link_url'] ?>">
								<img class="rounded" alt="<?= $v['place_name'] ?>" src="<?= $v['photo_url'] ?>">
							</a>
						</div>

						<div class="col-sm-6 mb-3 mb-md-0">
							<h4><a href="<?= $v['link_url'] ?>"><?= $v['place_name'] ?></a></h4>

							<div class="user-item-pubdate">
								<span class="date-sm"><?= $v['submission_date'] ?></span>
							</div>

							<div class=""><?= $v['description'] ?></div>
						</div>

						<div class="col-sm-3">
							<!-- controls -->
							<button class="btn btn-light btn-block remove-favorite" data-place-id="<?= $v['place_id'] ?>">
								<i class="lar la-trash-alt"></i>
								<?= $txt_remove ?>
							</button>
						</div>
					</div>

					<hr>
					<?php
				}
				?>
				<nav>
					<ul class="pagination flex-wrap">
						<?php
						if($total_rows > 0) {
							include_once(__DIR__ . '/../../inc/pagination.php');
						}
						?>
					</ul>
				</nav>
			<?php
			}

			else {
				?>
				<?= $txt_no_results ?>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>