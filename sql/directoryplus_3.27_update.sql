ALTER TABLE `places` CHANGE `short_desc` `short_desc` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

UPDATE `language` SET template = 'global' WHERE var_name = 'txt_please_wait';

UPDATE `email_templates` SET `available_vars` = '%site_name%\n%site_url%\n%listing_link%' WHERE `type` IN('subscr_signup','web_accept','subscr_eot');
