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
			if(!empty($users_arr)) {
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
								<a href="<?= $baseurl ?>/admin/users-trash?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"></th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'name';
								if($sort == 'name') $sort_param = 'name-desc';
								if($sort == 'name-desc') $sort_param = 'name';
								?>
								<a href="<?= $baseurl ?>/admin/users-trash?sort=<?= $sort_param ?>">
								<?= $txt_name ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'name') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'name-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'email';
								if($sort == 'email') $sort_param = 'email-desc';
								if($sort == 'email-desc') $sort_param = 'email';
								?>
								<a href="<?= $baseurl ?>/admin/users-trash?sort=<?= $sort_param ?>">
								<?= $txt_email ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'email') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'email-desc') $sort_icon = '<i class="las la-sort-down"></i>';

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
								<a href="<?= $baseurl ?>/admin/users-trash?sort=<?= $sort_param ?>">
								<?= $txt_created ?>
								<?php
								$sort_icon = '<i class="las la-sort"></i>';
								if($sort == 'date') $sort_icon = '<i class="las la-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="las la-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap"><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($users_arr as $k => $v) {
							?>
							<tr id="tr-user-<?= $v['id'] ?>">
								<td class="text-nowrap shrink"><?= $v['id'] ?></td>
								<td class="text-nowrap shrink"><a href="<?= $baseurl ?>/profile/<?= $v['id'] ?>" target="_blank"><img src="<?= $v['prof_pic_url'] ?>" class="cover rounded-circle min-h-40 min-w-40"></a></td>
								<td><a href="<?= $baseurl ?>/profile/<?= $v['id'] ?>" target="_blank" class="text-dark"><?= $v['name'] ?></a></td>
								<td class="text-nowrap shrink"><?= $v['email'] ?></td>
								<td class="text-nowrap shrink"><?= $v['created'] ?></td>
								<td class="text-nowrap shrink">
									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
										<button class="btn btn-light btn-sm restore-user"
											data-user-id="<?= $v['id'] ?>">
											<i class="las la-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_user ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-user-modal"
											data-user-id="<?= $v['id'] ?>">
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
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>

		</div>
	</div>
</div>

<!-- Remove user modal -->
<div id="remove-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_modal_remove_title ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-user" data-dismiss="modal" data-user-id><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty trash modal -->
<div id="empty-trash-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_empty ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
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
var num_items = <?= count($users_arr) ?>;

// define actual page size on page load
if(num_items < page_size)  page_size = num_items;

/*--------------------------------------------------
Restore user
--------------------------------------------------*/
(function(){
    $('.restore-user').on('click', function(e){
		e.preventDefault();

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-user.php';
		var user_id = $(this).data('user-id');
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-user-' + user_id;

		// post
		$.post(post_url, { user_id: user_id }, function(data) {
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
Remove user permanently
--------------------------------------------------*/
(function(){
	// on show modal
	$('#remove-user-modal').on('show.bs.modal', function(e) {
		// vars
		var button = $(e.relatedTarget);
		var user_id = button.data('user-id');

		// add user_id value to button
		$('#remove-user-modal .remove-user').attr('data-user-id', user_id);
	});

	// remove button in modal clicked
	$('.remove-user').on('click', function(e) {
		e.preventDefault();

		// vars
		var user_id = $('.remove-user').attr('data-user-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-user-perm.php';
		var num_rows = $('.total-rows').text();
		var wrapper  = '#tr-user-' + user_id;

		// post
		$.post(post_url, { user_id: user_id }, function(data) {
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

	// empty button in modal clicked
    $('.empty-trash-confirm').on('click', function(e){
		e.preventDefault();

		// vars
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-users.php';

		// post
		$.post(post_url, { from_check: 'admin-users-trash' }, function(data) {
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