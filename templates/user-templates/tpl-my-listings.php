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

							<?php
							if($v['status'] == 'approved') {
								?>
								<span class="badge badge-pill badge-success mb-2"><?= $txt_approved ?></span>
								<?php
							}

							else {
								?>
								<span class="badge badge-pill badge-dark mb-2"><?= $txt_pending ?></span>
								<?php
							}
							?>

							<div class="user-item-pubdate">
								<span class="date-sm"><?= $v['submission_date'] ?></span>
							</div>

							<div class=""><?= $v['description'] ?></div>
						</div>

						<div class="col-sm-3">
							<!-- controls -->
							<button class="btn btn-light btn-block mb-2"
								data-toggle="modal"
								data-target="#remove-place-modal"
								data-place-id="<?= $v['place_id'] ?>">
								<i class="lar la-trash-alt"></i>
								<?= $txt_remove_place ?>
							</button>

							<a href="<?= $baseurl ?>/user/edit-listing/<?= $v['place_id'] ?>" class="btn btn-dark btn-block edit-place"
								data-edit-id="<?= $v['place_id'] ?>">
								<i class="las la-pen"></i>
								<?= $txt_edit_place ?>
							</a>
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
				<?= $txt_no_activity ?>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Remove Place Modal -->
<div class="modal fade" id="remove-place-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_confirm ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary remove-place" data-dismiss="modal" data-remove-id><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>