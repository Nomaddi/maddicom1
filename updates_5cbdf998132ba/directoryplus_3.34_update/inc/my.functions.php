<?php
/*
In this file you can write your custom PHP code and it will run after 'inc/common.inc.php' and before the core file for the page that is being loaded.

The most common use case is for overwriting main configuration variables.
*/
// input field type for fields like smtp passwords, api secrets, etc.
$input_password = "text";

// coupon default size (w x h)
$coupon_size = array(480, 480);

// logo default size (w x h)
$cfg_logo_size = array(480, 480);
$cfg_logo_quality = 95;

// short_desc field max length
$short_desc_length = 100;

// use disqus (yes = 1; no = 0)
$use_disqus = 1;

// default cat icon
$cfg_default_cat_icon = '';

// min seconds between sending contact messages
$cgf_min_secs = 5;

// thumb width
$global_thumb_width = 640;
$global_thumb_height = 640;

// custom field type toggle values list
$cfg_custom_field_toggle_values = 'yes;no';

// default cat bg
$cfg_default_cat_bg = "#f6f6f6";

// show custom field icons
$cfg_show_custom_fields_icons = false;

// when select2 is not used, this is the limit for the number of option elements
$cfg_city_dropdown_limit = 200;

// homepage hero image
$cfg_hero_img = $baseurl . '/assets/imgs/hero01.jpg';

// enable GDPR
$cfg_gdpr_on = true;

// currency without cents
$cfg_cur_without_cents = false;

// currency symbol position
$cfg_cur_symbol_pos = 'left';

// show maps on search results
$cfg_show_maps_on_listings = true;

// enable sitemaps
$cfg_enable_sitemaps = true;

// stripe cents
$stripe_min_unit_is_cent = true;

// auto approve listing
$cfg_auto_approve_listing = false;

// show country calling code on listing page
$cfg_show_country_calling_code = false;

// show image or icon for categories on the home page
$cfg_cat_display = 'image';

// show website link in results pages
$cfg_show_website = true;
