-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 05, 2019 at 11:16 AM
-- Server version: 5.7.13-log
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chandra_enterprise`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_engineers`
--

CREATE TABLE `assign_engineers` (
  `id` int(11) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `user_manager_id` int(11) NOT NULL,
  `user_engineer_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reason_date` datetime NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assign_managers`
--

CREATE TABLE `assign_managers` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_manager_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reason_date` datetime NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ph_no` varchar(50) NOT NULL,
  `state` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `pin_code` varchar(50) NOT NULL,
  `contact_person_1` varchar(50) NOT NULL,
  `contact_person_2` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `remarks` text,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `zone_id`, `name`, `email`, `ph_no`, `state`, `district`, `pin_code`, `contact_person_1`, `contact_person_2`, `address`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'client 1', 'client@test.test', '+9187676767', 'Assam', 'Kamrup Rural', '876877', '4665655474', '5475477567', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis, dolore.', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis, dolore. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis, dolore.', 1, '2019-01-29 10:59:47', '2019-01-29 05:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `unique_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ph_no` varchar(50) NOT NULL,
  `state` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `pin_code` varchar(50) DEFAULT NULL,
  `address` text NOT NULL,
  `gst_no` varchar(255) NOT NULL,
  `pan_card_no` varchar(255) NOT NULL,
  `remarks` text,
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `unique_no`, `name`, `email`, `ph_no`, `state`, `district`, `pin_code`, `address`, `gst_no`, `pan_card_no`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, '1000', 'web.com india pvt ltd', 'web@test.test', '+9187686765', 'Assam', 'Kamrup Metropolitan', '876877', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa praesentium placeat quis ea nisi tempora esse magnam eos, qui, rerum nam maiores aperiam aliquid, temporibus possimus dolorum soluta hic earum.', 'gfr4656g6', '666666', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa praesentium placeat quis ea nisi tempora esse magnam eos, qui, rerum nam maiores aperiam aliquid, temporibus possimus dolorum soluta hic earum.', '1', '2019-01-29 12:42:49', '2019-01-29 07:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Anantapur', 1, '2019-01-24 06:16:13', NULL),
(2, 1, 'Chittoor', 1, '2019-01-24 06:19:20', NULL),
(3, 1, 'East Godavari', 1, '2019-01-24 06:19:20', NULL),
(4, 1, 'Guntur', 1, '2019-01-24 06:19:21', NULL),
(5, 1, 'Krishna', 1, '2019-01-24 06:19:21', NULL),
(6, 1, 'Kurnool', 1, '2019-01-24 06:19:21', NULL),
(7, 1, 'Prakasam', 1, '2019-01-24 06:19:21', NULL),
(8, 1, 'Srikakulam', 1, '2019-01-24 06:19:21', NULL),
(9, 1, 'SriPotti Sri Ramulu Nellore', 1, '2019-01-24 06:19:21', NULL),
(10, 1, 'Vishakhapatnam', 1, '2019-01-24 06:19:21', NULL),
(11, 1, 'Vizianagaram', 1, '2019-01-24 06:19:21', NULL),
(12, 1, 'West Godavari', 1, '2019-01-24 06:19:21', NULL),
(13, 1, 'Cudappah', 1, '2019-01-24 06:19:21', NULL),
(14, 2, 'Anjaw', 1, '2019-01-24 06:20:18', NULL),
(15, 2, 'Changlang', 1, '2019-01-24 06:23:44', NULL),
(16, 2, 'Dibang Valley', 1, '2019-01-24 06:23:44', NULL),
(17, 2, 'East Siang', 1, '2019-01-24 06:23:44', NULL),
(18, 2, 'East Kameng', 1, '2019-01-24 06:23:44', NULL),
(19, 2, 'Kurung Kumey', 1, '2019-01-24 06:23:44', NULL),
(20, 2, 'Lohit', 1, '2019-01-24 06:23:44', NULL),
(21, 2, 'Longding', 1, '2019-01-24 06:23:44', NULL),
(22, 2, 'Lower Dibang Valley', 1, '2019-01-24 06:23:44', NULL),
(23, 2, 'Lower Subansiri', 1, '2019-01-24 06:23:44', NULL),
(24, 2, 'Papum Pare', 1, '2019-01-24 06:23:44', NULL),
(25, 2, 'Tawang', 1, '2019-01-24 06:23:44', NULL),
(26, 2, 'Tirap', 1, '2019-01-24 06:23:44', NULL),
(27, 2, 'Upper Siang', 1, '2019-01-24 06:23:45', NULL),
(28, 2, 'Upper Subansiri', 1, '2019-01-24 06:23:45', NULL),
(29, 2, 'West Kameng', 1, '2019-01-24 06:23:45', NULL),
(30, 2, 'West Siang', 1, '2019-01-24 06:23:45', NULL),
(31, 3, 'Baksa', 1, '2019-01-24 06:35:06', NULL),
(32, 3, 'Barpeta', 1, '2019-01-24 06:41:54', NULL),
(33, 3, 'Bongaigaon', 1, '2019-01-24 06:41:55', NULL),
(34, 3, 'Cachar', 1, '2019-01-24 06:41:55', NULL),
(35, 3, 'Chirang', 1, '2019-01-24 06:41:55', NULL),
(36, 3, 'Darrang', 1, '2019-01-24 06:41:55', NULL),
(37, 3, 'Dhemaji', 1, '2019-01-24 06:41:55', NULL),
(38, 3, 'Dima Hasao', 1, '2019-01-24 06:41:55', NULL),
(39, 3, 'Dhubri', 1, '2019-01-24 06:41:55', NULL),
(40, 3, 'Dibrugarh', 1, '2019-01-24 06:41:55', NULL),
(41, 3, 'Goalpara', 1, '2019-01-24 06:41:55', NULL),
(42, 3, 'Golaghat', 1, '2019-01-24 06:41:55', NULL),
(43, 3, 'Hailakandi', 1, '2019-01-24 06:41:55', NULL),
(44, 3, 'Jorhat', 1, '2019-01-24 06:41:55', NULL),
(45, 3, 'Kamrup Rural', 1, '2019-01-24 06:41:56', NULL),
(46, 3, 'Kamrup Metropolitan', 1, '2019-01-24 06:41:56', NULL),
(47, 3, 'Karbi Anglong', 1, '2019-01-24 06:41:56', NULL),
(48, 3, 'Karimganj', 1, '2019-01-24 06:41:56', NULL),
(49, 3, 'Kokrajhar', 1, '2019-01-24 06:41:56', NULL),
(50, 3, 'Lakhimpur', 1, '2019-01-24 06:41:56', NULL),
(51, 3, 'Morigaon', 1, '2019-01-24 06:41:56', NULL),
(52, 3, 'Nagaon', 1, '2019-01-24 06:41:56', NULL),
(53, 3, 'Nalbari', 1, '2019-01-24 06:41:56', NULL),
(54, 3, 'Sivasagar', 1, '2019-01-24 06:41:56', NULL),
(55, 3, 'Sonitpur', 1, '2019-01-24 06:41:56', NULL),
(56, 3, 'Tinsukia', 1, '2019-01-24 06:41:57', NULL),
(57, 3, 'Udalguri', 1, '2019-01-24 06:41:57', NULL),
(58, 4, 'Araria', 1, '2019-01-24 06:42:47', NULL),
(59, 4, 'Arwal', 1, '2019-01-24 06:58:57', NULL),
(60, 4, 'Aurangabad', 1, '2019-01-24 06:58:57', NULL),
(61, 4, 'Banka', 1, '2019-01-24 06:58:58', NULL),
(62, 4, 'Begusarai', 1, '2019-01-24 06:58:58', NULL),
(63, 4, 'Bhagalpur', 1, '2019-01-24 06:58:58', NULL),
(64, 4, 'Bhojpur', 1, '2019-01-24 06:58:58', NULL),
(65, 4, 'Buxar', 1, '2019-01-24 06:58:58', NULL),
(66, 4, 'Darbhanga', 1, '2019-01-24 06:58:58', NULL),
(67, 4, 'East Champaran', 1, '2019-01-24 06:58:58', NULL),
(68, 4, 'Gaya', 1, '2019-01-24 06:58:58', NULL),
(69, 4, 'Gopalganj', 1, '2019-01-24 06:58:58', NULL),
(70, 4, 'Jamui', 1, '2019-01-24 06:58:58', NULL),
(71, 4, 'Jehanabad', 1, '2019-01-24 06:58:58', NULL),
(72, 4, 'Kaimur', 1, '2019-01-24 06:58:59', NULL),
(73, 4, 'Katihar', 1, '2019-01-24 06:58:59', NULL),
(74, 4, 'Khagaria', 1, '2019-01-24 06:58:59', NULL),
(75, 4, 'Kishanganj', 1, '2019-01-24 06:58:59', NULL),
(76, 4, 'Lakhisarai', 1, '2019-01-24 06:58:59', NULL),
(77, 4, 'Madhepura', 1, '2019-01-24 06:58:59', NULL),
(78, 4, 'Madhubani', 1, '2019-01-24 06:58:59', NULL),
(79, 4, 'Munger', 1, '2019-01-24 06:58:59', NULL),
(80, 4, 'Muzaffarpur', 1, '2019-01-24 06:58:59', NULL),
(81, 4, 'Nalanda', 1, '2019-01-24 06:58:59', NULL),
(82, 4, 'Nawada', 1, '2019-01-24 06:58:59', NULL),
(83, 4, 'Patna', 1, '2019-01-24 06:59:00', NULL),
(84, 4, 'Purnia', 1, '2019-01-24 06:59:00', NULL),
(85, 4, 'Rohtas', 1, '2019-01-24 06:59:00', NULL),
(86, 4, 'Saharsa', 1, '2019-01-24 06:59:00', NULL),
(87, 4, 'Samastipur', 1, '2019-01-24 06:59:00', NULL),
(88, 4, 'Saran', 1, '2019-01-24 06:59:00', NULL),
(89, 4, 'Sheikhpura', 1, '2019-01-24 06:59:00', NULL),
(90, 4, 'Sheohar', 1, '2019-01-24 06:59:00', NULL),
(91, 4, 'Sitamarhi', 1, '2019-01-24 06:59:00', NULL),
(92, 4, 'Siwan', 1, '2019-01-24 06:59:00', NULL),
(93, 4, 'Supaul', 1, '2019-01-24 06:59:00', NULL),
(94, 4, 'Vaishali', 1, '2019-01-24 06:59:00', NULL),
(95, 4, 'West Champaran', 1, '2019-01-24 06:59:01', NULL),
(96, 5, 'Chandigarh', 1, '2019-01-24 07:11:12', NULL),
(97, 6, 'Balod', 1, '2019-01-24 07:12:40', NULL),
(98, 6, 'Baloda Bazar', 1, '2019-01-24 07:18:37', NULL),
(99, 6, 'Balrampur', 1, '2019-01-24 07:18:38', NULL),
(100, 6, 'Bastar', 1, '2019-01-24 07:18:38', NULL),
(101, 6, 'Bemetara', 1, '2019-01-24 07:18:38', NULL),
(102, 6, 'Bijapur', 1, '2019-01-24 07:18:38', NULL),
(103, 6, 'Bilaspur', 1, '2019-01-24 07:18:38', NULL),
(104, 6, 'Dantewada', 1, '2019-01-24 07:18:38', NULL),
(105, 6, 'Dhamtari', 1, '2019-01-24 07:18:38', NULL),
(106, 6, 'Durg', 1, '2019-01-24 07:18:38', NULL),
(107, 6, 'Gariaband', 1, '2019-01-24 07:18:38', NULL),
(108, 6, 'Janjgir-Champa', 1, '2019-01-24 07:18:38', NULL),
(109, 6, 'Jashpur', 1, '2019-01-24 07:18:38', NULL),
(110, 6, 'Kabirdham', 1, '2019-01-24 07:18:38', NULL),
(111, 6, 'Kanker', 1, '2019-01-24 07:18:38', NULL),
(112, 6, 'Kondagaon', 1, '2019-01-24 07:18:39', NULL),
(113, 6, 'Korba', 1, '2019-01-24 07:18:39', NULL),
(114, 6, 'Koriya', 1, '2019-01-24 07:18:39', NULL),
(115, 6, 'Mahasamund', 1, '2019-01-24 07:18:39', NULL),
(116, 6, 'Mungeli', 1, '2019-01-24 07:18:39', NULL),
(117, 6, 'Narayanpur', 1, '2019-01-24 07:18:39', NULL),
(118, 6, 'Raigarh', 1, '2019-01-24 07:18:39', NULL),
(119, 6, 'Raipur', 1, '2019-01-24 07:18:39', NULL),
(120, 6, 'Rajnandgaon', 1, '2019-01-24 07:18:39', NULL),
(121, 6, 'Sukma', 1, '2019-01-24 07:18:39', NULL),
(122, 6, 'Surajpur', 1, '2019-01-24 07:18:39', NULL),
(123, 6, 'Surguja', 1, '2019-01-24 07:18:39', NULL),
(124, 7, 'Amal', 1, '2019-01-24 07:20:38', NULL),
(125, 7, 'Silvassa', 1, '2019-01-24 07:20:52', NULL),
(126, 8, 'Daman', 1, '2019-01-24 07:21:45', NULL),
(127, 8, 'Diu', 1, '2019-01-24 07:21:56', NULL),
(128, 9, 'Central Delhi', 1, '2019-01-24 07:23:30', NULL),
(129, 9, 'East Delhi', 1, '2019-01-24 07:26:50', NULL),
(130, 9, 'New Delhi', 1, '2019-01-24 07:26:50', NULL),
(131, 9, 'North Delhi', 1, '2019-01-24 07:26:50', NULL),
(132, 9, 'North East Delhi', 1, '2019-01-24 07:26:50', NULL),
(133, 9, 'North West Delhi', 1, '2019-01-24 07:26:50', NULL),
(134, 9, 'Shahdara', 1, '2019-01-24 07:26:50', NULL),
(135, 9, 'South Delhi', 1, '2019-01-24 07:26:50', NULL),
(136, 9, 'South East Delhi', 1, '2019-01-24 07:26:50', NULL),
(137, 9, 'South West Delhi', 1, '2019-01-24 07:26:50', NULL),
(138, 9, 'West Delhi', 1, '2019-01-24 07:26:50', NULL),
(139, 10, 'Chapora', 1, '2019-01-24 07:31:09', NULL),
(140, 10, 'Dabolim', 1, '2019-01-24 07:33:01', NULL),
(141, 10, 'Madgaon', 1, '2019-01-24 07:33:01', NULL),
(142, 10, 'Marmugao (Marmagao)', 1, '2019-01-24 07:33:02', NULL),
(143, 10, 'Panaji Port', 1, '2019-01-24 07:33:02', NULL),
(144, 10, 'Panjim', 1, '2019-01-24 07:33:02', NULL),
(145, 10, 'Pellet Plant Jetty/Shiroda', 1, '2019-01-24 07:33:02', NULL),
(146, 10, 'Talpona', 1, '2019-01-24 07:33:02', NULL),
(147, 10, 'Vasco da Gama', 1, '2019-01-24 07:33:02', NULL),
(148, 11, 'Ahmedabad', 1, '2019-01-24 07:33:54', NULL),
(149, 11, 'Amreli district', 1, '2019-01-24 07:39:56', NULL),
(150, 11, 'Anand', 1, '2019-01-24 07:39:56', NULL),
(151, 11, 'Aravalli', 1, '2019-01-24 07:39:56', NULL),
(152, 11, 'Banaskantha', 1, '2019-01-24 07:39:56', NULL),
(153, 11, 'Bharuch', 1, '2019-01-24 07:39:56', NULL),
(154, 11, 'Bhavnagar', 1, '2019-01-24 07:39:56', NULL),
(155, 11, 'Dahod', 1, '2019-01-24 07:39:56', NULL),
(156, 11, 'Dang', 1, '2019-01-24 07:39:56', NULL),
(157, 11, 'Gandhinagar', 1, '2019-01-24 07:39:56', NULL),
(158, 11, 'Jamnagar', 1, '2019-01-24 07:39:56', NULL),
(159, 11, 'Junagadh', 1, '2019-01-24 07:39:57', NULL),
(160, 11, 'Kutch', 1, '2019-01-24 07:39:57', NULL),
(161, 11, 'Kheda', 1, '2019-01-24 07:39:57', NULL),
(162, 11, 'Mehsana', 1, '2019-01-24 07:39:57', NULL),
(163, 11, 'Narmada', 1, '2019-01-24 07:39:57', NULL),
(164, 11, 'Navsari', 1, '2019-01-24 07:39:57', NULL),
(165, 11, 'Patan', 1, '2019-01-24 07:39:57', NULL),
(166, 11, 'Panchmahal', 1, '2019-01-24 07:39:57', NULL),
(167, 11, 'Porbandar', 1, '2019-01-24 07:39:57', NULL),
(168, 11, 'Rajkot', 1, '2019-01-24 07:39:57', NULL),
(169, 11, 'Sabarkantha', 1, '2019-01-24 07:39:57', NULL),
(170, 11, 'Surendranagar', 1, '2019-01-24 07:39:57', NULL),
(171, 11, 'Surat', 1, '2019-01-24 07:39:57', NULL),
(172, 11, 'Tapi', 1, '2019-01-24 07:39:58', NULL),
(173, 11, 'Vadodara', 1, '2019-01-24 07:39:58', NULL),
(174, 11, 'Valsad', 1, '2019-01-24 07:39:58', NULL),
(175, 12, 'Ambala', 1, '2019-01-24 07:40:50', NULL),
(176, 12, 'Bhiwani', 1, '2019-01-24 07:44:32', NULL),
(177, 12, 'Faridabad', 1, '2019-01-24 07:44:32', NULL),
(178, 12, 'Fatehabad', 1, '2019-01-24 07:44:32', NULL),
(179, 12, 'Gurgaon', 1, '2019-01-24 07:44:32', NULL),
(180, 12, 'Hissar', 1, '2019-01-24 07:44:32', NULL),
(181, 12, 'Jhajjar', 1, '2019-01-24 07:44:32', NULL),
(182, 12, 'Jind', 1, '2019-01-24 07:44:32', NULL),
(183, 12, 'Karnal', 1, '2019-01-24 07:44:32', NULL),
(184, 12, 'Kaithal', 1, '2019-01-24 07:44:33', NULL),
(185, 12, 'Kurukshetra', 1, '2019-01-24 07:44:33', NULL),
(186, 12, 'Mahendragarh', 1, '2019-01-24 07:44:33', NULL),
(187, 12, 'Mewat', 1, '2019-01-24 07:44:33', NULL),
(188, 12, 'Palwal', 1, '2019-01-24 07:44:33', NULL),
(189, 12, 'Panchkula', 1, '2019-01-24 07:44:33', NULL),
(190, 12, 'Panipat', 1, '2019-01-24 07:44:33', NULL),
(191, 12, 'Rewari', 1, '2019-01-24 07:44:33', NULL),
(192, 12, 'Rohtak', 1, '2019-01-24 07:44:33', NULL),
(193, 12, 'Sirsa', 1, '2019-01-24 07:44:33', NULL),
(194, 12, 'Sonipat', 1, '2019-01-24 07:44:33', NULL),
(195, 12, 'Yamuna Nagar', 1, '2019-01-24 07:44:33', NULL),
(196, 13, 'Baddi', 1, '2019-01-24 07:45:39', NULL),
(197, 13, 'Baitalpur', 1, '2019-01-24 07:47:55', NULL),
(198, 13, 'Chamba', 1, '2019-01-24 07:47:55', NULL),
(199, 13, 'Dharamsala', 1, '2019-01-24 07:47:55', NULL),
(200, 13, 'Hamirpur', 1, '2019-01-24 07:47:55', NULL),
(201, 13, 'Kangra', 1, '2019-01-24 07:47:55', NULL),
(202, 13, 'Kinnaur', 1, '2019-01-24 07:47:55', NULL),
(203, 13, 'Kullu', 1, '2019-01-24 07:47:55', NULL),
(204, 13, 'Lahaul & Spiti', 1, '2019-01-24 07:47:55', NULL),
(205, 13, 'Mandi', 1, '2019-01-24 07:47:55', NULL),
(206, 13, 'Simla', 1, '2019-01-24 07:47:55', NULL),
(207, 13, 'Sirmaur', 1, '2019-01-24 07:47:56', NULL),
(208, 13, 'Solan', 1, '2019-01-24 07:47:56', NULL),
(209, 13, 'Una', 1, '2019-01-24 07:47:56', NULL),
(210, 14, 'Jammu', 1, '2019-01-24 07:48:55', NULL),
(211, 14, 'Leh', 1, '2019-01-24 07:49:34', NULL),
(212, 14, 'Rajouri', 1, '2019-01-24 07:49:34', NULL),
(213, 14, 'Srinagar', 1, '2019-01-24 07:49:34', NULL),
(214, 15, 'Bokaro', 1, '2019-01-24 07:50:22', NULL),
(215, 15, 'Chatra', 1, '2019-01-24 08:23:18', NULL),
(216, 15, 'Deoghar', 1, '2019-01-24 08:23:18', NULL),
(217, 15, 'Dhanbad', 1, '2019-01-24 08:23:18', NULL),
(218, 15, 'Dumka', 1, '2019-01-24 08:23:18', NULL),
(219, 15, 'East Singhbhum', 1, '2019-01-24 08:23:18', NULL),
(220, 15, 'Garhwa', 1, '2019-01-24 08:23:18', NULL),
(221, 15, 'Giridih', 1, '2019-01-24 08:23:18', NULL),
(222, 15, 'Godda', 1, '2019-01-24 08:23:18', NULL),
(223, 15, 'Gumla', 1, '2019-01-24 08:23:18', NULL),
(224, 15, 'Hazaribag', 1, '2019-01-24 08:23:18', NULL),
(225, 15, 'Jamtara', 1, '2019-01-24 08:23:19', NULL),
(226, 15, 'Khunti', 1, '2019-01-24 08:23:19', NULL),
(227, 15, 'Koderma', 1, '2019-01-24 08:23:19', NULL),
(228, 15, 'Latehar', 1, '2019-01-24 08:23:19', NULL),
(229, 15, 'Lohardaga', 1, '2019-01-24 08:23:19', NULL),
(230, 15, 'Pakur', 1, '2019-01-24 08:23:19', NULL),
(231, 15, 'Palamu', 1, '2019-01-24 08:23:19', NULL),
(232, 15, 'Ramgarh', 1, '2019-01-24 08:23:19', NULL),
(233, 15, 'Ranchi', 1, '2019-01-24 08:23:19', NULL),
(234, 15, 'Sahibganj', 1, '2019-01-24 08:23:19', NULL),
(235, 15, 'Seraikela Kharsawan', 1, '2019-01-24 08:23:20', NULL),
(236, 15, 'Simdega', 1, '2019-01-24 08:23:20', NULL),
(237, 15, 'West Singhbhum', 1, '2019-01-24 08:23:20', NULL),
(238, 16, 'Bagalkot', 1, '2019-01-24 08:24:09', NULL),
(239, 16, 'Bangalore', 1, '2019-01-24 08:43:38', NULL),
(240, 16, 'Bangalore Urban', 1, '2019-01-24 08:43:38', NULL),
(241, 16, 'Belgaum', 1, '2019-01-24 08:43:38', NULL),
(242, 16, 'Bellary', 1, '2019-01-24 08:43:38', NULL),
(243, 16, 'Bidar', 1, '2019-01-24 08:43:38', NULL),
(244, 16, 'Bijapur', 1, '2019-01-24 08:43:38', NULL),
(245, 16, 'Chamarajnagar', 1, '2019-01-24 08:43:38', NULL),
(246, 16, 'Chikkamagaluru', 1, '2019-01-24 08:43:38', NULL),
(247, 16, 'Chikkaballapur', 1, '2019-01-24 08:43:38', NULL),
(248, 16, 'Chitradurga', 1, '2019-01-24 08:43:38', NULL),
(249, 16, 'Davanagere', 1, '2019-01-24 08:43:38', NULL),
(250, 16, 'Dharwad', 1, '2019-01-24 08:43:38', NULL),
(251, 16, 'Dakshina Kannada', 1, '2019-01-24 08:43:39', NULL),
(252, 16, 'Gadag', 1, '2019-01-24 08:43:39', NULL),
(253, 16, 'Gulbarga', 1, '2019-01-24 08:43:39', NULL),
(254, 16, 'Hassan', 1, '2019-01-24 08:43:39', NULL),
(255, 16, 'Haveri district', 1, '2019-01-24 08:43:39', NULL),
(256, 16, 'Kodagu', 1, '2019-01-24 08:43:39', NULL),
(257, 16, 'Kolar', 1, '2019-01-24 08:43:39', NULL),
(258, 16, 'Koppal', 1, '2019-01-24 08:43:39', NULL),
(259, 16, 'Mandya', 1, '2019-01-24 08:43:39', NULL),
(260, 16, 'Mysore', 1, '2019-01-24 08:43:39', NULL),
(261, 16, 'Raichur', 1, '2019-01-24 08:43:39', NULL),
(262, 16, 'Shimoga', 1, '2019-01-24 08:43:39', NULL),
(263, 16, 'Tumkur', 1, '2019-01-24 08:43:39', NULL),
(264, 16, 'Udupi', 1, '2019-01-24 08:43:40', NULL),
(265, 16, 'Uttara Kannada', 1, '2019-01-24 08:43:40', NULL),
(266, 16, 'Ramanagara', 1, '2019-01-24 08:43:40', NULL),
(267, 16, 'Yadgir', 1, '2019-01-24 08:43:40', NULL),
(268, 17, 'Alappuzha', 1, '2019-01-24 09:36:02', NULL),
(269, 17, 'Ernakulam', 1, '2019-01-24 09:38:56', NULL),
(270, 17, 'Idukki', 1, '2019-01-24 09:38:56', NULL),
(271, 17, 'Kannur', 1, '2019-01-24 09:38:56', NULL),
(272, 17, 'Kasaragod', 1, '2019-01-24 09:38:56', NULL),
(273, 17, 'Kollam', 1, '2019-01-24 09:38:56', NULL),
(274, 17, 'Kottayam', 1, '2019-01-24 09:38:56', NULL),
(275, 17, 'Kozhikode', 1, '2019-01-24 09:38:56', NULL),
(276, 17, 'Malappuram', 1, '2019-01-24 09:38:56', NULL),
(277, 17, 'Palakkad', 1, '2019-01-24 09:38:56', NULL),
(278, 17, 'Pathanamthitta', 1, '2019-01-24 09:38:57', NULL),
(279, 17, 'Thrissur', 1, '2019-01-24 09:38:57', NULL),
(280, 17, 'Thiruvananthapuram', 1, '2019-01-24 09:38:57', NULL),
(281, 17, 'Wayanad', 1, '2019-01-24 09:38:57', NULL),
(282, 18, 'Lakshadweep', 1, '2019-01-24 09:40:08', NULL),
(283, 19, 'Alirajpur', 1, '2019-01-24 09:41:57', NULL),
(284, 19, 'Anuppur', 1, '2019-01-24 09:51:31', NULL),
(285, 19, 'Ashoknagar', 1, '2019-01-24 09:51:31', NULL),
(286, 19, 'Balaghat', 1, '2019-01-24 09:51:31', NULL),
(287, 19, 'Barwani', 1, '2019-01-24 09:51:31', NULL),
(288, 19, 'Betul', 1, '2019-01-24 09:51:31', NULL),
(289, 19, 'Bhilai', 1, '2019-01-24 09:51:31', NULL),
(290, 19, 'Bhind', 1, '2019-01-24 09:51:31', NULL),
(291, 19, 'Bhopal', 1, '2019-01-24 09:51:31', NULL),
(292, 19, 'Burhanpur', 1, '2019-01-24 09:51:32', NULL),
(293, 19, 'Chhatarpur', 1, '2019-01-24 09:51:32', NULL),
(294, 19, 'Chhindwara', 1, '2019-01-24 09:51:32', NULL),
(295, 19, 'Damoh', 1, '2019-01-24 09:51:32', NULL),
(296, 19, 'Dewas', 1, '2019-01-24 09:51:32', NULL),
(297, 19, 'Dhar', 1, '2019-01-24 09:51:32', NULL),
(298, 19, 'Guna', 1, '2019-01-24 09:51:32', NULL),
(299, 19, 'Gwalior', 1, '2019-01-24 09:51:32', NULL),
(300, 19, 'Hoshangabad', 1, '2019-01-24 09:51:32', NULL),
(301, 19, 'Indore', 1, '2019-01-24 09:51:32', NULL),
(302, 19, 'Itarsi', 1, '2019-01-24 09:51:32', NULL),
(303, 19, 'Jabalpur', 1, '2019-01-24 09:51:32', NULL),
(304, 19, 'Khajuraho', 1, '2019-01-24 09:51:32', NULL),
(305, 19, 'Khandwa', 1, '2019-01-24 09:51:33', NULL),
(306, 19, 'Khargone', 1, '2019-01-24 09:51:33', NULL),
(307, 19, 'Malanpur', 1, '2019-01-24 09:51:33', NULL),
(308, 19, 'Malanpuri (Gwalior)', 1, '2019-01-24 09:51:33', NULL),
(309, 19, 'Mandla', 1, '2019-01-24 09:51:33', NULL),
(310, 19, 'Mandsaur', 1, '2019-01-24 09:51:33', NULL),
(311, 19, 'Morena', 1, '2019-01-24 09:51:33', NULL),
(312, 19, 'Narsinghpur', 1, '2019-01-24 09:51:33', NULL),
(313, 19, 'Neemuch', 1, '2019-01-24 09:51:33', NULL),
(314, 19, 'Panna', 1, '2019-01-24 09:51:33', NULL),
(315, 19, 'Pithampur', 1, '2019-01-24 09:51:33', NULL),
(316, 19, 'Raipur', 1, '2019-01-24 09:51:34', NULL),
(317, 19, 'Raisen', 1, '2019-01-24 09:51:34', NULL),
(318, 19, 'Ratlam', 1, '2019-01-24 09:51:34', NULL),
(319, 19, 'Rewa', 1, '2019-01-24 09:51:34', NULL),
(320, 19, 'Sagar', 1, '2019-01-24 09:51:34', NULL),
(321, 19, 'Satna', 1, '2019-01-24 09:51:34', NULL),
(322, 19, 'Sehore', 1, '2019-01-24 09:51:34', NULL),
(323, 19, 'Seoni', 1, '2019-01-24 09:51:34', NULL),
(324, 19, 'Shahdol', 1, '2019-01-24 09:51:34', NULL),
(325, 19, 'Singrauli', 1, '2019-01-24 09:51:34', NULL),
(326, 19, 'Ujjain', 1, '2019-01-24 09:51:34', NULL),
(327, 20, 'Ahmednagar', 1, '2019-01-24 09:52:57', NULL),
(328, 20, 'Akola', 1, '2019-01-24 10:17:08', NULL),
(329, 20, 'Alibag', 1, '2019-01-24 10:17:08', NULL),
(330, 20, 'Amaravati', 1, '2019-01-24 10:17:08', NULL),
(331, 20, 'Arnala', 1, '2019-01-24 10:17:08', NULL),
(332, 20, 'Aurangabad', 1, '2019-01-24 10:17:08', NULL),
(333, 20, 'Bandra', 1, '2019-01-24 10:17:08', NULL),
(334, 20, 'Bassain', 1, '2019-01-24 10:17:08', NULL),
(335, 20, 'Belapur', 1, '2019-01-24 10:17:08', NULL),
(336, 20, 'Bhiwandi', 1, '2019-01-24 10:17:08', NULL),
(337, 20, 'Bhusaval', 1, '2019-01-24 10:17:08', NULL),
(338, 20, 'Borliai-Mandla', 1, '2019-01-24 10:17:08', NULL),
(339, 20, 'Chandrapur', 1, '2019-01-24 10:17:08', NULL),
(340, 20, 'Dahanu', 1, '2019-01-24 10:17:08', NULL),
(341, 20, 'Daulatabad', 1, '2019-01-24 10:17:08', NULL),
(342, 20, 'Dighi (Pune)', 1, '2019-01-24 10:17:08', NULL),
(343, 20, 'Dombivali', 1, '2019-01-24 10:17:08', NULL),
(344, 20, 'Goa', 1, '2019-01-24 10:17:09', NULL),
(345, 20, 'Jaitapur', 1, '2019-01-24 10:17:09', NULL),
(346, 20, 'Jalgaon', 1, '2019-01-24 10:17:09', NULL),
(347, 20, 'Jawaharlal Nehru (Nhava Sheva)', 1, '2019-01-24 10:17:09', NULL),
(348, 20, 'Kalyan', 1, '2019-01-24 10:17:09', NULL),
(349, 20, 'Karanja', 1, '2019-01-24 10:17:09', NULL),
(350, 20, 'Kelwa', 1, '2019-01-24 10:17:09', NULL),
(351, 20, 'Khopoli', 1, '2019-01-24 10:17:09', NULL),
(352, 20, 'Kolhapur', 1, '2019-01-24 10:17:09', NULL),
(353, 20, 'Lonavale', 1, '2019-01-24 10:17:09', NULL),
(354, 20, 'Malegaon', 1, '2019-01-24 10:17:09', NULL),
(355, 20, 'Malwan', 1, '2019-01-24 10:17:09', NULL),
(356, 20, 'Manori', 1, '2019-01-24 10:17:09', NULL),
(357, 20, 'Mira Bhayandar', 1, '2019-01-24 10:17:09', NULL),
(358, 20, 'Miraj', 1, '2019-01-24 10:17:09', NULL),
(359, 20, 'Mumbai (ex Bombay)', 1, '2019-01-24 10:17:09', NULL),
(360, 20, 'Murad', 1, '2019-01-24 10:17:09', NULL),
(361, 20, 'Nandurbar', 1, '2019-01-24 10:22:06', NULL),
(362, 20, 'Nagpur', 1, '2019-01-24 10:17:10', NULL),
(363, 20, 'Nalasopara', 1, '2019-01-24 10:17:10', NULL),
(364, 20, 'Nanded', 1, '2019-01-24 10:17:10', NULL),
(365, 20, 'Nandgaon', 1, '2019-01-24 10:17:10', NULL),
(366, 20, 'Nasik', 1, '2019-01-24 10:17:10', NULL),
(367, 20, 'Navi Mumbai', 1, '2019-01-24 10:17:10', NULL),
(368, 20, 'Nhave', 1, '2019-01-24 10:17:10', NULL),
(369, 20, 'Osmanabad', 1, '2019-01-24 10:17:10', NULL),
(370, 20, 'Palghar', 1, '2019-01-24 10:17:10', NULL),
(371, 20, 'Panvel', 1, '2019-01-24 10:17:10', NULL),
(372, 20, 'Pimpri', 1, '2019-01-24 10:17:10', NULL),
(373, 20, 'Pune', 1, '2019-01-24 10:17:10', NULL),
(374, 20, 'Ratnagiri', 1, '2019-01-24 10:17:10', NULL),
(375, 20, 'Sholapur', 1, '2019-01-24 10:17:10', NULL),
(376, 20, 'Shrirampur', 1, '2019-01-24 10:17:10', NULL),
(377, 20, 'Shriwardhan', 1, '2019-01-24 10:17:10', NULL),
(378, 20, 'Tarapur', 1, '2019-01-24 10:17:10', NULL),
(379, 20, 'Thane', 1, '2019-01-24 10:18:55', NULL),
(380, 20, 'Trombay', 1, '2019-01-24 10:17:11', NULL),
(381, 20, 'Varsova', 1, '2019-01-24 10:17:11', NULL),
(382, 20, 'Vengurla', 1, '2019-01-24 10:17:11', NULL),
(383, 20, 'Virar', 1, '2019-01-24 10:17:11', NULL),
(384, 20, 'Wardha', 1, '2019-01-24 10:18:36', NULL),
(385, 20, 'Washim', 1, '2019-01-24 10:24:43', NULL),
(386, 20, 'Yavatmal', 1, '2019-01-24 10:24:55', NULL),
(387, 21, 'Bishnupur', 1, '2019-01-24 10:26:21', NULL),
(388, 21, 'Thoubal', 1, '2019-01-24 10:30:47', NULL),
(389, 21, 'Imphal East', 1, '2019-01-24 10:30:47', NULL),
(390, 21, 'Imphal West', 1, '2019-01-24 10:30:47', NULL),
(391, 21, 'Senapati', 1, '2019-01-24 10:30:47', NULL),
(392, 21, 'Ukhrul', 1, '2019-01-24 10:30:47', NULL),
(393, 21, 'Chandel', 1, '2019-01-24 10:30:47', NULL),
(394, 21, 'Churachandpur', 1, '2019-01-24 10:30:47', NULL),
(395, 21, 'Tamenglong', 1, '2019-01-24 10:30:47', NULL),
(396, 21, 'Jiribam', 1, '2019-01-24 10:30:47', NULL),
(397, 21, 'Kangpokpi (Sadar Hills)', 1, '2019-01-24 10:30:47', NULL),
(398, 21, 'Kakching', 1, '2019-01-24 10:30:47', NULL),
(399, 21, 'Tengnoupal', 1, '2019-01-24 10:30:47', NULL),
(400, 21, 'Kamjong', 1, '2019-01-24 10:30:47', NULL),
(401, 21, 'Noney', 1, '2019-01-24 10:30:47', NULL),
(402, 21, 'Pherzawl', 1, '2019-01-24 10:30:48', NULL),
(403, 22, 'East Garo Hills', 1, '2019-01-24 10:33:11', NULL),
(404, 22, 'East Khasi Hills', 1, '2019-01-24 10:45:08', NULL),
(405, 22, 'East Jaintia Hills', 1, '2019-01-24 10:45:08', NULL),
(406, 22, 'North Garo Hills', 1, '2019-01-24 10:45:08', NULL),
(407, 22, 'Ri Bhoi', 1, '2019-01-24 10:45:08', NULL),
(408, 22, 'South Garo Hills', 1, '2019-01-24 10:45:08', NULL),
(409, 22, 'South West Garo Hills', 1, '2019-01-24 10:45:08', NULL),
(410, 22, 'South West Khasi Hills', 1, '2019-01-24 10:45:08', NULL),
(411, 22, 'West Jaintia Hills', 1, '2019-01-24 10:45:09', NULL),
(412, 22, 'West Garo Hills', 1, '2019-01-24 10:45:09', NULL),
(413, 22, 'West Khasi Hills', 1, '2019-01-24 10:45:09', NULL),
(414, 23, 'Aizawl', 1, '2019-01-24 10:48:29', NULL),
(415, 23, 'Champhai', 1, '2019-01-24 10:49:40', NULL),
(416, 23, 'Kolasib', 1, '2019-01-24 10:49:40', NULL),
(417, 23, 'Lawngtlai', 1, '2019-01-24 10:49:40', NULL),
(418, 23, 'Lunglei', 1, '2019-01-24 10:49:40', NULL),
(419, 23, 'Mamit', 1, '2019-01-24 10:49:40', NULL),
(420, 23, 'Saiha', 1, '2019-01-24 10:49:40', NULL),
(421, 23, 'Serchhip', 1, '2019-01-24 10:49:41', NULL),
(422, 24, 'Dimapur', 1, '2019-01-24 10:50:52', NULL),
(423, 24, 'Kiphire', 1, '2019-01-24 10:52:27', NULL),
(424, 24, 'Kohima', 1, '2019-01-24 10:52:27', NULL),
(425, 24, 'Longleng', 1, '2019-01-24 10:52:27', NULL),
(426, 24, 'Mokokchung', 1, '2019-01-24 10:52:27', NULL),
(427, 24, 'Mon', 1, '2019-01-24 10:52:27', NULL),
(428, 24, 'Peren', 1, '2019-01-24 10:52:27', NULL),
(429, 24, 'Phek', 1, '2019-01-24 10:52:27', NULL),
(430, 24, 'Tuensang', 1, '2019-01-24 10:52:27', NULL),
(431, 24, 'Wokha', 1, '2019-01-24 10:52:27', NULL),
(432, 24, 'Zunheboto', 1, '2019-01-24 10:52:27', NULL),
(433, 25, 'Angul', 1, '2019-01-24 10:54:17', NULL),
(434, 25, 'Boudh (Bauda)', 1, '2019-01-24 10:55:57', NULL),
(435, 25, 'Bhadrak', 1, '2019-01-24 10:56:09', NULL),
(436, 25, 'Balangir', 1, '2019-01-24 10:56:22', NULL),
(437, 25, 'Bargarh (Baragarh)', 1, '2019-01-24 10:56:38', NULL),
(438, 25, 'Balasore', 1, '2019-01-24 10:57:01', NULL),
(439, 25, 'Cuttack', 1, '2019-01-24 10:57:13', NULL),
(440, 25, 'Debagarh (Deogarh)', 1, '2019-01-24 10:57:34', NULL),
(441, 25, 'Dhenkanal', 1, '2019-01-24 11:05:09', NULL),
(442, 25, 'Ganjam', 1, '2019-01-24 11:05:09', NULL),
(443, 25, 'Gajapati', 1, '2019-01-24 11:05:09', NULL),
(444, 25, 'Jharsuguda', 1, '2019-01-24 11:05:09', NULL),
(445, 25, 'Jajpur', 1, '2019-01-24 11:05:09', NULL),
(446, 25, 'Jagatsinghpur', 1, '2019-01-24 11:05:09', NULL),
(447, 25, 'Khordha', 1, '2019-01-24 11:05:09', NULL),
(448, 25, 'Kendujhar (Keonjhar)', 1, '2019-01-24 11:05:09', NULL),
(449, 25, 'Kalahandi', 1, '2019-01-24 11:05:09', NULL),
(450, 25, 'Kandhamal', 1, '2019-01-24 11:05:10', NULL),
(451, 25, 'Koraput', 1, '2019-01-24 11:05:10', NULL),
(452, 25, 'Kendrapara', 1, '2019-01-24 11:05:10', NULL),
(453, 25, 'Malkangiri', 1, '2019-01-24 11:05:10', NULL),
(454, 25, 'Mayurbhanj', 1, '2019-01-24 11:05:10', NULL),
(455, 25, 'Nabarangpur', 1, '2019-01-24 11:05:10', NULL),
(456, 25, 'Nuapada', 1, '2019-01-24 11:05:10', NULL),
(457, 25, 'Nayagarh', 1, '2019-01-24 11:05:10', NULL),
(458, 25, 'Puri', 1, '2019-01-24 11:05:10', NULL),
(459, 25, 'Rayagada', 1, '2019-01-24 11:05:10', NULL),
(460, 25, 'Sambalpur', 1, '2019-01-24 11:05:10', NULL),
(461, 25, 'Subarnapur (Sonepur)', 1, '2019-01-24 11:05:10', NULL),
(462, 25, 'Sundargarh', 1, '2019-01-24 11:05:10', NULL),
(463, 26, 'Karaikal', 1, '2019-01-24 11:06:14', NULL),
(464, 26, 'Mahe', 1, '2019-01-24 11:06:53', NULL),
(465, 26, 'Pondicherry', 1, '2019-01-24 11:06:53', NULL),
(466, 26, 'Yanam', 1, '2019-01-24 11:06:53', NULL),
(467, 27, 'Amritsar', 1, '2019-01-24 11:09:33', NULL),
(468, 27, 'Barnala', 1, '2019-01-24 11:14:27', NULL),
(469, 27, 'Bathinda', 1, '2019-01-24 11:14:28', NULL),
(470, 27, 'Firozpur', 1, '2019-01-24 11:14:28', NULL),
(471, 27, 'Faridkot', 1, '2019-01-24 11:14:28', NULL),
(472, 27, 'Fatehgarh Sahib', 1, '2019-01-24 11:14:28', NULL),
(473, 27, 'Fazilka', 1, '2019-01-24 11:14:28', NULL),
(474, 27, 'Gurdaspur', 1, '2019-01-24 11:14:28', NULL),
(475, 27, 'Hoshiarpur', 1, '2019-01-24 11:14:28', NULL),
(476, 27, 'Jalandhar', 1, '2019-01-24 11:14:28', NULL),
(477, 27, 'Kapurthala', 1, '2019-01-24 11:14:28', NULL),
(478, 27, 'Ludhiana', 1, '2019-01-24 11:14:28', NULL),
(479, 27, 'Mansa', 1, '2019-01-24 11:14:28', NULL),
(480, 27, 'Moga', 1, '2019-01-24 11:14:28', NULL),
(481, 27, 'Sri Muktsar Sahib', 1, '2019-01-24 11:14:28', NULL),
(482, 27, 'Pathankot', 1, '2019-01-24 11:14:29', NULL),
(483, 27, 'Patiala', 1, '2019-01-24 11:14:29', NULL),
(484, 27, 'Rupnagar', 1, '2019-01-24 11:14:29', NULL),
(485, 27, 'Sahibzada Ajit Singh Nagar', 1, '2019-01-24 11:14:29', NULL),
(486, 27, 'Sangrur', 1, '2019-01-24 11:14:29', NULL),
(487, 27, 'Shahid Bhagat Singh Nagar', 1, '2019-01-24 11:14:29', NULL),
(488, 27, 'Tarn Taran', 1, '2019-01-24 11:14:29', NULL),
(489, 27, 'Amritsar', 1, '2019-01-24 11:14:29', NULL),
(490, 27, 'Amritsar', 1, '2019-01-24 11:14:29', NULL),
(491, 28, 'Ajmer', 1, '2019-01-24 11:16:13', NULL),
(492, 28, 'Alwar', 1, '2019-01-24 11:34:04', NULL),
(493, 28, 'Bikaner', 1, '2019-01-24 11:34:05', NULL),
(494, 28, 'Barmer', 1, '2019-01-24 11:34:05', NULL),
(495, 28, 'Banswara', 1, '2019-01-24 11:34:05', NULL),
(496, 28, 'Bharatpur', 1, '2019-01-24 11:34:05', NULL),
(497, 28, 'Baran', 1, '2019-01-24 11:34:05', NULL),
(498, 28, 'Bundi', 1, '2019-01-24 11:34:05', NULL),
(499, 28, 'Bhilwara', 1, '2019-01-24 11:34:05', NULL),
(500, 28, 'Churu', 1, '2019-01-24 11:34:05', NULL),
(501, 28, 'Chittorgarh', 1, '2019-01-24 11:34:05', NULL),
(502, 28, 'Dausa', 1, '2019-01-24 11:34:05', NULL),
(503, 28, 'Dholpur', 1, '2019-01-24 11:34:05', NULL),
(504, 28, 'Dungarpur', 1, '2019-01-24 11:34:05', NULL),
(505, 28, 'Ganganagar', 1, '2019-01-24 11:34:06', NULL),
(506, 28, 'Hanumangarh', 1, '2019-01-24 11:34:06', NULL),
(507, 28, 'Jhunjhunu', 1, '2019-01-24 11:34:06', NULL),
(508, 28, 'Jalore', 1, '2019-01-24 11:34:06', NULL),
(509, 28, 'Jodhpur', 1, '2019-01-24 11:34:06', NULL),
(510, 28, 'Jaipur', 1, '2019-01-24 11:34:06', NULL),
(511, 28, 'Jaisalmer', 1, '2019-01-24 11:34:06', NULL),
(512, 28, 'Jhalawar', 1, '2019-01-24 11:34:06', NULL),
(513, 28, 'Karauli', 1, '2019-01-24 11:34:06', NULL),
(514, 28, 'Kota', 1, '2019-01-24 11:34:06', NULL),
(515, 28, 'Nagaur', 1, '2019-01-24 11:34:06', NULL),
(516, 28, 'Pali', 1, '2019-01-24 11:34:06', NULL),
(517, 28, 'Pratapgarh', 1, '2019-01-24 11:34:06', NULL),
(518, 28, 'Rajsamand', 1, '2019-01-24 11:34:07', NULL),
(519, 28, 'Sikar', 1, '2019-01-24 11:34:07', NULL),
(520, 28, 'Sawai Madhopur', 1, '2019-01-24 11:34:07', NULL),
(521, 28, 'Sirohi', 1, '2019-01-24 11:34:07', NULL),
(522, 28, 'Tonk', 1, '2019-01-24 11:34:07', NULL),
(523, 28, 'Udaipur', 1, '2019-01-24 11:34:07', NULL),
(524, 29, 'East Sikkim', 1, '2019-01-24 11:36:30', NULL),
(525, 29, 'North Sikkim', 1, '2019-01-24 11:37:14', NULL),
(526, 29, 'South Sikkim', 1, '2019-01-24 11:37:14', NULL),
(527, 29, 'West Sikkim', 1, '2019-01-24 11:37:14', NULL),
(528, 30, 'Ariyalur', 1, '2019-01-24 11:38:52', NULL),
(529, 30, 'Chennai', 1, '2019-01-24 11:45:21', NULL),
(530, 30, 'Coimbatore', 1, '2019-01-24 11:45:21', NULL),
(531, 30, 'Cuddalore', 1, '2019-01-24 11:45:21', NULL),
(532, 30, 'Dharmapuri', 1, '2019-01-24 11:45:21', NULL),
(533, 30, 'Dindigul', 1, '2019-01-24 11:45:21', NULL),
(534, 30, 'Erode', 1, '2019-01-24 11:45:21', NULL),
(535, 30, 'Kanchipuram', 1, '2019-01-24 11:45:21', NULL),
(536, 30, 'Kanyakumari', 1, '2019-01-24 11:45:21', NULL),
(537, 30, 'Karur', 1, '2019-01-24 11:45:21', NULL),
(538, 30, 'Krishnagiri', 1, '2019-01-24 11:45:21', NULL),
(539, 30, 'Madurai', 1, '2019-01-24 11:45:21', NULL),
(540, 30, 'Nagapattinam', 1, '2019-01-24 11:45:21', NULL),
(541, 30, 'Nilgiris', 1, '2019-01-24 11:45:22', NULL),
(542, 30, 'Namakkal', 1, '2019-01-24 11:45:22', NULL),
(543, 30, 'Perambalur', 1, '2019-01-24 11:45:22', NULL),
(544, 30, 'Pudukkottai', 1, '2019-01-24 11:45:22', NULL),
(545, 30, 'Ramanathapuram', 1, '2019-01-24 11:45:22', NULL),
(546, 30, 'Salem', 1, '2019-01-24 11:45:22', NULL),
(547, 30, 'Sivaganga', 1, '2019-01-24 11:45:22', NULL),
(548, 30, 'Tirupur', 1, '2019-01-24 11:45:22', NULL),
(549, 30, 'Tiruchirappalli', 1, '2019-01-24 11:45:22', NULL),
(550, 30, 'Theni', 1, '2019-01-24 11:45:22', NULL),
(551, 30, 'Tirunelveli', 1, '2019-01-24 11:45:22', NULL),
(552, 30, 'Thanjavur', 1, '2019-01-24 11:45:22', NULL),
(553, 30, 'Thoothukudi', 1, '2019-01-24 11:45:23', NULL),
(554, 30, 'Tiruvallur', 1, '2019-01-24 11:45:23', NULL),
(555, 30, 'Tiruvarur', 1, '2019-01-24 11:45:23', NULL),
(556, 30, 'Tiruvannamalai', 1, '2019-01-24 11:45:23', NULL),
(557, 30, 'Vellore', 1, '2019-01-24 11:45:23', NULL),
(558, 30, 'Viluppuram', 1, '2019-01-24 11:45:23', NULL),
(559, 30, 'Virudhunagar', 1, '2019-01-24 11:45:23', NULL),
(560, 31, 'Adilabad', 1, '2019-01-24 11:51:53', NULL),
(561, 31, 'Hyderabad', 1, '2019-01-24 11:53:26', NULL),
(562, 31, 'Karimnagar', 1, '2019-01-24 11:53:26', NULL),
(563, 31, 'Mahbubnagar', 1, '2019-01-24 11:53:26', NULL),
(564, 31, 'Medak', 1, '2019-01-24 11:53:26', NULL),
(565, 31, 'Nalgonda', 1, '2019-01-24 11:53:26', NULL),
(566, 31, 'Nizamabad', 1, '2019-01-24 11:53:26', NULL),
(567, 31, 'Ranga Reddy', 1, '2019-01-24 11:53:26', NULL),
(568, 31, 'Warangal', 1, '2019-01-24 11:53:27', NULL),
(569, 32, 'Agartala', 1, '2019-01-24 11:55:19', NULL),
(570, 32, 'Dhalaighat', 1, '2019-01-24 11:57:54', NULL),
(571, 32, 'Kailashahar', 1, '2019-01-24 11:57:54', NULL),
(572, 32, 'Kamalpur', 1, '2019-01-24 11:57:54', NULL),
(573, 32, 'Kanchanpur', 1, '2019-01-24 11:57:54', NULL),
(574, 32, 'Kel Sahar Subdivision', 1, '2019-01-24 11:57:54', NULL),
(575, 32, 'Khowai', 1, '2019-01-24 11:57:55', NULL),
(576, 32, 'Khowaighat', 1, '2019-01-24 11:57:55', NULL),
(577, 32, 'Mahurighat', 1, '2019-01-24 11:57:55', NULL),
(578, 32, 'Old Raghna Bazar', 1, '2019-01-24 11:57:55', NULL),
(579, 32, 'Sabroom', 1, '2019-01-24 11:57:55', NULL),
(580, 32, 'Srimantapur', 1, '2019-01-24 11:57:55', NULL),
(581, 33, 'Almora', 1, '2019-01-24 11:59:55', NULL),
(582, 33, 'Badrinath', 1, '2019-01-24 12:03:19', NULL),
(583, 33, 'Bangla', 1, '2019-01-24 12:03:39', NULL),
(584, 33, 'Barkot', 1, '2019-01-24 12:03:50', NULL),
(585, 33, 'Bazpur', 1, '2019-01-24 12:04:09', NULL),
(586, 33, 'Chamoli', 1, '2019-01-24 12:04:21', NULL),
(587, 33, 'Chopra', 1, '2019-01-24 12:04:32', NULL),
(588, 33, 'Dehra Dun', 1, '2019-01-24 12:04:43', NULL),
(589, 33, 'Dwarahat', 1, '2019-01-24 12:04:56', NULL),
(590, 33, 'Garhwal', 1, '2019-01-24 12:05:06', NULL),
(591, 33, 'Haldwani', 1, '2019-01-24 12:05:18', NULL),
(592, 33, 'Haridwar', 1, '2019-01-24 12:06:12', NULL),
(593, 33, 'Jamal', 1, '2019-01-24 12:06:26', NULL),
(594, 33, 'Jwalapur', 1, '2019-01-24 12:07:22', NULL),
(595, 33, 'Kalsi', 1, '2019-01-24 12:07:34', NULL),
(596, 33, 'Kashipur', 1, '2019-01-24 12:07:49', NULL),
(597, 33, 'Mall', 1, '2019-01-24 12:08:25', NULL),
(598, 33, 'Mussoorie', 1, '2019-01-24 12:10:24', NULL),
(599, 33, 'Nahar', 1, '2019-01-24 12:10:25', NULL),
(600, 33, 'Naini', 1, '2019-01-24 12:10:25', NULL),
(601, 33, 'Pantnagar', 1, '2019-01-24 12:10:25', NULL),
(602, 33, 'Pauri', 1, '2019-01-24 12:10:25', NULL),
(603, 33, 'Pithoragarh', 1, '2019-01-24 12:10:25', NULL),
(604, 33, 'Rameshwar', 1, '2019-01-24 12:10:25', NULL),
(605, 33, 'Rishikesh', 1, '2019-01-24 12:10:25', NULL),
(606, 33, 'Rohni', 1, '2019-01-24 12:10:25', NULL),
(607, 33, 'Roorkee', 1, '2019-01-24 12:10:25', NULL),
(608, 33, 'Sama', 1, '2019-01-24 12:10:25', NULL),
(609, 33, 'Saur', 1, '2019-01-24 12:10:25', NULL),
(610, 34, 'Agra', 1, '2019-01-24 12:11:08', NULL),
(611, 34, 'Allahabad', 1, '2019-01-24 12:17:04', NULL),
(612, 34, 'Auraiya', 1, '2019-01-24 12:17:04', NULL),
(613, 34, 'Banbasa', 1, '2019-01-24 12:17:04', NULL),
(614, 34, 'Bareilly', 1, '2019-01-24 12:17:04', NULL),
(615, 34, 'Berhni', 1, '2019-01-24 12:17:04', NULL),
(616, 34, 'Bhadohi', 1, '2019-01-24 12:17:04', NULL),
(617, 34, 'Dadri', 1, '2019-01-24 12:17:04', NULL),
(618, 34, 'Dharchula', 1, '2019-01-24 12:17:04', NULL),
(619, 34, 'Gandhar', 1, '2019-01-24 12:17:05', NULL),
(620, 34, 'Gauriphanta', 1, '2019-01-24 12:17:05', NULL),
(621, 34, 'Ghaziabad', 1, '2019-01-24 12:17:05', NULL),
(622, 34, 'Gorakhpur', 1, '2019-01-24 12:17:05', NULL),
(623, 34, 'Gunji', 1, '2019-01-24 12:17:05', NULL),
(624, 34, 'Jarwa', 1, '2019-01-24 12:17:05', NULL),
(625, 34, 'Jhulaghat (Pithoragarh)', 1, '2019-01-24 12:17:05', NULL),
(626, 34, 'Kanpur', 1, '2019-01-24 12:17:05', NULL),
(627, 34, 'Katarniyaghat', 1, '2019-01-24 12:17:05', NULL),
(628, 34, 'Khunwa', 1, '2019-01-24 12:17:05', NULL),
(629, 34, 'Loni', 1, '2019-01-24 12:17:05', NULL),
(630, 34, 'Lucknow', 1, '2019-01-24 12:17:05', NULL),
(631, 34, 'Meerut', 1, '2019-01-24 12:17:05', NULL),
(632, 34, 'Moradabad', 1, '2019-01-24 12:17:05', NULL),
(633, 34, 'Muzaffarnagar', 1, '2019-01-24 12:17:06', NULL),
(634, 34, 'Nepalgunj Road', 1, '2019-01-24 12:17:06', NULL),
(635, 34, 'Pakwara (Moradabad)', 1, '2019-01-24 12:17:06', NULL),
(636, 34, 'Pantnagar', 1, '2019-01-24 12:17:06', NULL),
(637, 34, 'Saharanpur', 1, '2019-01-24 12:17:06', NULL),
(638, 34, 'Sonauli', 1, '2019-01-24 12:17:06', NULL),
(639, 34, 'Surajpur', 1, '2019-01-24 12:17:06', NULL),
(640, 34, 'Tikonia', 1, '2019-01-24 12:17:06', NULL),
(641, 34, 'Varanasi', 1, '2019-01-24 12:17:06', NULL),
(642, 35, 'Alipurduar', 1, '2019-01-24 12:17:43', NULL),
(643, 35, 'Bankura', 1, '2019-01-24 12:21:37', NULL),
(644, 35, 'Bardhaman', 1, '2019-01-24 12:21:38', NULL),
(645, 35, 'Birbhum', 1, '2019-01-24 12:21:38', NULL),
(646, 35, 'Cooch Behar', 1, '2019-01-24 12:21:38', NULL),
(647, 35, 'Dakshin Dinajpur', 1, '2019-01-24 12:21:38', NULL),
(648, 35, 'Darjeeling', 1, '2019-01-24 12:21:38', NULL),
(649, 35, 'Hooghly', 1, '2019-01-24 12:21:38', NULL),
(650, 35, 'Howrah', 1, '2019-01-24 12:21:38', NULL),
(651, 35, 'Jalpaiguri', 1, '2019-01-24 12:21:38', NULL),
(652, 35, 'Kolkata', 1, '2019-01-24 12:21:38', NULL),
(653, 35, 'Maldah', 1, '2019-01-24 12:21:38', NULL),
(654, 35, 'Murshidabad', 1, '2019-01-24 12:21:39', NULL),
(655, 35, 'Nadia', 1, '2019-01-24 12:21:39', NULL),
(656, 35, 'North 24 Parganas', 1, '2019-01-24 12:21:39', NULL),
(657, 35, 'Paschim Medinipur', 1, '2019-01-24 12:21:39', NULL),
(658, 35, 'Purba Medinipur', 1, '2019-01-24 12:21:39', NULL),
(659, 35, 'Purulia', 1, '2019-01-24 12:21:39', NULL),
(660, 35, 'South 24 Parganas', 1, '2019-01-24 12:21:39', NULL),
(661, 35, 'Uttar Dinajpur', 1, '2019-01-24 12:21:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text,
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 'group A', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-23 07:46:15', NULL),
(2, 'group B\r\n', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-23 07:46:34', NULL),
(3, 'group C', 'Lorem ipsum dolor sit amet.', '1', '2019-01-30 05:57:21', '2019-01-30 00:27:21'),
(4, 'group D', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 05:59:15', NULL),
(5, 'group E', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:14', NULL),
(6, 'group F', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:14', NULL),
(7, 'group G', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:14', NULL),
(8, 'group H', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:14', NULL),
(9, 'group I', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt. Sit amet, consectetur adipisicing elit.', '1', '2019-01-30 06:01:14', NULL),
(10, 'group J', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:15', NULL),
(11, 'group K', 'Lorem ipsum dolor hte lanpcb, consectetur sit amet, consectetur adipisicing elit. Ipsum, incidunt.', '1', '2019-01-30 06:01:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `route`, `status`, `created_at`, `updated_at`) VALUES
(1, 'master', NULL, 1, '2019-02-04 00:54:42', '2019-02-04 00:54:42'),
(2, 'test master 2', NULL, 1, '2019-02-04 06:41:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_02_05_081322_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('nikita.webcom@gmail.com', '$2y$10$jYgHcHTf.jZwzwt2beijK.Op4263xktgxH7DUDdcA5th3VSppnHl.', '2019-01-19 04:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_engineer_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `unique_no` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_engineer_id`, `name`, `unique_no`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'product test for update', '1000', '1', '2019-01-30 07:58:40', '2019-01-30 02:28:40');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Andhra Pradesh', 1, '2019-01-24 05:46:52', NULL),
(2, 'Arunachal Pradesh', 1, '2019-01-24 05:54:21', NULL),
(3, 'Assam', 1, '2019-01-24 05:54:21', NULL),
(4, 'Bihar', 1, '2019-01-24 05:54:21', NULL),
(5, 'Chandigarh', 1, '2019-01-24 05:54:21', NULL),
(6, 'Chhattisgarh', 1, '2019-01-24 05:54:21', NULL),
(7, 'Dadra and Nagar Haveli', 1, '2019-01-24 05:54:21', NULL),
(8, 'Daman and Diu', 1, '2019-01-24 05:54:21', NULL),
(9, 'Delhi', 1, '2019-01-24 05:54:21', NULL),
(10, 'Goa', 1, '2019-01-24 05:54:22', NULL),
(11, 'Gujarat', 1, '2019-01-24 05:54:22', NULL),
(12, 'Haryana', 1, '2019-01-24 05:54:22', NULL),
(13, 'Himachal Pradesh', 1, '2019-01-24 05:54:22', NULL),
(14, 'Jammu and Kashmir', 1, '2019-01-24 05:54:22', NULL),
(15, 'Jharkhand', 1, '2019-01-24 05:54:22', NULL),
(16, 'Karnataka', 1, '2019-01-24 05:54:22', NULL),
(17, 'Kerala', 1, '2019-01-24 05:54:22', NULL),
(18, 'Lakshadweep', 1, '2019-01-24 05:54:22', NULL),
(19, 'Madhya Pradesh', 1, '2019-01-24 05:54:22', NULL),
(20, 'Maharashtra', 1, '2019-01-24 05:54:22', NULL),
(21, 'Manipur', 1, '2019-01-24 05:54:22', NULL),
(22, 'Meghalaya', 1, '2019-01-24 05:54:22', NULL),
(23, 'Mizoram', 1, '2019-01-24 05:54:23', NULL),
(24, 'Nagaland', 1, '2019-01-24 05:54:23', NULL),
(25, 'Orissa', 1, '2019-01-24 05:54:23', NULL),
(26, 'Pondicherry', 1, '2019-01-24 05:54:23', NULL),
(27, 'Punjab', 1, '2019-01-24 05:54:23', NULL),
(28, 'Rajasthan', 1, '2019-01-24 05:54:23', NULL),
(29, 'Sikkim', 1, '2019-01-24 05:54:23', NULL),
(30, 'Tamil Nadu', 1, '2019-01-24 05:54:23', NULL),
(31, 'Telangana', 1, '2019-01-24 11:48:05', NULL),
(32, 'Tripura', 1, '2019-01-24 11:48:31', NULL),
(33, 'Uttaranchal', 1, '2019-01-24 11:48:42', NULL),
(34, 'Uttar Pradesh', 1, '2019-01-24 11:49:17', NULL),
(35, 'West Bengal\r\n', 1, '2019-01-24 11:49:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_groups`
--

CREATE TABLE `sub_groups` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text,
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sub_groups`
--

INSERT INTO `sub_groups` (`id`, `group_id`, `name`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'sub-group A', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, rerum!', '1', '2019-01-23 11:23:50', NULL),
(2, 2, 'sub group B', 'Lorem ipsum dolor sit amet.', '1', '2019-01-30 07:23:53', '2019-01-30 01:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `sub_menus`
--

CREATE TABLE `sub_menus` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sub_menus`
--

INSERT INTO `sub_menus` (`id`, `menu_id`, `name`, `route`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'role', 'http://localhost/enterprise/public/role', 1, '2019-02-04 10:23:16', '2019-02-04 00:54:58'),
(2, 1, 'menu', 'http://localhost/enterprise/public/menu', 1, '2019-02-04 10:23:32', '2019-02-04 00:55:12'),
(3, 1, 'sub menu', 'http://localhost/enterprise/public/sub-menu', 1, '2019-02-04 10:23:49', '2019-02-04 00:55:26'),
(4, 1, 'user', 'http://localhost/enterprise/public/user', 1, '2019-02-04 10:24:05', '2019-02-04 00:55:38'),
(5, 1, 'group', 'http://localhost/enterprise/public/group', 1, '2019-02-04 10:24:21', '2019-02-04 00:56:03'),
(6, 1, 'sub group', 'http://localhost/enterprise/public/sub-group', 1, '2019-02-04 10:24:38', '2019-02-04 00:56:19'),
(7, 1, 'zone', 'http://localhost/enterprise/public/zones', 1, '2019-02-04 10:24:50', '2019-02-04 00:56:37'),
(8, 1, 'product', 'http://localhost/enterprise/public/products', 1, '2019-02-04 10:25:17', '2019-02-04 00:57:09'),
(9, 1, 'company', 'http://localhost/enterprise/public/companies', 1, '2019-02-04 10:25:30', '2019-02-04 00:57:49'),
(10, 1, 'client', 'http://localhost/enterprise/public/clients', 1, '2019-02-04 10:25:44', '2019-02-04 00:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `emp_code` varchar(255) DEFAULT NULL,
  `emp_designation` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `pan_card_no` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL COMMENT '1(admin), 2(manager), 3(engineer)',
  `role` varchar(255) DEFAULT NULL,
  `ph_no` varchar(50) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `pin_code` varchar(50) DEFAULT NULL,
  `address` text,
  `remarks` text,
  `status` varchar(10) NOT NULL DEFAULT '1' COMMENT '0(delete), 1(active), 7(assign), 9(transfer)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `title`, `first_name`, `middle_name`, `last_name`, `email`, `emp_code`, `emp_designation`, `dob`, `pan_card_no`, `password`, `remember_token`, `user_type`, `role`, `ph_no`, `state`, `district`, `pin_code`, `address`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'administrator', 'super', 'admin', 'nikita.webcom@gmail.com', 'Admin', NULL, NULL, NULL, '$2y$10$G7x3.cRBvel9BLycXaPkQeYfCPmPEV1F66QcQU.90vn3r7T0D/v7y', 'NNbtVcpiOv6K7oo3fvvg9w7SivK2YuJZ6A2fdTLRZfLg9PKsXXhXC7D0sXUD', 1, 'admin', '1234567890', 'Assam', 'kamrup (M)', '123456', NULL, NULL, '1', '2019-02-05 06:51:49', '2019-01-18 02:32:02'),
(2, NULL, 'manager new', NULL, NULL, 'manager@test.test', 'manager_new', NULL, NULL, NULL, '$2y$10$VTYbedwHTm87lBIHPk0Cz.iSb93bZmPBP7hm/x00cBU1YEgSq8Hu6', 'BtHPR73NWza9rlZDlkIQ8Mm90WRdUWgjDiHYu67qNLiYpn3H5oa3b5jXRhvT', 2, 'manager', '88764 56456', 'Assam', 'bongaigaon', '876877', NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro, possimus nam dolor, ullam eum maiores ratione repudiandae in mollitia nulla.', '1', '2019-02-05 07:59:41', '2019-01-28 01:53:35'),
(3, NULL, 'engineer new', NULL, NULL, 'eng@test.test', 'eng_new', NULL, NULL, NULL, '$2y$10$gK3B9NaO2VocDvVmIaaHtOwBqRoDXr3dllZGTYH67qQMfHhGJpG76', NULL, 3, 'engineer', '88769 87654', 'Assam', 'Goalpara', '435465', NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi modi, vel reprehenderit earum fuga, hic?', '1', '2019-01-31 04:39:56', '2019-01-30 23:09:56'),
(4, 'Miss', 'arunima', NULL, 'choudhury', 'arunima@test.test', 'emp010219120227', 'artist', '2018-12-05', '666666', '$2y$10$D1NGAK62dsk20CJCfwlsH.6ieIKYCqR0wBkZ0u4nsWQKb1DWeaYkq', NULL, 3, 'engineer', '87887 87868', 'Assam', 'Bongaigaon', '876877', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta, maiores cumque nam reiciendis. Beatae, facere. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta, maiores cumque nam reiciendis. Beatae, facere.', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta, maiores cumque nam reiciendis. Beatae, facere.', '1', '2019-02-01 06:32:27', '2019-02-01 06:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 'zone A', 'Lorem ipsum dolor sit amet.', '1', '2019-01-23 11:46:58', NULL),
(2, 'zone B', 'Lorem ipsum dolor sit amet, consectetur.', '1', '2019-01-30 07:44:14', '2019-01-30 02:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `zone_managers`
--

CREATE TABLE `zone_managers` (
  `id` int(11) NOT NULL,
  `user_manager_id` int(11) DEFAULT NULL,
  `zone_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL COMMENT 'transfer/assign',
  `reason_date` datetime DEFAULT NULL COMMENT 'transfer date/assign date',
  `status` varchar(10) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_engineers`
--
ALTER TABLE `assign_engineers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_managers`
--
ALTER TABLE `assign_managers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_groups`
--
ALTER TABLE `sub_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_menus`
--
ALTER TABLE `sub_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zone_managers`
--
ALTER TABLE `zone_managers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_engineers`
--
ALTER TABLE `assign_engineers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assign_managers`
--
ALTER TABLE `assign_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=662;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `sub_groups`
--
ALTER TABLE `sub_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sub_menus`
--
ALTER TABLE `sub_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `zone_managers`
--
ALTER TABLE `zone_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
