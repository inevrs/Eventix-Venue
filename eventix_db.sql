-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2026 at 05:02 AM
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
(1, 2, 1, '2026-06-12', '2026-06-12', 15, '', 'confirmed', '2026-06-10 10:38:19'),
(2, 2, 1, '2026-06-12', '2026-06-12', 15, '', 'cancelled', '2026-06-10 11:04:35'),
(3, 2, 2, '2026-06-12', '2026-06-12', 100, '', 'confirmed', '2026-06-10 11:16:06');

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

--
-- Dumping data for table `booking_addons`
--

INSERT INTO `booking_addons` (`id`, `booking_id`, `addon_id`, `price`) VALUES
(1, 3, 1, 350.00),
(2, 3, 2, 280.00);

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
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `method`, `status`, `paid_at`) VALUES
(1, 1, 100.00, 'Online Banking', 'paid', '2026-06-10 10:38:24'),
(2, 3, 830.00, 'eWallet', 'paid', '2026-06-10 11:16:30');

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
(1, 2, 2, 4, 'Test2', '2026-06-10 11:16:49');

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
(1, 'Admin', 'admin@eventix.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-10 04:38:07', NULL, NULL),
(2, 'Muhammad Isyraf Nasruddin', 'isyrafnas2009@gmail.com', '01119835758', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '2026-06-10 04:52:59', NULL, NULL),
(3, 'Muhammad Ridhuan', 'ridhuan@gmail.com', '01124639425', '$2y$10$wEWZXJv0eLeR0QePu5ks9e..On5kV0VYPKAqkrGyLT0du2x0Htq2C', 'manager', '2026-06-10 10:34:23', NULL, NULL);

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
(1, 3, 'Test', 'Test', 20, 100.00, 'Test', 'active', '2026-06-10 10:35:43'),
(2, 3, 'Test2', 'Test2', 300, 200.00, 'Test2', 'active', '2026-06-10 11:14:50');

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
(1, 2, 'uploads/venues/venue_2_thumb_1781090090.jpg', 1, 0),
(2, 2, 'uploads/venues/venue_2_gallery_1781090090_0.jpg', 0, 0),
(3, 2, 'uploads/venues/venue_2_gallery_1781090090_1.jpg', 0, 1),
(4, 2, 'uploads/venues/venue_2_gallery_1781090090_2.jpg', 0, 2),
(5, 2, 'uploads/venues/venue_2_gallery_1781090090_3.jpg', 0, 3);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_addons`
--
ALTER TABLE `booking_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `venue_images`
--
ALTER TABLE `venue_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
