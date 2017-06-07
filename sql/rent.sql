-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2017 at 05:20 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_electricity_bills`
--

CREATE TABLE IF NOT EXISTS `tbl_electricity_bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `billing_month_year` date NOT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `units_used` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tbl_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `expense_type` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date_of_expense` date DEFAULT NULL,
  `notes` text,
  `payment_method` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_expense_types`
--

CREATE TABLE IF NOT EXISTS `tbl_expense_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_of_expense` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_guests`
--

CREATE TABLE IF NOT EXISTS `tbl_guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(255) NOT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_incomes`
--

CREATE TABLE IF NOT EXISTS `tbl_incomes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rent_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `income_type` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_of_income` date DEFAULT NULL,
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rent_amount_received` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


--
-- Table structure for table `tbl_income_types`
--

CREATE TABLE IF NOT EXISTS `tbl_income_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_of_income` varchar(255) DEFAULT NULL,
  `is_edit` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_income_types`
--

INSERT INTO `tbl_income_types` (`id`, `type_of_income`, `is_edit`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Advance', 0, 1, '2017-03-02 11:27:24', '2017-03-02 05:57:29'),
(2, 'Rent', 0, 1, '2017-03-07 11:36:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus`
--

CREATE TABLE IF NOT EXISTS `tbl_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_icon` varchar(255) DEFAULT NULL,
  `menu_link` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_order` int(11) DEFAULT NULL,
  `active_menu_id` int(11) DEFAULT NULL,
  `is_child` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `tbl_menus`
--

INSERT INTO `tbl_menus` (`id`, `menu_name`, `menu_icon`, `menu_link`, `parent_id`, `menu_order`, `active_menu_id`, `is_child`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'home', 'home', 0, 1, 1, 0, 1, '2017-03-08 12:22:57', '2017-03-08 12:22:57'),
(2, 'Guests', 'user', 'guests', 0, 2, 2, 0, 1, '2017-03-08 12:22:57', '2017-03-08 12:22:57'),
(3, 'Expense types', 'plus-circle', 'expense-types', 0, 3, 3, 0, 1, '2017-03-08 12:23:53', '2017-03-08 12:23:53'),
(4, 'Income types', 'plus', 'income-types', 0, 4, 4, 0, 1, '2017-03-08 12:23:53', '2017-03-08 12:23:53'),
(5, 'Incomes', 'rupee', 'incomes', 0, 5, 5, 0, 1, '2017-03-08 12:24:24', '2017-03-08 12:24:24'),
(6, 'Expenses', 'eject', 'expenses', 0, 6, 6, 0, 1, '2017-03-08 12:24:24', '2017-03-08 12:24:24'),
(7, 'Rents', 'home', NULL, 0, 7, NULL, 1, 1, '2017-03-08 12:24:49', '2017-03-08 12:24:49'),
(8, 'Settings', 'cogs', 'settings', 0, 9, 8, 0, 1, '2017-03-08 12:24:49', '2017-03-08 12:24:49'),
(9, 'Rooms', 'bed', 'rooms', 0, 10, 9, 0, 1, '2017-03-08 12:25:09', '2017-03-08 12:25:09'),
(10, 'Reports', 'bar-chart', 'reports', 0, 11, NULL, 1, 1, '2017-03-08 12:30:44', '2017-03-08 12:30:44'),
(11, 'Incomes', 'rupee', 'reports/incomes', 10, 1, 11, 0, 1, '2017-03-08 12:31:48', '2017-03-08 12:31:48'),
(12, 'Expenses', 'shopping-cart', 'reports/expenses', 10, 2, 12, 0, 1, '2017-03-08 12:32:37', '2017-03-08 12:32:37'),
(13, 'Electricity bills', 'rupee', 'rents/get-bill-monthly', 7, 3, 13, 0, 1, '2017-03-30 14:17:43', '2017-03-30 14:17:43'),
(14, 'Room', 'bed', 'reports/rooms', 10, 3, 14, 0, 1, '2017-04-05 09:39:41', '2017-04-05 09:39:41'),
(15, 'Rents', 'history', 'reports/rents', 10, 4, 15, 0, 1, '2017-04-07 08:59:53', '2017-04-07 08:59:53'),
(16, 'Rents list', 'book', 'rents', 7, 1, 16, 0, 1, '2017-04-13 09:51:43', '2017-04-13 09:51:43'),
(17, 'Edit rents', 'pencil', 'rents/list-update', 7, 2, NULL, 0, 0, '2017-04-13 09:51:43', '2017-04-13 09:51:43'),
(18, 'Rent', 'calendar', 'rents/get-rent-monthly', 7, 4, 18, 0, 1, '2017-05-25 13:05:44', '2017-05-25 13:05:44'),
(19, 'Messages', 'envelope', 'message/index', 0, 12, 19, 0, 1, '2017-06-06 13:19:36', '2017-06-06 13:19:36'),
(20, 'Guest edit', NULL, 'guests/{guests}/edit', NULL, NULL, 2, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(21, 'Guest destroy', NULL, 'guests/{id}/destroy', NULL, NULL, 2, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(22, 'Expense types edit', NULL, 'expense-types/{expense_types}/edit', NULL, NULL, 3, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(23, 'Expense types destroy', NULL, 'expense-types/id/destroy', NULL, NULL, 3, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(24, 'Income types edit', NULL, 'income-types/{income_types}/edit', NULL, NULL, 4, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(25, 'Income types destroy', NULL, 'income-types/{id}/destroy', NULL, NULL, 4, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(26, 'Incomes edit', NULL, 'incomes/{incomes}/edit', NULL, NULL, 5, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(27, 'Incomes destroy', NULL, 'incomes/{id}/destroy', NULL, NULL, 5, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(28, 'Expenses edit', NULL, 'expenses/{expenses}/edit', NULL, NULL, 6, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(29, 'Expenses destroy', NULL, 'expenses/{id}/destroy', NULL, NULL, 6, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(30, 'Rents edit', NULL, 'rents/{room_id}/rent-edit', NULL, NULL, 16, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(31, 'Rooms edit', NULL, 'rooms/{rooms}/edit', NULL, NULL, 9, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(32, 'Rooms destroy', NULL, 'rooms/{id}/destroy', NULL, NULL, 9, 0, 0, '2017-06-06 14:00:18', '0000-00-00 00:00:00'),
(33, 'Change password', 'key', 'users/change-password', 0, 13, 33, 0, 1, '2017-06-07 07:05:00', '2017-06-07 07:05:00'),
(34, 'Guests create', NULL, 'guests/create', NULL, NULL, 2, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(35, 'Expense types create', NULL, 'expense-types/create', NULL, NULL, 3, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(36, 'Income types create', NULL, 'Income-types/create', NULL, NULL, 4, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(37, 'Incomes create', NULL, 'incomes/create', NULL, NULL, 5, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(38, 'Expenses create', NULL, 'expenses/create', NULL, NULL, 6, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(39, 'Rents create', NULL, 'rents/create', NULL, NULL, 16, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(40, 'Rooms create', NULL, 'rooms/create', NULL, NULL, 9, 0, 0, '2017-06-07 09:26:29', '0000-00-00 00:00:00'),
(41, 'Rent edit by rentid', NULL, 'rents/{rents}/edit', NULL, NULL, 16, 0, 0, '2017-06-07 10:38:13', '2017-06-07 10:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_permissions`
--

