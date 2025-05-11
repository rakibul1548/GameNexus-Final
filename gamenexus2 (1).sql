-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 01:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamenexus2`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `category`, `brand`, `price`, `sale_price`, `stock`, `status`, `description`, `images`, `created_at`) VALUES
(1, 'xyz', 'xyz', 'action', 'microsoft', 500.00, 600.00, 4, 'active', 'best', 'Uploads/682072cc8e231_Md Rakibul_picture.jpeg', '2025-05-11 09:50:04'),
(3, 'rfg', 'ngx', 'adventure', 'microsoft', 1200.00, 1500.00, 10, 'active', 'best', 'Uploads/6820737fc3502_CEO.jpeg', '2025-05-11 09:53:03'),
(6, 'now', 'won', 'action', 'sony', 1200.00, 1300.00, 1, 'active', 'how', 'Uploads/682073d274ead_Md Rakibul_picture.jpeg', '2025-05-11 09:54:26'),
(10, 'new', 'wen', 'racing', 'microsoft', 1200.00, 1300.00, 3, 'active', 'hi', 'Uploads/682077b72103d_CEO.jpeg', '2025-05-11 10:11:03');

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int(11) NOT NULL,
  `tip_title` varchar(255) NOT NULL,
  `game` varchar(100) NOT NULL,
  `tip_content` text NOT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `submitted_by` varchar(100) NOT NULL,
  `submission_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, '1', '1@gmail.com', '123', '2025-05-09 20:58:52'),
(2, 'rakib', 'rakibul122601@gmail.com', '$2y$10$zkhImb4pB.bDymydBPzwl.RVH1OdIICzh9e0XTOoK1bJuUREKf4VO', '2025-05-09 21:06:41'),
(3, 'grok', 'grok@gmail.com', '$2y$10$d0PpX8Vj4TU5mzgKdOfmF.iiRjKv8fjRL4sE6vL5HDABCXAH4J7d6', '2025-05-09 21:26:27'),
(4, 'rifat', 'rifat@gmail.com', '$2y$10$NnOEvrWd73NHNasOfY1xmucYTBxsjv1Rl.yiNXiqFZpQ2vxkzWfzO', '2025-05-09 21:35:48'),
(6, 'rayhan', 'rayhan@gmail.com', '$2y$10$mbFIpqHl4FUw5FgDpDzupe6e5UPprlKLdIV2S./pIULVXRBFXLS/W', '2025-05-10 11:09:01'),
(9, 'rana', 'rana@gmail.com', '$2y$10$2H6KAKvVZbeq9dpS8sTNWeUZjfgC7WEl2DTHv7y1UBhoaKdCBaaby', '2025-05-11 08:12:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
