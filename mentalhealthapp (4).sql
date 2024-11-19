-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 12:41 PM
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
-- Database: `mentalhealthapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `criteria` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `unlock_xp` int(11) DEFAULT 0,
  `required_login_count` int(11) DEFAULT 0
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

--
-- Dumping data for table `journal`
--

INSERT INTO `journal` (`userID`, `file_name`, `file_content`, `created_at`) VALUES
(1, 'mood for today', 0x546f6461792069206665656c20677261746566756c20666f72207468652070656f706c65207468617420737572726f756e64206d65, '2024-11-18 08:37:04'),
(1, 'Monday madness', 0x49206861766520616c6f74206f6620776f726b20746f20646f20746f646179202c206275742069206665656c206966206920736574206f75742061206c697374206f66207768617420746f20636f6d706c65746520692063616e20626520646f6e65206561726c69657220616e642068617665206d6f72652074696d6520746f20676f206f7574207769746820667269656e6473206c617465722e, '2024-11-18 08:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_type` enum('daily','weekly','monthly') NOT NULL,
  `xp_points` int(11) NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `task_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_type`, `xp_points`, `ID`, `is_completed`, `task_description`) VALUES
(11, 'Log Mood', 'daily', 10, NULL, 0, 'Record your mood for the day.'),
(12, 'Drink Water', 'daily', 5, NULL, 0, 'Drink at least 8 glasses of water.'),
(13, 'Take a Walk', 'daily', 15, NULL, 0, 'Walk for at least 30 minutes.'),
(14, 'Meditate', 'daily', 20, NULL, 0, 'Meditate for at least 10 minutes.'),
(15, 'Read a Book', 'daily', 15, NULL, 0, 'Read for at least 20 minutes.'),
(16, 'Practice Gratitude', 'daily', 10, NULL, 0, 'Write down 3 things you are grateful for.'),
(17, 'Stretching Routine', 'daily', 15, NULL, 0, 'Perform a 10-minute stretching routine.'),
(18, 'Digital Detox', 'daily', 25, NULL, 0, 'Spend a day without social media.'),
(19, 'Listen to Music', 'daily', 10, NULL, 0, 'Listen to a new genre or artist for 30 minutes.'),
(20, 'Cook a Healthy Meal', 'daily', 20, NULL, 0, 'Prepare a nutritious meal for yourself.'),
(21, 'Write a Positive Affirmation', 'daily', 10, NULL, 0, 'Write and recite a positive affirmation.'),
(22, 'Unplug Before Bed', 'daily', 15, NULL, 0, 'Turn off screens 1 hour before bedtime.'),
(23, 'Engage in a Hobby', 'daily', 20, NULL, 0, 'Spend at least 30 minutes on a hobby.'),
(24, 'Help Someone', 'daily', 15, NULL, 0, 'Do a small act of kindness for someone.'),
(25, 'Review Daily Goals', 'daily', 10, NULL, 0, 'Reflect on and adjust your daily goals.'),
(26, 'Reflect on the Week', 'weekly', 30, NULL, 0, 'Write a reflection on your week.'),
(27, 'Complete a Challenge', 'weekly', 50, NULL, 0, 'Complete a personal challenge (e.g., fitness).'),
(28, 'Connect with a Friend', 'weekly', 20, NULL, 0, 'Reach out to a friend or family member.'),
(29, 'Journal Entry', 'weekly', 25, NULL, 0, 'Write a journal entry about your feelings.'),
(30, 'Plan for the Week', 'weekly', 15, NULL, 0, 'Set goals for the upcoming week.'),
(31, 'Attend a Local Event', 'weekly', 40, NULL, 0, 'Participate in a community event or gathering.'),
(32, 'Try a New Recipe', 'weekly', 30, NULL, 0, 'Cook a new dish you’ve never tried before.'),
(33, 'Volunteer', 'weekly', 50, NULL, 0, 'Spend time volunteering for a cause.'),
(34, 'Explore a New Place', 'weekly', 25, NULL, 0, 'Visit a new park, café, or area in your city.'),
(35, 'Digital Declutter', 'weekly', 20, NULL, 0, 'Organize your digital files and emails.'),
(36, 'Family Game Night', 'weekly', 30, NULL, 0, 'Spend quality time playing games with family.'),
(37, 'Learn Something New', 'weekly', 40, NULL, 0, 'Take an online course or tutorial.'),
(38, 'Write a Letter', 'weekly', 15, NULL, 0, 'Write a letter to a friend or family member.'),
(39, 'Attend a Fitness Class', 'weekly', 30, NULL, 0, 'Join a fitness or yoga class.'),
(40, 'Self-Care Activity', 'weekly', 50, NULL, 0, 'Dedicate time to a self-care activity (e.g., spa day).'),
(41, 'Monthly Review', 'monthly', 50, NULL, 0, 'Review your month and set new goals.'),
(42, 'Attend a Workshop', 'monthly', 75, NULL, 0, 'Attend a mental health or personal development workshop.'),
(43, 'Volunteer', 'monthly', 100, NULL, 0, 'Participate in a community service activity.'),
(44, 'Self-Care Day', 'monthly', 80, NULL, 0, 'Dedicate a day to self-care activities.'),
(45, 'Complete a Book', 'monthly', 60, NULL, 0, 'Finish reading a book and summarize it.'),
(46, 'Create a Vision Board', 'monthly', 70, NULL, 0, 'Design a vision board for your goals and dreams.'),
(47, 'Family Outing', 'monthly', 60, NULL, 0, 'Plan and enjoy a day out with family.'),
(48, 'Financial Review', 'monthly', 50, NULL, 0, 'Review your finances and set a budget for the month.'),
(49, 'Explore a New Hobby', 'monthly', 75, NULL, 0, 'Start a new hobby or craft project.'),
(50, 'Reflect on Personal Growth', 'monthly', 40, NULL, 0, 'Write about your personal growth over the month.'),
(51, 'Attend a Support Group', 'monthly', 80, NULL, 0, 'Participate in a support group or discussion.'),
(52, 'Plan a Weekend Getaway', 'monthly', 100, NULL, 0, 'Organize a short trip or getaway for relaxation.'),
(53, 'Create a Monthly Playlist', 'monthly', 30, NULL, 0, 'Curate a playlist of songs that inspire you.'),
(54, 'Write a Blog Post', 'monthly', 50, NULL, 0, 'Share your thoughts or experiences in a blog post.'),
(55, 'Set Long-Term Goals', 'monthly', 60, NULL, 0, 'Define your long-term goals and aspirations.');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

