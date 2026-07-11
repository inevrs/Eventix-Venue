-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2026 at 08:41 AM
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
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 'Ahmad Zaidan', 'zaidan@gmail.com', '+60149530029', '$2y$10$RvyZ0SPMGdCKTYNx00gJl.k1vbOeGgzlbQbkaw4C1nInu5mK7kbXu', 'customer', '2026-06-23 09:37:28', 'uploads/profiles/1782207630_Screenshot 2026-06-23 174003.png', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_addons`
--
ALTER TABLE `booking_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
