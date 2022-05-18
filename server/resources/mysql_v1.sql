-- creating database
CREATE DATABASE IF NOT EXISTS EHCMS;

-- creating users table
CREATE TABLE IF NOT EXISTS `EHCMS`.`users` (
  `recordId` int(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userId` varchar(20) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `userCreatedTime` varchar(50) NOT NULL,
  `roleConfig` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- creating patientDetails table
CREATE TABLE IF NOT EXISTS `EHCMS`.`patientDetails` (
  `date` varchar(50) NOT NULL,
  `place` varchar(50) NOT NULL,
  `symptoms` varchar(500) NOT NULL,
  `severity` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(250) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `dateOfBirth` varchar(20) NOT NULL,
  `bloodGroup` varchar(5) NOT NULL,
  `medicalReportFile` json DEFAULT NULL,
  `photoFile` json DEFAULT NULL,
  `recordId` int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `creatorEmail` varchar(50) NOT NULL,
  `recordCreationTime` varchar(50) NOT NULL,
  `creatorName` varchar(50) NOT NULL,
  `allocatedHospitalId` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `assignedHospitalId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;