<script>
/*--------------------------------------------------
Rating
--------------------------------------------------*/
<?php
if($cfg_enable_reviews) {
	?>
(function(){
	$.fn.raty.defaults.path = '<?= $baseurl ?>/templates/lib/raty/images';
	$('.featured-item-rating').raty({
		readOnly: true,
		score: function(){
			return this.getAttribute('data-rating');
		}
	});
}());
<?php
}
?>
</script>