-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Št 18.Máj 2023, 20:52
-- Verzia serveru: 8.0.32-0ubuntu0.22.04.2
-- Verzia PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `school`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `exercise`
--

CREATE TABLE `exercise` (
  `id` int UNSIGNED NOT NULL,
  `file_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `exercise`
--

INSERT INTO `exercise` (`id`, `file_name`) VALUES
(4, 'odozva01pr.tex'),
(5, 'blokovka01pr.tex');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `student`
--

CREATE TABLE `student` (
  `id` int UNSIGNED NOT NULL,
  `login` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `surname` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `student`
--

INSERT INTO `student` (`id`, `login`, `password`, `name`, `surname`) VALUES
(1, 'doctah', '$argon2id$v=19$m=65536,t=4,p=1$OThrRGU0TjFkSzliV0p4ZA$CRt8K5SIC3dK+3JzsnUlGQ3InOUrh8U9IdD1/V6nvXw', 'Dominik', 'Racek'),
(2, 'student', '$argon2id$v=19$m=65536,t=4,p=1$bmhzcWltaFdVLy5neDkyTQ$Q8LTONtL8S4NxdgqFqGe5o53fJwBnVZBXfQTU0ef3Vc', 'Student', 'Student');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `student_exercise`
--

CREATE TABLE `student_exercise` (
  `id` int UNSIGNED NOT NULL,
  `exercise_id` int UNSIGNED NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `max_points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `student_exercise`
--

INSERT INTO `student_exercise` (`id`, `exercise_id`, `date_start`, `date_end`, `max_points`) VALUES
(3, 4, '2023-05-18', '2023-05-20', 5),
(4, 5, '2023-05-18', '2023-05-20', 5);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `task`
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

--
-- Sťahujem dáta pre tabuľku `task`
--

INSERT INTO `task` (`id`, `student_id`, `exercise_id`, `text`, `solution`, `your_solution`, `submitted`, `points`, `assigned_to`, `completed`) VALUES
(5, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{35}{(2s+5)^2}e^{-6s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7}{2}(t-6)e^{-\\frac{5}{2}(t-6)} \\right] \\eta(t-6)\n    ', '', 0, 0, NULL, 0),
(6, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{12}{(5s+4)^2}e^{-7s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{4}-\\dfrac{3}{4}e^{-\\frac{4}{5}(t-7)}-\\dfrac{3}{5}(t-7)e^{-\\frac{4}{5}(t-7)} \\right] \\eta(t-7)\n    ', '', 0, 0, NULL, 0),
(7, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{6}{(5s+2)^2}e^{-4s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)\n    ', '', 0, 0, NULL, 0),
(8, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{35}{(2s+5)^2}e^{-6s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7}{2}(t-6)e^{-\\frac{5}{2}(t-6)} \\right] \\eta(t-6)\n    ', '', 0, 0, NULL, 0),
(15, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{6}{(5s+2)^2}e^{-4s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)\n    ', '', 0, 0, NULL, 0),
(16, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{12}{(5s+4)^2}e^{-7s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{4}-\\dfrac{3}{4}e^{-\\frac{4}{5}(t-7)}-\\dfrac{3}{5}(t-7)e^{-\\frac{4}{5}(t-7)} \\right] \\eta(t-7)\n    ', '', 0, 0, NULL, 0),
(17, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{12}{(5s+4)^2}e^{-7s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{4}-\\dfrac{3}{4}e^{-\\frac{4}{5}(t-7)}-\\dfrac{3}{5}(t-7)e^{-\\frac{4}{5}(t-7)} \\right] \\eta(t-7)\n    ', '', 0, 0, NULL, 0),
(18, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{35}{(2s+5)^2}e^{-6s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7}{2}(t-6)e^{-\\frac{5}{2}(t-6)} \\right] \\eta(t-6)\n    ', '', 0, 0, NULL, 0),
(19, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{6}{(5s+2)^2}e^{-4s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)\n    ', '', 0, 0, NULL, 0),
(21, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{6}{(5s+2)^2}e^{-4s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)\n    ', '', 0, 0, NULL, 0),
(22, 2, 5, 'Nájdite prenosovú funkciu $F(s)=\\frac{Y(s)}{W(s)}$ pre systém opísaný blokovou schémou: \n\n    ', '\n        \\dfrac{2s^2+13s+10}{s^3+7s^2+18s+15}\n    ', '', 0, 0, NULL, 0),
(23, 2, 5, 'Nájdite prenosovú funkciu $F(s)=\\frac{Y(s)}{W(s)}$ pre systém opísaný blokovou schémou: \n\n    ', '\n        \\dfrac{2s^2+13s+10}{s^3+7s^2+18s+15}\n    ', '', 0, 0, NULL, 0),
(24, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{35}{(2s+5)^2}e^{-6s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7}{2}(t-6)e^{-\\frac{5}{2}(t-6)} \\right] \\eta(t-6)\n    ', '', 0, 0, NULL, 0),
(25, 2, 4, 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou\n    \\begin{equation*}\n        F(s)=\\frac{35}{(2s+5)^2}e^{-6s}\n    \\end{equation*}', '\n        y(t)=\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7}{2}(t-6)e^{-\\frac{5}{2}(t-6)} \\right] \\eta(t-6)\n    ', '', 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `teacher`
--

CREATE TABLE `teacher` (
  `id` int UNSIGNED NOT NULL,
  `login` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL,
  `surname` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `teacher`
--

INSERT INTO `teacher` (`id`, `login`, `password`, `name`, `surname`) VALUES
(1, 'xpavlisn', '$argon2id$v=19$m=65536,t=4,p=1$elIuVURCaW5VOVZKeXNORg$ZGNub829i6LTqGs0Cw0v42mRroIPqvQxcdjo/upJG/g', 'Nikolas', 'Pavlis'),
(2, 'teacher', '$argon2id$v=19$m=65536,t=4,p=1$NmdEY0NxcUI0czF0RjY3bg$yRU0/oqIaZNLpp6tyLRkXME1UlS5KFJWXaz/UQqvaYY', 'teacher', 'teacher');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `student_exercise`
--
ALTER TABLE `student_exercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexy pre tabuľku `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexy pre tabuľku `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pre tabuľku `student`
--
ALTER TABLE `student`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pre tabuľku `student_exercise`
--
ALTER TABLE `student_exercise`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `task`
--
ALTER TABLE `task`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pre tabuľku `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `student_exercise`
--
ALTER TABLE `student_exercise`
  ADD CONSTRAINT `student_exercise_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
