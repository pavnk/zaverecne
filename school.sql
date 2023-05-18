-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2023 at 09:19 PM
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
-- Table structure for table `exercise`
--

CREATE TABLE `exercise` (
  `id` int UNSIGNED NOT NULL,
  `file_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Dumping data for table `exercise`
--

INSERT INTO `exercise` (`id`, `file_name`) VALUES
(6, 'blokovka01pr.tex'),
(7, 'blokovka02pr.tex'),
(8, 'odozva01pr.tex'),
(9, 'odozva02pr.tex');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int UNSIGNED NOT NULL,
  `login` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `surname` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `login`, `password`, `name`, `surname`) VALUES
(3, 'student', '$argon2id$v=19$m=65536,t=4,p=1$QW0vaEhBL3EvbnBtTmJIdQ$ixl2Xoi/CN6613Ig7i6zkOsDgkOStFrLUqnTuXlkRYE', 'student', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `student_exercise`
--

CREATE TABLE `student_exercise` (
  `id` int UNSIGNED NOT NULL,
  `exercise_id` int UNSIGNED NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `max_points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `exercise_id` int UNSIGNED NOT NULL,
  `text` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `solution` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `your_solution` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  `submitted` tinyint NOT NULL,
  `points` int NOT NULL,
  `assigned_to` int UNSIGNED DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int UNSIGNED NOT NULL,
  `login` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `surname` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `login`, `password`, `name`, `surname`) VALUES
(3, 'teacher', '$argon2id$v=19$m=65536,t=4,p=1$OWg0NjV3ZXVFS3lGa2t5RA$72WwEL5YBIXbUquzGyS0rUZJCVOCzJWZZAVdKV6CHcI', 'teacher', 'teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercise`
--
ALTER TABLE `exercise`
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
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_exercise`
--
ALTER TABLE `student_exercise`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_exercise`
--
ALTER TABLE `student_exercise`
  ADD CONSTRAINT `student_exercise_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
