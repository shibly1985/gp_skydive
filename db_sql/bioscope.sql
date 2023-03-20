-- phpMyAdmin SQL Dump
-- version 4.0.10.19
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 09, 2017 at 06:05 AM
-- Server version: 5.5.54
-- PHP Version: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bioscope`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned_comment_status`
--

CREATE TABLE IF NOT EXISTS `assigned_comment_status` (
  `acsID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `comment_id` varchar(50) DEFAULT NULL,
  `assignTime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`acsID`),
  UNIQUE KEY `comment_id` (`comment_id`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_message`
--

CREATE TABLE IF NOT EXISTS `assigned_message` (
  `amID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `sender_id` varchar(30) NOT NULL,
  `assignTime` varchar(50) NOT NULL,
  PRIMARY KEY (`amID`),
  UNIQUE KEY `comment_id` (`sender_id`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_wall`
--

CREATE TABLE IF NOT EXISTS `assigned_wall` (
  `acsID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `comment_id` varchar(50) DEFAULT NULL,
  `assignTime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`acsID`),
  UNIQUE KEY `comment_id` (`comment_id`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_comments_status`
--

CREATE TABLE IF NOT EXISTS `assignment_comments_status` (
  `ascID` int(8) NOT NULL AUTO_INCREMENT,
  `comment_id` varchar(50) DEFAULT NULL,
  `uID` int(4) DEFAULT NULL,
  `created_time` int(8) DEFAULT '0',
  `assign_time` int(8) DEFAULT '0',
  PRIMARY KEY (`ascID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_comments_status_tracker`
--

CREATE TABLE IF NOT EXISTS `assignment_comments_status_tracker` (
  `cstID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) NOT NULL DEFAULT '0',
  `ugID` int(4) NOT NULL DEFAULT '0',
  `comment_id` varchar(50) NOT NULL DEFAULT '0',
  `transfer_time` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cstID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_comments_wall`
--

CREATE TABLE IF NOT EXISTS `assignment_comments_wall` (
  `acwID` int(8) NOT NULL AUTO_INCREMENT,
  `comment_id` varchar(50) DEFAULT NULL,
  `uID` int(4) DEFAULT NULL,
  `created_time` int(8) DEFAULT '0',
  `assign_time` int(8) DEFAULT '0',
  PRIMARY KEY (`acwID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_comments_wall_tracker`
--

CREATE TABLE IF NOT EXISTS `assignment_comments_wall_tracker` (
  `cwtID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) NOT NULL DEFAULT '0',
  `ugID` int(4) NOT NULL DEFAULT '0',
  `comment_id` varchar(50) NOT NULL DEFAULT '0',
  `transfer_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cwtID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_wall`
--

CREATE TABLE IF NOT EXISTS `assignment_wall` (
  `awID` int(8) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(50) DEFAULT NULL,
  `uID` int(4) DEFAULT NULL,
  `activity` varchar(1) DEFAULT NULL,
  `created_time` int(8) DEFAULT '0',
  `assign_time` int(8) DEFAULT '0',
  PRIMARY KEY (`awID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_wall_tracker`
--

CREATE TABLE IF NOT EXISTS `assignment_wall_tracker` (
  `wtID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) NOT NULL DEFAULT '0',
  `ugID` int(4) NOT NULL DEFAULT '0',
  `post_id` varchar(50) NOT NULL DEFAULT '0',
  `transfer_time` int(8) DEFAULT '0',
  PRIMARY KEY (`wtID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attachment_file`
--

CREATE TABLE IF NOT EXISTS `attachment_file` (
  `afID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) DEFAULT NULL,
  `afFile` varchar(30) DEFAULT NULL,
  `afOrder` int(4) DEFAULT NULL,
  `afType` varchar(10) DEFAULT NULL,
  `createdBy` int(4) DEFAULT NULL,
  `createdOn` int(8) DEFAULT NULL,
  PRIMARY KEY (`afID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `baned_sender`
--

CREATE TABLE IF NOT EXISTS `baned_sender` (
  `sender_id` varchar(50) NOT NULL,
  `type` varchar(1) NOT NULL,
  `type_id` varchar(50) NOT NULL,
  `uID` int(4) NOT NULL,
  `banTime` int(8) NOT NULL,
  PRIMARY KEY (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_send_comment_status`
--

CREATE TABLE IF NOT EXISTS `bulk_send_comment_status` (
  `comment_id` varchar(50) NOT NULL,
  `uID` int(4) NOT NULL,
  KEY `comment_id` (`comment_id`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status`
--

CREATE TABLE IF NOT EXISTS `comments_status` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) DEFAULT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL COMMENT 'When a child reply then here put actual target id',
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`),
  KEY `target_c_id` (`target_c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status_delete`
--

CREATE TABLE IF NOT EXISTS `comments_status_delete` (
  `comment_id` varchar(50) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created_time` int(8) NOT NULL,
  `remove_time` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status_hide`
--

CREATE TABLE IF NOT EXISTS `comments_status_hide` (
  `comment_id` varchar(50) NOT NULL,
  `uID` varchar(50) NOT NULL,
  `hideTime` varchar(50) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status_hour_report_1`
--

CREATE TABLE IF NOT EXISTS `comments_status_hour_report_1` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) DEFAULT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL COMMENT 'When a child reply then here put actual target id',
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status_hour_report_2`
--

CREATE TABLE IF NOT EXISTS `comments_status_hour_report_2` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) DEFAULT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL COMMENT 'When a child reply then here put actual target id',
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_status_like`
--

CREATE TABLE IF NOT EXISTS `comments_status_like` (
  `comment_id` varchar(50) NOT NULL,
  `likeTime` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall`
--

CREATE TABLE IF NOT EXISTS `comments_wall` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `post_sender_id` varchar(50) NOT NULL,
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall_delete`
--

CREATE TABLE IF NOT EXISTS `comments_wall_delete` (
  `comment_id` varchar(50) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created_time` int(8) NOT NULL,
  `remove_time` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall_hide`
--

CREATE TABLE IF NOT EXISTS `comments_wall_hide` (
  `post_id` varchar(50) NOT NULL,
  `uID` varchar(50) NOT NULL,
  `hideTime` varchar(50) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall_like`
--

CREATE TABLE IF NOT EXISTS `comments_wall_like` (
  `comment_id` varchar(50) NOT NULL,
  `likeTime` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall_report_1`
--

CREATE TABLE IF NOT EXISTS `comments_wall_report_1` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `post_sender_id` varchar(50) NOT NULL,
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `photo` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comments_wall_report_2`
--

CREATE TABLE IF NOT EXISTS `comments_wall_report_2` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `post_sender_id` varchar(50) NOT NULL,
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `photo` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `comment_hourly_report`
--

CREATE TABLE IF NOT EXISTS `comment_hourly_report` (
  `reportTime` int(8) NOT NULL,
  `cAdminPost` int(4) DEFAULT '0',
  `cUserComment` int(4) DEFAULT '0',
  `cAdminComment` int(4) DEFAULT '0',
  `cAdminDone` int(4) DEFAULT '0',
  `cAdminQueue` int(4) DEFAULT '0',
  `cAdminRemove` int(4) DEFAULT '0',
  `wUserPost` int(4) DEFAULT '0',
  `wUserComment` int(4) DEFAULT '0',
  `wAdminComment` int(4) DEFAULT '0',
  `wAdminDone` int(4) DEFAULT '0',
  `wAdminRemove` int(4) DEFAULT '0',
  `wAdminQueue` int(4) DEFAULT '0',
  PRIMARY KEY (`reportTime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cor_module`
--

CREATE TABLE IF NOT EXISTS `cor_module` (
  `cmId` int(4) NOT NULL AUTO_INCREMENT,
  `cmParent` int(4) DEFAULT '0',
  `cmTitle` varchar(30) DEFAULT NULL,
  `cmSlug` varchar(30) DEFAULT NULL,
  `cmFolder` varchar(30) DEFAULT NULL,
  `cmPageName` varchar(50) DEFAULT NULL,
  `cmOrder` int(4) DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`cmId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=201 ;

--
-- Dumping data for table `cor_module`
--

INSERT INTO `cor_module` (`cmId`, `cmParent`, `cmTitle`, `cmSlug`, `cmFolder`, `cmPageName`, `cmOrder`, `isActive`) VALUES
(138, 0, 'Module', 'module', 'system', 'module.php', NULL, 1),
(139, 0, 'Settings', 'settings', 'system', 'settings.php', NULL, 1),
(140, 139, 'Wrapup', 'wrapup', 'settings', 'wrapup.php', NULL, 1),
(141, 0, 'Agents', 'agents', 'system', 'agents.php', NULL, 1),
(142, 0, 'Comments', 'comments', 'feeds', 'comments.php', NULL, 1),
(143, 139, 'Message Templates', 'message_templates', 'settings', 'message_templates.php', NULL, 1),
(144, 0, 'Agents Group', 'agents_group', 'system', 'agents_group.php', NULL, 1),
(145, 139, 'Assignment', 'assignment', 'assignment', 'assignment.php', NULL, 1),
(146, 0, 'Bulk Reply', 'bulk-reply', 'assignment', 'bulk-reply.php', NULL, 1),
(147, 0, 'Response Stats', 'report1', 'reports', 'report1.php', NULL, 1),
(148, 0, 'Daily Wrap-up Stats', 'wrap_up_stats', 'reports', 'report2.php', NULL, 0),
(149, 0, 'Privilege', 'privilege', 'settings', 'privilege.php', NULL, 1),
(150, 0, 'New Requests', 'new_requests', 'feeds', 'new_requests.php', NULL, 0),
(151, 0, 'Agent Login Report', 'rep_attendance', 'reports', 'rep_attendance.php', NULL, 1),
(152, 0, 'Messages', 'messages', 'feeds', 'messages.php', NULL, 1),
(153, 0, 'Operators Response Report', 'operators_response', 'reports', 'operators_response.php', NULL, 1),
(154, 147, 'Country Wide Page Insights', 'county_insights', 'reports', 'county_insights.php', NULL, 1),
(155, 0, 'Wall Post', 'wall_post', 'feeds', 'wall_post.php', NULL, 1),
(156, 0, 'Operators Reply Report', 'operators_reply', 'reports', 'operators_reply.php', NULL, 1),
(157, 0, 'This Operators Reply Report', 'this_operators_reply', 'reports', 'this_operators_reply.php', NULL, 1),
(158, 0, 'Five Minutes Activities', 'five_min_activities', 'reports', 'five_min_activities.php', NULL, 0),
(159, 0, 'Sent', 'sent', 'feeds', 'sent.php', NULL, 1),
(160, 139, 'Wall Post Include', 'wall_post_include', 'settings', 'wall_post_include.php', NULL, 1),
(161, 0, 'Sentiment Report', 'sentiment_report', 'reports', 'sentimental_report.php', NULL, 1),
(162, 0, 'Customer Contact Frequency', 'customer_contact_frequency', 'reports', 'customer_contact_frequency.php', NULL, 0),
(163, 0, 'Total activity', 'total_activity', 'reports', 'total_activity.php', NULL, 1),
(164, 0, 'Profile', 'profile', 'include_files', 'profile.php', NULL, 1),
(165, 0, 'Comment Delete Reports', 'comment_delete_reports', 'reports', 'comment_delete_reports.php', NULL, 1),
(166, 0, 'Wrapup Category', 'wrapup_category', 'settings', 'wrapup_category.php', NULL, 1),
(167, 0, 'Outbox', 'outbox', 'feeds', 'outbox.php', NULL, 1),
(168, 0, 'Deleted', 'deleted', 'reports', 'deleted.php', NULL, 1),
(169, 138, 'Permission', 'permission', 'system', 'permission.php', NULL, 1),
(170, 0, 'Agent Hourly Performance', 'agent_hourly_per', 'reports', 'agent_hourly_per.php', NULL, 1),
(171, 0, 'Agent Hourly Performance', 'agent_hourly_per', 'reports', 'agent_hourly_per.php', NULL, 1),
(172, 0, 'Daily Team Performance', 'daily_team_performance', 'reports', 'daily_team_performance.php', NULL, 1),
(173, 0, 'Traffic Analysis', 'traffic_analysis', 'reports', 'traffic_analysis.php', NULL, 1),
(174, 0, 'X Second Report', 'x_sec_report', 'reports', 'x_sec_report.php', NULL, 1),
(175, 0, 'Done Report', 'done_report', 'reports', 'done_report.php', NULL, 1),
(176, 0, 'Transfer Report', 'transfer_report', 'reports', 'transfer_report.php', NULL, 1),
(177, 0, 'Hide Report', 'hide_report', 'reports', 'hide_report.php', NULL, 1),
(178, 0, 'Total Break Report', 'total_break_report', 'reports', 'total_break_report.php', NULL, 1),
(179, 0, 'Break Report', 'break_report', 'reports', 'break_report.php', NULL, 1),
(180, 0, 'Agent Status Report', 'agent_status_report', 'reports', 'agent_status_report.php', NULL, 1),
(181, 0, 'Agent Performance Report', 'availability_report', 'reports', 'availability_report.php', NULL, 1),
(182, 0, 'FCR Report', 'fcr_report', 'reports', 'fcr_report.php', NULL, 1),
(183, 0, 'Queue Status', 'queue_status', 'feeds', 'queue_status.php', NULL, 1),
(184, 0, 'Transferd Queue', 'transferd_queue', 'feeds', 'transferd_queue.php', NULL, 1),
(185, 0, 'QA', 'qa', 'reports', 'qa.php', NULL, 1),
(200, 0, 'Post Wise Sentiment', 'post_wise_sentiment', 'reports', 'post_wise_sentiment.php', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cor_module_permissions`
--

CREATE TABLE IF NOT EXISTS `cor_module_permissions` (
  `cmId` int(4) DEFAULT NULL,
  `ugID` int(4) DEFAULT NULL,
  KEY `cmId` (`cmId`),
  KEY `bID` (`ugID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cor_module_permissions`
--

INSERT INTO `cor_module_permissions` (`cmId`, `ugID`) VALUES
(141, 7),
(144, 7),
(145, 7),
(146, 7),
(143, 7),
(138, 7),
(149, 7),
(140, 7),
(142, 7),
(139, 7),
(148, 7),
(147, 7),
(154, 7),
(152, 7),
(150, 7),
(153, 7),
(151, 7),
(155, 7),
(158, 7),
(156, 7),
(159, 7),
(157, 7),
(160, 7),
(161, 7),
(162, 7),
(163, 7),
(145, 10),
(146, 10),
(165, 10),
(142, 10),
(154, 10),
(162, 10),
(148, 10),
(158, 10),
(143, 10),
(150, 10),
(156, 10),
(153, 10),
(164, 10),
(151, 10),
(147, 10),
(159, 10),
(161, 10),
(139, 10),
(157, 10),
(155, 10),
(160, 10),
(140, 10),
(142, 9),
(143, 9),
(150, 9),
(164, 9),
(159, 9),
(155, 9),
(160, 9),
(140, 9),
(163, 10),
(164, 7),
(166, 9),
(167, 10),
(166, 10),
(165, 7),
(167, 7),
(156, 11),
(164, 11),
(157, 11),
(171, 7),
(170, 7),
(170, 10),
(142, 12),
(152, 12),
(164, 12),
(159, 12),
(157, 12),
(155, 12),
(140, 12),
(166, 12),
(160, 12),
(150, 12),
(143, 12),
(172, 10),
(173, 10),
(173, 7),
(172, 7),
(174, 7),
(166, 7),
(169, 7),
(168, 7),
(168, 10),
(174, 10),
(152, 10),
(175, 7),
(175, 10),
(176, 7),
(176, 10),
(171, 10),
(156, 9),
(177, 7),
(177, 10),
(179, 10),
(178, 10),
(142, 13),
(143, 13),
(152, 13),
(150, 13),
(156, 13),
(153, 13),
(167, 13),
(164, 13),
(159, 13),
(155, 13),
(160, 13),
(140, 13),
(147, 13),
(151, 13),
(157, 13),
(161, 13),
(179, 13),
(146, 13),
(172, 13),
(175, 13),
(177, 13),
(176, 13),
(163, 13),
(180, 7),
(179, 7),
(178, 7),
(141, 10),
(144, 10),
(180, 10),
(181, 7),
(181, 10),
(183, 7),
(183, 9),
(183, 10),
(183, 12),
(182, 7),
(182, 10),
(183, 13),
(168, 13),
(171, 13),
(170, 13),
(184, 10),
(184, 7),
(173, 13),
(182, 13),
(180, 13),
(181, 13),
(145, 13),
(165, 13),
(185, 7),
(167, 12),
(152, 9),
(170, 14),
(171, 14),
(151, 14),
(181, 14),
(180, 14),
(145, 14),
(179, 14),
(146, 14),
(165, 14),
(142, 14),
(154, 14),
(162, 14),
(172, 14),
(148, 14),
(168, 14),
(175, 14),
(182, 14),
(158, 14),
(152, 14),
(143, 14),
(177, 14),
(150, 14),
(156, 14),
(153, 14),
(167, 14),
(169, 14),
(149, 14),
(185, 14),
(183, 14),
(159, 14),
(147, 14),
(161, 14),
(139, 14),
(157, 14),
(163, 14),
(178, 14),
(173, 14),
(176, 14),
(184, 14),
(155, 14),
(160, 14),
(140, 14),
(174, 14),
(164, 14),
(166, 14),
(200, 7),
(200, 10),
(200, 13),
(200, 14),
(178, 12),
(179, 12),
(180, 12),
(149, 10),
(142, 15),
(179, 15),
(180, 15),
(181, 15),
(151, 15),
(143, 15),
(166, 15),
(140, 15),
(160, 15),
(178, 15),
(155, 15),
(157, 15),
(159, 15),
(183, 15),
(164, 15),
(167, 15),
(150, 15),
(152, 15),
(172, 15),
(156, 15),
(153, 15),
(176, 15),
(173, 15),
(163, 15);

-- --------------------------------------------------------

--
-- Table structure for table `cron_log`
--

CREATE TABLE IF NOT EXISTS `cron_log` (
  `clID` int(4) NOT NULL AUTO_INCREMENT,
  `clPage` varchar(50) DEFAULT NULL,
  `clTimeStart` varchar(50) DEFAULT NULL,
  `clTimeEnd` varchar(50) DEFAULT NULL,
  `clRunSecond` int(4) DEFAULT NULL,
  PRIMARY KEY (`clID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_report`
--

CREATE TABLE IF NOT EXISTS `dashboard_report` (
  `drID` int(4) NOT NULL AUTO_INCREMENT,
  `drType` tinyint(4) DEFAULT NULL,
  `drLastUpdate` int(4) DEFAULT NULL,
  `CAP` int(4) DEFAULT NULL,
  `CUA` int(4) DEFAULT NULL,
  `CAA` int(4) DEFAULT NULL,
  `CAHT` varchar(20) DEFAULT NULL,
  `CART` varchar(20) DEFAULT NULL,
  `WP` int(4) DEFAULT NULL,
  `WAA` int(4) DEFAULT NULL,
  `WAHT` varchar(20) DEFAULT NULL,
  `WART` varchar(20) DEFAULT NULL,
  `MAHT` varchar(20) DEFAULT NULL,
  `MWART` varchar(20) DEFAULT NULL,
  `MI` int(4) DEFAULT '0',
  `MO` int(4) DEFAULT '0',
  `MUU` int(4) DEFAULT '0',
  `MUR` int(4) DEFAULT '0',
  PRIMARY KEY (`drID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dashboard_report`
--

INSERT INTO `dashboard_report` (`drID`, `drType`, `drLastUpdate`, `CAP`, `CUA`, `CAA`, `CAHT`, `CART`, `WP`, `WAA`, `WAHT`, `WART`, `MAHT`, `MWART`, `MI`, `MO`, `MUU`, `MUR`) VALUES
(1, 1, 0, 1, 893, 1012, '00:01:16', '00:51:21', 355, 383, '00:01:26', '00:09:27', NULL, NULL, 0, 0, 0, 0),
(2, 2, 0, 33, 17868, 17873, '00:01:06', '00:32:17', 6998, 6988, '00:01:30', '00:06:35', NULL, NULL, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `display_cach`
--

CREATE TABLE IF NOT EXISTS `display_cach` (
  `dcID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) DEFAULT NULL,
  `dcTime` int(4) DEFAULT NULL,
  `caht` varchar(50) DEFAULT '0',
  `cart` varchar(50) DEFAULT '0',
  `waht` varchar(50) DEFAULT '0',
  `wart` varchar(50) DEFAULT '0',
  `dcType` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`dcID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83299 ;

--
-- Dumping data for table `display_cach`
--

INSERT INTO `display_cach` (`dcID`, `uID`, `dcTime`, `caht`, `cart`, `waht`, `wart`, `dcType`) VALUES
(83295, 138, 1510200508, '67', '142', '119', '664', NULL),
(83296, 110, 1510201090, '45', '2230', '100', '208', NULL),
(83297, 156, 1510201250, '278', '2593', '0', '0', NULL),
(83298, 156, 1510201253, '321', '2741', '0', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fav_msg_template`
--

CREATE TABLE IF NOT EXISTS `fav_msg_template` (
  `fmtID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(11) DEFAULT NULL,
  `mtID` int(11) DEFAULT NULL,
  PRIMARY KEY (`fmtID`),
  KEY `uID` (`uID`),
  KEY `mtID` (`mtID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `mid` varchar(50) NOT NULL,
  `seq` varchar(30) NOT NULL,
  `targetSeq` int(11) NOT NULL DEFAULT '0',
  `sender_id` varchar(30) NOT NULL,
  `sendTime` int(8) NOT NULL,
  `text` text NOT NULL,
  `url` text NOT NULL,
  `replyed` tinyint(4) NOT NULL,
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `sendType` tinyint(4) NOT NULL DEFAULT '1',
  `assignTime` int(8) NOT NULL,
  `assignTo` int(4) NOT NULL,
  `replyTime` int(8) NOT NULL,
  `replyBy` int(8) NOT NULL,
  `wuID` int(4) NOT NULL,
  `scentiment` tinyint(4) NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `targetSeq` (`targetSeq`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages_sender`
--

CREATE TABLE IF NOT EXISTS `messages_sender` (
  `sender_id` varchar(30) NOT NULL,
  `senderName` tinytext CHARACTER SET utf8mb4 NOT NULL,
  `senderPictureLink` tinytext NOT NULL,
  `sendTime` int(8) NOT NULL,
  `lastUpdate` int(8) NOT NULL,
  `replyed` tinyint(4) NOT NULL,
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `assignLastCheck` int(8) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `msg_template`
--

CREATE TABLE IF NOT EXISTS `msg_template` (
  `mtID` int(4) NOT NULL AUTO_INCREMENT,
  `mtTitle` text CHARACTER SET utf8,
  `mtText` text CHARACTER SET utf8,
  `mtType` tinyint(4) DEFAULT '0',
  `mtOrder` int(4) DEFAULT '0',
  `createdBy` int(4) DEFAULT NULL,
  `createdOn` int(8) DEFAULT NULL,
  `modifiedBy` int(4) DEFAULT NULL,
  `modifiedOn` int(8) DEFAULT NULL,
  `isActive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`mtID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `no_rep_comments_status`
--

CREATE TABLE IF NOT EXISTS `no_rep_comments_status` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) DEFAULT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL COMMENT 'When a child reply then here put actual target id',
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`),
  KEY `target_c_id` (`target_c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `no_rep_comments_wall`
--

CREATE TABLE IF NOT EXISTS `no_rep_comments_wall` (
  `comment_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `post_sender_id` varchar(50) NOT NULL,
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `target_c_id` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL COMMENT 'Creator FB ID',
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `photo` tinytext,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `no_rep_messages`
--

CREATE TABLE IF NOT EXISTS `no_rep_messages` (
  `mid` varchar(50) NOT NULL,
  `seq` varchar(30) NOT NULL,
  `targetSeq` int(11) NOT NULL DEFAULT '0',
  `sender_id` varchar(30) NOT NULL,
  `sendTime` int(8) NOT NULL,
  `text` text NOT NULL,
  `url` text NOT NULL,
  `replyed` tinyint(4) NOT NULL,
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `sendType` tinyint(4) NOT NULL DEFAULT '1',
  `assignTime` int(8) NOT NULL,
  `assignTo` int(4) NOT NULL,
  `replyTime` int(8) NOT NULL,
  `replyBy` int(8) NOT NULL,
  `wuID` int(4) NOT NULL,
  `scentiment` tinyint(4) NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `targetSeq` (`targetSeq`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `no_rep_messages_sender`
--

CREATE TABLE IF NOT EXISTS `no_rep_messages_sender` (
  `sender_id` varchar(30) NOT NULL,
  `senderName` tinytext CHARACTER SET utf8mb4 NOT NULL,
  `senderPictureLink` tinytext NOT NULL,
  `sendTime` int(8) NOT NULL,
  `lastUpdate` int(8) NOT NULL,
  `replyed` tinyint(4) NOT NULL,
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `assignLastCheck` int(8) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `no_rep_post_wall`
--

CREATE TABLE IF NOT EXISTS `no_rep_post_wall` (
  `post_id` varchar(50) NOT NULL,
  `post_id_2` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL,
  `created_time` int(8) NOT NULL DEFAULT '0',
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `link` tinytext NOT NULL,
  `permalink_url` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `created_time` (`created_time`),
  KEY `replyBy` (`replyBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `perID` int(4) NOT NULL AUTO_INCREMENT,
  `cmId` int(4) DEFAULT NULL,
  `perDesc` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`perID`),
  KEY `mId` (`cmId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=107 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`perID`, `cmId`, `perDesc`) VALUES
(85, 138, 'Status Change'),
(86, 138, 'Module permission'),
(87, 138, 'Module Edit'),
(88, 149, 'Change Permission'),
(89, 139, 'Comments Lifo Fifo'),
(90, 139, 'Comments include'),
(91, 139, 'Session lif Time cha'),
(92, 139, 'FB Page Info Change'),
(93, 164, 'Change Password'),
(94, 164, 'Change Full Name'),
(95, 144, 'Add Group'),
(96, 166, 'Add Wrapup Category'),
(97, 141, 'Add Agent'),
(98, 139, 'Clear Queue'),
(99, 164, 'Ban User'),
(100, 142, 'Delete Comment'),
(101, 155, 'Delete Wall'),
(102, 139, 'Custom servie time'),
(103, 139, 'User Licence'),
(104, 141, 'User Delete'),
(105, 146, 'Bulk Reply Comment'),
(106, 146, 'Bulk Reply Wall');

-- --------------------------------------------------------

--
-- Table structure for table `post_status`
--

CREATE TABLE IF NOT EXISTS `post_status` (
  `post_id` varchar(50) NOT NULL,
  `created_time` int(8) NOT NULL COMMENT 'FB providet time +6 hours',
  `created_time_actual` int(8) NOT NULL,
  `landingTime` int(8) NOT NULL,
  `message` text NOT NULL,
  `link` tinytext NOT NULL,
  `permalink_url` text NOT NULL,
  `post_status` tinytext NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_status_photos`
--

CREATE TABLE IF NOT EXISTS `post_status_photos` (
  `phID` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(50) NOT NULL,
  `link_post_id` varchar(50) NOT NULL,
  `link` tinytext NOT NULL,
  PRIMARY KEY (`phID`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall`
--

CREATE TABLE IF NOT EXISTS `post_wall` (
  `post_id` varchar(50) NOT NULL,
  `post_id_2` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL,
  `created_time` int(8) NOT NULL DEFAULT '0',
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `link` tinytext NOT NULL,
  `permalink_url` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `created_time` (`created_time`),
  KEY `replyBy` (`replyBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall_delete`
--

CREATE TABLE IF NOT EXISTS `post_wall_delete` (
  `post_id` varchar(50) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created_time` int(8) NOT NULL,
  `remove_time` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall_hide`
--

CREATE TABLE IF NOT EXISTS `post_wall_hide` (
  `post_id` varchar(50) NOT NULL,
  `uID` varchar(50) NOT NULL,
  `hideTime` varchar(50) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall_like`
--

CREATE TABLE IF NOT EXISTS `post_wall_like` (
  `post_id` varchar(50) NOT NULL,
  `likeTime` int(8) NOT NULL,
  `uID` int(8) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall_report_1`
--

CREATE TABLE IF NOT EXISTS `post_wall_report_1` (
  `post_id` varchar(50) NOT NULL,
  `post_id_2` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL,
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `link` tinytext NOT NULL,
  `permalink_url` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `post_wall_report_2`
--

CREATE TABLE IF NOT EXISTS `post_wall_report_2` (
  `post_id` varchar(50) NOT NULL,
  `post_id_2` varchar(50) DEFAULT NULL,
  `sender_id` varchar(30) NOT NULL,
  `created_time` int(8) NOT NULL,
  `created_time_actual` int(8) NOT NULL DEFAULT '0',
  `landingTime` int(8) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `link` tinytext NOT NULL,
  `permalink_url` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `isDone` tinyint(4) NOT NULL DEFAULT '0',
  `assignTime` int(8) NOT NULL DEFAULT '0',
  `rcvTime` int(8) NOT NULL DEFAULT '0',
  `assignTo` int(4) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `queue_comments_status`
--

CREATE TABLE IF NOT EXISTS `queue_comments_status` (
  `scqID` int(11) NOT NULL AUTO_INCREMENT,
  `target_c_id` varchar(50) NOT NULL,
  `sendType` varchar(50) NOT NULL,
  `deleteTarget` tinyint(4) NOT NULL DEFAULT '0',
  `errorCode` varchar(20) NOT NULL,
  `errorMessage` text NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `message` text NOT NULL,
  `photo` tinytext NOT NULL,
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `totalTry` tinyint(4) NOT NULL DEFAULT '0',
  `created_time` int(8) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  `sendSuccess` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`scqID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queue_comments_wall`
--

CREATE TABLE IF NOT EXISTS `queue_comments_wall` (
  `scqID` int(11) NOT NULL AUTO_INCREMENT,
  `target_c_id` varchar(50) NOT NULL,
  `post_id` varchar(50) NOT NULL COMMENT 'Main post',
  `parent_id` varchar(50) NOT NULL COMMENT 'If child then parent comment',
  `message` text NOT NULL,
  `photo` tinytext NOT NULL,
  `wuID` int(4) NOT NULL DEFAULT '0',
  `scentiment` tinyint(4) NOT NULL DEFAULT '0',
  `replyed` tinyint(4) NOT NULL DEFAULT '0',
  `replyBy` int(4) NOT NULL DEFAULT '0',
  `created_time` int(8) NOT NULL DEFAULT '0',
  `replyTime` int(8) NOT NULL DEFAULT '0',
  `targetType` varchar(1) NOT NULL,
  `sendType` varchar(50) NOT NULL,
  `deleteTarget` tinyint(4) NOT NULL DEFAULT '0',
  `totalTry` tinyint(4) NOT NULL DEFAULT '0',
  `sendSuccess` tinyint(4) NOT NULL DEFAULT '0',
  `errorCode` varchar(20) NOT NULL,
  `errorMessage` text NOT NULL,
  PRIMARY KEY (`scqID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_cach`
--

CREATE TABLE IF NOT EXISTS `report_cach` (
  `rcID` int(11) NOT NULL AUTO_INCREMENT,
  `rcKey` text,
  `rcValidity` int(8) DEFAULT NULL,
  `rcValue` longtext CHARACTER SET utf8,
  PRIMARY KEY (`rcID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `report_cach`
--

INSERT INTO `report_cach` (`rcID`, `rcKey`, `rcValidity`, `rcValue`) VALUES
(1, 'ahtart_1510164000_1510250399_0', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","mwID":"e","f":"09-11-2017 12:00:00 AM","t":"09-11-2017 11:59:59 PM"}'),
(2, 'ahtartw_1510164000_1510250399_0', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","mwID":"e","f":"09-11-2017 12:00:00 AM","t":"09-11-2017 11:59:59 PM"}'),
(3, 'ahtartm_1510164000_1510250399_', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","f":"09-11-2017 12:00:00 AM","t":"09-11-2017 11:59:59 PM"}'),
(4, 'msgUniSener1510164000_1510250399', 1510211103, '0'),
(5, 'msgUniReply1510164000_1510250399', 1510211103, '0'),
(6, 'ahtart_1509472800_1511978399_0', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","mwID":"e","f":"01-11-2017 12:00:00 AM","t":"29-11-2017 11:59:59 PM"}'),
(7, 'ahtartw_1509472800_1511978399_0', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","mwID":"e","f":"01-11-2017 12:00:00 AM","t":"29-11-2017 11:59:59 PM"}'),
(8, 'ahtartm_1509472800_1511978399_0', 1510211103, '{"ahti":0,"arti":0,"aht":"00:00:00","art":"00:00:00","mwt":"00:00:00","f":"01-11-2017 12:00:00 AM","t":"29-11-2017 11:59:59 PM"}'),
(9, 'msgUniSener1509472800_1511978399', 1510211103, '0'),
(10, 'msgUniReply1509472800_1511978399', 1510211103, '0'),
(11, 'topque', 1510207542, '{"status":0,"m":[],"login":1,"commentQueue":"0","wallPostQue":0,"msgQue":"0"}');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `ugID` int(4) DEFAULT NULL,
  `perID` int(4) DEFAULT NULL,
  KEY `perID` (`perID`),
  KEY `bID` (`ugID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`ugID`, `perID`) VALUES
(7, 85),
(7, 86),
(7, 87),
(7, 88),
(7, 89),
(7, 90),
(7, 91),
(7, 92),
(10, 93),
(10, 94),
(10, 89),
(10, 90),
(10, 91),
(9, 93),
(7, 93),
(7, 94),
(10, 96),
(9, 94),
(7, 95),
(11, 93),
(11, 94),
(11, 88),
(12, 93),
(12, 94),
(7, 96),
(7, 97),
(13, 93),
(13, 94),
(7, 98),
(10, 98),
(7, 99),
(10, 99),
(7, 104),
(7, 102),
(7, 103),
(7, 105),
(10, 105),
(7, 100),
(7, 101),
(14, 88),
(14, 93),
(14, 94),
(14, 99),
(14, 90),
(14, 89),
(14, 91),
(14, 92),
(14, 98),
(14, 96),
(10, 101),
(10, 88),
(15, 93),
(15, 94);

-- --------------------------------------------------------

--
-- Table structure for table `senders`
--

CREATE TABLE IF NOT EXISTS `senders` (
  `id` varchar(50) NOT NULL,
  `name` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE IF NOT EXISTS `site_settings` (
  `ssID` int(4) NOT NULL AUTO_INCREMENT,
  `ssKey` varchar(50) NOT NULL,
  `ssTitle` varchar(50) NOT NULL,
  `ssVal` tinytext NOT NULL,
  PRIMARY KEY (`ssID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`ssID`, `ssKey`, `ssTitle`, `ssVal`) VALUES
(1, 'pagePostAssign', 'Comments Assign type', '2'),
(2, 'commentFlowType', 'Comment Flow Type LIFO / FIFO', '1'),
(3, 'commentsInclude', 'Comments Include Default', '1'),
(4, 'wallPostInclude', 'Wall Post Include Default', '1'),
(5, 'wallPostAssign', 'Wall Post Assign type', '2'),
(6, 'wallPostFlowType', 'Wall Post Flow Type LIFO / FIFO', '1'),
(7, 'sessionLifeTime', 'Inactive after this time (min) 2-59', '20'),
(8, 'messageAssign', 'Message Assign type Auto / Manual', '1'),
(9, 'messageFlowType', 'Message Flow Type LIFO / FIFO', '0'),
(10, 'page_id', 'Facebook Page ID', '135237519825044'),
(11, 'appid', 'Facebook App ID', '1852576798310428'),
(12, 'appsecret', 'FB APP Secret', '05d73513c02d4c5444e6c7eca800996a'),
(13, 'access_token', 'FB APP Access Token', 'EAAaU6KX7gBwBAEHpsx6LvOWDkdotxwdZBBFLq7S4sCiQWuhF7dClVB9szeeGgnFbembSxTQsJm994s3YoPK7gCZCBUBbjg6ZAw2n4pQFtPy7HDniWOe7FG59iiARcPwyHvlo1Pa3a6ATL1a0azOsAOAu2dneUseoSzf1wl0fY1ZAahZApclYs'),
(14, 'page_name', 'FB Page Name', 'Grameenphone'),
(15, 'queueCleareComment', 'Comment Queue Cleare', '1510201185'),
(16, 'queueCleareWall', 'Wall Queue Cleare', '0'),
(17, 'queueCleareMessage', 'Message Queue Cleare', '0'),
(18, 'commentReportTable', 'Comment Report Table', '46'),
(19, 'postWallReportTable', 'Post Wall Report Table', '48'),
(20, 'commentWallReportTable', 'Comment Wall Report Table', '50'),
(21, 'service24hours', '1 means 24 hours else custom', '1'),
(22, 'serviceStartTime', 'If 12 hour then start hour and minute', '1'),
(23, 'serviceEndTime', 'If 12 hour then end hour and minute', '1'),
(24, 'userLicence', 'How much user can active', '90'),
(25, 'messageMaxService', 'How much user can get service from one agent at a ', '1'),
(26, 'messageFlowType', 'Message Flow Type LIFO/ FIFO', '1');

-- --------------------------------------------------------

--
-- Table structure for table `useraccount`
--

CREATE TABLE IF NOT EXISTS `useraccount` (
  `uID` int(4) NOT NULL AUTO_INCREMENT,
  `ugID` int(4) DEFAULT NULL,
  `uLoginName` varchar(20) DEFAULT NULL,
  `uFullName` varchar(50) DEFAULT NULL,
  `uDisplayName` varchar(50) DEFAULT NULL,
  `uCommentFlow` tinyint(4) DEFAULT NULL,
  `uWallpostFlow` tinyint(4) DEFAULT NULL,
  `uCommentFlowDate` int(8) DEFAULT NULL,
  `uWallpostFlowDate` int(8) DEFAULT NULL,
  `uContact` varchar(100) DEFAULT NULL,
  `uEmail` varchar(50) DEFAULT NULL,
  `uImage` varchar(200) DEFAULT NULL,
  `uNote` text,
  `uPassword` varchar(128) DEFAULT NULL,
  `uPassSalt` varchar(32) DEFAULT NULL,
  `isActive` tinyint(4) DEFAULT '1',
  `createdBy` int(4) DEFAULT NULL,
  `createdOn` int(8) DEFAULT NULL,
  `modifiedBy` int(4) DEFAULT NULL,
  `modifiedOn` int(8) DEFAULT NULL,
  `rlID` int(4) DEFAULT NULL,
  PRIMARY KEY (`uID`),
  KEY `ugID` (`ugID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=159 ;

--
-- Dumping data for table `useraccount`
--

INSERT INTO `useraccount` (`uID`, `ugID`, `uLoginName`, `uFullName`, `uDisplayName`, `uCommentFlow`, `uWallpostFlow`, `uCommentFlowDate`, `uWallpostFlowDate`, `uContact`, `uEmail`, `uImage`, `uNote`, `uPassword`, `uPassSalt`, `isActive`, `createdBy`, `createdOn`, `modifiedBy`, `modifiedOn`, `rlID`) VALUES
(44, 7, 'system', 'SYSTEM', NULL, 1, 1, NULL, NULL, '01730912895', 'mabdsalam12@gmail.com', '', NULL, '215557ea4271e0df038449934b90402718072cb43b968313dcd9568f197e10a21085f2a547718cbff5678b1b3c27eafec4d76e1e9ddbd029db6f81e199a96745', 'c4ca4238a0b923820dcc509a6f75849b', 1, 1, 1, 44, 1480400450, 0),
(45, 7, 'admin', 'Admin', NULL, 1, 1, NULL, NULL, '01730912895', 'mabdsalam12@gmail.com', '', NULL, '13232d55f5fdbf30141a3526fd791366d2a2fd160acedf97dc1064f99f5bdbf17fb34c022a0948be8579d1897bcaada7885adf43ad43c2e3567dafec56574b09', 'c4ca4238a0b923820dcc509a6f75849b', 1, 44, 1472106232, 44, 1472106232, 0),
(73, 13, 'user1', 'Anika Faria', 'Anik', 1, 1, 0, 0, '', '', '', NULL, '91b9abb7cf14112eae6d6a51f029877fa34c5aa797ad1773ffe4c1c6f0148fe17d629aa30efdee43ec8ea41de2bcb910b3432ba9bdc68b60a4b6da6ae1a0b136', 'debea0b2ffe06b113c2a979acdcfa54c', 1, 44, 1479634081, 73, 1488080013, NULL),
(74, 10, 'atique', 'Atique', 'Atique', 1, 1, NULL, NULL, '', '', '74_11.jpg', NULL, 'afdc238151579b7f3490ec372201ada0770594f02d5b55fa5c8745dd6afd0e346a8ae75badef5cc5781724f7f154faf74500a78bdce18ecbf18a8107e45ec489', '919bfe8fb51ddbf643bc9124b9946832', 1, 44, 1479635091, 74, 1497427381, NULL),
(75, 10, 'gpspvr2', 'Gpspvr2', 'gpspvr2', 1, 1, NULL, NULL, '', '', '', NULL, 'b6e730296d4380ffa1a71407c45bcb9ca71a89da9445435f124db04428683bb5c9a76382fee228fb4d46e82c1146cae5a7bec2f3e88ea1f8783632741a9b7a6d', '919bfe8fb51ddbf643bc9124b9946832', 1, 44, 1479635350, 75, 1497685689, NULL),
(76, 13, 'user2', 'Iftekhar Tanveer Ahmed Khan', 'Iftekhar', 1, 1, NULL, NULL, '', '', '', NULL, '46054b9a1e47ce1f2394c3a2d53239f7cb536bc2eae425f7192aa155761835c1392dacdd8792ade0ffbd87fad089f518a41795e913562baf13eeb9ae0d10ff4e', 'a7ec42c8f4154f4be1a117a5e21bece9', 1, 44, 1479635391, 74, 1486626096, NULL),
(77, 13, 'user3', 'Iqbal Mahbub', 'Iqbal', 1, 1, 0, 0, '', '', '', NULL, '6cc30c5ae6a71a3bc1d825816443f45bfb972adf37ee0592c8b39cb1531101fb4c24ed9d71da13ab1d26d0b9d2dc3e3d14918d6fba945528bd75b1e8b456880b', '5316be2a347345a16d242aba88f2d3f5', 1, 44, 1479635408, 74, 1489043816, NULL),
(78, 11, 'imran', 'Md. Imranul Hossain', 'Imran', 1, 1, 0, 0, '', '', '', NULL, 'fdbec449af472ba3b9548d5ff23f7a311a8c6f78e84976980387d2d81ac10748643d2d3869641d5c3c21e599b0b6f4c9ec408189c20840ff9441c78299cc3086', '28967b363289e4c2abd613fd34fa0247', 1, 44, 1479635423, 74, 1500378225, NULL),
(79, 9, 'user5', 'Md Jahidur Rahman Chowdhury', NULL, 1, 1, NULL, NULL, '', '', '79_53.jpg', NULL, 'b6b46d409ee910464edae9d49ab9ccdb9fa83fac5640e7de39493cc1951cb58296cbfea8960ab2ff47a04d79132a21c0b3135c3b1619cdd1cea5ab2e337bc223', '8b903d58d8cf4f8ab05b4c74ee228f9c', 0, 44, 1479635441, 79, 1481699625, NULL),
(80, 9, 'user6', 'Md. Insanul Haque Siddique', 'Waqar', 1, 1, NULL, NULL, '', '', '80_37.jpg', NULL, 'e2798aff986e91f4e95715788c09931ae4fa6b76e326743cc7da35483c59676516a323cfa3148f6a513cb642756924c33f85028493aea4ae94bf32f2183cdbc6', '8048c72500ecf4dc36acea071dd6a77c', 0, 44, 1479635456, 74, 1486626039, NULL),
(81, 9, 'user7', 'Sohan', 'Sohan', 0, 1, NULL, NULL, '', '', '81_26.jpg', NULL, '880f545a554f6d49b868611b45dd4337ec352f8a8cdb587ed9f38ad47612ec47b83c1f30f927893275b6ee76af04577f148f0bd5943831fcfe1a17f444a12443', '26d4edf3b680953e4552d87608013e18', 1, 44, 1479635470, 118, 1497458394, NULL),
(82, 9, 'user8', 'Mohona Jahan  Muna', 'Mohona', 1, 1, NULL, NULL, '', '', '82_77.jpg', NULL, '130e0cc1e6a962755054ff00ba95a16be4745ee7899e7c9ce0036ab5100c013c33be384f2d73ad899e9255f2ff94bd30b3bcb8f7ac1caf61849c8aeebfdbf95e', 'db6c720003760778523aac9a99210c7e', 1, 44, 1479635488, 75, 1486889450, NULL),
(83, 9, 'user9', 'Muhammad Arif Hussain', 'Apu', 1, 1, 0, 0, '', '', '', NULL, 'b17bb26f6aabd73121987351146d5161ed5bccd3cacb81cbb59641d4721373700c4dcf03ae404ba4d0597ade0e4c86e9b87d816e9bfe6a92f2520abd5668b282', '8fc59c4116d4b778e6c9d172a8a0d141', 1, 44, 1479635578, 118, 1499680655, NULL),
(84, 9, 'user10', 'Sabrina Rabbi', 'Sabreen', 1, 1, 0, 0, '', '', '', NULL, '50c364ffb733f4a29a7c3057859ea972ec59bebe7b8614882302d58f9594a7242e66289e71fe8696daf208d295d3f7d9f97c29c6b5146d9d5ce34328a0ef1fe1', '69e03f99e30e5b401e7c40ec696184d7', 0, 44, 1479635591, 84, 1497366880, NULL),
(85, 13, 'user11', 'Arif Hossain Suman', 'Suman', 0, 1, 0, 0, '', '', '', NULL, '9d250bfdc1b32e2d8f7a0192fdbd1cc33977e6e199e282937762f7626c11a669ee57c097a1001033335346caaa46b0386a3b37f1d473750277b490794cc139e3', 'fbff9428b074e9c83a6e8a600a0ca50c', 1, 44, 1479635610, 74, 1495606480, NULL),
(86, 9, 'user12', 'Md. Rahat Mridha', 'Rahat', 1, 1, 0, 0, '', '', '', NULL, '19c5ea29154bc1c13d1a5aa1d9d748c8aef6f8347679d5d64b38576e3cbe483b9dd98a25ae3aac632a6d0c5e585f14972693fcdb66d73d7c9553c134817c36b4', 'beee37f7bac44b0fe7c2b42b49ebcfbd', 0, 44, 1479636433, 74, 1487663841, NULL),
(87, 10, 'gpspvr0', 'Mostofa Zaman', 'Zaman', 0, 1, NULL, NULL, '', '', '', NULL, '80e92469579af09bace7be67f9837303f91e6846eb4e25eb4837e194c5965f362e1e0f1ba4c5070ca8fb744df6ebea277160e0fe07c6d0d9ca87617b9b6c0d25', 'd5d529f75c7a4c3fe74f28dc56440c2d', 1, 44, 1479636522, 87, 1497427488, NULL),
(88, 9, 'user14', 'Amin-Ur-Rashid', 'Amin', 1, 1, NULL, NULL, '', '', '', NULL, '70ed49269027b6125b2a2be1bfaf4b2d0286babe7910bdc4aa0f37acb3b397478507450a5bba1aaf1dcffef4e2477f4ca5e298f27d01530d7fada8e638c00e3c', '6d6fe77037935a12b608f42d0cd97873', 1, 44, 1479636566, 118, 1499680666, NULL),
(89, 9, 'user15', 'Sk.Ishtiaque Ahmed', 'Ishtiaque', 1, 1, NULL, NULL, '', '', '', NULL, 'afefbd0031ab3d9f9b2e456edd72c7296acb56ab19cba3aa8a9223a30f3f4ec91d8080a811ba943fe708c99a0aa08ed5c6570d2d13946d6b36dd6c827d835e57', '9ed22eb0a9afd88e4eaae2ec1899956a', 1, 44, 1479636585, 118, 1498551559, NULL),
(90, 9, 'huzzatul', 'Md.Huzzatul Islam', 'Huzzatul', 1, 1, NULL, NULL, '', '', '90_27.jpg', NULL, 'c5c25c9274625ea27a20cf040fe7bc5da18d4a4dc3567df6601d633cb40731dc0e826d46278a9c46b0a6c1bad325e4fd608ca28d27eab32f7a7e51499b7550be', '04117eb4bdef5c9661b946baef6ebc9d', 1, 44, 1479636605, 118, 1499680630, NULL),
(91, 9, 'user17', 'N.A.M Atikujjaman Khan', 'Atik', 0, 1, 0, 0, '', '', '91_71.jpg', NULL, '97955df3d1b8d3d1ae1c51b6d6c75ba1dc0d1c1677d9bf6090267c5a44b2d283fdacedfbdbb0ea60476941e197ed90f38f548cd6cc51e34f289f6349eb10606f', 'd063ac2c51bf8595cd6032dc2b323220', 0, 44, 1479636726, 74, 1484807258, NULL),
(92, 9, 'user18', 'Tasnia Ahmed', 'Tasnia', 0, 1, NULL, NULL, '', '', '', NULL, '395109c3c23782720f7032bfd40fdce840aa604b2f398299c486b9e09c32a6d816ffc6f9e9e62a9acc214a2ce88a1e0b8d661d275bbe66ff7786fa77d384f6b7', 'f3b36135dcb549a816646af79f8c7ef3', 0, 44, 1479636748, 74, 1486366873, NULL),
(93, 1, 'systemsuper', 'SYSTEM', NULL, 1, 1, NULL, NULL, '01730912895', 'mabdsalam12@gmail.com', '', NULL, '215557ea4271e0df038449934b90402718072cb43b968313dcd9568f197e10a21085f2a547718cbff5678b1b3c27eafec4d76e1e9ddbd029db6f81e199a96745', 'c4ca4238a0b923820dcc509a6f75849b', 1, 1, 1, 52, 1474459034, 0),
(94, 11, 'gpqa1', 'gpqa1', NULL, 1, 1, NULL, NULL, '', '', '', NULL, 'ed2996f3891db97cddbb0d26fa85879da79cc670d5f124c020c2be0b121bd3c6d4251f398c42679ec7a132e32fb2529777f5562605b89c946b41fb6533106f1f', 'f73bbf9e73c649fea22ed873e830806a', 1, 44, 1480999236, 94, 1481430213, NULL),
(95, 11, 'gpqa2', 'gpqa2', NULL, 1, 1, NULL, NULL, '', '', '', NULL, 'f898ff034f8190a301e0a7f5054d1f1bcbeb92de78134a41c00c5574c0e26b147e4282af79250b3e6c56a07552526fa9f2c7cfbc97ace10dd5824d81a9f86fd3', '48515f438c9a7aed01f0183c3b3354a7', 1, 44, 1480999248, 95, 1481430584, NULL),
(96, 11, 'gpqa3', 'gpqa3', NULL, 1, 1, NULL, NULL, '', '', '', NULL, 'a058c759815685c9b73ed757816b20b0a0366e9b133d7f5124c6b3e95d2d41e4aaf52a8c2805d45d60226ec6b1d2ff322738847ff72205f7a59a7aa2b8d1b07b', 'ec85a772c5a47486d8ca34cd18a4b087', 1, 44, 1480999260, 96, 1481430674, NULL),
(97, 11, 'gpqa4', 'gpqa4', NULL, 1, 1, NULL, NULL, '', '', '', NULL, '54931', '55112', 3, 44, 1480999271, 44, 1505625860, NULL),
(98, 11, 'gpqa5', 'gpqa5', NULL, 1, 1, NULL, NULL, '', '', '', NULL, '2024', '55010', 3, 44, 1480999292, 44, 1505625868, NULL),
(99, 9, 'user19', 'Tanvin Akter', 'Tanvin', 0, 1, NULL, NULL, '', '', '', NULL, '36c94c4577511da60f80b4cb43a0a1508817847b00aeae98ad176719a1c5ca4b75993fe2221630408fbc0191cc00b7f197fdbe91fb070dbf6180ea54736f9db1', '71afa4ab9ff71845609f6dc9ce7ab6f3', 0, 44, 1480999316, 74, 1486366903, NULL),
(100, 9, 'user20', 'Md. Salauddin Ahmed', 'Salauddin', 0, 1, NULL, NULL, '', '', '', NULL, 'd9cc92961ca1175ed2f6eaa8bdddfe6aba9522b6c8deacc0936a99798cae83eab327922874837c70eb5daf80704d06e29c82eedd2d67a9c90b5861946de0e09f', 'd72abf792628e89c8eaa459fe069d02b', 0, 44, 1480999329, 74, 1486367397, NULL),
(101, 9, 'user21', 'Rahul Saha', 'Rahul', 0, 1, NULL, NULL, '', '', '', NULL, '7b3507455426829bbc53522bfcbdfc04e15f6fc4da0f4262d35a2290fa8bbde52c374912c4ad5df5ac3afeee7614d8d98f47434c7ce71a2b205d396af34b157c', '09359009115d027db4d5546b7a38a3d1', 0, 44, 1480999341, 74, 1486367424, NULL),
(102, 9, 'user22', 'Omor Shahjalal Shish', 'Omor', 0, 1, NULL, NULL, '', '', '', NULL, '8bcf58fabd3ffc831b6b142ecd2222fabe5b1bd100eb644aef7e7310ebea52c9e8502cb52409bfbd95961dfe747216465cf84a6c6fe87e6caaafd27faf3cc5e6', 'f4ed5f1e402676ec9a8160b496da52dc', 0, 44, 1480999684, 74, 1486367452, NULL),
(103, 9, 'user23', 'Md. Mojadded Alam', 'Alam', 0, 1, NULL, NULL, '', '', '', NULL, '220b42cd49333558b770d90719e67a304f311c5bb6c0214910a7c89d81fc3bc22a378b449d5737cbe84f4b1e6877a43c3877562c053a91cadf68ff1d5c245729', '812d598732d9b076b29bf7ec26125478', 0, 44, 1480999700, 74, 1486367508, NULL),
(104, 12, 'ssl1', 'Ummul Khair', 'Ummul Khair', 1, 1, 0, 0, '', '', '', NULL, '13747', '23968', 3, 44, 1481548435, 44, 1505625757, NULL),
(105, 12, 'ssl2', 'Mehedi Hassan Shihab', 'Mehedi Hassan', 1, 0, 0, 0, '', '', '', NULL, '8b23b4b790862779831efc66bfb43fc0658317e5bbc0d7d47b028f64a61811748feaa2421be8362117ec047c67a75f2859224df0f6e85c0d8bd7f2bada0d5e82', '93ac18981789a2f610054aa8f713b26d', 0, 44, 1481548452, 118, 1508588026, NULL),
(106, 12, 'ssl3', 'Md. Rakib Hossain', 'Md. Rakib Hossain', 0, 1, 0, 0, '', '', '106_76.jpg', NULL, 'c10ee6b918cdcfb966c842dd44c771bc49dc134258fd7a9aa08da69e0ed75f427bf2da08a3bf4bbaa66219e9a8871d4c75e5fc02a164c50f4a4b56660925d14a', '4c8790ceb50842e9f2c31e955340cbeb', 1, 44, 1481548468, 118, 1509300892, NULL),
(107, 12, 'ssl4', 'Md. Ashiqur Rahman', 'Md. Ashiqur Rahman', 0, 1, 0, 0, '', '', '107_27.jpg', NULL, '90885', '72095', 3, 44, 1481548489, 44, 1505625775, NULL),
(108, 12, 'ssl5', 'Shahadad Hosan', 'Shahadad Hosan', 0, 1, 0, 0, '', '', '108_48.jpg', NULL, '6ee85270ddff83ff04760404c7aa3529ef27a3cc644c7a06fc7ff90ce390aee8236a59a2eef0d0c39eea01e76ea104e72edd0014472da354d2db72b89ff5c975', '5bc612dd3b3e8c4853fa628441950429', 1, 44, 1481548507, 118, 1509121844, NULL),
(109, 12, 'ssl6', 'Tarikul Islam', '', 1, 1, 0, 0, '', '', '', NULL, '40535', '45512', 3, 44, 1481548523, 44, 1505625785, NULL),
(110, 12, 'sanjida', 'Sanjida Rahman', 'Sanjida Rahman', 0, 1, 0, 0, '', '', '', NULL, '63c29b2fba300bace7ce4692cd53edb11f245a8d816d3323513374191e28142b19fb5c3f70c9e46196922d0d3ea856e1e1157ec22ae7876580a26c07bdfd25f9', '819c3714ec5f348bd0db6dc714f622e3', 1, 44, 1481548880, 143, 1509686605, NULL),
(111, 12, 'ssl8', 'Zia Uddin Gazi', 'Zia Uddin Gazi', 0, 0, 0, 0, '', '', '', NULL, '8abfe779d55415529b5194f3494678b898902ee02553d507c744078265f15594acb5e9c80e2437f2aa29390b29fec1018babb34eeeece55eea6f14a79c6df5ce', 'b2862176a184325592c2e11bdc158f2a', 0, 44, 1481548894, 120, 1506506204, NULL),
(112, 12, 'ssl9', 'Aynun Nahar Jeny', 'Aynun Nahar Jeny', 0, 1, 0, 0, '', '', '', NULL, '2e180f156c06d0b8e261e209377c9aa0be8ae7e3386a8c4d865a381dc8f0ea3eca318ed7ac33bde970de8f3ebeb12230dc18162a46d93fae88a9c1d5dfad8067', 'b448c19424c77ae5bdc0c1c2a737c147', 1, 44, 1481548911, 121, 1509771219, NULL),
(113, 12, 'ssl10', 'Sheikh Mobassher Enam', 'Sheikh Mobassher Enam', 0, 1, 0, 0, '', '', '', NULL, 'd9306ac7a2bb7d33e2d71b62e61a14f4f9be2ddc5ff8712eecab99b44bffa07a20c343a0c1ae55e214105e6d1ef8e0413df3a1ff22bbaa4b56802e6866ed0bd8', '4d2cec29abc4444a4eee5336079823de', 1, 44, 1481548929, 118, 1510019042, NULL),
(114, 12, 'ssl11', 'A.K.M Shafiqul Alam', 'A.K.M Shafiqul Alam', 1, 1, 0, 0, '', '', '', NULL, '84140', '24998', 3, 44, 1481548943, 44, 1505625794, NULL),
(115, 12, 'ssl12', 'Yasin Arafat', 'Yasin Arafat', 1, 1, 0, 0, '', '', '', NULL, '86666', '94031', 3, 44, 1481548959, 44, 1505625803, NULL),
(116, 12, 'ssl13', 'Irfan Ahmad', 'Irfan Ahmad', 0, 1, 0, 0, '', '', '', NULL, '72227', '96517', 3, 44, 1481548973, 44, 1505625830, NULL),
(117, 10, 'sslspvr1', 'D.M. FERDOWS IFTEKHAR', '', 1, 1, 0, 0, '', '', '', NULL, '65604', '55529', 3, 44, 1481549002, 44, 1505625990, NULL),
(118, 10, 'sslspvr2', 'Arshad', 'Arshad', 1, 1, 0, 0, '', '', '118_51.png', NULL, '5d55501f58bbb4afcd35821c6e633c68c6c5b780a16bfbf089a7eaccfb2cae3a6612db11d29b1a5e20356c27522e283a302169adc1f83410bacfe3dcf096d935', 'cda22ad0686d28d2e9e3037766ebc5eb', 1, 44, 1481549018, 118, 1509113695, NULL),
(119, 10, 'sslspvr3', 'Sherin Khan', '', 1, 1, 0, 0, '', '', '', NULL, '66374', '3214', 3, 44, 1481549034, 44, 1505625996, NULL),
(120, 10, 'sslspvr4', 'Md Tumberul', '', 1, 1, 0, 0, '', '', '', NULL, 'fe51df7c6eed85e216141da7974d3e52d9ec200cb679e8629de3295da6ba89c5c7fb8b20e86d8743a18ef0a997eac8e8cc97e0f3284d4182e147d8b1cf46a02a', '29af3778dd4cf16cd0084dd33a503e5d', 0, 44, 1481549047, 120, 1482558597, NULL),
(121, 10, 'sslqa1', 'Benozir Khan', 'Benozir Khan', 1, 1, NULL, NULL, '', '', '', NULL, 'b6b033af8744f4fcbd3601864e299a5c88eca6fbc9624a0975f63cad9032ac9203a392cec302b8d492913ce93881331966e016b374f53d314d15bcb155271b2b', '069025fd33b084edca434874cace5dd4', 1, 44, 1481549073, 121, 1509614641, NULL),
(122, 12, 'sslqa2', 'Mahmudur Rahman', 'Mahmudur Rahman', 1, 1, NULL, NULL, '', '', '', NULL, '12e8efda57d58ffe5400c0eb3e87e4603f1ee04c8c6fd63a89f0361638d4b9d558d312baaf5d356c1ab3f58e2291a5a368ad584865096dd5ba297a67cd92cafd', '75773e016f9f1fd343befcff911248f1', 1, 44, 1481549087, 118, 1506405475, NULL),
(123, 12, 'sslqa3', 'Benozir Khan', 'Benozir Khan', 1, 1, NULL, NULL, '', '', '', NULL, '70f9a4196caf7af85d6c707a2991c575ad98a6e92a56d6de490dfe9c97a1472dcd9bf2998c1732a3f4348c24577c3014501a8c3df6f573658ca443ab49e45004', '4c2b3d2de942df3790ae9d08536b555a', 1, 44, 1481549100, 118, 1506405439, NULL),
(124, 11, 'sslqa4', 'sslqa4', NULL, 1, 1, NULL, NULL, '', '', '', NULL, '8d65014eb80a5ec7aba1e066f205e82dc10f57d58830a26bf3048f2507c59385a9ed79b8a5c0d96498e3095c75a9a900afc3be41d98d9e6b6ed836825167e0a5', 'e953f594251d54d5710be0bbadf7e2c3', 1, 44, 1481549111, 44, 1481549111, NULL),
(125, 12, 'ssl14', 'Saki', 'ssl14', 0, 0, NULL, NULL, '', '', '', NULL, '9e12d474d4852cdffd68a4065808a81bc5ca3bca0dc229d160f081e5419486cd1ee40123fc8f80fb4871374b631c12007efc1725da4d5a66549acbc8a020f887', '7e285032a12ab0243922d7683d4c42d6', 1, 44, 1482738612, 120, 1504689256, NULL),
(126, 12, 'ssl15', 'Sherin', 'Sherin', 1, 0, 0, 0, '', '', '', NULL, '9970', '60760', 3, 44, 1482738651, 44, 1505625811, NULL),
(127, 12, 'mahmudul', 'Mohammad Mahmudul Hassan', 'Mahmudul', 1, 0, NULL, NULL, '', '', '', NULL, '641d4c98db36ba938195592eede3b0a51ce837c2587da20da913a09450879523e7c4411be21ca8b2b20e207ad240d21873c0d01593d26105ccc163fd430e249f', 'da21281705af7a8f545f51a8bd31b3b9', 1, 44, 1486651902, 118, 1510078799, NULL),
(128, 12, 'mahedi', 'Md. Mahedi Hasan Wahid', 'Mahedi', 1, 1, 0, 0, '', '', '', NULL, '57365', '73392', 3, 44, 1486651992, 44, 1505625839, NULL),
(129, 12, 'rahima', 'Rahima Rahman', 'Rahima Rahman', 1, 1, 0, 0, '', '', '', NULL, 'b55a45cb9d29d161bd0ae6826d33955b671566458032daf9a8aac703a84bd71dcc2dba5540e6d79db21175915a2ef884d3abc9665d17cc92ca0883d580dc3991', '13769e53c7d3b5feab6ec87d5905267d', 0, 44, 1487504364, 118, 1504007223, NULL),
(130, 12, 'tanvirjoy', 'Tanvir Rahman Joy', 'Tanvir Rahman Joy', 0, 0, NULL, NULL, '', '', '130_82.jpg', NULL, '64bdf718d2a9616f28031a427a66eeb215a73f1ab88024eee8f3ebf1ef21f8527fe820c0cbf44caa1673f80db14623a0f22d378edfa76f5d1b2e8b6e40401227', 'df28c4f6725eea855e590d35ff8f8e43', 0, 44, 1487846657, 118, 1508608849, NULL),
(131, 10, 'gpspvr3', 'gpspvr3', 'gpspvr3', NULL, NULL, NULL, NULL, '', '', '', NULL, '747a2e6cfce3cb1ec87cb279fbed3c3f9d062f0e7128dd8c37d878f8663a5630dd32a48b0a950c3774956d90f7cace321bfca3e1dbdbba76d6e5d1935b04a00f', 'd2a44a79e41cf37ad27670c2b1ee49ad', 1, 44, 1488180040, 44, 1488180040, NULL),
(132, 12, 'rafsun', 'Md. Rafsun-Al-Rafat', 'Md. Rafsun-Al-Rafat', 1, 1, NULL, NULL, '', '', '132_27.jpg', NULL, '2c608f8076b0df4d71d74482674b75f76cd6e89100c2789a42c0521cc6cde14722449e4a46c026c6b8cd474eb3011bbe51b63c4a1b13bc59b949580c0bcc4313', '3150b3137edb210008fc639ce8de5bf6', 1, 44, 1491986634, 118, 1510057966, NULL),
(133, 10, 'ismail', 'ismail', 'ismail', NULL, NULL, NULL, NULL, '', '', '', NULL, '865a4408176b5b9c8a2fc0aefa55e394ffb63616a19920e1fbeff2d6b3388db0e627fff54a57a0ba56113bab319390e9a65405a53b057c3f542eb1a37403177a', 'd2f5d0e1080213c77b33b2f782fe8ae1', 1, 44, 1492596782, 133, 1492596864, NULL),
(134, 12, 'khorshed', 'Khorshed Alam', 'Khorshed Alam', 1, 1, NULL, NULL, '', '', '', NULL, '76884', '29111', 3, 44, 1492596812, 44, 1505625907, NULL),
(135, 12, 'benozir', 'Benozir Khan', 'Benozir Khan', 1, 0, NULL, NULL, '', '', '', NULL, '247d1da2797667d74292b9cf3567206bbfbb1c25e0e20780a775589eec62a0b1dc2cc211f9cab79fc2b54bbb26029696f5e8c28108a04966cdd9f6be6b50361f', '032f937ca8bf67f2c56af527ae74f9b8', 1, 44, 1493093733, 119, 1494056883, NULL),
(136, 12, 'shamiul', 'Md. Shamiul Bashar', 'Md. Shamiul Bashar', 0, 0, NULL, NULL, '', '', '', NULL, '26442', '40361', 3, 44, 1493538446, 44, 1505625932, NULL),
(137, 12, 'test', 'test', 'test', 0, 1, NULL, NULL, '', '', '', NULL, '8186c1f6e7d9d779672e4357ea5c7b5d', '0c799cb8ec9f839671d8329c8afd84e8', 1, 44, 1494326747, 120, 1494340531, NULL),
(138, 12, 'aditi', 'Aditi Tonny Daring', 'Aditi Tonny Daring', 1, 0, NULL, NULL, '', '', '', NULL, '449b46fe473656c25b909a82af534aab6e2d900b46e18db3f8ead71bc7c36e2b38538ce332623b2dd6fa4f7120b2b351956b2c621c44551ca3c0776a5155f068', '7e891677e7ee5b114fdbc8c7b2be2eb0', 1, 44, 1496115747, 121, 1509770785, NULL),
(139, 12, 'sadia', 'Sadia Nabi Biva', 'Sadia Nabi Biva', 0, 1, NULL, NULL, '', '', '', NULL, 'd38860bd49c0f7f555c003e49f6f29490df4878283b9222528c7d50759b2d0fbb52ba8f5ef551bd659a5d73591be420ed0ed6640df80eac4296917a10cb0dad5', 'ce1cf7b44fa0836fabdabcd8a7f04634', 1, 44, 1496115806, 121, 1509784506, NULL),
(140, 12, 'mohammad', 'Mohammad Ullah', 'Mohammad Ullah', 1, 1, NULL, NULL, '', '', '', NULL, 'f9d115abd3630ef457a7de9c517b0efaeaebb7ca70b9931fbb79b8f6ce8a4252b422d9ae2b5e1facc1073dce88520f68fb76ba2c72b7995a05f8b4ea8bb738eb', '5451dc42f57207948f3f39b0f39b869c', 1, 44, 1497952248, 118, 1509638467, NULL),
(141, 12, 'rakatul', 'Rakatul Islam', 'Rakatul', 0, 0, NULL, NULL, '', '', '', NULL, '48f8efb7aeffa6897e78c8a554eeb0eab9aaa1fe8eb7da735a11f062eef9c435088363f92ea0a5dd79011e1b81e7359935506f132a466de68f3c6430de26f630', '8bccb9ba837848e9b61132894d33813e', 1, 44, 1499590296, 154, 1509891007, NULL),
(142, 12, 'muntakimu', 'Muntakimu Abda', 'Muntakimu Abda', 0, 1, NULL, NULL, '', '', '', NULL, 'cbc42a4c9f170d326d264233b51768c04cce4d3bb2ce052a8c5f40d371751c0bf7b0daf5a1c00d0bb560b9367bf7c06dcce1b2dd32b09421aeec5add65281718', 'd0bb75a3c042fb4b25ac0958a22a9050', 1, 44, 1501063910, 118, 1505920597, NULL),
(143, 10, 'mrahman', 'Mahmudur Rahman', 'Mahmudur Rahman', 0, 1, NULL, NULL, '', '', '143_21.jpg', NULL, '76279dbc9489e00d0908040594741e32feeab4baa93390321e7e65b9717da1382b6b4175f99915c9b9043b012d6ee328b86a74afc987c5ea938139a9a08e30e4', '162f4cc8e491f4d11adc708d9d9a5d52', 1, 44, 1503492681, 143, 1505457258, NULL),
(144, 12, 'aminul', 'Aminul Islam', 'Aminul Islam', 1, 1, NULL, NULL, '', '', '', NULL, '5fe6c0126f3476d72604892b5b4e7918e33a67a87a422e7b8caf7fb9212597f503df70e4e51eaf17a671bd1206f8d6144e680611dbd1a1d2ecc604a7f8ad5edc', '8612d61c39e7918ebf6860232f15d7de', 1, 44, 1504095714, 118, 1508695812, NULL),
(145, 12, 'shahriar', 'Shahriar Tanvir', 'Shahriar Tanvir', 0, 0, NULL, NULL, '', '', '', NULL, '2c265cfee1cad4c9351450a70670e18572e1fa28635fc05fcd84898b3c917baba39c10dcd7e77987003affcc27a0ffe5e1676532aabcbcb39d50f81fd37d2ad7', 'bc29408d5a00f75471ecb8a24b01a31a', 1, 44, 1504095753, 118, 1508692367, NULL),
(146, 12, 'arifin', 'Md. Muhebbul Arifin', 'Md. Muhebbul Arifin', 0, 1, NULL, NULL, '', '', '146_11.jpg', NULL, 'a6d6b0345fce373e75353a8c732eb70a19d2c668d8356a0df3a4115d881f73ff65fd5175c42eac0f88acc553c1670e9164aa97d21f0db381eba79084fa638a7a', '96d700048ca8228d3e126a0c20a9ed8c', 1, 44, 1504095778, 154, 1509803558, NULL),
(147, 12, 'afroza', 'Afroja Akter Ruma', 'Afroja Akter Ruma', 0, 0, NULL, NULL, '', '', '', NULL, 'a63b18faf3196b71a57582037a7b1497cd5577823a53e881a6e1503f9835d2694987e2aa5ba149dd43a895faa1f0a76643c479b3f52faf54c6d1e50f7f6f6d4d', '5cfc4cde36f8963b163321f1da5e8f7e', 0, 44, 1504095805, 147, 1504584002, NULL),
(148, 12, 'nahar', 'Nazmun Nahar', 'Nazmun Nahar', 1, 0, NULL, NULL, '', '', '', NULL, '942b242ea8d293cc3cce050d5c6cb840cb7d286e787674d451056d6efbe237257f4dd8c14fdcf8ae7b03e286b76a354e88e1ad0e37eebe026a66efe5805a2624', 'bd983d7c54661a82bee93db63c150bd0', 1, 44, 1505216056, 121, 1509934087, NULL),
(149, 12, 'ihossain', 'Ismail Hossain', 'Ismail Hossain', 1, 1, NULL, NULL, '', '', '', NULL, 'a9f1d7b704acad0b2564269bcb8961fb0ab89e2cd87ec9a8e8239243aeb0f0e13ae87b2b8cce5a8124790212416550317352c76a09cc7f7a15116700968c6354', '1cc76b1307a5e06cb82e106e636f30d2', 1, 44, 1505317273, 118, 1509638491, NULL),
(150, 12, 'zaiem', 'Abdullah Al Zaiem', 'Abdullah Al Zaiem', 0, 0, NULL, NULL, '', '', '', NULL, '42434', '30603', 3, 44, 1505381855, 44, 1505621482, NULL),
(151, 12, 'martin', 'Joy Martin Sikder', 'Joy Martin Sikder', 0, 0, NULL, NULL, '', '', '151_99.jpg', NULL, 'a5ca3ddd75b9dee7be9de6edb434dfe3d8d70a9e0e53ca8bb3cd08a8374c4075068238c13cdc68f04d5afa71bc127a05003c6c3cbc8512362b632b78eabf2348', '08e4c29f071d29626dfefe3bd4032acb', 1, 44, 1508124067, 151, 1509918491, NULL),
(152, 12, 'fojlul', 'Fojlul Kabir', 'Fojlul Kabir', 1, 0, NULL, NULL, '', '', '', NULL, 'cb50cdd1aca5bb313b431cc23bcd536c3b9c97f51a859a0369e0d4723a60ecc0a46ddff78939cf42e46634a8b30df0953fdf09a7744fe70ba8463c51e8bcfd52', '564c23e9f660b31e727e3b89d64f79cd', 1, 44, 1508124190, 143, 1509883089, NULL),
(153, 12, 'shofiqur', 'Shofiqur Rahaman', 'Shofiqur Rahaman', 0, 1, NULL, NULL, '', '', '', NULL, 'b67d45465d7e30405189d3d0151a9d07c5cd5691f5f549a0bff5d79bef29f91a51b0fffba4eb950fcc49d018b895c90f6178ea980c19d5afccfe0865ededf2cc', 'd8be9aea5ab398c933d90c9d81214e78', 1, 44, 1508646163, 154, 1509890901, NULL),
(154, 10, 'fatema', 'Fatema Jabin', 'Fatema Jabin', 0, 0, NULL, NULL, '', '', '', NULL, '5c08f294b78a9cdd04f931c1d1c93302337e38d32444746d3aa436a77f928c48a7f9bda34e1c5b19858def3122f4e34d436cf0af1b3a29642205b705ca1c70b5', '93b85c7867ea6d3d0360af460ba429c9', 1, 44, 1509612385, 143, 1509682100, NULL),
(155, 12, 'sakline', 'K. M. Sakline Masud', 'Sakline Masud', 1, 0, NULL, NULL, '', '', '155_70.jpg', NULL, '8ad84f8a6df1d067456348240b3e60ee521297bd15954215fc0a898b488b6f186dced7dd4dcdda617ba738e675370ea26f259fe9bf8a838da6ab8c14012b2594', 'a364223192a621a1e8c2601c0ee7f796', 1, 45, 1509852906, 155, 1510151468, NULL),
(156, 12, 'setu', 'Setu Ranjan Saha', 'Setu Ranjan', NULL, NULL, NULL, NULL, '', '', '', NULL, 'd2490a02dbe286ecca19d1e7cd0b60e33688586519dd7138c99e1144cf8a1f03513dee827f7de93f725f3379db6816f9d83e5a08ca87157583170943521cddd8', '44a3c5d3c6041a7e60cc3d88b30df887', 1, 44, 1510111141, 156, 1510113318, NULL),
(157, 12, 'ratul', 'Rokibul Islam Ratul', 'Rokibul Islam', 1, 1, NULL, NULL, '', '', '', NULL, '1ebdb41090828c3992e551d2e77b9eef8c26ea69654eb18c37b6ffda4b8ae8b4f960a8ced5e41b6b9df21922eb9559ecfd968ba9efce52670bd66cf2402af9c9', 'fff8adbc7039d58f2ed205b2b7be69a0', 1, 44, 1510111235, 118, 1510131744, NULL),
(158, 12, 'fahim', 'Fahim Faishal', 'Fahim Faishal', NULL, NULL, NULL, NULL, '', '', '', NULL, '655fb59fcf703c675de681774ae6918141d8a3dfe20b237e0100b981d24710d5b3b34afe12b180b59bb102a3d3c31d23858a863bf07589669ba5085afb73f9a9', '911e7a062f4a6059d7fd7413050bb08e', 1, 44, 1510111272, 44, 1510111272, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `ugID` int(4) NOT NULL AUTO_INCREMENT,
  `ugTitle` varchar(100) NOT NULL,
  `ugDescription` varchar(500) NOT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ugID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=16 ;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`ugID`, `ugTitle`, `ugDescription`, `isActive`) VALUES
(1, 'Developer', 'Not view', 1),
(7, 'Super Admin', 'services for Flexiload related issue', 1),
(9, 'GP Agent', 'GP Agent', 1),
(10, 'GP Supervisor', 'GP Supervisor', 1),
(11, 'GPQA', 'GP QA Group', 1),
(12, 'SSL', 'SSL Agent', 1),
(13, 'Moderator', 'Moderator', 1),
(14, 'SSL Supervisor', 'SSL Supervisor', 1),
(15, 'SC', 'Shift Supervisor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_break_reason`
--

CREATE TABLE IF NOT EXISTS `user_break_reason` (
  `ubrID` int(4) NOT NULL AUTO_INCREMENT,
  `ubrTitle` varchar(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ubrID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_break_reason`
--

INSERT INTO `user_break_reason` (`ubrID`, `ubrTitle`) VALUES
(1, 'Fresh Room'),
(2, 'Meal Break'),
(3, 'Prayer Break'),
(4, 'Training'),
(5, 'PC Maintance');

-- --------------------------------------------------------

--
-- Table structure for table `user_break_time_tracker`
--

CREATE TABLE IF NOT EXISTS `user_break_time_tracker` (
  `btID` int(4) NOT NULL AUTO_INCREMENT,
  `uID` int(4) NOT NULL,
  `ubrID` int(11) NOT NULL,
  `btTime` int(8) NOT NULL,
  `btAppReturnTime` int(8) NOT NULL,
  `btReturnTime` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`btID`),
  KEY `uID` (`uID`),
  KEY `ubrID` (`ubrID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_session`
--

CREATE TABLE IF NOT EXISTS `user_login_session` (
  `ulsID` int(8) NOT NULL AUTO_INCREMENT,
  `uID` int(4) DEFAULT NULL,
  `ulsStartTime` int(8) DEFAULT NULL,
  `ulsValidity` int(8) DEFAULT NULL,
  `ulsLastActivity` int(8) DEFAULT '0',
  `ulsLastService` int(8) DEFAULT '0',
  `ulsService` int(8) DEFAULT '0',
  `ulsEndTime` int(8) DEFAULT NULL,
  `ulsString` varchar(32) DEFAULT NULL,
  `ulsIP` varchar(15) DEFAULT NULL,
  `ulsStatus` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`ulsID`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_login_session`
--

INSERT INTO `user_login_session` (`ulsID`, `uID`, `ulsStartTime`, `ulsValidity`, `ulsLastActivity`, `ulsLastService`, `ulsService`, `ulsEndTime`, `ulsString`, `ulsIP`, `ulsStatus`) VALUES
(1, 44, 1510203356, 1510204556, 0, 0, 0, 1510204562, 'eb7c53450a1cc39e483609d96ea37e38', '59.152.97.26', 0),
(2, 44, 1510207502, 1510208703, 0, 0, 0, NULL, '38808f9082675c81e9974057862bbd42', '103.100.93.69', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_session_activity`
--

CREATE TABLE IF NOT EXISTS `user_login_session_activity` (
  `ulsaID` int(8) NOT NULL AUTO_INCREMENT,
  `ulsID` int(8) DEFAULT NULL,
  `uID` int(4) DEFAULT NULL,
  `service` int(8) DEFAULT '0',
  `active` int(8) DEFAULT '0',
  PRIMARY KEY (`ulsaID`),
  KEY `uID` (`uID`),
  KEY `ulsID` (`ulsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_session_activity_summery`
--

CREATE TABLE IF NOT EXISTS `user_login_session_activity_summery` (
  `ulsaID` int(8) NOT NULL AUTO_INCREMENT,
  `serviceStart` int(8) DEFAULT NULL,
  `uID` int(4) DEFAULT NULL,
  `hit` int(4) DEFAULT NULL,
  `service` int(4) DEFAULT '0',
  `active` int(4) DEFAULT '0',
  PRIMARY KEY (`ulsaID`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wrapup`
--

CREATE TABLE IF NOT EXISTS `wrapup` (
  `wuID` int(4) NOT NULL AUTO_INCREMENT,
  `wuTitle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `createdOn` int(8) NOT NULL,
  `createdBy` int(4) NOT NULL,
  `isActive` int(4) NOT NULL DEFAULT '1',
  `isArchive` int(4) NOT NULL DEFAULT '0',
  `wuForFcr` int(4) NOT NULL DEFAULT '1',
  `wcID` int(10) NOT NULL,
  PRIMARY KEY (`wuID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=121 ;

--
-- Dumping data for table `wrapup`
--

INSERT INTO `wrapup` (`wuID`, `wuTitle`, `createdOn`, `createdBy`, `isActive`, `isArchive`, `wuForFcr`, `wcID`) VALUES
(1, 'Digital Products and services', 1479984332, 93, 0, 0, 1, 1),
(2, 'Flexiplan', 1479984341, 93, 0, 0, 1, 1),
(3, 'Over charging', 1473324026, 52, 0, 0, 1, 0),
(4, 'GP-CP', 1479984350, 93, 0, 0, 1, 1),
(5, 'VAS', 1479984361, 93, 0, 0, 1, 1),
(6, 'Churnback', 1479984372, 93, 0, 0, 1, 1),
(7, 'Coverage/connectivity/Speed/Call Drop', 1479984380, 93, 0, 0, 1, 1),
(8, 'Micro Campaign', 1479984403, 93, 0, 0, 1, 1),
(9, 'Overcharge', 1479984442, 93, 0, 0, 1, 1),
(10, 'Product and Feature', 1479984466, 93, 0, 0, 1, 1),
(11, 'Regular Offer', 1479984479, 93, 0, 0, 1, 1),
(12, 'Startup', 1479984490, 93, 0, 0, 1, 1),
(13, 'Customer Process', 1479984515, 93, 0, 0, 1, 1),
(14, 'Friend Share', 1479984390, 93, 0, 0, 0, 2),
(15, 'Feedback', 1479984504, 93, 0, 0, 0, 2),
(16, 'Irreverent', 1479984575, 93, 0, 0, 0, 3),
(17, 'Customer Process', 1479984524, 93, 0, 0, 1, 4),
(18, 'Digital Products and services', 1479984585, 93, 0, 0, 1, 4),
(19, 'Flexiplan', 1479984595, 93, 0, 0, 1, 4),
(20, 'GP-CP', 1479984685, 93, 0, 0, 1, 4),
(21, 'Products & features', 1479984661, 93, 0, 0, 1, 4),
(22, 'VAS', 1479984648, 93, 0, 0, 1, 4),
(23, 'Churnback', 1479984635, 93, 0, 0, 1, 4),
(24, 'Coverage/connectivity/Speed/Call Drop', 1479984626, 93, 0, 0, 1, 4),
(25, 'Micro Campaign', 1479984614, 93, 0, 0, 1, 4),
(26, 'Regular Offer', 1479984602, 93, 0, 0, 1, 4),
(27, 'Startup', 1479984591, 93, 0, 0, 1, 4),
(28, 'VAS-Activation', 1479984581, 93, 0, 0, 1, 5),
(29, 'VAS-Deactivation', 1479984563, 93, 0, 0, 1, 5),
(30, 'GP-CP Activation', 1479984551, 93, 0, 0, 1, 5),
(31, 'GP-CP Deactivation', 1479984515, 93, 0, 0, 1, 5),
(32, 'Lost phone', 1479984499, 93, 0, 0, 1, 5),
(33, 'Internet Pack Activation', 1479984487, 93, 0, 0, 1, 5),
(34, 'Internet Pack Deactivation', 1479984470, 93, 0, 0, 1, 5),
(35, 'Internet Settings', 1479984457, 93, 0, 0, 1, 5),
(36, 'PIN/PUK', 1479984444, 93, 0, 0, 1, 5),
(37, 'Itemized Bill', 1479984527, 93, 0, 0, 1, 5),
(38, 'Service Appreciation', 1482040323, 75, 1, 0, 0, 2),
(39, 'Back to BOT', 1488693806, 44, 0, 0, 1, 7),
(40, 'Silent', 1488693844, 44, 1, 0, 1, 6),
(41, 'Data', 1488693952, 44, 1, 0, 0, 8),
(42, 'Voice', 1488693961, 44, 1, 0, 0, 8),
(43, 'Customer service delivery Process', 1488693970, 44, 1, 0, 0, 8),
(44, 'Off the Topic', 1488698260, 44, 1, 0, 0, 3),
(45, 'Negative', 1488698770, 44, 1, 0, 0, 2),
(46, 'Positive', 1488698786, 44, 1, 0, 0, 2),
(47, 'Neutral', 1488698796, 44, 1, 0, 0, 2),
(48, 'E-care', 1488698855, 44, 1, 0, 1, 1),
(49, 'GP shop', 1488698868, 44, 1, 0, 1, 1),
(50, 'Balance Transfer', 1488698906, 44, 1, 0, 1, 1),
(51, 'HLR Parameter', 1488698927, 44, 1, 0, 1, 1),
(52, 'My GP', 1488698940, 44, 1, 0, 1, 1),
(53, 'Lost Phone', 1488698957, 44, 1, 0, 1, 1),
(54, 'Overcharge', 1488698970, 44, 1, 0, 1, 1),
(55, 'Itemized Bill', 1488698994, 44, 1, 0, 1, 1),
(56, 'PIN/PUK', 1488699021, 44, 1, 0, 1, 1),
(57, 'FlexiPlan', 1488699048, 44, 1, 0, 1, 1),
(58, 'WowBox', 1488699059, 44, 1, 0, 1, 1),
(59, 'Customer service delivery Process', 1488699104, 44, 1, 0, 1, 1),
(60, 'Basic Product & features', 1488699171, 44, 1, 0, 1, 1),
(61, 'Digital Service/ Products', 1488699190, 44, 1, 0, 1, 1),
(62, 'GP-CP Activation Deactivation', 1488699211, 44, 1, 0, 1, 1),
(63, 'VAS Activation deactivation', 1488699236, 44, 1, 0, 1, 1),
(64, 'Network/ Speed/ 3G coverage', 1488699434, 44, 1, 0, 1, 1),
(65, 'Handset/Modem/Router Offer', 1488699453, 44, 1, 0, 1, 1),
(66, 'Data pack act Deact Config', 1488699476, 44, 1, 0, 1, 1),
(67, 'Micro Campaign Combo', 1488699497, 44, 1, 0, 1, 1),
(68, 'Micro Campaign Data', 1488699537, 44, 1, 0, 1, 1),
(69, 'Micro Campaign Voice', 1488699548, 44, 1, 0, 1, 1),
(70, 'Start Up', 1488699566, 44, 1, 0, 1, 1),
(71, 'Churn Back', 1488699584, 44, 1, 0, 1, 1),
(72, 'STAR Offer', 1488699598, 44, 1, 0, 1, 1),
(73, 'General bundle Offer', 1488699608, 44, 1, 0, 1, 1),
(74, 'General voice Offer', 1488699619, 44, 1, 0, 1, 1),
(75, 'General data Offer', 1488699629, 44, 1, 0, 1, 1),
(76, 'Redirect to MyGp', 1488699651, 44, 1, 0, 1, 5),
(77, 'DND number inclution', 1488699704, 44, 1, 0, 1, 5),
(78, 'International Call Forwarding', 1488699732, 44, 1, 0, 1, 5),
(79, 'Mirror Number', 1488699747, 44, 1, 0, 1, 5),
(80, 'HLR Parameter', 1488699769, 44, 1, 0, 1, 5),
(81, 'Lost Phone/ SIM unbar', 1488699828, 44, 1, 0, 1, 5),
(82, 'Bill related', 1488699845, 44, 1, 0, 1, 5),
(83, 'Balance Transfer', 1488699863, 44, 1, 0, 1, 5),
(84, 'E-care', 1488699881, 44, 1, 0, 1, 5),
(85, 'VAS act/deact', 1488699907, 44, 1, 0, 1, 5),
(86, 'Regular data package act/deact', 1488699924, 44, 1, 0, 1, 5),
(87, 'Gp-Cp deact', 1488699934, 44, 1, 0, 1, 5),
(88, 'BS related info', 1488700034, 44, 1, 0, 1, 4),
(89, 'E-care', 1488700048, 44, 1, 0, 1, 4),
(90, 'GP shop', 1488700063, 44, 1, 0, 1, 4),
(91, 'Balance Transfer', 1488700074, 44, 1, 0, 1, 4),
(92, 'HLR Parameter', 1488700084, 44, 1, 0, 1, 4),
(93, 'My GP', 1488700094, 44, 1, 0, 1, 4),
(94, 'Lost Phone/ SIM unbar', 1488700103, 44, 1, 0, 1, 4),
(95, 'Bill info, Bill delivery, E-bill, Itemized Bill', 1488700112, 44, 1, 0, 1, 4),
(96, 'PIN/PUK', 1488700121, 44, 1, 0, 1, 4),
(97, 'FlexiPlan', 1488700133, 44, 1, 0, 1, 4),
(98, 'WowBox', 1488700143, 44, 1, 0, 1, 4),
(99, 'Customer service delivery Process', 1488700154, 44, 1, 0, 1, 4),
(100, 'Basic Product & features', 1488701534, 44, 1, 0, 1, 4),
(101, 'Digital Service/ Products', 1488701554, 44, 1, 0, 1, 4),
(102, 'GP-CP Activation Deactivation', 1488701580, 44, 1, 0, 1, 4),
(103, 'VAS Activation deactivation', 1488701591, 44, 1, 0, 1, 4),
(104, 'Network/ Speed/ 3G coverage', 1488701604, 44, 1, 0, 1, 4),
(105, 'Handset/Modem/Router Offer', 1488701614, 44, 1, 0, 1, 4),
(106, 'Data pack act/ Deact/ Config', 1488701627, 44, 1, 0, 1, 4),
(107, 'Micro Campaign Combo', 1488701640, 44, 1, 0, 1, 4),
(108, 'Micro Campaign Data', 1488701656, 44, 1, 0, 1, 4),
(109, 'Micro Campaign Voice', 1488701666, 44, 1, 0, 1, 4),
(110, 'Start Up', 1488701698, 44, 1, 0, 1, 4),
(111, 'Churn Back', 1488701721, 44, 1, 0, 1, 4),
(112, 'STAR Offer', 1488701733, 44, 1, 0, 1, 4),
(113, 'General bundle Offer', 1488701743, 44, 1, 0, 1, 4),
(114, 'General voice Offer', 1488701760, 44, 1, 0, 1, 4),
(115, 'General data Offer', 1488701772, 44, 1, 0, 1, 4),
(116, 'Viral', 1499059467, 74, 1, 0, 0, 8),
(117, 'New Topic', 1502192180, 74, 1, 0, 1, 8),
(118, '4G Complaint', 1505557117, 143, 1, 0, 1, 1),
(119, '4G Query', 1505630210, 118, 1, 0, 1, 4),
(120, '4G Feedback', 1505630314, 118, 1, 0, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `wrapup_category`
--

CREATE TABLE IF NOT EXISTS `wrapup_category` (
  `wcID` int(4) NOT NULL AUTO_INCREMENT,
  `wcTitle` varchar(100) NOT NULL DEFAULT '0',
  `createdOn` int(8) NOT NULL DEFAULT '0',
  `createdBy` int(4) NOT NULL DEFAULT '0',
  `isActive` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`wcID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `wrapup_category`
--

INSERT INTO `wrapup_category` (`wcID`, `wcTitle`, `createdOn`, `createdBy`, `isActive`) VALUES
(1, 'Complaint', 1479983991, 93, 1),
(2, 'Engagement', 1479984007, 93, 1),
(3, 'Irrelevant', 1479984028, 93, 1),
(4, 'Query', 1479984062, 93, 1),
(5, 'Service Request', 1479984081, 93, 1),
(6, 'Silent', 1488693726, 44, 1),
(7, 'Resolved Query', 1488693735, 44, 1),
(8, 'Customer Insight', 1488693771, 44, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
