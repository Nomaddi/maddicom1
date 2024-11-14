<script>
/*--------------------------------------------------
Profile pic
--------------------------------------------------*/
(function() {
	// hide warning if exists
	$('#profile-pic-fail').hide();

	// generate a click on the hidden input file field
	$('#upload-profile-pic').on('click', function(e){
		e.preventDefault();
		$('#profile_pic').trigger('click');
	});

	$('#profile_pic').on('change', function(e) {
		// append file input to form data
		var fileInput = document.getElementById('profile_pic');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('profile_pic', file);

		$.ajax({
			url: '<?= $baseurl ?>/user/process-upload-profile.php',
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="profile-pic-preloader"><i class="las la-spinner la-spin"></i></div>').appendTo('#profile-pic-wrapper');
			},
			success: function(data) {
				// parse json string from response
				var data = JSON.parse(data);

				// hide possible previous error messages
				$('#profile-pic-fail').hide();

				// check if previous upload failed
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#profile-pic-preloader').remove();

				// remove current category img
				$('#profile-pic-wrapper').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var profile_pic = '<img src="<?= $pic_baseurl ?>/<?= $profile_thumb_folder ?>/<?= $folder ?>/' + data.filename + '" width="180">';

					// display uploaded pic's thumb
					$('#profile-pic-wrapper').append(profile_pic);

					// add hidden input field
					$('#uploaded_pic').val(data.filename);
				}

				else {
					$('#profile-pic-fail').show();
					$('#profile-pic-fail').text(data.message);
				}
			},
			error: function(e) {
				$('#profile-pic-fail').show();
				$('#profile-pic-fail').text(e);
			}
		});
	});
}());

// user delete profile
(function() {
	$('#delete-profile-pic').on('click', function(){
		var post_url = 'delete-profile-pic.php';
		$.post(post_url, function(data){
			// create thumbnail src
			var blank = '<img src="<?= $baseurl ?>/assets/imgs/blank.png" width="150" height="150">';

			// display uploaded pic's thumb
			// empty original thumb
			$('#profile-pic-wrapper').empty();
			$('#profile-pic-wrapper').append(blank);
		});
	});
}());
</script>