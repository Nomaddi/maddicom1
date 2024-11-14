<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once('admin-head.php') ?>
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

			<!-- Charts -->
			<div class="row mb-5">
				<div class="col-lg-3 mb-5 mb-lg-0">
					<div class="chart-wrapper rounded border">
						<div class="p-2">
							<span class="text-uppercase d-block"><?= $txt_listings ?></span>

							<h2>
								<?= $total_ads ?>
								<?php
								if(isset($listings_variation)) {
									if($listings_variation != 0) {
										?>
										<small class="triangle triangle-<?= $listings_variation > 0 ? 'up' : 'down' ?> text-<?= $listings_variation > 0 ? 'green' : 'red' ?>"><span class="text-dark"><?= $listings_variation ?>%</span></small>
										<?php
									}

									else {
										?>
										<small class="triangle"><span class="text-dark">0%</span></small>
										<?php
									}
								}
								?>
							</h2>
						</div>

						<?php
						if(empty($cfg_admin_home_disable_charts)) {
							?>
							<hr>

							<canvas id="myChart1" class="charts"></canvas>
							<?php
						}
						?>
					</div>
				</div>

				<div class="col-lg-3 mb-5 mb-lg-0">
					<div class="chart-wrapper rounded border">
						<div class="p-2">
							<span class="text-uppercase d-block"><?= $txt_users ?></span>
							<h2>
								<?= $total_users ?>
								<?php
								if(isset($signups_variation)) {
									if($signups_variation != 0) {
										?>
										<small class="triangle triangle-<?= $signups_variation > 0 ? 'up' : 'down' ?> text-<?= $signups_variation > 0 ? 'green' : 'red' ?>"><span class="text-dark"><?= $signups_variation ?>%</span></small>
										<?php
									}

									else {
										?>
										<small class="triangle"><span class="text-dark">0%</span></small>
										<?php
									}
								}
								?>
							</h2>
						</div>

						<?php
						if(empty($cfg_admin_home_disable_charts)) {
							?>
							<hr>

							<canvas id="myChart2" class="charts"></canvas>
							<?php
						}
						?>
					</div>
				</div>

				<div class="col-lg-3 mb-5 mb-lg-0">
					<div class="chart-wrapper rounded border">
						<div class="p-2">
							<span class="text-uppercase d-block"><?= $txt_reviews ?></span>

							<h2>
								<?= $total_reviews ?>
								<?php
								if(isset($reviews_variation)) {
									if($reviews_variation != 0) {
										?>
										<small class="triangle triangle-<?= $reviews_variation > 0 ? 'up' : 'down' ?> text-<?= $reviews_variation > 0 ? 'green' : 'red' ?>"><span class="text-dark"><?= $reviews_variation ?>%</span></small>
										<?php
									}

									else {
										?>
										<small class="triangle"><span class="text-dark">0%</span></small>
										<?php
									}
								}
								?>
							</h2>
						</div>

						<?php
						if(empty($cfg_admin_home_disable_charts)) {
							?>
							<hr>

							<canvas id="myChart3" class="charts"></canvas>
							<?php
						}
						?>
					</div>
				</div>

				<div class="col-lg-3 mb-5 mb-lg-0">
					<div class="chart-wrapper rounded border-0">
						<div>
							<div class="p-2"><span class="text-uppercase d-block">Info</span></div>

							<div class="p-2">
								<small>
									<strong>
										Script version: <span class="text-muted"><?= $version ?></span><br>
										MySQL version: <span class="text-muted"><?= $mysql_version ?></span><br>
										PHP version: <span class="text-muted"><?= phpversion() ?></span><br>
										Server: <span class="text-muted"><?= $_SERVER['SERVER_SOFTWARE'] ?></span><br>
										Address: <span class="text-muted"><?= $_SERVER['REMOTE_ADDR'] ?></span>
									</strong>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 mb-5 mb-lg-0">
					<div class="card">
						<div class="list-group list-group-flush">
							<a href="#" class="list-group-item"><strong><?= $txt_latest_listings ?></strong></a>

							<?php
							foreach($latest_listings as $v) {
								?>
								<a href="<?= $v['place_link'] ?>" class="list-group-item list-group-item-action"><?= $v['place_name'] ?></a>
								<?php
							}
							?>
						</div>
					</div>
				</div>

				<div class="col-md-6 mb-5 mb-lg-0">
					<div class="card">
						<div class="list-group list-group-flush">
							<a href="#" class="list-group-item"><strong><?= $txt_latest_signups ?></strong></a>

							<?php
							foreach($latest_users as $v) {
								?>
								<a href="mailto:<?= $v['user_email'] ?>" class="list-group-item list-group-item-action"><?= $v['user_email'] ?></a>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<?php
if(empty($cfg_admin_home_disable_charts)) {
	?>
<script>
/*--------------------------------------------------
Chartjs
--------------------------------------------------*/
(function(){
	const ctx1 = document.getElementById('myChart1').getContext('2d');
	const chart1 = new Chart(ctx1, {
		type: 'line',
		data: {
			labels: ['', '', '', '', '', ''],
			datasets: [
				{
					data: [
					<?php
					foreach($listings_per_period as $v) {
						echo $v, ',';
					}
					?>
					],
					backgroundColor: "rgba(52,172,224,.2)",
					pointBorderColor: "#fff",
					pointHoverBorderColor: "#fff",
					pointBackgroundColor: "#34ace0",
					pointBorderWidth: 2,
				}
			]
		},
		options: {
			responsive: false,
			legend: {
				display: false
			},
			elements: {
				line: {
					borderColor: '#34ace0',
					borderWidth: 2
				},
				point: {
					radius: 3,
					hitRadius: 6,
				}
			},
			tooltips: {
				enabled: true,
				callbacks: {
					label: function(tooltipItems, data) {
						return ' ' + tooltipItems.yLabel + ' <?= $txt_listings ?>';
					}
				}
			},
			scales: {
				yAxes: [
					{
						display: false
					}
				],
				xAxes: [
					{
						display: false
					}
				]
			}
		}
	});

	const ctx2 = document.getElementById('myChart2').getContext('2d');
	const chart2 = new Chart(ctx2, {
		type: 'line',
		data: {
			labels: ['', '', '', '', '', ''],
			datasets: [
				{
					data: [
					<?php
					foreach($signups_per_period as $v) {
						echo $v, ',';
					}
					?>
					],
					backgroundColor: "rgba(52,172,224,0.2)",
					pointBorderColor: "#fff",
					pointHoverBorderColor: "#fff",
					pointBackgroundColor: "#34ace0",
					pointBorderWidth: 2,
				}
			]
		},
		options: {
			responsive: false,
			legend: {
				display: false
			},
			elements: {
				line: {
					borderColor: '#34ace0',
					borderWidth: 2
				},
				point: {
					radius: 3, // 0 if you don't want to show point for this chart
					hitRadius: 6,
				}
			},
			tooltips: {
				enabled: true,
				callbacks: {
					label: function(tooltipItems, data) {
						return ' ' + tooltipItems.yLabel + ' <?= $txt_users ?>';
					}
				}
			},
			scales: {
				yAxes: [
					{
						display: false
					}
				],
				xAxes: [
					{
						display: false
					}
				]
			}
		}
	});

	const ctx3 = document.getElementById('myChart3').getContext('2d');
	const chart3 = new Chart(ctx3, {
		type: 'line',
		data: {
			labels: ['', '', '', '', '', ''],
			datasets: [
				{
					data: [
					<?php
					foreach($reviews_per_period as $v) {
						echo $v, ',';
					}
					?>
					],
					backgroundColor: "rgba(52,172,224,0.2)",
					pointBorderColor: "#fff",
					pointHoverBorderColor: "#fff",
					pointBackgroundColor: "#34ace0",
					pointBorderWidth: 2,
				}
			]
		},
		options: {
			responsive: false,
			legend: {
				display: false
			},
			elements: {
				line: {
					borderColor: '#34ace0',
					borderWidth: 2
				},
				point: {
					radius: 3, // don't show point for this chart
					hitRadius: 6,
				}
			},
			tooltips: {
				enabled: true,
				callbacks: {
					label: function(tooltipItems, data) {
						return ' ' + tooltipItems.yLabel + ' <?= $txt_reviews ?>';
					}
				}
			},
			scales: {
				yAxes: [
					{
						display: false
					}
				],
				xAxes: [
					{
						display: false
					}
				]
			}
		}
	});
}());
</script>
<?php
}
?>
</body>
</html>