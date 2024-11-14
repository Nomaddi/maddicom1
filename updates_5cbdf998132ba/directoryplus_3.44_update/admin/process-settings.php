<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/iso-639-1.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_php.php');

// post vars, first set to empty if not exists, set defaults later to save line space
$admin_email                   = !empty($_POST['admin_email'                  ]) ? $_POST['admin_email'                  ] : '';
$cfg_contact_business_subject  = !empty($_POST['cfg_contact_business_subject' ]) ? $_POST['cfg_contact_business_subject' ] : '';
$cfg_contact_user_subject      = !empty($_POST['cfg_contact_user_subject'     ]) ? $_POST['cfg_contact_user_subject'     ] : '';
$cfg_date_format               = !empty($_POST['cfg_date_format'              ]) ? $_POST['cfg_date_format'              ] : '';
$cfg_decimal_separator         = !empty($_POST['cfg_decimal_separator'        ]) ? $_POST['cfg_decimal_separator'        ] : '';
$cfg_languages                 = !empty($_POST['cfg_languages'                ]) ? $_POST['cfg_languages'                ] : '';
$cfg_latest_listings_count     = !empty($_POST['cfg_latest_listings_count'    ]) ? $_POST['cfg_latest_listings_count'    ] : '';
$cfg_use_select2               = !empty($_POST['cfg_use_select2'              ]) ? $_POST['cfg_use_select2'              ] : '';
$cgf_near_listings_radius      = !empty($_POST['cgf_near_listings_radius'     ]) ? $_POST['cgf_near_listings_radius'     ] : '';
$country_name                  = !empty($_POST['country_name'                 ]) ? $_POST['country_name'                 ] : '';
$currency_code                 = !empty($_POST['currency_code'                ]) ? $_POST['currency_code'                ] : '';
$currency_symbol               = !empty($_POST['currency_symbol'              ]) ? $_POST['currency_symbol'              ] : '';
$default_city_slug             = !empty($_POST['default_city_slug'            ]) ? $_POST['default_city_slug'            ] : '';
$default_country_code          = !empty($_POST['default_country_code'         ]) ? $_POST['default_country_code'         ] : '';
$default_lat                   = !empty($_POST['default_lat'                  ]) ? $_POST['default_lat'                  ] : '';
$default_lng                   = !empty($_POST['default_lng'                  ]) ? $_POST['default_lng'                  ] : '';
$default_loc_id                = !empty($_POST['default_loc_id'               ]) ? $_POST['default_loc_id'               ] : '';
$dev_email                     = !empty($_POST['dev_email'                    ]) ? $_POST['dev_email'                    ] : '';
$disqus_shortname              = !empty($_POST['disqus_shortname'             ]) ? $_POST['disqus_shortname'             ] : '';
$facebook_key                  = !empty($_POST['facebook_key'                 ]) ? $_POST['facebook_key'                 ] : '';
$facebook_secret               = !empty($_POST['facebook_secret'              ]) ? $_POST['facebook_secret'              ] : '';
$google_key                    = !empty($_POST['google_key'                   ]) ? $_POST['google_key'                   ] : '';
$here_key                      = !empty($_POST['here_key'                     ]) ? $_POST['here_key'                     ] : '';
$here_secret                   = !empty($_POST['here_secret'                  ]) ? $_POST['here_secret'                  ] : '';
$html_lang                     = !empty($_POST['html_lang'                    ]) ? $_POST['html_lang'                    ] : '';
$items_per_page                = !empty($_POST['items_per_page'               ]) ? $_POST['items_per_page'               ] : '';
$mail_after_post               = !empty($_POST['mail_after_post'              ]) ? $_POST['mail_after_post'              ] : '';
$maintenance_mode              = !empty($_POST['maintenance_mode'             ]) ? $_POST['maintenance_mode'             ] : '';
$mapbox_secret                 = !empty($_POST['mapbox_secret'                ]) ? $_POST['mapbox_secret'                ] : '';
$max_pics                      = !empty($_POST['max_pics'                     ]) ? $_POST['max_pics'                     ] : '';
$paypal_bn                     = !empty($_POST['paypal_bn'                    ]) ? $_POST['paypal_bn'                    ] : '';
$paypal_checkout_logo_url      = !empty($_POST['paypal_checkout_logo_url'     ]) ? $_POST['paypal_checkout_logo_url'     ] : '';
$paypal_locale                 = !empty($_POST['paypal_locale'                ]) ? $_POST['paypal_locale'                ] : '';
$paypal_merchant_id            = !empty($_POST['paypal_merchant_id'           ]) ? $_POST['paypal_merchant_id'           ] : '';
$paypal_sandbox_merch_id       = !empty($_POST['paypal_sandbox_merch_id'      ]) ? $_POST['paypal_sandbox_merch_id'      ] : '';
$site_logo_width               = !empty($_POST['site_logo_width'              ]) ? $_POST['site_logo_width'              ] : '';
$site_name                     = !empty($_POST['site_name'                    ]) ? $_POST['site_name'                    ] : '';
$smtp_pass                     = !empty($_POST['smtp_pass'                    ]) ? $_POST['smtp_pass'                    ] : '';
$smtp_port                     = !empty($_POST['smtp_port'                    ]) ? $_POST['smtp_port'                    ] : '';
$smtp_server                   = !empty($_POST['smtp_server'                  ]) ? $_POST['smtp_server'                  ] : '';
$smtp_user                     = !empty($_POST['smtp_user'                    ]) ? $_POST['smtp_user'                    ] : '';
$stripe_currency_symbol        = !empty($_POST['stripe_currency_symbol'       ]) ? $_POST['stripe_currency_symbol'       ] : '';
$stripe_data_currency          = !empty($_POST['stripe_data_currency'         ]) ? $_POST['stripe_data_currency'         ] : '';
$stripe_data_description       = !empty($_POST['stripe_data_description'      ]) ? $_POST['stripe_data_description'      ] : '';
$stripe_data_image             = !empty($_POST['stripe_data_image'            ]) ? $_POST['stripe_data_image'            ] : '';
$stripe_live_publishable_key   = !empty($_POST['stripe_live_publishable_key'  ]) ? $_POST['stripe_live_publishable_key'  ] : '';
$stripe_live_secret_key        = !empty($_POST['stripe_live_secret_key'       ]) ? $_POST['stripe_live_secret_key'       ] : '';
$stripe_test_publishable_key   = !empty($_POST['stripe_test_publishable_key'  ]) ? $_POST['stripe_test_publishable_key'  ] : '';
$stripe_test_secret_key        = !empty($_POST['stripe_test_secret_key'       ]) ? $_POST['stripe_test_secret_key'       ] : '';
$timezone                      = !empty($_POST['timezone'                     ]) ? $_POST['timezone'                     ] : '';
$tomtom_secret                 = !empty($_POST['tomtom_secret'                ]) ? $_POST['tomtom_secret'                ] : '';
$twitter_key                   = !empty($_POST['twitter_key'                  ]) ? $_POST['twitter_key'                  ] : '';
$twitter_secret                = !empty($_POST['twitter_secret'               ]) ? $_POST['twitter_secret'               ] : '';
$user_created_notify           = !empty($_POST['user_created_notify'          ]) ? $_POST['user_created_notify'          ] : '';
$cfg_permalink_struct          = !empty($_POST['cfg_permalink_struct'         ]) ? $_POST['cfg_permalink_struct'         ] : '';
$cgf_max_dist_values           = !empty($_POST['cgf_max_dist_values'          ]) ? $_POST['cgf_max_dist_values'          ] : '';
$cgf_max_dist_unit             = !empty($_POST['cgf_max_dist_unit'            ]) ? $_POST['cgf_max_dist_unit'            ] : '';
$cfg_smtp_encryption           = !empty($_POST['cfg_smtp_encryption'          ]) ? $_POST['cfg_smtp_encryption'          ] : '';
$cfg_auto_approve_listing      = !empty($_POST['cfg_auto_approve_listing'     ]) ? $_POST['cfg_auto_approve_listing'     ] : '';
$cfg_enable_sitemaps           = !empty($_POST['cfg_enable_sitemaps'          ]) ? $_POST['cfg_enable_sitemaps'          ] : '';
$cfg_enable_reviews            = !empty($_POST['cfg_enable_reviews'           ]) ? $_POST['cfg_enable_reviews'           ] : '';
$cfg_enable_coupons            = !empty($_POST['cfg_enable_coupons'           ]) ? $_POST['cfg_enable_coupons'           ] : '';
$cfg_show_country_calling_code = !empty($_POST['cfg_show_country_calling_code']) ? $_POST['cfg_show_country_calling_code'] : '';
$stripe_min_unit_is_cent       = !empty($_POST['stripe_min_unit_is_cent'      ]) ? $_POST['stripe_min_unit_is_cent'      ] : '';
$cfg_gdpr_on                   = !empty($_POST['cfg_gdpr_on'                  ]) ? $_POST['cfg_gdpr_on'                  ] : '';
$use_disqus                    = !empty($_POST['use_disqus'                   ]) ? $_POST['use_disqus'                   ] : '';
$cfg_cur_without_cents         = !empty($_POST['cfg_cur_without_cents'        ]) ? $_POST['cfg_cur_without_cents'        ] : '';

