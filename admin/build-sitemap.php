<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

$start_time = microtime(true);

sitemap_build_sitemap($cfg_permalink_struct);

$end_time = microtime(true);

$time = $end_time - $start_time;

echo 'Sitemap generated: ' . number_format($time, 4) . ' seconds';