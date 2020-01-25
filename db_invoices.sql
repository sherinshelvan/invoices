-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2020 at 11:15 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_invoices`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `gstin_no` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_clients`
--

INSERT INTO `tbl_clients` (`id`, `name`, `email`, `phone`, `address`, `gstin_no`, `created_by`, `created_date`, `active`) VALUES
(1, 'Client One', 'clientone12@gmail.com', '123456', 'address123456', '12', 1, '2020-01-22 15:43:49', '1'),
(2, 'Client Two', 'sherin.ks@sreyas.com', '123456', '', '1', 1, '2020-01-22 15:43:59', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currency`
--

CREATE TABLE `tbl_currency` (
  `id` int(11) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `symbol` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_currency`
--

INSERT INTO `tbl_currency` (`id`, `country`, `currency`, `code`, `symbol`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek'),
(2, 'America', 'Dollars', 'USD', '$'),
(3, 'Afghanistan', 'Afghanis', 'AFN', '?'),
(4, 'Argentina', 'Pesos', 'ARS', '$'),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ'),
(6, 'Australia', 'Dollars', 'AUD', '$'),
(7, 'Azerbaijan', 'New Manats', 'AZN', '???'),
(8, 'Bahamas', 'Dollars', 'BSD', '$'),
(9, 'Barbados', 'Dollars', 'BBD', '$'),
(10, 'Belarus', 'Rubles', 'BYR', 'p.'),
(11, 'Belgium', 'Euro', 'EUR', '€'),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$'),
(13, 'Bermuda', 'Dollars', 'BMD', '$'),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b'),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM'),
(16, 'Botswana', 'Pula', 'BWP', 'P'),
(17, 'Bulgaria', 'Leva', 'BGN', '??'),
(18, 'Brazil', 'Reais', 'BRL', 'R$'),
(19, 'Britain (United Kingdom)', 'Pounds', 'GBP', '£'),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$'),
(21, 'Cambodia', 'Riels', 'KHR', '?'),
(22, 'Canada', 'Dollars', 'CAD', '$'),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$'),
(24, 'Chile', 'Pesos', 'CLP', '$'),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥'),
(26, 'Colombia', 'Pesos', 'COP', '$'),
(27, 'Costa Rica', 'Colón', 'CRC', '?'),
(28, 'Croatia', 'Kuna', 'HRK', 'kn'),
(29, 'Cuba', 'Pesos', 'CUP', '?'),
(30, 'Cyprus', 'Euro', 'EUR', '€'),
(31, 'Czech Republic', 'Koruny', 'CZK', 'K?'),
(32, 'Denmark', 'Kroner', 'DKK', 'kr'),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$'),
(34, 'East Caribbean', 'Dollars', 'XCD', '$'),
(35, 'Egypt', 'Pounds', 'EGP', '£'),
(36, 'El Salvador', 'Colones', 'SVC', '$'),
(37, 'England (United Kingdom)', 'Pounds', 'GBP', '£'),
(38, 'Euro', 'Euro', 'EUR', '€'),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£'),
(40, 'Fiji', 'Dollars', 'FJD', '$'),
(41, 'France', 'Euro', 'EUR', '€'),
(42, 'Ghana', 'Cedis', 'GHC', '¢'),
(43, 'Gibraltar', 'Pounds', 'GIP', '£'),
(44, 'Greece', 'Euro', 'EUR', '€'),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q'),
(46, 'Guernsey', 'Pounds', 'GGP', '£'),
(47, 'Guyana', 'Dollars', 'GYD', '$'),
(48, 'Holland (Netherlands)', 'Euro', 'EUR', '€'),
(49, 'Honduras', 'Lempiras', 'HNL', 'L'),
(50, 'Hong Kong', 'Dollars', 'HKD', '$'),
(51, 'Hungary', 'Forint', 'HUF', 'Ft'),
(52, 'Iceland', 'Kronur', 'ISK', 'kr'),
(53, 'India', 'Rupees', 'INR', 'Rp'),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp'),
(55, 'Iran', 'Rials', 'IRR', '?'),
(56, 'Ireland', 'Euro', 'EUR', '€'),
(57, 'Isle of Man', 'Pounds', 'IMP', '£'),
(58, 'Israel', 'New Shekels', 'ILS', '?'),
(59, 'Italy', 'Euro', 'EUR', '€'),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$'),
(61, 'Japan', 'Yen', 'JPY', '¥'),
(62, 'Jersey', 'Pounds', 'JEP', '£'),
(63, 'Kazakhstan', 'Tenge', 'KZT', '??'),
(64, 'Korea (North)', 'Won', 'KPW', '?'),
(65, 'Korea (South)', 'Won', 'KRW', '?'),
(66, 'Kyrgyzstan', 'Soms', 'KGS', '??'),
(67, 'Laos', 'Kips', 'LAK', '?'),
(68, 'Latvia', 'Lati', 'LVL', 'Ls'),
(69, 'Lebanon', 'Pounds', 'LBP', '£'),
(70, 'Liberia', 'Dollars', 'LRD', '$'),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF'),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt'),
(73, 'Luxembourg', 'Euro', 'EUR', '€'),
(74, 'Macedonia', 'Denars', 'MKD', '???'),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM'),
(76, 'Malta', 'Euro', 'EUR', '€'),
(77, 'Mauritius', 'Rupees', 'MUR', '?'),
(78, 'Mexico', 'Pesos', 'MXN', '$'),
(79, 'Mongolia', 'Tugriks', 'MNT', '?'),
(80, 'Mozambique', 'Meticais', 'MZN', 'MT'),
(81, 'Namibia', 'Dollars', 'NAD', '$'),
(82, 'Nepal', 'Rupees', 'NPR', '?'),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ'),
(84, 'Netherlands', 'Euro', 'EUR', '€'),
(85, 'New Zealand', 'Dollars', 'NZD', '$'),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$'),
(87, 'Nigeria', 'Nairas', 'NGN', '?'),
(88, 'North Korea', 'Won', 'KPW', '?'),
(89, 'Norway', 'Krone', 'NOK', 'kr'),
(90, 'Oman', 'Rials', 'OMR', '?'),
(91, 'Pakistan', 'Rupees', 'PKR', '?'),
(92, 'Panama', 'Balboa', 'PAB', 'B/.'),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs'),
(94, 'Peru', 'Nuevos Soles', 'PEN', 'S/.'),
(95, 'Philippines', 'Pesos', 'PHP', 'Php'),
(96, 'Poland', 'Zlotych', 'PLN', 'z?'),
(97, 'Qatar', 'Rials', 'QAR', '?'),
(98, 'Romania', 'New Lei', 'RON', 'lei'),
(99, 'Russia', 'Rubles', 'RUB', '???'),
(100, 'Saint Helena', 'Pounds', 'SHP', '£'),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '?'),
(102, 'Serbia', 'Dinars', 'RSD', '???.'),
(103, 'Seychelles', 'Rupees', 'SCR', '?'),
(104, 'Singapore', 'Dollars', 'SGD', '$'),
(105, 'Slovenia', 'Euro', 'EUR', '€'),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$'),
(107, 'Somalia', 'Shillings', 'SOS', 'S'),
(108, 'South Africa', 'Rand', 'ZAR', 'R'),
(109, 'South Korea', 'Won', 'KRW', '?'),
(110, 'Spain', 'Euro', 'EUR', '€'),
(111, 'Sri Lanka', 'Rupees', 'LKR', '?'),
(112, 'Sweden', 'Kronor', 'SEK', 'kr'),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF'),
(114, 'Suriname', 'Dollars', 'SRD', '$'),
(115, 'Syria', 'Pounds', 'SYP', '£'),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$'),
(117, 'Thailand', 'Baht', 'THB', '?'),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$'),
(119, 'Turkey', 'Lira', 'TRY', 'TL'),
(120, 'Turkey', 'Liras', 'TRL', '£'),
(121, 'Tuvalu', 'Dollars', 'TVD', '$'),
(122, 'Ukraine', 'Hryvnia', 'UAH', '?'),
(123, 'United Kingdom', 'Pounds', 'GBP', '£'),
(124, 'United States of America', 'Dollars', 'USD', '$'),
(125, 'Uruguay', 'Pesos', 'UYU', '$U'),
(126, 'Uzbekistan', 'Sums', 'UZS', '??'),
(127, 'Vatican City', 'Euro', 'EUR', '€'),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs'),
(129, 'Vietnam', 'Dong', 'VND', '?'),
(130, 'Yemen', 'Rials', 'YER', '?'),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_groups`
--

INSERT INTO `tbl_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'subscriber', 'Subscriber'),
(3, 'staff', 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoices`
--

CREATE TABLE `tbl_invoices` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `invoice_date` date NOT NULL,
  `client_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_details` text NOT NULL,
  `currency` varchar(10) NOT NULL,
  `total_amount` double NOT NULL,
  `items` text NOT NULL,
  `extra_items` text NOT NULL,
  `note` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoices`
--

INSERT INTO `tbl_invoices` (`id`, `invoice_no`, `invoice_date`, `client_id`, `tax_id`, `tax_details`, `currency`, `total_amount`, `items`, `extra_items`, `note`, `created_date`, `created_by`) VALUES
(4, '003', '2020-01-24', 1, 1, '{\"id\":\"1\",\"name\":\"Kerala Tax\",\"taxes\":\"{\\\"0\\\":{\\\"name\\\":\\\"CGST\\\",\\\"type\\\":\\\"percentage\\\",\\\"value\\\":\\\"9\\\"},\\\"1579532235360\\\":{\\\"name\\\":\\\"SGST\\\",\\\"type\\\":\\\"percentage\\\",\\\"value\\\":\\\"10\\\"}}\",\"created_by\":\"1\",\"created_date\":\"2020-01-24 17:07:07\",\"active\":\"1\"}', 'USD', 182, '[{\"name\":\"item one\",\"description\":\"23\",\"unit_type\":\"minutes\",\"unit\":\"1\",\"price\":\"2\"},{\"name\":\"item two\",\"description\":\"\",\"unit_type\":\"quantity\",\"unit\":\"5\",\"price\":\"30\"}]', '[{\"name\":\"expense one\",\"amount\":\"10\"},{\"name\":\"expense two\",\"amount\":\"20\"}]', '321\r\n31', '2020-01-25 09:27:29', 1),
(5, '002', '2020-01-25', 1, 0, 'null', 'USD', 0, '', '', '', '2020-01-25 07:09:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_attempts`
--

CREATE TABLE `tbl_login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `field_name` varchar(150) NOT NULL,
  `value` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `field_name`, `value`) VALUES
(1, 'site_name', 'Subscription System'),
(2, 'site_address', 'site address'),
(3, 'site_logo', 'logo.png'),
(4, 'site_email', 'sherin@gmail.com'),
(5, 'thumbnail_width', '400'),
(6, 'thumbnail_height', '400'),
(8, 'payment_environment', 'sandbox'),
(13, 'fcm_sender_id', '26133650672'),
(14, 'fcm_server_key', 'AAAABhWv3PA:APA91bFkKNDhuZfxBeuD994K1-4JGojPjlLj5q-M7J107-rzuGTwgPJDNWPVucMiJTkBwU3P9e3L167t1Hsp-uelJ3g8Twr7EgNvHFVHXhAsOccG0P9vttdAC1gwF5UxCjn1xGpcHGOU'),
(15, 'stripe_public_key', 'pk_test_OcWK2atLL9Xck8W5j6PBgwAk00tYEFuctW'),
(16, 'stripe_secret_key', 'sk_test_Ak6W2j5zrOZLXyEJULrTuLvK00VMSYBKTY'),
(17, 'currency', 'chf'),
(18, 'smtp_host', 'smtp.gmail.com'),
(19, 'smtp_port', '465'),
(20, 'smtp_user', 'sherin.ks@gmail.com'),
(21, 'smtp_password', 'sherin!23'),
(22, 'invoice_address', 'SyoWare WebSolutions (OPC) Pvt Ltd.\r\nCompany Idetiication Number :\r\nU72501KL2018OPC055147\r\n( Registered with Ministry of Current\r\nAffairs, India)\r\nGSTIN :32ABBCS0139H1ZS\r\nDeependu,Manikkath Road,\r\nPermanoor, Ravipuram, Ernakulam,\r\nKerala - 68 20 16, India'),
(23, 'invoice_currency', 'USD'),
(24, 'invoice_thanks_msg', 'Thank you very much. We really appreciate your\r\nbusiness. We look forward to working with you\r\nagain.'),
(25, 'invoice_bank_account', 'Account Holder:Syoware Websolutions(OPC) Pvt\r\nLtd\r\nBank :Axis Bank Ltd ,\r\nVytilla ,Kochi,\r\nKerala-682019\r\nIndia\r\nAccount Number:919020068890981\r\nIFSC:UTIB0000827\r\nSWIFT Code:AXISINBB081\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taxes`
--

CREATE TABLE `tbl_taxes` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `taxes` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_taxes`
--

INSERT INTO `tbl_taxes` (`id`, `name`, `taxes`, `created_by`, `created_date`, `active`) VALUES
(1, 'Kerala Tax', '{\"0\":{\"name\":\"CGST\",\"type\":\"percentage\",\"value\":\"9\"},\"1579532235360\":{\"name\":\"SGST\",\"type\":\"percentage\",\"value\":\"10\"}}', 1, '2020-01-24 11:37:07', '1'),
(2, 'Rest of india', '[{\"name\":\"GST\",\"type\":\"percentage\",\"value\":\"18\"}]', 1, '2020-01-20 15:09:44', '1'),
(3, 'Outside India', '[{\"name\":\"no tax\",\"type\":\"percentage\",\"value\":\"0\"}]', 1, '2020-01-20 15:10:25', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$12$sdWuccdD7eh57kBVc/nyO.J5Pz2WACWFCTeAuNT2sVaL16yIUL8TC', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1579934028, 1, 'Administrator', '01', 'ADMIN', '0'),
(13, '::1', 'one@gmail.com', '$2y$10$wr2n3XW1ATPPSRgQfxIPnOffMI4CcZEW.Qkoj0z/fjduevGiktmde', 'one@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1579528634, NULL, 1, 'one', 'one', NULL, '123456');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_groups`
--

CREATE TABLE `tbl_users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_groups`
--

INSERT INTO `tbl_users_groups` (`id`, `user_id`, `group_id`) VALUES
(54, 1, 1),
(55, 1, 2),
(56, 1, 3),
(38, 13, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_invoices`
--
ALTER TABLE `tbl_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login_attempts`
--
ALTER TABLE `tbl_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_taxes`
--
ALTER TABLE `tbl_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

--
-- Indexes for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_invoices`
--
ALTER TABLE `tbl_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_login_attempts`
--
ALTER TABLE `tbl_login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_taxes`
--
ALTER TABLE `tbl_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
