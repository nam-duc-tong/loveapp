-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2026 at 11:18 AM
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
-- Database: `lovex_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood` enum('happy','love','normal','sad','angry','tired') NOT NULL,
  `note` text DEFAULT NULL,
  `checkin_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkins`
--

INSERT INTO `checkins` (`id`, `couple_id`, `user_id`, `mood`, `note`, `checkin_date`, `created_at`) VALUES
(1, 1, 2, 'love', '', '2026-09-30', '2026-09-30 04:20:33'),
(3, 1, 1, 'love', '', '2026-09-30', '2026-09-30 04:30:00'),
(4, 1, 1, 'sad', '', '2026-10-01', '2026-10-01 00:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `couples`
--

CREATE TABLE `couples` (
  `id` int(11) NOT NULL,
  `user_1_id` int(11) NOT NULL,
  `user_2_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `status` enum('pending','active','paused','broken') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `couples`
--

INSERT INTO `couples` (`id`, `user_1_id`, `user_2_id`, `start_date`, `status`, `created_at`) VALUES
(1, 1, 2, '2026-09-26', 'active', '2026-09-30 03:35:55'),
(2, 2, NULL, '2026-09-30', 'pending', '2026-09-30 04:19:53'),
(3, 3, 4, '2026-10-01', 'active', '2026-10-01 08:41:19'),
(4, 4, NULL, '2026-10-01', 'pending', '2026-10-01 08:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `hearts`
--

CREATE TABLE `hearts` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `love_letters`
--

CREATE TABLE `love_letters` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `love_letters`
--

INSERT INTO `love_letters` (`id`, `couple_id`, `sender_id`, `title`, `content`, `is_read`, `created_at`) VALUES
(1, 1, 1, 'Gửi em yêu', 'Cảm ơn em đã đến bên anh. Mỗi ngày bên em là một ngày hạnh phúc.', 1, '2026-09-30 04:22:27'),
(2, 1, 2, 'Gửi anh', 'Em yêu anh nhiều lắm! Cảm ơn anh đã luôn ở bên em.', 0, '2026-09-30 04:22:27'),
(3, 1, 1, 'ngày 30/9', 'em ko rep tin nhắn', 1, '2026-09-30 04:30:40'),
(4, 3, 4, 'Gửi em', 'anh nhớ em', 0, '2026-10-01 09:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `memories`
--

CREATE TABLE `memories` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memories`
--

INSERT INTO `memories` (`id`, `couple_id`, `title`, `description`, `event_date`, `image_url`, `created_by`, `created_at`) VALUES
(1, 1, 'ngày đầu làm quen', 'test', '2026-09-26', 'uploads/mem_6abc8fe5b565c.jpg', 2, '2026-09-30 04:28:21');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `background_color` varchar(20) DEFAULT '#FF4D79',
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `couple_id`, `user_id`, `content`, `image_url`, `background_color`, `expires_at`, `created_at`) VALUES
(3, 3, 4, 'test', 'uploads/stories/story_6abe23f08b342.png', '#FF4D79', '2026-10-02 11:12:16', '2026-10-01 09:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `avatar_url` text DEFAULT NULL,
  `couple_id` int(11) DEFAULT NULL,
  `pair_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `onboarding_done` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password_hash`, `display_name`, `avatar_url`, `couple_id`, `pair_code`, `created_at`, `bio`, `birthday`, `onboarding_done`) VALUES
(1, 'nam@gmail.com', 'user1', '$2y$10$vKzM0lZLq6VEozFZWmB0xe1xEzEj7iirSL0mDvnHwnWvVTWc3NQRK', 'Tống Đức Nam', NULL, 1, 'BC9DAA', '2026-09-30 01:38:52', 'Lập trình viên', '2000-08-23', 1),
(2, 'test@gmail.com', 'user2', '$2y$10$kTYRi3bBG1jrCBxE5Lrl3uQYxRnVhIc95IE9OP6YQUjF.kAFuoGYu', 'test', NULL, 1, '0980FC', '2026-09-30 03:45:00', NULL, NULL, 1),
(3, NULL, 'nam', '$2y$10$jO3PyZfMc8GKbj3n9OtCxeMAOmbcQNUXy72426FWP5QNbJqrlmGiK', 'Tống Đức Nam', NULL, 3, '2D034D', '2026-10-01 08:40:46', NULL, NULL, 0),
(4, NULL, 'test', '$2y$10$KSAJPCVpIXjjBV3WzxAOxetynDfChhaRw7C0gFeaVASyJZpPaneWO', 'Test', NULL, 3, '9CDC0D', '2026-10-01 08:42:40', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_daily` (`user_id`,`checkin_date`),
  ADD KEY `couple_id` (`couple_id`);

--
-- Indexes for table `couples`
--
ALTER TABLE `couples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_1_id` (`user_1_id`),
  ADD KEY `user_2_id` (`user_2_id`);

--
-- Indexes for table `hearts`
--
ALTER TABLE `hearts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `couple_id` (`couple_id`);

--
-- Indexes for table `love_letters`
--
ALTER TABLE `love_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `couple_id` (`couple_id`);

--
-- Indexes for table `memories`
--
ALTER TABLE `memories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `couple_id` (`couple_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_couple_expires` (`couple_id`,`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pair_code` (`pair_code`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `idx_users_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `couples`
--
ALTER TABLE `couples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hearts`
--
ALTER TABLE `hearts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `love_letters`
--
ALTER TABLE `love_letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `memories`
--
ALTER TABLE `memories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `couples`
--
ALTER TABLE `couples`
  ADD CONSTRAINT `couples_ibfk_1` FOREIGN KEY (`user_1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `couples_ibfk_2` FOREIGN KEY (`user_2_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hearts`
--
ALTER TABLE `hearts`
  ADD CONSTRAINT `hearts_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `love_letters`
--
ALTER TABLE `love_letters`
  ADD CONSTRAINT `love_letters_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memories`
--
ALTER TABLE `memories`
  ADD CONSTRAINT `memories_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `memories_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `stories_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stories_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