CREATE TABLE `therapists` (
  `therapist_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
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
  `level` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `profile_pic` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userloginreg`
--

INSERT INTO `userloginreg` (`ID`, `username`, `email`, `password`, `timecreated`, `points`, `level`, `name`, `contact`, `reset_token`, `reset_expires`, `profile_pic`) VALUES
(2, 'haleyg', 'haleygovender21@gmail.com', 'hales@21', '2024-11-18 15:31:22', 0, 0, 'Haley Govender', '0828257724', 'aa2c2c785242b259cd9d3ae4e4f423d6d0f4093d92afc2872901e928964ee492', '2024-11-18 17:47:37', NULL),
(33, 'shannonsahdeo', 'shannonlsahdeo@gmail.com', 'shannon', '2024-11-10 05:10:58', 0, 0, 'shannon sahdeo', '0670124691', '1c01302c350adf24d7a1ea615f18ecedb457190f05fdf0ad48511eeaf109409e', '2024-11-18 17:43:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `ID` (`ID`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`therapist_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `userloginreg`
--
ALTER TABLE `userloginreg`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `therapists`
--
ALTER TABLE `therapists`
  MODIFY `therapist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userloginreg`
--
ALTER TABLE `userloginreg`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `subscriptions` (`ID`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`therapist_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `userloginreg` (`ID`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `userloginreg` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
