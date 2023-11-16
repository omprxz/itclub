-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql308.byetcluster.com
-- Generation Time: Oct 31, 2023 at 05:28 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_35270932_itclub`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`if0_35270932`@`192.168.0.%` PROCEDURE `log_activity_procedure` (`tableName` VARCHAR(255), `activityType` VARCHAR(10), `sqlStatement` TEXT)  BEGIN
    INSERT INTO log_activity (TableName, Activity, SQLActivity, ActivityTimeStamp)
    VALUES (tableName, activityType, sqlStatement, NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `adminCreds`
--

CREATE TABLE `adminCreds` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_pass` varchar(255) NOT NULL,
  `admin_level` tinyint(1) NOT NULL DEFAULT 1,
  `admin_identification` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `adminCreds`
--

INSERT INTO `adminCreds` (`admin_id`, `admin_email`, `admin_name`, `admin_pass`, `admin_level`, `admin_identification`) VALUES
(1, 'omprxz@gmail.com', 'Omprakash', 'Om123', 7, ''),
(2, 'theversion1602@gmail.com', 'Vivek Kumar', 'It2021@1602', 1, ''),
(3, 'modassirraza616@gmail.com', 'Md. Modassir Raza', 'Zainab@234', 1, '');

--
-- Triggers `adminCreds`
--
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_adminCreds_delete` AFTER DELETE ON `adminCreds` FOR EACH ROW BEGIN
    CALL log_activity_procedure('adminCreds', 'DELETE', 'DELETE statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_adminCreds_insert` AFTER INSERT ON `adminCreds` FOR EACH ROW BEGIN
    CALL log_activity_procedure('adminCreds', 'INSERT', 'INSERT statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_adminCreds_update` AFTER UPDATE ON `adminCreds` FOR EACH ROW BEGIN
    CALL log_activity_procedure('adminCreds', 'UPDATE', 'UPDATE statement executed');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(10) NOT NULL,
  `event_title` varchar(255) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_imgurl` varchar(255) DEFAULT NULL,
  `event_gphotoslink` varchar(255) DEFAULT NULL,
  `event_ytlink` varchar(255) DEFAULT NULL,
  `event_queryTimestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `query_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_title`, `event_description`, `event_date`, `event_imgurl`, `event_gphotoslink`, `event_ytlink`, `event_queryTimestamp`, `query_admin_id`) VALUES
