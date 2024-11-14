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

			<div class="table-responsive">
				<table class="table admin-table">
					<tr>
						<th><?= $txt_tool ?></th>
						<th><?= $txt_action ?></th>
					</tr>
					<tr>
						<td class="text-nowrap"><?= $txt_deactivate_expired ?></td>
						<td class="text-nowrap shrink">
							<button class="btn btn-light btn-sm px-2"
								style="width:auto"
								data-toggle="modal"
								data-target="#deactivate-listings-modal">
								<?= $txt_execute ?>
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-nowrap"><?= $txt_delete_tmp_pics ?></td>
						<td class="text-nowrap shrink">
							<button class="btn btn-light btn-sm px-2"
								style="width:auto"
								data-toggle="modal"
								data-target="#delete-tmp-pics-modal">
								<?= $txt_execute ?>
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-nowrap"><?= $txt_regenerate_sitemap ?></td>
						<td class="text-nowrap shrink">
							<button class="btn btn-light btn-sm px-2"
								style="width:auto"
								data-toggle="modal"
								data-target="#regenerate-sitemap-modal">
								<?= $txt_execute ?>
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-nowrap"><?= $txt_submit_sitemap ?></td>
						<td class="text-nowrap shrink">
							<a class="btn btn-light btn-sm px-2"
								style="width:auto"
								href="http://www.google.com/ping?sitemap=<?= $baseurl ?>/sitemaps/sitemap-index.xml"
								target="_blank">
								<?= $txt_execute ?>
							</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Deactivate listings modal -->
<div id="deactivate-listings-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-1" class="modal-title"><?= $txt_deactivate_expired ?></h5>
				<button class="close" data-dismiss="modal" aria-label="<?= a($txt_close) ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_ok ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Delete tmp pics modal -->
<div id="delete-tmp-pics-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-2" class="modal-title"><?= $txt_delete_tmp_pics ?></h5>
				<button class="close" data-dismiss="modal" aria-label="<?= a($txt_close) ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_ok ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Generate sitemap modal -->
<div id="regenerate-sitemap-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title-3" class="modal-title"><?= $txt_regenerate_sitemap ?></h5>
				<button class="close" data-dismiss="modal" aria-label="<?= a($txt_close) ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><?= $txt_ok ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<script>
/*--------------------------------------------------
Deactivate expired listings
--------------------------------------------------*/
(function() {
	// show edit plan modal
	$('#deactivate-listings-modal').on('show.bs.modal', function (event) {
		var modal = $(this);

		// ajax post
		var post_url = '<?= $baseurl ?>' + '/admin/deactivate-listings.php';

		$.post(post_url, { },
			function(data) {
				modal.find('.modal-body').html(data);
			}
		);
	});
}());

/*--------------------------------------------------
Delete tmp pics
--------------------------------------------------*/
(function() {
	// show edit plan modal
	$('#delete-tmp-pics-modal').on('show.bs.modal', function (event) {
		var modal = $(this);

		// ajax post
		var post_url = '<?= $baseurl ?>' + '/cron/remove-tmp-pics.php';

		$.post(post_url, { },
			function(data) {
				modal.find('.modal-body').html(data);
			}
		);
	});
}());

/*--------------------------------------------------
Regenerate sitemap
--------------------------------------------------*/
(function() {
	// on show modal
	$('#regenerate-sitemap-modal').on('show.bs.modal', function(e) {
		// vars
		var modal = $(this);
		var spinner = '<i class="las la-spinner fa-spin"></i> <?= $txt_wait ?>';
		var post_url = '<?= $baseurl ?>' + '/admin/build-sitemap.php';

		// show spinner
		modal.find('.modal-body').html(spinner);

		// post
		$.post(post_url, { }, function(data) {
				modal.find('.modal-body').empty().html(data);
			}
		);
	});
}());
</script>
</body>
</html>