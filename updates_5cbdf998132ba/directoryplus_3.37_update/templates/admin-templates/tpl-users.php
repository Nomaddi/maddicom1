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
				<form class="form-inline" action="<?= $baseurl ?>/admin/users" method="get">
					<input type="hidden" name="sort" value="<?= $sort ?>">
					<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" id="s" name="s">

					<button type="submit" class="btn btn-primary btn-sm mb-2"><?= $txt_search ?></button>
				</form>
			</div>

			<div class="mb-3">
				<p><strong><?= $txt_sort ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/users?sort=name" class="btn btn-light btn-sm"><?= $txt_by_name ?></a>
				<a href="<?= $baseurl ?>/admin/users?sort=email" class="btn btn-light btn-sm"><?= $txt_by_email ?></a>
				<a href="<?= $baseurl ?>/admin/users?sort=date" class="btn btn-light btn-sm"><?= $txt_by_date ?></a>
				</p>
			</div>

			<?php
			if(!empty($users_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/users-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table">
						<tr>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date-asc';
								if($sort == 'date-asc') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date-asc';
								?>
								<a href="<?= $baseurl ?>/admin/users?sort=<?= $sort_param ?>">
								<?= $txt_id ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'date-asc') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th></th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'name-asc';
								if($sort == 'name-asc') $sort_param = 'name-desc';
								if($sort == 'name-desc') $sort_param = 'name-asc';
								?>
								<a href="<?= $baseurl ?>/admin/users?sort=<?= $sort_param ?>">
								<?= $txt_name ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'name-asc') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'name-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'email-asc';
								if($sort == 'email-asc') $sort_param = 'email-desc';
								if($sort == 'email-desc') $sort_param = 'email-asc';
								?>
								<a href="<?= $baseurl ?>/admin/users?sort=<?= $sort_param ?>">
								<?= $txt_email ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'email-asc') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'email-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?php
								$sort_param = 'date-asc';
								if($sort == 'date-asc') $sort_param = 'date-desc';
								if($sort == 'date-desc') $sort_param = 'date-asc';
								?>
								<a href="<?= $baseurl ?>/admin/users?sort=<?= $sort_param ?>">
								<?= $txt_created ?>
								<?php
								$sort_icon = '<i class="fas fa-sort"></i>';
								if($sort == 'date-asc') $sort_icon = '<i class="fas fa-sort-up"></i>';
								if($sort == 'date-desc') $sort_icon = '<i class="fas fa-sort-down"></i>';

								echo $sort_icon;
								?>
								</a>
							</th>
							<th class="text-nowrap">
								<?= $txt_action ?>
							</th>
						</tr>
						<?php
						foreach($users_arr as $k => $v) {
							?>
							<tr id="tr-user-<?= $v['id'] ?>">
								<td class="text-nowrap shrink"><?= $v['id'] ?></td>
								<td class="text-nowrap shrink"><a href="<?= $baseurl ?>/profile/<?= $v['id'] ?>" target="_blank"><img src="<?= $v['prof_pic_url'] ?>" id="profile-pic-<?= $v['id'] ?>" class="cover rounded-circle min-h-40 min-w-40"></a></td>
								<td class="text-nowrap"><a href="<?= $baseurl ?>/profile/<?= $v['id'] ?>" target="_blank" class="text-dark"><?= $v['name'] ?></a></td>
								<td class="text-nowrap shrink"><?= $v['email'] ?></td>
								<td class="text-nowrap shrink"><?= $v['created'] ?></td>
								<td class="text-nowrap shrink">
									<?php
									if($v['status'] == 'pending') {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-light btn-sm approve-user"
												id="status-user-<?= $v['id'] ?>"
												data-user-id="<?= $v['id'] ?>"
												data-status="pending">
												<i class="fas fa-toggle-off"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-success btn-sm approve-user"
												id="status-user-<?= $v['id'] ?>"
												data-user-id="<?= $v['id'] ?>"
												data-status="approved">
												<i class="fas fa-toggle-on"></i>
											</button>
										</span>
										<?php
									}
									?>

									<span id="profile-pic-btn-<?= $v['id'] ?>" data-toggle="tooltip" title="<?= $txt_approve_profile_pic ?>">
										<button class="btn btn-light btn-sm"
											data-id="<?= $v['id'] ?>"
											data-toggle="modal"
											data-target="#profile-pic-modal"
											data-profile-id="<?= $v['id'] ?>"
											data-profile-pic-folder="<?= $v['prof_pic_folder'] ?>"
											data-profile-pic-filename="<?= $v['prof_pic_url'] ?>">
											<i class="fas fa-camera"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_remove_user ?>">
										<a href="" class="btn btn-light btn-sm remove-user"
											data-user-id="<?= $v['id'] ?>">
											<i class="far fa-trash-alt"></i>
										</a>
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
					<div><a href="<?= $baseurl ?>/admin/users-trash"><?= $txt_trash ?></a></div>
				</div>
				<div><?= $txt_no_results ?></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Profile picture modal -->
<div id="profile-pic-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_approve_profile_pic ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="button" class="btn btn-light btn-sm pic-delete" data-dismiss="modal" data-delete-id><?= $txt_delete ?></button>
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
Profile pic
--------------------------------------------------*/
(function(){
	var do_reload = false;

	// on hide modal
	$('#profile-pic-modal').on('hide.bs.modal', function (e) {
		if(do_reload) location.reload(true);
	});

	// on show modal
	$('#profile-pic-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var profile_id = button.data('profile-id');
		var filename = button.data('profile-pic-filename');
		var modal = $(this);

		modal.find('.pic-approve').attr('data-approve-id', profile_id);
		modal.find('.pic-delete').attr('data-delete-id', profile_id);
		$('.modal-body').empty();
		$('.modal-body').prepend('<img src="' + filename + '" class="modal-profile-pic">');
	});

	// delete profile pic
	$('.pic-delete').on('click', function(e) {
		e.preventDefault();

		// vars
		var delete_id = $(this).attr('data-delete-id');
		var post_url = '<?= $baseurl ?>' + '/admin/moderate-profile-pic.php';
		var btn_wrapper = '#profile-pic-btn-' + delete_id;
		var default_profile_pic = '<?= $baseurl ?>/assets/imgs/blank.png';

		$.post(post_url, { delete_id: delete_id, operation: 'delete' },	function(data) {
				if(data == '1') {
					$('#profile-pic-' + delete_id).attr('src', default_profile_pic);
				} else {
					console.log(data);
				}
			}
		);
	});
}());

