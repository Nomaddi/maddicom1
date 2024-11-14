<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'pages');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = e($row['translated']);
}

// cur date in case date field is empty
$cur_date = new DateTime();
$cur_date = $cur_date->format('Y-m-d H:i:s');

// get post data
$params = array();
parse_str($_POST['params'], $params);

$page_id         = !empty($params['page_id'        ]) ? $params['page_id'        ] : 0;
$page_title      = !empty($params['page_title'     ]) ? $params['page_title'     ] : '';
$page_slug       = !empty($params['page_slug'      ]) ? $params['page_slug'      ] : '';
$meta_desc       = !empty($params['meta_desc'      ]) ? $params['meta_desc'      ] : '';
$page_contents   = !empty($params['page_html'      ]) ? $params['page_html'      ] : '';
$page_date       = !empty($params['page_date'      ]) ? $params['page_date'      ] : $cur_date;
$show_in_blog    = !empty($params['show_in_blog'   ]) ? $params['show_in_blog'   ] : 0;
$enable_comments = !empty($params['enable_comments']) ? $params['enable_comments'] : 0;
$uploaded_thumb  = !empty($params['uploaded_thumb' ]) ? $params['uploaded_thumb' ] : '';

// check if empty fields
if(empty($page_title)) {
	die('Invalid empty parameters');
}

// create slug if empty
if(empty($page_slug)) {
	$page_slug = to_slug($page_title);
}

// check if slug is unique
$is_slug_unique = false;
$count = 2;
$new_page_slug = $page_slug;

while(!$is_slug_unique) {
	$query = "SELECT COUNT(*) AS total_rows FROM pages WHERE page_slug = :page_slug AND page_id <> :page_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':page_slug', $new_page_slug);
	$stmt->bindValue(':page_id', $page_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row['total_rows'] == 0) {
		$is_slug_unique = true;
	}

	else {
		$new_page_slug = $page_slug . '-' . $count;
		$count++;
	}
}

// page status
$page_status = 1;
if($show_in_blog == 0) {
	$page_status = 0;
}

// original page link
$query = "SELECT page_slug FROM pages WHERE page_id = :page_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':page_id', $page_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$original_slug = !empty($row['page_slug']) ? $row['page_slug'] : '';
$original_page_link = "$baseurl/post/$original_slug";

// update
$query = "UPDATE pages SET
			page_title      = :page_title,
			page_slug       = :page_slug,
			meta_desc       = :meta_desc,
			page_contents   = :page_contents,
			page_date       = :page_date,
			page_status     = :page_status,
			enable_comments = :enable_comments
		WHERE page_id = :page_id";

$stmt = $conn->prepare($query);
$stmt->bindValue(':page_id'        , $page_id);
$stmt->bindValue(':page_title'     , $page_title);
$stmt->bindValue(':page_slug'      , $new_page_slug);
$stmt->bindValue(':meta_desc'      , $meta_desc);
$stmt->bindValue(':page_contents'  , $page_contents);
$stmt->bindValue(':page_date'      , $page_date);
$stmt->bindValue(':page_status'    , $page_status);
$stmt->bindValue(':enable_comments', $enable_comments);
$stmt->execute();

echo $txt_page_updated;

$new_page_link = "$baseurl/post/$new_page_slug";
sitemap_remove_url($original_page_link);
sitemap_add_url($new_page_link);

/*--------------------------------------------------
Page thumb
--------------------------------------------------*/

// get extension of uploaded image
if(!empty($uploaded_thumb)) {
	$thumb_tmp = $pic_basepath . '/page-thumb-tmp/' . $uploaded_thumb;
	$path_parts = pathinfo($thumb_tmp);
	$img_ext = $path_parts['extension'];

	// final destination
	$img_final = $pic_basepath . '/page-thumb/page-' . $page_id . '.' . $img_ext;

	if(is_file($thumb_tmp)) {
		if(copy($thumb_tmp, $img_final)) {
			unlink($thumb_tmp);
		}
	}
}
