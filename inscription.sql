-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 01:52 PM
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
-- Database: `inscription`
--

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(25) DEFAULT NULL,
  `EMAIL` varchar(25) DEFAULT NULL,
  `PASSWORD` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`ID`, `USERNAME`, `EMAIL`, `PASSWORD`) VALUES
(9, 'bernice', 'nkwainbernice20@gmail.com', '$2y$10$dtzneAzuYNo.Y1QSqI'),
(10, 'varine', 'varine@gmail.com', '$2y$10$0alEluJBLqYoIz3njZ'),
(11, 'varine', 'cind@gmail.com', '$2y$10$L20jCdM6ubGkvnheh.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
