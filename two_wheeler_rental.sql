-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
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
-- Database: `two_wheeler_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 3, 'Booking Approved', 'Your booking for NS 200 has been approved.', 1, '2026-03-11 12:00:57'),
(2, 3, 'Booking Approved', 'Your booking for NS 200 has been approved.', 1, '2026-03-12 03:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `renter_name` varchar(100) DEFAULT NULL,
  `renter_email` varchar(100) DEFAULT NULL,
  `renter_phone` varchar(20) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','completed','cancelled') DEFAULT 'pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `vehicle_id`, `user_id`, `renter_name`, `renter_email`, `renter_phone`, `start_date`, `end_date`, `total_price`, `message`, `status`, `booking_date`) VALUES
(1, 7, 3, 'sujijf yttt', 'sujitpaudel99@gmail.com', '1234567890', '2026-03-11', '2026-03-19', 27000.00, '', 'approved', '2026-03-11 12:00:43'),
(2, 7, 3, 'sujijf yttt', 'sujitpaudel99@gmail.com', '1234567890', '2026-03-12', '2026-03-19', 24000.00, '', 'approved', '2026-03-12 03:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
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
  `user_type` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `user_type`, `created_at`) VALUES
(1, 'Admin', 'admin@twowheeler.com', '9876543210', '$2y$10$YourHashedPasswordHere', 'admin', '2026-03-11 11:12:00'),
(2, 'samit', 'samit12@gmail.com', '123456', '$2y$10$b5W12ET/bSq6AlWWfY2Nv.FO3Eukph0ePvIisbOfI47hnIPNk.KVy', 'admin', '2026-03-11 11:22:02'),
(3, 'sujijf yttt', 'sujitpaudel99@gmail.com', '1234567890', '$2y$10$uDvdt6EshOql1OAwijd5QeifdG8yUb5GsK4wfg3I5RXgehdzZSmXO', 'customer', '2026-03-11 11:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `engine_cc` int(11) DEFAULT NULL,
  `fuel_type` enum('Petrol','Diesel','Electric','Other') DEFAULT NULL,
  `transmission` enum('Manual','Automatic') DEFAULT NULL,
  `mileage` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `availability_status` enum('available','booked','maintenance') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `title`, `description`, `brand`, `model`, `year`, `engine_cc`, `fuel_type`, `transmission`, `mileage`, `color`, `location`, `city`, `price_per_day`, `availability_status`, `image`, `created_at`) VALUES
(4, 'Bike', 'The Bajaj Pulsar 150 is one of the most popular 150 cc motorcycles made by Bajaj Auto. It is known for its sporty design, good performance, and reliable engine, making it a common choice for daily commuting and long rides in many countries.', 'Bajaj', 'Pulsar 150', 2026, 150, 'Petrol', 'Manual', '35', 'Black and Red', 'Kathmandu', 'kathmandu', 1500.00, 'available', 'assets/uploads/1773229962_Screenshot 2026-02-17 202614.png', '2026-03-11 11:52:42'),
(5, 'Scooter', 'The Honda Dio 125 is a sporty scooter produced by Honda. It is popular among young riders because of its stylish design, smooth automatic transmission, and good fuel efficiency. It is mainly designed for comfortable city riding.', 'Honda', 'Dio 125', 2024, 125, 'Petrol', 'Manual', '55', 'White and Blace', 'Kathmandu', 'kathmandu', 1200.00, 'available', 'assets/uploads/1773230069_Screenshot 2026-02-17 211249.png', '2026-03-11 11:54:29'),
(6, 'Bike', 'The Royal Enfield Classic 350 is a famous cruiser motorcycle made by Royal Enfield. It is known for its classic retro design, powerful engine, and deep exhaust sound, which makes it very popular among bike lovers.', 'Royal Enfield', 'Royal Enfiled 350', 2021, 350, 'Petrol', 'Manual', '30', 'Black', 'Kathmandu', 'kathmandu', 4000.00, 'available', 'assets/uploads/1773230230_Screenshot 2026-02-17 210725.png', '2026-03-11 11:57:10'),
(7, 'NS 200', 'The Pulsar NS200 (Naked Sport 200) has a muscular fuel tank, sharp body design, and a sporty riding position. It is built for riders who want speed, performance, and stylish looks for both city riding and highway trips.', 'Bajaj', 'NS 200', 2025, 200, 'Petrol', 'Manual', '38', 'Black', 'Kathmandu', 'kathmandu', 3000.00, 'available', 'assets/uploads/1773230322_Screenshot 2026-02-17 203822.png', '2026-03-11 11:58:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
