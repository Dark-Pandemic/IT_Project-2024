-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 01:43 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mentalhealthapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `userloginreg`
--

CREATE TABLE `userloginreg` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `timecreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userloginreg`
--

INSERT INTO `userloginreg` (`ID`, `username`, `email`, `password`, `timecreated`) VALUES
(1, 'shannon123', 'shannon@gmail.com', '@Shan12', '2024-10-05 23:16:03'),
(2, 'halezgov', 'hgov@hotmail.com', 'haley123@', '2024-10-05 23:17:23'),
(3, 'heyitsjuwi', 'juwi@gmail.com', '!Juwi30', '2024-10-05 23:18:11'),
(4, 'fardheens123', 'fardeen@gmail.com', 'Fards@12', '2024-10-05 23:20:16'),
(5, 'diaan53', 'diaan@hotmail.com', '@Ddr123', '2024-10-05 23:22:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `userloginreg`
--
ALTER TABLE `userloginreg`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `userloginreg`
--
ALTER TABLE `userloginreg`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
