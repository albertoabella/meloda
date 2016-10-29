-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 29, 2016 at 03:03 AM
-- Server version: 5.5.51-38.2-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `calendb0_meloda`
--

-- --------------------------------------------------------

--
-- Table structure for table `M_API_values`
--

CREATE TABLE IF NOT EXISTS `M_API_values` (
  `Id` int(11) NOT NULL,
  `Parameter` int(11) NOT NULL,
  `Level` int(11) NOT NULL,
  `Value` decimal(3,2) NOT NULL,
  `Version` decimal(3,2) NOT NULL DEFAULT '4.10'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `M_API_values`
--

INSERT INTO `M_API_values` (`Id`, `Parameter`, `Level`, `Value`, `Version`) VALUES
(1, 1, 1, '0.00', '4.00'),
(2, 1, 2, '0.00', '4.00'),
(3, 1, 3, '0.25', '4.10'),
(4, 1, 4, '0.90', '4.10'),
(5, 1, 5, '1.00', '4.10'),
(6, 2, 1, '0.10', '4.10'),
(7, 2, 2, '0.35', '4.10'),
(8, 2, 3, '0.60', '4.10'),
(9, 2, 4, '1.00', '4.10'),
(10, 3, 1, '0.00', '4.10'),
(11, 3, 2, '0.10', '4.10'),
(12, 3, 3, '0.50', '4.10'),
(13, 3, 4, '0.90', '4.10'),
(14, 3, 5, '1.00', '4.10'),
(15, 4, 1, '0.15', '4.10'),
(16, 4, 2, '0.35', '4.10'),
(17, 4, 3, '0.50', '4.10'),
(18, 4, 4, '0.90', '4.10'),
(19, 4, 5, '1.00', '4.10'),
(20, 5, 1, '0.15', '4.10'),
(21, 5, 2, '0.30', '4.10'),
(22, 5, 3, '0.50', '4.10'),
(23, 5, 4, '0.90', '4.10'),
(24, 5, 5, '1.00', '4.10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `M_API_values`
--
ALTER TABLE `M_API_values`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `M_API_values`
--
ALTER TABLE `M_API_values`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
