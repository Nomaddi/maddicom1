-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2020 at 03:10 AM
-- Server version: 10.0.37-MariaDB
-- PHP Version: 7.2.12

--
-- Database: `directoryplus`
--

-- --------------------------------------------------------

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
