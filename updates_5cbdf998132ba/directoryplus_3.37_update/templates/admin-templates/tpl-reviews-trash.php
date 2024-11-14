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
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
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
								<a href="<?= $baseurl ?>/admin/reviews-trash?sort=<?= $sort_param ?>&show=<?= $show ?>">
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
								<a href="<?= $baseurl ?>/admin/reviews-trash?sort=<?= $sort_param ?>&show=<?= $show ?>">
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
								<a href="<?= $baseurl ?>/admin/reviews-trash?sort=<?= $sort_param ?>&show=<?= $show ?>">
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
								<a href="<?= $baseurl ?>/admin/reviews-trash?sort=<?= $sort_param ?>&show=<?= $show ?>">
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
									<!-- expand review btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand_review ?>">
										<button class="btn btn-light btn-sm expand-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-expand"></i>
										</button>
									</span>

									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
										<button class="btn btn-light btn-sm restore-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_review ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-review-modal"
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
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Remove review modal -->
<div id="remove-review-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_remove_review ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-review" data-dismiss="modal" data-review-id><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty trash modal -->
<div id="empty-trash-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_empty_trash ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure_all ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm"><?= $txt_empty ?></button>
			</div>
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

	// expand review
	$('.expand-review').on('click', function(e) {
		e.preventDefault();

		var review_id = $(this).data('review-id');
		$('#expand-review-' + review_id).toggle();
	});
}());

/*--------------------------------------------------
Restore review
--------------------------------------------------*/
(function(){
    $('.restore-review').on('click', function(e){
		e.preventDefault();

		// vars
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-review.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-review-' + review_id;

		// post
		$.post(post_url, { review_id: review_id }, function(data) {
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
Remove review permanently
--------------------------------------------------*/
(function(){
	// on show modal
	$('#remove-review-modal').on('show.bs.modal', function(e) {
		// vars
		var button = $(e.relatedTarget);
		var review_id = button.data('review-id');

		// add review_id value to button
		$('#remove-review-modal .remove-review').attr('data-review-id', review_id);
	});

	// remove button in modal clicked
	$('.remove-review').on('click', function(e) {
		e.preventDefault();

		// vars
		var review_id = $('.remove-review').attr('data-review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-review-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-review-' + review_id;

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
Empty trash
--------------------------------------------------*/
(function(){
	// only reload if form is submitted
	var do_reload = false;

	// on show modal
	$('#empty-trash-modal').on('show.bs.modal', function (e) {
		// show default message
		$('#empty-trash-modal .modal-body').empty();
		$('#empty-trash-modal .modal-body').html('<?= $txt_remove_perm_sure_all ?>').fadeIn();
	});

	// on hide modal
	$('#empty-trash-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-reviews.php';

		// post
		$.post(post_url, {}, function(data) {
				if(data == '1') {
					// set reload to true for on hide modal event
					do_reload = true;

					// reload
					location.reload(true);
				} else {
					// show error message
					$('#empty-trash-modal .modal-body').empty();
					$('#empty-trash-modal .modal-body').html(data).fadeIn();
				}
			}
		);
    });
}());
</script>

</body>
</html>