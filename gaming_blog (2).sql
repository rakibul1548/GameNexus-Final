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
-- Database: `gaming_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int(11) NOT NULL,
  `tip_title` varchar(255) NOT NULL,
  `game` varchar(100) NOT NULL,
  `tips` text NOT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_name` varchar(100) DEFAULT NULL,
  `tip_category` varchar(50) DEFAULT NULL,
  `reading_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`id`, `tip_title`, `game`, `tips`, `media_path`, `created_at`, `author_name`, `tip_category`, `reading_time`) VALUES
(2, 'z', 'Call of Duty', 'aa', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:24:39', '', '', NULL),
(3, 'z', 'Fortnite', 'mj', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:25:11', 'm', 'Intermediate', 5),
(4, 'z', 'Fortnite', 'mj', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:28:25', 'm', 'Intermediate', 5),
(5, 'z', 'Fortnite', 'mj', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:31:11', 'm', 'Intermediate', 5),
(6, 'z', 'Call of Duty', 'e', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:31:42', 'm', 'Intermediate', 1),
(7, 'rak', 'League of Legends', 'deenjnjinuinui', 'uploads/Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-10 20:34:21', 'fer', 'Pro', 1),
(8, 'xxxxx', 'Minecraft', 'xxxxxxx', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 20:53:39', 'xxxxxxx', 'Intermediate', 1),
(9, 'demo tips', 'Valorant', 'This will be best', 'uploads/CEO.jpeg', '2025-05-10 21:15:04', 'rakib', 'Advanced', 2),
(10, 'demo tips', 'Valorant', 'This will be best', 'uploads/CEO.jpeg', '2025-05-10 21:16:11', 'rakib', 'Advanced', 2),
(11, 'demo tips', 'Valorant', 'This will be best', 'uploads/CEO.jpeg', '2025-05-10 21:49:35', 'rakib', 'Advanced', 2),
(12, 'velorent', 'Call of Duty', 'whefhwbehf', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 21:49:59', 'rakib', 'Intermediate', 1),
(13, 'velorent', 'Call of Duty', 'whefhwbehf', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:02:52', 'rakib', 'Intermediate', 1),
(14, 'hiwfqheqf', 'Call of Duty', 'thdtyjttj', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:03:17', 'rakib', 'Beginner', 8),
(15, 'hiwfqheqf', 'Call of Duty', 'thdtyjttj', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:05:50', 'rakib', 'Beginner', 8),
(16, 'xxxxx', 'Minecraft', 'xxxxxxx', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:13:45', 'xxxxxxx', 'Intermediate', 1),
(17, 'velorent', 'GTA 5', 'wfbwhbdf', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:14:16', 'xxxxxxx', 'Advanced', 5),
(20, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:24:21', 'xxxxxxx', 'Advanced', 15),
(21, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:27:14', 'xxxxxxx', 'Advanced', 15),
(22, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:27:20', 'xxxxxxx', 'Advanced', 15),
(23, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:27:38', 'xxxxxxx', 'Advanced', 15),
(24, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:27:57', 'xxxxxxx', 'Advanced', 15),
(25, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:31:18', 'xxxxxxx', 'Advanced', 15),
(26, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:32:35', 'xxxxxxx', 'Advanced', 15),
(27, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:34:06', 'xxxxxxx', 'Advanced', 15),
(28, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:34:10', 'xxxxxxx', 'Advanced', 15),
(29, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:34:30', 'xxxxxxx', 'Advanced', 15),
(30, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:34:52', 'xxxxxxx', 'Advanced', 15),
(31, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:36:29', 'xxxxxxx', 'Advanced', 15),
(32, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:37:14', 'xxxxxxx', 'Advanced', 15),
(33, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:38:35', 'xxxxxxx', 'Advanced', 15),
(34, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:41:05', 'xxxxxxx', 'Advanced', 15),
(35, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:41:12', 'xxxxxxx', 'Advanced', 15),
(36, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:41:44', 'xxxxxxx', 'Advanced', 15),
(37, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:42:36', 'xxxxxxx', 'Advanced', 15),
(38, 'velorent best game', 'Egypt and World', 'best', 'uploads/Md Rakibul_picture.jpeg', '2025-05-10 22:43:59', 'xxxxxxx', 'Advanced', 15),
(39, 'demo tips', 'Fortnite', 'You can try', 'uploads/dad1e9cc-97ec-4326-a025-154353ea54aa.jpg', '2025-05-10 22:44:52', 'rakib', 'Beginner', 1),
(40, 'demo tips', 'Fortnite', 'You can try', 'uploads/dad1e9cc-97ec-4326-a025-154353ea54aa.jpg', '2025-05-10 22:46:07', 'rakib', 'Beginner', 1),
(41, 'velorent', 'Cyberpunk 2077', 'sdfghjkl', 'uploads/CEO.jpeg', '2025-05-10 22:46:35', 'rakib', 'Beginner', 5),
(42, 'demo tips', 'Minecraft', 'you can try', 'uploads/CEO.jpeg', '2025-05-11 08:10:25', 'Rana', 'Advanced', 1),
(43, 'best game', 'League of Legends', 'sdfghjk', 'uploads/Md Rakibul_picture.jpeg', '2025-05-11 09:12:34', 'Rana', 'Advanced', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
