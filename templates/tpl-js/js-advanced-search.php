<script>
(function() {
	// toggle categories checkboxes
	$('#select_all').on('click', function(e){
		var checkedStatus = this.checked;
		$('#cat-checkboxes').find(':checkbox').each(function() {
			$(this).prop('checked', checkedStatus);
		});
	});

	$('#city_id').select2({
		ajax: {
			url: '<?= $baseurl ?>/_return_cities_select2.php',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					query: params.term, // search term
					page: params.page
				};
			}
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1
	});
}());
</script>