// isset
$stripe_mode  = isset($_POST['stripe_mode' ]) ? $_POST['stripe_mode' ] : -1;
$paypal_mode  = isset($_POST['paypal_mode' ]) ? $_POST['paypal_mode' ] : -1;
$map_provider = isset($_POST['map_provider']) ? $_POST['map_provider'] : array('Wikimedia');

// trim
$admin_email                   = trim($admin_email                  );
$cfg_contact_business_subject  = trim($cfg_contact_business_subject );
$cfg_contact_user_subject      = trim($cfg_contact_user_subject     );
$cfg_date_format               = trim($cfg_date_format              );
$cfg_decimal_separator         = trim($cfg_decimal_separator        );
$cfg_languages                 = trim($cfg_languages                );
$cfg_latest_listings_count     = trim($cfg_latest_listings_count    );
$cfg_use_select2               = trim($cfg_use_select2              );
$cgf_near_listings_radius      = trim($cgf_near_listings_radius     );
$country_name                  = trim($country_name                 );
$currency_code                 = trim($currency_code                );
$currency_symbol               = trim($currency_symbol              );
$default_city_slug             = trim($default_city_slug            );
$default_country_code          = trim($default_country_code         );
$default_lat                   = trim($default_lat                  );
$default_lng                   = trim($default_lng                  );
$default_loc_id                = trim($default_loc_id               );
$dev_email                     = trim($dev_email                    );
$disqus_shortname              = trim($disqus_shortname             );
$facebook_key                  = trim($facebook_key                 );
$facebook_secret               = trim($facebook_secret              );
$google_key                    = trim($google_key                   );
$here_key                      = trim($here_key                     );
$here_secret                   = trim($here_secret                  );
$html_lang                     = trim($html_lang                    );
$items_per_page                = trim($items_per_page               );
$mail_after_post               = trim($mail_after_post              );
$maintenance_mode              = trim($maintenance_mode             );
$mapbox_secret                 = trim($mapbox_secret                );
$max_pics                      = trim($max_pics                     );
$paypal_bn                     = trim($paypal_bn                    );
$paypal_checkout_logo_url      = trim($paypal_checkout_logo_url     );
$paypal_locale                 = trim($paypal_locale                );
$paypal_merchant_id            = trim($paypal_merchant_id           );
$paypal_mode                   = trim($paypal_mode                  );
$paypal_sandbox_merch_id       = trim($paypal_sandbox_merch_id      );
$site_logo_width               = trim($site_logo_width              );
$site_name                     = trim($site_name                    );
$smtp_pass                     = trim($smtp_pass                    );
$smtp_port                     = trim($smtp_port                    );
$smtp_server                   = trim($smtp_server                  );
$smtp_user                     = trim($smtp_user                    );
$stripe_currency_symbol        = trim($stripe_currency_symbol       );
$stripe_data_currency          = trim($stripe_data_currency         );
$stripe_data_description       = trim($stripe_data_description      );
$stripe_data_image             = trim($stripe_data_image            );
$stripe_live_publishable_key   = trim($stripe_live_publishable_key  );
$stripe_live_secret_key        = trim($stripe_live_secret_key       );
$stripe_mode                   = trim($stripe_mode                  );
$stripe_test_publishable_key   = trim($stripe_test_publishable_key  );
$stripe_test_secret_key        = trim($stripe_test_secret_key       );
$timezone                      = trim($timezone                     );
$tomtom_secret                 = trim($tomtom_secret                );
$twitter_key                   = trim($twitter_key                  );
$twitter_secret                = trim($twitter_secret               );
$user_created_notify           = trim($user_created_notify          );
$cfg_permalink_struct          = trim($cfg_permalink_struct, '/'    );
$cgf_max_dist_values           = trim($cgf_max_dist_values          );
$cgf_max_dist_unit             = trim($cgf_max_dist_unit            );
$cfg_smtp_encryption           = trim($cfg_smtp_encryption          );
$cfg_auto_approve_listing      = trim($cfg_auto_approve_listing     );
$cfg_enable_sitemaps           = trim($cfg_enable_sitemaps          );
$cfg_enable_reviews            = trim($cfg_enable_reviews           );
$cfg_enable_coupons            = trim($cfg_enable_coupons           );
$cfg_show_country_calling_code = trim($cfg_show_country_calling_code);
$stripe_min_unit_is_cent       = trim($stripe_min_unit_is_cent      );
$cfg_gdpr_on                   = trim($cfg_gdpr_on                  );
$use_disqus                    = trim($use_disqus                   );
$cfg_cur_without_cents         = trim($cfg_cur_without_cents        );

