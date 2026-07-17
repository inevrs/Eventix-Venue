-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 12:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventix_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `icon` varchar(10) DEFAULT '✨'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `name`, `description`, `price`, `icon`) VALUES
(1, 'Audiovisual Gear', 'Speakers, projectors & microphone setup', 350.00, '🎙️'),
(2, 'Custom Lighting', 'Uplighting & ambient mood lighting', 280.00, '💡'),
(3, 'Furniture Upgrade', 'Premium tables, chairs & lounge sets', 450.00, '🪑'),
(4, 'Event Planning', 'Dedicated coordinator for your event', 600.00, '📋'),
(5, 'Specialty F&B Stations', 'Custom food & beverage stations', 500.00, '🍽️');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `guest_count` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `venue_id`, `start_date`, `end_date`, `guest_count`, `notes`, `status`, `created_at`) VALUES
(67, 68, 4, '2026-07-17', '2026-07-18', 200, '', 'confirmed', '2026-07-14 04:10:17'),
(68, 4, 4, '2026-07-17', '2026-07-17', 200, '', 'confirmed', '2026-07-16 08:22:07'),
(69, 4, 18, '2026-07-17', '2026-07-19', 100, '', 'confirmed', '2026-07-16 08:34:36'),
(70, 69, 6, '2026-09-14', '2026-09-16', 64, 'Seminar and workshop for 100 attendees.', 'confirmed', '0000-00-00 00:00:00'),
(71, 70, 9, '2026-08-19', '2026-08-20', 75, 'Hari Raya open house celebration.', 'confirmed', '0000-00-00 00:00:00'),
(72, 71, 11, '2026-12-05', '2026-12-07', 294, 'Team building event with breakout sessions.', 'confirmed', '0000-00-00 00:00:00'),
(73, 71, 11, '2026-08-05', '2026-08-07', 231, 'Hari Raya open house celebration.', 'confirmed', '0000-00-00 00:00:00'),
(74, 71, 11, '2026-09-02', '2026-09-03', 60, 'Seminar and workshop for 100 attendees.', 'confirmed', '0000-00-00 00:00:00'),
(75, 72, 4, '2026-11-28', '2026-11-29', 266, 'Family reunion gathering, buffet style catering.', 'confirmed', '0000-00-00 00:00:00'),
(76, 72, 18, '2026-10-02', '2026-10-03', 241, 'Year-end company party, casual dress code.', 'pending', '0000-00-00 00:00:00'),
(77, 72, 9, '2026-08-11', '2026-08-14', 227, 'Baby shower with themed decorations.', 'confirmed', '0000-00-00 00:00:00'),
(78, 73, 4, '2026-08-26', '2026-08-28', 207, 'Baby shower with themed decorations.', 'cancelled', '0000-00-00 00:00:00'),
(79, 73, 9, '2026-12-11', '2026-12-14', 253, 'Press conference and media event.', 'cancelled', '0000-00-00 00:00:00'),
(80, 73, 8, '2026-07-21', '2026-07-23', 57, 'ROM ceremony followed by dinner reception.', 'confirmed', '0000-00-00 00:00:00'),
(81, 74, 16, '2026-11-16', '2026-11-17', 229, 'Year-end company party, casual dress code.', 'confirmed', '0000-00-00 00:00:00'),
(82, 75, 8, '2026-09-11', '2026-09-12', 28, 'Farewell party for departing colleagues.', 'cancelled', '0000-00-00 00:00:00'),
(83, 75, 5, '2026-12-29', '2026-12-31', 53, 'Birthday celebration with DJ and dance floor required.', 'pending', '0000-00-00 00:00:00'),
(84, 75, 8, '2027-01-01', '2027-01-02', 159, 'Farewell party for departing colleagues.', 'cancelled', '0000-00-00 00:00:00'),
(85, 76, 9, '2026-09-02', '2026-09-05', 178, 'Networking event with cocktail stations.', 'cancelled', '0000-00-00 00:00:00'),
(86, 76, 9, '2026-09-28', '2026-10-01', 251, 'Farewell party for departing colleagues.', 'pending', '0000-00-00 00:00:00'),
(87, 76, 6, '2027-01-09', '2027-01-10', 281, 'Seminar and workshop for 100 attendees.', 'confirmed', '0000-00-00 00:00:00'),
(88, 77, 16, '2026-12-07', '2026-12-09', 219, 'Press conference and media event.', 'confirmed', '0000-00-00 00:00:00'),
(89, 78, 4, '2026-10-09', '2026-10-11', 269, 'Networking event with cocktail stations.', 'confirmed', '0000-00-00 00:00:00'),
(90, 78, 7, '2026-07-28', '2026-07-31', 270, 'Press conference and media event.', 'cancelled', '0000-00-00 00:00:00'),
(91, 78, 4, '2026-08-24', '2026-08-27', 106, 'Farewell party for departing colleagues.', 'confirmed', '0000-00-00 00:00:00'),
(92, 79, 16, '2026-09-05', '2026-09-08', 248, 'Family reunion gathering, buffet style catering.', 'confirmed', '0000-00-00 00:00:00'),
(93, 80, 6, '2026-09-18', '2026-09-20', 39, 'Press conference and media event.', 'pending', '0000-00-00 00:00:00'),
(94, 80, 17, '2026-07-25', '2026-07-27', 131, 'Corporate annual dinner for 80 people with live band setup.', 'pending', '0000-00-00 00:00:00'),
(95, 80, 9, '2026-07-25', '2026-07-28', 132, 'Baby shower with themed decorations.', 'confirmed', '0000-00-00 00:00:00'),
(96, 81, 8, '2026-12-27', '2026-12-29', 164, 'Year-end company party, casual dress code.', 'cancelled', '0000-00-00 00:00:00'),
(97, 81, 13, '2026-08-04', '2026-08-07', 159, 'Wedding reception - need fairy lights and floral arch.', 'pending', '0000-00-00 00:00:00'),
(98, 82, 17, '2026-11-05', '2026-11-08', 296, 'Wedding reception - need fairy lights and floral arch.', 'confirmed', '0000-00-00 00:00:00'),
(99, 83, 19, '2026-07-25', '2026-07-27', 126, 'Engagement ceremony - garden setup preferred.', 'cancelled', '0000-00-00 00:00:00'),
(100, 83, 16, '2026-10-18', '2026-10-21', 38, 'Hari Raya open house celebration.', 'confirmed', '0000-00-00 00:00:00'),
(101, 83, 6, '2026-11-08', '2026-11-11', 77, 'Engagement ceremony - garden setup preferred.', 'cancelled', '0000-00-00 00:00:00'),
(102, 84, 4, '2026-11-02', '2026-11-05', 60, 'Engagement ceremony - garden setup preferred.', 'pending', '0000-00-00 00:00:00'),
(103, 84, 19, '2026-12-11', '2026-12-14', 180, 'Baby shower with themed decorations.', 'confirmed', '0000-00-00 00:00:00'),
(104, 84, 19, '2026-07-29', '2026-07-30', 208, 'Family reunion gathering, buffet style catering.', 'cancelled', '0000-00-00 00:00:00'),
(105, 85, 7, '2026-10-17', '2026-10-20', 242, 'Networking event with cocktail stations.', 'cancelled', '0000-00-00 00:00:00'),
(106, 85, 6, '2027-01-01', '2027-01-03', 60, 'Corporate annual dinner for 80 people with live band setup.', 'pending', '0000-00-00 00:00:00'),
(107, 85, 9, '2026-07-23', '2026-07-25', 165, 'Hari Raya open house celebration.', 'confirmed', '0000-00-00 00:00:00'),
(108, 86, 13, '2026-12-07', '2026-12-10', 91, 'Press conference and media event.', 'confirmed', '0000-00-00 00:00:00'),
(109, 87, 16, '2026-07-24', '2026-07-27', 199, 'Charity gala dinner with auction setup.', 'confirmed', '0000-00-00 00:00:00'),
(110, 88, 7, '2026-12-11', '2026-12-12', 134, 'Seminar and workshop for 100 attendees.', 'cancelled', '0000-00-00 00:00:00'),
(111, 89, 8, '2027-01-01', '2027-01-02', 98, 'Birthday celebration with DJ and dance floor required.', 'confirmed', '0000-00-00 00:00:00'),
(112, 89, 7, '2026-11-16', '2026-11-19', 162, 'Family reunion gathering, buffet style catering.', 'confirmed', '0000-00-00 00:00:00'),
(113, 89, 19, '2027-01-04', '2027-01-05', 221, 'Company quarterly town hall meeting.', 'confirmed', '0000-00-00 00:00:00'),
(114, 90, 7, '2026-08-02', '2026-08-05', 123, 'Team building event with breakout sessions.', 'confirmed', '0000-00-00 00:00:00'),
(115, 90, 6, '2027-01-10', '2027-01-13', 150, 'Team building event with breakout sessions.', 'confirmed', '0000-00-00 00:00:00'),
(116, 91, 5, '2026-08-26', '2026-08-29', 91, 'Year-end company party, casual dress code.', 'confirmed', '0000-00-00 00:00:00'),
(117, 91, 17, '2026-09-03', '2026-09-05', 221, 'Hari Raya open house celebration.', 'pending', '0000-00-00 00:00:00'),
(118, 91, 11, '2026-08-24', '2026-08-27', 263, 'Charity gala dinner with auction setup.', 'pending', '0000-00-00 00:00:00'),
(119, 92, 4, '2026-11-28', '2026-11-29', 83, 'Company quarterly town hall meeting.', 'confirmed', '0000-00-00 00:00:00'),
(120, 93, 18, '2026-08-02', '2026-08-04', 200, 'Influencer meetup with photo booth areas.', 'confirmed', '0000-00-00 00:00:00'),
(121, 93, 7, '2026-12-10', '2026-12-11', 23, 'Anniversary dinner, intimate setting for 30 guests.', 'confirmed', '0000-00-00 00:00:00'),
(122, 94, 18, '2027-01-08', '2027-01-10', 74, 'Anniversary dinner, intimate setting for 30 guests.', 'pending', '0000-00-00 00:00:00'),
(123, 94, 13, '2026-07-18', '2026-07-20', 173, 'Anniversary dinner, intimate setting for 30 guests.', 'confirmed', '0000-00-00 00:00:00'),
(124, 94, 6, '2026-11-18', '2026-11-21', 45, 'Baby shower with themed decorations.', 'confirmed', '0000-00-00 00:00:00'),
(125, 95, 16, '2026-08-15', '2026-08-17', 51, 'Anniversary dinner, intimate setting for 30 guests.', 'pending', '0000-00-00 00:00:00'),
(126, 95, 7, '2026-08-26', '2026-08-28', 214, 'Anniversary dinner, intimate setting for 30 guests.', 'pending', '0000-00-00 00:00:00'),
(127, 95, 8, '2026-08-16', '2026-08-18', 118, 'ROM ceremony followed by dinner reception.', 'pending', '0000-00-00 00:00:00'),
(128, 96, 7, '2026-11-11', '2026-11-14', 111, 'Engagement ceremony - garden setup preferred.', 'cancelled', '0000-00-00 00:00:00'),
(129, 96, 4, '2026-09-20', '2026-09-23', 169, 'Year-end company party, casual dress code.', 'cancelled', '0000-00-00 00:00:00'),
(130, 97, 5, '2026-08-19', '2026-08-21', 163, 'Farewell party for departing colleagues.', 'confirmed', '0000-00-00 00:00:00'),
(131, 98, 9, '2026-08-10', '2026-08-13', 139, 'Baby shower with themed decorations.', 'pending', '0000-00-00 00:00:00'),
(132, 99, 5, '2026-10-10', '2026-10-11', 109, 'Engagement ceremony - garden setup preferred.', 'pending', '0000-00-00 00:00:00'),
(133, 99, 5, '2026-10-01', '2026-10-02', 76, 'Family reunion gathering, buffet style catering.', 'confirmed', '0000-00-00 00:00:00'),
(134, 100, 16, '2026-07-29', '2026-07-30', 261, 'Engagement ceremony - garden setup preferred.', 'confirmed', '0000-00-00 00:00:00'),
(135, 100, 11, '2026-09-26', '2026-09-27', 174, 'Charity gala dinner with auction setup.', 'cancelled', '0000-00-00 00:00:00'),
(136, 100, 9, '2026-11-14', '2026-11-17', 163, 'Wedding reception - need fairy lights and floral arch.', 'confirmed', '0000-00-00 00:00:00'),
(137, 101, 11, '2026-08-24', '2026-08-25', 241, 'Team building event with breakout sessions.', 'pending', '0000-00-00 00:00:00'),
(138, 101, 8, '2026-11-24', '2026-11-25', 165, 'ROM ceremony followed by dinner reception.', 'cancelled', '0000-00-00 00:00:00'),
(139, 102, 11, '2026-09-11', '2026-09-12', 297, 'Engagement ceremony - garden setup preferred.', 'cancelled', '0000-00-00 00:00:00'),
(140, 103, 19, '2026-11-18', '2026-11-20', 288, 'Networking event with cocktail stations.', 'confirmed', '0000-00-00 00:00:00'),
(141, 103, 17, '2026-11-21', '2026-11-22', 246, 'Year-end company party, casual dress code.', 'cancelled', '0000-00-00 00:00:00'),
(142, 103, 13, '2026-11-14', '2026-11-17', 234, 'Baby shower with themed decorations.', 'pending', '0000-00-00 00:00:00'),
(143, 104, 17, '2026-12-10', '2026-12-11', 143, 'Team building event with breakout sessions.', 'confirmed', '0000-00-00 00:00:00'),
(144, 104, 5, '2026-08-03', '2026-08-04', 247, 'Birthday celebration with DJ and dance floor required.', 'cancelled', '0000-00-00 00:00:00'),
(145, 105, 9, '2026-09-30', '2026-10-03', 262, 'Anniversary dinner, intimate setting for 30 guests.', 'confirmed', '0000-00-00 00:00:00'),
(146, 105, 7, '2026-08-09', '2026-08-10', 56, 'Influencer meetup with photo booth areas.', 'confirmed', '0000-00-00 00:00:00'),
(147, 106, 18, '2026-12-12', '2026-12-15', 271, 'Corporate annual dinner for 80 people with live band setup.', 'pending', '0000-00-00 00:00:00'),
(148, 107, 8, '2026-11-04', '2026-11-05', 289, 'Charity gala dinner with auction setup.', 'confirmed', '0000-00-00 00:00:00'),
(149, 107, 11, '2026-10-08', '2026-10-11', 257, 'Company quarterly town hall meeting.', 'confirmed', '0000-00-00 00:00:00'),
(150, 107, 11, '2026-08-20', '2026-08-22', 279, 'Birthday celebration with DJ and dance floor required.', 'confirmed', '0000-00-00 00:00:00'),
(151, 108, 4, '2026-07-21', '2026-07-23', 161, 'Seminar and workshop for 100 attendees.', 'confirmed', '0000-00-00 00:00:00'),
(152, 68, 13, '2026-07-17', '2026-07-17', 20, '', 'confirmed', '2026-07-16 08:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `booking_addons`
--

CREATE TABLE `booking_addons` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `method`, `status`, `paid_at`, `payment_proof`) VALUES
(5, 67, 2000.00, 'Online Banking', 'paid', '2026-07-14 04:10:33', NULL),
(6, 68, 1000.00, 'Online Banking', 'paid', '2026-07-16 08:22:17', NULL),
(7, 69, 10500.00, 'Online Banking', 'paid', '2026-07-16 08:34:49', 'uploads/payments/proof_69_1784190889.jpg'),
(8, 70, 2700.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(9, 71, 4000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(10, 72, 9000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(11, 73, 9000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(12, 74, 6000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(13, 75, 2000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(14, 76, 7000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(15, 77, 8000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(16, 80, 4500.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(17, 81, 3000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(18, 83, 6000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(19, 86, 8000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(20, 87, 1800.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(21, 88, 4500.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(22, 89, 3000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(23, 91, 4000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(24, 92, 6000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(25, 93, 2700.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(26, 94, 6000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(27, 95, 8000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(28, 97, 12000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(29, 98, 8000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(30, 100, 6000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(31, 102, 4000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(32, 103, 12000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(33, 106, 2700.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(34, 107, 6000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(35, 108, 12000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(36, 109, 6000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(37, 111, 3000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(38, 112, 2800.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(39, 113, 6000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(40, 114, 2800.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(41, 115, 3600.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(42, 116, 8000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(43, 117, 6000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(44, 118, 12000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(45, 119, 2000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(46, 120, 10500.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(47, 121, 1400.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(48, 122, 10500.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(49, 123, 9000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(50, 124, 3600.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(51, 125, 4500.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(52, 126, 2100.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(53, 127, 4500.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(54, 130, 6000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(55, 131, 8000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(56, 132, 4000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(57, 133, 4000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(58, 134, 3000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(59, 136, 8000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(60, 137, 6000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(61, 140, 9000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(62, 142, 12000.00, 'Credit Card', 'paid', '0000-00-00 00:00:00', NULL),
(63, 143, 4000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(64, 145, 8000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(65, 146, 1400.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(66, 147, 14000.00, 'eWallet', 'paid', '0000-00-00 00:00:00', NULL),
(67, 148, 3000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(68, 149, 12000.00, 'Online Banking', 'paid', '0000-00-00 00:00:00', NULL),
(69, 150, 9000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(70, 151, 3000.00, 'Debit Card', 'paid', '0000-00-00 00:00:00', NULL),
(71, 152, 3000.00, 'Online Banking', 'paid', '2026-07-16 08:54:41', 'uploads/payments/proof_152_1784192081.png');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `venue_id`, `rating`, `review`, `created_at`) VALUES
(63, 4, 4, 4, 'Good venue', '2026-07-16 08:27:25'),
(64, 71, 11, 5, 'Absolutely stunning venue! The ambiance was perfect for our corporate dinner. Staff was incredibly helpful and responsive. Would definitely book again.', '2026-05-17 02:39:18'),
(65, 72, 4, 5, 'We hosted our wedding reception here and it was magical. The views were breathtaking and the space was beautifully maintained. Highly recommend!', '2026-07-15 02:39:18'),
(66, 73, 8, 4, 'Great venue with excellent facilities. The lighting setup was impressive. Only minor issue was parking, but overall a fantastic experience.', '2026-07-11 02:39:18'),
(67, 74, 16, 5, 'Best event space in KL hands down. Our product launch was a massive success thanks to the amazing setup and professional staff.', '2026-06-11 02:39:18'),
(68, 76, 6, 4, 'Very spacious and well-maintained. The acoustics were great for our live band. Food catering options could be more diverse though.', '2026-07-04 02:39:18'),
(69, 77, 16, 5, 'Hosted a birthday party here and everyone was blown away. The panoramic views at night are simply gorgeous. Worth every ringgit.', '2026-06-25 02:39:18'),
(70, 78, 4, 3, 'Decent venue for the price. The space is nice but the air conditioning was a bit inconsistent during our event. Staff was friendly though.', '2026-06-05 02:39:18'),
(71, 79, 16, 4, 'Really enjoyed the outdoor space. Perfect for our garden-themed engagement party. The greenery and natural lighting were beautiful.', '2026-06-15 02:39:18'),
(72, 80, 9, 5, 'Incredible venue! The setup was seamless and the event coordinator was super professional. Our guests couldn\'t stop complimenting the space.', '2026-06-21 02:39:18'),
(73, 82, 17, 4, 'Booked for our annual gala dinner. The ballroom was elegant and the audiovisual equipment was top-notch. Minor delay in setup but all went smoothly.', '2026-06-15 02:39:18'),
(74, 83, 16, 5, 'Absolutely loved this place. The rooftop setting with KLCC views made our anniversary dinner unforgettable. Staff went above and beyond.', '2026-07-05 02:39:18'),
(75, 85, 9, 3, 'Good space overall but felt a bit cramped for 200 guests. The venue looks much bigger in photos. That said, the food was excellent.', '2026-06-03 02:39:18'),
(76, 86, 13, 4, 'Professional team, beautiful venue, and great location. Our seminar went perfectly. Would appreciate more parking options nearby.', '2026-06-22 02:39:18'),
(77, 89, 8, 5, 'My daughter\'s 21st birthday party was held here and it was perfect! The DJ area was well set up and the dance floor was spacious.', '2026-06-10 02:39:18'),
(78, 89, 7, 4, 'Solid choice for mid-sized events. The decor options they offer are beautiful and reasonably priced. Booking process was smooth.', '2026-05-17 02:39:18'),
(79, 89, 19, 5, 'We\'ve used this venue three times now for different company events and it never disappoints. The consistency in quality is remarkable.', '2026-06-14 02:39:18'),
(80, 90, 7, 4, 'Beautiful architecture and great photo opportunities throughout the venue. Our guests loved the aesthetic. Catering was delicious too.', '2026-05-22 02:39:18'),
(81, 90, 6, 3, 'Average experience. The venue itself is lovely but communication before the event was slow. During the event everything was fine though.', '2026-05-20 02:39:18'),
(82, 91, 5, 5, 'Hosted our charity gala here and raised record funds! The venue set the perfect tone. Elegant, spacious, and the staff was phenomenal.', '2026-05-18 02:39:18'),
(83, 92, 4, 4, 'Great for intimate gatherings. The cozy atmosphere made our family reunion special. Appreciated the flexible timing arrangements.', '2026-06-21 02:39:18'),
(84, 94, 13, 5, 'This venue exceeded all our expectations! From the initial site visit to the event day, everything was handled with utmost professionalism.', '2026-07-03 02:39:18'),
(85, 97, 5, 4, 'Very good venue for corporate events. Modern facilities, great WiFi, and the breakout rooms were perfect for our workshop sessions.', '2026-06-17 02:39:18'),
(86, 99, 5, 5, 'Our engagement ceremony was held here and it was dreamy. The natural light during golden hour was perfect for photos. Love this place!', '2026-06-25 02:39:18'),
(87, 100, 16, 3, 'Nice venue but the pricing is on the higher side for what you get. The view does make up for it though. Staff could be more attentive.', '2026-06-10 02:39:18'),
(88, 100, 9, 5, 'Absolutely perfect for our company\'s year-end party. The team building activities in the garden area were fantastic. Best venue in Selangor!', '2026-06-30 02:39:18'),
(89, 103, 19, 4, 'Hosted a product showcase here. The open layout was great for our exhibition booths. Sound system was crystal clear. Highly recommend.', '2026-06-26 02:39:18'),
(90, 104, 17, 5, 'From the moment we walked in, we knew this was the venue for our wedding. The garden ceremony was picture-perfect. Thank you for making our day special!', '2026-06-22 02:39:18'),
(91, 105, 9, 4, 'Good venue with attentive staff. The event coordination team helped us plan everything down to the last detail. Very impressed.', '2026-07-14 02:39:18'),
(92, 105, 7, 5, 'A hidden gem! We discovered this venue through a friend\'s recommendation and weren\'t disappointed. The sunset views are unreal.', '2026-07-11 02:39:18'),
(93, 107, 8, 4, 'Booked for our quarterly town hall meeting. The presentation facilities were excellent and the refreshments were top quality.', '2026-07-07 02:39:18'),
(94, 107, 11, 5, 'Our family\'s Hari Raya open house was held here and it was the talk of the family. Everyone loved the spacious garden and the modern interior.', '2026-06-23 02:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`, `profile_picture`, `bio`) VALUES
(1, 'Admin', 'admin@eventix.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-10 04:38:07', 'uploads/profiles/1782206034_Screenshot 2026-06-23 171332.png', ''),
(4, 'Isyraf Nasruddin', 'isyraf@gmail.com', '+601119835758', '$2y$10$GCo6AkTleJNVfnldoNdIGuaZ0hHFL.LZCgDHoXdrtJoEFG68mWjgy', 'customer', '2026-06-16 02:21:08', 'uploads/profiles/1781576519_hmmw.jpg', ''),
(5, 'Muhammad Ridhuan', 'ridhuan@gmail.com', '+601124639425', '$2y$10$X31OUx/R1OsOjdNvFf.dTuM12FTRCJzttRDHstXtYJjVbZBeUONUu', 'manager', '2026-06-16 02:36:45', 'uploads/profiles/1782206812_Screenshot 2026-06-23 172639.png', ''),
(6, 'Anas Syukri', 'anas@gmail.com', '+60108476099', '$2y$10$aenbLnAaAtpIVKKeXkkfueh5olXCP7yPiRePrjDSygm8uIb7hB9Vu', 'manager', '2026-06-23 09:32:50', 'uploads/profiles/1782207303_a044c1c5-d529-4046-a7a5-0b06d4840d8a.jpg', ''),
(68, 'Ahmad Zaidan Bin Zahren', 'zidanezahren@gmail.com', '+60149530029', '$2y$10$r4YdTVB3bVUK4izwYEbMEuwcJopv48M3Al0t4SwhWMdZCXeasbZXO', 'customer', '2026-07-14 04:03:13', NULL, NULL),
(69, 'James Lee', 'james@gmail.com', '+60121110001', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-24 08:39:17', NULL, NULL),
(70, 'Sarah Tan', 'sarah@gmail.com', '+60121110002', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-02 08:39:17', NULL, NULL),
(71, 'David Wong', 'david@gmail.com', '+60121110003', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-17 08:39:17', NULL, NULL),
(72, 'Emily Chen', 'emily@gmail.com', '+60121110004', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-27 08:39:17', NULL, NULL),
(73, 'Michael Lim', 'michael@gmail.com', '+60121110005', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-05 08:39:17', NULL, NULL),
(74, 'Jessica Ng', 'jessica@gmail.com', '+60121110006', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-24 08:39:17', NULL, NULL),
(75, 'Daniel Kumar', 'daniel@gmail.com', '+60121110007', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-24 08:39:17', NULL, NULL),
(76, 'Rachel Ahmad', 'rachel@gmail.com', '+60121110008', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-29 08:39:17', NULL, NULL),
(77, 'Ryan Chong', 'ryan@gmail.com', '+60121110009', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-07-01 08:39:17', NULL, NULL),
(78, 'Sophia Abdullah', 'sophia@gmail.com', '+60121110010', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-21 08:39:17', NULL, NULL),
(79, 'Kevin Yap', 'kevin@gmail.com', '+60121110011', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-26 08:39:17', NULL, NULL),
(80, 'Amanda Soh', 'amanda@gmail.com', '+60121110012', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-18 08:39:17', NULL, NULL),
(81, 'Justin Ooi', 'justin@gmail.com', '+60121110013', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-22 08:39:17', NULL, NULL),
(82, 'Natalie Goh', 'natalie@gmail.com', '+60121110014', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-07-09 08:39:17', NULL, NULL),
(83, 'Brandon Teo', 'brandon@gmail.com', '+60121110015', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-25 08:39:17', NULL, NULL),
(84, 'Melissa Raj', 'melissa@gmail.com', '+60121110016', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-19 08:39:17', NULL, NULL),
(85, 'Andrew Sim', 'andrew@gmail.com', '+60121110017', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-01 08:39:17', NULL, NULL),
(86, 'Chloe Foo', 'chloe@gmail.com', '+60121110018', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-27 08:39:17', NULL, NULL),
(87, 'Marcus Koh', 'marcus@gmail.com', '+60121110019', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-18 08:39:17', NULL, NULL),
(88, 'Hannah Yeoh', 'hannah@gmail.com', '+60121110020', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-28 08:39:17', NULL, NULL),
(89, 'Timothy Ong', 'timothy@gmail.com', '+60121110021', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-02 08:39:17', NULL, NULL),
(90, 'Grace Lau', 'grace@gmail.com', '+60121110022', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-06 08:39:17', NULL, NULL),
(91, 'Patrick Chin', 'patrick@gmail.com', '+60121110023', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-31 08:39:17', NULL, NULL),
(92, 'Victoria Phua', 'victoria@gmail.com', '+60121110024', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-29 08:39:17', NULL, NULL),
(93, 'Samuel Ho', 'samuel@gmail.com', '+60121110025', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-09 08:39:17', NULL, NULL),
(94, 'Olivia Nair', 'olivia@gmail.com', '+60121110026', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-30 08:39:17', NULL, NULL),
(95, 'Jason Yong', 'jason@gmail.com', '+60121110027', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-15 08:39:17', NULL, NULL),
(96, 'Ashley Gan', 'ashley@gmail.com', '+60121110028', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-07-02 08:39:17', NULL, NULL),
(97, 'Christopher Loh', 'christopher@gmail.com', '+60121110029', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-06 08:39:17', NULL, NULL),
(98, 'Stephanie Wee', 'stephanie@gmail.com', '+60121110030', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-05 08:39:17', NULL, NULL),
(99, 'Nicholas Tan', 'nicholas@gmail.com', '+60121110031', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-25 08:39:17', NULL, NULL),
(100, 'Vanessa Khoo', 'vanessa@gmail.com', '+60121110032', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-30 08:39:17', NULL, NULL),
(101, 'Alexander Chia', 'alexander@gmail.com', '+60121110033', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-29 08:39:17', NULL, NULL),
(102, 'Isabelle Low', 'isabelle@gmail.com', '+60121110034', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-09 08:39:17', NULL, NULL),
(103, 'Benjamin Heng', 'benjamin@gmail.com', '+60121110035', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-02 08:39:17', NULL, NULL),
(104, 'Audrey Teh', 'audrey@gmail.com', '+60121110036', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-24 08:39:17', NULL, NULL),
(105, 'Jonathan Liew', 'jonathan@gmail.com', '+60121110037', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-05-12 08:39:17', NULL, NULL),
(106, 'Megan Soo', 'megan@gmail.com', '+60121110038', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-26 08:39:17', NULL, NULL),
(107, 'Dylan Chua', 'dylan@gmail.com', '+60121110039', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-06-16 08:39:17', NULL, NULL),
(108, 'Fiona Leong', 'fiona@gmail.com', '+60121110040', '$2y$10$BfLZawYcjEZibe9DfsMexedl07O8yVz2L8Jx03ntHUU/M7hVi6dc.', 'customer', '2026-04-18 08:39:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `manager_id`, `name`, `location`, `capacity`, `price_per_day`, `description`, `status`, `created_at`) VALUES
(4, 5, 'The Estate on Federal Hill', 'Bukit Damansara,Kuala Lumpur', 350, 1000.00, 'A timeless landmark in the heart of Kuala Lumpur, where heritage meets modernity.\r\nWhitehouse @ The Estate on Federal Hill offers more than just space, it’s a sanctuary where architecture, nature, and meaningful gatherings come together.\r\n\r\nSpread across 3,000 sqft of indoor event space, 4,000 sqft of modern F&B-ready retail space, and a stunning 9,000 sqft garden with KL city views, Whitehouse is your perfect escape — right within the city.\r\n\r\nWhether you’re hosting a corporate event or celebrating personal milestones, this venue offers both serenity and statement.\r\n\r\nEscape the hustle and bustle. Embrace heritage, greenery, and intention.\r\n\r\nPerfect for:\r\n🚀 Product Launches & Brand Activations\r\n\r\nMake a statement your audience won’t forget. From elegant product reveals to full-on experiential brand showcases, the space adapts to your imagination.\r\n\r\n🎥 Production Shoots\r\n\r\nMinimalist architecture, heritage textures, and natural light — perfect for commercials, fashion shoots, music videos, and brand storytelling.\r\n\r\n🪩 Private Parties\r\n\r\nCelebrate birthdays, milestones, or just life itself in a venue that’s both stylish and soulful. Sunset drinks in the garden? Yes, please.\r\n\r\n📸 Press / Influencer Events\r\n\r\nCurate a photogenic experience for media & content creators with natural lighting, aesthetic corners, and a vibe that travels well on social.\r\n\r\n🏢 Corporate Events & Team Dinners\r\n\r\nWhether you’re thanking the team or closing Q4, the space offers a balance of professionalism and warmth — with skyline views to toast your wins.\r\n\r\n💍 Weddings & ROM\r\n\r\nSay “I do” in a garden sanctuary or host an intimate dinner under the stars. Perfect for those who want something low-key, yet unforgettable', 'active', '2026-06-23 18:04:08'),
(5, 5, 'Grand Lumière Ballroom (Puchong)', 'Puchong,Selangor', 700, 2000.00, 'Where Every Celebration Shines Brightest !\r\n\r\nStep into a world of refined elegance at Grand Lumière Ballroom — Puchong\'s premier destination for grand celebrations and distinguished gatherings. Nestled on the 5th floor, our magnificent modern ballroom is thoughtfully designed to transform every occasion into an unforgettable masterpiece.\r\n\r\nBathed in professional lighting and complemented by a state-of-the-art sound system, the Grand Lumière Ballroom sets the perfect stage for life\'s most cherished moments. Whether you envision an intimate banquet or a lavish grand affair, our versatile layout adapts seamlessly to bring your vision to life — accommodating up to 650 guests for a seated banquet or 1,000 guests for a standing reception.\r\n\r\nFrom opulent wedding banquets and prestigious corporate galas to distinguished association functions and exclusive private celebrations — every event is elevated by our dedicated team and full in-house banquet services, ensuring a seamless and memorable experience from start to finish.\r\n\r\nAt Grand Lumière Ballroom, we believe every detail matters. Let us illuminate your most precious moments with grace, grandeur, and impeccable service.', 'active', '2026-06-23 18:07:43'),
(6, 5, 'KLoé Hotel, Poolside Studio', 'Bukit Bintang,Kuala Lumpur', 50, 900.00, 'Located in the heart of Kuala Lumpur’s most dynamic district, KLoé Hotel delivers a refreshing alternative to conventional venues perfect for hosts looking to create meaningful, memorable experiences.\r\n\r\nIntroducing Studio, a light filled-multipurpose space located by the poolside in the hotel, Studio charms with its rustic brick walls, warm teak wood floors, and floor-to-ceiling windows, offering a unique setting that strikes the perfect balance between formal and casual.\r\n\r\nTogether with our restaurant partner - Mauceri who is known for its fresh handmade pasta and contemporary authentic Italian cuisine, Mauceri brings a warm and elevated dining experience to every occasion.\r\n\r\nPerfect for:\r\nProduct launches\r\nPrivate dinners & social celebrations\r\nCreative talks, panel discussions & community events', 'active', '2026-06-23 18:11:54'),
(7, 5, 'Cyberview Resort & Spa', 'Cyberjaya,Selangor', 200, 700.00, 'Cyberview has no pesky curfews so party as long as the booze lasts. The unstructured space – though fairly bare-bone with just hills as the scenery – allows you to be elegant or as casual as you like since the manicured lawn already provides a ready-made frame for photo-op or exchanging vows. Just expect small shortcomings like the rain, heat and sweaty guests – in other words, do not skimp on tents and fans.\r\n\r\nSuitable for garden weddings, outdoor parties, corporate functions, birthday celebrations, family functions, social gatherings.', 'active', '2026-06-23 18:14:37'),
(8, 5, 'HEMISFERA Luxury Sky Hall', 'Jalan P. Ramlee,Kuala Lumpur', 300, 1500.00, 'Elevate Your Celebrations at HEMISFERA Luxury Sky Hall\r\n\r\nDiscover the breathtaking beauty of HEMISFERA Luxury Sky Hall, a premier event venue perched 288 meters above ground, offering the highest banquet hall experience in Southeast Asia. With stunning panoramic views of the Kuala Lumpur skyline, HEMISFERA Luxury Sky Hall transforms any event into an extraordinary occasion.\r\n\r\n\r\nUnforgettable Events Awaits\r\n\r\nAccommodating up to 300 seated and 400 standing guests, HEMISFERA Luxury Sky Hall is the perfect setting for an array of celebrations, from lavish weddings and elegant dinners to corporate functions and seminars. Our sophisticated space features exquisite décor and a serene atmosphere, making it ideal for both formal and intimate gatherings.\r\n\r\nCulinary Excellence\r\n\r\nIndulge in a delightful culinary journey with our carefully curated menu, showcasing a fusion of Chinese and contemporary cuisines. While external catering is not permitted, our in-house team is dedicated to providing exceptional service and flavors that will leave a lasting impression on your guests.\r\n\r\nAn Experience Above the Rest\r\n\r\nImagine your guests mingling under the enchanting glow of the city lights, celebrating life’s milestones in an indoor setting that feels like a private sky sanctuary. For inquiries or to begin planning your next unforgettable event at HEMISFERA Luxury Sky Hall, please contact us for more information.\r\n\r\nMinimum Spend Information\r\nWith a minimum spend of RM180 per person for at least 30 guests, enjoy tailored pricing options as your guest list grows. Let us make your vision a reality at HEMISFERA Luxury Sky Hall, where elegance meets celestial charm.', 'active', '2026-06-23 18:23:08'),
(9, 5, 'Cielo Rooftop - Dining and Lounge', 'Jalan Yap Kwan Seng, Kuala Lumpur', 40, 2000.00, 'Perched on the rooftop with breathtaking KLCC views, this intimate venue offers a charming and elevated setting for private gatherings, celebrations, and small events. Surrounded by the city skyline, the space features a relaxed yet stylish atmosphere that feels both exclusive and welcoming. Perfect for cocktail evenings, cozy meetups, and social occasions, guests can unwind with fresh air, scenic views, and warm ambiance.\r\n\r\nMore than just a dining spot, the lounge also serves as a comfortable haven for quick coffee breaks, casual meals, and afternoon catch-ups — making it an ideal destination for both relaxation and memorable gatherings', 'active', '2026-06-23 18:25:23'),
(11, 6, 'Elegant Rooftop Venue', 'Jalan Dutamas 2, Kompleks Kerajaan, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 300, 3000.00, 'A contemporary rooftop event space with modern décor and panoramic views. Perfect for corporate dinners, team celebrations, and social networking events.', 'active', '2026-06-25 17:07:40'),
(13, 6, 'Malaysian Petroleum Club', 'No, 42. Tower 2, Petronas Twin Tower, Kuala Lumpur City Centre, 50088 Kuala Lumpur, Malaysia', 250, 3000.00, 'Host your annual dinner in the timeless elegance of the Malaysian Petroleum Club. Featuring grand interiors, refined décor, and panoramic views of Kuala Lumpur, this prestigious venue delivers a luxurious atmosphere ideal for formal corporate events, executive gatherings, and distinguished gala celebrations.', 'active', '2026-06-25 17:13:52'),
(16, 6, 'UpperDeck KL', '158-1, Jalan Petaling, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 150, 1500.00, 'A contemporary rooftop venue with stunning city views and an energetic atmosphere. Ideal for birthday parties, evening celebrations, and social events, offering a memorable setting for guests to enjoy and celebrate together.', 'active', '2026-06-25 17:25:17'),
(17, 6, 'Vida Event Space', 'Level 22 VIDA, 1D, Jalan Ceylon, Bukit Ceylon, 50200 Kuala Lumpur, Malaysia', 250, 2000.00, 'A versatile event venue designed for creative and vibrant birthday celebrations. With spacious interiors and flexible layouts, it is suitable for themed parties, family events, and social gatherings of various sizes.', 'active', '2026-06-25 17:27:26'),
(18, 6, 'Bayswater KLCC', '8, Jalan Sejahtera, Kampung Datuk Keramat, 55000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 300, 3500.00, 'A contemporary event venue located near Kuala Lumpur\'s city centre. With stylish interiors and stunning urban views, it provides an elegant setting for intimate to medium-sized wedding celebrations.', 'active', '2026-06-25 17:40:07'),
(19, 6, 'The Grounds', 'Ground Floor, Block B, MAHSA AVENUE Jalan Elmu, off, Jln Profesor Diraja Ungku Aziz, 59100 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 350, 3000.00, 'A versatile event space combining modern design with an open and welcoming atmosphere. Suitable for wedding ceremonies, receptions, and customized themed celebrations.', 'active', '2026-06-25 17:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `venue_images`
--

CREATE TABLE `venue_images` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_thumbnail` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_images`
--

INSERT INTO `venue_images` (`id`, `venue_id`, `image_path`, `is_thumbnail`, `sort_order`) VALUES
(6, 4, 'uploads/venues/venue_4_thumb_1782237848.jpg', 1, 0),
(7, 4, 'uploads/venues/venue_4_gallery_1782237848_0.jpg', 0, 0),
(8, 4, 'uploads/venues/venue_4_gallery_1782237848_1.jpg', 0, 1),
(9, 4, 'uploads/venues/venue_4_gallery_1782237848_2.jpg', 0, 2),
(10, 5, 'uploads/venues/venue_5_thumb_1782238063.jpg', 1, 0),
(11, 5, 'uploads/venues/venue_5_gallery_1782238063_0.jpg', 0, 0),
(12, 5, 'uploads/venues/venue_5_gallery_1782238063_1.jpg', 0, 1),
(13, 5, 'uploads/venues/venue_5_gallery_1782238063_2.jpg', 0, 2),
(14, 6, 'uploads/venues/venue_6_thumb_1782238314.jpg', 1, 0),
(15, 6, 'uploads/venues/venue_6_gallery_1782238314_0.jpg', 0, 0),
(16, 6, 'uploads/venues/venue_6_gallery_1782238314_1.jpg', 0, 1),
(17, 7, 'uploads/venues/venue_7_thumb_1782238477.jpg', 1, 0),
(18, 8, 'uploads/venues/venue_8_thumb_1782238988.jpg', 1, 0),
(19, 8, 'uploads/venues/venue_8_gallery_1782238988_0.jpg', 0, 0),
(20, 8, 'uploads/venues/venue_8_gallery_1782238988_1.jpg', 0, 1),
(21, 9, 'uploads/venues/venue_9_thumb_1782239123.jpg', 1, 0),
(22, 9, 'uploads/venues/venue_9_gallery_1782239123_0.jpg', 0, 0),
(23, 9, 'uploads/venues/venue_9_gallery_1782239123_1.jpg', 0, 1),
(27, 11, 'uploads/venues/venue_11_thumb_1782407260.png', 1, 0),
(28, 11, 'uploads/venues/venue_11_gallery_1782407260_0.png', 0, 0),
(29, 11, 'uploads/venues/venue_11_gallery_1782407260_1.png', 0, 1),
(33, 13, 'uploads/venues/venue_13_thumb_1782407632.png', 1, 0),
(34, 13, 'uploads/venues/venue_13_gallery_1782407632_0.png', 0, 0),
(35, 13, 'uploads/venues/venue_13_gallery_1782407632_1.png', 0, 1),
(42, 16, 'uploads/venues/venue_16_thumb_1782408317.png', 1, 0),
(43, 16, 'uploads/venues/venue_16_gallery_1782408317_0.png', 0, 0),
(44, 16, 'uploads/venues/venue_16_gallery_1782408317_1.png', 0, 1),
(45, 17, 'uploads/venues/venue_17_thumb_1782408446.png', 1, 0),
(46, 17, 'uploads/venues/venue_17_gallery_1782408446_0.png', 0, 0),
(47, 17, 'uploads/venues/venue_17_gallery_1782408446_1.png', 0, 1),
(48, 18, 'uploads/venues/venue_18_thumb_1782409207.png', 1, 0),
(49, 18, 'uploads/venues/venue_18_gallery_1782409207_0.png', 0, 0),
(50, 18, 'uploads/venues/venue_18_gallery_1782409207_1.png', 0, 1),
(51, 19, 'uploads/venues/venue_19_thumb_1782409352.png', 1, 0),
(52, 19, 'uploads/venues/venue_19_gallery_1782409352_0.png', 0, 0),
(53, 19, 'uploads/venues/venue_19_gallery_1782409352_1.png', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`user_id`,`venue_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `booking_addons`
--
ALTER TABLE `booking_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `venue_images`
--
ALTER TABLE `venue_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD CONSTRAINT `booking_addons_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `venues_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD CONSTRAINT `venue_images_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
