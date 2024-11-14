<?php
if(file_exists(__DIR__ . '/user-menu-child.php') && basename(__FILE__) != 'user-menu-child.php') {
	include_once('user-menu-child.php');
	return;
}

$menu_home_active      = 0;
$menu_profile_active   = 0;
$menu_listings_active  = 0;
$menu_coupons_active   = 0;
$menu_reviews_active   = 0;
$menu_edit_pass_active = 0;
$menu_favorites_active = 0;

if($route[1] == 'index') {
	$menu_home_active = 1;
}

if($route[1] == 'my-profile') {
	$menu_profile_active = 1;
}

if($route[1] == 'my-listings') {
	$menu_listings_active = 1;
}

if($route[1] == 'my-coupons') {
	$menu_coupons_active = 1;
}

if($route[1] == 'my-reviews') {
	$menu_reviews_active = 1;
}

if($route[1] == 'edit-pass') {
	$menu_edit_pass_active = 1;
}

if($route[1] == 'my-favorites') {
	$menu_favorites_active = 1;
}
?>

<div class="card">
	<div class="card-header">
		<?= $txt_dashboard ?>
	</div>

	<ul class="list-group list-group-flush text-dark">
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/my-profile" class="text-dark <?= ($menu_profile_active) ?  'active' : '' ?>">
				<i class="las la-user"></i>
				<span class="menu-txt"><?= $txt_profile ?></span>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/my-listings" class="text-dark <?= ($menu_listings_active) ? 'active' : '' ?>">
				<i class="las la-list-ul"></i>
				<span class="menu-txt"><?= $txt_listings ?></span>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/my-favorites" class="text-dark <?= ($menu_favorites_active) ? 'active' : '' ?>">
				<i class="lar la-heart"></i>
				<span class="menu-txt"><?= $txt_favorites ?></span>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/my-coupons" class="text-dark <?= ($menu_coupons_active) ? 'active' : '' ?>">
				<i class="las la-tags"></i>
				<span class="menu-txt"><?= $txt_coupons ?></span>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/my-reviews" class="text-dark <?= ($menu_reviews_active) ? 'active' : '' ?>">
				<i class="las la-comments"></i>
				<span class="menu-txt"><?= $txt_reviews ?></span>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/user/select-plan" class="text-dark">
				<i class="las la-pen"></i>
				<span class="menu-txt"><?= $txt_create_listing ?></span>
			</a>
		</li>

		<?php
		if($hybridauth_provider_name == 'local' || $is_admin) { ?>
			<li class="list-group-item">
				<a href="<?= $baseurl ?>/user/edit-pass" class="text-dark <?= ($menu_edit_pass_active) ? 'active' : '' ?>">
					<i class="las la-key"></i>
					<span class="menu-txt"><?= $txt_change_pass ?></span>
				</a>
			</li>
			<?php
		}
		?>
	</ul>
</div>