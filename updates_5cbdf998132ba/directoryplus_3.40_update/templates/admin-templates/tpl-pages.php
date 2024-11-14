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
				<strong><?= $txt_action ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/create-page" class="btn btn-light btn-sm create-cat-btn"><?= $txt_create_page ?></a>
			</div>

			<?php
			if(!empty($pages_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/pages-trash"><?= $txt_trash ?></a></div>
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
								<a href="<?= $baseurl ?>/admin/pages?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

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
								<a href="<?= $baseurl ?>/admin/pages?sort=<?= $sort_param ?>">
								<?= $txt_title ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'title') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'title-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_action ?>
							</th>
						</tr>

						<?php
						foreach($pages_arr as $k => $v) {
							?>
							<tr id="tr-page-<?= $v['page_id'] ?>">
								<td class="text-nowrap shrink"><?= $v['page_id'] ?></td>
								<td><a href="<?= $v['page_link'] ?>" target="_blank"><?= $v['page_title'] ?></a></td>
								<td class="text-nowrap shrink">
									<span data-toggle="tooltip" title="<?= $txt_edit_page ?>">
										<a href="<?= $baseurl ?>/admin/edit-page?id=<?= $v['page_id'] ?>" class="btn btn-light btn-sm edit-page-btn"
											data-id="<?= $v['page_id'] ?>">
											<i class="las la-pen"></i>
										</a>
									</span>

									<span data-toggle="tooltip"	title="<?= $txt_remove_page ?>">
										<button class="btn btn-light btn-sm remove-page"
											data-id="<?= $v['page_id'] ?>">
											<i class="lar la-trash-alt"></i>
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
					<div><a href="<?= $baseurl ?>/admin/pages-trash"><?= $txt_trash ?></a></div>
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
var num_items = <?= count($pages_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Remove page
--------------------------------------------------*/
(function(){
	$('.remove-page').on('click', function(e) {
		e.preventDefault();

		// vars
		var page_id  = $(this).data('id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-page.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-page-' + page_id;

		// post
		$.post(post_url, { page_id: page_id	}, function(data) {
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
					var removed_row = $('<td colspan="3"></td>');
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