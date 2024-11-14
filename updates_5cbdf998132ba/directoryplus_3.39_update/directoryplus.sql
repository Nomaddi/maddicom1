-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 29, 2018 at 04:32 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `directoryplus_100`
--

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE IF NOT EXISTS `cats` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `plural_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cat_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `cat_icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cat_order` int(10) NOT NULL DEFAULT '0',
  `cat_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`id`, `name`, `plural_name`, `cat_slug`, `parent_id`, `cat_icon`, `cat_order`, `cat_status`) VALUES
(20, 'Automotive', 'Automotive', 'automotive', 0, '', 0, 1),
(21, 'Beauty', 'Beauty', 'beauty', 0, '', 0, 1),
(22, 'Computer', 'Computer', 'computer', 0, '', 0, 1),
(23, 'Entertainment', 'Entertainment', 'entertainment', 0, '', 0, 1),
(24, 'Events', 'Events', 'events', 0, '', 0, 1),
(25, 'Financial', 'Financial', 'financial', 0, '', 0, 1),
(26, 'Food', 'Food', 'food', 0, '', 0, 1),
(27, 'Health & Wellness', 'Health & Wellness', 'health-wellness', 0, '', 0, 1),
(28, 'Home Improvement', 'Home Improvement', 'home-improvement', 0, '', 0, 1),
(29, 'Hotels & Travel', 'Hotels & Travel', 'hotels-travel', 0, '', 0, 1),
(30, 'Legal', 'Legal', 'legal', 0, '', 0, 1),
(31, 'Lessons', 'Lessons', 'lessons', 0, '', 0, 1),
(32, 'Local Services', 'Local Services', 'local-services', 0, '', 0, 1),
(33, 'Pets', 'Pets', 'pets', 0, '', 0, 1),
(34, 'Shopping', 'Shopping', 'shopping', 0, '', 0, 1),
(35, 'Real Estate', 'Real Estate', 'real-estate', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `state_id` int(11) DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities_feat`
--

CREATE TABLE IF NOT EXISTS `cities_feat` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) unsigned NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `property` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `type`, `property`, `value`) VALUES
(1, 'email', 'admin_email', 'admin@email.com'),
(2, 'email', 'dev_email', 'dev@email.com'),
(3, 'email', 'smtp_server', 'tls://mail.example.com'),
(4, 'email', 'smtp_user', 'user@example.com'),
(5, 'email', 'smtp_pass', ''),
(6, 'email', 'smtp_port', '465'),
(7, 'api', 'google_key', ''),
(8, 'api', 'mapbox_secret', ''),
(9, 'api', 'tomtom_secret', ''),
(10, 'api', 'here_key', ''),
(11, 'api', 'here_secret', ''),
(12, 'api', 'facebook_key', ''),
(13, 'api', 'facebook_secret', ''),
(14, 'api', 'twitter_key', ''),
(15, 'api', 'twitter_secret', ''),
(16, 'api', 'disqus_shortname', ''),
(17, 'config', 'items_per_page', '20'),
(18, 'config', 'site_name', 'Business Directory'),
(19, 'config', 'country_name', 'United States'),
(20, 'config', 'default_country_code', 'US'),
(21, 'config', 'default_city_slug', 'city-slug'),
(22, 'config', 'default_loc_id', '1'),
(23, 'config', 'timezone', 'America/Los_Angeles'),
(24, 'maps', 'default_lat', ''),
(25, 'maps', 'default_lng', ''),
(26, 'maps', 'map_provider', 'a:1:{i:0;s:9:"Wikimedia";}'),
(27, 'config', 'html_lang', 'en'),
(28, 'config', 'max_pics', '15'),
(29, 'config', 'mail_after_post', '0'),
(30, 'payment', 'paypal_merchant_id', ''),
(31, 'payment', 'paypal_bn', 'DirectoryPlus'),
(32, 'payment', 'paypal_checkout_logo_url', ''),
(33, 'payment', 'currency_code', 'USD'),
(34, 'payment', 'currency_symbol', '$'),
(35, 'payment', 'paypal_locale', 'US'),
(36, 'payment', 'paypal_mode', '0'),
(37, 'payment', 'paypal_sandbox_merch_id', ''),
(43, 'payment', 'stripe_mode', '0'),
(44, 'payment', 'stripe_test_secret_key', ''),
(45, 'payment', 'stripe_test_publishable_key', ''),
(46, 'payment', 'stripe_live_secret_key', ''),
(47, 'payment', 'stripe_live_publishable_key', ''),
(48, 'payment', 'stripe_data_currency', 'USD'),
(49, 'payment', 'stripe_currency_symbol', '$'),
(50, 'payment', 'stripe_data_image', ''),
(51, 'payment', 'stripe_data_description', ''),
(52, 'config', 'maintenance_mode', '0'),
(53, 'config', 'cgf_near_listings_radius', '100'),
(54, 'config', 'cfg_latest_listings_count', '12'),
(55, 'config', 'user_created_notify', '1'),
(56, 'config', 'site_logo_width', '180'),
(57, 'config', 'cfg_decimal_separator', '.'),
(58, 'config', 'cfg_use_select2', '1'),
(59, 'config', 'cfg_contact_business_subject', 'You received a message about your ad'),
(60, 'config', 'cfg_contact_user_subject', 'You received a message'),
(61, 'config', 'cfg_date_format', 'Y-m-d');

-- --------------------------------------------------------

--
-- Table structure for table `contact_msgs`
--

CREATE TABLE IF NOT EXISTS `contact_msgs` (
  `id` int(11) NOT NULL,
  `sender_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_ip` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_id` int(11) NOT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country_abbr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'country',
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid` int(10) NOT NULL,
  `place_id` int(10) NOT NULL,
  `expire` datetime NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE IF NOT EXISTS `custom_fields` (
  `field_id` int(11) NOT NULL,
  `field_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filter_display` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'select',
  `values_list` text COLLATE utf8mb4_unicode_ci,
  `value_unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tooltip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `searchable` tinyint(1) NOT NULL DEFAULT '1',
  `show_in_results` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no' COMMENT 'Possible values: no, name, icon, name-icon',
  `field_group` int(11) NOT NULL DEFAULT '1',
  `field_order` int(11) NOT NULL DEFAULT '0',
  `field_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_groups`
--

CREATE TABLE IF NOT EXISTS `custom_fields_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_order` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `group_status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `custom_fields_groups`
  ADD PRIMARY KEY (`group_id`);


ALTER TABLE `custom_fields_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `custom_fields_groups` (`group_id`, `group_name`, `group_order`, `group_status`) VALUES
(1, 'Features', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `translation_cf_groups`
--

CREATE TABLE `translation_cf_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  `group_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `translation_cf_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`),
  ADD KEY `field_id` (`group_id`);


ALTER TABLE `translation_cf_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `translation_cf_groups`
  ADD CONSTRAINT `translation_cf_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `custom_fields_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `translation_cf_groups` (`lang`, `group_id`, `group_name`) VALUES
('en', 1, 'Features');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_vars` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `type`, `description`, `subject`, `body`, `available_vars`) VALUES
(1, 'reset_pass', 'Reset password email', 'Reset your password - %site_name% %site_url%', 'Hello,\r\n\r\nSomeone has requested a link to change your password on Business Directory. You can do this through the link below. \r\n\r\n%reset_link%\r\n\r\nIf you didn''t request this, please ignore this email. \r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\r\n%site_url%\r\n%reset_link%'),
(2, 'signup_confirm', 'Signup email address confirmation email', 'Welcome to %site_name% ! Please confirm your email - %site_url%', 'Hello,\r\n\r\nYou have signed up for Business Directory.\r\n\r\nIf you received this email by mistake, simply delete it. Your account will be removed if you don''t click the confirmation link below.\r\n\r\nConfirm: %confirm_link%\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\r\n%site_url%\r\n%confirm_link%'),
(4, 'subscr_failed', 'Subscription payment failed email', 'Subscription payment failed - Business Directory', 'Hello,\r\n\r\nYour subscription payment failed. Please take moment to check your payment info, you may need to update the credit card expiration date, etc. You still have access, we''ll try again in a few days.\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\n%site_url%'),
(5, 'subscr_signup', 'Subscription successful email', 'Thank you! Welcome to Business Directory', 'Hello,\r\n\r\nYour subscription is active. The link to your listing is:\r\n\r\n%listing_link%\r\n\r\nYou can edit your listing at any moment by logging into your account.\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\r\n%site_url%\r\n%listing_link%'),
(6, 'web_accept', 'One time payment successful email', 'Thank you! Welcome to Business Directory', 'Hello,\r\n\r\nYour listing is active. The link to your listing is:\r\n\r\n%listing_link%\r\n\r\nYou can edit your listing at any moment by logging into your account.\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\r\n%site_url%\r\n%listing_link%'),
(7, 'subscr_eot', 'Subscription expired email', 'Subscription expired - %site_name%', 'Hello, \r\n\r\nYour subscription on %site_name% expired. The link to your listing is:\r\n\r\n%listing_link%\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\r\n%site_url%\r\n%listing_link%'),
(8, 'web_accept_fail', 'One time payment failed email', 'Your most recent payment failed', 'Hi there,\r\n\r\nUnfortunately your most recent payment for your ad on our site was declined. This could be due to a change in your card number or your card expiring, cancelation of your credit card, or the bank not recognizing the payment and taking action to prevent it.\r\n\r\nPlease update your payment information as soon as possible by signing in here:\r\n%site_url%\r\n\r\nThanks,\r\n\r\nBusiness Directory - http://yoursite.com', '%site_name%\n%site_url%'),
(9, 'process_add_listing', 'User submission notification', 'A new listing was submitted', 'A user created a new listing at:\r\n\r\n%new_listing_url%\r\n', '%new_listing_url%'),
(10, 'process_edit_listing', 'User edit listing notification', 'A new listing was edited', 'A user has edited a listing at:\r\n\r\n%edited_listing_url%\r\n', '%edited_listing_url%'),
(11, 'charge_failed', 'Email sent when a payment failed', 'Charge failed', 'Hello,\r\n\r\nYour recent payment did not complete and credits were not added to the database. Please contact the site administrator.\r\n\r\nThanks,\r\n\r\n%site_name% - %site_url%', '%site_name%\n%site_url%'),
(12, 'user_signup', 'Email sent to the admin when a new user registers on the site', 'New User Signup', 'New signup from user:\r\n%signup_email%', '%signup_email%');

-- --------------------------------------------------------

--
-- Table structure for table `loggedin`
--

CREATE TABLE IF NOT EXISTS `loggedin` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `neighborhoods`
--

CREATE TABLE IF NOT EXISTS `neighborhoods` (
  `neighborhood_id` int(10) NOT NULL,
  `neighborhood_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `neighborhood_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL,
  `page_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `page_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `page_contents` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_comments` tinyint(1) NOT NULL DEFAULT '1',
  `page_order` int(10) NOT NULL DEFAULT '0',
  `page_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `page_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-don''t show in feed; 1-show in feed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_title`, `page_slug`, `meta_desc`, `page_contents`, `page_group`, `enable_comments`, `page_order`, `page_date`, `page_status`) VALUES
