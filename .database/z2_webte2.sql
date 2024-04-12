-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2024 at 04:37 PM
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
-- Database: `z2_webte2`
--

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classroom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `day`, `type`, `name`, `classroom`) VALUES
(850, 'Št', 'cvičenie', 'Webové technológie 2', 'c117b'),
(851, 'Št', 'cvičenie', 'Algebraické štruktúry', 'c517'),
(852, 'Pi', 'cvičenie', 'Vývoj softvérových aplikácií', 'cpu-e'),
(853, 'Po', 'prednáška', 'Úvod do herného dizajnu', 'bc300'),
(854, 'Po', 'prednáška', 'Vývoj softvérových aplikácií', 'de300'),
(855, 'Ut', 'prednáška', 'Webové technológie 2', 'cd300'),
(856, 'Ut', 'prednáška', 'Algebraické štruktúry', 'bc300'),
(857, 'St', 'prednáška', 'Webové technológie 2', 'cd300'),
(858, 'Št', 'cvičenie', 'Úvod do herného dizajnu', 'de150'),
(859, 'Št', 'cvičenie', 'Webové technológie 2', 'c117b'),
(860, 'Št', 'cvičenie', 'Algebraické štruktúry', 'c517'),
(861, 'Pi', 'cvičenie', 'Vývoj softvérových aplikácií', 'cpu-e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=863;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
