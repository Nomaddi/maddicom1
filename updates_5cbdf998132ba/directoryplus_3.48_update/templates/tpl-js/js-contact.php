<script>
/*--------------------------------------------------
Contact form
--------------------------------------------------*/
(function(){
	$('#contact-result').hide();

	$('#submit-contact').on('click', function(e) {
		// to use checkValidity, we need to get the form element without jquery
		var the_form = document.getElementById('contact-form');

		// check validity and process form
		if(the_form.checkValidity()) {
			e.preventDefault();

			// vars
			var url = '<?= $baseurl ?>/process-contact.php';
			var spinner = '<i class="las la-circle-notch la-spin"></i><span class="sr-only"><?= $txt_loading ?></span>';

			// hide form and show spinner
			$('#contact-result').show();
			$('#contact-form').toggle(120);
			$('#contact-result').html(spinner).fadeIn();

			// ajax post
			$.post(url, {
				params: $('#contact-form').serialize(),
			}, function(data) {

				$('#contact-result').html(data).fadeIn();

				// bind event that shows form when alert is dismissed
				$('.contact-alert').bind('closed.bs.alert', function () {
					$('#contact-form').toggle(120);
				});
			});
		}
	});
}());
</script>