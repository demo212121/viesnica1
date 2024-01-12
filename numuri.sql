-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2024 at 01:15 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `numuri`
--

CREATE TABLE `numuri` (
  `NumuraID` int NOT NULL,
  `Nosaukums` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Apraksts` text COLLATE utf8mb4_general_ci NOT NULL,
  `Cena` decimal(10,2) NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reserved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `numuri`
--

INSERT INTO `numuri` (`NumuraID`, `Nosaukums`, `Apraksts`, `Cena`, `image`, `created_at`, `reserved`) VALUES
(8, 'adadad', 'arwoamf', '232.00', 'uploads/luxus-grand-hotel.jpg', '2024-01-12 12:52:23', 2),
(9, 'adadad', 'bhuhuyuivyuioybio', '333.00', 'uploads/6072b60b_vid5996d66a634b6big.jpg', '2024-01-12 12:54:38', 1),
(10, 'adadad', 'jgkchgkc', '232.00', 'uploads/6072b60b_vid5996d66a634b6big.jpg', '2024-01-12 12:58:20', 1),
(11, 'oky', 'tiesi ta', '37.00', 'uploads/63300438.jpg', '2024-01-12 13:11:12', 0),
(12, 'aaa', 'aaa', '1000.00', 'uploads/olimpiska-centra-ventspils-viesnica-viesnicas-ventspili-2471.jpeg', '2024-01-12 13:11:35', 0),
(13, 'ddd', 'ddd', '46.00', 'uploads/unnamed.jpg', '2024-01-12 13:11:48', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `numuri`
--
ALTER TABLE `numuri`
  ADD PRIMARY KEY (`NumuraID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `numuri`
--
ALTER TABLE `numuri`
  MODIFY `NumuraID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
