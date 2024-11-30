-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 06:02 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignallocate`
--

CREATE TABLE `assignallocate` (
  `assignmentId` int(11) NOT NULL,
  `duedate` date DEFAULT NULL,
  `assignment` mediumblob DEFAULT NULL,
  `course_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignsubmission`
--

CREATE TABLE `assignsubmission` (
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `solution` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_name` varchar(255) DEFAULT NULL,
  `course_id` varchar(255) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_name`, `course_id`, `instructor_id`) VALUES
('dbms', 'dbms101', 241),
('Database Management', 'LA2104', 244),
('Database', 'MA2104', 244),
('DataBase Management System', 'MA518', 246),
('Computer Programming', 'MA540', 244),
('Computer Programming in C', 'MA541', 246),
('Network Science', 'MA560', 246),
('Data Structure & Algorithm', 'MA595', 246),
('Network_science', 'NET2104', 241);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `enrollmentId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `courseId` varchar(255) NOT NULL,
  `feedBack` enum('Excellent','Good','Average','Poor') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollmentId`, `studentId`, `courseId`, `feedBack`) VALUES
(9, 2402, 'MA518', ''),
(10, 2402, 'MA540', ''),
(11, 2402, 'MA595', ''),
(4, 2403, 'dbms101', 'Average'),
(12, 2405, 'MA518', NULL),
(13, 2405, 'MA595', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `instructor_mail` varchar(255) DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `password_` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`instructor_mail`, `instructor_id`, `Name`, `password_`) VALUES
('suvro@gmail.com', 241, 'suvromoy', '$2y$10$CQCAnAhjLX4f0PhUgnBsLOz2fweSpIHAIFPfQcDukyqPvcTguYARy'),
('ety@gmail.com', 242, 'Ety Halder', '$2y$10$P5JugBUXqK15P8gSt6LxZehUxIJsGU0NaLRsLf7iAfBYOLygNrwAK'),
('prafulla@gmail.com', 243, 'prafulla kumar saha', '$2y$10$uzUItEjrHbvHmsboeB5JtuNJEpBDINo4Cmv20oV4JR7c03DmcfTgi'),
('avinandan24@gmail.com', 244, 'Avinandan Halder', '$2y$10$p82VffOgoUJinWrV2hBg6urSfTDJM.mnz8PSgyeSsXg663L.hC9ki'),
('ankit24@gmail.com', 245, 'Ankit Halder', '$2y$10$jUPly8AideCvazCxVxhxzuXERfrwY8NZ3C7Z.oSO6XEML7vqLdjxm'),
('ashok@gmail.com', 246, 'Ashok Singh Sairam', '$2y$10$Lme1pk0wSCu6EzsYMHwdvOmn9yNxNrD/qs0ATp6VU/Xs2fgmR0VZC');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `type` enum('MCQ','Short Answer','T/F') DEFAULT NULL,
  `course_id` varchar(255) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `type`, `course_id`, `question`) VALUES
(19, 'Short Answer', 'MA518', 'Describe Functional Dependencies Mathematically.'),
(20, 'Short Answer', 'MA518', 'Why do we need Normalization?'),
(21, 'T/F', 'MA518', 'A database is in BCNF implies that it is also in 3NF'),
(22, 'Short Answer', 'MA595', 'write the algorithm to create a binary search tree from the post-order traversal of that tree'),
(23, 'T/F', 'MA595', 'Each Tree is a graph but not every graph is a tree.'),
(24, 'Short Answer', 'MA595', 'Write the code for Merge Sort.'),
(25, 'Short Answer', 'MA595', 'write the code for fibonacci series.');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(11) NOT NULL,
  `course_id` varchar(255) DEFAULT NULL,
  `question_id` int(11) NOT NULL,
  `quiz_name` varchar(255) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `course_id`, `question_id`, `quiz_name`, `instructor_id`) VALUES
(1, 'MA595', 22, 'DataStructure QuizI', 246),
(1, 'MA595', 23, 'DataStructure QuizI', 246),
(2, 'MA518', 19, 'Database quizI', 246),
(2, 'MA518', 20, 'Database quizI', 246),
(2, 'MA518', 21, 'Database quizI', 246),
(3, 'MA518', 19, 'DBMS_Quiz_2', 246),
(3, 'MA518', 21, 'DBMS_Quiz_2', 246),
(4, 'MA595', 22, 'DSA_2', 246),
(4, 'MA595', 24, 'DSA_2', 246),
(4, 'MA595', 25, 'DSA_2', 246);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt`
--

CREATE TABLE `quiz_attempt` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `grade` decimal(4,2) DEFAULT NULL,
  `attempt_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempt`
--