//defaults
$admin_email                  = !empty($admin_email                 ) ? $admin_email                  : 'admin@email.com';
$dev_email                    = !empty($dev_email                   ) ? $dev_email                    : 'dev@email.com';
$smtp_server                  = !empty($smtp_server                 ) ? $smtp_server                  : '';
$smtp_user                    = !empty($smtp_user                   ) ? $smtp_user                    : '';
$smtp_pass                    = !empty($smtp_pass                   ) ? $smtp_pass                    : '';
$smtp_port                    = !empty($smtp_port                   ) ? $smtp_port                    : '';
$google_key                   = !empty($google_key                  ) ? $google_key                   : '';
$mapbox_secret                = !empty($mapbox_secret               ) ? $mapbox_secret                : '';
$tomtom_secret                = !empty($tomtom_secret               ) ? $tomtom_secret                : '';
$here_key                     = !empty($here_key                    ) ? $here_key                     : '';
$here_secret                  = !empty($here_secret                 ) ? $here_secret                  : '';
$facebook_key                 = !empty($facebook_key                ) ? $facebook_key                 : '';
$facebook_secret              = !empty($facebook_secret             ) ? $facebook_secret              : '';
$twitter_key                  = !empty($twitter_key                 ) ? $twitter_key                  : '';
$twitter_secret               = !empty($twitter_secret              ) ? $twitter_secret               : '';
$items_per_page               = !empty($items_per_page              ) ? $items_per_page               : '20';
$site_name                    = !empty($site_name                   ) ? $site_name                    : 'Business Directory';
$country_name                 = !empty($country_name                ) ? $country_name                 : 'United States';
$default_country_code         = !empty($default_country_code        ) ? $default_country_code         : 'us';
$default_city_slug            = !empty($default_city_slug           ) ? $default_city_slug            : 'city-slug';
$default_loc_id               = !empty($default_loc_id              ) ? $default_loc_id               : '1';
$timezone                     = !empty($timezone                    ) ? $timezone                     : 'America/Los_Angeles';
$default_lat                  = !empty($default_lat                 ) ? $default_lat                  : '37.3002752813443';
$default_lng                  = !empty($default_lng                 ) ? $default_lng                  : '-94.482421875';
$html_lang                    = in_array($html_lang, $iso_639_1     ) ? $html_lang                    : 'en';
$max_pics                     = !empty($max_pics                    ) ? $max_pics                     : '15';
$paypal_merchant_id           = !empty($paypal_merchant_id          ) ? $paypal_merchant_id           : '';
$paypal_bn                    = !empty($paypal_bn                   ) ? $paypal_bn                    : '';
$paypal_checkout_logo_url     = !empty($paypal_checkout_logo_url    ) ? $paypal_checkout_logo_url     : '';
$currency_code                = !empty($currency_code               ) ? $currency_code                : 'USD';
$currency_symbol              = !empty($currency_symbol             ) ? $currency_symbol              : '$';
$paypal_locale                = !empty($paypal_locale               ) ? $paypal_locale                : 'US';
$paypal_sandbox_merch_id      = !empty($paypal_sandbox_merch_id     ) ? $paypal_sandbox_merch_id      : '';
$stripe_mode                  = !empty($stripe_mode                 ) ? $stripe_mode                  : '0';
$stripe_test_secret_key       = !empty($stripe_test_secret_key      ) ? $stripe_test_secret_key       : '';
$stripe_test_publishable_key  = !empty($stripe_test_publishable_key ) ? $stripe_test_publishable_key  : '';
$stripe_live_secret_key       = !empty($stripe_live_secret_key      ) ? $stripe_live_secret_key       : '';
$stripe_live_publishable_key  = !empty($stripe_live_publishable_key ) ? $stripe_live_publishable_key  : '';
$stripe_data_currency         = !empty($stripe_data_currency        ) ? $stripe_data_currency         : 'USD';
$stripe_currency_symbol       = !empty($stripe_currency_symbol      ) ? $stripe_currency_symbol       : '$';
$stripe_data_image            = !empty($stripe_data_image           ) ? $stripe_data_image            : '';
$stripe_data_description      = !empty($stripe_data_description     ) ? $stripe_data_description      : '';
$cfg_languages                = !empty($cfg_languages               ) ? $cfg_languages                : 'en';
$cgf_near_listings_radius     = !empty($cgf_near_listings_radius    ) ? $cgf_near_listings_radius     : 150;
$cfg_latest_listings_count    = !empty($cfg_latest_listings_count   ) ? $cfg_latest_listings_count    : 20;
$site_logo_width              = !empty($site_logo_width             ) ? $site_logo_width              : 180;
$cfg_decimal_separator        = !empty($cfg_decimal_separator       ) ? $cfg_decimal_separator        : '.';
$cfg_date_format              = !empty($cfg_date_format             ) ? $cfg_date_format              : 'Y-m-d';
$cfg_smtp_encryption          = !empty($cfg_smtp_encryption         ) ? $cfg_smtp_encryption          : '';

