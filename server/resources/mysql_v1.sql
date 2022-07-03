-- creating database
CREATE DATABASE IF NOT EXISTS EHCMS;

-- creating users table
CREATE TABLE IF NOT EXISTS `EHCMS`.`users` (
  `recordId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userId` varchar(20) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `roleConfig` TEXT NOT NULL,
  `recordCreatedTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- creating patients table
CREATE TABLE IF NOT EXISTS `EHCMS`.`patients`(
    `recordId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date` VARCHAR(50) NOT NULL,
    `place` VARCHAR(50) NOT NULL,
    `symptoms` VARCHAR(500) NOT NULL,
    `severity` VARCHAR(20) NOT NULL,
    `gender` VARCHAR(10) NOT NULL,
    `address` VARCHAR(250) NOT NULL,
    `fullName` VARCHAR(50) NOT NULL,
    `phoneNumber` VARCHAR(15) NOT NULL,
    `dateOfBirth` VARCHAR(20) NOT NULL,
    `bloodGroup` VARCHAR(5) NOT NULL,
    `medicalReportFile` JSON DEFAULT NULL,
    `profileImage` JSON DEFAULT NULL,
    `relatedUserRecordId` int NOT NULL,
    `recordCreatedBy` VARCHAR(100) NOT NULL,
    `allocatedHospitalId` VARCHAR(50) DEFAULT NULL,
    `assignedHospitalId` VARCHAR(50) DEFAULT NULL,
    `recordCreationTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `recordLastUpdatedTime` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(`relatedUserRecordId`) REFERENCES users(`recordId`)
) ENGINE = INNODB DEFAULT CHARSET = utf8;