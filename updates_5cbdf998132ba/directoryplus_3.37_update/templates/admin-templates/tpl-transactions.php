<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once(__DIR__ . '/admin-head.php') ?>
</head>
<body class="tpl-admin-<?= $route[1] ?>">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('admin-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<?php
			if(!empty($transactions_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
				</div>

				<div class="table-responsive">
					<table class="table table-striped">
						<tr>
							<th class="text-nowrap"><?= $txt_txn_id ?></th>
							<th class="text-nowrap"><?= $txt_txn_type ?></th>
							<th class="text-nowrap">place_id</th>
							<th class="text-nowrap"><?= $txt_user ?></th>
							<th class="text-nowrap"><?= $txt_email ?></th>
							<th class="text-nowrap">Gateway</th>
							<th class="text-nowrap"><?= $txt_amount ?></th>
							<th class="text-nowrap"><?= $txt_date ?></th>
						</tr>

						<?php
						foreach($transactions_arr as $k => $v) {
							?>
							<tr>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['txn_id'] ?></a></td>
								<td class="text-nowrap"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['txn_type'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['place_id'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['user'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['paym_email'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['gateway'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['amount'] ?></a></td>
								<td class="text-nowrap shrink"><a href="#" class="show-details" data-toggle="modal" data-target="#modal1" data-tx-vars="<?= e($v['txn_data']) ?>"><?= $v['txn_date'] ?></a></td>
							</tr>
							<?php
						}
					?>
					</table>
				</div>

				<nav>
					<ul class="pagination flex-wrap">
						<?php
						if($total_rows > 0) {
							include_once(__DIR__ . '/../../inc/pagination.php');
						}
						?>
					</ul>
				</nav>
				<?php
			}

			else {
				?>
				<div class="mt-5 mb-3">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal-title1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title" id="exampleModalScrollableTitle">Tx Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="tx-vars" class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $txt_close ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	$(document).on('click', '.show-details', function () {
		 var tx_vars = $(this).data('tx-vars');
		 $("#tx-vars").html(tx_vars);
		 console.log(tx_vars);
	});
}());
</script>

</body>
</html>