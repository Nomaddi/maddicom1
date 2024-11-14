
<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $page_title ?></title>
<meta name="description" content="<?= $meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once(__DIR__ . '/../inc/inc-social-media.php') ?>
<?php require_once('header.php') ?>

<div class="container mt-5">
	<h2 class=""><?= $page_title ?></h2>
</div>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-8 page-content mb-3">
			<?= $page_contents ?>

			<!-- Disqus -->
			<?php
			if($enable_comments) {
				include_once(__DIR__ . '/../inc/disqus.php');
			}
			?>
		</div>

		<!-- Sidebar -->
		<div class="col-md-4">
			<!-- Share listing -->
			<h5 class="mb-3"><?= $txt_share ?></h5>

			<div class="mb-5">
				<div class="fb-share-button"
					data-href="<?= $canonical ?>"
					data-layout="button"
					data-size="small"
					data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical) ?>&src=sdkpreparse" class="fb-xfbml-parse-ignore"><?= $txt_share ?></a>
				</div>

				<a class="twitter-share-button" href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical) ?>&text=<?= strip_tags(get_snippet($page_contents)) ?>..."><?= $txt_tweet ?></a>
			</div>

			<h5 class="mb-3"><?= $txt_search_pages ?></h5>

			<form class="mb-5" method="get" action="<?= $baseurl ?>/posts">
				<div class="form-group">
					<input type="text" id="" class="form-control" name="term">
				</div>
				<button class="btn btn-primary btn-block"><?= $txt_search ?></button>
			</form>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>