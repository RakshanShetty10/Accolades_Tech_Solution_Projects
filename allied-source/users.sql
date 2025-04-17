-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 07:10 AM
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
-- Database: `ksahc_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_email_id` varchar(250) DEFAULT NULL,
  `user_status` varchar(50) DEFAULT NULL,
  `user_created_on` datetime DEFAULT NULL,
  `user_created_by` varchar(250) DEFAULT NULL,
  `user_last_updated_on` datetime DEFAULT NULL,
  `user_last_updated_by` varchar(250) DEFAULT NULL,
  `user_phone_number` varchar(25) DEFAULT NULL,
  `user_full_name` varchar(250) DEFAULT NULL,
  `user_image` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `user_role`, `user_name`, `user_password`, `user_email_id`, `user_status`, `user_created_on`, `user_created_by`, `user_last_updated_on`, `user_last_updated_by`, `user_phone_number`, `user_full_name`, `user_image`, `date_of_birth`) VALUES
(1, 'Admin', 'ksahcadmin123', '417cd3243d45c3158d22a61c25693d98', 'admin@example.com', 'Active', NULL, 'Admin', '2025-04-16 17:33:33', 'ksahcadmin123', '9876543210', 'KSAHC', '1744804415.jpeg', '1990-01-01'),
(2, 'Chairman', 'Rachitha', 'ed0f0bf892c0ae006f9433617ebe1037', 'rachitha@gmail.com', 'Active', '2025-04-16 17:32:34', 'ksahcadmin123', NULL, NULL, '8792235863', 'Rachitha Acharya', '1744804954.jpeg', '2002-08-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
