<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

// sort order
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date-desc';

if(!in_array($sort, array('date', 'date-desc', 'email', 'email-desc', 'name', 'name-desc'))) {
	$sort = 'date-desc';
}

// page url
$page_url = "$baseurl/admin/users-trash?sort=$sort&page=";

// count query
$query = "SELECT COUNT(*) AS total_rows FROM users WHERE status = 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];

// init
$users_arr = array();

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// define sort
	$order_by = "id DESC";

	if($sort == 'date') {
		$order_by = "id";
	}

	if($sort == 'date-desc') {
		$order_by = "id DESC";
	}

	if($sort == 'email') {
		$order_by = "email";
	}

	if($sort == 'email-desc') {
		$order_by = "email DESC";
	}

	if($sort == 'name') {
		$order_by = "first_name";
	}

	if($sort == 'name-desc') {
		$order_by = "first_name DESC";
	}

	// query
	$query = "SELECT * FROM users WHERE status = 'trashed' ORDER BY $order_by LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_id              = $row['id'];
		$this_first_name      = !empty($row['first_name'        ]) ? $row['first_name'        ] : '';
		$this_last_name       = !empty($row['last_name'         ]) ? $row['last_name'         ] : '';
		$this_email           = !empty($row['email'             ]) ? $row['email'             ] : '';
		$this_created         = !empty($row['created'           ]) ? $row['created'           ] : '';
		$this_status          = !empty($row['status'            ]) ? $row['status'            ] : '';
		$this_prof_pic_status = !empty($row['profile_pic_status']) ? $row['profile_pic_status'] : '';

		// sanitize
		$this_first_name = e($this_first_name);
		$this_last_name = e($this_last_name);
		$this_email = e($this_email);

		// username
		$this_username = $this_first_name . ' ' . $this_last_name;
		if (mb_strlen($this_username) > 15) {
			$this_username = mb_substr($this_username, 0, 15) . '...';
		}

		// sanitize
		$this_username = e(trim($this_username));
		$this_email    = e(trim($this_email));

		// simplify date
		$this_created = strtotime($this_created);
		$this_created = date( 'Y-m-d', $this_created );

		// profile pic
		$folder = floor($this_id / 1000) + 1;

		if(strlen($folder) < 1) {
			$folder = '999';
		}

		// profile pic path
		$this_pic_path = $pic_basepath . '/' . $profile_full_folder . '/' . $folder . '/' . $this_id;

		// check if file exists
		$this_pic_glob_arr = glob("$this_pic_path.*");

		if(!empty($this_pic_glob_arr)) {
			$this_prof_pic_filename = basename($this_pic_glob_arr[0]);
		}

		else {
			$this_prof_pic_filename = '';
		}

		if(!empty($this_pic_glob_arr)) {
			// set first match as profile pic
			$this_prof_pic_url = $pic_baseurl . '/' . $profile_full_folder . '/' . $folder . '/' . $this_prof_pic_filename;
		}

		else {
			$this_prof_pic_url = $baseurl . '/assets/imgs/blank.png';
		}

		$cur_loop_arr = array(
			'id'              => $this_id,
			'name'            => $this_username,
			'email'           => $this_email,
			'created'         => $this_created,
			'status'          => $this_status,
			'prof_pic_status' => $this_prof_pic_status,
			'prof_pic_url'    => $this_prof_pic_url
		);

		if($cur_loop_arr['id'] != 1) {
			$users_arr[] = $cur_loop_arr;
		}
	}
}
