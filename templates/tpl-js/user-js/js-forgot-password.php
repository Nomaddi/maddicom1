<script>
/*--------------------------------------------------
Forgot password form
--------------------------------------------------*/
(function(){
	$('#result-spinner').hide();
	$('#success-result').hide();
	$('#invalid-email-result').hide();
	$('#smtp-error-result').hide();

	$('#forgot-password-submit').on('click', function(e) {
		// to use checkValidity, we need to get the form element without jquery
		var the_form = document.getElementById('forgot-password-form');

		// check validity and process form
		if(the_form.checkValidity()) {
			e.preventDefault();

			// vars
			var url = '<?= $baseurl ?>/user/process-forgot-password.php';

			// hide form and show spinner
			$('#forgot-password-form').toggle(120);
			$('#result-spinner').show();
			$('#success-result').hide();
			$('#invalid-email-result').hide();
			$('#smtp-error-result').hide();

			// ajax post
			$.post(url, {
				params: $('#forgot-password-form').serialize(),
			}, function(data) {
				// hide spinner
				$('#result-spinner').hide();

				// parse json string
				var data = JSON.parse(data);

				if(data.response == 'success') {
					$('#success-result').show();
				}

				if(data.response == 'smtp_error') {
					$('#smtp-error-result').show();
				}

				if(data.response == 'invalid') {
					$('#invalid-email-result').show();
				}
			});
		}
	});
}());
</script>