CREATE TABLE IF NOT EXISTS `tbl_menu_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_menu_permissions`
--

INSERT INTO `tbl_menu_permissions` (`id`, `menu_id`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2017-06-06 14:43:26', '2017-06-06 14:43:26'),
(2, 3, 2, 1, '2017-06-06 15:18:29', '2017-06-06 15:18:29'),
(3, 22, 2, 1, '2017-06-06 15:18:29', '2017-06-06 15:18:29'),
(4, 23, 2, 1, '2017-06-06 15:18:40', '2017-06-06 15:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE IF NOT EXISTS `tbl_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) DEFAULT NULL,
  `rent_income_id` int(11) DEFAULT NULL,
  `date_of_message` date DEFAULT NULL,
  `delivery_status` tinyint(1) NOT NULL DEFAULT '0',
  `error_message` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_migrations`
--

CREATE TABLE IF NOT EXISTS `tbl_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_migrations`
--

INSERT INTO `tbl_migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_password_resets`
--

CREATE TABLE IF NOT EXISTS `tbl_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rents`
--

CREATE TABLE IF NOT EXISTS `tbl_rents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `is_incharge` tinyint(1) NOT NULL DEFAULT '0',
  `incharge_set` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `rent_amount` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_rent_incomes`
--

CREATE TABLE IF NOT EXISTS `tbl_rent_incomes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rent_id` int(11) DEFAULT NULL,
  `date_of_rent` date DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `no_of_person` int(11) DEFAULT NULL,
  `electricity_amount` int(255) NOT NULL,
  `type_of_rent` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE IF NOT EXISTS `tbl_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`id`, `role_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 1, '2017-03-08 12:05:51', '2017-03-08 12:05:51'),
(2, 'manager', 1, '2017-03-08 12:05:51', '2017-03-08 12:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rooms`
--

CREATE TABLE IF NOT EXISTS `tbl_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(255) DEFAULT NULL,
  `room_no` int(11) DEFAULT NULL,
  `max_persons_allowed` int(11) DEFAULT NULL,
  `rent_amount_person` int(11) NOT NULL,
  `total_rent_amount` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE IF NOT EXISTS `tbl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'title', 'Rent', '2017-03-06 14:29:48', '2017-03-06 09:16:45'),
(2, 'small_title', 'RT', '2017-06-07 09:48:00', '2017-06-07 09:48:00'),
(3, 'electricity_bill_units', '12', '2017-03-06 14:29:48', '2017-03-06 09:16:45'),
(4, 'admin_email', 'sample12@mail.com', '2017-03-06 14:29:48', '2017-03-06 09:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `role_id`, `username`, `name`, `email`, `password`, `avatar`, `remember_token`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'Admin', 'admin@mail.com', '$2y$10$GPvU1a3X4sdfCrAXcF5Ase/TifNx.QZ8KLe9beEKFEAlzipelY3Mi', 'avatar.jpg', 'TF0IMXjTwmr23wa9KNga0xhkKIscOOILn7K2W29ec5TVukH1mmuo0lBY7PKZ', 1, '2017-02-28 07:22:27', '2017-06-07 04:52:57'),
(2, 2, 'manager', 'Manager', 'manager@mail.com', '$2y$10$HauB9Iw9uvALGUv9OSpNYe3afi6mDh4cdgCHrtHQ1GvyNF/kfUi3y', NULL, 'w6hQVALXBIfloEhja1zBRPEnflBCqMlEIird1vfDw5aupGa5YGdO1ZazbPk8', 1, NULL, '2017-06-07 04:53:15');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
