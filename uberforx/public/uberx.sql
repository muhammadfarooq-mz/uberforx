-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2015 at 12:40 PM
-- Server version: 5.5.41
-- PHP Version: 5.5.23-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uberNew`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` float(8,2) NOT NULL,
  `longitude` float(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash`
--

CREATE TABLE IF NOT EXISTS `cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` float(8,2) NOT NULL,
  `expiry` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE IF NOT EXISTS `certificates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_type` int(11) NOT NULL,
  `file_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Default', '2015-05-12 01:39:33', '2015-05-12 01:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `dog`
--

CREATE TABLE IF NOT EXISTS `dog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `breed` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `likes` text COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `dog_name_index` (`name`),
  KEY `dog_owner_id_index` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `generic_keywords`
--

CREATE TABLE IF NOT EXISTS `generic_keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `generic_keywords`
--

INSERT INTO `generic_keywords` (`id`, `keyword`, `alias`, `created_at`, `updated_at`) VALUES
(1, 'Provider', 'Provider', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(2, 'User', 'User', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(3, 'Taxi', 'Taxi', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(4, 'Trip', 'Trip', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(5, '$', 'Currency', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(6, 'total_trip', '1', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(7, 'cancelled_trip', '2', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(8, 'total_payment', '3', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(9, 'completed_trip', '4', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(10, 'card_payment', '5', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(11, 'credit_payment', '6', '2015-05-12 01:39:33', '2015-05-12 01:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `icons`
--

CREATE TABLE IF NOT EXISTS `icons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `icon_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `icons`
--

INSERT INTO `icons` (`id`, `icon_name`, `icon_code`, `icon_type`, `created_at`, `updated_at`) VALUES
(1, 'Road', '&#xf018;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(2, 'Star', '&#xf005;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(3, 'Remove', '&#xf00d;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(4, 'Ok', '&#xf00c;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(5, 'Money', '&#xf0d6;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(6, 'Credit Card', '&#xf09d;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(7, 'Inbox', '&#xf01c;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(8, 'Flag', '&#xf024;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(9, 'Plus', '&#xf067;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(10, 'Minus', '&#xf068;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(11, 'Thumbs Up', '&#xf087;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33'),
(12, 'Smile', '&#xf118;', 'fa', '2015-05-12 01:39:33', '2015-05-12 01:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumblob,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

CREATE TABLE IF NOT EXISTS `ledger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL,
  `referral_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_referrals` int(11) NOT NULL,
  `amount_earned` float(8,2) NOT NULL,
  `amount_spent` float(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ledger_owner_id_foreign` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_09_25_044324_create_owners_table', 1),
('2014_09_25_060804_create_dogs_table', 1),
('2014_09_30_014129_create_walker_table', 1),
('2014_10_07_113949_create_review_dog_table', 1),
('2014_10_07_114245_create_review_walker_table', 1),
('2014_10_07_114415_create_walk_location_table', 1),
('2014_10_07_114726_create_walk_table', 1),
('2014_10_07_115301_create_schedule_table', 1),
('2014_10_07_115554_create_schedule_meta_table', 1),
('2014_10_10_025736_create_payment_table', 1),
('2014_10_11_144202_add_note_to_walk_table', 1),
('2014_10_13_024755_add_picture_to_walker_table', 1),
('2014_10_14_052816_add_walker_id_to_schedules_table', 1),
('2014_10_14_142220_add_fields_to_owner', 1),
('2014_10_14_142558_add_fields_to_walker', 1),
('2014_10_15_114904_add_lat_long_to_walker_table', 1),
('2014_10_15_115120_add_endson_seeding_to_meta_table', 1),
('2014_10_17_131510_add_is_confirmed_to_schedules_table', 1),
('2014_10_17_152616_add_meta_id_in_walk', 1),
('2014_10_18_051813_add_owner_id_to_payment', 1),
('2014_10_19_070302_create_request_table', 1),
('2014_10_19_070310_create_request_meta_table', 1),
('2014_10_20_084102_add_availability_on_job', 1),
('2014_10_20_084141_add_lat_long', 1),
('2014_10_20_085531_remove_schedule_id', 1),
('2014_10_20_102804_add_status_flags', 1),
('2014_10_21_013919_replace_walk_id_to_request_id', 1),
('2014_10_21_021438_replace_walk_id_to_reques_id_review_walker_table', 1),
('2014_10_21_021816_add_is_rated_in_walk', 1),
('2014_10_21_023844_replace_walk_id_to_reques_id_walk_location_table', 1),
('2014_10_23_033257_create_settings_table', 1),
('2014_10_24_050705_add_payment_fileds_to_request', 1),
('2014_10_27_112457_change_lat_long_data_type', 1),
('2014_10_27_112629_change_lat_long_data_type_walk_location', 1),
('2014_10_27_112915_add_lat_long_data_type_walker', 1),
('2014_10_27_112953_add_lat_long_data_type', 1),
('2014_11_01_015046_create_admin_table', 1),
('2014_11_01_015258_add_is_approved_to_walker', 1),
('2014_11_09_154756_add_information_table', 1),
('2014_11_09_181432_add_referal_data_to_owner', 1),
('2014_11_09_181525_add_ledger_table', 1),
('2014_11_10_035803_add_walker_type_table', 1),
('2014_11_10_040329_add_type_to_walker', 1),
('2014_11_13_064410_add_icon_to_type', 1),
('2014_11_13_064452_add_icon_to_info', 1),
('2014_11_17_052356_add_customerid', 1),
('2014_11_17_134313_add_paymen_split', 1),
('2014_11_18_111038_add_distance_walk_location', 1),
('2014_11_19_001415_change_value_datatype', 1),
('2014_11_19_001841_add_value_datatype', 1),
('2014_11_21_115919_remove_dog_id', 1),
('2014_11_21_115930_remove_dog_id_review', 1),
('2014_11_21_130810_add_is_cancelled_request', 1),
('2014_11_21_131108_add_is_cancelled', 1),
('2014_11_25_112910_add_tip_page', 1),
('2014_11_26_025409_add_last_four', 1),
('2014_12_03_170427_add_foreign_key_dog', 1),
('2014_12_03_171436_add_foreign_key_ledger', 1),
('2014_12_03_171732_add_foreign_key_payment', 1),
('2014_12_03_172008_add_foreign_key_request', 1),
('2014_12_03_172703_add_foreign_key_request_meta', 1),
('2014_12_03_172949_add_foreign_key_review_dog', 1),
('2014_12_03_173126_add_foreign_key_review_dog_2', 1),
('2014_12_03_173221_add_foreign_key_review_dog_3', 1),
('2014_12_03_174014_add_foreign_key_review_walker', 1),
('2014_12_03_174427_add_foreign_key_walk_location', 1),
('2014_12_08_121851_add_documents_table', 1),
('2014_12_08_130512_add_document_type_table', 1),
('2014_12_14_114805_add_type_to_request', 1),
('2014_12_17_132347_update_walker_table', 1),
('2014_12_26_111728_create_walker_services_table', 1),
('2014_12_26_115353_create_request_services_table', 1),
('2014_12_26_115511_remove_fields_from_walker_type_table', 1),
('2014_12_27_023550_add_fields_to_walker_services_table', 1),
('2014_12_27_045844_add_payment_fields_to_request_services_table', 1),
('2014_12_27_050208_delete_payment_fields_from_request_table', 1),
('2014_12_27_142242_delete_type_and_add_refund_request_table', 1),
('2014_12_27_150623_create_theme_table', 1),
('2014_12_29_124126_update_theme_table', 1),
('2014_12_31_172311_add_card_token_to_payment', 1),
('2015_01_02_125640_add_card_id_to_walker', 1),
('2015_01_10_110711_create_installation_settings_table', 1),
('2015_01_22_113756_add_transfer_to_request', 1),
('2015_02_02_114411_add_is_default_to_payment', 1),
('2015_02_09_192206_add__debt_to_owner', 1),
('2015_02_10_100336_create_provider_availability', 1),
('2015_02_18_095619_add_later_in_request', 1),
('2015_02_19_121041_index_owener_table', 1),
('2015_02_19_123457_index_dog_table', 1),
('2015_02_19_124305_index_walker_table', 1),
('2015_02_19_124904_index_request_table', 1),
('2015_02_19_134856_index_requestServices_table', 1),
('2015_02_19_135324_index_walkerServices_table', 1),
('2015_02_26_104212_addDestinationToRequests', 1),
('2015_02_26_110951_add_cod_to_request_table', 1),
('2015_03_02_123731_alter_fields_in_cod_to_payment_mode_in_request', 1),
('2015_03_03_071753_add_payment_id_to_request_table', 1),
('2015_03_12_060741_add_new_field_in_walker', 1),
('2015_03_16_101246_alter_datatype_for_payment_id', 1),
('2015_03_19_072803_add_promo_code_to_request_table', 1),
('2015_03_19_074736_add_promo_codes_table', 1),
('2015_03_20_065250_add_walker_table_default_value', 1),
('2015_03_20_065758_alter_walker_table_banking_details', 1),
('2015_03_20_092439_add_field_activation_provider', 1),
('2015_03_23_120633_add_timezone_to_user_table', 1),
('2015_03_23_121121_add_timezone_to_provider_table', 1),
('2015_03_27_140947_add_generic_keywords_table', 1),
('2015_03_31_194143_add_delete_to_walker_table', 1),
('2015_03_31_195348_add_soft_delete_to_owner_table', 1),
('2015_04_01_093144_add_alter_datatype_information_content', 1),
('2015_04_02_123726_create_icons_table', 1),
('2015_04_06_175511_create_certificates_table', 1),
('2015_04_06_183013_add_user_to_certificates_table', 1),
('2015_04_07_105412_add_file_type_to_certifcates', 1),
('2015_04_12_170912_add_default_to_certificates', 1),
('2015_04_14_192343_alter_security_key_datatype_in_request_table', 1),
('2015_04_15_110351_create_promo_history_table', 1),
('2015_04_29_103838_makeNewCashtable', 1),
('2015_05_05_124752_addLocationToAdmin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dog_id` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_expiry` int(11) NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` enum('android','ios') COLLATE utf8_unicode_ci NOT NULL,
  `login_by` enum('manual','facebook','google') COLLATE utf8_unicode_ci NOT NULL,
  `social_unique_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `referred_by` int(11) NOT NULL,
  `debt` float(8,2) NOT NULL DEFAULT '0.00',
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UTC',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `owner_id` int(10) unsigned NOT NULL,
  `customer_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_four` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `payment_owner_id_foreign` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE IF NOT EXISTS `promo_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `uses` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `promo_history`
--

CREATE TABLE IF NOT EXISTS `promo_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promo_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount_earned` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `provider_availability`
--

CREATE TABLE IF NOT EXISTS `provider_availability` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `confirmed_walker` int(11) NOT NULL,
  `current_walker` int(11) NOT NULL,
  `request_start_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_walker_started` int(11) NOT NULL,
  `is_walker_arrived` int(11) NOT NULL,
  `is_started` int(11) NOT NULL,
  `is_completed` int(11) NOT NULL,
  `is_dog_rated` int(11) NOT NULL,
  `is_walker_rated` int(11) NOT NULL,
  `distance` float(8,2) NOT NULL,
  `time` float(8,2) NOT NULL,
  `total` float(8,2) NOT NULL,
  `is_paid` int(11) NOT NULL,
  `card_payment` float(8,2) NOT NULL,
  `ledger_payment` float(8,2) NOT NULL,
  `is_cancelled` int(11) NOT NULL,
  `refund` float(8,2) NOT NULL DEFAULT '0.00',
  `transfer_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `later` tinyint(1) NOT NULL DEFAULT '0',
  `D_latitude` double(15,8) DEFAULT NULL,
  `D_longitude` double(15,8) DEFAULT NULL,
  `security_key` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_mode` int(11) NOT NULL DEFAULT '0',
  `payment_id` text COLLATE utf8_unicode_ci,
  `promo_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_owner_id_foreign` (`owner_id`),
  KEY `request_is_walker_started_index` (`is_walker_started`),
  KEY `request_is_walker_arrived_index` (`is_walker_arrived`),
  KEY `request_is_started_index` (`is_started`),
  KEY `request_is_completed_index` (`is_completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_meta`
--

CREATE TABLE IF NOT EXISTS `request_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` int(10) unsigned NOT NULL,
  `walker_id` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_cancelled` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_meta_request_id_foreign` (`request_id`),
  KEY `request_meta_walker_id_foreign` (`walker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_services`
--

CREATE TABLE IF NOT EXISTS `request_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `base_price` float(8,2) NOT NULL DEFAULT '0.00',
  `distance_cost` float(8,2) NOT NULL DEFAULT '0.00',
  `time_cost` float(8,2) NOT NULL DEFAULT '0.00',
  `total` float(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `request_services_request_id_index` (`request_id`),
  KEY `request_services_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_dog`
--

CREATE TABLE IF NOT EXISTS `review_dog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `walker_id` int(10) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `review_dog_owner_id_foreign` (`owner_id`),
  KEY `review_dog_walker_id_foreign` (`walker_id`),
  KEY `review_dog_request_id_foreign` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_walker`
--

CREATE TABLE IF NOT EXISTS `review_walker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `walker_id` int(10) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `review_walker_owner_id_foreign` (`owner_id`),
  KEY `review_walker_walker_id_foreign` (`walker_id`),
  KEY `review_walker_request_id_foreign` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dog_id` int(11) NOT NULL,
  `lockbox_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_recurring` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `walker_id` int(11) NOT NULL,
  `is_confirmed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_meta`
--

CREATE TABLE IF NOT EXISTS `schedule_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `ends_on` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `started_on` float(8,2) NOT NULL,
  `seeding_status` float(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tool_tip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `created_at`, `updated_at`, `value`, `tool_tip`, `page`) VALUES
(1, 'default_distance_unit', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '0', 'This is the default unit of distance', 1),
(2, 'default_charging_method_for_users', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '1', 'Default Changing method for users', 1),
(3, 'base_price', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '50', 'Incase of Fixed price payment, Base price is the total amount thats charged to users', 1),
(4, 'price_per_unit_distance', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '10', 'Needed only incase of time and distance based payment', 1),
(5, 'price_per_unit_time', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '8', 'Needed only incase of time and distance based payment', 1),
(6, 'provider_timeout', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '60', 'Maximum time for provider to respond for a request', 1),
(7, 'sms_notification', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '0', 'Send SMS Notifications', 1),
(8, 'email_notification', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '1', 'Send Email Notifications', 1),
(9, 'push_notification', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '1', 'Send Push Notifications', 1),
(10, 'default_referral_bonus', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '10', 'Bonus credit that should be added incase if user refers another', 1),
(11, 'admin_phone_number', '2015-05-12 01:39:31', '2015-05-12 01:39:31', '+917708288018', 'This mobile number will get SMS notifications about requests', 1),
(12, 'admin_email_address', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'prabakaranbs@gmail.com', 'This address will get Email notifications about requests', 1),
(13, 'sms_when_provider_accepts', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Hi %user%, Your request is accepted by %driver%. You can reach him by %driver_mobile%', 'This Template will be used to notify user by SMS when a provider the accepts request', 2),
(14, 'sms_when_provider_arrives', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Hi %user%, The %driver% has arrived at your location.You can reach user by %driver_mobile%', 'This Template will be used to notify user by SMS when a provider the arrives', 2),
(15, 'sms_when_provider_completes_job', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Hi %user%, Your request is successfully completed by %driver%. Your Bill amount id %amount%', 'This Template will be used to notify user by SMS when a provider the completes the service', 2),
(16, 'sms_request_created', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request id %id% is created by %user%, You can reach him by %user_mobile%', 'This Template will be used to notify admin by SMS when a new request is created', 2),
(17, 'sms_request_unanswered', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request id %id% created by %user% is left unanswered, You can reach user by %user_mobile%', 'This Template will be used to notify admin by SMS when a request remains unanswered by all providers', 2),
(18, 'sms_request_completed', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request id %id% created by %user% is completed, You can reach user by %user_mobile%', 'This Template will be used to notify admin by SMS when a request is completed', 2),
(19, 'sms_payment_generated', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Payment for Request id %id% is generated.', 'This Template will be used to notify admin by SMS when payment is generated for a request', 2),
(20, 'email_forgot_password', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Your New Password is %password%. Please dont forget to change the password once you log in next time.', 'This Template will be used to notify users and providers by email when they reset their password', 3),
(21, 'email_walker_new_registration', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Welcome on Board %name% , After Logged in to your account Upload your documents to get approve from the admin side , Please Activation your Email here %link% . Upload your documents and someone will look into your application and get back.', 'This Template will be used for welcome mail to provider', 3),
(22, 'email_owner_new_registration', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Welcome on Board %name%', 'This Template will be used for welcome mail to user', 3),
(23, 'email_new_request', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'New Request %id% is created. Follow the request through %url%', 'This Template will be used notify admin by email when a new request is created', 3),
(24, 'email_request_unanswered', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request %id% has beed declined by all providers. Follow the request through %url%', 'This Template will be used notify admin by email when a request remains unanswerd by all providers', 3),
(25, 'email_request_finished', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request %id% is finished. Follow the request through %url%', 'This Template will be used notify admin by email when a request is completed', 3),
(26, 'email_payment_charged', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Request %id% is finished. Follow the request through %url%', 'This Template will be used notify admin by email when a client is charged for a request', 3),
(27, 'email_invoice_generated_user', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'invoice for Request id %id% is generated. Total amount is %amount%', 'This Template will be used notify user by email when invoice is generated', 3),
(28, 'email_invoice_generated_provider', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'invoice for Request id %id% is generated. Total amount is %amount%', 'This Template will be used notify provider by email when invoice is generated', 3),
(29, 'map_center_latitude', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '0', 'This is latitude for the map center', 1),
(30, 'map_center_longitude', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '0', 'This is longitude for the map center', 1),
(31, 'default_search_radius', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '5', 'Defalt search radius to look for providers', 1),
(32, 'provider_selection', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '1', 'Automatically assign provider or manually select from a displayed list of all providers', 4),
(33, 'service_fee', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '10', 'Service Fee Amount', 4),
(34, 'payment_made_client', '2015-05-12 01:39:32', '2015-05-12 01:39:32', 'Payment has been made for Request id %id%. Total amount is %amount%', 'This Template will be used notify provider by email when payment has been made', 3),
(35, 'transfer', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '0', 'Transfer', 7),
(36, 'allow_calendar', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '1', 'Allow Calendar', 7),
(37, 'cod', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '1', 'Pay by Cash', 8),
(38, 'paypal', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '0', 'Pay by Paypal', 8),
(39, 'promo_code', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '0', 'Promo Code Allowed', 8),
(40, 'get_destination', '2015-05-12 01:39:32', '2015-05-12 01:39:32', '1', 'Allow or not to get Destination', 3),
(41, 'allow_multiple_service', '2015-05-12 01:39:33', '2015-05-12 01:39:33', '0', 'Enable/Disable multiple service select', 3);

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `theme_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `primary_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secondary_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hover_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `favicon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `walk`
--

CREATE TABLE IF NOT EXISTS `walk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `dog_id` int(11) NOT NULL,
  `walker_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_walker_rated` int(11) NOT NULL,
  `is_dog_rated` int(11) NOT NULL,
  `is_confirmed` int(11) NOT NULL,
  `is_started` int(11) NOT NULL,
  `is_completed` int(11) NOT NULL,
  `is_cancelled` int(11) NOT NULL,
  `distance` float(8,2) NOT NULL,
  `time` int(11) NOT NULL,
  `is_poo` int(11) NOT NULL,
  `is_pee` int(11) NOT NULL,
  `photo_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `walker`
--

CREATE TABLE IF NOT EXISTS `walker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` enum('android','ios') COLLATE utf8_unicode_ci NOT NULL,
  `login_by` enum('manual','facebook','google') COLLATE utf8_unicode_ci NOT NULL,
  `social_unique_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_activation` int(11) NOT NULL,
  `token_expiry` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` int(11) NOT NULL,
  `is_available` int(11) DEFAULT '1',
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `is_approved` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `merchant_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_id` text COLLATE utf8_unicode_ci,
  `last_4` text COLLATE utf8_unicode_ci,
  `activation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UTC',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `walker_email_index` (`email`),
  KEY `walker_first_name_index` (`first_name`),
  KEY `walker_last_name_index` (`last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `walker_documents`
--

CREATE TABLE IF NOT EXISTS `walker_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `walker_id` int(11) NOT NULL,
  `document_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `walker_services`
--

CREATE TABLE IF NOT EXISTS `walker_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `price_per_unit_distance` float(8,2) NOT NULL DEFAULT '0.00',
  `price_per_unit_time` float(8,2) NOT NULL DEFAULT '0.00',
  `base_price` float(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `walker_services_provider_id_index` (`provider_id`),
  KEY `walker_services_type_index` (`type`),
  KEY `walker_services_price_per_unit_distance_index` (`price_per_unit_distance`),
  KEY `walker_services_price_per_unit_time_index` (`price_per_unit_time`),
  KEY `walker_services_base_price_index` (`base_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `walker_type`
--

CREATE TABLE IF NOT EXISTS `walker_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `walker_type`
--

INSERT INTO `walker_type` (`id`, `name`, `is_default`, `created_at`, `updated_at`, `icon`) VALUES
(1, 'Default', 1, '2015-05-12 01:39:33', '2015-05-12 01:39:33', '');

-- --------------------------------------------------------

--
-- Table structure for table `walk_location`
--

CREATE TABLE IF NOT EXISTS `walk_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_id` int(10) unsigned NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `distance` float(8,3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `walk_location_request_id_foreign` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dog`
--
ALTER TABLE `dog`
  ADD CONSTRAINT `dog_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`);

--
-- Constraints for table `ledger`
--
ALTER TABLE `ledger`
  ADD CONSTRAINT `ledger_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`);

--
-- Constraints for table `request_meta`
--
ALTER TABLE `request_meta`
  ADD CONSTRAINT `request_meta_walker_id_foreign` FOREIGN KEY (`walker_id`) REFERENCES `walker` (`id`),
  ADD CONSTRAINT `request_meta_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`);

--
-- Constraints for table `review_dog`
--
ALTER TABLE `review_dog`
  ADD CONSTRAINT `review_dog_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `review_dog_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`),
  ADD CONSTRAINT `review_dog_walker_id_foreign` FOREIGN KEY (`walker_id`) REFERENCES `walker` (`id`);

--
-- Constraints for table `review_walker`
--
ALTER TABLE `review_walker`
  ADD CONSTRAINT `review_walker_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `review_walker_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`),
  ADD CONSTRAINT `review_walker_walker_id_foreign` FOREIGN KEY (`walker_id`) REFERENCES `walker` (`id`);

--
-- Constraints for table `walk_location`
--
ALTER TABLE `walk_location`
  ADD CONSTRAINT `walk_location_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