(1, 'Youth to Youth Connect: BIS Seminar at NGP PATNA-13', 'An activity called Youth to Youth Connect is being organized in collaboration with Bureau of Indian Standards, BIS.<div><br></div><div>To enlighten our students about the various certification schemes and safety benefits derived from BIS certified products and the ISI Mark products certified by the standard seal, New Government Polytechnic Patna-13 is organising a seminar.</div><div><br></div><div><b>Date: 7th Oct. 2023</b></div><div><b>Time: 11am onwards&nbsp;</b></div><div><b>Venue: QIP Hall</b></div><div><br></div><div>Thanks and Regards</div><div><b>IT CLUB</b></div><div><b>NGP PATNA-13</b></div>', '2023-10-07', 'IMG_20231027_204617_058.jpg', 'https://photos.app.goo.gl/LF7tt7ym784vcMsP7', 'https://www.youtube.com/live/SNquuXUdkYI?si=smeZcDUy1Yr3Igs4', '2023-10-27 16:53:36.427531', 1),
(2, 'à¤¹à¤¿à¤‚à¤¦à¥€ à¤¦à¤¿à¤µà¤¸ à¤¸à¤®à¤¾à¤°à¥‹à¤¹: à¤¹à¤®à¤¾à¤°à¥€ à¤®à¤¾à¤¤à¥ƒà¤­à¤¾à¤·à¤¾ à¤•à¤¾ à¤—à¥Œà¤°à¤µ', '\"à¤œà¥ˆà¤¸à¥‡ à¤°à¤‚à¤—à¥‹à¤‚ à¤•à¥‡ à¤®à¤¿à¤²à¤¨à¥‡ à¤¸à¥‡ à¤–à¤¿à¤²à¤¤à¤¾ à¤¹à¥ˆ à¤¬à¤¸à¤‚à¤¤,<div>à¤µà¥ˆà¤¸à¥‡ à¤­à¤¾à¤·à¤¾à¤“à¤‚ à¤•à¥€ à¤®à¤¿à¤¶à¥à¤°à¥€ à¤¸à¥€ à¤¬à¥‹à¤²à¥€ à¤¹à¥ˆ à¤¹à¤¿à¤¨à¥à¤¦à¥€\"</div><div><br></div><div>à¤¹à¤¿à¤‚à¤¦à¥€ à¤¦à¤¿à¤µà¤¸ à¤•à¥€ à¤¶à¥à¤­à¤•à¤¾à¤®à¤¨à¤¾à¤à¤‚!</div><div><br></div><div>&nbsp; &nbsp; &nbsp; à¤¹à¤¿à¤‚à¤¦à¥€ à¤à¤• à¤à¤¸à¥€ à¤­à¤¾à¤·à¤¾ à¤¹à¥ˆ à¤œà¥‹ à¤­à¤¾à¤°à¤¤ à¤•à¥€ à¤µà¤¿à¤µà¤¿à¤§à¤¤à¤¾ à¤”à¤° à¤à¤•à¤¤à¤¾ à¤•à¤¾ à¤ªà¥à¤°à¤¤à¥€à¤• à¤¹à¥ˆà¥¤ à¤¯à¤¹ à¤¨ à¤•à¥‡à¤µà¤² à¤¶à¤¬à¥à¤¦à¥‹à¤‚ à¤•à¤¾ à¤¸à¤‚à¤—à¥à¤°à¤¹à¤£ à¤¹à¥ˆ, à¤¬à¤²à¥à¤•à¤¿ à¤­à¤¾à¤°à¤¤à¥€à¤¯ à¤¸à¤‚à¤¸à¥à¤•à¥ƒà¤¤à¤¿, à¤—à¥à¤°à¤‚à¤¥ à¤¸à¤¾à¤¹à¤¿à¤¤à¥à¤¯, à¤”à¤° à¤§à¤°à¥à¤® à¤•à¤¾ à¤­à¥€ à¤ªà¥à¤°à¤¤à¥€à¤• à¤¹à¥ˆà¥¤ à¤¹à¤¿à¤‚à¤¦à¥€ à¤¨à¥‡ à¤µà¤¿à¤­à¤¿à¤¨à¥à¤¨ à¤°à¤¾à¤œà¥à¤¯à¥‹à¤‚ à¤®à¥‡à¤‚ à¤…à¤ªà¤¨à¥‡ à¤µà¤¿à¤­à¤¿à¤¨à¥à¤¨ à¤°à¥‚à¤ªà¥‹à¤‚ à¤•à¥‹ à¤µà¤¿à¤•à¤¸à¤¿à¤¤ à¤•à¤¿à¤¯à¤¾ à¤¹à¥ˆà¥¤</div><div><br></div><div>&nbsp; &nbsp; &nbsp;à¤‡à¤¸ à¤¹à¤¿à¤‚à¤¦à¥€ à¤¦à¤¿à¤µà¤¸ à¤ªà¤° , à¤¨à¤µà¥€à¤¨ à¤°à¤¾à¤œà¤•à¥€à¤¯ à¤ªà¥‰à¤²à¤¿à¤Ÿà¥‡à¤•à¥à¤¨à¤¿à¤•, à¤ªà¤Ÿà¤¨à¤¾-13 à¤†à¤ª à¤¸à¤­à¥€ à¤•à¥‹ à¤¹à¤¿à¤‚à¤¦à¥€ à¤¦à¤¿à¤µà¤¸ à¤•à¥€ à¤¹à¤¾à¤°à¥à¤¦à¤¿à¤• à¤¶à¥à¤­à¤•à¤¾à¤®à¤¨à¤¾à¤à¤‚ à¤¦à¥‡à¤¤à¤¾ à¤¹à¥ˆ, à¤¤à¥‹ à¤†à¤‡à¤ à¤¹à¤® à¤¸à¤­à¥€ à¤®à¤¿à¤²à¤•à¤° à¤¹à¤¿à¤‚à¤¦à¥€ à¤•à¥‹ à¤à¤• à¤®à¤œà¤¬à¥‚à¤¤ à¤”à¤° à¤¸à¤®à¥ƒà¤¦à¥à¤§ à¤­à¤¾à¤·à¤¾ à¤•à¥‡ à¤°à¥‚à¤ª à¤®à¥‡à¤‚ à¤¬à¤¢à¤¼à¤¾à¤µà¤¾ à¤¦à¥‡à¤‚ à¤”à¤° à¤¹à¤®à¤¾à¤°à¥€ à¤®à¤¾à¤¤à¥ƒà¤­à¤¾à¤·à¤¾ à¤•à¤¾ à¤—à¥Œà¤°à¤µ à¤¬à¤¢à¤¼à¤¾à¤à¤‚à¥¤</div><div><br></div><div>à¤¸à¤¾à¤¦à¤° à¤§à¤¨à¥à¤¯à¤µà¤¾à¤¦</div><div><b>à¤†à¤ˆà¤Ÿà¥€ à¤•à¥à¤²à¤¬</b></div><div><b>à¤à¤¨à¤œà¥€à¤ªà¥€, à¤ªà¤Ÿà¤¨à¤¾-13</b></div>', '2023-09-14', 'IMG_20231027_205114_569.jpg', 'https://photos.app.goo.gl/goDX8Q4jV3UTLCuw9', 'https://www.youtube.com/live/dKJQ5iRgluE?feature=shared', '2023-10-27 16:56:07.571432', 1),
(3, '77th Independence Day Celebration at NGP PATNA-13', '\"ðŸ‡®ðŸ‡³ Celebrate Freedom, Embrace Unity! ðŸ•Šï¸\"<div><br></div><div>\"77th INDEPENDENCE DAY\"</div><div><br></div><div>From the echoes of \'Jai Hind\' to the diversity that defines us, Independence Day is a testament to India\'s rich heritage and indomitable spirit. Let\'s stand together, breaking the chains of prejudice and forging ahead as one united, vibrant nation.&nbsp;</div><div><br></div><div>NEW GOVERNMENT POLYTECHNIC PATNA 13 wants you all to Join us in commemorating the resilience and progress that mark this day. Let\'s light up our hearts with the flames of patriotism and pledge to uphold the values of our freedom fighters. HAPPY INDEPENDENCE DAY!! ðŸ‡®ðŸ‡³</div><div>&nbsp;</div><div>Thanks &amp; Regards</div><div><b>IT CLUB</b></div><div><b>NGP PATNA -13</b></div>', '2023-08-15', 'IMG_20231027_222634_895.jpg', 'https://photos.app.goo.gl/GYrpX6vSer96e7d27', 'https://www.youtube.com/live/k1s8l9bObvg?feature=share', '2023-10-27 16:58:29.405499', 1),
(5, 'Munshi Premchand Jayanti Celebration at NGP PATNA-13', 'à¤®à¤¨ à¤à¤• à¤¡à¤°à¤ªà¥‹à¤• à¤¶à¤¤à¥à¤°à¥ à¤¹à¥ˆ à¤œà¥‹ à¤¹à¤®à¥‡à¤¶à¤¾ à¤ªà¥€à¤  à¤•à¥‡ à¤ªà¥€à¤›à¥‡ à¤¸à¥‡ à¤µà¤¾à¤° à¤•à¤°à¤¤à¤¾ à¤¹à¥ˆà¥¤<div>&nbsp;*~à¤®à¥à¤‚à¤¶à¥€ à¤ªà¥à¤°à¥‡à¤®à¤šà¤‚à¤¦*&nbsp; &nbsp;</div><div>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</div><div>à¤ªà¥à¤°à¥‡à¤®à¤šà¤‚à¤¦ à¤œà¥€ à¤•à¥€ à¤°à¤šà¤¨à¤¾à¤à¤ à¤­à¤¾à¤°à¤¤à¥€à¤¯ à¤¸à¤¾à¤¹à¤¿à¤¤à¥à¤¯ à¤•à¥€ à¤à¤• à¤®à¤¹à¤¤à¥à¤µà¤ªà¥‚à¤°à¥à¤£ à¤§à¤°à¥‹à¤¹à¤° à¤¹à¥ˆà¤‚à¥¤ à¤‰à¤¨à¤•à¥€ à¤•à¤¹à¤¾à¤¨à¤¿à¤¯à¤¾à¤ à¤œà¥€à¤µà¤¨ à¤•à¥‡ à¤…à¤²à¤—-à¤…à¤²à¤— à¤ªà¤¹à¤²à¥à¤“à¤‚ à¤•à¥‹ à¤›à¥‚à¤¤à¥€ à¤¹à¥ˆà¤‚ à¤”à¤° à¤¸à¤®à¤¾à¤œ à¤•à¥€ à¤¸à¤®à¤¸à¥à¤¯à¤¾à¤“à¤‚ à¤•à¥‹ à¤‰à¤œà¤¾à¤—à¤° à¤•à¤°à¤¤à¥€ à¤¹à¥ˆà¤‚à¥¤ à¤ªà¥à¤°à¥‡à¤®à¤šà¤‚à¤¦ à¤œà¥€ à¤•à¥€ à¤°à¤šà¤¨à¤¾à¤à¤ à¤¹à¤®à¥‡à¤‚ à¤¸à¤®à¤¾à¤œ à¤®à¥‡à¤‚ à¤œà¤¾à¤—à¤°à¥‚à¤•à¤¤à¤¾ à¤«à¥ˆà¤²à¤¾à¤¨à¥‡, à¤¨à¥à¤¯à¤¾à¤¯ à¤•à¥€ à¤®à¤¾à¤‚à¤— à¤•à¤°à¤¨à¥‡, à¤”à¤° à¤…à¤šà¥à¤›à¥‡ à¤•à¤¾à¤® à¤•à¥€ à¤ªà¥à¤°à¥‡à¤°à¤£à¤¾ à¤¦à¥‡à¤¨à¥‡ à¤•à¥‡ à¤²à¤¿à¤ à¤ªà¥à¤°à¥‡à¤°à¤¿à¤¤ à¤•à¤°à¤¤à¥€ à¤¹à¥ˆà¤‚à¥¤&nbsp;</div><div><br></div><div>*à¤¨à¤µà¥€à¤¨ à¤°à¤¾à¤œà¤•à¥€à¤¯ à¤ªà¥‰à¤²à¤¿à¤Ÿà¥‡à¤•à¥à¤¨à¤¿à¤• à¤ªà¤Ÿà¤¨à¤¾ -13* à¤•à¥€ à¤¤à¤°à¤« à¤¸à¥‡ à¤‰à¤ªà¤¨à¥à¤¯à¤¾à¤¸ à¤¸à¤®à¥à¤°à¤¾à¤Ÿ à¤ªà¥à¤°à¥‡à¤®à¤šà¤‚à¤¦ à¤œà¥€ à¤•à¥‡ à¤œà¤¨à¥à¤®à¤¦à¤¿à¤µà¤¸ à¤ªà¤°, à¤‰à¤¨à¤•à¥€ à¤•à¤¾à¤²à¤œà¤¯à¥€ à¤°à¤šà¤¨à¤¾à¤“à¤‚, à¤ªà¥à¤°à¤–à¤° à¤¸à¥‹à¤š à¤µ à¤µà¥à¤¯à¤•à¥à¤¤à¤¿à¤¤à¥à¤µ à¤•à¥‹ à¤¶à¤¤ à¤¶à¤¤ à¤¨à¤®à¤¨à¥¤</div><div>*à¤®à¥à¤‚à¤¶à¥€ à¤ªà¥à¤°à¥‡à¤®à¤šà¤‚à¤¦ à¤œà¤¯à¤‚à¤¤à¥€ à¤•à¥€ à¤¹à¤¾à¤°à¥à¤¦à¤¿à¤• à¤¶à¥à¤­à¤•à¤¾à¤®à¤¨à¤¾à¤à¤‚!*</div><div><br></div><div>Thanks &amp; Regards&nbsp;</div><div><b>IT CLUB</b></div><div><b>NGP PATNA - 13</b></div>', '2023-07-15', 'IMG_20231027_222850_016.jpg', 'https://photos.app.goo.gl/qRZAvjCCwL2UXXCy5', 'https://www.youtube.com/live/EfFqcmJukIE?feature=share', '2023-10-27 18:10:48.402852', 1);