// boolean settings
$cfg_enable_coupons            = !empty($cfg_enable_coupons           ) ? 1 : 0;
$cfg_enable_reviews            = !empty($cfg_enable_reviews           ) ? 1 : 0;
$cfg_enable_sitemaps           = !empty($cfg_enable_sitemaps          ) ? 1 : 0;
$cfg_auto_approve_listing      = !empty($cfg_auto_approve_listing     ) ? 1 : 0;
$cfg_use_select2               = !empty($cfg_use_select2              ) ? 1 : 0;
$mail_after_post               = !empty($mail_after_post              ) ? 1 : 0;
$maintenance_mode              = !empty($maintenance_mode             ) ? 1 : 0;
$user_created_notify           = !empty($user_created_notify          ) ? 1 : 0;
$cfg_show_country_calling_code = !empty($cfg_show_country_calling_code) ? 1 : 0;
$stripe_min_unit_is_cent       = !empty($stripe_min_unit_is_cent      ) ? 1 : 0;
$cfg_gdpr_on                   = !empty($cfg_gdpr_on                  ) ? 1 : 0;
$use_disqus                    = !empty($use_disqus                   ) ? 1 : 0;
$cfg_cur_without_cents         = !empty($cfg_cur_without_cents        ) ? 1 : 0;

