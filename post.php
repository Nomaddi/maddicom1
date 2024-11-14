<?php
require_once(__DIR__ . '/inc/config.php');

/*--------------------------------------------------
Valid routes (below starting at index[1]

post
post/slug
--------------------------------------------------*/
$page_slug = !empty($route[1]) ? $route[1] : '';

$query = "SELECT * FROM pages WHERE page_slug = :page_slug";
$stmt = $conn->prepare($query);
$stmt->bindValue(':page_slug', $page_slug);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(empty($row)) {
	http_response_code(404);
	require_once(__DIR__ . '/templates/404.php');
	die();
}

$page_id         = !empty($row['page_id'        ]) ? $row['page_id'        ] : '';
$page_title      = !empty($row['page_title'     ]) ? $row['page_title'     ] : '';
$page_slug       = !empty($row['page_slug'      ]) ? $row['page_slug'      ] : $page_id;
$meta_desc       = !empty($row['meta_desc'      ]) ? $row['meta_desc'      ] : '';
$page_contents   = !empty($row['page_contents'  ]) ? $row['page_contents'  ] : '';
$page_group      = !empty($row['page_group'     ]) ? $row['page_group'     ] : '';
$page_order      = !empty($row['page_order'     ]) ? $row['page_order'     ] : '';
$enable_comments = !empty($row['enable_comments']) ? $row['enable_comments'] : false;

// sanitize
// don't sanitize posts

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/post/' . $page_slug;

/*--------------------------------------------------
Disqus
--------------------------------------------------*/
$page_identifier = 'page-' . $page_id;