--
-- Triggers `events`
--
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_events_delete` AFTER DELETE ON `events` FOR EACH ROW BEGIN
    CALL log_activity_procedure('events', 'DELETE', 'DELETE statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_events_insert` AFTER INSERT ON `events` FOR EACH ROW BEGIN
    CALL log_activity_procedure('events', 'INSERT', 'INSERT statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_events_update` AFTER UPDATE ON `events` FOR EACH ROW BEGIN
    CALL log_activity_procedure('events', 'UPDATE', 'UPDATE statement executed');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `extra`
--

CREATE TABLE `extra` (
  `dataid` int(10) NOT NULL,
  `key1` varchar(255) DEFAULT NULL,
  `value1` varchar(255) DEFAULT NULL,
  `value2` varchar(255) DEFAULT NULL,
  `value3` varchar(255) DEFAULT NULL,
  `timestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `query_admin_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extra`
--

INSERT INTO `extra` (`dataid`, `key1`, `value1`, `value2`, `value3`, `timestamp`, `query_admin_id`) VALUES
(1, 'joinForm_expiry', '2023-10-23 17:13:00', '', '', '2023-10-29 21:55:41.000000', 1);

--
-- Triggers `extra`
--
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_extra_delete` AFTER DELETE ON `extra` FOR EACH ROW BEGIN
    CALL log_activity_procedure('extra', 'DELETE', 'DELETE statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_extra_insert` AFTER INSERT ON `extra` FOR EACH ROW BEGIN
    CALL log_activity_procedure('extra', 'INSERT', 'INSERT statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_extra_update` AFTER UPDATE ON `extra` FOR EACH ROW BEGIN
    CALL log_activity_procedure('extra', 'UPDATE', 'UPDATE statement executed');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `joinrequests`
--

CREATE TABLE `joinrequests` (
  `joiningid` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `session` varchar(10) NOT NULL,
  `branch` varchar(10) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `choice1` varchar(50) NOT NULL,
  `choice2` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `rollno` varchar(20) NOT NULL DEFAULT '2022-CSE-02',
  `testResult1` tinyint(1) NOT NULL DEFAULT 0,
  `testResult2` tinyint(1) NOT NULL DEFAULT 0,
  `interviewResult1` tinyint(1) NOT NULL DEFAULT 0,
  `interviewResult2` tinyint(1) NOT NULL DEFAULT 0,
  `query_admin_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `LogID` int(11) NOT NULL,
  `TableName` varchar(255) DEFAULT NULL,
  `Activity` varchar(10) DEFAULT NULL,
  `SQLActivity` text DEFAULT NULL,
  `ActivityTimeStamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_activity`
--

INSERT INTO `log_activity` (`LogID`, `TableName`, `Activity`, `SQLActivity`, `ActivityTimeStamp`) VALUES
(1, 'extra', 'INSERT', 'INSERT statement executed', '2023-10-29 21:50:49'),
(2, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:52:12'),
(3, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:52:14'),
(4, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:53:10'),
(5, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:53:43'),
(6, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:53:56'),
(7, 'extra', 'DELETE', 'DELETE statement executed', '2023-10-29 21:54:43'),
(8, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:55:41'),
(9, 'extra', 'UPDATE', 'UPDATE statement executed', '2023-10-29 21:56:26'),
(10, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(11, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(12, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(13, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(14, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(15, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(16, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(17, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(18, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(19, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20'),
(20, 'members', 'UPDATE', 'UPDATE statement executed', '2023-10-30 18:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(255) NOT NULL,
  `member_branch` varchar(255) NOT NULL,
  `member_session` varchar(255) NOT NULL,
  `member_email` varchar(255) DEFAULT NULL,
  `member_linkedin` varchar(255) DEFAULT NULL,
  `member_instagram` varchar(255) DEFAULT NULL,
  `member_category` varchar(255) NOT NULL,
  `member_pfpurl` varchar(255) DEFAULT NULL,
  `member_secretcode` varchar(255) DEFAULT NULL,
  `member_designation` varchar(255) NOT NULL DEFAULT 'IT Club Member',
  `member_induction` tinyint(2) DEFAULT NULL,
  `member_isTeamLeader` tinyint(1) DEFAULT NULL,
  `member_isClubLeader` int(11) NOT NULL DEFAULT 0,
  `member_category2` varchar(100) DEFAULT NULL,
  `member_category3` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `member_name`, `member_branch`, `member_session`, `member_email`, `member_linkedin`, `member_instagram`, `member_category`, `member_pfpurl`, `member_secretcode`, `member_designation`, `member_induction`, `member_isTeamLeader`, `member_isClubLeader`, `member_category2`, `member_category3`) VALUES
(1, 'Omprakash Kumar', 'CSE', '2022-25', 'omprxz@gmail.com', 'www.linkedin.com/in/omprxz', 'https://www.instagram.com/omprxz', 'Website Maintenance', '1.webp', '0151', 'Club Member', 3, 0, 0, NULL, NULL),
(2, 'Utkarsh Kumar', 'CSE', '2021-24', 'utkarshkumargupta1415@gmail.com', 'https://www.linkedin.com/in/gupta-utkarsh-61ab16226', 'https://www.instagram.com/m_ohit_gupta', 'Graphics Designing', '2.webp', '0152', 'Club Member', 2, 0, 0, '', ''),
(3, 'Yash Kumar', 'AUTOM', '2022-25', 'yash39948@gmail.com', 'https://www.linkedin.com/in/yash-kumar-ab8235265', 'https://instagram.com/stfu_yassh?igshid=NGVhN2U2NjQ0Yg==', 'Photography', '3.webp', '153', 'Club Member', 3, 0, 0, NULL, NULL),
(4, 'Abhinav Koushik', 'CE', '2022-25', 'abhinavkoushikbnb@gmail.com', 'https://in.linkedin.com/in/abhinavkoushik77', 'https://www.instagram.com/abhinavkoushik77/', 'Graphics Designing', '4.webp', '154', 'Club Member', 3, 0, 0, NULL, NULL),
(5, 'Raushan Kumar', 'EE', '2022-25', 'raushan0736@gmail.com', 'https://www.linkedin.com/in/raushan-kumar-3777a8296?utm_source=share&utm_campaign=share_via&utm_cont', 'https://instagram.com/thenameis_rk__?igshid=OGQ5ZDc2ODk2ZA==', 'Content Writing', '5.webp', '155', 'Club Member', 3, 0, 0, NULL, NULL),
(6, 'Jyoti Kumari', 'CSE', '2021-24', 'jyotiwrs712@gmail.com', 'https://www.linkedin.com/in/jyoti-kumari-2509b2264?utm_source=share&utm_campaign=share_via&utm_conte', '', 'Graphics Designing', '6.webp', '156', 'Graphics Designing Team Leader', 2, 1, 0, NULL, NULL),
(7, 'Adityansh Kumar Singh', 'ECE', '2022-25', 'adityanshsingh.adityansh@gmail.com', 'https://in.linkedin.com/in/adityansh', 'https://instagram.com/mr.scientist01?igshid=OGQ5ZDc2ODk2ZA==', 'Technical Team', '7.webp', '157', 'Club Member', 3, 0, 0, NULL, NULL),
(8, 'Priyadarshni Kumari', 'ECE', '2022-25', 'pk1133606@gmail.com', 'https://www.linkedin.com/in/priyadarshni-kumari-b70643274?utm_source=share&utm_campaign=share_via&ut', 'https://instagram.com/nurizz___21?igshid=NGVhN2U2NjQ0Yg==', 'Social Media Management', '8.webp', '158', 'Club Member', 3, 0, 0, NULL, NULL),
(9, 'Vivek Kumar', 'CSE', '2021-24', 'theversion1602@gmail.com', 'https://in.linkedin.com/in/vivek-kumar-083bb7234', 'https://www.instagram.com/vivek_18925a/', 'Photography', '9.webp', '159', 'Photography Team Leader', 2, 1, 1, 'Website Maintenance', NULL),
(10, 'Babi kumari', 'CSE', '2022-25', 'babigujral@gmail.com', 'https://www.linkedin.com/in/babi-kumari-866279289', 'https://instagram.com/babi_kumari_gujral_2023?igshid=MzRlODBiNWFlZA==', 'Graphics Designing', '10.webp', '1510', 'Club Member', 3, 0, 0, NULL, NULL),
(11, 'Mukund Kumar Ray', 'CSE', '2021-24', 'mukundkumar0606@gmail.com', 'https://www.linkedin.com/in/mukund-kumar-bb6a43264?utm_source=share&utm_campaign=share_via&utm_conte', '', 'Video Editing', '11.webp', '1511', 'Video Editing Team Leader', 2, 1, 0, NULL, NULL),
(12, 'Madhu kumari', 'CE', '2022-25', 'madhukumarijha62@gmail.com', 'https://www.linkedin.com/in/madhu-jha-223a84276?trk=contact-info', 'https://www.instagram.com/merasafar19/', 'Content Writing', '12.webp', '1512', 'Club Member', 3, 0, 0, NULL, NULL),
(13, 'Khushi kumari\r\n', 'ECE', '2022-25', 'kumkhushi9386@gmail.com', 'https://www.linkedin.com/in/khushi-sharma-a9b177296?utm_source=share&utm_campaign=share_via&utm_cont', 'https://instagram.com/khushisharma20018?igshid=OGQ5ZDc2ODk2ZA==', 'Video Editing', '13.webp', '1513', 'Club Member', 3, 0, 0, NULL, NULL),
(14, 'Sawli Bharti', 'CSE', '2021-24', 'bhartisawli@gmail.com', '', 'https://instagram.com/bharti_3883?igshid=MzRlODBiNWFlZA==', 'Content Writing', '14.webp', '1514', 'Content Writing Team Leader', 2, 1, 0, NULL, NULL),
(15, 'Payal Rana', 'CSE', '2021-24', '6payalrana@gmail.com', 'https://www.linkedin.com/in/payal-rana-797812296', 'https://instagram.com/payalrana_06?igshid=MzRlODBiNWFlZA==', 'Content Writing', '15.webp', '1515', 'Content Writing Team Leader', 2, 1, 0, NULL, NULL),
(16, 'Priya Kumari', 'ME', '2021-24', 'py336454@gmail.com', '', '', 'Technical Team', '16.webp', '1516', 'Technical Team Leader', 2, 1, 0, NULL, NULL),
(17, 'Rounak Kumar', 'CSE', '2021-24', 'rounaksingh2779@gmail.com', '', 'https://instagram.com/imrounakrajput?utm_source=qr&igshid=MzNlNGNkZWQ4Mg==', 'Technical Team', '17.webp', '1517', 'Club Member', 2, 0, 0, NULL, NULL),
(18, 'Md. Modassir Raza', 'EE', '2021-24', 'modassirraza616@gmail.com', NULL, 'https://www.instagram.com/modassir_raza_', 'Website Maintenance', '18.webp', '1518', 'Website Maintenance Team Leader', 2, 1, 0, NULL, NULL);

--
-- Triggers `members`
--
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_members_delete` AFTER DELETE ON `members` FOR EACH ROW BEGIN
    CALL log_activity_procedure('members', 'DELETE', 'DELETE statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_members_insert` AFTER INSERT ON `members` FOR EACH ROW BEGIN
    CALL log_activity_procedure('members', 'INSERT', 'INSERT statement executed');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_activity_trigger_members_update` AFTER UPDATE ON `members` FOR EACH ROW BEGIN
    CALL log_activity_procedure('members', 'UPDATE', 'UPDATE statement executed');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `notice_id` int(10) NOT NULL,
  `notice_title` varchar(255) NOT NULL,
  `notice_content` text DEFAULT NULL,
  `notice_imgurl` varchar(255) DEFAULT NULL,
  `notice_createdby` varchar(15) NOT NULL DEFAULT '1234567',
  `notice_timestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `query_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`notice_id`, `notice_title`, `notice_content`, `notice_imgurl`, `notice_createdby`, `notice_timestamp`, `query_admin_id`) VALUES
(4, 'Exciting News: IT Club Website is Live!', '<div>We are thrilled to announce that the official website of the IT Club is now live!</div><div><br></div><div>You can visit the website from NGP Patna official site to access a wealth of information and resources related to the IT Club. This website is designed to be a central hub for all IT enthusiasts, where you can find updates on our events, workshops, and projects.</div><div><br></div><div>We encourage all members and IT enthusiasts to explore the website and provide feedback. Your input is essential in helping us improve the website and make it an even more valuable resource for our community.</div><div><br></div><div>We are committed to fostering a vibrant IT community, and this website is a significant step in that direction. We look forward to your active participation and engagement.</div><div><br></div><div>Thank you for your support, and we hope you enjoy exploring the new IT Club website.</div><div><br></div><div>Sincerely,</div><div><b>IT Club Team</b></div>', 'itclublogo.webp', '1234567', '2023-10-22 15:03:48.209116', 1),
(5, 'Celebrating IT Club 1000 Members Milestone ðŸš€ðŸŒðŸ’»', 'ðŸŽ‰ Celebrating a major milestone! ðŸš€ IT CLUB - NGP has hit 1000 members! ðŸ™Œ Thank you to our incredible community for being part of this tech journey. Here\'s to more knowledge sharing and growth together! ðŸŒðŸ’»', 'IMG_20231027_205920_469.jpg', '1234567', '2023-10-27 15:30:58.293594', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminCreds`
--
ALTER TABLE `adminCreds`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `extra`
--
ALTER TABLE `extra`
  ADD PRIMARY KEY (`dataid`);

--
-- Indexes for table `joinrequests`
--
ALTER TABLE `joinrequests`
  ADD PRIMARY KEY (`joiningid`);

--
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`notice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminCreds`
--
ALTER TABLE `adminCreds`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `extra`
--
ALTER TABLE `extra`
  MODIFY `dataid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `joinrequests`
--
ALTER TABLE `joinrequests`
  MODIFY `joiningid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `notice_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
