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
				<strong><?= $txt_installed_lang ?>:</strong><br>

				<?php
				foreach($available_langs as $v) {
					?>
					<a href="<?= $baseurl ?>/admin/language?lang=<?= $v ?><?= isset($_GET['tpl']) ? '&tpl=' . $_GET['tpl'] : '' ?>" class="btn <?= $this_lang == $v ? 'btn-dark' : 'btn-light' ?> btn-sm"><?= $v ?></a>
					<?php
				}
				?>

				<a href="" class="btn btn-light btn-sm" data-toggle="modal" data-target="#create-lang-modal"><?= $txt_create_lang ?></a>

				<a href="" class="btn btn-light btn-sm" data-toggle="modal" data-target="#create-string-modal"><?= $txt_create_string ?></a>
			</div>

			<div class="mb-5">
				<strong><?= $txt_template ?>:</strong><br>

				<form class="form-inline" action="<?= $baseurl ?>/admin/language" method="get">
					<?php
					if(isset($_GET['lang'])) {
						?>
						<input type="hidden" name="lang" value="<?= $_GET['lang'] ?>">
						<?php
					}
					?>
					<select id="select-template" class="form-control mr-2" name="tpl">
						<?php
						foreach($available_tpls as $v) {
							?>
							<option value="<?= $v ?>" <?= $v == "$this_section/$this_tpl" ? 'selected' : '' ?>>
								<?= $v ?>
							</option>
							<?php
						}
						?>
					</select>
				</form>
			</div>

			<?php
			if(!empty($phrases_arr)) {
				?>
				<strong><?= $txt_vars ?>:</strong> (<?= $iso_639_1_native_names[$this_lang] ?>)<br>

				<form method="post" action="">
					<?php
					foreach($phrases_arr as $v) {
						?>
						<div class="form-group">
							<input type="text" class="form-control" name="txt_<?= $v['id'] ?>" value="<?= $v['translated'] ?>">
							<small class="form-text text-muted">[<?= $v['lang'] ?>][<?= $v['template'] ?>][<?= $v['var_name'] ?>]</small>
						</div>
						<?php
					}
					?>

					<input type="submit" class="btn btn-primary btn-lg">
				</form>
			<?php
			}

			else {
				?>
				<div class="mt-5 mb-3">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- modal create language -->
<div class="modal fade" id="create-lang-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_create_lang ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="mb-5">
					<p><?= $txt_select_lang_create ?>:</p>

					<form id="form-create-lang" class="form-inline" action="" method="post">
						<select id="select-lang" class="form-control form-control-sm mr-2" name="create-lang">
							<?php
							foreach($iso_639_1_native_names as $k => $v) {
								$selected = '';
								if($k == $html_lang) $selected = 'selected'
								?>
								<option value="<?= $k ?>" <?= $selected ?>><?= $k ?> (<?= $v ?>)</option>
								<?php
							}
							?>
						</select>
						<button id="create-lang-submit" class="btn btn-primary btn-sm"><?= $txt_create_lang ?></button>
					</form>
				</div>

				<?php
				if(!empty($available_sqls)) {
					?>
					<p><?= $txt_install_sql ?>:</p>

					<form class="form-inline" action="" method="post">
						<select id="select-file" class="form-control form-control-sm mr-2" name="install-lang">
							<?php
							foreach($available_sqls as $v) {
								?>
								<option value="<?= $v ?>"><?= $v ?></option>
								<?php
							}
							?>
						</select>
						<button id="install-lang-submit" class="btn btn-primary btn-sm"><?= $txt_install ?></button>
					</form>
					<?php
				}
				?>
			</div>

			<div class="modal-footer">
				<button type="button" id="create-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
			</div>
		</div>
	</div>
</div>

<!-- modal create string -->
<div class="modal fade" id="create-string-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_create_string ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-create-string" class="form" method="post">
					<div class="form-group">
						<div><strong><?= $txt_template ?></strong></div>

						<select class="form-control mr-2" name="template">
							<?php
							foreach($available_tpls as $v) {
								?>
								<option value="<?= $v ?>" <?= $v == "$this_section/$this_tpl" ? 'selected' : '' ?>>
									<?= $v ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<div><strong><?= $txt_var_name ?></strong></div>
						<input type="text" id="cat_name" class="form-control" name="var_name" required>
					</div>

					<?php
					foreach($available_langs as $v) {
						?>
						<div class="form-group">
							<label class="label" for="string_value_<?= $v ?>">
								<?= $txt_string_value ?>:
								<span class="badge badge-warning"><?= $iso_639_1_native_names[$v] ?></span>
							</label>

							<input type="text" id="string_value_<?= $v ?>" class="form-control" name="string_value[<?= $v ?>]">
						</div>
						<?php
					}
					?>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" id="create-string-cancel" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<input type="submit" id="create-string-submit" class="btn btn-primary btn-sm">
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
/*--------------------------------------------------
Modal
--------------------------------------------------*/
(function(){
	// lang modal on close
	$('#create-lang-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// select lang change
	$('#select-lang').on('change', function(e){
		if($('#create-lang-submit').html() == '<?= $txt_done ?>') {
			$('#create-lang-submit').empty();
			$('#create-lang-submit').html('<?= $txt_create_lang ?>');
		}
	});

	// select file change
	$('#select-file').on('change', function(e){
		if($('#install-lang-submit').html() == '<?= $txt_done ?>') {
			$('#install-lang-submit').empty();
			$('#install-lang-submit').html('<?= $txt_install_lang ?>');
		}
	});

	// select template change
	$('#select-template').on('change', function(e){
		$(this).closest('form').submit();
	});

	// create lang submit
	$('#create-lang-submit').on('click', function(e){
		e.preventDefault();

		// show spinner
		$('#create-lang-submit').empty();
		$('#create-lang-submit').html('<i class="fas fa-spinner fa-spin"></i> <?= $txt_installing ?>');

		// vars
		var modal = $('#create-lang-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-lang.php';

		$.post(post_url, {
			lang: $('#select-lang').val(),
			},
			function(data) {
				console.log(data);
				$('#create-lang-submit').empty();
				$('#create-lang-submit').html('<?= $txt_done ?>');
			}
		);
	});

	// install lang submit
	$('#install-lang-submit').on('click', function(e){
		e.preventDefault();

		$('#install-lang-submit').empty();
		$('#install-lang-submit').html('<i class="fas fa-spinner fa-spin"></i> <?= $txt_installing ?>');

		// vars
		var modal = $('#create-lang-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-install-lang.php';

		$.post(post_url, {
			file: $('#select-file').val(),
			},
			function(data) {
				console.log(data);
				$('#install-lang-submit').empty();
				$('#install-lang-submit').html('<?= $txt_done ?>');
			}
		);
	});

	// create string form submit
    $('#create-string-submit').on('click', function(e){
		e.preventDefault();

		// check validity
		$('#form-create-string')[0].checkValidity();
		$('#form-create-string')[0].reportValidity();

		// if all required fields filled, process
		var modal = $('#create-string-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-string.php';

		$.post(post_url, {
			params: $('#form-create-string').serialize(),
			},
			function(data) {
				modal.find('.modal-body').html(data);
				modal.find('#create-string-submit').remove();
				modal.find('#create-string-cancel').empty().text('<?= $txt_ok ?>');
			}
		);
    });

	// create string modal on close
	$('#create-string-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});
}());
</script>
</body>
</html>