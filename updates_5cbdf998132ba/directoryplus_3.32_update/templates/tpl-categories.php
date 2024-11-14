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
	<h2 class=""><?= $txt_all_cats ?></h2>
</div>

<div class="container mt-5">
	<?php
	if(!empty($option_link)) {
		?>
		<p class="text-right mb-0"><small><?= $option_link ?></small></p>
		<?php
	}

	foreach($cat_tree as $v) {
		?>
		<div class="card mb-3">
			<h5 class="card-header"><a href="<?= $v['cat_link'] ?>" class="text-dark"><?= $v['cat_name'] ?> (<?= isset($cats_arr[$v['cat_id']]['cat_count']) ?  $cats_arr[$v['cat_id']]['cat_count'] : '0' ?>)</a></h5>

			<div class="card-body">
				<div class="row">
					<?php
					if(isset($v['childs'])) {
						foreach($v['childs'] as $v2) {
							?>
							<div class="col-sm-12 col-md-6 col-lg-3">
								<a href="<?= $v2['cat_link'] ?>"><?= $v2['cat_name'] ?> (<?= isset($cats_arr[$v2['cat_id']]['cat_count']) ?  $cats_arr[$v2['cat_id']]['cat_count'] : '0' ?>)</a>

								<?php
								if(isset($v2['childs'])) {
									foreach($v2['childs'] as $v3) {
										?>
										<br><li> <a href="<?= $v3['cat_link'] ?>"><?= $v3['cat_name'] ?> (<?= isset($cats_arr[$v3['cat_id']]['cat_count']) ?  $cats_arr[$v3['cat_id']]['cat_count'] : '0' ?>)</a>
										<?php
									}
								}
								?>
							</div>
							<?php
						}
					}

					else {
						?>
						<div class="col-12">
							<a href="<?= $v['cat_link'] ?>"><?= $txt_view_all ?></a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>