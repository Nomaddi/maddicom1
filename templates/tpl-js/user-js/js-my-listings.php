<script>
// remove place modal
(function(){
	$('#remove-place-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var place_id = button.data('place-id');
		var modal = $(this);

		// ajax request
		var post_url = '<?= $baseurl ?>' + '/user/ajax-get-place-name.php';

		$.post(post_url, { place_id: place_id },
			function(data) {
				modal.find('.modal-title').text(data);
				modal.find('.remove-place').data('remove-id', place_id);
			}
		);
	});
}());

// remove place submit
(function(){
	$('.remove-place').on('click', function(e) {
		e.preventDefault();
		var place_id = $(this).data('remove-id');
		var wrapper = '#place-' + place_id;

		// ajax request
		var post_url = '<?= $baseurl ?>' + '/user/process-remove-listing.php';

		$.post(post_url, {
			place_id: place_id
			},
			function(data) {
				console.log(data);
				location.reload(true);
			}
		);
	});
}());
</script>