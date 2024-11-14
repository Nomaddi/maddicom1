<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once(__DIR__ . '/admin-head.php') ?>
</head>
<body class="tpl-admin-<?= $route[1] ?>">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('admin-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<?php
			if(!empty($coupons_arr)) {
				?>
				<div class="d-flex rows-line">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/coupons-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';
								?>
								<a href="<?= $baseurl ?>/admin/coupons?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'title';
								if($sort == 'title') $sort_param = 'title-desc';
								if($sort == 'title-desc') $sort_param = 'title';
								?>
								<a href="<?= $baseurl ?>/admin/coupons?sort=<?= $sort_param ?>">
								<?= $txt_title ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'title') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'title-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date';
								if($sort == 'date') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date';
								?>
								<a href="<?= $baseurl ?>/admin/coupons?sort=<?= $sort_param ?>">
								<?= $txt_created ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'expire';
								if($sort == 'expire') $sort_param = 'expire-desc';
								if($sort == 'expire-desc') $sort_param = 'expire';
								?>
								<a href="<?= $baseurl ?>/admin/coupons?sort=<?= $sort_param ?>">
								<?= $txt_expire ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'expire') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'expire-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_action ?>
							</th>
						</tr>
						<?php
						foreach($coupons_arr as $k => $v) {
							?>
							<tr id="tr-coupon-<?= $v['coupon_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['coupon_id'] ?></td>
								<td>
									<a href="<?= $v['coupon_link'] ?>"><?= $v['coupon_title'] ?></a>
									<br><small><?= $v['place_name'] ?></small>
								</td>
								<td class="text-nowrap shrink"><?= $v['coupon_created'] ?></td>
								<td class="text-nowrap shrink"><?= $v['coupon_expire'] ?></td>
								<td class="text-nowrap shrink">
									<span data-toggle="tooltip" title="<?= $txt_remove ?>">
										<button class="btn btn-light btn-sm remove-coupon"
											data-coupon-id="<?= $v['coupon_id'] ?>">
											<i class="far fa-trash-alt" aria-hidden="true"></i>
										</button>
									</span>
								</td>
							</tr>
						<?php
						}
					?>
					</table>
				</div>

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
				<div class="d-flex">
					<div class="flex-grow-1"></div>
					<div><a href="<?= $baseurl ?>/admin/coupons-trash"><?= $txt_trash ?></a></div>
				</div>
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

// page size configuration
var page_size = '<?= intval($items_per_page) ?>';

// initial number of items
var num_items = <?= count($coupons_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Remove coupon
--------------------------------------------------*/
(function(){
	$('.remove-coupon').on('click', function(e){
		e.preventDefault();

		// vars
		var coupon_id = $(this).data('coupon-id');
		var post_url  = '<?= $baseurl ?>' + '/admin/process-remove-coupon.php';
		var num_rows  = $('.total-rows').text();
		var wrapper   = '#tr-coupon-' + coupon_id;

		// post
		$.post(post_url, { coupon_id: coupon_id }, function(data) {
				if(data == '1') {
					// subtract from the total rows value
					var new_total = parseInt(num_rows) - 1;
					$('.total-rows').text(new_total);

					// page size
					page_size = page_size - 1;

					// hide row
					if (new_total > 0 && page_size > 0) {
						setTimeout(function(){
							$(wrapper).fadeOut('fast');
						}, 100);
					} else {
						window.location.href = '<?= $page_url ?><?= $page - 1 > 0 ? $page - 1 : 1 ?>';
					}
				} else {
					$(wrapper).empty();
					var removed_row = $('<td colspan="5"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});
}());
</script>

</body>
</html>