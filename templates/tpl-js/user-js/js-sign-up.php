<script>
(function() {
	// form submit
	$("#submit-btn").on('click', function() {
		// hide validations
		$('#validate-fname').hide();
		$('#validate-email').hide();
		$('#validate-password').hide();

		// validate
		if(!checkRequired("fname")) {
			$(window).scrollTop($('#validate-fname').offset().top -100);
			return false;
		}

		if(!checkRequired("email")) {
			$(window).scrollTop($('#validate-email').offset().top -100);
			return false;
		}

		if(!checkRequired("password")) {
			$(window).scrollTop($('#validate-password').offset().top -100);
			return false;
		}

		// if ok, submit
		$('#the_form').submit();
	});
}());

function checkRequired(id) {
	if($("#" + id).val() == null || $("#" + id).val() == '') {
		$('#validate-' + id).show();
		return false;
	}
	else {
		return true;
	}
}
</script>