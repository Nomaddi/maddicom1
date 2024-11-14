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

			<div class="mb-3">
				<strong><?= $txt_show ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/reviews?show=pending" class="btn btn-light btn-sm"><?= $txt_pending ?></a>
			</div>

			<?php
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></div>
					<div class=""><a href="<?= $baseurl ?>/admin/reviews-trash"><?= $txt_trash ?></a></div>
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
								<a href="<?= $baseurl ?>/admin/reviews?sort=<?= $sort_param ?>&show=<?= $show ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th></th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'title';
								if($sort == 'title') $sort_param = 'title-desc';
								if($sort == 'title-desc') $sort_param = 'title';
								?>
								<a href="<?= $baseurl ?>/admin/reviews?sort=<?= $sort_param ?>&show=<?= $show ?>">
								<?= $txt_place_name ?>
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
								<a href="<?= $baseurl ?>/admin/reviews?sort=<?= $sort_param ?>&show=<?= $show ?>">
								<?= $txt_date ?>
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
								$sort_param = 'user';
								if($sort == 'user') $sort_param = 'user-desc';
								if($sort == 'user-desc') $sort_param = 'user';
								?>
								<a href="<?= $baseurl ?>/admin/reviews?sort=<?= $sort_param ?>&show=<?= $show ?>">
								<?= $txt_user ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'user') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'user-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_action ?>
							</th>
						</tr>

						<?php
						foreach($reviews_arr as $k => $v) {
							?>
							<tr id="tr-review-<?= $v['review_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['review_id'] ?></td>
								<td class="text-nowrap shrink">
								<img src="<?= $v['author_pic_url'] ?>" class="rounded-circle min-h-40 min-w-40" width="40"></td>
								<td><a href="<?= $v['link_url'] ?>" target="_blank"><?= $v['place_name'] ?></a></td>
								<td class="text-nowrap shrink"><?= $v['pubdate'] ?></td>
								<td class="text-nowrap shrink"><?= $v['author_name'] ?></td>
								<td class="text-nowrap shrink">
									<?php
									if($v['status'] == 'pending') {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_tooltip_toggle_approved ?>">
											<button class="btn btn-light btn-sm approve-review"
												id="status-review-<?= $v['review_id'] ?>"
												data-review-id="<?= $v['review_id'] ?>"
												data-status="pending">
												<i class="fas fa-toggle-off"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_tooltip_toggle_approved ?>">
											<button class="btn btn-success btn-sm approve-review"
												id="status-review-<?= $v['review_id'] ?>"
												data-review-id="<?= $v['review_id'] ?>"
												data-status="approved">
												<i class="fas fa-toggle-on"></i>
											</button>
										</span>
										<?php
									}
									?>

									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand_review ?>">
										<button class="btn btn-light btn-sm expand-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-expand"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_review ?>">
										<button class="btn btn-light btn-sm remove-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="far fa-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<tr id="expand-review-<?= $v['review_id'] ?>" class="review-text">
								<td></td>
								<td colspan="5" class="wrap">
									<strong><?= $v['author_name'] ?></strong>
									<div class="review-text-wrapper"><?= $v['text'] ?></div>
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
					<div><a href="<?= $baseurl ?>/admin/reviews-trash"><?= $txt_trash ?></a></div>
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
var num_items = <?= count($reviews_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Expand review
--------------------------------------------------*/
(function(){
	// hide all reviews' texts
	$('.review-text').hide();

	$('.expand-review').on('click', function(e) {
		e.preventDefault();

		var review_id = $(this).data('review-id');
		$('#expand-review-' + review_id).toggle();
	});
}());

/*--------------------------------------------------
Remove review
--------------------------------------------------*/
(function(){
	$('.remove-review').on('click', function(e) {
		e.preventDefault();

		// vars
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-review.php';
		var num_rows = $('.total-rows').text();
		var wrapper = '#tr-review-' + review_id;

		// post
		$.post(post_url, { review_id: review_id	}, function(data) {
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
					var removed_row = $('<td colspan="6"></td>');
					$(removed_row).text(data);
					$(removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});
}());

/*--------------------------------------------------
Review status
--------------------------------------------------*/
(function(){
	$('.approve-review').on('click', function(e) {
		e.preventDefault();

		// vars
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-toggle-review-status.php';
		var status = $(this).data('status');

		// post
		$.post(post_url, { review_id: review_id, status: status	}, function(data) {
				if(data == 'on') {
					$('#status-review-' + review_id).removeClass('btn-light');
					$('#status-review-' + review_id).addClass('btn-success');
					$('#status-review-' + review_id + ' i').removeClass('fa-toggle-off');
					$('#status-review-' + review_id + ' i').addClass('fa-toggle-on');
					$('#status-review-' + review_id).data('status', 'approved');
				}

				if(data == 'off') {
					$('#status-review-' + review_id).removeClass('btn-success');
					$('#status-review-' + review_id).addClass('btn-light');
					$('#status-review-' + review_id + ' i').removeClass('fa-toggle-on');
					$('#status-review-' + review_id + ' i').addClass('fa-toggle-off');
					$('#status-review-' + review_id).data('status', 'pending');
				}
			}
		);
	});
}());
</script>

</body>
</html>