<script>
/*--------------------------------------------------
Ratings
--------------------------------------------------*/
<?php
if($cfg_enable_reviews) {
	?>
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		starType: 'i'
	});
}());
<?php
}
?>
</script>
