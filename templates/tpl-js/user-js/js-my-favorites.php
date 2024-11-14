<script>
// remove listing submit
(function(){
	$('.remove-favorite').on('click', function(e) {
		e.preventDefault();
		var place_id = $(this).data('place-id');
		var wrapper = '#listing-' + place_id;

		// ajax request
		var post_url = '<?= $baseurl ?>' + '/user/process-remove-favorite.php';

		$.post(post_url, {
			place_id: place_id
			},
			function(data) {
				console.log(data)
				if(data == 'success') {
					location.reload(true);
				}

				else {
					console.log(data)
				}
			}
		);
	});
}());
</script>