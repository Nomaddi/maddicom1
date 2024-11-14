<?php
require_once(__DIR__ . '/inc/config.php');

// query is the default name of the request parameter that contains the query.
$city_input = !empty($_GET['query']) ? $_GET['query'] : '';
$city_input = '%' . $city_input . '%';

if(!empty($city_input)) {
	$stmt = $conn->prepare("SELECT * FROM cities WHERE city_name LIKE :city_input LIMIT 100");
	$stmt->bindValue(':city_input', $city_input);
	$stmt->execute();

	$response = '
		{
		"results":
		[';

	$rowCount = 0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$rowCount++;

		$this_city_id   = !empty($row['city_id'  ]) ? $row['city_id'  ] : '';
		$this_city_name = !empty($row['city_name']) ? $row['city_name'] : '';
		$this_state     = !empty($row['state'    ]) ? $row['state'    ] : '';

		// sanitize
		$this_city_id   = e($this_city_id  );
		$this_city_name = e($this_city_name);
		$this_state     = e($this_state    );

		if($rowCount != $stmt->rowCount()) {
			$response .= '{ "id": "' . $this_city_id . '", "text": "' . $this_city_name . ', ' . $this_state . '" },';
		}

		else {
			$response .= '{ "id": "' . $this_city_id . '", "text": "' . $this_city_name . ',' . $this_state . '" }';
		}
	}

	$response .= '
		]
		}';

	// $response = '{"results":[{"id":0,"text":"text name0"}]}';

	echo $response;
}