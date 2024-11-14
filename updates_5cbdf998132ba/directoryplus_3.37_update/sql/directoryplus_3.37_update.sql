--
-- Table custom_fields_groups, create table
--

CREATE TABLE `custom_fields_groups` (
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

--
-- Table custom_fields, add new columns value_unit, show_in_results and field_group
--

ALTER TABLE `custom_fields` ADD `value_unit` VARCHAR(50) NOT NULL DEFAULT '' AFTER `values_list`;

ALTER TABLE `custom_fields` ADD `show_in_results` VARCHAR(10) NOT NULL DEFAULT 'no' COMMENT 'Possible values: no, name, icon, name-icon' AFTER `searchable`;

ALTER TABLE `custom_fields` ADD `field_group` INT(11) NOT NULL DEFAULT '1' AFTER `show_in_results`;

--
-- Table language, fix strings
--

UPDATE `language` SET var_name='txt_html_title' WHERE var_name='txt_show_html_title';
UPDATE `language` SET var_name='txt_main_title' WHERE var_name='txt_show_main_title';
UPDATE `language` SET var_name='txt_create_field' WHERE var_name='txt_show_create_field';
UPDATE `language` SET var_name='txt_edit_field' WHERE var_name='txt_show_edit_field';
UPDATE `language` SET var_name='txt_remove_field' WHERE var_name='txt_show_remove_field';

--
-- Table translation_cf_groups, create table
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
