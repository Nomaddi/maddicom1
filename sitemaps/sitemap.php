<?php
declare(strict_types=1);

include('../inc/config.php');
include(__DIR__ . '/sitemap-functions.php');

sitemap_build_sitemap($cfg_permalink_struct);