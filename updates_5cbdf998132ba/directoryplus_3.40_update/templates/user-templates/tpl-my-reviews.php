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
<body class="tpl-user-my-reviews">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php require_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<?php
			if(!empty($reviews)) {
				$count = 1;
				foreach($reviews as $k => $v) {
					if(!(empty($v['place_name']))) {
						?>
						<div class="row mb-3 user-item" id="review-<?= $v['review_id'] ?>">
							<div class="col-sm-2">
								<a href="<?= $v['link_url'] ?>"><img src="<?= $v['thumb_url'] ?>" class="rounded"></a>
							</div>

							<div class="col-sm-7 user-item-description">
								<h4><a href="<?= $v['link_url'] ?>"><?= $v['place_name'] ?></a></h4>

								<div class="review-pubdate"><span class="date-sm"><?= $v['pubdate'] ?></span></div>

								<div
									class="editable"
									data-type="textarea"
									data-url="<?= $baseurl ?>/user/process-edit-review.php"
									data-activator="#activator-<?= $v['review_id'] ?>"
									data-attribute="<?= $v['review_id'] ?>"
								>
									<?php echo nl2p(ucfirst($v['text'])) ?>
								</div>
							</div>

							<div class="col-sm-3">
								<button class="btn btn-light btn-block mb-2"
									data-toggle="modal"
									data-target="#remove-review-modal"
									data-review-id="<?= $v['review_id'] ?>"><i class="lar la-trash-alt"></i> <?= $txt_remove_review ?>
								</button>

								<button id="activator-<?= $v['review_id'] ?>" class="btn btn-dark btn-block">
									<i class="las la-pen"></i> <?= $txt_edit_review ?>
								</button>
							</div>
						</div>

						<hr>
						<?php
					}
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
				<div class="my-5">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Modal Remove Review -->
<div class="modal fade" id="remove-review-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove_review ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_confirm ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary remove-review" data-dismiss="modal" data-remove-id><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>
