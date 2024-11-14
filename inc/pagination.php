<?php
if(isset($pager) && $pager->getTotalPages() > 1) {
	$curPage = $page;

	$startPage = ($curPage < 5)? 1 : $curPage - 4;
	$endPage = 8 + $startPage;
	$endPage = ($pager->getTotalPages() < $endPage) ? $pager->getTotalPages() : $endPage;
	$diff = $startPage - $endPage + 8;
	$startPage -= ($startPage - $diff > 0) ? $diff : 0;

	$startPage = ($startPage == 1) ? 2 : $startPage;
	$endPage = ($endPage == $pager->getTotalPages()) ? $endPage - 1 : $endPage;

	// sort
	$sort = '';

	if(!empty($_GET['sort']) && $route[0] == 'listings') {
		$sort .= '?sort=' . e($_GET['sort']);
	}

	if(!empty($_GET['sort']) && $route[0] == 'results') {
		$sort .= '&sort=' . e($_GET['sort']);
	}

	// distance filter for listings route
	$dist = '';

	if(!empty($_GET['dist']) && $route[0] == 'listings') {
		if(!empty($sort)) {
			$dist .= '&';
		}

		else {
			$dist .= '?';
		}

		$dist .= 'dist=' . e($_GET['dist']);
	}

	if(!empty($_GET['dist']) && $route[0] == 'results') {
		$dist .= '&dist=' . e($_GET['dist']);
	}

	if ($curPage > 1) {
		?>
		<li class="page-item"><a href="<?= $page_url ?>1<?= $sort ?><?= $dist ?>" class="page-link"><?= $txt_pager_page1 ?></a></li>
		<?php
	}

	if ($curPage > 6) {
		?>
		<li class="page-item"><span class="page-link">...</span></li>
		<?php
	}

	if ($curPage == 1) {
		?>
		<li class="page-item active"><span class="page-link"><?= $txt_pager_page1 ?></span></li>
		<?php
	}

	for($i = $startPage; $i <= $endPage; $i++) {
		if($i == $page) {
			?>
			<li class="page-item active"><span class="page-link"><?= $i ?></span></li>
			<?php
		}

		else {
			?>
			<li class="page-item"><a href="<?php echo $page_url, $i ?><?= $sort ?><?= $dist ?>" class="page-link"><?= $i ?></a></li>
			<?php
		}
	}

	if($curPage + 5 < $pager->getTotalPages()) {
		?>
		<li class="page-item"><span class="page-link">...</span></li>
		<?php
	}

	if($pager->getTotalPages() > 5) {
		$last_page_txt = $txt_pager_last_page;
	}

	$last_page_txt = ($pager->getTotalPages() > 5) ? $txt_pager_last_page : $pager->getTotalPages();

	if($curPage == $pager->getTotalPages()) {
		?>
		<li class="page-item active"><span class="page-link"><?= $last_page_txt ?></span></li>
		<?php
	}

	else {
		?>
		<li class="page-item"><a href="<?php echo $page_url, $pager->getTotalPages() ?><?= $sort ?><?= $dist ?>" class="page-link"><?= $last_page_txt ?></a></li>
		<?php
	}
}
