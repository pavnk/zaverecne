-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2023 at 06:35 PM
-- Server version: 8.0.32-0ubuntu0.22.04.2
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
                           `id` int UNSIGNED NOT NULL,
                           `exercise_file` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `exercise_file`) VALUES
  (5, 'blokovka01pr.tex');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
                         `id` int UNSIGNED NOT NULL,
                         `login` varchar(128) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `password` varchar(512) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `name` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `surname` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `login`, `password`, `name`, `surname`) VALUES
  (2, 'DominikRacek', '$argon2id$v=19$m=65536,t=4,p=1$Z1hyVjJ1azY4U1E3ZVNuUQ$gzO8T5HMjYcGgw8vZJbGKrNWS31tJHBhY+LlYU2wgqE', 'Dominik', 'Racek');

-- --------------------------------------------------------

--
-- Table structure for table `student_exercise`
--

CREATE TABLE `student_exercise` (
                                  `id` int UNSIGNED NOT NULL,
                                  `student_id` int UNSIGNED NOT NULL,
                                  `exercise_id` int UNSIGNED NOT NULL,
                                  `task_number` int UNSIGNED NOT NULL,
                                  `date_start` date DEFAULT NULL,
                                  `date_end` date DEFAULT NULL,
                                  `submited` tinyint NOT NULL,
                                  `max_points` int NOT NULL,
                                  `gotten_points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `student_exercise`
--

INSERT INTO `student_exercise` (`id`, `student_id`, `exercise_id`, `task_number`, `date_start`, `date_end`, `submited`, `max_points`, `gotten_points`) VALUES
  (1, 2, 5, 0, '2023-05-12', '2023-05-19', 0, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
                         `id` int NOT NULL,
                         `login` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `password` varchar(512) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `name` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL,
                         `surname` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `login`, `password`, `name`, `surname`) VALUES
                                                                       (3, 'jozkomrkva', '$argon2id$v=19$m=65536,t=4,p=1$UWd3YjhDQUpvQWdubW1mSQ$r8LvAayVGdXaIJcAATfFBhaj5jx/XrR4X5dlerxhb0U', '', ''),
                                                                       (5, 'xpavlisn', '$argon2id$v=19$m=65536,t=4,p=1$MkZxNU5QRnJzQ3IwbkFrZw$kUI6dQjeQBo8f0QcyZnCYBL1xSyLKAgbiy6uACrkrbU', '', ''),
                                                                       (6, 'test123', '$argon2id$v=19$m=65536,t=4,p=1$UEFqNVFSL29MQTVxV256QQ$4lfLmnVMLv4Rx6ETk/c5/dZ1t/XQKxv9bLCfW0RFZ3k', 'test', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_exercise`
--
ALTER TABLE `student_exercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `excercise_id` (`exercise_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_exercise`
--
ALTER TABLE `student_exercise`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_exercise`
--
ALTER TABLE `student_exercise`
  ADD CONSTRAINT `student_exercise_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `student_exercise_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
