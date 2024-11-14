<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/iso-639-1.php');
require_once(__DIR__ . '/../inc/iso-3166-1.php');
require_once(__DIR__ . '/_admin_inc.php');

// valid timezones
$timezone_identifiers = DateTimeZone::listIdentifiers();

// valid paypal locales
$paypal_locale_identifiers = array(
	'AU', 'AT', 'BE', 'BR', 'CA', 'CH', 'CN', 'DE', 'ES', 'GB', 'FR', 'IT', 'NL', 'PL', 'PT', 'RU', 'US', 'cs_CZ', 'da_DK', 'he_IL', 'id_ID', 'ja_JP', 'no_NO', 'pt_BR', 'ru_RU', 'sv_SE', 'th_TH', 'tr_TR', 'zh_CN', 'zh_HK', 'zh_TW');

// radio buttons for paypal
$checked_live     = '';
$checked_sandbox  = '';
$checked_disabled = '';
if($paypal_mode == 1)  $checked_live     = 'checked';
if($paypal_mode == 0)  $checked_sandbox  = 'checked';
if($paypal_mode == -1) $checked_disabled = 'checked';

// radio buttons for stripe
$stripe_checked_live     = '';
$stripe_checked_sandbox  = '';
$stripe_checked_disabled = '';

if($stripe_mode == 1)  $stripe_checked_live     = 'checked';
if($stripe_mode == 0)  $stripe_checked_sandbox  = 'checked';
if($stripe_mode == -1) $stripe_checked_disabled = 'checked';

// map apis
$mapbox_secret = !empty($mapbox_secret) ? $mapbox_secret : '';
$tomtom_secret = !empty($tomtom_secret) ? $tomtom_secret : '';
$here_key      = !empty($here_key     ) ? $here_key      : '';
$here_secret   = !empty($here_secret  ) ? $here_secret   : '';

// map provider checkboxes
$mapbox_checked    = '';
$wikimedia_checked = '';
$osm_checked       = '';
$tomtom_checked    = '';
$here_checked      = '';
$google_checked    = '';
$cartov_checked    = '';
$cartop_checked    = '';
$stamen_checked    = '';

$map_providers = unserialize($map_providers);

foreach($map_providers as $v) {
	if($v == 'MapBox') {
		$mapbox_checked = 'checked';
	}

	if($v == 'Wikimedia') {
		$wikimedia_checked = 'checked';
	}

	if($v == 'OpenStreetMap') {
		$osm_checked = 'checked';
	}

	if($v == 'Tomtom') {
		$tomtom_checked = 'checked';
	}

	if($v == 'HERE') {
		$here_checked = 'checked';
	}

	if($v == 'Google') {
		$google_checked = 'checked';
	}

	if($v == 'CartoDB.Voyager') {
		$cartov_checked = 'checked';
	}

	if($v == 'CartoDB.Positron') {
		$cartop_checked = 'checked';
	}

	if($v == 'Stamen.Terrain') {
		$stamen_checked = 'checked';
	}
}

// dont use paypal_merchant_id that could be overwritten in common.php
$query = "SELECT * FROM config WHERE property = 'paypal_merchant_id'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$paypal_merchant_id = $row['value'];

/*--------------------------------------------------
other vars (should use the same query but will do later)
--------------------------------------------------*/

// init
$maintenance_mode = 0;
$cfg_contact_business_subject = 'You received a message about your ad';
$cfg_contact_user_subject = 'You received a message';

// get vars
$query = "SELECT * FROM config";
$stmt  = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	switch ($row['property']) {
		case 'maintenance_mode':
			$maintenance_mode = !empty($row['value']) ? $row['value'] : 0;
			break;

		case 'cfg_contact_business_subject':
			$cfg_contact_business_subject = $row['value'];
			break;

		case 'cfg_contact_user_subject':
			$cfg_contact_user_subject = $row['value'];
			break;
	}
}

/*--------------------------------------------------
Language strings defaults for newly created strings in 3.52
--------------------------------------------------*/

$txt_auto_approve_listing = !empty($txt_auto_approve_listing) ? $txt_auto_approve_listing : 'Auto-approve listings';
$txt_smtp_encryption      = !empty($txt_smtp_encryption     ) ? $txt_smtp_encryption      : 'SMTP encryption';
$txt_create_webhook       = !empty($txt_create_webhook      ) ? $txt_create_webhook       : 'Create webhook';
$txt_create_test_webhook  = !empty($txt_create_test_webhook ) ? $txt_create_test_webhook  : 'Create test webhook';
$txt_create_live_webhook  = !empty($txt_create_live_webhook ) ? $txt_create_live_webhook  : 'Create live webhook';
$txt_cents                = !empty($txt_cents               ) ? $txt_cents                : 'Cents';
$txt_none                 = !empty($txt_none                ) ? $txt_none                 : 'None';
