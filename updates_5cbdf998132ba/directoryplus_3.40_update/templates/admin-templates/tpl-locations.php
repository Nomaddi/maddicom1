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
				<form class="form-inline" action="<?= $baseurl ?>/admin/locations" method="get">
					<input type="hidden" name="sort" value="<?= $sort ?>">
					<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="s">

					<button type="submit" class="btn btn-primary btn-sm mb-2"><?= $txt_search ?></button>
				</form>
			</div>

			<div class="mb-3">
				<strong><?= $txt_show ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/locations?sort=cities" class="btn btn-light btn-sm"><?= $txt_cities ?></a>
				<a href="<?= $baseurl ?>/admin/locations?sort=states" class="btn btn-light btn-sm"><?= $txt_states ?></a>
				<a href="<?= $baseurl ?>/admin/locations?sort=countries" class="btn btn-light btn-sm"><?= $txt_countries ?></a>
			</div>

			<div class="mb-3">
				<strong><?= $txt_action ?>:</strong><br>
				<a href="" class="create-loc-btn btn btn-light btn-sm"
					data-loc-type="city"
					data-modal-title="<?= $txt_create_city ?>"
					data-toggle="modal"
					data-target="#create-loc-modal"
					><?= $txt_create_city ?></a>
				<a href="" class="create-loc-btn btn btn-light btn-sm"
					data-loc-type="state"
					data-modal-title="<?= $txt_create_state ?>"
					data-toggle="modal"
					data-target="#create-loc-modal"
					><?= $txt_create_state ?></a>
				<a href="" class="create-loc-btn btn btn-light btn-sm"
					data-loc-type="country"
					data-modal-title="<?= $txt_create_country ?>"
					data-toggle="modal"
					data-target="#create-loc-modal"
					><?= $txt_create_country ?></a>
			</div>

			<?php
			if($total_rows < 1) {
				echo $txt_no_results;
			}

			else {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></div>
					<!--<div class=""><a href="<?= $baseurl ?>/admin/locations-trash"><?= $txt_trash ?></a></div>-->
				</div>
				<?php
				// show cities
				if($sort == 'cities') {
					?>
					<div class="table-responsive">
						<table class="table admin-table">
							<tr>
								<th class="text-nowrap"><?= $txt_city_id ?></th>
								<th class="text-nowrap"><?= $txt_city_name ?></th>
								<th class="text-nowrap"><?= $txt_state ?></th>
								<th class="text-nowrap"><?= $txt_action ?></th>
							</tr>

							<?php
							foreach($cities_arr as $k => $v) {
								?>
								<tr id="tr-city-<?= $v['city_id'] ?>">
									<td class="text-nowrap shrink"><?= $v['city_id'] ?></td>
									<td class="text-nowrap"><?= $v['city_name'] ?></td>
									<td class="text-nowrap shrink"><?= $v['state_abbr'] ?></td>
									<td class="text-nowrap shrink">
										<!-- featured_home city toggle -->
										<?php
										if(empty($v['is_feat'])) {
											?>
											<span data-toggle="tooltip"	title="<?= $txt_toggle_featured ?>">
												<button class="btn btn-light btn-sm featured-home"
													id="featured-home-<?= $v['city_id'] ?>"
													data-city-id="<?= $v['city_id'] ?>"
													data-city-status="not_featured">
													<i class="las la-home" aria-hidden="true"></i>
												</button>
											</span>
											<?php
										}

										else {
											?>
											<span data-toggle="tooltip"	title="<?= $txt_toggle_featured ?>">
												<button class="btn btn-success btn-sm featured-home"
													id="featured-home-<?= $v['city_id'] ?>"
													data-city-id="<?= $v['city_id'] ?>"
													data-city-status="featured">
													<i class="las la-home" aria-hidden="true"></i>
												</button>
											</span>
											<?php
										}
										?>

										<!-- edit city -->
										<span data-toggle="tooltip" title="<?= $txt_edit_city ?>">
											<button class="btn btn-light btn-sm"
												data-loc-id="<?= $v['city_id'] ?>"
												data-loc-type="city"
												data-toggle="modal"
												data-target="#edit-loc-modal">
												<i class="las la-pen"></i>
											</button>
										</span>

										<!-- remove city -->
										<span data-toggle="tooltip" title="<?= $txt_remove_city ?>">
											<button class="btn btn-light btn-sm remove-loc"
												data-loc-id="<?= $v['city_id'] ?>"
												data-loc-type="city">
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
				<?php
				}

				// show states
				if($sort == 'states') {
					?>
					<div class="table-responsive">
						<table class="table admin-table">
							<tr>
								<th class="text-nowrap"><?= $txt_state_id ?></th>
								<th class="text-nowrap"><?= $txt_state_name ?></th>
								<th class="text-nowrap"><?= $txt_country ?></th>
								<th class="text-nowrap"><?= $txt_action ?></th>
							</tr>
							<?php
							foreach($states_arr as $k => $v) {
								?>
								<tr id="tr-state-<?= $v['state_id'] ?>">
									<td class="text-nowrap shrink"><?= $v['state_id'] ?></td>
									<td><?= $v['state_name'] ?></td>
									<td class="text-nowrap shrink"><?= $v['country_abbr'] ?></td>
									<td class="text-nowrap shrink">
										<!-- edit state -->
										<span data-toggle="tooltip" title="<?= $txt_edit_state ?>">
											<button class="btn btn-light btn-sm"
												data-loc-id="<?= $v['state_id'] ?>"
												data-loc-type="state"
												data-toggle="modal"
												data-target="#edit-loc-modal">
												<i class="las la-pen"></i>
											</button>
										</span>

										<!-- remove state -->
										<span data-toggle="tooltip" title="<?= $txt_remove_state ?>">
											<a href="" class="btn btn-light btn-sm remove-loc"
												data-loc-id="<?= $v['state_id'] ?>"
												data-loc-type="state">
												<i class="lar la-trash-alt"></i>
											</a>
										</span>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
				<?php
				}

				// show countries
				if($sort == 'countries') {
					?>
					<div class="table-responsive">
						<table class="table admin-table">
							<tr>
								<th class="text-nowrap"><?= $txt_country_id ?></th>
								<th class="text-nowrap"><?= $txt_country_name ?></th>
								<th class="text-nowrap"><?= $txt_country_code ?></th>
								<th class="text-nowrap"><?= $txt_action ?></th>
							</tr>
							<?php
							foreach($countries_arr as $k => $v) {
								?>
								<tr id="tr-country-<?= $v['country_id'] ?>">
									<td class="text-nowrap shrink"><?= $v['country_id'] ?></td>
									<td><?= $v['country_name'] ?></td>
									<td class="text-nowrap shrink"><?= $v['country_abbr'] ?></td>
									<td class="text-nowrap shrink">
										<!-- edit country -->
										<span data-toggle="tooltip" title="<?= $txt_edit_country ?>">
											<button class="btn btn-light btn-sm"
												data-loc-id="<?= $v['country_id'] ?>"
												data-loc-type="country"
												data-toggle="modal"
												data-target="#edit-loc-modal">
												<i class="las la-pen"></i>
											</button>
										</span>

										<!-- remove country -->
										<span data-toggle="tooltip" title="<?= $txt_remove_country ?>">
											<a href="" class="btn btn-light btn-sm remove-loc"
												data-loc-id="<?= $v['country_id'] ?>"
												data-loc-type="country">
												<i class="lar la-trash-alt"></i>
											</a>
										</span>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
				<?php
				}
			}
			?>
			<nav>
				<ul class="pagination flex-wrap">
					<?php
					if(isset($pager) && $pager->getTotalPages() > 1) {
						$curPage = $page;

						$startPage = ($curPage < 21)? 1 : $curPage - 20;
						$endPage = 40 + $startPage;
						$endPage = ($pager->getTotalPages() < $endPage) ? $pager->getTotalPages() : $endPage;
						$diff = $startPage - $endPage + 40;
						$startPage -= ($startPage - $diff > 0) ? $diff : 0;

						$startPage = ($startPage == 1) ? 2 : $startPage;
						$endPage = ($endPage == $pager->getTotalPages()) ? $endPage - 1 : $endPage;

						if($total_rows > 0) {
							$page_url = "$baseurl/admin/locations?sort=$sort&page=";

							if(isset($_GET['s'])) {
								$s = urlencode($_GET['s']);
								$page_url = "$baseurl/admin/locations?s=$s&sort=$sort&page=";
							}

							include_once(__DIR__ . '/../../inc/pagination.php');
						}
					}
					?>
				</ul>

				<?php
				if(isset($pager) && $pager->getTotalPages() > 200) {
					$cents = floor($pager->getTotalPages() / 100);
					?>
					<ul class="pagination flex-wrap">
						<li class="page-item"><a href="#" class="page-link"><?= $txt_quick_jump ?></a></li>
						<?php
						for($i = 1; $i <= $cents; $i++) {
							$j = $i * 100;
							?><li class="page-item"><a href="<?php echo $page_url, $j ?>" class="page-link"><?= $j ?></a></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				?>
			</nav>
		</div>
	</div>
</div>

<!-- create loc modal -->
<div id="create-loc-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button type="button" id="create-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="create-loc-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- edit loc modal -->
<div id="edit-loc-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_edit_location ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button id="edit-cancel" type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="edit-loc-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
'use strict';

/*--------------------------------------------------
Create location modal
--------------------------------------------------*/
(function(){
	$('#create-loc-modal').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var loc_type = button.data('loc-type');
		var modal_title = button.data('modal-title');
		var modal = $(this);

		// vars
		var post_url = '<?= $baseurl ?>' + '/admin/modal-create-loc.php';

		// post
		$.post(post_url, { loc_type: loc_type },
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('.modal-title').html(modal_title);

				// listener to add click event to hidden input file field
				$('#upload-city-img').on('click', function(e){
					e.preventDefault();
					$('#city_img').trigger('click');
				});

				$("#city_img").on('change', function(e) {
					// append file input to form data
					var fileInput = document.getElementById('city_img');
					var file = fileInput.files[0];
					var formData = new FormData();
					formData.append('city_img', file);

					$.ajax({
						url: "<?= $baseurl ?>/admin/process-upload-city-img.php",
						type: "POST",
						data: formData,
						contentType: false,
						cache: false,
						processData:false,
						beforeSend : function() {
							// Add preloader
							$('<div class="city-img-preloader"><i class="las la-spinner la-spin"></i></div>').appendTo('#city-img-wrapper');
						},
						success: function(data) {
							// parse json string from response
							var data = JSON.parse(data);

							// check if previous upload failed
							// #upload_failed div created by onSumit function above
							if ($('#upload-failed').length){
								$('#upload-failed').remove();
							}

							// delete preloader spinner
							$('#city-img-preloader').remove();

							// remove current city img
							$('#city-img-wrapper').empty();

							if(data.result == 'success') {
								// create thumbnail src
								var city_img = '<img src="' + data.message + '" width="180">';

								// display uploaded pic's thumb
								$('#city-img-wrapper').append(city_img);

								// add hidden input field
								$('#uploaded_img').val(data.filename);
							}

							else {
								$('<div id="upload-failed"></div>').appendTo('#city-img-wrapper').text(data.message);
							}
						},
						error: function(e) {
								$('<div id="upload-failed"></div>').appendTo('#city-img-wrapper').text(e);
						}
					});
				});
			}
		);
	});

	$('#create-loc-modal').on('hide.bs.modal', function (e) {
		location.reload(true);
	});

	// process create loc
    $('#create-loc-submit').on('click', function(e){
		e.preventDefault();
		var modal = $('#create-loc-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-loc.php';

		$.post(post_url, {
			params: $('form.form-create-loc').serialize(),
			},
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('#create-loc-submit').remove();
				modal.find('#create-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// edit loc modal
	$('#edit-loc-modal').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var loc_id = button.data('loc-id');
		var loc_type = button.data('loc-type');
		var modal = $(this);

		var post_url = '<?= $baseurl ?>' + '/admin/get-loc.php';

		$.post(post_url, { loc_id: loc_id, loc_type: loc_type },
			function(data) {
				modal.find('.modal-body').html(data);
				onShowEdit();
			}
		);
	});

	// process edit loc
    $('#edit-loc-submit').on('click', function(e){
		e.preventDefault();
		var modal = $('#edit-loc-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-loc.php';

		$.post(post_url, {
			params: $('form.form-edit-loc').serialize(),
			},
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('#edit-loc-submit').remove();
				modal.find('#edit-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// edit loc modal on close
	$('#edit-loc-modal').on('hide.bs.modal', function (e) {
		location.reload(true);
	});

	// remove loc
	$('.remove-loc').on('click', function(e) {
		e.preventDefault();
		var loc_id = $(this).data('loc-id');
		var loc_type = $(this).data('loc-type');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-loc.php';

		switch (loc_type) {
			case 'city':
				var wrapper = '#tr-city-' + loc_id;
				break;
			case 'state':
				var wrapper = '#tr-state-' + loc_id;
				break;
			case 'country':
				var wrapper = '#tr-country-' + loc_id;
				break;
		}

		$.post(post_url, {
			loc_id: loc_id,
			loc_type: loc_type
			},
			function(data) {
				if(data) {
					$(wrapper).empty();
					var loc_removed_row = $('<td colspan="6" class="alert alert-success"></td>');
					$(loc_removed_row).text(data);
					$(loc_removed_row).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});

	// toggle city featured
	$('.featured-home').on('click', function(e) {
		e.preventDefault();
		var city_id     = $(this).data('city-id');
		var post_url    = '<?= $baseurl ?>' + '/admin/process-toggle-city-featured.php';
		var city_status = $(this).data('city-status');

		$.post(post_url, {
			city_id    : city_id,
			city_status: city_status
			},
			function(data) {
				if(data == 'featured') {
					$('#featured-home-' + city_id).removeClass('btn-light');
					$('#featured-home-' + city_id).addClass('btn-success');
					$('#featured-home-' + city_id).data('city-status', 'featured');
				}

				if(data == 'not_featured') {
					$('#featured-home-' + city_id).removeClass('btn-success');
					$('#featured-home-' + city_id).addClass('btn-light');
					$('#featured-home-' + city_id).data('city-status', 'not_featured');
				}
				//location.reload(true);
			}
		);
	});
}());
</script>

<!-- upload city img -->
<script>
(function() {
	// generate a click on the hidden input file field
	$('#upload-city-img').on('click', function(e){
		e.preventDefault();
		$('#city_img').trigger('click');
	});

	$("#city_img").on('change', function(e) {
		// append file input to form data
		var fileInput = document.getElementById('city_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('city_img', file);

		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-city-img.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="city-img-preloader"><i class="las la-spinner la-spin"></i></div>').appendTo('#city-img-wrapper');
			},
			success: function(data) {
				console.log(data);
				// parse json string from response
				var data = JSON.parse(data);

				// check if previous upload failed
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#city-img-preloader').remove();

				// remove current city img
				$('#city-img-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var city_img = '<img src="' + data.message + '" width="180">';

					// display uploaded pic's thumb
					$('#city-img-wrapper').append(city_img);

					// add hidden input field
					$('#uploaded_img').val(data.filename);
				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#city-img-wrapper').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="upload-failed"></div>').appendTo('#city-img-wrapper').text(e);
			}
		});
	});
}());
</script>

<!-- upload city img on edit city -->
<script>
function onShowEdit() {
	// generate a click on the hidden input file field
	$('#edit-upload-city-img').on('click', function(e){
		e.preventDefault();
		$('#edit_city_img').trigger('click');
	});

	$("#edit_city_img").on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('edit_city_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('city_img', file);

		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-city-img.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="edit-city-img-preloader"><i class="las la-spinner la-spin"></i></div>').appendTo('#edit-city-img-wrapper');
			},
			success: function(data) {
				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed
				// #upload_failed div created by onSumit function above
				if ($('#edit-upload-failed').length){
					$('#edit-upload-failed').remove();
				}

				// delete preloader spinner
				$('#edit-city-img-preloader').remove();

				// empty wrapper
				$('#edit-city-img-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var city_img = '<img src="' + data.message + '" width="180">';

					// display uploaded image
					$('#edit-city-img-wrapper').append(city_img);

					// add hidden input field
					$('#edit_uploaded_img').val(data.filename);
				}

				else {
					$('<div id="edit-upload-failed"></div>').appendTo('#edit-city-img-wrapper').text(data.message);
				}
			},
			error: function(e) {
				$('<div id="edit-upload-failed"></div>').appendTo('#edit-city-img-wrapper').text(e);
			}
		});
	}));
}
</script>
</body>
</html>