(2, 'Privacy Policy', 'privacy-policy', 'Privacy policy', '<h2>Privacy Policy</h2>\r\n<p>At "Your Site" we are committed to safeguarding and preserving the privacy of our visitors. This Privacy Policy document (the "Policy") has been provided by the legal resource DIY Legals and reviewed and approved by their solicitors.</p>\r\n<p>This Policy explains what happens to any personal data that you provide to us, or that we collect from you whilst you visit our site and how we use cookies on this website.</p>\r\n<p>We do update this Policy from time to time so please do review this Policy regularly.</p>\r\n<h3>Information That We Collect</h3>\r\n<p>In running and maintaining our website we may collect and process the following data about you:</p>\r\n<ul>\r\n<li>Information about your use of our site including details of your visits such as pages viewed and the resources that you access. Such information includes traffic data, location data and other communication data.</li>\r\n<li>Information provided voluntarily by you. For example, when you register for information or make a purchase.</li>\r\n<li>Information that you provide when you communicate with us by any means.</li>\r\n</ul>\r\n<h3>Use of Cookies</h3>\r\n<p>Cookies provide information regarding the computer used by a visitor. We may use cookies where appropriate to gather information about your computer in order to assist us in improving our website.</p>\r\n<p>We may gather information about your general internet use by using the cookie. Where used, these cookies are downloaded to your computer and stored on the computer&rsquo;s hard drive. Such information will not identify you personally; it is statistical data which does not identify any personal details whatsoever.</p>\r\n<p>Our advertisers may also use cookies, over which we have no control. Such cookies (if used) would be downloaded once you click on advertisements on our website.</p>\r\n<p>You can adjust the settings on your computer to decline any cookies if you wish. This can be done within the &ldquo;settings&rdquo; section of your computer. For more information please read the advice at AboutCookies.org.</p>\r\n<h3>Use of Your Information</h3>\r\n<p>We use the information that we collect from you to provide our services to you. In addition to this we may use the information for one or more of the following purposes:</p>\r\n<ul>\r\n<li>To provide information to you that you request from us relating to our products or services.</li>\r\n<li>To provide information to you relating to other products that may be of interest to you. Such additional information will only be provided where you have consented to receive such information.</li>\r\n<li>To inform you of any changes to our website, services or goods and products.</li>\r\n</ul>\r\n<p>If you have previously purchased goods or services from us we may provide to you details of similar goods or services, or other goods and services, that you may be interested in.</p>\r\n<p><strong>We never give your details to third parties to use your data to enable them to provide you with information regarding unrelated goods or services.</strong></p>\r\n<h3>Storing Your Personal Data</h3>\r\n<p>In operating our website it may become necessary to transfer data that we collect from you to locations outside of the European Union for processing and storing. By providing your personal data to us, you agree to this transfer, storing and processing. We do our utmost to ensure that all reasonable steps are taken to make sure that your data is stored securely.</p>\r\n<p>Unfortunately the sending of information via the internet is not totally secure and on occasion such information can be intercepted. We cannot guarantee the security of data that you choose to send us electronically, sending such information is entirely at your own risk.</p>\r\n<h3>Disclosing Your Information</h3>\r\n<p>We will not disclose your personal information to any other party other than in accordance with this Privacy Policy and in the circumstances detailed below:</p>\r\n<ul>\r\n<li>In the event that we sell any or all of our business to the buyer.</li>\r\n<li>Where we are legally required by law to disclose your personal information.</li>\r\n<li>To further fraud protection and reduce the risk of fraud.</li>\r\n</ul>\r\n<h3>Third Party Links</h3>\r\n<p>On occasion we include links to third parties on this website. Where we provide a link it does not mean that we endorse or approve that site''s policy towards visitor privacy. You should review their privacy policy before sending them any personal data.</p>\r\n<h3>Access to Information</h3>\r\n<p>In accordance with the Data Protection Act 1998 you have the right to access any information that we hold relating to you. Please note that we reserve the right to charge a fee of &pound;10 to cover costs incurred by us in providing you with the information.</p>\r\n<h3>Contacting Us</h3>\r\n<p>Please do not hesitate to contact us regarding any matter relating to this Privacy and Cookies Policy via email at <a href="mailto:contact@yoursite.com">contact@yoursite.com</a>.</p>', 'footer_menu', 0, 0, '2018-08-21 00:00:00', 0),
(3, 'Terms of Use', 'tou', 'Terms of use for this website.', '<p class="MsoNormal" style="text-align: center;" align="center"><strong><span lang="EN-GB" style="font-size: 9.0pt;">TEMPLATE WEBSITE TERMS AND CONDITIONS</span></strong></p>\r\n<p class="MsoNormal" style="text-align: center;" align="center">&nbsp;</p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></strong><strong><span lang="EN-GB" style="font-size: 9.0pt;">Credit</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt; mso-bidi-font-size: 11.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">This document was created using a Contractology template available at <a href="http://www.contractology.com/">http://www.contractology.com</a>.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Introduction</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">These terms and conditions govern your use of this website; by using this website, you accept these terms and conditions in full.&nbsp;&nbsp; If you disagree with these terms and conditions or any part of these terms and conditions, you must not use this website. </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[You must be at least [18] years of age to use this website.&nbsp; By using this website [and by agreeing to these terms and conditions] you warrant and represent that you are at least [18] years of age.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[This website uses cookies.&nbsp; By using this website and agreeing to these terms and conditions, you consent to our [NAME]''s use of cookies in accordance with the terms of [NAME]''s [privacy policy / cookies policy].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">License to use website</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Unless otherwise stated, [NAME] and/or its licensors own the intellectual property rights in the website and material on the website.&nbsp; Subject to the license below, all these intellectual property rights are reserved.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You may view, download for caching purposes only, and print pages [or [OTHER CONTENT]] from the website for your own personal use, subject to the restrictions set out below and elsewhere in these terms and conditions.&nbsp; </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You must not:</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">republish material from this website (including republication on another website);</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">sell, rent or sub-license material from the website;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">show any material from the website in public;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">reproduce, duplicate, copy or otherwise exploit material on this website for a commercial purpose;]</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">[edit or otherwise modify any material on the website; or]</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Symbol; mso-fareast-font-family: Symbol; mso-bidi-font-family: Symbol;">&middot;<span style="font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: ''Times New Roman'';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">[redistribute material from this website [except for content specifically and expressly made available for redistribution].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[Where content is specifically made available for redistribution, it may only be redistributed [within your organisation].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Acceptable use</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You must not use this website in any way that causes, or may cause, damage to the website or impairment of the availability or accessibility of the website; or in any way which is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You must not use this website to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You must not conduct any systematic or automated data collection activities (including without limitation scraping, data mining, data extraction and data harvesting) on or in relation to this website without [NAME''S] express written consent.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[You must not use this website to transmit or send unsolicited commercial communications.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[You must not use this website for any purposes related to marketing without [NAME''S] express written consent.]&nbsp; </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">[Restricted access</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[Access to certain areas of this website is restricted.]&nbsp; [NAME] reserves the right to restrict access to [other] areas of this website, or indeed this entire website, at [NAME''S] discretion.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">If [NAME] provides you with a user ID and password to enable you to access restricted areas of this website or other content or services, you must ensure that the user ID and password are kept confidential.&nbsp; </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] may disable your user ID and password in [NAME''S] sole discretion without notice or explanation.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">[User content</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">In these terms and conditions, &ldquo;your user content&rdquo; means material (including without limitation text, images, audio material, video material and audio-visual material) that you submit to this website, for whatever purpose.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You grant to [NAME] a worldwide, irrevocable, non-exclusive, royalty-free license to use, reproduce, adapt, publish, translate and distribute your user content in any existing or future media.&nbsp; You also grant to [NAME] the right to sub-license these rights, and the right to bring an action for infringement of these rights.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Your user content must not be illegal or unlawful, must not infringe any third party''s legal rights, and must not be capable of giving rise to legal action whether against you or [NAME] or a third party (in each case under any applicable law).&nbsp; </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You must not submit any user content to the website that is or has ever been the subject of any threatened or actual legal proceedings or other similar complaint.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[NAME] reserves the right to edit or remove any material submitted to this website, or stored on [NAME''S] servers, or hosted or published upon this website.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p><span lang="EN-GB" style="font-size: 9.0pt; font-family: ''Times New Roman'',serif; mso-fareast-font-family: ''Lucida Sans Unicode''; mso-font-kerning: .5pt; mso-ansi-language: EN-GB; mso-fareast-language: #00FF; mso-bidi-language: AR-SA;">[Notwithstanding [NAME''S] rights under these terms and conditions in relation to user content, [NAME] does not&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">undertake to monitor the submission of such content to, or the publication of such content on, this website.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">No warranties</span></strong></p>\r\n<p class="MsoNormal" style="text-align: center;" align="center"><strong><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">This website is provided &ldquo;as is&rdquo; without any representations or warranties, express or implied.<span style="mso-spacerun: yes;">&nbsp; </span>[NAME] makes no representations or warranties in relation to this website or the information and materials provided on this website.<span style="mso-spacerun: yes;">&nbsp; </span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Without prejudice to the generality of the foregoing paragraph, [NAME] does not warrant that:</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l1 level1 lfo2; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">this website will be constantly available, or available at all; or</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l1 level1 lfo2; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">the information on this website is complete, true, accurate or non-misleading.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Nothing on this website constitutes, or is meant to constitute, advice of any kind.<span style="mso-spacerun: yes;">&nbsp; </span>[If you require advice in relation to any [legal, financial or medical] matter you should consult an appropriate professional.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Limitations of liability</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[NAME] will not be liable to you (whether under the law of contact, the law of torts or otherwise) in relation to the contents of, or use of, or otherwise in connection with, this website:</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l1 level1 lfo2; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">[to the extent that the website is provided free-of-charge, for any direct loss;]</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l1 level1 lfo2; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">for any indirect, special or consequential loss; or</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l1 level1 lfo2; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">for any business losses, loss of revenue, income, profits or anticipated savings, loss of contracts or business relationships, loss of reputation or goodwill, or loss or corruption of information or data.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">These limitations of liability apply even if [NAME] has been expressly advised of the potential loss.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Exceptions</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Nothing in this website disclaimer will exclude or limit any warranty implied by law that it would be unlawful to exclude or limit; and nothing in this website disclaimer will exclude or limit [NAME''S] liability in respect of any:</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">death or personal injury caused by [NAME''S] negligence;</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">fraud or fraudulent misrepresentation on the part of [NAME]; or</span></p>\r\n<p class="MsoNormal" style="margin-left: 36.0pt; text-align: justify; text-indent: -18.0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt left 576.0pt;"><!-- [if !supportLists]--><span lang="EN-GB" style="font-size: 9.0pt; font-family: Wingdings; mso-fareast-font-family: Wingdings; mso-bidi-font-family: Wingdings;"><span style="mso-list: Ignore;">l<span style="font: 7.0pt ''Times New Roman'';">&nbsp; </span></span></span><!--[endif]--><span lang="EN-GB" style="font-size: 9.0pt;">matter which it would be illegal or unlawful for [NAME] to exclude or limit, or to attempt or purport to exclude or limit, its liability. </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Reasonableness</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">By using this website, you agree that the exclusions and limitations of liability set out in this website disclaimer are reasonable.<span style="mso-spacerun: yes;">&nbsp; </span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">If you do not think they are reasonable, you must not use this website.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Other parties</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt; mso-fareast-font-family: SimSun; mso-fareast-language: AR-SA;">[You accept that, as a limited liability entity, [NAME] has an interest in limiting the personal liability of its officers and employees.<span style="mso-spacerun: yes;">&nbsp; </span>You agree that you will not bring any claim personally against [NAME''S] officers or employees in respect of any losses you suffer in connection with the website.]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[Without prejudice to the foregoing paragraph,] you agree that the limitations of warranties and liability set out in this website disclaimer will protect [NAME''S] officers, employees, agents, subsidiaries, successors, assigns and sub-contractors as well as [NAME]. </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Unenforceable provisions</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">If any provision of this website disclaimer is, or is found to be, unenforceable under applicable law, that will not affect the enforceability of the other provisions of this website disclaimer.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Indemnity</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You hereby indemnify [NAME] and undertake to keep [NAME] indemnified against any losses, damages, costs, liabilities and expenses (including without limitation legal expenses and any amounts paid by [NAME] to a third party in settlement of a claim or dispute on the advice of [NAME''S] legal advisers) incurred or suffered by [NAME] arising out of any breach by you of any provision of these terms and conditions[, or arising out of any claim that you have breached any provision of these terms and conditions].</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Breaches of these terms and conditions</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">Without prejudice to [NAME''S] other rights under these terms and conditions, if you breach these terms and conditions in any way, [NAME] may take such action as [NAME] deems appropriate to deal with the breach, including suspending your access to the website, prohibiting you from accessing the website, blocking computers using your IP address from accessing the website, contacting your internet service provider to request that they block your access to the website and/or bringing court proceedings against you.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Variation</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[NAME] may revise these terms and conditions from time-to-time.<span style="mso-spacerun: yes;">&nbsp; </span>Revised terms and conditions will apply to the use of this website from the date of the publication of the revised terms and conditions on this website.<span style="mso-spacerun: yes;">&nbsp; </span>Please check this page regularly to ensure you are familiar with the current version.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Assignment</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[NAME] may transfer, sub-contract or otherwise deal with [NAME''S] rights and/or obligations under these terms and conditions without notifying you or obtaining your consent.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You may not transfer, sub-contract or otherwise deal with your rights and/or obligations under these terms and conditions.<span style="mso-spacerun: yes;">&nbsp;</span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;">&nbsp;</p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Severability</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">If a provision of these terms and conditions is determined by any court or other competent authority to be unlawful and/or unenforceable, the other provisions will continue in effect.<span style="mso-spacerun: yes;">&nbsp; </span>If any unlawful and/or unenforceable provision would be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect. </span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Entire agreement</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">These terms and conditions [, together with [DOCUMENTS],] constitute the entire agreement between you and [NAME] in relation to your use of this website, and supersede all previous agreements in respect of your use of this website.</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">Law and jurisdiction</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">These terms and conditions will be governed by and construed in accordance with [GOVERNING LAW], and any disputes relating to these terms and conditions will be subject to the [non-]exclusive jurisdiction of the courts of [JURISDICTION].</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">[Registrations and authorisations</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] is registered with [TRADE REGISTER]. <span style="mso-spacerun: yes;">&nbsp;</span>You can find the online version of the register at [URL].<span style="mso-spacerun: yes;">&nbsp; </span>[NAME''S] registration number is [NUMBER].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] is subject to [AUTHORISATION SCHEME], which is supervised by [SUPERVISORY AUTHORITY].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] is registered with [PROFESSIONAL BODY].<span style="mso-spacerun: yes;">&nbsp; </span>[NAME''S] professional title is [TITLE] and it has been granted in the United Kingdom.<span style="mso-spacerun: yes;">&nbsp; </span>[NAME] is subject to the [RULES] which can be found at [URL].]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] subscribes to the following code[s] of conduct: [CODE(S) OF CONDUCT].<span style="mso-spacerun: yes;">&nbsp; </span>[These codes/this code] can be consulted electronically at [URL(S)].</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME''S] [TAX] number is [NUMBER].]]</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><strong><span lang="EN-GB" style="font-size: 9.0pt;">[NAME''S] details</span></strong></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">The full name of [NAME] is [FULL NAME].<span style="mso-spacerun: yes;">&nbsp; </span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[[NAME] is registered in [JURISDICTION] under registration number [NUMBER].]<span style="mso-spacerun: yes;">&nbsp; </span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">[NAME''S] [registered] address is [ADDRESS].<span style="mso-spacerun: yes;">&nbsp; </span></span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify;"><span lang="EN-GB" style="font-size: 9.0pt;">You can contact [NAME] by email to [EMAIL].</span></p>', '', 0, 0, '2018-09-13 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pass_request`
--

CREATE TABLE IF NOT EXISTS `pass_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `photo_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `dir` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE IF NOT EXISTS `places` (
  `place_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `place_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cross_street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `country_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `inside` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `area_code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `wa_country_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `wa_area_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `wa_phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `twitter` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `facebook` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `contact_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_desc` text COLLATE utf8mb4_unicode_ci,
  `business_hours` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `submission_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `origin` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USER',
  `feat` tinyint(1) NOT NULL DEFAULT '0',
  `feat_home` tinyint(1) NOT NULL DEFAULT '0',
  `plan` int(11) DEFAULT NULL,
  `valid_until` datetime NOT NULL DEFAULT '9999-01-01 00:00:00',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE IF NOT EXISTS `plans` (
  `plan_id` int(11) NOT NULL,
  `plan_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `plan_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `plan_features` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `plan_period` int(10) NOT NULL DEFAULT '0',
  `plan_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `plan_order` int(11) NOT NULL DEFAULT '0',
  `plan_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rel_cat_custom_fields`
--

CREATE TABLE IF NOT EXISTS `rel_cat_custom_fields` (
  `rel_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rel_place_cat`
--

CREATE TABLE `rel_place_cat` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT '0',
  `cat_id` int(11) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `is_main` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rel_place_custom_fields`
--

CREATE TABLE IF NOT EXISTS `rel_place_custom_fields` (
  `rel_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `field_value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `text` mediumtext COLLATE utf8mb4_unicode_ci,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `signup_confirm`
--

CREATE TABLE IF NOT EXISTS `signup_confirm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_str` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `state_id` int(11) NOT NULL,
  `state_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_abbr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country_abbr` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'country',
  `country_id` int(10) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_photos`
--

CREATE TABLE IF NOT EXISTS `tmp_photos` (
  `id` int(11) NOT NULL,
  `submit_token` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL,
  `txn_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `place_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user` int(11) NOT NULL,
  `paym_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gateway` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `amount` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `txn_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `txn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `b_year` int(4) DEFAULT NULL,
  `b_month` int(2) DEFAULT NULL,
  `b_day` int(2) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hybridauth_provider_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'Provider name',
  `hybridauth_provider_uid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'Provider user ID',
  `ip_addr` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `profile_pic_status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `city_name`, `country_name`, `gender`, `b_year`, `b_month`, `b_day`, `created`, `hybridauth_provider_name`, `hybridauth_provider_uid`, `ip_addr`, `status`, `profile_pic_status`) VALUES
(1, 'admin@example.com', '$2y$10$rYSx5KkWrXjRid./EsMgWumr2JNg7oK.ZPF5uWknJ4K56TCAUMZyq', 'Admin', '', '', '', '', 0, 0, 0, '2016-03-19 23:24:10', 'local', '', '127.0.0.1', 'approved', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `cat_status` (`cat_status`),
  ADD KEY `cat_slug` (`cat_slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `cities_feat`
--
ALTER TABLE `cities_feat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `city_id` (`city_id`) USING BTREE;

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_msgs`
--
ALTER TABLE `contact_msgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_ip` (`sender_ip`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loggedin`
--
ALTER TABLE `loggedin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `neighborhoods`
--
ALTER TABLE `neighborhoods`
  ADD PRIMARY KEY (`neighborhood_id`),
  ADD KEY `neighborhood_slug` (`neighborhood_slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`),
  ADD KEY `page_date` (`page_date`),
  ADD KEY `page_slug` (`page_slug`),
  ADD FULLTEXT KEY `page_search` (`page_title`,`meta_desc`,`page_contents`);

--
-- Indexes for table `pass_request`
--
ALTER TABLE `pass_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`place_id`),
  ADD KEY `area_code` (`area_code`),
  ADD KEY `userid` (`userid`),
  ADD KEY `status` (`status`),
  ADD KEY `neighborhood` (`neighborhood`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `slug` (`slug`),
  ADD FULLTEXT KEY `description` (`description`);

ALTER TABLE `places`
  ADD FULLTEXT KEY `place_name_descrip` (`place_name`,`description`,`short_desc`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `rel_cat_custom_fields`
--
ALTER TABLE `rel_cat_custom_fields`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `rel_place_cat`
--
ALTER TABLE `rel_place_cat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `place_x_cat` (`place_id`,`cat_id`) USING BTREE,
  ADD KEY `place_id` (`place_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `rel_place_custom_fields`
--
ALTER TABLE `rel_place_custom_fields`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `option_id` (`field_id`),
  ADD KEY `place_id` (`place_id`),
  ADD FULLTEXT KEY `field_value` (`field_value`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `signup_confirm`
--
ALTER TABLE `signup_confirm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`state_id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `tmp_photos`
--
ALTER TABLE `tmp_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filename` (`filename`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities_feat`
--
ALTER TABLE `cities_feat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_msgs`
--
ALTER TABLE `contact_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loggedin`
--
ALTER TABLE `loggedin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `neighborhoods`
--
ALTER TABLE `neighborhoods`
  MODIFY `neighborhood_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pass_request`
--
ALTER TABLE `pass_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rel_cat_custom_fields`
--
ALTER TABLE `rel_cat_custom_fields`
  MODIFY `rel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rel_place_cat`
--
ALTER TABLE `rel_place_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rel_place_custom_fields`
--
ALTER TABLE `rel_place_custom_fields`
  MODIFY `rel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signup_confirm`
--
ALTER TABLE `signup_confirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_photos`
--
ALTER TABLE `tmp_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities_feat`
--
ALTER TABLE `cities_feat`
  ADD CONSTRAINT `cities_feat_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loggedin`
--
ALTER TABLE `loggedin`
  ADD CONSTRAINT `loggedin_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pass_request`
--
ALTER TABLE `pass_request`
  ADD CONSTRAINT `pass_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rel_cat_custom_fields`
--
ALTER TABLE `rel_cat_custom_fields`
  ADD CONSTRAINT `rel_cat_custom_fields_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_cat_custom_fields_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rel_place_cat`
--
ALTER TABLE `rel_place_cat`
  ADD CONSTRAINT `rel_place_cat_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_place_cat_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rel_place_custom_fields`
--
ALTER TABLE `rel_place_custom_fields`
  ADD CONSTRAINT `rel_place_custom_fields_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_place_custom_fields_ibfk_3` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `signup_confirm`
--
ALTER TABLE `signup_confirm`
  ADD CONSTRAINT `signup_confirm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Update from v100 to v200
--
ALTER TABLE `cats` ADD `cat_bg` VARCHAR(10) NOT NULL DEFAULT '' AFTER `cat_icon`;

--
-- Update from v2.00 to v3.12
--

--
-- Table structure for table `rel_favorites`
--

CREATE TABLE IF NOT EXISTS `rel_favorites` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT '0',
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rel_favorites`
--

ALTER TABLE `rel_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rel_favorites`
--
ALTER TABLE `rel_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `rel_favorites`
--
ALTER TABLE `rel_favorites`
  ADD CONSTRAINT `rel_favorites_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_favorites_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `contact_msgs` ADD `recipient_id` INT(11) NULL AFTER `place_id`;
ALTER TABLE `contact_msgs` CHANGE `place_id` `place_id` INT(11) NULL;

--
-- Table structure for table `plan_types`
--

CREATE TABLE IF NOT EXISTS `plan_types` (
  `id` int(11) NOT NULL,
  `plan_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `plan_priority` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_types`
--

INSERT INTO `plan_types` (`id`, `plan_type`, `plan_priority`) VALUES
(1, 'free', 1),
(2, 'free_feat', 1),
(3, 'one_time', 10),
(4, 'one_time_feat', 20),
(5, 'monthly', 10),
(6, 'monthly_feat', 20),
(7, 'annual', 10),
(8, 'annual_feat', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `plan_types`
--
ALTER TABLE `plan_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plan_types`
--
ALTER TABLE `plan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `video_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT '0',
  `video_url` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `place_id` (`place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL,
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `section` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `template` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translated` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`),
  ADD KEY `template` (`template`),
  ADD KEY `section` (`section`);


ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
('en', 'public', 'global', 'txt_admin', 'Admin Area'),
('en', 'public', 'global', 'txt_address', 'Address'),
('en', 'public', 'global', 'txt_approve', 'Approve'),
('en', 'public', 'global', 'txt_approved', 'Approved'),
('en', 'public', 'global', 'txt_bg_color', 'Background color'),
('en', 'public', 'global', 'txt_blog', 'Blog'),
('en', 'public', 'global', 'txt_cancel', 'Cancel'),
('en', 'public', 'global', 'txt_category', 'Category'),
('en', 'public', 'global', 'txt_categories', 'Categories'),
('en', 'public', 'global', 'txt_change', 'Change'),
('en', 'public', 'global', 'txt_change_pass', 'Change Password'),
('en', 'public', 'global', 'txt_cities', 'Cities'),
('en', 'public', 'global', 'txt_city', 'City'),
('en', 'public', 'global', 'txt_clear', 'Clear'),
('en', 'public', 'global', 'txt_close', 'Close'),
('en', 'public', 'global', 'txt_confirm', 'Confirm'),
('en', 'public', 'global', 'txt_contact', 'Contact'),
('en', 'public', 'global', 'txt_contact_user', 'Contact User'),
('en', 'public', 'global', 'txt_countries', 'Countries'),
('en', 'public', 'global', 'txt_country', 'Country'),
('en', 'public', 'global', 'txt_coupon', 'Coupon'),
('en', 'public', 'global', 'txt_coupons', 'Coupons'),
('en', 'public', 'global', 'txt_create_listing', 'Create Listing'),
('en', 'public', 'global', 'txt_dashboard', 'Dashboard'),
('en', 'public', 'global', 'txt_date', 'Date'),
('en', 'public', 'global', 'txt_delete', 'Delete'),
('en', 'public', 'global', 'txt_disabled', 'Disabled'),
('en', 'public', 'global', 'txt_email', 'Email'),
('en', 'public', 'global', 'txt_enabled', 'Enabled'),
('en', 'public', 'global', 'txt_event', 'Event'),
('en', 'public', 'global', 'txt_events', 'Events'),
('en', 'public', 'global', 'txt_execute', 'Execute'),
('en', 'public', 'global', 'txt_explore', 'Explore'),
('en', 'public', 'global', 'txt_favorites', 'Favorites'),
('en', 'public', 'global', 'txt_featured', 'Featured'),
('en', 'public', 'global', 'txt_get_listed', 'Get Listed Today'),
('en', 'public', 'global', 'txt_help', 'Help'),
('en', 'public', 'global', 'txt_home', 'Home'),
('en', 'public', 'global', 'txt_id', 'Id'),
('en', 'public', 'global', 'txt_invalid_email', 'Invalid email'),
('en', 'public', 'global', 'txt_keyword', 'Ex: pizza, hotel, car sale'),
('en', 'public', 'global', 'txt_listing', 'Listing'),
('en', 'public', 'global', 'txt_listings', 'Listings'),
('en', 'public', 'global', 'txt_loading', 'Loading'),
('en', 'public', 'global', 'txt_location', 'Location'),
('en', 'public', 'global', 'txt_manager', 'Manager'),
('en', 'public', 'global', 'txt_message', 'Message'),
('en', 'public', 'global', 'txt_message_sent', 'Message sent'),
('en', 'public', 'global', 'txt_meta_desc', ''),
('en', 'public', 'global', 'txt_month', 'Month'),
('en', 'public', 'global', 'txt_name', 'Name'),
('en', 'public', 'global', 'txt_no', 'No'),
('en', 'public', 'global', 'txt_no_results', 'No results'),
('en', 'public', 'global', 'txt_ok', 'OK'),
('en', 'public', 'global', 'txt_optional', 'Optional'),
('en', 'public', 'global', 'txt_page', 'Page'),
('en', 'public', 'global', 'txt_pager_page1', 'Page 1'),
('en', 'public', 'global', 'txt_pager_last_page', 'Last Page'),
('en', 'public', 'global', 'txt_pages', 'Pages'),
('en', 'public', 'global', 'txt_password', 'Password'),
('en', 'public', 'global', 'txt_pending', 'Pending'),
('en', 'public', 'global', 'txt_phone', 'Phone'),
('en', 'public', 'global', 'txt_photo', 'Photo'),
('en', 'public', 'global', 'txt_photos', 'Photos'),
('en', 'public', 'global', 'txt_picture', 'Picture'),
('en', 'public', 'global', 'txt_pictures', 'Pictures'),
('en', 'public', 'global', 'txt_please_wait', 'Please wait a few seconds before submitting again'),
('en', 'public', 'global', 'txt_postal_code', 'Postal Code'),
('en', 'public', 'global', 'txt_print', 'Print'),
('en', 'public', 'global', 'txt_privacy_agree', 'I agree to the terms of use and privacy policy'),
('en', 'public', 'global', 'txt_profile', 'Profile'),
('en', 'public', 'global', 'txt_rate_bad', 'bad'),
('en', 'public', 'global', 'txt_rate_good', 'good'),
('en', 'public', 'global', 'txt_rate_gorgeous', 'gorgeous'),
('en', 'public', 'global', 'txt_rate_poor', 'poor'),
('en', 'public', 'global', 'txt_rate_regular', 'regular'),
('en', 'public', 'global', 'txt_register', 'Register'),
('en', 'public', 'global', 'txt_remaining', 'Remaining'),
('en', 'public', 'global', 'txt_remove', 'Remove'),
('en', 'public', 'global', 'txt_required', 'Required'),
('en', 'public', 'global', 'txt_reviews', 'Reviews'),
('en', 'public', 'global', 'txt_save', 'Save changes'),
('en', 'public', 'global', 'txt_search', 'Search'),
('en', 'public', 'global', 'txt_selectyourcity', 'Select city'),
('en', 'public', 'global', 'txt_send_message', 'Send Message'),
('en', 'public', 'global', 'txt_short_desc', 'Short Description'),
('en', 'public', 'global', 'txt_signin', 'Sign in'),
('en', 'public', 'global', 'txt_signout', 'Sign out'),
('en', 'public', 'global', 'txt_state', 'State'),
('en', 'public', 'global', 'txt_states', 'States'),
('en', 'public', 'global', 'txt_subject', 'Subject'),
('en', 'public', 'global', 'txt_submit', 'Submit'),
('en', 'public', 'global', 'txt_sort', 'Sort'),
('en', 'public', 'global', 'txt_tags', 'Tags'),
('en', 'public', 'global', 'txt_thanks', 'Thanks'),
('en', 'public', 'global', 'txt_total_rows', 'Total rows'),
('en', 'public', 'global', 'txt_try_again', 'Try again'),
('en', 'public', 'global', 'txt_upload', 'Upload'),
('en', 'public', 'global', 'txt_upload_limit', 'Upload Limit Reached'),
('en', 'public', 'global', 'txt_use_cur_loc', 'Use current location'),
('en', 'public', 'global', 'txt_user', 'User'),
('en', 'public', 'global', 'txt_video', 'Video'),
('en', 'public', 'global', 'txt_videos', 'Videos'),
('en', 'public', 'global', 'txt_users', 'Users'),
('en', 'public', 'global', 'txt_view_all', 'View All'),
('en', 'public', 'global', 'txt_wait', 'Wait'),
('en', 'public', 'global', 'txt_year', 'Year'),
('en', 'public', 'global', 'txt_yes', 'Yes'),
('en', 'public', 'home', 'txt_html_title', 'Business Directory'),
('en', 'public', 'home', 'txt_meta_desc', 'Business Directory is a nationwide business directory where users can search for the businesses near them and write reviews.'),
('en', 'public', 'home', 'txt_featured_cities', 'Featured Cities'),
('en', 'public', 'home', 'txt_featured_listings', 'Featured Listings'),
('en', 'public', 'home', 'txt_latest_listings', 'Latest Listings'),
('en', 'public', 'home', 'txt_near_listings', 'Places Near You'),
('en', 'public', 'home', 'txt_view_cat', 'View Category'),
('en', 'public', 'home', 'txt_view_listing', 'View Listing'),
('en', 'public', 'home', 'txt_view_listings', 'View Listings'),
('en', 'public', 'home', 'txt_phrase_01', 'Explore places in this city'),
('en', 'public', 'home', 'txt_phrase_02', 'Find the best hotels, shops and restaurants in your local area.'),
('en', 'public', 'home', 'txt_phrase_03', 'Most Popular Categories'),
('en', 'public', 'home', 'txt_phrase_04', 'Featured Listings'),
('en', 'public', 'home', 'txt_phrase_05', 'Explore The Latest Listings'),
('en', 'public', 'home', 'txt_phrase_06', 'Most Popular Cities'),
('en', 'public', 'home', 'txt_phrase_07', 'Recommended Places Near You'),
('en', 'public', 'home', 'txt_phrase_08', ''),
('en', 'public', 'home', 'txt_phrase_09', ''),
('en', 'public', 'home', 'txt_phrase_10', ''),
('en', 'public', 'categories', 'txt_html_title_1', 'All Categories in %city_name%, %state_abbr%'),
('en', 'public', 'categories', 'txt_meta_desc_1', 'Best businesses in %city_name%, %state_abbr%'),
('en', 'public', 'categories', 'txt_main_title_1', 'All Categories in %city_name%, %state_abbr%'),
('en', 'public', 'categories', 'txt_suggest_city', 'Show categories in %city_name%'),
('en', 'public', 'categories', 'txt_all_cats_city', 'All Categories in %city_name%'),
('en', 'public', 'categories', 'txt_all_locs', 'All locations'),
('en', 'public', 'categories', 'txt_html_title_2', 'All Categories'),
('en', 'public', 'categories', 'txt_meta_desc_2', 'Best businesses'),
('en', 'public', 'categories', 'txt_main_title_2', 'All Categories'),
('en', 'public', 'categories', 'txt_all_cats', 'All Categories'),
('en', 'public', 'claim', 'txt_html_title', 'Claim Listing'),
('en', 'public', 'claim', 'txt_main_title', 'Claim Listing'),
('en', 'public', 'claim', 'txt_buy_now', 'Select this plan'),
('en', 'public', 'claim', 'txt_claimed', 'This place has already been claimed'),
('en', 'public', 'claim', 'txt_month', 'month'),
('en', 'public', 'claim', 'txt_no_plans', 'There are no plans defined.'),
('en', 'public', 'claim', 'txt_sign_in', 'To claim this listing you need to sign in.'),
('en', 'public', 'claim', 'txt_year', 'year'),
('en', 'public', 'claim', 'txt_select_plan', 'Select Plan'),
('en', 'public', 'contact', 'txt_html_title', 'Contact'),
('en', 'public', 'contact', 'txt_main_title', 'Contact'),
('en', 'public', 'contact', 'txt_message', 'Message'),
('en', 'public', 'contact', 'txt_send_message', 'Send Message'),
('en', 'public', 'contact', 'txt_message_sent', 'Message sent.'),
('en', 'public', 'coupon', 'txt_html_title', 'Coupons'),
('en', 'public', 'coupon', 'txt_meta_desc', 'Coupons'),
('en', 'public', 'coupon', 'txt_view_details', 'View Details'),
('en', 'public', 'coupon', 'txt_tweet', 'Tweet'),
('en', 'public', 'coupon', 'txt_share', 'Share'),
('en', 'public', 'coupon', 'txt_mail', 'Mail'),
('en', 'public', 'coupon', 'txt_created_by', 'Created by'),
('en', 'public', 'coupon', 'txt_expires', 'Expires'),
('en', 'public', 'coupon', 'txt_expired', 'Expired'),
('en', 'public', 'coupon', 'txt_about_coupon', 'About this coupon'),
('en', 'public', 'coupons', 'txt_html_title', 'Coupons'),
('en', 'public', 'coupons', 'txt_meta_desc', 'Coupons'),
('en', 'public', 'coupons', 'txt_view_details', 'View Details'),
('en', 'public', 'coupons', 'txt_tweet', 'Tweet'),
('en', 'public', 'coupons', 'txt_share', 'Share'),
('en', 'public', 'coupons', 'txt_mail', 'Mail'),
('en', 'public', 'coupons', 'txt_created_by', 'Created by'),
('en', 'public', 'coupons', 'txt_expires', 'Expires'),
('en', 'public', 'coupons', 'txt_expired', 'Expired'),
('en', 'public', 'coupons', 'txt_about_coupon', 'About this coupon'),
('en', 'public', 'favorites', 'txt_html_title', '%profile_display_name%''s Favorites'),
('en', 'public', 'favorites', 'txt_meta_desc', '%profile_display_name%''s Favorites'),
('en', 'public', 'favorites', 'txt_joined_on', 'Joined on %join_date%'),
('en', 'public', 'listing', 'txt_add_to_favorites', 'Add to Favorites'),
('en', 'public', 'listing', 'txt_additional_info', 'Additional Info'),
('en', 'public', 'listing', 'txt_claim', 'Claim Listing'),
('en', 'public', 'listing', 'txt_click_to_chat', 'Click to chat using whatsapp'),
('en', 'public', 'listing', 'txt_contact_business', 'Send Message'),
('en', 'public', 'listing', 'txt_description', 'Description'),
('en', 'public', 'listing', 'txt_features', 'Features'),
('en', 'public', 'listing', 'txt_hours', 'Hours'),
('en', 'public', 'listing', 'txt_location', 'Location'),
('en', 'public', 'listing', 'txt_more_details', 'More Details'),
('en', 'public', 'listing', 'txt_other_places', 'Other places'),
('en', 'public', 'listing', 'txt_overview', 'Overview'),
('en', 'public', 'listing', 'txt_please_rate', 'Please rate this business'),
('en', 'public', 'listing', 'txt_related', 'See Also'),
('en', 'public', 'listing', 'txt_review_login_req', 'To write a review, you must login first.'),
('en', 'public', 'listing', 'txt_review_txtarea_label', 'Review'),
('en', 'public', 'global', 'txt_send_email', 'Send an email'),
('en', 'public', 'listing', 'txt_share', 'Share This Place'),
('en', 'public', 'listing', 'txt_similar_listings', 'Similar Items'),
('en', 'public', 'listing', 'txt_social', 'Social Media'),
('en', 'public', 'listing', 'txt_short_desc', 'Short Description'),
('en', 'public', 'listing', 'txt_view_details', 'View Details'),
('en', 'public', 'listing', 'txt_website', 'Website'),
('en', 'public', 'listing', 'txt_write_review', 'Write a review'),
('en', 'public', 'listing', 'txt_your_email', 'Your email'),
('en', 'public', 'listings', 'txt_html_title', 'Best %plural_name% in %location%'),
('en', 'public', 'listings', 'txt_meta_desc', 'Best %plural_name% in %location%. %places_names% and others.'),
('en', 'public', 'listings', 'txt_businesses', 'Businesses'),
('en', 'public', 'listings', 'txt_no_results', 'There are no listings to display.'),
('en', 'public', 'listings', 'txt_pager_lastpage', 'Last Page'),
('en', 'public', 'listings', 'txt_pager_page1', 'Page 1'),
('en', 'public', 'listings', 'txt_recommended', 'Recommended'),
('en', 'public', 'listings', 'txt_results', 'Result(s)'),
('en', 'public', 'msg', 'txt_main_title_stripe', 'Thank you for your payment'),
('en', 'public', 'msg', 'txt_msg_stripe', 'Payment successful. Thank you for your business!'),
('en', 'public', 'post', 'txt_view_details', 'View Details'),
('en', 'public', 'post', 'txt_tweet', 'Tweet'),
('en', 'public', 'post', 'txt_share', 'Share'),
('en', 'public', 'post', 'txt_mail', 'Mail'),
('en', 'public', 'post', 'txt_created_by', 'Created by'),
('en', 'public', 'post', 'txt_expires', 'Expires'),
('en', 'public', 'post', 'txt_expired', 'Expired'),
('en', 'public', 'post', 'txt_about_coupon', 'About this coupon'),
('en', 'public', 'post', 'txt_search_pages', 'Search blog'),
('en', 'public', 'posts', 'txt_html_title', 'Website Blog'),
('en', 'public', 'posts', 'txt_meta_desc', 'Website Blog'),
('en', 'public', 'posts', 'txt_view_details', 'View Details'),
('en', 'public', 'posts', 'txt_tweet', 'Tweet'),
('en', 'public', 'posts', 'txt_share', 'Share'),
('en', 'public', 'posts', 'txt_mail', 'Mail'),
('en', 'public', 'posts', 'txt_search_pages', 'Search blog'),
('en', 'public', 'posts', 'txt_posted_on', 'Posted on %date%'),
('en', 'public', 'profile', 'txt_html_title', '%profile_display_name% Profile'),
('en', 'public', 'profile', 'txt_meta_desc', '%profile_display_name% Profile'),
('en', 'public', 'profile', 'txt_joined_on', 'Joined on %join_date%'),
('en', 'public', 'profile', 'txt_recent_activity', 'Recent Activity'),
('en', 'public', 'profile', 'txt_no_activity', 'No recent activity'),
('en', 'public', 'reviews', 'txt_html_title', '%profile_display_name%''s Reviews'),
('en', 'public', 'reviews', 'txt_meta_desc', '%profile_display_name%''s Reviews'),
('en', 'public', 'reviews', 'txt_joined_on', 'Joined on %join_date%'),
('en', 'user', 'create-listing', 'txt_html_title', 'Create Listing'),
('en', 'user', 'create-listing', 'txt_main_title', 'Create Listing'),
('en', 'user', 'create-listing', 'txt_meta_desc', ''),
('en', 'user', 'create-listing', 'txt_area_code', 'Area Code'),
('en', 'user', 'create-listing', 'txt_business_name', 'Business Name'),
('en', 'user', 'create-listing', 'txt_click_map', 'Please click on the map to create a marker representing the business location.'),
('en', 'user', 'create-listing', 'txt_cross_street', 'Cross street'),
('en', 'user', 'create-listing', 'txt_custom_fields', 'Custom fields'),
('en', 'user', 'create-listing', 'txt_description', 'Description'),
('en', 'user', 'create-listing', 'txt_error_file_size', 'The uploaded file exceeds allowed size'),
('en', 'user', 'create-listing', 'txt_error_upload', 'Problem uploading file. Please try again or try another file.'),
('en', 'user', 'create-listing', 'txt_hours', 'Hours'),
('en', 'user', 'create-listing', 'txt_logo', 'Logo'),
('en', 'user', 'create-listing', 'txt_neighborhood', 'Neighborhood'),
('en', 'user', 'create-listing', 'txt_other_info', 'Other Information'),
('en', 'user', 'create-listing', 'txt_postal_code', 'Postal Code'),
('en', 'user', 'create-listing', 'txt_select_cat', 'Select category'),
('en', 'user', 'create-listing', 'txt_select_city', 'Select city'),
('en', 'user', 'create-listing', 'txt_submit_listing', 'Submit Listing'),
('en', 'user', 'create-listing', 'txt_upload', 'Upload photos'),
('en', 'user', 'create-listing', 'txt_upload_btn', 'Browse'),
('en', 'user', 'create-listing', 'txt_upload_logo', 'Upload logo'),
('en', 'user', 'create-listing', 'txt_validate_addr', 'Please enter the address'),
('en', 'user', 'create-listing', 'txt_validate_cat', 'Please select a category'),
('en', 'user', 'create-listing', 'txt_validate_city', 'Please select a city'),
('en', 'user', 'create-listing', 'txt_validate_map', 'Please place a pin on the map'),
('en', 'user', 'create-listing', 'txt_validate_name', 'Please enter the business name'),
('en', 'user', 'create-listing', 'txt_website', 'Website'),
('en', 'user', 'edit-listing', 'txt_html_title', 'Edit Business Information'),
('en', 'user', 'edit-listing', 'txt_main_title', 'Edit Business Information'),
('en', 'user', 'edit-listing', 'txt_sub_header', 'Editing information for: %place_name%'),
('en', 'user', 'edit-listing', 'txt_area_code', 'Area Code'),
('en', 'user', 'edit-listing', 'txt_business_info', 'Business Information'),
('en', 'user', 'edit-listing', 'txt_business_name', 'Business Name'),
('en', 'user', 'edit-listing', 'txt_click_map', 'Please click on the map to create a marker representing the business location.'),
('en', 'user', 'edit-listing', 'txt_cross_street', 'Cross street'),
('en', 'user', 'edit-listing', 'txt_custom_fields', 'Custom fields'),
('en', 'user', 'edit-listing', 'txt_description', 'Description'),
('en', 'user', 'edit-listing', 'txt_error_file_size', 'The uploaded file exceeds allowed size'),
('en', 'user', 'edit-listing', 'txt_error_upload', 'Problem uploading file. Please try again or try another file.'),
('en', 'user', 'edit-listing', 'txt_hours', 'Hours'),
('en', 'user', 'edit-listing', 'txt_neighborhood', 'Neighborhood'),
('en', 'user', 'edit-listing', 'txt_logo', 'Logo'),
('en', 'user', 'edit-listing', 'txt_other_info', 'Other Information'),
('en', 'user', 'edit-listing', 'txt_postal_code', 'Postal Code'),
('en', 'user', 'edit-listing', 'txt_select_cat', 'Select category'),
('en', 'user', 'edit-listing', 'txt_select_city', 'Select city'),
('en', 'user', 'edit-listing', 'txt_submit_listing', 'Submit Listing'),
('en', 'user', 'edit-listing', 'txt_upload', 'Upload photos'),
('en', 'user', 'edit-listing', 'txt_upload_btn', 'Browse'),
('en', 'user', 'edit-listing', 'txt_upload_logo', 'Upload logo'),
('en', 'user', 'edit-listing', 'txt_validate_addr', 'Please enter the address'),
('en', 'user', 'edit-listing', 'txt_validate_cat', 'Please select a category'),
('en', 'user', 'edit-listing', 'txt_validate_city', 'Please select a city'),
('en', 'user', 'edit-listing', 'txt_validate_map', 'Please place a pin on the map'),
('en', 'user', 'edit-listing', 'txt_validate_name', 'Please enter the business name'),
('en', 'user', 'edit-listing', 'txt_website', 'Website'),
('en', 'user', 'edit-pass', 'txt_html_title', 'Edit Pass'),
('en', 'user', 'edit-pass', 'txt_main_title', 'Change Password'),
('en', 'user', 'edit-pass', 'txt_label_cur_pass', 'Current Password'),
('en', 'user', 'edit-pass', 'txt_label_new_pass', 'New Password'),
('en', 'user', 'edit-pass', 'txt_btn_submit', 'Save Changes'),
('en', 'user', 'edit-pass', 'txt_social_user', '<p>You''re logged in using Facebook or Twitter, so you don''t have to manage a password for this site.</p>'),
('en', 'user', 'forgot-password', 'txt_html_title', 'Forgot your password'),
('en', 'user', 'forgot-password', 'txt_main_title', 'Forgot your password?'),
('en', 'user', 'forgot-password', 'txt_forgot_pass', 'Forgot password'),
('en', 'user', 'forgot-password', 'txt_enter_email', 'Enter your email address to reset your password'),
('en', 'user', 'forgot-password', 'txt_or_login', 'Or'),
('en', 'user', 'forgot-password', 'txt_request_sent', 'You will receive a password recovery link at your email address in a few seconds.'),
('en', 'user', 'forgot-password', 'txt_mailer_problem', 'Error: could not deliver email to the specified address. Please try again.'),
('en', 'user', 'forgot-password', 'txt_invalid_email', 'Invalid email address. Please try again.'),
('en', 'user', 'forgot-password', 'txt_try_again', 'Try again'),
('en', 'user', 'my-coupons', 'txt_html_title', 'My Coupons'),
('en', 'user', 'my-coupons', 'txt_main_title', 'My Coupons'),
('en', 'user', 'my-coupons', 'txt_no_coupons', 'You haven''t created any coupons yet.'),
('en', 'user', 'my-coupons', 'txt_no_listings', 'To create coupons, please create a listing first.'),
('en', 'user', 'my-coupons', 'txt_title', 'Title'),
('en', 'user', 'my-coupons', 'txt_description', 'Coupon description'),
('en', 'user', 'my-coupons', 'txt_expire', 'Valid until'),
('en', 'user', 'my-coupons', 'txt_expired', 'Expired'),
('en', 'user', 'my-coupons', 'txt_apply_to', 'Apply to'),
('en', 'user', 'my-coupons', 'txt_img', 'Coupon image'),
('en', 'user', 'my-coupons', 'txt_create', 'Create coupon'),
('en', 'user', 'my-coupons', 'txt_remove_warn', 'Are you sure you want to delete this coupon?'),
('en', 'user', 'my-favorites', 'txt_html_title', 'My Favorites'),
('en', 'user', 'my-favorites', 'txt_main_title', 'My Favorites'),
('en', 'user', 'my-listings', 'txt_html_title', 'My Listings'),
('en', 'user', 'my-listings', 'txt_main_title', 'My Listings'),
('en', 'user', 'my-listings', 'txt_remove_place', 'Remove Listing'),
('en', 'user', 'my-listings', 'txt_edit_place', 'Edit Listing'),
('en', 'user', 'my-listings', 'txt_status_approved', 'Approved'),
('en', 'user', 'my-listings', 'txt_status_pending', 'Pending'),
('en', 'user', 'my-listings', 'txt_no_activity', 'You haven''t submitted a listing yet.'),
('en', 'user', 'my-listings', 'txt_remove_confirm', 'Are you sure you want to remove this listing?'),
('en', 'user', 'my-profile', 'txt_html_title', 'My Profile'),
('en', 'user', 'my-profile', 'txt_main_title', 'My Profile'),
('en', 'user', 'my-profile', 'txt_fname', 'First Name'),
('en', 'user', 'my-profile', 'txt_lname', 'Last Name'),
('en', 'user', 'my-profile', 'txt_download_data', 'Download Data'),
('en', 'user', 'my-reviews', 'txt_html_title', 'My Reviews'),
('en', 'user', 'my-reviews', 'txt_main_title', 'My Reviews'),
('en', 'user', 'my-reviews', 'txt_remove_review', 'Remove review'),
('en', 'user', 'my-reviews', 'txt_edit_review', 'Edit review'),
('en', 'user', 'my-reviews', 'txt_no_activity', 'You have not reviewed any place yet.'),
('en', 'user', 'my-reviews', 'txt_remove_confirm', 'Are you sure you want to remove this review?'),
('en', 'user', 'password-reset', 'txt_html_title', 'Reset your password'),
('en', 'user', 'password-reset', 'txt_main_title', 'Reset your password'),
('en', 'user', 'password-reset', 'txt_enter_new_pass', 'Enter your new password below'),
('en', 'user', 'password-reset', 'txt_or_login', 'Or'),
('en', 'user', 'password-reset', 'txt_invalid_token', 'Invalid Token'),
('en', 'user', 'password-reset', 'txt_update_success', 'Password updated successfully'),
('en', 'user', 'process-claim', 'txt_html_title', 'Claim Listing'),
('en', 'user', 'process-claim', 'txt_main_title', 'Claim Listing'),
('en', 'user', 'process-claim', 'txt_meta_desc', 'Claim Listing'),
('en', 'user', 'process-claim', 'txt_confirm_claim', 'Confirm your plan'),
('en', 'user', 'process-claim', 'txt_selected_plan', 'Selected Plan'),
('en', 'user', 'process-claim', 'txt_plan_price', 'Price'),
('en', 'user', 'process-claim', 'txt_pay_paypal', 'Pay with Paypal'),
('en', 'user', 'process-claim', 'txt_pay_stripe', 'Pay with Stripe'),
('en', 'user', 'process-create-listing', 'txt_html_title', 'Include Listing'),
('en', 'user', 'process-create-listing', 'txt_html_title_free', 'Listing submitted'),
('en', 'user', 'process-create-listing', 'txt_main_title_success', 'Confirm Inclusion'),
('en', 'user', 'process-create-listing', 'txt_main_title_error', 'Inclusion Error'),
('en', 'user', 'process-create-listing', 'txt_main_title_reload', 'Inclusion Error'),
('en', 'user', 'process-create-listing', 'txt_main_title_free', 'Thank you'),
('en', 'user', 'process-create-listing', 'txt_thanks_msg', 'Your listing will be approved soon.'),
('en', 'user', 'process-create-listing', 'txt_thanks_admin', 'Listing submitted.'),
('en', 'user', 'process-create-listing', 'txt_checkout_msg', 'Please check your order details and continue with payment'),
('en', 'user', 'process-create-listing', 'txt_selected_plan', 'Selected plan'),
('en', 'user', 'process-create-listing', 'txt_plan_value', 'Price'),
('en', 'user', 'process-create-listing', 'txt_pay_paypal', 'Pay with Paypal'),
('en', 'user', 'process-create-listing', 'txt_order_details', 'Order Details'),
('en', 'user', 'process-create-listing', 'txt_invalid_email', 'Invalid Email'),
('en', 'user', 'process-edit-listing', 'txt_html_title', 'Edit Listing'),
('en', 'user', 'process-edit-listing', 'txt_main_title', 'Edit Listing - ''%place_name%'''),
('en', 'user', 'process-edit-listing', 'txt_error_no_permission', 'You don''t have permission to access this page'),
('en', 'user', 'process-edit-listing', 'txt_success', '%place_name% information has been saved.'),
('en', 'user', 'process-edit-pass', 'txt_html_title', 'Change Password'),
('en', 'user', 'process-edit-pass', 'txt_main_title', 'Change Password'),
('en', 'user', 'process-edit-pass', 'txt_success', 'Password changed successfully.'),
('en', 'user', 'process-edit-pass', 'txt_problem', 'Problem writing new password into database.'),
('en', 'user', 'process-edit-pass', 'txt_wrong', 'Current password is wrong.'),
('en', 'user', 'process-edit-pass', 'txt_social', 'Error: You are logged in with a social media account and you cannot set a new password.'),
('en', 'user', 'register', 'txt_html_title', 'Create an Account'),
('en', 'user', 'register', 'txt_main_title', 'Sign up'),
('en', 'user', 'register', 'txt_acct_created', 'Account created!'),
('en', 'user', 'register', 'txt_acct_created_explain', 'Please check your email and click on the confirmation link.'),
('en', 'user', 'register', 'txt_email_exists', 'User already exists'),
('en', 'user', 'register', 'txt_email_exists_explain', 'Sorry, this email is already registered.'),
('en', 'user', 'register', 'txt_fname', 'First Name'),
('en', 'user', 'register', 'txt_has_account', 'Already have an account?'),
('en', 'user', 'register', 'txt_invalid_email', 'Invalid email'),
('en', 'user', 'register', 'txt_invalid_email_explain', 'Mailbox given [] does not comply with RFC 2822'),
('en', 'user', 'register', 'txt_lname', 'Last Name'),
('en', 'user', 'register', 'txt_missing_fields', 'Missing fields'),
('en', 'user', 'register', 'txt_missing_fields_explain', 'You left some of the fields blank. All fields are required.'),
('en', 'user', 'register', 'txt_submit_again', 'Please submit the form again.'),
('en', 'user', 'register-confirm', 'txt_html_title', 'Signup Confirmation'),
('en', 'user', 'register-confirm', 'txt_main_title', 'Signup Confirmation'),
('en', 'user', 'register-confirm', 'txt_confirmation_success', 'Confirmation succeeded.'),
('en', 'user', 'register-confirm', 'txt_sign_in', 'You can now sign in.'),
('en', 'user', 'register-confirm', 'txt_confirmation_fail', 'Confirmation failed.'),
('en', 'user', 'resend-confirmation', 'txt_html_title', 'Resend Confirmation Email'),
('en', 'user', 'resend-confirmation', 'txt_main_title', 'Resend Confirmation Email'),
('en', 'user', 'resend-confirmation', 'txt_wrong_pass', 'Wrong password or email'),
('en', 'user', 'resend-confirmation', 'txt_confirmation_sent', 'Confirmation sent. Please check your email.'),
('en', 'user', 'resend-confirmation', 'txt_mailer_problem', 'Email failed'),
('en', 'user', 'resend-confirmation', 'txt_invalid_email', 'Invalid email'),
('en', 'user', 'select-plan', 'txt_html_title', 'Select a Plan'),
('en', 'user', 'select-plan', 'txt_main_title', 'Select a Plan'),
('en', 'user', 'select-plan', 'txt_month', 'month'),
('en', 'user', 'select-plan', 'txt_buy_now', 'Select'),
('en', 'user', 'select-plan', 'txt_no_plans', 'There are no plans defined.'),
('en', 'user', 'sign-in', 'txt_html_title', 'Sign in'),
('en', 'user', 'sign-in', 'txt_main_title', 'Sign in'),
('en', 'user', 'sign-in', 'txt_wrong_pass', 'Wrong password or email'),
('en', 'user', 'sign-in', 'txt_email_used', 'Your facebook or twitter email is linked to an account on this site. Please login below.'),
('en', 'user', 'sign-in', 'txt_forgot_pass', 'Forgot password?'),
('en', 'user', 'sign-in', 'txt_new_to_site', 'Need an account?'),
('en', 'user', 'sign-in', 'txt_create_account', 'Create an account?'),
('en', 'user', 'sign-in', 'txt_pending_account', 'Account is pending confirmation.'),
('en', 'user', 'sign-in', 'txt_resend_confirmation', 'Resend confirmation email'),
('en', 'user', 'sign-in', 'txt_from_select_plan', 'To include a listing, please login or create an account.'),
('en', 'user', 'sign-in', 'txt_not_registered', 'This email is not registered.'),
('en', 'user', 'sign-out', 'txt_html_title', 'Logging you off'),
('en', 'user', 'sign-out', 'txt_main_title', 'Logging you off'),
('en', 'user', 'sign-out', 'txt_message', 'Please wait a few seconds.'),
('en', 'user', 'thanks', 'txt_html_title', 'Thank you'),
('en', 'user', 'thanks', 'txt_main_title', 'Thank you for your payment!'),
('en', 'user', 'thanks', 'txt_thanks_msg', 'Payment completed successfully and your listing will be approved soon.'),
('en', 'admin', 'admin-global', 'txt_action', 'Action'),
('en', 'admin', 'admin-global', 'txt_active', 'Active'),
('en', 'admin', 'admin-global', 'txt_all', 'All'),
('en', 'admin', 'admin-global', 'txt_create_listing', 'Create Listing'),
('en', 'admin', 'admin-global', 'txt_admin_dashboard', 'Admin Dashboard'),
('en', 'admin', 'admin-global', 'txt_approved', 'Approved'),
('en', 'admin', 'admin-global', 'txt_custom_fields', 'Custom Fields'),
('en', 'admin', 'admin-global', 'txt_emails', 'Emails'),
('en', 'admin', 'admin-global', 'txt_empty', 'Empty Trash'),
('en', 'admin', 'admin-global', 'txt_inactive', 'Inactive'),
('en', 'admin', 'admin-global', 'txt_listings', 'Listings'),
('en', 'admin', 'admin-global', 'txt_locations', 'Locations'),
('en', 'admin', 'admin-global', 'txt_optional', 'Optional'),
('en', 'admin', 'admin-global', 'txt_paid', 'Paid'),
('en', 'admin', 'admin-global', 'txt_plans', 'Plans'),
('en', 'admin', 'admin-global', 'txt_restore', 'Restore'),
('en', 'admin', 'admin-global', 'txt_site_settings', 'Site Settings'),
('en', 'admin', 'admin-global', 'txt_slug', 'Slug'),
('en', 'admin', 'admin-global', 'txt_sort', 'Sort'),
('en', 'admin', 'admin-global', 'txt_status', 'Status'),
('en', 'admin', 'admin-global', 'txt_title', 'Title'),
('en', 'admin', 'admin-global', 'txt_tools', 'Tools'),
('en', 'admin', 'admin-global', 'txt_transactions', 'Transactions'),
('en', 'admin', 'admin-global', 'txt_trash', 'Trash'),
('en', 'admin', 'admin-global', 'txt_unpaid', 'Unpaid'),
('en', 'admin', 'admin-global', 'txt_update_success', 'Updated successfully'),
('en', 'admin', 'categories', 'txt_html_title', 'Categories'),
('en', 'admin', 'categories', 'txt_main_title', 'Categories'),
('en', 'admin', 'categories', 'txt_by_name', 'By name'),
('en', 'admin', 'categories', 'txt_by_parent_id', 'By parent id'),
('en', 'admin', 'categories', 'txt_cat_created', 'Category created successfully'),
('en', 'admin', 'categories', 'txt_cat_edited', 'Category edited successfully'),
('en', 'admin', 'categories', 'txt_cat_icon', 'Icon font tag'),
('en', 'admin', 'categories', 'txt_cat_img', 'Category image'),
('en', 'admin', 'categories', 'txt_cat_name', 'Category name'),
('en', 'admin', 'categories', 'txt_cat_name_empty', 'Category name cannot be empty.'),
('en', 'admin', 'categories', 'txt_cat_order', 'Category order (must be a number)'),
('en', 'admin', 'categories', 'txt_cat_removed', 'Category removed'),
('en', 'admin', 'categories', 'txt_cat_slug', 'Category slug'),
('en', 'admin', 'categories', 'txt_create_cat', 'Create Category'),
('en', 'admin', 'categories', 'txt_edit_cat', 'Edit category'),
('en', 'admin', 'categories', 'txt_icon_filename', 'Icon File'),
('en', 'admin', 'categories', 'txt_no_parent', 'None'),
('en', 'admin', 'categories', 'txt_order', 'Order'),
('en', 'admin', 'categories', 'txt_parent_cat', 'Parent category'),
('en', 'admin', 'categories', 'txt_parent_explain', 'Maximum 3 levels deep.'),
('en', 'admin', 'categories', 'txt_parent_id', 'Parent Id'),
('en', 'admin', 'categories', 'txt_plural_name', 'Plural Name'),
('en', 'admin', 'categories', 'txt_remove_cat', 'Remove category'),
('en', 'admin', 'categories', 'txt_remove_warn', 'Are you sure you want to remove this category?'),
('en', 'admin', 'categories-trash', 'txt_html_title', 'Categories - Trash'),
('en', 'admin', 'categories-trash', 'txt_main_title', 'Categories - Trash'),
('en', 'admin', 'categories-trash', 'txt_order', 'Order'),
('en', 'admin', 'categories-trash', 'txt_parent_id', 'Parent Id'),
('en', 'admin', 'categories-trash', 'txt_restore', 'Restore category'),
('en', 'admin', 'categories-trash', 'txt_remove_sure', 'Are you sure you want to remove this category permanently? This action cannot be undone.'),
('en', 'admin', 'categories-trash', 'txt_remove_all_sure', 'Are you sure you want to remove permanently all categories in the trash can? This action cannot be undone.'),
('en', 'admin', 'coupons', 'txt_html_title', 'Coupons'),
('en', 'admin', 'coupons', 'txt_main_title', 'Coupons'),
('en', 'admin', 'coupons', 'txt_title', 'Title'),
('en', 'admin', 'coupons', 'txt_created', 'Created'),
('en', 'admin', 'coupons', 'txt_expire', 'Expire'),
('en', 'admin', 'coupons', 'txt_listing', 'Listing'),
('en', 'admin', 'coupons-trash', 'txt_html_title', 'Coupons Trash'),
('en', 'admin', 'coupons-trash', 'txt_main_title', 'Coupons Trash'),
('en', 'admin', 'coupons-trash', 'txt_created', 'Created'),
('en', 'admin', 'coupons-trash', 'txt_expire', 'Expire'),
('en', 'admin', 'coupons-trash', 'txt_listing', 'Listing'),
('en', 'admin', 'coupons-trash', 'txt_remove', 'Remove permanently'),
('en', 'admin', 'coupons-trash', 'txt_remove_all_sure', 'Are you sure you want to remove all plans in the trash bin? This action cannot be undone.'),
('en', 'admin', 'coupons-trash', 'txt_remove_sure', 'Are you sure you want to remove this coupon? This action cannot be undone.'),
('en', 'admin', 'coupons-trash', 'txt_removed_all', 'All coupons removed'),
('en', 'admin', 'coupons-trash', 'txt_title', 'Title'),
('en', 'admin', 'create-page', 'txt_html_title', 'Create Page'),
('en', 'admin', 'create-page', 'txt_main_title', 'Create Page'),
('en', 'admin', 'create-page', 'txt_page_title', 'Page Title'),
('en', 'admin', 'create-page', 'txt_meta_desc', 'Meta description'),
('en', 'admin', 'create-page', 'txt_order', 'Order'),
('en', 'admin', 'create-page', 'txt_page_created', 'Page created successfully'),
('en', 'admin', 'create-page', 'txt_show_in_blog', 'Show in blog?'),
('en', 'admin', 'create-page', 'txt_enable_comments', 'Enable comments?'),
('en', 'admin', 'create-page', 'txt_thumb', 'Page thumbnail'),
('en', 'admin', 'create-custom-field', 'txt_html_title', 'Create Custom Field'),
('en', 'admin', 'create-custom-field', 'txt_main_title', 'Create Custom Field'),
('en', 'admin', 'create-custom-field', 'txt_field_name', 'Field Name'),
('en', 'admin', 'create-custom-field', 'txt_field_order', 'Field Order'),
('en', 'admin', 'create-custom-field', 'txt_field_type', 'Field Type'),
('en', 'admin', 'create-custom-field', 'txt_filter_display', 'Filter Display As'),
('en', 'admin', 'create-custom-field', 'txt_goto', 'Go to Custom Fields'),
('en', 'admin', 'create-custom-field', 'txt_options', 'Options'),
('en', 'admin', 'create-custom-field', 'txt_range_number', 'Range Number'),
('en', 'admin', 'create-custom-field', 'txt_range_select', 'Range Select'),
('en', 'admin', 'create-custom-field', 'txt_range_text', 'Range Text'),
('en', 'admin', 'create-custom-field', 'txt_required', 'Required?'),
('en', 'admin', 'create-custom-field', 'txt_searchable', 'Searchable?'),
('en', 'admin', 'create-custom-field', 'txt_select_all', 'Select All'),
('en', 'admin', 'create-custom-field', 'txt_tooltip', 'Tooltip <em>(optional)</em>'),
('en', 'admin', 'create-custom-field', 'txt_type_check', 'Checkbox'),
('en', 'admin', 'create-custom-field', 'txt_type_multiline', 'Text Multiline'),
('en', 'admin', 'create-custom-field', 'txt_type_radio', 'Radio'),
('en', 'admin', 'create-custom-field', 'txt_type_select', 'Select'),
('en', 'admin', 'create-custom-field', 'txt_type_text', 'Text'),
('en', 'admin', 'create-custom-field', 'txt_type_url', 'URL'),
('en', 'admin', 'create-custom-field', 'txt_values_list', 'List of values allowed <em>(separated by ;)</em> *only used for radio, select and checkboxes'),
('en', 'admin', 'custom-fields', 'txt_field_name', 'Field Name'),
('en', 'admin', 'custom-fields', 'txt_field_type', 'Field Type'),
('en', 'admin', 'custom-fields', 'txt_type_text', 'Text'),
('en', 'admin', 'custom-fields', 'txt_type_radio', 'Radio'),
('en', 'admin', 'custom-fields', 'txt_type_select', 'Select'),
('en', 'admin', 'custom-fields', 'txt_type_check', 'Checkbox'),
('en', 'admin', 'custom-fields', 'txt_type_multiline', 'Text Multiline'),
('en', 'admin', 'custom-fields', 'txt_type_url', 'URL'),
('en', 'admin', 'custom-fields', 'txt_values_list', 'List of values allowed <em>(separated by ;)</em> *only used for radio, select and checkboxes'),
('en', 'admin', 'custom-fields', 'txt_tooltip', 'Tooltip <em>(optional)</em>'),
('en', 'admin', 'custom-fields', 'txt_icon', 'Icon tag (e.g. Fontawesome tag)'),
('en', 'admin', 'custom-fields', 'txt_options', 'Options'),
('en', 'admin', 'custom-fields', 'txt_required', 'Required?'),
('en', 'admin', 'custom-fields', 'txt_searchable', 'Searchable?'),
('en', 'admin', 'custom-fields', 'txt_field_order', 'Field Order'),
('en', 'admin', 'custom-fields', 'txt_categories', 'Categories'),
('en', 'admin', 'custom-fields', 'txt_html_title', 'Custom Fields'),
('en', 'admin', 'custom-fields', 'txt_main_title', 'Custom Fields'),
('en', 'admin', 'custom-fields', 'txt_create_field', 'Create Field'),
('en', 'admin', 'custom-fields', 'txt_edit_field', 'Edit this custom field'),
('en', 'admin', 'custom-fields', 'txt_remove_field', 'Remove this custom field'),
('en', 'admin', 'custom-fields', 'txt_field_created', 'Field created'),
('en', 'admin', 'custom-fields', 'txt_create_another', 'Create another field.'),
('en', 'admin', 'custom-fields', 'txt_field_removed', 'Field removed'),
('en', 'admin', 'custom-fields', 'txt_header_custom_fields', 'Additional Information'),
('en', 'admin', 'custom-fields', 'txt_no_custom_fields', 'No custom fields available for this category'),
('en', 'admin', 'custom-fields', 'txt_html_title', 'Custom Fields'),
('en', 'admin', 'custom-fields', 'txt_main_title', 'Custom Fields'),
('en', 'admin', 'custom-fields', 'txt_meta_desc', 'Custom Fields'),
('en', 'admin', 'custom-fields', 'txt_search_results', 'Search Results'),
('en', 'admin', 'custom-fields', 'txt_label_keyword', 'Keyword'),
('en', 'admin', 'custom-fields', 'txt_label_city', 'Select City'),
('en', 'admin', 'custom-fields', 'txt_label_category', 'Select Category'),
('en', 'admin', 'custom-fields', 'txt_label_cat_all', 'Select All'),
('en', 'admin', 'edit-custom-field', 'txt_html_title', 'Edit Custom Field'),
('en', 'admin', 'edit-custom-field', 'txt_main_title', 'Edit Custom Field'),
('en', 'admin', 'edit-custom-field', 'txt_field_name', 'Field name'),
('en', 'admin', 'edit-custom-field', 'txt_field_order', 'Field Order'),
('en', 'admin', 'edit-custom-field', 'txt_field_type', 'Field type'),
('en', 'admin', 'edit-custom-field', 'txt_field_updated', 'Field updated'),
('en', 'admin', 'edit-custom-field', 'txt_filter_display', 'Filter Display As'),
('en', 'admin', 'edit-custom-field', 'txt_options', 'Options'),
('en', 'admin', 'edit-custom-field', 'txt_range_number', 'Range Number'),
('en', 'admin', 'edit-custom-field', 'txt_range_select', 'Range Select'),
('en', 'admin', 'edit-custom-field', 'txt_range_text', 'Range Text'),
('en', 'admin', 'edit-custom-field', 'txt_searchable', 'Searchable'),
('en', 'admin', 'edit-custom-field', 'txt_select_all', 'Select All'),
('en', 'admin', 'edit-custom-field', 'txt_tooltip', 'Tooltip'),
('en', 'admin', 'edit-custom-field', 'txt_values_list', 'List of values allowed (separated by ;) *only used for radio, select and checkboxes'),
('en', 'admin', 'edit-page', 'txt_html_title', 'Edit Page'),
('en', 'admin', 'edit-page', 'txt_main_title', 'Edit Page'),
('en', 'admin', 'edit-page', 'txt_page_title', 'Page Title'),
('en', 'admin', 'edit-page', 'txt_show_in_blog', 'Show in blog?'),
('en', 'admin', 'edit-page', 'txt_meta_desc', 'Meta description'),
('en', 'admin', 'edit-page', 'txt_enable_comments', 'Enable comments?'),
('en', 'admin', 'edit-page', 'txt_thumb', 'Page thumbnail'),
('en', 'admin', 'emails', 'txt_html_title', 'Email Templates'),
('en', 'admin', 'emails', 'txt_main_title', 'Email Templates'),
('en', 'admin', 'emails', 'txt_type', 'Type'),
('en', 'admin', 'emails', 'txt_description', 'Description'),
('en', 'admin', 'emails', 'txt_edit_template', 'Edit email template'),
('en', 'admin', 'emails', 'txt_no_templates', 'No email templates'),
('en', 'admin', 'emails', 'txt_available_vars_header', 'Available variables'),
('en', 'admin', 'emails', 'txt_email_subject', 'Email Subject'),
('en', 'admin', 'emails', 'txt_email_body', 'Email Body'),
('en', 'admin', 'emails', 'txt_instruct_reset', 'You must include the ''%reset_link%'' token in the email body. It will get replaced by a real link the user will have to click to reset his password'),
('en', 'admin', 'emails', 'txt_instruct_signup', 'You must include the ''%confirm_link%'' token in the email body. It will get replaced by a real link the user will have to click to confirm account creation.'),
('en', 'admin', 'emails', 'txt_email_template_updated', 'Template updated sucessfully'),
('en', 'admin', 'home', 'txt_html_title', 'Admin dashboard'),
('en', 'admin', 'home', 'txt_main_title', 'Admin dashboard'),
('en', 'admin', 'home', 'txt_your_info', 'Your information'),
('en', 'admin', 'home', 'txt_your_id', 'Your id'),
('en', 'admin', 'home', 'txt_your_email', 'Your email'),
('en', 'admin', 'home', 'txt_your_prof', 'Your public profile'),
('en', 'admin', 'home', 'txt_site_stats', 'Site statistics'),
('en', 'admin', 'home', 'txt_pending_mod', 'Listings pending moderation'),
('en', 'admin', 'home', 'txt_total_list', 'Total listings'),
('en', 'admin', 'home', 'txt_total_users', 'Total users'),
('en', 'admin', 'home', 'txt_total_reviews', 'Total reviews'),
('en', 'admin', 'home', 'txt_version', 'Script version'),
('en', 'admin', 'home', 'txt_support', 'Support'),
('en', 'admin', 'home', 'txt_support_req', 'Send request'),
('en', 'admin', 'home', 'txt_latest_listings', 'Latest Listings'),
('en', 'admin', 'home', 'txt_latest_signups', 'Latest Signups'),
('en', 'admin', 'listings', 'txt_html_title', 'Listings'),
('en', 'admin', 'listings', 'txt_main_title', 'Listings'),
('en', 'admin', 'listings', 'txt_by_date', 'By date'),
('en', 'admin', 'listings', 'txt_by_name', 'By name'),
('en', 'admin', 'listings', 'txt_edit_place', 'Edit Listing'),
('en', 'admin', 'listings', 'txt_find', 'Find'),
('en', 'admin', 'listings', 'txt_listing_owner', 'Owner'),
('en', 'admin', 'listings', 'txt_place_name', 'Listing Name'),
('en', 'admin', 'listings', 'txt_plan_name', 'Plan Name'),
('en', 'admin', 'listings', 'txt_plan_type', 'Plan Type'),
('en', 'admin', 'listings', 'txt_place_removed', 'Listing removed successfully'),
('en', 'admin', 'listings', 'txt_remove_place', 'Remove Listing'),
('en', 'admin', 'listings', 'txt_remove_warn', 'Are you sure you want to remove this listing?'),
('en', 'admin', 'listings', 'txt_search_results', 'Search results for:'),
('en', 'admin', 'listings', 'txt_toggle_approved', 'Toggle Approved/Pending'),
('en', 'admin', 'listings', 'txt_toggle_featured', 'Toggle Featured Home'),
('en', 'admin', 'listings', 'txt_toggle_paid', 'Toggle Paid/Unpaid Status'),
('en', 'admin', 'listings', 'txt_tooltip_expand', 'Expand details'),
('en', 'admin', 'listings', 'txt_transfer_owner', 'Transfer to user id: '),
('en', 'admin', 'listings-trash', 'txt_by_date', 'By date'),
('en', 'admin', 'listings-trash', 'txt_by_name', 'By name'),
('en', 'admin', 'listings-trash', 'txt_html_title', 'Listings - Trash'),
('en', 'admin', 'listings-trash', 'txt_listing_owner', 'Owner'),
('en', 'admin', 'listings-trash', 'txt_main_title', 'Listings - Trash'),
('en', 'admin', 'listings-trash', 'txt_place_name', 'Listing name'),
('en', 'admin', 'listings-trash', 'txt_place_removed', 'Listing removed'),
('en', 'admin', 'listings-trash', 'txt_plan_name', 'Plan name'),
('en', 'admin', 'listings-trash', 'txt_plan_type', 'Plan type'),
('en', 'admin', 'listings-trash', 'txt_remove_perm', 'Remove permanently'),
('en', 'admin', 'listings-trash', 'txt_remove_perm_sure', 'Are you sure you want to remove this listing permanently? This action cannot be undone.'),
('en', 'admin', 'listings-trash', 'txt_remove_perm_sure_all', 'Are you sure you want to remove permanently all listings in the trash can? This action cannot be undone.'),
('en', 'admin', 'listings-trash', 'txt_tooltip_expand', 'Expand details'),
('en', 'admin', 'listings-trash', 'txt_tooltip_remove', 'Remove permanently'),
('en', 'admin', 'listings-trash', 'txt_tooltip_restore', 'Restore listing'),
('en', 'admin', 'locations', 'txt_html_title', 'Locations'),
('en', 'admin', 'locations', 'txt_main_title', 'Locations'),
('en', 'admin', 'locations', 'txt_city_id', 'City id'),
('en', 'admin', 'locations', 'txt_city_name', 'City name'),
('en', 'admin', 'locations', 'txt_country_code', 'Country code'),
('en', 'admin', 'locations', 'txt_country_id', 'Country id'),
('en', 'admin', 'locations', 'txt_country_name', 'Country name'),
('en', 'admin', 'locations', 'txt_create_city', 'Create city'),
('en', 'admin', 'locations', 'txt_create_country', 'Create country'),
('en', 'admin', 'locations', 'txt_create_state', 'Create state'),
('en', 'admin', 'locations', 'txt_edit_city', 'Edit city'),
('en', 'admin', 'locations', 'txt_edit_country', 'Edit country'),
('en', 'admin', 'locations', 'txt_edit_location', 'Edit location'),
('en', 'admin', 'locations', 'txt_edit_state', 'Edit state'),
('en', 'admin', 'locations', 'txt_quick_jump', 'Quick jump'),
('en', 'admin', 'locations', 'txt_remove_city', 'Remove city'),
('en', 'admin', 'locations', 'txt_remove_country', 'Remove country'),
('en', 'admin', 'locations', 'txt_remove_state', 'Remove state'),
('en', 'admin', 'locations', 'txt_state_id', 'State id'),
('en', 'admin', 'locations', 'txt_state_name', 'State name'),
('en', 'admin', 'locations', 'txt_toggle_featured', 'Toggle home featured'),
('en', 'admin', 'locations', 'txt_country_abbr', 'Country abbreviation (max 3 chars)'),
('en', 'admin', 'locations', 'txt_lat', 'Latitude'),
('en', 'admin', 'locations', 'txt_lng', 'Longitude'),
('en', 'admin', 'locations', 'txt_msg_no_country', 'No country has been created yet'),
('en', 'admin', 'locations', 'txt_msg_no_state', 'No state has been created yet'),
('en', 'admin', 'locations', 'txt_select_country', 'Select country'),
('en', 'admin', 'locations', 'txt_select_state', 'Select state'),
('en', 'admin', 'locations', 'txt_state_abbr', 'State abbreviation'),
('en', 'admin', 'locations', 'txt_city_created', 'City created successfully'),
('en', 'admin', 'locations', 'txt_city_name_empty', 'City name cannot be empty. Please enter the city name.'),
('en', 'admin', 'locations', 'txt_country_created', 'Country created successfully'),
('en', 'admin', 'locations', 'txt_country_name_empty', 'Country name and abbreviation cannot be empty. Please enter the name and abbreviation.'),
('en', 'admin', 'locations', 'txt_pls_create_country', 'Please create a country first.'),
('en', 'admin', 'locations', 'txt_pls_create_state', 'Please create a state first.'),
('en', 'admin', 'locations', 'txt_state_created', 'State created successfully'),
('en', 'admin', 'locations', 'txt_state_name_empty', 'State name and abbreviation cannot be empty. Please enter the name and abbreviation.'),
('en', 'admin', 'locations', 'txt_city_edited', 'City edited successfully'),
('en', 'admin', 'locations', 'txt_state_edited', 'State edited successfully'),
('en', 'admin', 'locations', 'txt_country_edited', 'Country edited successfully'),
('en', 'admin', 'locations', 'txt_loc_removed', 'Location deleted'),
('en', 'admin', 'locations', 'txt_loc_remove_problem', 'Problem removing location'),
('en', 'admin', 'pages', 'txt_html_title', 'Pages'),
('en', 'admin', 'pages', 'txt_main_title', 'Pages'),
('en', 'admin', 'pages', 'txt_create_page', 'Create page'),
('en', 'admin', 'pages', 'txt_edit_page', 'Edit page'),
('en', 'admin', 'pages', 'txt_remove_page', 'Remove page'),
('en', 'admin', 'pages', 'txt_page_created', 'Page created sucessfully'),
('en', 'admin', 'pages', 'txt_page_updated', 'Page updated sucessfully'),
('en', 'admin', 'pages', 'txt_page_removed', 'Page deleted'),
('en', 'admin', 'pages-trash', 'txt_html_title', 'Pages - Trash'),
('en', 'admin', 'pages-trash', 'txt_main_title', 'Pages - Trash'),
('en', 'admin', 'pages-trash', 'txt_page_title', 'Page Title'),
('en', 'admin', 'pages-trash', 'txt_group', 'Group'),
('en', 'admin', 'pages-trash', 'txt_order', 'Order'),
('en', 'admin', 'pages-trash', 'txt_remove', 'Remove permanently'),
('en', 'admin', 'pages-trash', 'txt_remove_sure', 'Are you sure you want to remove this page? This action cannot be undone.'),
('en', 'admin', 'pages-trash', 'txt_remove_all_sure', 'Are you sure you want to remove permanently all pages in the trash bin? This action cannot be undone.'),
('en', 'admin', 'pages-trash', 'txt_removed_all', 'All pages removed'),
('en', 'admin', 'plans', 'txt_html_title', 'Billing Plans'),
('en', 'admin', 'plans', 'txt_main_title', 'Billing Plans'),
('en', 'admin', 'plans', 'txt_annual', 'Annual'),
('en', 'admin', 'plans', 'txt_annual_f', 'Annual featured'),
('en', 'admin', 'plans', 'txt_create', 'Create Plan'),
('en', 'admin', 'plans', 'txt_edit_plan', 'Edit Plan'),
('en', 'admin', 'plans', 'txt_features', 'Features (one per line)'),
('en', 'admin', 'plans', 'txt_free', 'Free'),
('en', 'admin', 'plans', 'txt_free_featured', 'Free featured'),
('en', 'admin', 'plans', 'txt_monthly', 'Monthly'),
('en', 'admin', 'plans', 'txt_monthly_f', 'Monthly featured'),
('en', 'admin', 'plans', 'txt_no_plans', 'There are no billing plans defined. Please create a billing plan.'),
('en', 'admin', 'plans', 'txt_one_time', 'One time payment'),
('en', 'admin', 'plans', 'txt_one_time_f', 'One time payment featured');
INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
('en', 'admin', 'plans', 'txt_order', 'Order (at which they will appear to the user in the Select Plan page)'),
('en', 'admin', 'plans', 'txt_period', 'Plan Period (Number of days. Only valid for free and ''one time payments'' plan types). Enter 0 or leave blank for non expiring ads (permanent)'),
('en', 'admin', 'plans', 'txt_plan_name', 'Plan Name'),
('en', 'admin', 'plans', 'txt_plan_price', 'Plan Price'),
('en', 'admin', 'plans', 'txt_plan_status', 'Plan Status'),
('en', 'admin', 'plans', 'txt_plan_type', 'Plan Type'),
('en', 'admin', 'plans', 'txt_price', 'Price'),
('en', 'admin', 'plans', 'txt_remove_plan', 'Remove Plan'),
('en', 'admin', 'plans', 'txt_toggle_active', 'Toggle Active/Inactive'),
('en', 'admin', 'plans', 'txt_plan_created', 'Plan created successfully'),
('en', 'admin', 'plans', 'txt_create_problem', 'Problem creating plan'),
('en', 'admin', 'plans', 'txt_plan_updated', 'Plan updated sucessfully'),
('en', 'admin', 'plans', 'txt_plan_removed', 'Plan removed'),
('en', 'admin', 'plans', 'txt_plan_period', 'Plan Period (Number of days. Only valid for free or ''one time payments'' plan types)'),
('en', 'admin', 'plans', 'txt_plan_order', 'Plan Order'),
('en', 'admin', 'plans', 'txt_change_price', 'It''s not possible to change the price for the ''free'' plan type'),
('en', 'admin', 'plans-trash', 'txt_html_title', 'Billing Plans - Trash'),
('en', 'admin', 'plans-trash', 'txt_main_title', 'Billing Plans - Trash'),
('en', 'admin', 'plans-trash', 'txt_plan_name', 'Plan Name'),
('en', 'admin', 'plans-trash', 'txt_plan_type', 'Plan Type'),
('en', 'admin', 'plans-trash', 'txt_price', 'Price'),
('en', 'admin', 'plans-trash', 'txt_remove', 'Remove permanently'),
('en', 'admin', 'plans-trash', 'txt_remove_sure', 'Are you sure you want to remove this plan? This action cannot be undone.'),
('en', 'admin', 'plans-trash', 'txt_remove_all_sure', 'Are you sure you want to remove permanently all plans in the trash bin? This action cannot be undone.'),
('en', 'admin', 'plans-trash', 'txt_removed_all', 'All plans removed'),
('en', 'admin', 'process-settings', 'txt_html_title', 'Settings Updated'),
('en', 'admin', 'process-settings', 'txt_main_title', 'Settings'),
('en', 'admin', 'process-settings', 'txt_update_success', 'Settings saved'),
('en', 'admin', 'reviews', 'txt_html_title', 'Reviews'),
('en', 'admin', 'reviews', 'txt_main_title', 'Reviews'),
('en', 'admin', 'reviews', 'txt_place_name', 'Place Name'),
('en', 'admin', 'reviews', 'txt_tooltip_toggle_approved', 'Toggle Approved/Pending'),
('en', 'admin', 'reviews', 'txt_tooltip_expand_review', 'Expand Review'),
('en', 'admin', 'reviews', 'txt_tooltip_remove_review', 'Remove Review'),
('en', 'admin', 'reviews', 'txt_no_reviews_pending', 'There are no reviews pending moderation'),
('en', 'admin', 'reviews-trash', 'txt_html_title', 'Reviews - Trash'),
('en', 'admin', 'reviews-trash', 'txt_main_title', 'Reviews - Trash'),
('en', 'admin', 'reviews-trash', 'txt_empty_trash', 'Empty Reviews Trash'),
('en', 'admin', 'reviews-trash', 'txt_place_name', 'Place name'),
('en', 'admin', 'reviews-trash', 'txt_remove_perm', 'Remove permanently'),
('en', 'admin', 'reviews-trash', 'txt_remove_perm_sure', 'Are you sure you want to remove this review permanently? This action cannot be undone.'),
('en', 'admin', 'reviews-trash', 'txt_remove_perm_sure_all', 'Are you sure you want to remove permanently all reviews in the trash can? This action cannot be undone.'),
('en', 'admin', 'reviews-trash', 'txt_remove_review', 'Remove review'),
('en', 'admin', 'reviews-trash', 'txt_tooltip_expand_review', 'Expand review'),
('en', 'admin', 'reviews-trash', 'txt_tooltip_remove_review', 'Remove review permanently'),
('en', 'admin', 'reviews-trash', 'txt_tooltip_restore', 'Restore review'),
('en', 'admin', 'settings', 'txt_html_title', 'Settings'),
('en', 'admin', 'settings', 'txt_main_title', 'Settings'),
('en', 'admin', 'settings', 'txt_admin_email', 'Admin email'),
('en', 'admin', 'settings', 'txt_contact_business_subject', 'Contact Business Subject'),
('en', 'admin', 'settings', 'txt_contact_user_subject', 'Contact User Subject'),
('en', 'admin', 'settings', 'txt_country_code', 'Country code'),
('en', 'admin', 'settings', 'txt_country_code_explain', 'ISO 3166-1 alpha-2: two-letter country code, will also be used to build canonical urls so don''t change this often'),
('en', 'admin', 'settings', 'txt_country_name', 'Country name'),
('en', 'admin', 'settings', 'txt_currency_code', 'Currency code'),
('en', 'admin', 'settings', 'txt_currency_code_explain', '3-character <a href=''https://en.wikipedia.org/wiki/ISO_4217#Active_codes'' target=''_blank''>ISO-4217</a> currency code. This will be used when sending data to Paypal.'),
('en', 'admin', 'settings', 'txt_currency_symbol', 'Currency symbol'),
('en', 'admin', 'settings', 'txt_default_cat', 'Default category'),
('en', 'admin', 'settings', 'txt_default_city_id', 'Default city id'),
('en', 'admin', 'settings', 'txt_default_city_id_explain', 'The database row id of the default city '),
('en', 'admin', 'settings', 'txt_default_city_slug', 'Default city slug'),
('en', 'admin', 'settings', 'txt_default_city_slug_explain', 'Slugs are used in urls, lower case name of the default city without special characters or spaces(use dash instead)'),
('en', 'admin', 'settings', 'txt_default_coupon_qty', 'Coupons per ad'),
('en', 'admin', 'settings', 'txt_default_lat', 'Default latitude'),
('en', 'admin', 'settings', 'txt_default_lng', 'Default longitude'),
('en', 'admin', 'settings', 'txt_dev_email', 'Dev email'),
('en', 'admin', 'settings', 'txt_disqus_shortname', 'Disqus Shortname'),
('en', 'admin', 'settings', 'txt_facebook_key', 'Facebook API key'),
('en', 'admin', 'settings', 'txt_facebook_key_explain', 'Used for social login.'),
('en', 'admin', 'settings', 'txt_facebook_secret', 'Facebook API Secret'),
('en', 'admin', 'settings', 'txt_gateway_currency', 'Currency '),
('en', 'admin', 'settings', 'txt_gateway_mode', 'Gateway Mode'),
('en', 'admin', 'settings', 'txt_gmaps_key', 'Google maps key'),
('en', 'admin', 'settings', 'txt_gmaps_key_explain', 'This API key is required to display maps using Google Maps'),
('en', 'admin', 'settings', 'txt_here_key', 'HERE App ID'),
('en', 'admin', 'settings', 'txt_here_secret', 'HERE App Code'),
('en', 'admin', 'settings', 'txt_html_lang', 'HTML lang'),
('en', 'admin', 'settings', 'txt_html_lang_explain', 'the value for the html lang attribute'),
('en', 'admin', 'settings', 'txt_items_per_page', 'Items per page'),
('en', 'admin', 'settings', 'txt_items_per_page_explain', 'How many items to show on each results page'),
('en', 'admin', 'settings', 'txt_live', 'Live'),
('en', 'admin', 'settings', 'txt_live_publishable_key', 'Live Publishable Key'),
('en', 'admin', 'settings', 'txt_live_secret_key', 'Live Secret Key'),
('en', 'admin', 'settings', 'txt_mail_after_post', 'Receive notification on post/edit listing?'),
('en', 'admin', 'settings', 'txt_maintenance_mode', 'Maintenance Mode'),
('en', 'admin', 'settings', 'txt_map_provider', 'Map Provider'),
('en', 'admin', 'settings', 'txt_mapbox_secret', 'MapBox Access Token'),
('en', 'admin', 'settings', 'txt_max_pics', 'Max pics'),
('en', 'admin', 'settings', 'txt_max_pics_explain', 'How many pictures can each business upload'),
('en', 'admin', 'settings', 'txt_paypal_checkout_logo_url', 'Paypal Checkout Logo Url'),
('en', 'admin', 'settings', 'txt_paypal_checkout_logo_url_explain', 'The URL of the logo that will be used on the Paypal checkout page.'),
('en', 'admin', 'settings', 'txt_paypal_header', 'Paypal Settings'),
('en', 'admin', 'settings', 'txt_paypal_locale', 'Paypal login page locale'),
('en', 'admin', 'settings', 'txt_paypal_merchant_id', 'Paypal merchant id'),
('en', 'admin', 'settings', 'txt_paypal_mode', 'Paypal mode (live or sandbox)'),
('en', 'admin', 'settings', 'txt_paypal_sandbox_merch_id', 'Paypal Sandbox merchant id'),
('en', 'admin', 'settings', 'txt_sandbox', 'Sandbox'),
('en', 'admin', 'settings', 'txt_site_name', 'Site name'),
('en', 'admin', 'settings', 'txt_smtp_pass', 'SMTP pass'),
('en', 'admin', 'settings', 'txt_smtp_port', 'SMTP port'),
('en', 'admin', 'settings', 'txt_smtp_server', 'SMTP server'),
('en', 'admin', 'settings', 'txt_smtp_user', 'SMTP user'),
('en', 'admin', 'settings', 'txt_stripe_currency_code', '3-Letter ISO Code'),
('en', 'admin', 'settings', 'txt_stripe_header', 'Stripe Settings'),
('en', 'admin', 'settings', 'txt_stripe_test_mode', 'Test'),
('en', 'admin', 'settings', 'txt_tab_apis', 'APIs'),
('en', 'admin', 'settings', 'txt_tab_email', 'Email'),
('en', 'admin', 'settings', 'txt_tab_general', 'General'),
('en', 'admin', 'settings', 'txt_tab_payment', 'Payment'),
('en', 'admin', 'settings', 'txt_test_publishable_key', 'Test Publishable Key'),
('en', 'admin', 'settings', 'txt_test_secret_key', 'Test Secret Key'),
('en', 'admin', 'settings', 'txt_timezone', 'Timezone'),
('en', 'admin', 'settings', 'txt_timezone_explain', 'Enter your timezone. <a href=''http://php.net/manual/en/timezones.php'' target=''_blank''>Click here</a> for a list of possible values.'),
('en', 'admin', 'settings', 'txt_tomtom_secret', 'TomTom Key'),
('en', 'admin', 'settings', 'txt_twitter_key', 'Twitter API key'),
('en', 'admin', 'settings', 'txt_twitter_key_explain', 'Used for social login.'),
('en', 'admin', 'settings', 'txt_twitter_secret', 'Twitter API secret'),
('en', 'admin', 'tools', 'txt_html_title', 'Admin Tools'),
('en', 'admin', 'tools', 'txt_main_title', 'Admin Tools'),
('en', 'admin', 'tools', 'txt_deactivate_expired', 'Deactivate expired listings'),
('en', 'admin', 'tools', 'txt_deactivate_success', 'Expired listings deactivated successfully'),
('en', 'admin', 'tools', 'txt_deactivate_fail', 'Expired listings deactivated failed'),
('en', 'admin', 'tools', 'txt_delete_tmp_pics', 'Delete temp images'),
('en', 'admin', 'tools', 'txt_regenerate_sitemap', 'Regenerate sitemap.xml'),
('en', 'admin', 'tools', 'txt_submit_sitemap', 'Submit sitemap.xml to Google'),
('en', 'admin', 'tools', 'txt_tool', 'Tool'),
('en', 'admin', 'transactions', 'txt_html_title', 'Transactions History'),
('en', 'admin', 'transactions', 'txt_main_title', 'Transactions History'),
('en', 'admin', 'transactions', 'txt_txn_id', 'Txn Id'),
('en', 'admin', 'transactions', 'txt_place_id', 'Place Id'),
('en', 'admin', 'transactions', 'txt_txn_type', 'Txn Type'),
('en', 'admin', 'transactions', 'txt_amount', 'Amount'),
('en', 'admin', 'transactions', 'txt_sub_id', 'Subscr Id'),
('en', 'admin', 'transactions', 'txt_txn_date', 'Txn Date'),
('en', 'admin', 'users', 'txt_html_title', 'Users'),
('en', 'admin', 'users', 'txt_main_title', 'Users'),
('en', 'admin', 'users', 'txt_by_date', 'By date'),
('en', 'admin', 'users', 'txt_by_email', 'By email'),
('en', 'admin', 'users', 'txt_by_name', 'By name'),
('en', 'admin', 'users', 'txt_created', 'Created'),
('en', 'admin', 'users', 'txt_no_users', 'There are no users'),
('en', 'admin', 'users', 'txt_approve_profile_pic', 'Approve Profile Pic'),
('en', 'admin', 'users', 'txt_remove_user', 'Remove User'),
('en', 'admin', 'users-trash', 'txt_html_title', 'Users - Trash'),
('en', 'admin', 'users-trash', 'txt_main_title', 'Users - Trash'),
('en', 'admin', 'users-trash', 'txt_by_date', 'By date'),
('en', 'admin', 'users-trash', 'txt_by_email', 'By email'),
('en', 'admin', 'users-trash', 'txt_by_name', 'By name'),
('en', 'admin', 'users-trash', 'txt_created', 'Created'),
('en', 'admin', 'users-trash', 'txt_modal_remove_title', 'Remove User'),
('en', 'admin', 'users-trash', 'txt_remove_perm_sure', 'Are you sure you want to remove this user permanently? This action cannot be undone.'),
('en', 'admin', 'users-trash', 'txt_remove_perm_sure_all', 'Are you sure you want to remove permanently all users in the trash can? This action cannot be undone.'),
('en', 'admin', 'users-trash', 'txt_tooltip_remove_user', 'Remove user permanently'),
('en', 'admin', 'users-trash', 'txt_tooltip_restore', 'Restore user'),
('en', 'admin', 'admin-global', 'txt_language', 'Language'),
('en', 'admin', 'language', 'txt_main_title', 'Language'),
('en', 'admin', 'language', 'txt_html_title', 'Language'),
('en', 'admin', 'language', 'txt_installed_lang', 'Installed languages'),
('en', 'admin', 'language', 'txt_create_lang', 'Create Language'),
('en', 'admin', 'language', 'txt_template', 'Template'),
('en', 'admin', 'language', 'txt_load_vars', 'Load vars'),
('en', 'admin', 'language', 'txt_vars', 'Vars'),
('en', 'admin', 'language', 'txt_select_lang_create', 'Select language to create'),
('en', 'admin', 'language', 'txt_install_sql', 'Or install from available sql files'),
('en', 'admin', 'language', 'txt_install', 'Install'),
('en', 'admin', 'language', 'txt_installing', 'Installing...'),
('en', 'admin', 'language', 'txt_done', 'Done'),
('en', 'admin', 'language', 'txt_install_lang', 'Install language'),
('en', 'admin', 'custom-fields-trash', 'txt_field_name', 'Field Name'),
('en', 'admin', 'custom-fields-trash', 'txt_field_type', 'Field Type'),
('en', 'admin', 'custom-fields-trash', 'txt_type_text', 'Text'),
('en', 'admin', 'custom-fields-trash', 'txt_type_radio', 'Radio'),
('en', 'admin', 'custom-fields-trash', 'txt_type_select', 'Select'),
('en', 'admin', 'custom-fields-trash', 'txt_type_check', 'Checkbox'),
('en', 'admin', 'custom-fields-trash', 'txt_type_multiline', 'Text Multiline'),
('en', 'admin', 'custom-fields-trash', 'txt_type_url', 'URL'),
('en', 'admin', 'custom-fields-trash', 'txt_values_list', 'List of values allowed <em>(separated by ;)</em> *only used for radio, select and checkboxes'),
('en', 'admin', 'custom-fields-trash', 'txt_tooltip', 'Tooltip <em>(optional)</em>'),
('en', 'admin', 'custom-fields-trash', 'txt_options', 'Options'),
('en', 'admin', 'custom-fields-trash', 'txt_required', 'Required?'),
('en', 'admin', 'custom-fields-trash', 'txt_searchable', 'Searchable?'),
('en', 'admin', 'custom-fields-trash', 'txt_field_order', 'Field Order'),
('en', 'admin', 'custom-fields-trash', 'txt_html_title', 'Custom Fields - Trash'),
('en', 'admin', 'custom-fields-trash', 'txt_main_title', 'Custom Fields - Trash'),
('en', 'admin', 'custom-fields-trash', 'txt_remove_field', 'Remove this custom field'),
('en', 'admin', 'custom-fields-trash', 'txt_field_removed', 'Field removed'),
('en', 'admin', 'custom-fields-trash', 'txt_header_custom_fields', 'Additional Information'),
('en', 'admin', 'custom-fields-trash', 'txt_no_custom_fields', 'No custom fields available for this category'),
('en', 'admin', 'custom-fields-trash', 'txt_html_title', 'Custom Fields - Trash'),
('en', 'admin', 'custom-fields-trash', 'txt_main_title', 'Custom Fields - Trash'),
('en', 'admin', 'custom-fields-trash', 'txt_meta_desc', 'Custom Fields'),
('en', 'admin', 'custom-fields-trash', 'txt_search_results', 'Search Results'),
('en', 'admin', 'custom-fields-trash', 'txt_label_keyword', 'Keyword'),
('en', 'admin', 'custom-fields-trash', 'txt_label_city', 'Select City'),
('en', 'admin', 'custom-fields-trash', 'txt_label_category', 'Select Category'),
('en', 'admin', 'custom-fields-trash', 'txt_label_cat_all', 'Select All'),
('en', 'admin', 'custom-fields-trash', 'txt_remove_custom_field', 'Remove Custom Field'),
('en', 'admin', 'custom-fields-trash', 'txt_remove_sure', 'Are you sure you want to remove this field? This action cannot be undone.'),
('en', 'admin', 'custom-fields-trash', 'txt_remove_all_sure', 'Are you sure you want to empty the trash bin? This action cannot be undone.'),
('en', 'public', 'maintenance', 'txt_html_title', 'Site Maintenance'),
('en', 'public', 'maintenance', 'txt_message', 'The site will be back soon'),
('en', 'admin', 'language', 'txt_create_string', 'Create String'),
('en', 'admin', 'language', 'txt_var_name', 'Variable Name (starts with txt_ e.g. txt_var_name)'),
('en', 'admin', 'language', 'txt_string_value', 'String Value'),
('en', 'admin', 'language', 'txt_string_created', 'String Created'),
('en', 'admin', 'admin-global', 'txt_maps', 'Maps'),
('en', 'admin', 'settings', 'txt_permalink_struct', 'Permalink Structure (*regenerate sitemap after change)'),
('en', 'admin', 'settings', 'txt_permalink_struct_explain', 'Available tags(use / as separator): %category%/%region%/%city%/%title%'),
('en', 'user', 'create-listing', 'txt_primary_category', 'Primary Category'),
('en', 'user', 'create-listing', 'txt_additional_categories', 'Additional Categories'),
('en', 'user', 'edit-listing', 'txt_primary_category', 'Primary Category'),
('en', 'user', 'edit-listing', 'txt_additional_categories', 'Additional Categories'),
('en', 'public', 'global', 'txt_nearby', 'Nearby'),
('en', 'public', 'global', 'txt_enable_geo', 'Geolocation is not enabled. Please enable to use this feature'),
('en', 'admin', 'settings', 'txt_nearby_filter_values', 'Nearby filter values(separated by semicolon)'),
('en', 'admin', 'settings', 'txt_distance_unit', 'Distance unit'),
('en', 'public', 'global', 'txt_filters', 'Filters'),
('en', 'public', 'global', 'txt_from', 'From'),
('en', 'public', 'global', 'txt_to', 'To'),
('en', 'admin', 'admin-global', 'txt_filter', 'Filter'),
('en', 'admin', 'admin-global', 'txt_show', 'Show'),
('en', 'admin', 'custom-fields', 'txt_all_categories', 'All categories'),
('en', 'admin', 'custom-fields', 'txt_all_groups', 'All groups'),
('en', 'admin', 'custom-fields', 'txt_create_group', 'Create group'),
('en', 'admin', 'custom-fields', 'txt_default_group', 'Default group'),
('en', 'admin', 'custom-fields', 'txt_edit_group', 'Edit group'),
('en', 'admin', 'custom-fields', 'txt_field_group', 'Field group'),
('en', 'admin', 'custom-fields', 'txt_fields', 'Fields'),
('en', 'admin', 'custom-fields', 'txt_fields_removed', 'Fields removed successfully'),
('en', 'admin', 'custom-fields', 'txt_group', 'Group'),
('en', 'admin', 'custom-fields', 'txt_group_created', 'Group created'),
('en', 'admin', 'custom-fields', 'txt_group_name', 'Group name'),
('en', 'admin', 'custom-fields', 'txt_group_order', 'Group order'),
('en', 'admin', 'custom-fields', 'txt_group_restored', 'Group restored successfully'),
('en', 'admin', 'custom-fields', 'txt_groups', 'Groups'),
('en', 'admin', 'custom-fields', 'txt_groups_removed', 'Groups removed successfully'),
('en', 'admin', 'custom-fields', 'txt_remove_fields_sure', 'Are you sure you want to remove these fields permanently? This action cannot be undone.'),
('en', 'admin', 'custom-fields', 'txt_remove_group', 'Remove group'),
('en', 'admin', 'custom-fields', 'txt_remove_group_sure', 'Are you sure you want to remove this group permanently? This action cannot be undone.'),
('en', 'admin', 'custom-fields', 'txt_remove_groups_sure', 'Are you sure you want to remove these groups permanently? This action cannot be undone.'),
('en', 'admin', 'custom-fields', 'txt_show_icon', 'Show field icon'),
('en', 'admin', 'custom-fields', 'txt_show_in_results', 'Show in results'),
('en', 'admin', 'custom-fields', 'txt_show_name', 'Show field name'),
('en', 'admin', 'custom-fields', 'txt_show_name_icon', 'Show field name and icon'),
('en', 'admin', 'custom-fields', 'txt_value_unit', 'Unit of measurement'),
('en', 'admin', 'listings', 'txt_featured_home', 'Featured on the homepage'),
('en', 'admin', 'locations', 'txt_city_photo', 'City photo'),
('en', 'admin', 'settings', 'txt_date_format', 'Date format'),
('en', 'admin', 'settings', 'txt_decimal_separator', 'Decimal separator'),
('en', 'admin', 'settings', 'txt_example', 'Example'),
('en', 'admin', 'settings', 'txt_from_email', 'From Email'),
('en', 'admin', 'settings', 'txt_languages', 'Languages (Ex: en,es,de)'),
('en', 'admin', 'settings', 'txt_latest_listings_count', 'Latest listings count'),
('en', 'admin', 'settings', 'txt_logo_width', 'Logo width'),
('en', 'admin', 'settings', 'txt_near_listings_radius', 'Near listings radius'),
('en', 'admin', 'settings', 'txt_new_sign_up_notification', 'New sign up notification'),
('en', 'admin', 'settings', 'txt_use_select2', 'Use Select2'),
('en', 'admin', 'users', 'txt_toggle_active', 'Toggle active/inactive'),
('en', 'user', 'my-profile', 'txt_email_already_in_use', 'This email is already in use.')
;

--
-- Table structure for table `translation_cf`
--

DROP TABLE IF EXISTS `translation_cf`;
CREATE TABLE `translation_cf` (
  `id` int(11) UNSIGNED NOT NULL,
  `lang` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `field_name` text COLLATE utf8mb4_unicode_ci,
  `tooltip` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `values_list` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `translation_cf`
--
ALTER TABLE `translation_cf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`),
  ADD KEY `field_id` (`field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `translation_cf`
--
ALTER TABLE `translation_cf`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `translation_cf`
--
ALTER TABLE `translation_cf`
  ADD CONSTRAINT `translation_cf_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Update table `email_templates`
--

UPDATE `email_templates` SET `available_vars` = '%site_name%\n%site_url%\n%listing_link%' WHERE `type` IN('subscr_signup','web_accept');

--
-- Indexes for table `places`
--

ALTER TABLE `places` ADD INDEX(`paid`);