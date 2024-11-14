<?php
if(file_exists(__DIR__ . '/admin-footer-child.php') && basename(__FILE__) != 'admin-footer-child.php') {
	include_once('admin-footer-child.php');
	return;
}
?>
<?php
include_once( __DIR__ . '/../footer.php');

// if SMTP is not configured, show toast
if (strpos($smtp_server, 'mail.example.com') !== false || empty($smtp_server)) {
	?>
	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: absolute; bottom: 0; right: 0;">
		<div class="toast-header">
			<strong class="mr-auto">Notice</strong>

			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body text-primary">
			Please define the <a href="<?= $baseurl ?>/admin/settings#email-panel" class="text-primary">SMTP settings</a> to allow user registration.
		</div>
	</div>

	<script>
	$('.toast').toast({autohide: false});
	$('.toast').toast('show');
	</script>
	<?php
}