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
<body class="tpl-user-my-coupons">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('user-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<div class="row mb-5">
				<div class="col-sm-9">
					<h2 class="flex-grow-1"><?= $txt_main_title ?></h2>
				</div>
				<div class="col-sm-3 text-right pr-4">
					<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#create-coupon-modal">
						<i class="fas fa-plus"></i>
						<?= $txt_create ?>
					</button>
				</div>
			</div>

			<?php
			if(!empty($coupons_arr)) {
				foreach($coupons_arr as $k => $v) {
					?>
					<div class="row mb-3 item" id="coupon-<?= $v['coupon_id'] ?>">
						<div class="col-sm-3 mb-3 mb-md-0">
							<a href=""><img class="rounded" src="<?= $v['coupon_img'] ?>"></a>
						</div>

						<div class="col-sm-6 mb-3 mb-md-0">
							<h4><a href="<?= $baseurl ?>/coupon/<?= $v['coupon_id'] ?>"><?= $v['coupon_title'] ?></a></h4>

							<div class="mb-2"><span class="date-sm"><?= $v['coupon_created'] ?></span></div>

							<?= $v['coupon_description'] ?>
						</div>

						<div class="col-sm-3">
							<span class="btn btn-light btn-block mb-2"><?= $v['coupon_expire'] == 'Expired' ? $txt_expired : $v['coupon_expire'] ?></span>

							<span data-toggle="tooltip"	title="<?= $txt_del ?>">
								<button class="btn btn-dark btn-block"
									data-toggle="modal"
									data-target="#remove-coupon-modal"
									data-coupon-id="<?= $v['coupon_id'] ?>">
									<i class="far fa-trash-alt"></i> <?= $txt_delete ?>
								</button>
							</span>

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
				<div class="my-5">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Modal remove coupon -->
<div class="modal fade" id="remove-coupon-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_delete ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_warn ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm btn-remove-coupon" data-coupon-id><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal create coupon -->
<div class="modal fade" id="create-coupon-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_create ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<?php
				if(!empty($user_places)) {
					?>
					<form class="form-create-coupon" method="post">
						<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

						<div class="form-group">
							<label for=""><?= $txt_title ?></label>
							<input type="text" id="coupon_title" class="form-control" name="coupon_title">
						</div>

						<div class="form-group">
							<label for=""><?= $txt_description ?></label>
							<textarea id="coupon_description" class="form-control" name="coupon_description"></textarea>
						</div>

						<div class="form-group block" id="coupon-img-row">
							<input type="file" id="coupon_img" name="coupon_img" style="display:block;visibility:hidden;width:0;height:0;">
							<input type="hidden" name="uploaded_img" id="uploaded_img" value="">

							<?= $txt_img ?><br>

							<div class="mb-3">
								<div id="coupon-img" style="width:<?= $coupon_size[0] ?>;height:<?= $coupon_size[1] ?>"></div>
							</div>

							<div class="coupon-img-controls mb-3">
								<span id="upload-coupon-img" class="btn btn-light btn-sm"><i class="fas fa-plus"></i>
								<?= $txt_upload ?></span>
							</div>
						</div>

						<div class="form-group">
							<label for=""><?= $txt_expire ?></label>
							<input type="date" id="coupon_expire" name="coupon_expire" class="form-control" placeholder="yyyy-mm-dd" required>
						</div>

						<div class="form-group">
							<label for=""><?= $txt_apply_to ?></label>
							<select id="coupon_place_id" name="coupon_place_id" class="form-control">
								<?php
								foreach($user_places as $v) {
									?>
									<option value="<?= $v['place_id'] ?>"><?= e($v['place_name']) ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</form>
					<?php
				}

				else {
					echo $txt_no_listings;
				}
				?>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="create-coupon-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>