INSERT INTO `quiz_attempt` (`attempt_id`, `student_id`, `quiz_id`, `grade`, `attempt_date`) VALUES
(1, 2402, 2, NULL, '0000-00-00'),
(2, 2405, 2, NULL, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt_`
--

CREATE TABLE `quiz_attempt_` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `attempt_number` int(11) NOT NULL,
  `grade` decimal(4,2) DEFAULT NULL,
  `attempt_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempt_`
--

INSERT INTO `quiz_attempt_` (`attempt_id`, `student_id`, `quiz_id`, `attempt_number`, `grade`, `attempt_date`) VALUES
(8, 2405, 4, 1, NULL, '2024-11-11'),
(9, 2405, 4, 2, NULL, '2024-11-11'),
(10, 2405, 3, 1, NULL, '2024-11-11'),
(11, 2405, 1, 1, NULL, '2024-11-11'),
(12, 2405, 2, 1, NULL, '2024-11-11'),
(13, 2405, 1, 2, NULL, '2024-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_response`
--

CREATE TABLE `quiz_response` (
  `response_id` int(11) NOT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `question_solution` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_response`
--

INSERT INTO `quiz_response` (`response_id`, `attempt_id`, `question_id`, `student_id`, `question_solution`) VALUES
(1, 8, 22, 2405, 'htg'),
(2, 8, 24, 2405, 'rger'),
(3, 8, 25, 2405, 'rgf'),
(4, 9, 22, 2405, 'I don\'t Know'),
(5, 9, 24, 2405, 'no'),
(6, 9, 25, 2405, 'no'),
(7, 10, 19, 2405, 'no'),
(8, 10, 21, 2405, 'False'),
(9, 11, 22, 2405, 'no'),
(10, 11, 23, 2405, 'True'),
(11, 12, 19, 2405, 'dcvcsdbc'),
(12, 12, 20, 2405, 'hdsbccb'),
(13, 12, 21, 2405, 'False'),
(14, 13, 22, 2405, 'nejfej'),
(15, 13, 23, 2405, 'True');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_mail_id` varchar(255) DEFAULT NULL,
  `rollNumber` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `password_` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_mail_id`, `rollNumber`, `Name`, `password_`) VALUES
('chayan@gmail.com', 2402, 'Chayan', '$2y$10$OrR6Sh.4qPqQ.fk7LY9elOgv9PWO4zmoLnJWzfGOJ/5pzThWS/LMy'),
('ankur@gmail.com', 2403, 'ankur raj', '$2y$10$vYf/cZ6XvVrETwzMK4Sg/ufl3UYzzgHRaKoCwaS8EeQTmf486aiwe'),
('ankit@gmail.com', 2404, 'Ankit Halder', '$2y$10$xVMpn0KZF/k95zLute0YguWZrC603RFrMFZY2T6tLeJujOYfUzkAO'),
('chayanh72@yahoo.com', 2405, 'Chayan Halder', '$2y$10$Pj.W1QaTAK21ByY/7t53E.T5P2PJh0u2B7RJZ/zU5quLQobFaKknu');

-- --------------------------------------------------------

--
-- Table structure for table `surveycreate`
--

CREATE TABLE `surveycreate` (
  `survey_id` int(11) NOT NULL,
  `questions` text DEFAULT NULL,
  `course_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surveyresponse`
--

CREATE TABLE `surveyresponse` (
  `surveyId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `response` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignallocate`
--
ALTER TABLE `assignallocate`
  ADD PRIMARY KEY (`assignmentId`),
  ADD KEY `fk_course_id` (`course_id`);

--
-- Indexes for table `assignsubmission`
--
ALTER TABLE `assignsubmission`
  ADD PRIMARY KEY (`assignment_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `unicou_name` (`course_name`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`studentId`,`courseId`),
  ADD UNIQUE KEY `enrollmentId` (`enrollmentId`),
  ADD KEY `courseId` (`courseId`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`instructor_id`),
  ADD UNIQUE KEY `uni_mail_1` (`instructor_mail`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `course_fk` (`course_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`,`question_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `question_fk` (`question_id`),
  ADD KEY `instruct_fk` (`instructor_id`);

--
-- Indexes for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_attempt_`
--
ALTER TABLE `quiz_attempt_`
  ADD PRIMARY KEY (`attempt_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`quiz_id`,`attempt_number`),
  ADD KEY `quiz_attempt__ibfk_2` (`quiz_id`);

--
-- Indexes for table `quiz_response`
--
ALTER TABLE `quiz_response`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `quiz_response_ibfk_2` (`attempt_id`),
  ADD KEY `quiz_response_ibfk_1` (`student_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`rollNumber`),
  ADD UNIQUE KEY `uni_mail_2` (`student_mail_id`);

--
-- Indexes for table `surveycreate`
--
ALTER TABLE `surveycreate`
  ADD PRIMARY KEY (`survey_id`),
  ADD KEY `fk_course_id_1` (`course_id`);

--
-- Indexes for table `surveyresponse`
--
ALTER TABLE `surveyresponse`
  ADD PRIMARY KEY (`surveyId`,`studentId`),
  ADD KEY `studentId` (`studentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignallocate`
--
ALTER TABLE `assignallocate`
  MODIFY `assignmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enrollmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_attempt_`
--
ALTER TABLE `quiz_attempt_`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quiz_response`
--
ALTER TABLE `quiz_response`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `rollNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2406;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignallocate`
--
ALTER TABLE `assignallocate`
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `assignsubmission`
--
ALTER TABLE `assignsubmission`
  ADD CONSTRAINT `assignsubmission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignallocate` (`assignmentId`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignsubmission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`rollNumber`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`instructor_id`) ON DELETE SET NULL;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `student` (`rollNumber`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`courseId`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `course_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `instruct_fk` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`instructor_id`),
  ADD CONSTRAINT `question_fk` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  ADD CONSTRAINT `quiz_attempt_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`rollNumber`);

--
-- Constraints for table `quiz_attempt_`
--
ALTER TABLE `quiz_attempt_`
  ADD CONSTRAINT `quiz_attempt__ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`rollNumber`),
  ADD CONSTRAINT `quiz_attempt__ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`);

--
-- Constraints for table `quiz_response`
--
ALTER TABLE `quiz_response`
  ADD CONSTRAINT `quiz_response_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`rollNumber`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_response_ibfk_2` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempt_` (`attempt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_response_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `surveycreate`
--
ALTER TABLE `surveycreate`
  ADD CONSTRAINT `fk_course_id_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `surveyresponse`
--
ALTER TABLE `surveyresponse`
  ADD CONSTRAINT `surveyresponse_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `student` (`rollNumber`) ON DELETE CASCADE,
  ADD CONSTRAINT `surveyresponse_ibfk_2` FOREIGN KEY (`surveyId`) REFERENCES `surveycreate` (`survey_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
