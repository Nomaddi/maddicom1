<script>
/*--------------------------------------------------
Add to Favorites
--------------------------------------------------*/
$(function() {
    addToFavorites();
});

/*--------------------------------------------------
Rating
--------------------------------------------------*/
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});

	$('.review-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});

	$('.raty').raty({
		scoreName: 'review_score',
		target : '#hint',
		targetKeep : true,
		hints: ['<?= $txt_rate_bad ?>', '<?= $txt_rate_poor ?>', '<?= $txt_rate_regular ?>', '<?= $txt_rate_good ?>', '<?= $txt_rate_gorgeous ?>'],
		starType: 'i'
	});
}());

/*--------------------------------------------------
Contact form
--------------------------------------------------*/
(function(){
	// on hide modal
	$('#contact-user-modal').on('hide.bs.modal', function (e) {
		$('#contact-user-form').show(120);
		$('#contact-user-result').empty();
	});

	// on submit
	$('#contact-user-submit').on('click', function(e) {
		e.preventDefault();

		// check validity
		if($('#contact-user-form')[0].checkValidity()) {
			// if all required fields filled, process
			var modal = $('#contact-user-modal');
			var post_url = '<?= $baseurl ?>/send-msg.php';
			var spinner = '<i class="las la-spinner la-spin"></i> <?= $txt_wait ?>';

			// hide form and show spinner
			$('#contact-user-result').show();
			$('#contact-user-form').hide(120);
			$('#contact-user-result').html(spinner);

			// ajax post
			$.post(post_url, {
				params: $('#contact-user-form').serialize(),
			}, function(data) {
				$('#contact-user-result').empty().html(data).fadeIn();
			});
		}

		else {
			$('#contact-user-form')[0].reportValidity();
		}
	});
}());
</script>