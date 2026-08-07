-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 04:21 AM
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
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `transmission` enum('automatic','manual') NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `status` enum('available','unavailable','maintenance') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `color`, `plate_number`, `capacity`, `transmission`, `daily_rate`, `status`, `image`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Honda', 'Civic', 2023, 'Black', 'DEF456', 5, 'automatic', 40.00, 'available', NULL, 'Reliable compact car with excellent fuel economy and modern features.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(3, 'BMW', 'X5', 2023, 'Blue', 'GHI789', 7, 'automatic', 85.00, 'available', NULL, 'Luxury SUV with premium features and spacious interior.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(4, 'Mercedes', 'C-Class', 2023, 'Silver', 'JKL012', 5, 'automatic', 75.00, 'available', NULL, 'Elegant luxury sedan with advanced technology and comfort features.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(5, 'Ford', 'Mustang', 2023, 'Red', 'MNO345', 4, 'manual', 65.00, 'available', NULL, 'Iconic sports car with powerful performance and sleek design.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(6, 'Toyota', 'RAV4', 2023, 'Gray', 'PQR678', 5, 'automatic', 50.00, 'available', NULL, 'Versatile SUV perfect for family trips and outdoor adventures.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(7, 'Hyundai', 'Tucson', 2023, 'Green', 'STU901', 5, 'automatic', 48.00, 'available', NULL, 'Modern SUV with smart features and comfortable ride.', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(9, 'Kurt', 'russel2344', 2023, 'White', '993987469123', 7, 'automatic', 100.00, 'maintenance', '1763945290_c8e9f1dc0385541366e0.jpg', 'beautiful', '2025-11-23 16:48:09', '2025-12-06 04:57:09'),
(10, 'yamaha', 'fgfhjgkg', 2010, 'red', '0909b', 8, 'automatic', 5000.00, 'available', NULL, '', '2025-12-15 19:18:48', '2025-12-15 19:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','debit_card','paypal','online') NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `reservation_id`, `amount`, `payment_method`, `status`, `transaction_id`, `payment_date`, `created_at`, `updated_at`) VALUES
(10, 14, 1105.00, 'cash', 'completed', 'TXN_69342ddbf1dc4', '2025-12-06 05:21:31', '2025-12-06 05:21:32', '2025-12-06 05:21:32');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','ongoing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `total_cost`, `status`, `created_at`, `updated_at`) VALUES
(14, 7, 3, '2025-12-12', '2025-12-24', 1105.00, 'cancelled', '2025-12-06 05:19:29', '2025-12-06 05:22:19'),
(15, 7, 4, '2025-12-11', '2025-12-17', 525.00, 'pending', '2025-12-10 17:17:54', '2025-12-10 17:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Kurt Russel Sabuero', 'admin@driverent.com', '$2y$10$TWV25DXQu8B98SfGJz8EO.rjhBFIwCHKDAAQbKAArcg2AI74OPft2', '09273532291', '123 Admin Street, City, State', 'admin', '2025-11-23 14:54:55', '2025-12-05 21:33:08'),
(3, 'Jane Smith', 'jane.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567892', '789 User Road, City, State', 'customer', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(4, 'Mike Johnson', 'mike.johnson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567893', '321 Driver Lane, City, State', 'customer', '2025-11-23 14:54:55', '2025-11-23 14:54:55'),
(7, 'Richard Miculob', 'miculobrichardvictor@gmail.com', '$2y$10$P7p.G7Cs908aqAQNg.Lwou/oCIK0TB/sBEJJYTlpVyi7g.PcS82HG', '09273532291', 'Zone 3 Bonbon', 'customer', '2025-12-05 21:29:18', '2025-12-05 21:31:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
