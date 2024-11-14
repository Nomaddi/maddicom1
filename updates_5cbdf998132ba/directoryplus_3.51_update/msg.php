<?php
require_once(__DIR__ . '/inc/config.php');

$txt_main_title = (!empty($txt_main_title_stripe)) ? $txt_main_title_stripe : 'Thank you for your payment';
$txt_msg        = (!empty($txt_msg_stripe       )) ? $txt_msg_stripe        : 'Payment successful. Thank you for your business!';