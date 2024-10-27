-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 05:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calorietracker`
--
DROP DATABASE IF EXISTS `calorietracker`;
CREATE DATABASE IF NOT EXISTS `calorietracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `calorietracker`;

-- --------------------------------------------------------

--
-- Table structure for table `daily_intake`
--

DROP TABLE IF EXISTS `daily_intake`;
CREATE TABLE `daily_intake` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `calories` decimal(10,2) DEFAULT NULL,
  `proteins` decimal(10,2) DEFAULT NULL,
  `fats` decimal(10,2) DEFAULT NULL,
  `carbs` decimal(10,2) DEFAULT NULL,
  `date_recorded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_intake_food_items`
--

DROP TABLE IF EXISTS `daily_intake_food_items`;
CREATE TABLE `daily_intake_food_items` (
  `daily_intake_id` int(11) NOT NULL,
  `food_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

DROP TABLE IF EXISTS `food_items`;
CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `calories` decimal(10,2) NOT NULL,
  `proteins` decimal(10,2) NOT NULL,
  `fats` decimal(10,2) NOT NULL,
  `carbs` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `food_name`, `calories`, `proteins`, `fats`, `carbs`) VALUES
(1, 'Apple', 52.00, 0.30, 0.20, 14.00),
(2, 'Chicken Breast', 165.00, 31.00, 3.60, 0.00),
(3, 'Rice', 130.00, 2.40, 0.30, 28.00),
(4, 'Apple', 95.00, 0.50, 0.30, 25.00),
(5, 'Banana', 105.00, 1.30, 0.40, 27.00),
(6, 'Chicken Breast', 165.00, 31.00, 3.60, 0.00),
(7, 'Broccoli', 55.00, 3.70, 0.60, 11.00),
(8, 'Egg', 78.00, 6.00, 5.00, 1.00),
(9, 'Almonds (1 oz)', 160.00, 6.00, 14.00, 6.00),
(10, 'Oatmeal (1 cup)', 154.00, 6.00, 2.60, 27.00),
(11, 'Salmon (3 oz)', 180.00, 17.00, 11.00, 0.00),
(12, 'Sweet Potato', 103.00, 2.30, 0.20, 24.00),
(13, 'Greek Yogurt (Plain, 6 oz)', 100.00, 10.00, 0.00, 7.00);

-- --------------------------------------------------------

--
-- Table structure for table `personal_details`
--

DROP TABLE IF EXISTS `personal_details`;
CREATE TABLE `personal_details` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `height_inches` decimal(5,2) NOT NULL,
  `current_weight` decimal(5,2) NOT NULL,
  `goal_weight` decimal(5,2) NOT NULL,
  `activity_level` enum('Sedentary','Lightly Active','Moderately Active','Very Active') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `calories` decimal(10,2) DEFAULT NULL,
  `protein` decimal(10,2) DEFAULT NULL,
  `fats` decimal(10,2) DEFAULT NULL,
  `carbs` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_details`
--

INSERT INTO `personal_details` (`id`, `name`, `age`, `gender`, `height_inches`, `current_weight`, `goal_weight`, `activity_level`, `created_at`, `calories`, `protein`, `fats`, `carbs`, `user_id`) VALUES
(1, 'John Doe', 30, 'Male', 70.00, 180.00, 200.00, 'Moderately Active', '2024-09-30 20:02:27', 2902.00, 160.00, 70.00, 408.00, NULL),
(4, 'Jason Berroa', 27, 'Male', 72.00, 250.00, 200.00, 'Sedentary', '2024-09-30 20:20:01', 2825.00, 160.00, 70.00, 389.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_intake`
--
ALTER TABLE `daily_intake`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_personal_details_daily_intake` (`user_id`);

--
-- Indexes for table `daily_intake_food_items`
--
ALTER TABLE `daily_intake_food_items`
  ADD PRIMARY KEY (`daily_intake_id`,`food_item_id`),
  ADD KEY `food_item_id` (`food_item_id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_intake`
--
ALTER TABLE `daily_intake`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_details`
--
ALTER TABLE `personal_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_intake`
--
ALTER TABLE `daily_intake`
  ADD CONSTRAINT `daily_intake_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `personal_details` (`id`),
  ADD CONSTRAINT `fk_personal_details_daily_intake` FOREIGN KEY (`user_id`) REFERENCES `personal_details` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_intake_food_items`
--
ALTER TABLE `daily_intake_food_items`
  ADD CONSTRAINT `daily_intake_food_items_ibfk_1` FOREIGN KEY (`daily_intake_id`) REFERENCES `daily_intake` (`id`),
  ADD CONSTRAINT `daily_intake_food_items_ibfk_2` FOREIGN KEY (`food_item_id`) REFERENCES `food_items` (`id`);

--
-- Constraints for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