// cast type
$smtp_port      = (int)$smtp_port;
$items_per_page = (int)$items_per_page;
$default_loc_id = (int)$default_loc_id;
$max_pics       = (int)$max_pics;

// map provider
$map_provider = serialize($map_provider);

try {
	$conn->beginTransaction();

	// first delete all
	$query = "DELETE FROM config WHERE type IN('email', 'api', 'config', 'maps', 'payment')";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	// reinsert values
	$query = "INSERT INTO config(type, property, value) VALUES
		('email'  , 'admin_email'                  , :admin_email                  ),
		('email'  , 'dev_email'                    , :dev_email                    ),
		('email'  , 'smtp_server'                  , :smtp_server                  ),
		('email'  , 'smtp_user'                    , :smtp_user                    ),
		('email'  , 'smtp_pass'                    , :smtp_pass                    ),
		('email'  , 'smtp_port'                    , :smtp_port                    ),
		('email'  , 'cfg_smtp_encryption'          , :cfg_smtp_encryption          ),
		('api'    , 'google_key'                   , :google_key                   ),
		('api'    , 'mapbox_secret'                , :mapbox_secret                ),
		('api'    , 'tomtom_secret'                , :tomtom_secret                ),
		('api'    , 'here_key'                     , :here_key                     ),
		('api'    , 'here_secret'                  , :here_secret                  ),
		('api'    , 'facebook_key'                 , :facebook_key                 ),
		('api'    , 'facebook_secret'              , :facebook_secret              ),
		('api'    , 'twitter_key'                  , :twitter_key                  ),
		('api'    , 'twitter_secret'               , :twitter_secret               ),
		('api'    , 'disqus_shortname'             , :disqus_shortname             ),
		('config' , 'items_per_page'               , :items_per_page               ),
		('config' , 'site_name'                    , :site_name                    ),
		('config' , 'country_name'                 , :country_name                 ),
		('config' , 'default_country_code'         , :default_country_code         ),
		('config' , 'default_city_slug'            , :default_city_slug            ),
		('config' , 'default_loc_id'               , :default_loc_id               ),
		('config' , 'timezone'                     , :timezone                     ),
		('maps'   , 'default_lat'                  , :default_lat                  ),
		('maps'   , 'default_lng'                  , :default_lng                  ),
		('maps'   , 'map_provider'                 , :map_provider                 ),
		('config' , 'html_lang'                    , :html_lang                    ),
		('config' , 'max_pics'                     , :max_pics                     ),
		('config' , 'mail_after_post'              , :mail_after_post              ),
		('payment', 'paypal_merchant_id'           , :paypal_merchant_id           ),
		('payment', 'paypal_bn'                    , :paypal_bn                    ),
		('payment', 'paypal_checkout_logo_url'     , :paypal_checkout_logo_url     ),
		('payment', 'currency_code'                , :currency_code                ),
		('payment', 'currency_symbol'              , :currency_symbol              ),
		('payment', 'paypal_locale'                , :paypal_locale                ),
		('payment', 'paypal_mode'                  , :paypal_mode                  ),
		('payment', 'paypal_sandbox_merch_id'      , :paypal_sandbox_merch_id      ),
		('payment', 'stripe_mode'                  , :stripe_mode                  ),
		('payment', 'stripe_test_secret_key'       , :stripe_test_secret_key       ),
		('payment', 'stripe_test_publishable_key'  , :stripe_test_publishable_key  ),
		('payment', 'stripe_live_secret_key'       , :stripe_live_secret_key       ),
		('payment', 'stripe_live_publishable_key'  , :stripe_live_publishable_key  ),
		('payment', 'stripe_data_currency'         , :stripe_data_currency         ),
		('payment', 'stripe_currency_symbol'       , :stripe_currency_symbol       ),
		('payment', 'stripe_data_image'            , :stripe_data_image            ),
		('payment', 'stripe_data_description'      , :stripe_data_description      ),
		('config' , 'maintenance_mode'             , :maintenance_mode             ),
		('config' , 'cfg_languages'                , :cfg_languages                ),
		('config' , 'cgf_near_listings_radius'     , :cgf_near_listings_radius     ),
		('config' , 'cfg_latest_listings_count'    , :cfg_latest_listings_count    ),
		('config' , 'user_created_notify'          , :user_created_notify          ),
		('config' , 'site_logo_width'              , :site_logo_width              ),
		('config' , 'cfg_decimal_separator'        , :cfg_decimal_separator        ),
		('config' , 'cfg_use_select2'              , :cfg_use_select2              ),
		('config' , 'cfg_contact_business_subject' , :cfg_contact_business_subject ),
		('config' , 'cfg_contact_user_subject'     , :cfg_contact_user_subject     ),
		('config' , 'cfg_permalink_struct'         , :cfg_permalink_struct         ),
		('config' , 'cfg_date_format'              , :cfg_date_format              ),
		('config' , 'cgf_max_dist_values'          , :cgf_max_dist_values          ),
		('config' , 'cgf_max_dist_unit'            , :cgf_max_dist_unit            ),
		('config' , 'cfg_auto_approve_listing'     , :cfg_auto_approve_listing     ),
		('config' , 'cfg_enable_sitemaps'          , :cfg_enable_sitemaps          ),
		('config' , 'cfg_enable_coupons'           , :cfg_enable_coupons           ),
		('config' , 'cfg_enable_reviews'           , :cfg_enable_reviews           ),
		('config' , 'cfg_show_country_calling_code', :cfg_show_country_calling_code),
		('config' , 'stripe_min_unit_is_cent'      , :stripe_min_unit_is_cent      ),
		('config' , 'cfg_gdpr_on'                  , :cfg_gdpr_on                  ),
		('config' , 'use_disqus'                   , :use_disqus                   ),
		('config' , 'cfg_cur_without_cents'        , :cfg_cur_without_cents        )
		";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':admin_email'                  , $admin_email                  );
	$stmt->bindValue(':dev_email'                    , $dev_email                    );
	$stmt->bindValue(':smtp_server'                  , $smtp_server                  );
	$stmt->bindValue(':smtp_user'                    , $smtp_user                    );
	$stmt->bindValue(':smtp_pass'                    , $smtp_pass                    );
	$stmt->bindValue(':smtp_port'                    , $smtp_port                    );
	$stmt->bindValue(':google_key'                   , $google_key                   );
	$stmt->bindValue(':mapbox_secret'                , $mapbox_secret                );
	$stmt->bindValue(':tomtom_secret'                , $tomtom_secret                );
	$stmt->bindValue(':here_key'                     , $here_key                     );
	$stmt->bindValue(':here_secret'                  , $here_secret                  );
	$stmt->bindValue(':items_per_page'               , $items_per_page               );
	$stmt->bindValue(':site_name'                    , $site_name                    );
	$stmt->bindValue(':country_name'                 , $country_name                 );
	$stmt->bindValue(':default_country_code'         , $default_country_code         );
	$stmt->bindValue(':default_city_slug'            , $default_city_slug            );
	$stmt->bindValue(':default_loc_id'               , $default_loc_id               );
	$stmt->bindValue(':timezone'                     , $timezone                     );
	$stmt->bindValue(':default_lat'                  , $default_lat                  );
	$stmt->bindValue(':default_lng'                  , $default_lng                  );
	$stmt->bindValue(':map_provider'                 , $map_provider                 );
	$stmt->bindValue(':html_lang'                    , $html_lang                    );
	$stmt->bindValue(':max_pics'                     , $max_pics                     );
	$stmt->bindValue(':mail_after_post'              , $mail_after_post              );
	$stmt->bindValue(':paypal_merchant_id'           , $paypal_merchant_id           );
	$stmt->bindValue(':paypal_bn'                    , $paypal_bn                    );
	$stmt->bindValue(':paypal_checkout_logo_url'     , $paypal_checkout_logo_url     );
	$stmt->bindValue(':currency_code'                , $currency_code                );
	$stmt->bindValue(':currency_symbol'              , $currency_symbol              );
	$stmt->bindValue(':paypal_locale'                , $paypal_locale                );
	$stmt->bindValue(':paypal_mode'                  , $paypal_mode                  );
	$stmt->bindValue(':paypal_sandbox_merch_id'      , $paypal_sandbox_merch_id      );
	$stmt->bindValue(':facebook_key'                 , $facebook_key                 );
	$stmt->bindValue(':facebook_secret'              , $facebook_secret              );
	$stmt->bindValue(':twitter_key'                  , $twitter_key                  );
	$stmt->bindValue(':twitter_secret'               , $twitter_secret               );
	$stmt->bindValue(':disqus_shortname'             , $disqus_shortname             );
	$stmt->bindValue(':stripe_mode'                  , $stripe_mode                  );
	$stmt->bindValue(':stripe_test_secret_key'       , $stripe_test_secret_key       );
	$stmt->bindValue(':stripe_test_publishable_key'  , $stripe_test_publishable_key  );
	$stmt->bindValue(':stripe_live_secret_key'       , $stripe_live_secret_key       );
	$stmt->bindValue(':stripe_live_publishable_key'  , $stripe_live_publishable_key  );
	$stmt->bindValue(':stripe_data_currency'         , $stripe_data_currency         );
	$stmt->bindValue(':stripe_currency_symbol'       , $stripe_currency_symbol       );
	$stmt->bindValue(':stripe_data_image'            , $stripe_data_image            );
	$stmt->bindValue(':stripe_data_description'      , $stripe_data_description      );
	$stmt->bindValue(':maintenance_mode'             , $maintenance_mode             );
	$stmt->bindValue(':cfg_languages'                , $cfg_languages                );
	$stmt->bindValue(':cgf_near_listings_radius'     , $cgf_near_listings_radius     );
	$stmt->bindValue(':cfg_latest_listings_count'    , $cfg_latest_listings_count    );
	$stmt->bindValue(':user_created_notify'          , $user_created_notify          );
	$stmt->bindValue(':site_logo_width'              , $site_logo_width              );
	$stmt->bindValue(':cfg_decimal_separator'        , $cfg_decimal_separator        );
	$stmt->bindValue(':cfg_use_select2'              , $cfg_use_select2              );
	$stmt->bindValue(':cfg_date_format'              , $cfg_date_format              );
	$stmt->bindValue(':cfg_contact_business_subject' , $cfg_contact_business_subject );
	$stmt->bindValue(':cfg_contact_user_subject'     , $cfg_contact_user_subject     );
	$stmt->bindValue(':cfg_permalink_struct'         , $cfg_permalink_struct         );
	$stmt->bindValue(':cgf_max_dist_values'          , $cgf_max_dist_values          );
	$stmt->bindValue(':cgf_max_dist_unit'            , $cgf_max_dist_unit            );
	$stmt->bindValue(':cfg_smtp_encryption'          , $cfg_smtp_encryption          );
	$stmt->bindValue(':cfg_auto_approve_listing'     , $cfg_auto_approve_listing     );
	$stmt->bindValue(':cfg_enable_sitemaps'          , $cfg_enable_sitemaps          );
	$stmt->bindValue(':cfg_enable_coupons'           , $cfg_enable_coupons           );
	$stmt->bindValue(':cfg_enable_reviews'           , $cfg_enable_reviews           );
	$stmt->bindValue(':cfg_show_country_calling_code', $cfg_show_country_calling_code);
	$stmt->bindValue(':stripe_min_unit_is_cent'      , $stripe_min_unit_is_cent      );
	$stmt->bindValue(':cfg_gdpr_on'                  , $cfg_gdpr_on                  );
	$stmt->bindValue(':use_disqus'                   , $use_disqus                   );
	$stmt->bindValue(':cfg_cur_without_cents'        , $cfg_cur_without_cents        );
	$stmt->execute();

	if($conn->inTransaction()) {
		$conn->commit();
	}

	$result_message = $txt_update_success;
}

catch(PDOException $e) {
	$conn->rollBack();
	$result_message =  $e->getMessage();
}
