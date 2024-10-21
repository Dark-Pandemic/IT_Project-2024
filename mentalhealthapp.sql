-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 05:29 PM
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
-- Table structure for table `achievement`
--

CREATE TABLE `achievement` (
  `userID` int(11) NOT NULL,
  `badge_name` char(255) NOT NULL,
  `points_earned` int(255) NOT NULL,
  `date_earned` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE `challenge` (
  `userID` int(11) NOT NULL,
  `challenge_title` char(100) NOT NULL,
  `chall_points` int(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `userID` int(11) NOT NULL,
  `file_name` char(100) NOT NULL,
  `file_content` blob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userloginreg`
--

CREATE TABLE `userloginreg` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `timecreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `points` int(255) NOT NULL,
  `level` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userloginreg`
--

INSERT INTO `userloginreg` (`ID`, `username`, `email`, `password`, `timecreated`, `points`, `level`) VALUES
(1, 'shannon123', 'shannon@gmail.com', '@Shan12', '2024-10-05 23:16:03', 0, 0),
(2, 'halezgov', 'hgov@hotmail.com', 'haley123@', '2024-10-05 23:17:23', 0, 0),
(3, 'heyitsjuwi', 'juwi@gmail.com', '!Juwi30', '2024-10-05 23:18:11', 0, 0),
(4, 'fardheens123', 'fardeen@gmail.com', 'Fards@12', '2024-10-05 23:20:16', 0, 0),
(5, 'diaan53', 'diaan@hotmail.com', '@Ddr123', '2024-10-05 23:22:39', 0, 0);

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
