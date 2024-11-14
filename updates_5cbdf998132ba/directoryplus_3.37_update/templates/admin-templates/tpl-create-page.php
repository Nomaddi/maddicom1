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

		<div class="col-md-8 col-lg-9 create-page">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<form class="form-create-page">
				<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

				<!-- Title -->
				<div class="form-group">
					<label class="label" for="page_title"><?= $txt_page_title ?></label>
					<input type="text" id="page_title" name="page_title" class="form-control" required>
				</div>

				<!-- Slug -->
				<div class="form-group">
					<label class="label" for="page_slug"><?= $txt_slug ?></label>
					<input type="text" id="page_slug" name="page_slug" class="form-control" required>
				</div>

				<!-- Show in blog -->
				<div><?= $txt_show_in_blog ?></div>

				<div class="form-check form-check-inline mb-3">
					<input type="radio" id="show_in_blog1" class="form-check-input" name="show_in_blog" value="1" checked>
					<label class="form-check-label" for="show_in_blog1"><?= $txt_yes ?></label>
				</div>

				<div class="form-check form-check-inline mb-3">
					<input type="radio" id="show_in_blog2" class="form-check-input" name="show_in_blog" value="0">
					<label class="form-check-label" for="show_in_blog2"><?= $txt_no ?></label>
				</div>

				<!-- Enable comments -->
				<div><?= $txt_enable_comments ?></div>

				<div class="form-check form-check-inline mb-3">
					<input type="radio" id="enable_comments_1" class="form-check-input" name="enable_comments" value="1" checked>
					<label class="form-check-label" for="enable_comments_1"><?= $txt_yes ?></label>
				</div>

				<div class="form-check form-check-inline mb-3">
					<input type="radio" id="enable_comments_2" class="form-check-input" name="enable_comments" value="0">
					<label class="form-check-label" for="enable_comments_3"><?= $txt_no ?></label>
				</div>

				<!-- Meta description -->
				<div class="form-group">
					<label class="label" for="meta_desc"><?= $txt_meta_desc ?></label>
					<input type="text" id="meta_desc" name="meta_desc" class="form-control">
				</div>

				<!-- Thumbnail -->
				<div class="form-group block" id="thumb-row">
					<input type="file" id="thumb" name="thumb" style="display:block;visibility:hidden;width:0;height:0;">
					<input type="hidden" name="uploaded_thumb" id="uploaded_thumb" value="">

					<?= $txt_thumb ?><br>

					<div class="mb-3">
						<div id="thumb-wrapper"></div>
					</div>

					<div class="mb-3">
						<span id="upload-thumb" class="btn btn-light btn-sm pointer"><i class="fas fa-plus"></i>
						<?= $txt_upload ?></span>
					</div>
				</div>

				<!-- Date -->
				<div class="form-group">
					<label class="label" for="page_date"><?= $txt_date ?></label>
					<input type="date" id="page_date" name="page_date" class="form-control" value="<?= date('Y-m-d') ?>">
				</div>

				<!-- Tinymce -->
				<div class="form-group">
					<input type="hidden" id="page_html" name="page_html">
					<textarea id="page_contents" class="form-control" name="page_contents" rows="15"></textarea>
				</div>

				<!-- Submit -->
				<div class="form-group">
					<input type="submit" id="submit" name="submit" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- Tinymce textarea -->
<script src="<?= $baseurl ?>/assets/js/tinymce/tinymce.min.js"></script>
<script>
(function() {
	tinymce.init({
		selector: 'textarea',
		convert_urls : false,
		plugins : 'code print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
		toolbar: 'code formatselect | bold italic strikethrough forecolor backcolor | link | image | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
		images_upload_url: 'process-upload-tinymce.php',
		image_dimensions: false,
		images_upload_handler : function(blobInfo, success, failure) {
			var xhr, formData;

			xhr = new XMLHttpRequest();

			xhr.withCredentials = false;
			xhr.open('POST', 'process-upload-tinymce.php');

			// manually set header
			xhr.setRequestHeader('X-CSRF-Token', '<?= session_id() ?>');

			xhr.onload = function() {
				var json;

				if (xhr.status != 200) {
					failure('HTTP Error: ' + xhr.status);
					return;
				}

				json = JSON.parse(xhr.responseText);

				if (!json || typeof json.location != 'string') {
					failure('Invalid JSON: ' + xhr.responseText);
					return;
				}

				// location is the index returned by process-upload-tinymce.php
				console.log(json);
				success(json.location);
			};

			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());

			xhr.send(formData);
		},
	});
}());
</script>

<!-- Page thumb -->
<script>
(function() {
	// generate a click on the hidden input file field
	$('#upload-thumb').on('click', function(e){
		e.preventDefault();
		$('#thumb').trigger('click');
	});

	$("#thumb").on('change', function(e) {
		// append file input to form data
		var fileInput = document.getElementById('thumb');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('thumb', file);

		$.ajax({
			url: "<?= $baseurl ?>/admin/process-upload-page-thumb.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="thumb-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#thumb-wrapper');
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
				$('#thumb-preloader').remove();

				// remove current category img
				$('#thumb-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var thumb_img = '<img src="' + data.message + '" width="180">';

					// display uploaded pic's thumb
					$('#thumb-wrapper').append(thumb_img);

					// add hidden input field
					$('#uploaded_thumb').val(data.filename);
				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#thumb-wrapper').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="upload-failed"></div>').appendTo('#thumb-wrapper').text(e);
			}
		});
	});
}());
</script>

<!-- javascript -->
<script>
// Process create page
(function() {
	$('#submit').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-page.php';

		// get html content from the tinymce iframe
		$('#page_html').val(tinyMCE.get('page_contents').getContent());

		$.post(post_url, {
			params: $('form.form-create-page').serialize(),
			},
			function(data) {
				$('html, body').animate({scrollTop : 0},360);
				$('.create-page').empty().html(data);
			}
		);
	});
}());
</script>
</body>
</html>