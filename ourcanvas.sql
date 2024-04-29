-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 03:02 AM
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
-- Database: `ourcanvas`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `assignmentId` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`assignmentId`, `name`, `description`, `type`, `dueDate`, `points`) VALUES
(1, 'STS Paper', 'Final paper (20+ pages)', 'Essay', '2024-05-01', 100),
(4, 'Final Deliverable', 'Final deliverable for the semester project.', 'Project', '2024-04-29', 160),
(5, 'Final Deliverable', 'Final deliverable for the semester project.', 'Project', '2024-04-29', 160);

-- --------------------------------------------------------

--
-- Table structure for table `assignmentwork`
--

CREATE TABLE `assignmentwork` (
  `assignmentId` int(11) NOT NULL,
  `workId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `assignmentwork`
--

INSERT INTO `assignmentwork` (`assignmentId`, `workId`) VALUES
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `assigns`
--

CREATE TABLE `assigns` (
  `courseId` int(11) NOT NULL,
  `assignmentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `assigns`
--

INSERT INTO `assigns` (`courseId`, `assignmentId`) VALUES
(1, 4),
(1, 5),
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `canvasuser`
--

CREATE TABLE `canvasuser` (
  `userId` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `login_email` varchar(255) DEFAULT NULL,
  `login_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canvasuser`
--

INSERT INTO `canvasuser` (`userId`, `name`, `login_email`, `login_password`) VALUES
(1, 'Brandon Park', 'bjp4un@virginia.edu', '1234'),
(2, 'Yuna Park', 'mah6xp@virginia.edu', '5678'),
(3, 'Max Bai', 'wbu7dr@virginia.edu', '1357'),
(4, 'Christian D-Virgilio', 'cjd8hs@virginia.edu', '2468');

-- --------------------------------------------------------

--
-- Table structure for table `collaborateon`
--

CREATE TABLE `collaborateon` (
  `userId` int(11) NOT NULL,
  `workId` int(11) NOT NULL,
  `permissions` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `collaborateon`
--

INSERT INTO `collaborateon` (`userId`, `workId`, `permissions`) VALUES
(1, 1, 1),
(2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseId` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseId`, `code`, `name`) VALUES
(1, 'CS4750', 'Database Systems'),
(2, 'CS4414', 'Operating Systems'),
(3, 'STS4600', 'The Engineer, Ethics, and Professional Responsibility'),
(4, 'CS4991', 'Capstone Technical Report'),
(7, 'CS3120', 'DMT2');

-- --------------------------------------------------------

--
-- Table structure for table `enrolledin`
--

CREATE TABLE `enrolledin` (
  `courseId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolledin`
--

INSERT INTO `enrolledin` (`courseId`, `userId`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(3, 1),
(3, 2),
(4, 2),
(7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `reqId` int(11) NOT NULL,
  `reqDate` date NOT NULL,
  `roomNumber` varchar(30) DEFAULT NULL,
  `reqBy` varchar(60) NOT NULL,
  `repairDesc` varchar(255) NOT NULL,
  `reqPriority` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userassignment`
--

CREATE TABLE `userassignment` (
  `userId` int(11) NOT NULL,
  `assignmentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userassignment`
--

INSERT INTO `userassignment` (`userId`, `assignmentId`) VALUES
(1, 1),
(2, 1),
(3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `userwork`
--

CREATE TABLE `userwork` (
  `userId` int(11) NOT NULL,
  `workId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userwork`
--

INSERT INTO `userwork` (`userId`, `workId`) VALUES
(1, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE `work` (
  `workId` int(11) NOT NULL,
  `notes` mediumtext DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work`
--

INSERT INTO `work` (`workId`, `notes`, `name`, `file`) VALUES
(1, 'Currently in progress', 'Notes from 3/23', NULL),
(3, 'in progress', 'Code', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`assignmentId`);

--
-- Indexes for table `assignmentwork`
--
ALTER TABLE `assignmentwork`
  ADD PRIMARY KEY (`assignmentId`,`workId`);

--
-- Indexes for table `assigns`
--
ALTER TABLE `assigns`
  ADD PRIMARY KEY (`courseId`,`assignmentId`),
  ADD KEY `assignment_fk` (`assignmentId`);

--
-- Indexes for table `canvasuser`
--
ALTER TABLE `canvasuser`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `collaborateon`
--
ALTER TABLE `collaborateon`
  ADD PRIMARY KEY (`userId`,`workId`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseId`);

--
-- Indexes for table `enrolledin`
--
ALTER TABLE `enrolledin`
  ADD PRIMARY KEY (`courseId`,`userId`),
  ADD KEY `user_fk` (`userId`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`reqId`);

--
-- Indexes for table `userassignment`
--
ALTER TABLE `userassignment`
  ADD PRIMARY KEY (`userId`,`assignmentId`);

--
-- Indexes for table `userwork`
--
ALTER TABLE `userwork`
  ADD PRIMARY KEY (`userId`,`workId`),
  ADD KEY `work_fk` (`workId`);

--
-- Indexes for table `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`workId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `assignmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `canvasuser`
--
ALTER TABLE `canvasuser`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `reqId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work`
--
ALTER TABLE `work`
  MODIFY `workId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigns`
--
ALTER TABLE `assigns`
  ADD CONSTRAINT `assignment_fk` FOREIGN KEY (`assignmentId`) REFERENCES `assignment` (`assignmentId`),
  ADD CONSTRAINT `course_fk` FOREIGN KEY (`courseId`) REFERENCES `course` (`courseId`);

--
-- Constraints for table `enrolledin`
--
ALTER TABLE `enrolledin`
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`userId`) REFERENCES `canvasuser` (`userId`);

--
-- Constraints for table `userwork`
--
ALTER TABLE `userwork`
  ADD CONSTRAINT `work_fk` FOREIGN KEY (`workId`) REFERENCES `work` (`workId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