/*--------------------------------------------------
Remove user
--------------------------------------------------*/
(function(){
	$('.remove-user').on('click', function(e) {
		e.preventDefault();

		// vars
		var user_id = $(this).data('user-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-user.php';
		var num_rows = $('.total-rows').text();
		var wrapper = '#tr-user-' + user_id;

		// post
		$.post(post_url, { user_id: user_id	}, function(data) {
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
User status
--------------------------------------------------*/
(function(){
	$('.approve-user').on('click', function(e) {
		e.preventDefault();

		// vars
		var user_id = $(this).data('user-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-toggle-user-status.php';
		var status = $(this).data('status');

		// post
		$.post(post_url, { user_id: user_id, status: status }, function(data) {
				if(data == 'approved') {
					$('#status-user-' + user_id).removeClass('btn-light');
					$('#status-user-' + user_id).addClass('btn-success');
					$('#status-user-' + user_id + ' i').removeClass('fa-toggle-off');
					$('#status-user-' + user_id + ' i').addClass('fa-toggle-on');
					$('#status-user-' + user_id).data('status', 'approved');
				}

				if(data == 'pending') {
					$('#status-user-' + user_id).removeClass('btn-success');
					$('#status-user-' + user_id).addClass('btn-light');
					$('#status-user-' + user_id + ' i').removeClass('fa-toggle-on');
					$('#status-user-' + user_id + ' i').addClass('fa-toggle-off');
					$('#status-user-' + user_id).data('status', 'pending');
				}
			}
		);
	});
}());
</script>

</body>
</html>