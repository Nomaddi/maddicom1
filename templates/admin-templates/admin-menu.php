<?php
if(file_exists(__DIR__ . '/admin-menu-child.php') && basename(__FILE__) != 'admin-menu-child.php') {
	include_once('admin-menu-child.php');
	return;
}

$categories_active    = $route[1] == 'categories'    ? 1 : 0;
$coupons_active       = $route[1] == 'coupons'       ? 1 : 0;
$custom_fields_active = $route[1] == 'custom-fields' ? 1 : 0;
$emails_active        = $route[1] == 'emails'        ? 1 : 0;
$home_active          = $route[1] == 'home'          ? 1 : 0;
$language_active      = $route[1] == 'language'      ? 1 : 0;
$listings_active      = $route[1] == 'listings'      ? 1 : 0;
$locations_active     = $route[1] == 'locations'     ? 1 : 0;
$pages_active         = $route[1] == 'pages'         ? 1 : 0;
$plans_active         = $route[1] == 'plans'         ? 1 : 0;
$reviews_active       = $route[1] == 'reviews'       ? 1 : 0;
$settings_active      = $route[1] == 'settings'      ? 1 : 0;
$tools_active         = $route[1] == 'tools'         ? 1 : 0;
$transactions_active  = $route[1] == 'transactions'  ? 1 : 0;
$users_active         = $route[1] == 'users'         ? 1 : 0;

if($route[1]== 'create-page') {
	$pages_active = 1;
}
?>
<div class="card">
	<ul class="list-group list-group-flush text-dark">
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/home" class="text-dark <?= ($home_active) ? 'active' : '' ?>">
				<i class="las la-home"></i>
				<?= $txt_admin_dashboard ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/categories" class="text-dark <?= ($categories_active) ? 'active' : '' ?>">
				<i class="las la-th-list"></i>
				<?= $txt_categories ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/coupons" class="text-dark <?= ($coupons_active) ? 'active' : '' ?>">
				<i class="las la-tags"></i>
				<?= $txt_coupons ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/custom-fields" class="text-dark <?= ($custom_fields_active) ? 'active' : '' ?>">
				<i class="las la-stream"></i>
				<?= $txt_custom_fields ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/emails" class="text-dark <?= ($emails_active) ? 'active' : '' ?>">
				<i class="las la-envelope"></i>
				<?= $txt_emails ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/language" class="text-dark <?= ($language_active) ? 'active' : '' ?>">
				<i class="las la-language"></i>
				<?= $txt_language ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/listings" class="text-dark <?= ($listings_active) ? 'active' : '' ?>">
				<i class="las la-list-ul"></i>
				<?= $txt_listings ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/locations/show-cities" class="text-dark <?= ($locations_active) ? 'active' : '' ?>">
				<i class="las la-location-arrow"></i>
				<?= $txt_locations ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/pages" class="text-dark <?= ($pages_active) ? 'active' : '' ?>">
				<i class="las la-file-alt"></i>
				<?= $txt_pages ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/plans" class="text-dark <?= ($plans_active) ? 'active' : '' ?>">
				<i class="las la-sticky-note"></i>
				<?= $txt_plans ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/reviews" class="text-dark <?= ($reviews_active) ? 'active' : '' ?>">
				<i class="las la-comment-alt"></i>
				<?= $txt_reviews ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/settings" class="text-dark <?= ($settings_active) ? 'active' : '' ?>">
				<i class="las la-cog"></i>
				<?= $txt_site_settings ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/tools" class="text-dark <?= ($tools_active) ? 'active' : '' ?>">
				<i class="las la-wrench"></i>
				<?= $txt_tools ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/transactions" class="text-dark <?= ($transactions_active) ? 'active' : '' ?>">
				<i class="las la-receipt"></i>
				<?= $txt_transactions ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/users" class="text-dark <?= ($users_active) ? 'active' : '' ?>">
				<i class="las la-users-cog"></i>
				<?= $txt_users ?>
			</a>
		</li>
	</ul>
</div>