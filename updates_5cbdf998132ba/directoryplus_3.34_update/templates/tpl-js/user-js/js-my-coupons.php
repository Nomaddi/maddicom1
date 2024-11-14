<script>
(function() {
	// generate a click on the hidden input file field
	$('#upload-coupon-img').on('click', function(e){
		e.preventDefault();
		$('#coupon_img').trigger('click');
	});

	// upload coupon img
	$("#coupon_img").on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('coupon_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('coupon_img', file);

		$.ajax({
			url: "<?= $baseurl ?>/user/process-upload-coupon.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="thumbs-preloader" id="coupon-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#coupon-img');
			},
			success: function(data) {
				console.log(data);
				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed because of non allowed ext
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#coupon-preloader').remove();

				// remove current img
				$('#coupon-img').empty();

				if(data.result == 'success') {

					// create thumbnail src
					var coupon_img = '<img src="' + data.message + '" width="120">';

					// display uploaded pic's thumb
					$('#coupon-img').append(coupon_img);

					// add hidden input field
					$('#uploaded_img').val(data.filename);

				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#coupon-img').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="upload-failed"></div>').appendTo('#coupon-img').text(e);
			}
		});
	}));

	// show remove coupon modal
	$('#remove-coupon-modal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget); // Button that triggered the modal
		var coupon_id = button.data('coupon-id');
		var modal = $(this);

		modal.find('.btn-remove-coupon').attr('data-coupon-id', coupon_id);
	});

	// remove coupon
	$('.btn-remove-coupon').on('click', function(e){
		e.preventDefault();
		var coupon_id = $(this).data('coupon-id');
		var post_url = '<?= $baseurl ?>' + '/user/process-remove-coupon.php';

		$.post(post_url, {
			coupon_id: coupon_id
			},
			function(data) {
				location.reload(true);
			}
		);
	});

	// submit create coupon modal
    $('#create-coupon-submit').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/user/process-create-coupon.php';

		$.post(post_url, {
			params: $('form.form-create-coupon').serialize(),
			},
			function(data) {
				location.reload(true);
			}
		);
    });
}());
</script>