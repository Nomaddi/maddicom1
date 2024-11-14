<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

$page_url = "$baseurl/admin/transactions?page=";

// count results
$query = "SELECT COUNT(*) AS c FROM transactions";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

// build results array
$count = ($page - 1) * $items_per_page;

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT * FROM transactions ORDER BY txn_date DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	$transactions_arr = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$count++;

		$txn_id     = $row['id'];
		$txn_type   = !empty($row['txn_type'  ]) ? $row['txn_type'  ] : '';
		$place_id   = !empty($row['place_id'  ]) ? $row['place_id'  ] : '';
		$user       = !empty($row['user'      ]) ? $row['user'      ] : '';
		$paym_email = !empty($row['paym_email']) ? $row['paym_email'] : '';
		$gateway    = !empty($row['gateway'   ]) ? $row['gateway'   ] : '';
		$amount     = !empty($row['amount'    ]) ? $row['amount'    ] : '';
		$txn_data   = !empty($row['txn_data'  ]) ? $row['txn_data'  ] : '';
		$txn_date   = !empty($row['txn_date'  ]) ? $row['txn_date'  ] : '';

		// sanitize
		$txn_type   = e($txn_type  );
		$place_id   = e($place_id  );
		$user       = e($user      );
		$paym_email = e($paym_email);
		$gateway    = e($gateway   );
		$amount     = e($amount    );
		$txn_data   = e($txn_data  );
		$txn_date   = e($txn_date  );

		$cur_lop_arr = array(
			'txn_id'     => $txn_id,
			'txn_type'   => $txn_type,
			'place_id'   => $place_id,
			'user'       => $user,
			'paym_email' => $paym_email,
			'gateway'    => $gateway,
			'amount'     => $amount,
			'txn_data'   => $txn_data,
			'txn_date'   => $txn_date,
		);

		$transactions_arr[] = $cur_lop_arr;
	}
}