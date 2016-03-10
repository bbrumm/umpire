-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 16, 2016 at 03:29 PM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--
-- Database: `bbrumm_umpire_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `age_group`
--

CREATE TABLE IF NOT EXISTS `age_group` (
  `ID` int(11) NOT NULL,
  `age_group` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `age_group`
--
DELETE FROM age_group;

INSERT INTO `age_group` (`ID`, `age_group`) VALUES
(1, 'Seniors'),
(2, 'Reserves'),
(3, 'Colts'),
(4, 'Under 16'),
(5, 'Under 14'),
(6, 'Youth Girls'),
(7, 'Junior Girls');

-- --------------------------------------------------------


--
-- Table structure for table `division`
--

CREATE TABLE IF NOT EXISTS `division` (
  `ID` int(11) NOT NULL,
  `division_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `division`
--

DELETE FROM division;

INSERT INTO `division` (`ID`, `division_name`) VALUES
(4, 'None'),
(5, 'Grading'),
(6, 'Practice'),
(7, 'Div 1'),
(8, 'Div 2'),
(9, 'Div 3'),
(10, 'Div 4'),
(11, 'Div 5'),
(12, 'Div 6');

-- --------------------------------------------------------


--
-- Table structure for table `age_group_division`
--

CREATE TABLE IF NOT EXISTS `age_group_division` (
  `ID` int(11) NOT NULL,
  `age_group_id` int(11) DEFAULT NULL,
  `division_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `age_group_division`
--

DELETE FROM age_group_division;

INSERT INTO `age_group_division` (`ID`, `age_group_id`, `division_id`) VALUES
(1, 1, 4),
(2, 2, 4),
(3, 3, 5),
(4, 3, 6),
(5, 4, 5),
(6, 5, 5),
(7, 4, 7),
(8, 4, 8),
(9, 4, 9),
(10, 4, 10),
(11, 4, 11),
(12, 5, 7),
(13, 5, 8),
(14, 5, 9),
(15, 5, 10),
(16, 5, 11),
(17, 5, 12),
(18, 6, 4),
(19, 7, 4),
(20, 3, 7),
(21, 3, 8),
(22, 3, 9),
(23, 3, 10);

-- --------------------------------------------------------

--
-- Table structure for table `club`
--

CREATE TABLE IF NOT EXISTS `club` (
  `ID` int(11) NOT NULL,
  `club_name` varchar(100) DEFAULT NULL,
  `abbreviation` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `club`
--

DELETE FROM club;

INSERT INTO `club` (`ID`, `club_name`, `abbreviation`) VALUES
(63, 'Anakie', ''),
(64, 'Anglesea', ''),
(65, 'Bannockburn', ''),
(66, 'Barwon Heads', ''),
(67, 'Bell Park', ''),
(68, 'Bell Post Hill', ''),
(69, 'Belmont Lions', ''),
(70, 'Belmont Lions / Newcomb', ''),
(71, 'Colac', ''),
(72, 'Corio', ''),
(73, 'Drysdale', ''),
(74, 'Drysdale Bennett', ''),
(75, 'Drysdale Byrne', ''),
(76, 'Drysdale Eddy', ''),
(77, 'Drysdale Hall', ''),
(78, 'Drysdale Hector', ''),
(79, 'Drysdale Hoyer', ''),
(80, 'East Geelong', ''),
(81, 'Geelong Amateur', ''),
(82, 'Geelong West', ''),
(83, 'Geelong West St Peters', ''),
(84, 'Grovedale', ''),
(85, 'Gwsp', ''),
(86, 'Gwsp / Bannockburn', ''),
(87, 'Inverleigh', ''),
(88, 'Lara', ''),
(89, 'Leopold', ''),
(90, 'Modewarre', ''),
(91, 'Newcomb', ''),
(92, 'Newcomb Power', ''),
(93, 'Newtown & Chilwell', ''),
(94, 'North Geelong', ''),
(95, 'North Shore', ''),
(96, 'Ocean Grove', ''),
(97, 'Ogcc', ''),
(98, 'Portarlington', ''),
(99, 'Queenscliff', ''),
(100, 'South Barwon', ''),
(101, 'South Barwon / Geelong Amateur', ''),
(102, 'St Albans', ''),
(103, 'St Albans Allthorpe', ''),
(104, 'St Albans Reid', ''),
(105, 'St Joseph''s', ''),
(106, 'St Joseph''s Hill', ''),
(107, 'St Joseph''s Podbury', ''),
(108, 'St Mary''s', ''),
(109, 'Thomson', ''),
(110, 'Tigers Black', ''),
(111, 'Tigers Gold', ''),
(112, 'Torquay', ''),
(113, 'Torquay Bumpstead', ''),
(114, 'Torquay Coles', ''),
(115, 'Torquay Dunstan', ''),
(116, 'Torquay Jones', ''),
(117, 'Torquay Nairn', ''),
(118, 'Torquay Papworth', ''),
(119, 'Torquay Pyers', ''),
(120, 'Torquay Scott', ''),
(121, 'Werribee Centrals', ''),
(122, 'Winchelsea', ''),
(123, 'Winchelsea / Grovedale', '');

-- --------------------------------------------------------


--
-- Table structure for table `season`
--

CREATE TABLE IF NOT EXISTS `season` (
  `ID` int(11) NOT NULL,
  `season_year` int(11) DEFAULT NULL COMMENT 'The year that this season belongs to.'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `season`
--

DELETE FROM season;

INSERT INTO `season` (`ID`, `season_year`) VALUES
(1, 2015);

-- --------------------------------------------------------


--
-- Table structure for table `league`
--

CREATE TABLE IF NOT EXISTS `league` (
  `ID` int(11) NOT NULL,
  `league_name` varchar(100) DEFAULT NULL COMMENT 'The name of a league of competition.',
  `sponsored_league_name` varchar(100) DEFAULT NULL COMMENT 'The full name of the league, including the sponsors name.',
  `short_league_name` varchar(200) DEFAULT NULL COMMENT 'The shorter name of the league, used for reports',
  `age_group_division_id` int(11) DEFAULT NULL COMMENT 'The division for an age group that this league belongs to.'
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `league`
--

DELETE FROM league;

INSERT INTO `league` (`ID`, `league_name`, `sponsored_league_name`, `short_league_name`, `age_group_division_id`) VALUES
(3, 'AFL Barwon Blood Toyota Geelong FNL', 'AFL Barwon Blood Toyota Geelong FNL', 'GFL', 1),
(4, 'AFL Barwon Buckleys Entertainment Centre Geelong FNL', 'AFL Barwon Buckleys Entertainment Centre Geelong FNL', 'GFL', 2),
(5, 'AFL Barwon Dow Bellarine FNL', 'AFL Barwon Dow Bellarine FNL', 'BFL', 1),
(6, 'AFL Barwon Buckleys Entertainment Centre Bellarine FNL', 'AFL Barwon Buckleys Entertainment Centre Bellarine FNL', 'BFL', 2),
(7, 'AFL Barwon', 'AFL Barwon', 'None', 3),
(8, 'AFL Barwon', 'AFL Barwon', 'None', 4),
(9, 'GDFL Smiths Holden Cup', 'GDFL Smiths Holden Cup', 'GDFL', 1),
(10, 'GDFL Buckleys Cup', 'GDFL Buckleys Cup', 'GDFL', 2),
(11, 'AFL Barwon', 'AFL Barwon', 'None', 5),
(12, 'AFL Barwon', 'AFL Barwon', 'None', 6),
(13, 'AFL Barwon Buckley''s Cup', 'AFL Barwon Buckley''s Cup', 'None', 7),
(14, 'AFL Barwon Home Hardware Cup', 'AFL Barwon Home Hardware Cup', 'None', 8),
(15, 'AFL Barwon Geelong Advertiser Cup', 'AFL Barwon Geelong Advertiser Cup', 'None', 9),
(16, 'AFL Barwon Geelong Tech Centre Cup', 'AFL Barwon Geelong Tech Centre Cup', 'None', 10),
(17, 'AFL Barwon Coca Cola Cup', 'AFL Barwon Coca Cola Cup', 'None', 11),
(18, 'AFL Barwon Kempe Cup', 'AFL Barwon Kempe Cup', 'None', 12),
(19, 'AFL Barwon Buckley''s Cup', 'AFL Barwon Buckley''s Cup', 'None', 13),
(20, 'AFL Barwon GMHBA Cup', 'AFL Barwon GMHBA Cup', 'None', 14),
(21, 'AFL Barwon Supatramp Cup', 'AFL Barwon Supatramp Cup', 'None', 15),
(22, 'AFL Barwon Geelong Advertiser Cup', 'AFL Barwon Geelong Advertiser Cup', 'None', 16),
(23, 'AFL Barwon Red Onion Cup', 'AFL Barwon Red Onion Cup', 'None', 17),
(24, 'AFL Barwon', 'AFL Barwon', 'None', 18),
(25, 'AFL Barwon', 'AFL Barwon', 'None', 19),
(26, 'AFL Barwon KRock Cup', 'AFL Barwon KRock Cup', 'None', 20),
(27, 'AFL Barwon Bendigo Bank Cup', 'AFL Barwon Bendigo Bank Cup', 'None', 21),
(28, 'AFL Barwon Corio Bay Health Group Cup', 'AFL Barwon Corio Bay Health Group Cup', 'None', 22),
(29, 'AFL Barwon Corio Bay Health Group Cup', 'AFL Barwon Corio Bay Health Group Cup', 'None', 23);

-- --------------------------------------------------------
-- --------------------------------------------------------


--
-- Table structure for table `competition_lookup`
--

CREATE TABLE IF NOT EXISTS `competition_lookup` (
  `ID` int(11) NOT NULL,
  `competition_name` varchar(100) DEFAULT NULL COMMENT 'The competition name from the imported spreadsheet.',
  `season_id` int(11) DEFAULT NULL,
  `league_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `competition_lookup`
--

DELETE FROM competition_lookup;

INSERT INTO `competition_lookup` (`ID`, `competition_name`, `season_id`, `league_id`) VALUES
(1, 'AFL Barwon - 2015 Blood Toyota Geelong FNL Seniors', 1, 3),
(2, 'AFL Barwon - 2015 Buckleys Entertainment Centre Geelong FNL Reserves', 1, 4),
(3, 'AFL Barwon - 2015 Dow Bellarine FNL Seniors', 1, 5),
(4, 'AFL Barwon - 2015 Buckleys Entertainment Centre Bellarine FNL Reserves', 1, 6),
(5, 'AFL Barwon - 2015 Colts Grading', 1, 7),
(6, 'AFL Barwon - 2015 Colts Practice Matches', 1, 8),
(7, 'GDFL - SMITHS HOLDEN CUP - SENIORS  2015', 1, 9),
(8, 'GDFL - BUCKLEYS CUP- RESERVES 2015', 1, 10),
(9, 'AFL Barwon - 2015 Under 16 Grading', 1, 11),
(10, 'AFL Barwon - 2015 Under 14 Grading', 1, 12),
(11, 'AFL Barwon - 2015 Under 16 Div 1 Buckley''s Cup', 1, 13),
(12, 'AFL Barwon - 2015 Under 16 Div 2 Home Hardware Cup', 1, 14),
(13, 'AFL Barwon - 2015 Under 16 Div 3 Geelong Advertiser Cup', 1, 15),
(14, 'AFL Barwon - 2015 Under 16 Div 4 Geelong Tech Centre Cup', 1, 16),
(15, 'AFL Barwon - 2015 Under 16 Div 5 Coca Cola Cup', 1, 17),
(16, 'AFL Barwon - 2015 Under 14 Div 1 Kempe Cup', 1, 18),
(17, 'AFL Barwon - 2015 Under 14 Div 2 Buckley''s Cup', 1, 19),
(18, 'AFL Barwon - 2015 Under 14 Div 3 GMHBA Cup', 1, 20),
(19, 'AFL Barwon - 2015 Under 14 Div 4 Supatramp Cup', 1, 21),
(20, 'AFL Barwon - 2015 Under 14 Div 5 Geelong Advertiser Cup', 1, 22),
(21, 'AFL Barwon - 2015 Under 14 Div 6 Red Onion Cup', 1, 23),
(22, 'AFL Barwon - 2015 Youth Girls', 1, 24),
(23, 'AFL Barwon - 2015 Junior Girls', 1, 25),
(24, 'AFL Barwon - 2015 Colts Div 1 KRock Cup', 1, 26),
(25, 'AFL Barwon - 2015 Colts Div 2 Bendigo Bank Cup', 1, 27),
(26, 'AFL Barwon - 2015 Colts Div 3 Corio Bay Health Group Cup', 1, 28),
(27, 'AFL Barwon - 2015 Colts Div 4 Corio Bay Health Group Cup', 1, 29);

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE IF NOT EXISTS `dates` (
  `ID` int(11) NOT NULL,
  `saturday_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dates`
--
DELETE FROM dates;


INSERT INTO `dates` (`ID`, `saturday_date`) VALUES
(1, '2015-01-03 00:00:00'),
(2, '2015-01-10 00:00:00'),
(3, '2015-01-17 00:00:00'),
(4, '2015-01-24 00:00:00'),
(5, '2015-01-31 00:00:00'),
(6, '2015-02-07 00:00:00'),
(7, '2015-02-14 00:00:00'),
(8, '2015-02-21 00:00:00'),
(9, '2015-02-28 00:00:00'),
(10, '2015-03-07 00:00:00'),
(11, '2015-03-14 00:00:00'),
(12, '2015-03-21 00:00:00'),
(13, '2015-03-28 00:00:00'),
(14, '2015-04-04 00:00:00'),
(15, '2015-04-11 00:00:00'),
(16, '2015-04-18 00:00:00'),
(17, '2015-04-25 00:00:00'),
(18, '2015-05-02 00:00:00'),
(19, '2015-05-09 00:00:00'),
(20, '2015-05-16 00:00:00'),
(21, '2015-05-23 00:00:00'),
(22, '2015-05-30 00:00:00'),
(23, '2015-06-06 00:00:00'),
(24, '2015-06-13 00:00:00'),
(25, '2015-06-20 00:00:00'),
(26, '2015-06-27 00:00:00'),
(27, '2015-07-04 00:00:00'),
(28, '2015-07-11 00:00:00'),
(29, '2015-07-18 00:00:00'),
(30, '2015-07-25 00:00:00'),
(31, '2015-08-01 00:00:00'),
(32, '2015-08-08 00:00:00'),
(33, '2015-08-15 00:00:00'),
(34, '2015-08-22 00:00:00'),
(35, '2015-08-29 00:00:00'),
(36, '2015-09-05 00:00:00'),
(37, '2015-09-12 00:00:00'),
(38, '2015-09-19 00:00:00'),
(39, '2015-09-26 00:00:00'),
(40, '2015-10-03 00:00:00'),
(41, '2015-10-10 00:00:00'),
(42, '2015-10-17 00:00:00'),
(43, '2015-10-24 00:00:00'),
(44, '2015-10-31 00:00:00'),
(45, '2015-11-07 00:00:00'),
(46, '2015-11-14 00:00:00'),
(47, '2015-11-21 00:00:00'),
(48, '2015-11-28 00:00:00'),
(49, '2015-12-05 00:00:00'),
(50, '2015-12-12 00:00:00'),
(51, '2015-12-19 00:00:00'),
(52, '2015-12-26 00:00:00');

-- --------------------------------------------------------


--
-- Table structure for table `ground`
--

CREATE TABLE IF NOT EXISTS `ground` (
  `ID` int(11) NOT NULL,
  `main_name` varchar(100) DEFAULT NULL COMMENT 'The common name for a ground.',
  `alternative_name` varchar(100) DEFAULT NULL COMMENT 'An alternative name for a ground, as there are multiple names for the same ground.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ground`
--

DELETE FROM ground;

INSERT INTO `ground` (`ID`, `main_name`, `alternative_name`) VALUES
(1, 'Alcoa Oval', 'Alcoa Oval'),
(2, 'Anakie Reserve', 'Anakie Reserve'),
(3, 'Bisinella Oval', 'Bisinella Oval'),
(4, 'Bob Pettit Reserve', 'Bob Pettit Reserve'),
(5, 'Burdoo Reserve', 'Burdoo Reserve'),
(6, 'Central Reserve', 'Central Reserve'),
(7, 'Colac Secondary College', 'Colac Secondary College'),
(8, 'Collendina Reserve', 'Collendina Reserve'),
(9, 'Community Bank Oval', 'Community Bank Oval'),
(10, 'Drew Reserve', 'Drew Reserve'),
(11, 'Drysdale Recreation Reserve', 'Drysdale Recreation Reserve'),
(12, 'Easten Reserve Hopkins St Winchelsea', 'Easten Reserve Hopkins St Winchelsea'),
(13, 'Eastern Reserve', 'Eastern Reserve'),
(14, 'Elderslie Reserve', 'Elderslie Reserve'),
(15, 'Galvin Park Werribee', 'Galvin Park'),
(16, 'Galvin Park Werribee', 'Galvin Park Werribee'),
(17, 'Grinter Reserve', 'Grinter Reserve'),
(18, 'Hamlyn Park', 'Hamlyn Park'),
(19, 'Harold Hurst Reserve', 'Harold Hurst Reserve'),
(20, 'Howard Harmer Oval', 'Howard Harmer Oval'),
(21, 'Inverleigh Reserve', 'Inverleigh Reserve'),
(22, 'Jetts Oval Winter Reserve', 'Jetts Oval (winter Reserve)'),
(23, 'Jetts Oval Winter Reserve', 'Jetts Oval Winter Reserve'),
(24, 'Keith Barclay Oval', 'Keith Barclay Oval'),
(25, 'Keith Barclay Oval Osborne Park', 'Keith Barclay Oval Osborne Park'),
(26, 'Leopold Memorial Park', 'Leopold Memorial Park'),
(27, 'Leopold Memorial Park No. 2', 'Leopold Memorial Park No. 2'),
(28, 'Mcdonald Reserve', 'Mcdonald Reserve'),
(29, 'Mcdonald Reserve No. 2', 'Mcdonald Reserve No. 2'),
(30, 'Mt Moriac Recreational Reserve No. 2', 'Mt Moriac Recreational Reserve No. 2'),
(31, 'Myers Reserve', 'Myers Reserve'),
(32, 'Ocean Grove Memorial Recreation Reserve', 'Ocean Grove Memorial Recreation Reserve'),
(33, 'Portarlington Recreation Reserve', 'Portarlington Recreation Reserve'),
(34, 'Queens Park', 'Queens Park'),
(35, 'Queenscliff Recreation Reserve', 'Queenscliff Recreation Reserve'),
(36, 'Richmond Cresent', 'Richmond Cresent'),
(37, 'Richmond Oval', 'Richmond Oval'),
(38, 'Shell Oval', 'Shell Oval'),
(39, 'Spring Creek Reserve', 'Spring Creek Reserve'),
(40, 'St Albans Reserve', 'St Albans Reserve'),
(41, 'St Marys Oval', 'St Marys Oval'),
(42, 'Thomson Recreation Reserve', 'Thomson Recreation Reserve'),
(43, 'Torquay Sports Precinct 1', 'Torquay Sports Precinct 1'),
(44, 'Victoria Park', 'Victoria Park'),
(45, 'Walker Oval', 'Walker Oval'),
(46, 'West Oval', 'West Oval'),
(47, 'Western Oval', 'Western Oval'),
(48, 'Windsor Park', 'Windsor Park'),
(49, 'The Quay Reserve', 'The Quay Reserve');

-- --------------------------------------------------------

--
-- Table structure for table `imported_files`
--

CREATE TABLE IF NOT EXISTS `imported_files` (
  `imported_file_id` int(11) NOT NULL,
  `filename` varchar(500) NOT NULL,
  `imported_datetime` datetime NOT NULL,
  `imported_user_id` varchar(200) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `imported_files`
--
DELETE FROM imported_files;


-- --------------------------------------------------------


--
-- Table structure for table `match_import`
--

CREATE TABLE IF NOT EXISTS `match_import` (
  `ID` int(11) NOT NULL,
  `season` int(11) DEFAULT NULL,
  `round` int(11) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `competition_name` varchar(200) DEFAULT NULL,
  `ground` varchar(200) DEFAULT NULL,
  `time` varchar(200) DEFAULT NULL,
  `home_team` varchar(200) DEFAULT NULL,
  `away_team` varchar(200) DEFAULT NULL,
  `field_umpire_1` varchar(200) DEFAULT NULL,
  `field_umpire_2` varchar(200) DEFAULT NULL,
  `field_umpire_3` varchar(200) DEFAULT NULL,
  `boundary_umpire_1` varchar(200) DEFAULT NULL,
  `boundary_umpire_2` varchar(200) DEFAULT NULL,
  `boundary_umpire_3` varchar(200) DEFAULT NULL,
  `boundary_umpire_4` varchar(200) DEFAULT NULL,
  `goal_umpire_1` varchar(200) DEFAULT NULL,
  `goal_umpire_2` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32173 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_import`
--

DELETE FROM match_import;


-- --------------------------------------------------------

--
-- Table structure for table `match_played`
--


CREATE TABLE IF NOT EXISTS `match_played` (
  `ID` int(11) NOT NULL,
  `round_id` int(11) DEFAULT NULL,
  `ground_id` int(11) DEFAULT NULL,
  `match_time` datetime DEFAULT NULL,
  `home_team_id` int(11) DEFAULT NULL,
  `away_team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31906 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_played`
--

DELETE FROM match_played;


-- --------------------------------------------------------

--
-- Table structure for table `match_staging`
--

CREATE TABLE IF NOT EXISTS `match_staging` (
  `appointments_id` int(11) NOT NULL,
  `appointments_season` int(11) DEFAULT NULL,
  `appointments_round` int(11) DEFAULT NULL,
  `appointments_date` datetime DEFAULT NULL,
  `appointments_compname` varchar(100) DEFAULT NULL,
  `appointments_ground` varchar(100) DEFAULT NULL,
  `appointments_time` datetime DEFAULT NULL,
  `appointments_hometeam` varchar(100) DEFAULT NULL,
  `appointments_awayteam` varchar(100) DEFAULT NULL,
  `appointments_field1_first` varchar(100) DEFAULT NULL,
  `appointments_field1_last` varchar(100) DEFAULT NULL,
  `appointments_field2_first` varchar(100) DEFAULT NULL,
  `appointments_field2_last` varchar(100) DEFAULT NULL,
  `appointments_field3_first` varchar(100) DEFAULT NULL,
  `appointments_field3_last` varchar(100) DEFAULT NULL,
  `appointments_boundary1_first` varchar(100) DEFAULT NULL,
  `appointments_boundary1_last` varchar(100) DEFAULT NULL,
  `appointments_boundary2_first` varchar(100) DEFAULT NULL,
  `appointments_boundary2_last` varchar(100) DEFAULT NULL,
  `appointments_boundary3_first` varchar(100) DEFAULT NULL,
  `appointments_boundary3_last` varchar(100) DEFAULT NULL,
  `appointments_boundary4_first` varchar(100) DEFAULT NULL,
  `appointments_boundary4_last` varchar(100) DEFAULT NULL,
  `appointments_goal1_first` varchar(100) DEFAULT NULL,
  `appointments_goal1_last` varchar(100) DEFAULT NULL,
  `appointments_goal2_first` varchar(100) DEFAULT NULL,
  `appointments_goal2_last` varchar(100) DEFAULT NULL,
  `season_id` int(11) DEFAULT NULL,
  `round_ID` int(11) DEFAULT NULL,
  `round_date` datetime DEFAULT NULL,
  `round_leagueid` int(11) DEFAULT NULL,
  `league_leaguename` varchar(100) DEFAULT NULL,
  `league_sponsored_league_name` varchar(100) DEFAULT NULL,
  `agd_agegroupid` int(11) DEFAULT NULL,
  `ag_agegroup` varchar(100) DEFAULT NULL,
  `agd_divisionid` int(11) DEFAULT NULL,
  `division_divisionname` varchar(100) DEFAULT NULL,
  `ground_id` int(11) DEFAULT NULL,
  `ground_mainname` varchar(100) DEFAULT NULL,
  `home_team_id` int(11) DEFAULT NULL,
  `away_team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_staging`
--

DELETE FROM match_staging;

-- --------------------------------------------------------

--
-- Table structure for table `mv_report_01`
--

CREATE TABLE IF NOT EXISTS `mv_report_01` (
  `full_name` varchar(200) DEFAULT NULL,
  `club_name` varchar(100) DEFAULT NULL,
  `short_league_name` varchar(100) DEFAULT NULL,
  `age_group` varchar(100) DEFAULT NULL,
  `umpire_type_name` varchar(100) DEFAULT NULL,
  `match_count` int(11) DEFAULT NULL,
  `GDFL|Anakie` int(11) DEFAULT NULL,
  `GDFL|Bannockburn` int(11) DEFAULT NULL,
  `None|Corio` int(11) DEFAULT NULL,
  `GDFL|East_Geelong` int(11) DEFAULT NULL,
  `GDFL|North_Geelong` int(11) DEFAULT NULL,
  `None|Portarlington` int(11) DEFAULT NULL,
  `GDFL|Werribee_Centrals` int(11) DEFAULT NULL,
  `GDFL|Winchelsea` int(11) DEFAULT NULL,
  `GFL|Bell_Park` int(11) DEFAULT NULL,
  `GDFL|Bell_Post_Hill` int(11) DEFAULT NULL,
  `GDFL|Belmont_Lions` int(11) DEFAULT NULL,
  `GFL|Colac` int(11) DEFAULT NULL,
  `BFL|Geelong_Amateur` int(11) DEFAULT NULL,
  `GDFL|Geelong_West` int(11) DEFAULT NULL,
  `GFL|Grovedale` int(11) DEFAULT NULL,
  `GFL|Gwsp` int(11) DEFAULT NULL,
  `GDFL|Inverleigh` int(11) DEFAULT NULL,
  `GFL|Lara` int(11) DEFAULT NULL,
  `GFL|Leopold` int(11) DEFAULT NULL,
  `GFL|Newtown_&_Chilwell` int(11) DEFAULT NULL,
  `GFL|North_Shore` int(11) DEFAULT NULL,
  `GFL|South_Barwon` int(11) DEFAULT NULL,
  `GFL|St_Joseph's` int(11) DEFAULT NULL,
  `GFL|St_Mary's` int(11) DEFAULT NULL,
  `BFL|Torquay` int(11) DEFAULT NULL,
  `None|Barwon_Heads` int(11) DEFAULT NULL,
  `None|Drysdale` int(11) DEFAULT NULL,
  `None|East_Geelong` int(11) DEFAULT NULL,
  `None|Geelong_West_St_Peters` int(11) DEFAULT NULL,
  `None|Grovedale` int(11) DEFAULT NULL,
  `None|Inverleigh` int(11) DEFAULT NULL,
  `None|Leopold` int(11) DEFAULT NULL,
  `None|Newcomb` int(11) DEFAULT NULL,
  `None|Newtown_&_Chilwell` int(11) DEFAULT NULL,
  `None|Ocean_Grove` int(11) DEFAULT NULL,
  `None|South_Barwon` int(11) DEFAULT NULL,
  `None|St_Albans` int(11) DEFAULT NULL,
  `None|St_Joseph's` int(11) DEFAULT NULL,
  `None|St_Mary's` int(11) DEFAULT NULL,
  `None|Torquay` int(11) DEFAULT NULL,
  `BFL|Anglesea` int(11) DEFAULT NULL,
  `BFL|Barwon_Heads` int(11) DEFAULT NULL,
  `GDFL|Corio` int(11) DEFAULT NULL,
  `GDFL|Thomson` int(11) DEFAULT NULL,
  `None|Anglesea` int(11) DEFAULT NULL,
  `None|Bell_Park` int(11) DEFAULT NULL,
  `None|North_Shore` int(11) DEFAULT NULL,
  `None|Belmont_Lions` int(11) DEFAULT NULL,
  `None|Colac` int(11) DEFAULT NULL,
  `None|North_Geelong` int(11) DEFAULT NULL,
  `None|Ogcc` int(11) DEFAULT NULL,
  `None|Torquay_Jones` int(11) DEFAULT NULL,
  `None|Torquay_Papworth` int(11) DEFAULT NULL,
  `None|Winchelsea_/_Grovedale` int(11) DEFAULT NULL,
  `BFL|Modewarre` int(11) DEFAULT NULL,
  `BFL|Newcomb_Power` int(11) DEFAULT NULL,
  `BFL|Queenscliff` int(11) DEFAULT NULL,
  `GFL|St_Albans` int(11) DEFAULT NULL,
  `None|Drysdale_Byrne` int(11) DEFAULT NULL,
  `None|Drysdale_Hall` int(11) DEFAULT NULL,
  `None|Drysdale_Hector` int(11) DEFAULT NULL,
  `None|Lara` int(11) DEFAULT NULL,
  `None|Queenscliff` int(11) DEFAULT NULL,
  `None|St_Albans_Reid` int(11) DEFAULT NULL,
  `None|Torquay_Bumpstead` int(11) DEFAULT NULL,
  `None|Torquay_Pyers` int(11) DEFAULT NULL,
  `None|Modewarre` int(11) DEFAULT NULL,
  `BFL|Ocean_Grove` int(11) DEFAULT NULL,
  `BFL|Drysdale` int(11) DEFAULT NULL,
  `BFL|Portarlington` int(11) DEFAULT NULL,
  `None|St_Joseph's_Podbury` int(11) DEFAULT NULL,
  `None|Geelong_Amateur` int(11) DEFAULT NULL,
  `None|Winchelsea` int(11) DEFAULT NULL,
  `None|Anakie` int(11) DEFAULT NULL,
  `None|Bannockburn` int(11) DEFAULT NULL,
  `None|South_Barwon_/_Geelong_Amateur` int(11) DEFAULT NULL,
  `None|St_Joseph's_Hill` int(11) DEFAULT NULL,
  `None|Torquay_Dunstan` int(11) DEFAULT NULL,
  `None|Werribee_Centrals` int(11) DEFAULT NULL,
  `None|Drysdale_Eddy` int(11) DEFAULT NULL,
  `None|Belmont_Lions_/_Newcomb` int(11) DEFAULT NULL,
  `None|Torquay_Coles` int(11) DEFAULT NULL,
  `None|Gwsp_/_Bannockburn` int(11) DEFAULT NULL,
  `None|St_Albans_Allthorpe` int(11) DEFAULT NULL,
  `None|Drysdale_Bennett` int(11) DEFAULT NULL,
  `None|Torquay_Scott` int(11) DEFAULT NULL,
  `None|Torquay_Nairn` int(11) DEFAULT NULL,
  `None|Tigers_Gold` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_report_01`
--

DELETE FROM mv_report_01;
-- --------------------------------------------------------

--
-- Table structure for table `mv_report_02`
--

CREATE TABLE IF NOT EXISTS `mv_report_02` (
  `full_name` varchar(200) DEFAULT NULL,
  `umpire_type_name` varchar(200) DEFAULT NULL,
  `short_league_name` varchar(200) DEFAULT NULL,
  `age_group` varchar(200) DEFAULT NULL,
  `Seniors|BFL` int(11) DEFAULT NULL,
  `Seniors|GDFL` int(11) DEFAULT NULL,
  `Seniors|GFL` int(11) DEFAULT NULL,
  `Reserves|BFL` int(11) DEFAULT NULL,
  `Reserves|GDFL` int(11) DEFAULT NULL,
  `Reserves|GFL` int(11) DEFAULT NULL,
  `Colts|None` int(11) DEFAULT NULL,
  `Under 16|None` int(11) DEFAULT NULL,
  `Under 14|None` int(11) DEFAULT NULL,
  `Youth Girls|None` int(11) DEFAULT NULL,
  `Junior Girls|None` int(11) DEFAULT NULL,
  `Seniors|2 Umpires` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_report_02`
--
DELETE FROM mv_report_02;

-- --------------------------------------------------------

--
-- Table structure for table `mv_report_03`
--

CREATE TABLE IF NOT EXISTS `mv_report_03` (
  `weekdate` date DEFAULT NULL,
  `No Senior Boundary|BFL` varchar(1000) DEFAULT NULL,
  `No Senior Boundary|GDFL` varchar(1000) DEFAULT NULL,
  `No Senior Boundary|GFL` varchar(1000) DEFAULT NULL,
  `No Senior Boundary|No.` int(11) DEFAULT NULL,
  `No Senior Goal|BFL` varchar(1000) DEFAULT NULL,
  `No Senior Goal|GDFL` varchar(1000) DEFAULT NULL,
  `No Senior Goal|GFL` varchar(1000) DEFAULT NULL,
  `No Senior Goal|No.` int(11) DEFAULT NULL,
  `No Reserve Goal|BFL` varchar(1000) DEFAULT NULL,
  `No Reserve Goal|GDFL` varchar(1000) DEFAULT NULL,
  `No Reserve Goal|GFL` varchar(1000) DEFAULT NULL,
  `No Reserve Goal|No.` int(11) DEFAULT NULL,
  `No Colts Field|Clubs` varchar(1000) DEFAULT NULL,
  `No Colts Field|No.` int(11) DEFAULT NULL,
  `No U16 Field|Clubs` varchar(1000) DEFAULT NULL,
  `No U16 Field|No.` int(11) DEFAULT NULL,
  `No U14 Field|Clubs` varchar(1000) DEFAULT NULL,
  `No U14 Field|No.` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_report_03`
--

DELETE FROM mv_report_03;


-- --------------------------------------------------------

--
-- Table structure for table `mv_report_04`
--

CREATE TABLE IF NOT EXISTS `mv_report_04` (
  `club_name` varchar(200) DEFAULT NULL,
  `Boundary|Seniors|BFL` int(11) DEFAULT NULL,
  `Boundary|Seniors|GDFL` int(11) DEFAULT NULL,
  `Boundary|Seniors|GFL` int(11) DEFAULT NULL,
  `Boundary|Reserves|BFL` int(11) DEFAULT NULL,
  `Boundary|Reserves|GDFL` int(11) DEFAULT NULL,
  `Boundary|Reserves|GFL` int(11) DEFAULT NULL,
  `Boundary|Colts|None` int(11) DEFAULT NULL,
  `Boundary|Under 16|None` int(11) DEFAULT NULL,
  `Boundary|Under 14|None` int(11) DEFAULT NULL,
  `Boundary|Youth Girls|None` int(11) DEFAULT NULL,
  `Boundary|Junior Girls|None` int(11) DEFAULT NULL,
  `Field|Seniors|BFL` int(11) DEFAULT NULL,
  `Field|Seniors|GDFL` int(11) DEFAULT NULL,
  `Field|Seniors|GFL` int(11) DEFAULT NULL,
  `Field|Reserves|BFL` int(11) DEFAULT NULL,
  `Field|Reserves|GDFL` int(11) DEFAULT NULL,
  `Field|Reserves|GFL` int(11) DEFAULT NULL,
  `Field|Colts|None` int(11) DEFAULT NULL,
  `Field|Under 16|None` int(11) DEFAULT NULL,
  `Field|Under 14|None` int(11) DEFAULT NULL,
  `Field|Youth Girls|None` int(11) DEFAULT NULL,
  `Field|Junior Girls|None` int(11) DEFAULT NULL,
  `Goal|Seniors|BFL` int(11) DEFAULT NULL,
  `Goal|Seniors|GDFL` int(11) DEFAULT NULL,
  `Goal|Seniors|GFL` int(11) DEFAULT NULL,
  `Goal|Reserves|BFL` int(11) DEFAULT NULL,
  `Goal|Reserves|GDFL` int(11) DEFAULT NULL,
  `Goal|Reserves|GFL` int(11) DEFAULT NULL,
  `Goal|Colts|None` int(11) DEFAULT NULL,
  `Goal|Under 16|None` int(11) DEFAULT NULL,
  `Goal|Under 14|None` int(11) DEFAULT NULL,
  `Goal|Youth Girls|None` int(11) DEFAULT NULL,
  `Goal|Junior Girls|None` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_report_04`
--

DELETE FROM mv_report_04;



-- --------------------------------------------------------

--
-- Table structure for table `mv_report_05`
--

CREATE TABLE IF NOT EXISTS `mv_report_05` (
  `umpire_type` varchar(200) DEFAULT NULL,
  `age_group` varchar(200) DEFAULT NULL,
  `BFL` int(11) DEFAULT NULL,
  `GDFL` int(11) DEFAULT NULL,
  `GFL` int(11) DEFAULT NULL,
  `None` int(11) DEFAULT NULL,
  `Total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_report_05`
--

DELETE FROM mv_report_05;

-- --------------------------------------------------------

--
-- Table structure for table `mv_summary_staging`
--

CREATE TABLE IF NOT EXISTS `mv_summary_staging` (
  `umpire_type_id` int(11) DEFAULT NULL,
  `umpire_type` varchar(200) DEFAULT NULL,
  `age_group` varchar(200) DEFAULT NULL,
  `short_league_name` varchar(200) DEFAULT NULL,
  `round_date` date DEFAULT NULL,
  `match_id` int(11) DEFAULT NULL,
  `home` varchar(200) DEFAULT NULL,
  `away` varchar(200) DEFAULT NULL,
  `home_club` varchar(200) DEFAULT NULL,
  `away_club` varchar(200) DEFAULT NULL,
  `age_group_ID` int(11) DEFAULT NULL,
  `weekdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mv_summary_staging`
--

DELETE FROM mv_summary_staging;
-- --------------------------------------------------------

--
-- Table structure for table `news`
--


-- --------------------------------------------------------

--
-- Table structure for table `report_column`
--

CREATE TABLE IF NOT EXISTS `report_column` (
  `report_column_id` int(11) NOT NULL,
  `column_name` varchar(200) DEFAULT NULL,
  `column_function` varchar(50) DEFAULT NULL,
  `overall_total` INT(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_column`
--

INSERT INTO `report_column` (`report_column_id`, `column_name`, `column_function`) VALUES
(1, 'BFL|Anglesea', 'SUM'),
(2, 'BFL|Barwon_Heads', 'SUM'),
(3, 'BFL|Drysdale', 'SUM'),
(4, 'BFL|Geelong_Amateur', 'SUM'),
(5, 'BFL|Modewarre', 'SUM'),
(6, 'BFL|Newcomb_Power', 'SUM'),
(7, 'BFL|Ocean_Grove', 'SUM'),
(8, 'BFL|Portarlington', 'SUM'),
(9, 'BFL|Queenscliff', 'SUM'),
(10, 'BFL|Torquay', 'SUM'),
(11, 'GDFL|Anakie', 'SUM'),
(12, 'GDFL|Bannockburn', 'SUM'),
(13, 'GDFL|Bell_Post_Hill', 'SUM'),
(14, 'GDFL|Belmont_Lions', 'SUM'),
(15, 'GDFL|Corio', 'SUM'),
(16, 'GDFL|East_Geelong', 'SUM'),
(17, 'GDFL|Geelong_West', 'SUM'),
(18, 'GDFL|Inverleigh', 'SUM'),
(19, 'GDFL|North_Geelong', 'SUM'),
(20, 'GDFL|Thomson', 'SUM'),
(21, 'GDFL|Werribee_Centrals', 'SUM'),
(22, 'GDFL|Winchelsea', 'SUM'),
(23, 'GFL|Bell_Park', 'SUM'),
(24, 'GFL|Colac', 'SUM'),
(25, 'GFL|Grovedale', 'SUM'),
(26, 'GFL|Gwsp', 'SUM'),
(27, 'GFL|Lara', 'SUM'),
(28, 'GFL|Leopold', 'SUM'),
(29, 'GFL|Newtown_&_Chilwell', 'SUM'),
(30, 'GFL|North_Shore', 'SUM'),
(31, 'GFL|South_Barwon', 'SUM'),
(32, 'GFL|St_Albans', 'SUM'),
(33, 'GFL|St_Joseph''s', 'SUM'),
(34, 'GFL|St_Mary''s', 'SUM'),
(35, 'None|Anakie', 'SUM'),
(36, 'None|Anglesea', 'SUM'),
(37, 'None|Bannockburn', 'SUM'),
(38, 'None|Barwon_Heads', 'SUM'),
(39, 'None|Bell_Park', 'SUM'),
(40, 'None|Belmont_Lions_/_Newcomb', 'SUM'),
(41, 'None|Belmont_Lions', 'SUM'),
(42, 'None|Colac', 'SUM'),
(43, 'None|Corio', 'SUM'),
(44, 'None|Drysdale_Bennett', 'SUM'),
(45, 'None|Drysdale_Byrne', 'SUM'),
(46, 'None|Drysdale_Eddy', 'SUM'),
(47, 'None|Drysdale_Hall', 'SUM'),
(48, 'None|Drysdale_Hector', 'SUM'),
(49, 'None|Drysdale', 'SUM'),
(50, 'None|East_Geelong', 'SUM'),
(51, 'None|Geelong_Amateur', 'SUM'),
(52, 'None|Geelong_West_St_Peters', 'SUM'),
(53, 'None|Grovedale', 'SUM'),
(54, 'None|Gwsp_/_Bannockburn', 'SUM'),
(55, 'None|Inverleigh', 'SUM'),
(56, 'None|Lara', 'SUM'),
(57, 'None|Leopold', 'SUM'),
(58, 'None|Modewarre', 'SUM'),
(59, 'None|Newcomb', 'SUM'),
(60, 'None|Newtown_&_Chilwell', 'SUM'),
(61, 'None|North_Geelong', 'SUM'),
(62, 'None|North_Shore', 'SUM'),
(63, 'None|Ocean_Grove', 'SUM'),
(64, 'None|Ogcc', 'SUM'),
(65, 'None|Portarlington', 'SUM'),
(66, 'None|Queenscliff', 'SUM'),
(67, 'None|South_Barwon_/_Geelong_Amateur', 'SUM'),
(68, 'None|South_Barwon', 'SUM'),
(69, 'None|St_Albans_Allthorpe', 'SUM'),
(70, 'None|St_Albans_Reid', 'SUM'),
(71, 'None|St_Albans', 'SUM'),
(72, 'None|St_Joseph''s_Hill', 'SUM'),
(73, 'None|St_Joseph''s_Podbury', 'SUM'),
(74, 'None|St_Joseph''s', 'SUM'),
(75, 'None|St_Mary''s', 'SUM'),
(76, 'None|Tigers_Gold', 'SUM'),
(77, 'None|Torquay_Bumpstead', 'SUM'),
(78, 'None|Torquay_Coles', 'SUM'),
(79, 'None|Torquay_Dunstan', 'SUM'),
(80, 'None|Torquay_Jones', 'SUM'),
(81, 'None|Torquay_Nairn', 'SUM'),
(82, 'None|Torquay_Papworth', 'SUM'),
(83, 'None|Torquay_Pyers', 'SUM'),
(84, 'None|Torquay_Scott', 'SUM'),
(85, 'None|Torquay', 'SUM'),
(86, 'None|Werribee_Centrals', 'SUM'),
(87, 'None|Winchelsea_/_Grovedale', 'SUM'),
(88, 'None|Winchelsea', 'SUM'),
(89, 'Seniors|BFL', 'SUM'),
(90, 'Seniors|GDFL', 'SUM'),
(91, 'Seniors|GFL', 'SUM'),
(92, 'Seniors|2 Umpires', 'SUM'),
(93, 'Reserves|BFL', 'SUM'),
(94, 'Reserves|GDFL', 'SUM'),
(95, 'Reserves|GFL', 'SUM'),
(96, 'Colts|None', 'SUM'),
(97, 'Under 16|None', 'SUM'),
(98, 'Under 14|None', 'SUM'),
(99, 'Youth Girls|None', 'SUM'),
(100, 'Junior Girls|None', 'SUM'),
(101, 'No Senior Boundary|BFL', NULL),
(102, 'No Senior Boundary|GDFL', NULL),
(103, 'No Senior Boundary|GFL', NULL),
(104, 'No Senior Boundary|No.', NULL),
(105, 'No Senior Goal|BFL', NULL),
(106, 'No Senior Goal|GDFL', NULL),
(107, 'No Senior Goal|GFL', NULL),
(108, 'No Senior Goal|No.', NULL),
(109, 'No Reserve Goal|BFL', NULL),
(110, 'No Reserve Goal|GDFL', NULL),
(111, 'No Reserve Goal|GFL', NULL),
(112, 'No Reserve Goal|No.', NULL),
(113, 'No Colts Field|Clubs', NULL),
(114, 'No Colts Field|No.', NULL),
(115, 'No U16 Field|Clubs', NULL),
(116, 'No U16 Field|No.', NULL),
(117, 'No U14 Field|Clubs', NULL),
(118, 'No U14 Field|No.', NULL),
(119, 'Boundary|Seniors|BFL', NULL),
(120, 'Boundary|Seniors|GDFL', NULL),
(121, 'Boundary|Seniors|GFL', NULL),
(122, 'Boundary|Reserves|BFL', NULL),
(123, 'Boundary|Reserves|GDFL', NULL),
(124, 'Boundary|Reserves|GFL', NULL),
(125, 'Boundary|Colts|None', NULL),
(126, 'Boundary|Under 16|None', NULL),
(127, 'Boundary|Under 14|None', NULL),
(128, 'Boundary|Youth Girls|None', NULL),
(129, 'Boundary|Junior Girls|None', NULL),
(130, 'Field|Seniors|BFL', NULL),
(131, 'Field|Seniors|GDFL', NULL),
(132, 'Field|Seniors|GFL', NULL),
(133, 'Field|Reserves|BFL', NULL),
(134, 'Field|Reserves|GDFL', NULL),
(135, 'Field|Reserves|GFL', NULL),
(136, 'Field|Colts|None', NULL),
(137, 'Field|Under 16|None', NULL),
(138, 'Field|Under 14|None', NULL),
(139, 'Field|Youth Girls|None', NULL),
(140, 'Field|Junior Girls|None', NULL),
(141, 'Goal|Seniors|BFL', NULL),
(142, 'Goal|Seniors|GDFL', NULL),
(143, 'Goal|Seniors|GFL', NULL),
(144, 'Goal|Reserves|BFL', NULL),
(145, 'Goal|Reserves|GDFL', NULL),
(146, 'Goal|Reserves|GFL', NULL),
(147, 'Goal|Colts|None', NULL),
(148, 'Goal|Under 16|None', NULL),
(149, 'Goal|Under 14|None', NULL),
(150, 'Goal|Youth Girls|None', NULL),
(151, 'Goal|Junior Girls|None', NULL),
(152, 'BFL', NULL),
(153, 'GDFL', NULL),
(154, 'GFL', NULL),
(155, 'None', NULL),
(156, 'Total', NULL);


update umpire.report_column
set overall_total = 0
where report_column_id = 92;
-- --------------------------------------------------------

--
-- Table structure for table `report_column_lookup`
--

CREATE TABLE IF NOT EXISTS `report_column_lookup` (
  `report_column_lookup_id` int(11) NOT NULL,
  `filter_name` varchar(40) DEFAULT NULL,
  `filter_value` varchar(100) DEFAULT NULL,
  `report_table_id` int(11) DEFAULT NULL,
  `report_column_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_column_lookup`
--

INSERT INTO `report_column_lookup` (`report_column_lookup_id`, `filter_name`, `filter_value`, `report_table_id`, `report_column_id`) VALUES
(1, 'short_league_name', 'All', 1, 11),
(2, 'short_league_name', 'All', 1, 12),
(3, 'short_league_name', 'All', 1, 43),
(4, 'short_league_name', 'All', 1, 16),
(5, 'short_league_name', 'All', 1, 19),
(6, 'short_league_name', 'All', 1, 65),
(7, 'short_league_name', 'All', 1, 21),
(8, 'short_league_name', 'All', 1, 22),
(9, 'short_league_name', 'All', 1, 23),
(10, 'short_league_name', 'All', 1, 13),
(11, 'short_league_name', 'All', 1, 14),
(12, 'short_league_name', 'All', 1, 24),
(13, 'short_league_name', 'All', 1, 4),
(14, 'short_league_name', 'All', 1, 17),
(15, 'short_league_name', 'All', 1, 25),
(16, 'short_league_name', 'All', 1, 26),
(17, 'short_league_name', 'All', 1, 18),
(18, 'short_league_name', 'All', 1, 27),
(19, 'short_league_name', 'All', 1, 28),
(20, 'short_league_name', 'All', 1, 29),
(21, 'short_league_name', 'All', 1, 30),
(22, 'short_league_name', 'All', 1, 31),
(23, 'short_league_name', 'All', 1, 33),
(24, 'short_league_name', 'All', 1, 34),
(25, 'short_league_name', 'All', 1, 10),
(26, 'short_league_name', 'All', 1, 38),
(27, 'short_league_name', 'All', 1, 49),
(28, 'short_league_name', 'All', 1, 50),
(29, 'short_league_name', 'All', 1, 52),
(30, 'short_league_name', 'All', 1, 53),
(31, 'short_league_name', 'All', 1, 55),
(32, 'short_league_name', 'All', 1, 57),
(33, 'short_league_name', 'All', 1, 59),
(34, 'short_league_name', 'All', 1, 60),
(35, 'short_league_name', 'All', 1, 63),
(36, 'short_league_name', 'All', 1, 68),
(37, 'short_league_name', 'All', 1, 71),
(38, 'short_league_name', 'All', 1, 74),
(39, 'short_league_name', 'All', 1, 75),
(40, 'short_league_name', 'All', 1, 85),
(41, 'short_league_name', 'All', 1, 1),
(42, 'short_league_name', 'All', 1, 2),
(43, 'short_league_name', 'All', 1, 15),
(44, 'short_league_name', 'All', 1, 20),
(45, 'short_league_name', 'All', 1, 36),
(46, 'short_league_name', 'All', 1, 39),
(47, 'short_league_name', 'All', 1, 62),
(48, 'short_league_name', 'All', 1, 41),
(49, 'short_league_name', 'All', 1, 42),
(50, 'short_league_name', 'All', 1, 61),
(51, 'short_league_name', 'All', 1, 64),
(52, 'short_league_name', 'All', 1, 80),
(53, 'short_league_name', 'All', 1, 82),
(54, 'short_league_name', 'All', 1, 87),
(55, 'short_league_name', 'All', 1, 5),
(56, 'short_league_name', 'All', 1, 6),
(57, 'short_league_name', 'All', 1, 9),
(58, 'short_league_name', 'All', 1, 32),
(59, 'short_league_name', 'All', 1, 45),
(60, 'short_league_name', 'All', 1, 47),
(61, 'short_league_name', 'All', 1, 48),
(62, 'short_league_name', 'All', 1, 56),
(63, 'short_league_name', 'All', 1, 66),
(64, 'short_league_name', 'All', 1, 70),
(65, 'short_league_name', 'All', 1, 77),
(66, 'short_league_name', 'All', 1, 83),
(67, 'short_league_name', 'All', 1, 58),
(68, 'short_league_name', 'All', 1, 7),
(69, 'short_league_name', 'All', 1, 3),
(70, 'short_league_name', 'All', 1, 8),
(71, 'short_league_name', 'All', 1, 73),
(72, 'short_league_name', 'All', 1, 51),
(73, 'short_league_name', 'All', 1, 88),
(74, 'short_league_name', 'All', 1, 35),
(75, 'short_league_name', 'All', 1, 37),
(76, 'short_league_name', 'All', 1, 67),
(77, 'short_league_name', 'All', 1, 72),
(78, 'short_league_name', 'All', 1, 79),
(79, 'short_league_name', 'All', 1, 86),
(80, 'short_league_name', 'All', 1, 46),
(81, 'short_league_name', 'All', 1, 40),
(82, 'short_league_name', 'All', 1, 78),
(83, 'short_league_name', 'All', 1, 54),
(84, 'short_league_name', 'All', 1, 69),
(85, 'short_league_name', 'All', 1, 44),
(86, 'short_league_name', 'All', 1, 84),
(87, 'short_league_name', 'All', 1, 81),
(88, 'short_league_name', 'All', 1, 76),
(89, 'short_league_name', 'BFL', 1, 4),
(90, 'short_league_name', 'BFL', 1, 10),
(91, 'short_league_name', 'BFL', 1, 1),
(92, 'short_league_name', 'BFL', 1, 2),
(93, 'short_league_name', 'BFL', 1, 5),
(94, 'short_league_name', 'BFL', 1, 6),
(95, 'short_league_name', 'BFL', 1, 9),
(96, 'short_league_name', 'BFL', 1, 7),
(97, 'short_league_name', 'BFL', 1, 3),
(98, 'short_league_name', 'BFL', 1, 8),
(99, 'short_league_name', 'GDFL', 1, 11),
(100, 'short_league_name', 'GDFL', 1, 12),
(101, 'short_league_name', 'GDFL', 1, 16),
(102, 'short_league_name', 'GDFL', 1, 19),
(103, 'short_league_name', 'GDFL', 1, 21),
(104, 'short_league_name', 'GDFL', 1, 22),
(105, 'short_league_name', 'GDFL', 1, 13),
(106, 'short_league_name', 'GDFL', 1, 14),
(107, 'short_league_name', 'GDFL', 1, 17),
(108, 'short_league_name', 'GDFL', 1, 18),
(109, 'short_league_name', 'GDFL', 1, 15),
(110, 'short_league_name', 'GDFL', 1, 20),
(111, 'short_league_name', 'GFL', 1, 23),
(112, 'short_league_name', 'GFL', 1, 24),
(113, 'short_league_name', 'GFL', 1, 25),
(114, 'short_league_name', 'GFL', 1, 26),
(115, 'short_league_name', 'GFL', 1, 27),
(116, 'short_league_name', 'GFL', 1, 28),
(117, 'short_league_name', 'GFL', 1, 29),
(118, 'short_league_name', 'GFL', 1, 30),
(119, 'short_league_name', 'GFL', 1, 31),
(120, 'short_league_name', 'GFL', 1, 33),
(121, 'short_league_name', 'GFL', 1, 34),
(122, 'short_league_name', 'GFL', 1, 32),
(123, 'short_league_name', 'All', 2, 89),
(124, 'short_league_name', 'All', 2, 90),
(125, 'short_league_name', 'All', 2, 91),
(126, 'short_league_name', 'All', 2, 92),
(127, 'short_league_name', 'All', 2, 93),
(128, 'short_league_name', 'All', 2, 94),
(129, 'short_league_name', 'All', 2, 95),
(130, 'short_league_name', 'All', 2, 96),
(131, 'short_league_name', 'All', 2, 97),
(132, 'short_league_name', 'All', 2, 98),
(133, 'short_league_name', 'All', 2, 99),
(134, 'short_league_name', 'All', 2, 100),
(135, 'short_league_name', 'BFL', 2, 89),
(136, 'short_league_name', 'BFL', 2, 93),
(137, 'short_league_name', 'GDFL', 2, 90),
(138, 'short_league_name', 'GDFL', 2, 94),
(139, 'short_league_name', 'GFL', 2, 91),
(140, 'short_league_name', 'GFL', 2, 95),
(141, 'age_group', 'Seniors', 2, 89),
(142, 'age_group', 'Seniors', 2, 90),
(143, 'age_group', 'Seniors', 2, 91),
(144, 'age_group', 'Seniors', 2, 92),
(145, 'age_group', 'Reserves', 2, 93),
(146, 'age_group', 'Reserves', 2, 94),
(147, 'age_group', 'Reserves', 2, 95),
(148, 'age_group', 'Colts', 2, 96),
(149, 'age_group', 'Under 16', 2, 97),
(150, 'age_group', 'Under 14', 2, 98),
(151, 'age_group', 'Youth Girls', 2, 99),
(152, 'age_group', 'Junior Girls', 2, 100),
(153, 'age_group', 'All', 2, 89),
(154, 'age_group', 'All', 2, 90),
(155, 'age_group', 'All', 2, 91),
(156, 'age_group', 'All', 2, 92),
(157, 'age_group', 'All', 2, 93),
(158, 'age_group', 'All', 2, 94),
(159, 'age_group', 'All', 2, 95),
(160, 'age_group', 'All', 2, 96),
(161, 'age_group', 'All', 2, 97),
(162, 'age_group', 'All', 2, 98),
(163, 'age_group', 'All', 2, 99),
(164, 'age_group', 'All', 2, 100),
(165, 'short_league_name', 'All', 3, 101),
(166, 'short_league_name', 'All', 3, 102),
(167, 'short_league_name', 'All', 3, 103),
(168, 'short_league_name', 'All', 3, 104),
(169, 'short_league_name', 'All', 3, 105),
(170, 'short_league_name', 'All', 3, 106),
(171, 'short_league_name', 'All', 3, 107),
(172, 'short_league_name', 'All', 3, 108),
(173, 'short_league_name', 'All', 3, 109),
(174, 'short_league_name', 'All', 3, 110),
(175, 'short_league_name', 'All', 3, 111),
(176, 'short_league_name', 'All', 3, 112),
(177, 'short_league_name', 'All', 3, 113),
(178, 'short_league_name', 'All', 3, 114),
(179, 'short_league_name', 'All', 3, 115),
(180, 'short_league_name', 'All', 3, 116),
(181, 'short_league_name', 'All', 3, 117),
(182, 'short_league_name', 'All', 3, 118),
(183, 'short_league_name', 'BFL', 3, 101),
(184, 'short_league_name', 'BFL', 3, 104),
(185, 'short_league_name', 'BFL', 3, 105),
(186, 'short_league_name', 'BFL', 3, 108),
(187, 'short_league_name', 'BFL', 3, 109),
(188, 'short_league_name', 'BFL', 3, 112),
(189, 'short_league_name', 'GDFL', 3, 102),
(190, 'short_league_name', 'GDFL', 3, 104),
(191, 'short_league_name', 'GDFL', 3, 106),
(192, 'short_league_name', 'GDFL', 3, 108),
(193, 'short_league_name', 'GDFL', 3, 110),
(194, 'short_league_name', 'GDFL', 3, 112),
(195, 'short_league_name', 'GFL', 3, 103),
(196, 'short_league_name', 'GFL', 3, 104),
(197, 'short_league_name', 'GFL', 3, 105),
(198, 'short_league_name', 'GFL', 3, 108),
(199, 'short_league_name', 'GFL', 3, 109),
(200, 'short_league_name', 'GFL', 3, 112),
(201, 'age_group', 'All', 3, 101),
(202, 'age_group', 'All', 3, 102),
(203, 'age_group', 'All', 3, 103),
(204, 'age_group', 'All', 3, 104),
(205, 'age_group', 'All', 3, 105),
(206, 'age_group', 'All', 3, 106),
(207, 'age_group', 'All', 3, 107),
(208, 'age_group', 'All', 3, 108),
(209, 'age_group', 'All', 3, 109),
(210, 'age_group', 'All', 3, 110),
(211, 'age_group', 'All', 3, 111),
(212, 'age_group', 'All', 3, 112),
(213, 'age_group', 'All', 3, 113),
(214, 'age_group', 'All', 3, 114),
(215, 'age_group', 'All', 3, 115),
(216, 'age_group', 'All', 3, 116),
(217, 'age_group', 'All', 3, 117),
(218, 'age_group', 'All', 3, 118),
(219, 'age_group', 'Seniors', 3, 101),
(220, 'age_group', 'Seniors', 3, 102),
(221, 'age_group', 'Seniors', 3, 103),
(222, 'age_group', 'Seniors', 3, 104),
(223, 'age_group', 'Seniors', 3, 105),
(224, 'age_group', 'Seniors', 3, 106),
(225, 'age_group', 'Seniors', 3, 107),
(226, 'age_group', 'Seniors', 3, 108),
(227, 'age_group', 'Reserves', 3, 109),
(228, 'age_group', 'Reserves', 3, 110),
(229, 'age_group', 'Reserves', 3, 111),
(230, 'age_group', 'Reserves', 3, 112),
(231, 'age_group', 'Colts', 3, 113),
(232, 'age_group', 'Colts', 3, 114),
(233, 'age_group', 'Under 16', 3, 115),
(234, 'age_group', 'Under 16', 3, 116),
(235, 'age_group', 'Under 14', 3, 117),
(236, 'age_group', 'Under 14', 3, 118),
(237, 'umpire_type', 'All', 3, 101),
(238, 'umpire_type', 'All', 3, 102),
(239, 'umpire_type', 'All', 3, 103),
(240, 'umpire_type', 'All', 3, 104),
(241, 'umpire_type', 'All', 3, 105),
(242, 'umpire_type', 'All', 3, 106),
(243, 'umpire_type', 'All', 3, 107),
(244, 'umpire_type', 'All', 3, 108),
(245, 'umpire_type', 'All', 3, 109),
(246, 'umpire_type', 'All', 3, 110),
(247, 'umpire_type', 'All', 3, 111),
(248, 'umpire_type', 'All', 3, 112),
(249, 'umpire_type', 'All', 3, 113),
(250, 'umpire_type', 'All', 3, 114),
(251, 'umpire_type', 'All', 3, 115),
(252, 'umpire_type', 'All', 3, 116),
(253, 'umpire_type', 'All', 3, 117),
(254, 'umpire_type', 'All', 3, 118),
(255, 'umpire_type', 'Boundary', 3, 101),
(256, 'umpire_type', 'Boundary', 3, 102),
(257, 'umpire_type', 'Boundary', 3, 103),
(258, 'umpire_type', 'Boundary', 3, 104),
(259, 'umpire_type', 'Field', 3, 113),
(260, 'umpire_type', 'Field', 3, 114),
(261, 'umpire_type', 'Field', 3, 115),
(262, 'umpire_type', 'Field', 3, 116),
(263, 'umpire_type', 'Field', 3, 117),
(264, 'umpire_type', 'Field', 3, 118),
(265, 'umpire_type', 'Goal', 3, 105),
(266, 'umpire_type', 'Goal', 3, 106),
(267, 'umpire_type', 'Goal', 3, 107),
(268, 'umpire_type', 'Goal', 3, 108),
(269, 'umpire_type', 'Goal', 3, 109),
(270, 'umpire_type', 'Goal', 3, 110),
(271, 'umpire_type', 'Goal', 3, 111),
(272, 'umpire_type', 'Goal', 3, 112),
(273, 'short_league_name', 'All', 4, 119),
(274, 'short_league_name', 'All', 4, 120),
(275, 'short_league_name', 'All', 4, 121),
(276, 'short_league_name', 'All', 4, 122),
(277, 'short_league_name', 'All', 4, 123),
(278, 'short_league_name', 'All', 4, 124),
(279, 'short_league_name', 'All', 4, 125),
(280, 'short_league_name', 'All', 4, 126),
(281, 'short_league_name', 'All', 4, 127),
(282, 'short_league_name', 'All', 4, 128),
(283, 'short_league_name', 'All', 4, 129),
(284, 'short_league_name', 'All', 4, 130),
(285, 'short_league_name', 'All', 4, 131),
(286, 'short_league_name', 'All', 4, 132),
(287, 'short_league_name', 'All', 4, 133),
(288, 'short_league_name', 'All', 4, 134),
(289, 'short_league_name', 'All', 4, 135),
(290, 'short_league_name', 'All', 4, 136),
(291, 'short_league_name', 'All', 4, 137),
(292, 'short_league_name', 'All', 4, 138),
(293, 'short_league_name', 'All', 4, 139),
(294, 'short_league_name', 'All', 4, 140),
(295, 'short_league_name', 'All', 4, 141),
(296, 'short_league_name', 'All', 4, 142),
(297, 'short_league_name', 'All', 4, 143),
(298, 'short_league_name', 'All', 4, 144),
(299, 'short_league_name', 'All', 4, 145),
(300, 'short_league_name', 'All', 4, 146),
(301, 'short_league_name', 'All', 4, 147),
(302, 'short_league_name', 'All', 4, 148),
(303, 'short_league_name', 'All', 4, 149),
(304, 'short_league_name', 'All', 4, 150),
(305, 'short_league_name', 'All', 4, 151),
(306, 'short_league_name', 'BFL', 4, 119),
(307, 'short_league_name', 'BFL', 4, 122),
(308, 'short_league_name', 'BFL', 4, 130),
(309, 'short_league_name', 'BFL', 4, 133),
(310, 'short_league_name', 'BFL', 4, 141),
(311, 'short_league_name', 'BFL', 4, 144),
(312, 'short_league_name', 'GDFL', 4, 120),
(313, 'short_league_name', 'GDFL', 4, 123),
(314, 'short_league_name', 'GDFL', 4, 131),
(315, 'short_league_name', 'GDFL', 4, 134),
(316, 'short_league_name', 'GDFL', 4, 142),
(317, 'short_league_name', 'GDFL', 4, 145),
(318, 'short_league_name', 'GFL', 4, 121),
(319, 'short_league_name', 'GFL', 4, 124),
(320, 'short_league_name', 'GFL', 4, 132),
(321, 'short_league_name', 'GFL', 4, 135),
(322, 'short_league_name', 'GFL', 4, 143),
(323, 'short_league_name', 'GFL', 4, 146),
(324, 'age_group', 'All', 4, 119),
(325, 'age_group', 'All', 4, 120),
(326, 'age_group', 'All', 4, 121),
(327, 'age_group', 'All', 4, 122),
(328, 'age_group', 'All', 4, 123),
(329, 'age_group', 'All', 4, 124),
(330, 'age_group', 'All', 4, 125),
(331, 'age_group', 'All', 4, 126),
(332, 'age_group', 'All', 4, 127),
(333, 'age_group', 'All', 4, 128),
(334, 'age_group', 'All', 4, 129),
(335, 'age_group', 'All', 4, 130),
(336, 'age_group', 'All', 4, 131),
(337, 'age_group', 'All', 4, 132),
(338, 'age_group', 'All', 4, 133),
(339, 'age_group', 'All', 4, 134),
(340, 'age_group', 'All', 4, 135),
(341, 'age_group', 'All', 4, 136),
(342, 'age_group', 'All', 4, 137),
(343, 'age_group', 'All', 4, 138),
(344, 'age_group', 'All', 4, 139),
(345, 'age_group', 'All', 4, 140),
(346, 'age_group', 'All', 4, 141),
(347, 'age_group', 'All', 4, 142),
(348, 'age_group', 'All', 4, 143),
(349, 'age_group', 'All', 4, 144),
(350, 'age_group', 'All', 4, 145),
(351, 'age_group', 'All', 4, 146),
(352, 'age_group', 'All', 4, 147),
(353, 'age_group', 'All', 4, 148),
(354, 'age_group', 'All', 4, 149),
(355, 'age_group', 'All', 4, 150),
(356, 'age_group', 'All', 4, 151),
(357, 'age_group', 'Seniors', 4, 119),
(358, 'age_group', 'Seniors', 4, 120),
(359, 'age_group', 'Seniors', 4, 121),
(360, 'age_group', 'Seniors', 4, 130),
(361, 'age_group', 'Seniors', 4, 131),
(362, 'age_group', 'Seniors', 4, 132),
(363, 'age_group', 'Seniors', 4, 141),
(364, 'age_group', 'Seniors', 4, 142),
(365, 'age_group', 'Seniors', 4, 143),
(366, 'age_group', 'Reserves', 4, 122),
(367, 'age_group', 'Reserves', 4, 123),
(368, 'age_group', 'Reserves', 4, 124),
(369, 'age_group', 'Reserves', 4, 133),
(370, 'age_group', 'Reserves', 4, 134),
(371, 'age_group', 'Reserves', 4, 135),
(372, 'age_group', 'Reserves', 4, 144),
(373, 'age_group', 'Reserves', 4, 145),
(374, 'age_group', 'Reserves', 4, 146),
(375, 'age_group', 'Colts', 4, 125),
(376, 'age_group', 'Colts', 4, 136),
(377, 'age_group', 'Colts', 4, 147),
(378, 'age_group', 'Under 16', 4, 126),
(379, 'age_group', 'Under 16', 4, 137),
(380, 'age_group', 'Under 16', 4, 148),
(381, 'age_group', 'Under 14', 4, 127),
(382, 'age_group', 'Under 14', 4, 138),
(383, 'age_group', 'Under 14', 4, 149),
(384, 'age_group', 'Youth Girls', 4, 128),
(385, 'age_group', 'Youth Girls', 4, 139),
(386, 'age_group', 'Youth Girls', 4, 150),
(387, 'age_group', 'Junior Girls', 4, 129),
(388, 'age_group', 'Junior Girls', 4, 140),
(389, 'age_group', 'Junior Girls', 4, 151),
(390, 'umpire_type', 'All', 4, 119),
(391, 'umpire_type', 'All', 4, 120),
(392, 'umpire_type', 'All', 4, 121),
(393, 'umpire_type', 'All', 4, 122),
(394, 'umpire_type', 'All', 4, 123),
(395, 'umpire_type', 'All', 4, 124),
(396, 'umpire_type', 'All', 4, 125),
(397, 'umpire_type', 'All', 4, 126),
(398, 'umpire_type', 'All', 4, 127),
(399, 'umpire_type', 'All', 4, 128),
(400, 'umpire_type', 'All', 4, 129),
(401, 'umpire_type', 'All', 4, 130),
(402, 'umpire_type', 'All', 4, 131),
(403, 'umpire_type', 'All', 4, 132),
(404, 'umpire_type', 'All', 4, 133),
(405, 'umpire_type', 'All', 4, 134),
(406, 'umpire_type', 'All', 4, 135),
(407, 'umpire_type', 'All', 4, 136),
(408, 'umpire_type', 'All', 4, 137),
(409, 'umpire_type', 'All', 4, 138),
(410, 'umpire_type', 'All', 4, 139),
(411, 'umpire_type', 'All', 4, 140),
(412, 'umpire_type', 'All', 4, 141),
(413, 'umpire_type', 'All', 4, 142),
(414, 'umpire_type', 'All', 4, 143),
(415, 'umpire_type', 'All', 4, 144),
(416, 'umpire_type', 'All', 4, 145),
(417, 'umpire_type', 'All', 4, 146),
(418, 'umpire_type', 'All', 4, 147),
(419, 'umpire_type', 'All', 4, 148),
(420, 'umpire_type', 'All', 4, 149),
(421, 'umpire_type', 'All', 4, 150),
(422, 'umpire_type', 'All', 4, 151),
(423, 'umpire_type', 'Boundary', 4, 119),
(424, 'umpire_type', 'Boundary', 4, 120),
(425, 'umpire_type', 'Boundary', 4, 121),
(426, 'umpire_type', 'Boundary', 4, 122),
(427, 'umpire_type', 'Boundary', 4, 123),
(428, 'umpire_type', 'Boundary', 4, 124),
(429, 'umpire_type', 'Boundary', 4, 125),
(430, 'umpire_type', 'Boundary', 4, 126),
(431, 'umpire_type', 'Boundary', 4, 127),
(432, 'umpire_type', 'Boundary', 4, 128),
(433, 'umpire_type', 'Boundary', 4, 129),
(434, 'umpire_type', 'Field', 4, 130),
(435, 'umpire_type', 'Field', 4, 131),
(436, 'umpire_type', 'Field', 4, 132),
(437, 'umpire_type', 'Field', 4, 133),
(438, 'umpire_type', 'Field', 4, 134),
(439, 'umpire_type', 'Field', 4, 135),
(440, 'umpire_type', 'Field', 4, 136),
(441, 'umpire_type', 'Field', 4, 137),
(442, 'umpire_type', 'Field', 4, 138),
(443, 'umpire_type', 'Field', 4, 139),
(444, 'umpire_type', 'Field', 4, 140),
(445, 'umpire_type', 'Goal', 4, 141),
(446, 'umpire_type', 'Goal', 4, 142),
(447, 'umpire_type', 'Goal', 4, 143),
(448, 'umpire_type', 'Goal', 4, 144),
(449, 'umpire_type', 'Goal', 4, 145),
(450, 'umpire_type', 'Goal', 4, 146),
(451, 'umpire_type', 'Goal', 4, 147),
(452, 'umpire_type', 'Goal', 4, 148),
(453, 'umpire_type', 'Goal', 4, 149),
(454, 'umpire_type', 'Goal', 4, 150),
(455, 'umpire_type', 'Goal', 4, 151),
(456, 'short_league_name', 'All', 5, 152),
(457, 'short_league_name', 'All', 5, 153),
(458, 'short_league_name', 'All', 5, 154),
(459, 'short_league_name', 'All', 5, 155),
(460, 'short_league_name', 'All', 5, 156),
(461, 'age_group', 'All', 5, 152),
(462, 'age_group', 'All', 5, 153),
(463, 'age_group', 'All', 5, 154),
(464, 'age_group', 'All', 5, 155),
(465, 'age_group', 'All', 5, 156),
(466, 'umpire_type', 'All', 5, 152),
(467, 'umpire_type', 'All', 5, 153),
(468, 'umpire_type', 'All', 5, 154),
(469, 'umpire_type', 'All', 5, 155),
(470, 'umpire_type', 'All', 5, 156),
(471, 'umpire_type', 'All', 2, 92),
(472, 'umpire_type', 'Field', 2, 92);
-- --------------------------------------------------------

--
-- Table structure for table `report_column_lookup_display`
--

CREATE TABLE IF NOT EXISTS `report_column_lookup_display` (
  `report_column_lookup_display_id` int(11) NOT NULL,
  `report_column_id` int(11) DEFAULT NULL,
  `column_display_filter_name` varchar(200) DEFAULT NULL,
  `column_display_name` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_column_lookup_display`
--

INSERT INTO `report_column_lookup_display` (`report_column_lookup_display_id`, `report_column_id`, `column_display_filter_name`, `column_display_name`) VALUES
(1, 1, 'short_league_name', 'BFL'),
(2, 2, 'short_league_name', 'BFL'),
(3, 3, 'short_league_name', 'BFL'),
(4, 4, 'short_league_name', 'BFL'),
(5, 5, 'short_league_name', 'BFL'),
(6, 6, 'short_league_name', 'BFL'),
(7, 7, 'short_league_name', 'BFL'),
(8, 8, 'short_league_name', 'BFL'),
(9, 9, 'short_league_name', 'BFL'),
(10, 10, 'short_league_name', 'BFL'),
(11, 11, 'short_league_name', 'GDFL'),
(12, 12, 'short_league_name', 'GDFL'),
(13, 13, 'short_league_name', 'GDFL'),
(14, 14, 'short_league_name', 'GDFL'),
(15, 15, 'short_league_name', 'GDFL'),
(16, 16, 'short_league_name', 'GDFL'),
(17, 17, 'short_league_name', 'GDFL'),
(18, 18, 'short_league_name', 'GDFL'),
(19, 19, 'short_league_name', 'GDFL'),
(20, 20, 'short_league_name', 'GDFL'),
(21, 21, 'short_league_name', 'GDFL'),
(22, 22, 'short_league_name', 'GDFL'),
(23, 23, 'short_league_name', 'GFL'),
(24, 24, 'short_league_name', 'GFL'),
(25, 25, 'short_league_name', 'GFL'),
(26, 26, 'short_league_name', 'GFL'),
(27, 27, 'short_league_name', 'GFL'),
(28, 28, 'short_league_name', 'GFL'),
(29, 29, 'short_league_name', 'GFL'),
(30, 30, 'short_league_name', 'GFL'),
(31, 31, 'short_league_name', 'GFL'),
(32, 32, 'short_league_name', 'GFL'),
(33, 33, 'short_league_name', 'GFL'),
(34, 34, 'short_league_name', 'GFL'),
(35, 35, 'short_league_name', 'None'),
(36, 36, 'short_league_name', 'None'),
(37, 37, 'short_league_name', 'None'),
(38, 38, 'short_league_name', 'None'),
(39, 39, 'short_league_name', 'None'),
(40, 40, 'short_league_name', 'None'),
(41, 41, 'short_league_name', 'None'),
(42, 42, 'short_league_name', 'None'),
(43, 43, 'short_league_name', 'None'),
(44, 44, 'short_league_name', 'None'),
(45, 45, 'short_league_name', 'None'),
(46, 46, 'short_league_name', 'None'),
(47, 47, 'short_league_name', 'None'),
(48, 48, 'short_league_name', 'None'),
(49, 49, 'short_league_name', 'None'),
(50, 50, 'short_league_name', 'None'),
(51, 51, 'short_league_name', 'None'),
(52, 52, 'short_league_name', 'None'),
(53, 53, 'short_league_name', 'None'),
(54, 54, 'short_league_name', 'None'),
(55, 55, 'short_league_name', 'None'),
(56, 56, 'short_league_name', 'None'),
(57, 57, 'short_league_name', 'None'),
(58, 58, 'short_league_name', 'None'),
(59, 59, 'short_league_name', 'None'),
(60, 60, 'short_league_name', 'None'),
(61, 61, 'short_league_name', 'None'),
(62, 62, 'short_league_name', 'None'),
(63, 63, 'short_league_name', 'None'),
(64, 64, 'short_league_name', 'None'),
(65, 65, 'short_league_name', 'None'),
(66, 66, 'short_league_name', 'None'),
(67, 67, 'short_league_name', 'None'),
(68, 68, 'short_league_name', 'None'),
(69, 69, 'short_league_name', 'None'),
(70, 70, 'short_league_name', 'None'),
(71, 71, 'short_league_name', 'None'),
(72, 72, 'short_league_name', 'None'),
(73, 73, 'short_league_name', 'None'),
(74, 74, 'short_league_name', 'None'),
(75, 75, 'short_league_name', 'None'),
(76, 76, 'short_league_name', 'None'),
(77, 77, 'short_league_name', 'None'),
(78, 78, 'short_league_name', 'None'),
(79, 79, 'short_league_name', 'None'),
(80, 80, 'short_league_name', 'None'),
(81, 81, 'short_league_name', 'None'),
(82, 82, 'short_league_name', 'None'),
(83, 83, 'short_league_name', 'None'),
(84, 84, 'short_league_name', 'None'),
(85, 85, 'short_league_name', 'None'),
(86, 86, 'short_league_name', 'None'),
(87, 87, 'short_league_name', 'None'),
(88, 88, 'short_league_name', 'None'),
(89, 1, 'club_name', 'Anglesea'),
(90, 2, 'club_name', 'Barwon Heads'),
(91, 3, 'club_name', 'Drysdale'),
(92, 4, 'club_name', 'Geelong Amateur'),
(93, 5, 'club_name', 'Modewarre'),
(94, 6, 'club_name', 'Newcomb Power'),
(95, 7, 'club_name', 'Ocean Grove'),
(96, 8, 'club_name', 'Portarlington'),
(97, 9, 'club_name', 'Queenscliff'),
(98, 10, 'club_name', 'Torquay'),
(99, 11, 'club_name', 'Anakie'),
(100, 12, 'club_name', 'Bannockburn'),
(101, 13, 'club_name', 'Bell Post Hill'),
(102, 14, 'club_name', 'Belmont Lions'),
(103, 15, 'club_name', 'Corio'),
(104, 16, 'club_name', 'East Geelong'),
(105, 17, 'club_name', 'Geelong West'),
(106, 18, 'club_name', 'Inverleigh'),
(107, 19, 'club_name', 'North Geelong'),
(108, 20, 'club_name', 'Thomson'),
(109, 21, 'club_name', 'Werribee Centrals'),
(110, 22, 'club_name', 'Winchelsea'),
(111, 23, 'club_name', 'Bell Park'),
(112, 24, 'club_name', 'Colac'),
(113, 25, 'club_name', 'Grovedale'),
(114, 26, 'club_name', 'Gwsp'),
(115, 27, 'club_name', 'Lara'),
(116, 28, 'club_name', 'Leopold'),
(117, 29, 'club_name', 'Newtown & Chilwell'),
(118, 30, 'club_name', 'North Shore'),
(119, 31, 'club_name', 'South Barwon'),
(120, 32, 'club_name', 'St Albans'),
(121, 33, 'club_name', 'St Joseph''s'),
(122, 34, 'club_name', 'St Mary''s'),
(123, 35, 'club_name', 'Anakie'),
(124, 36, 'club_name', 'Anglesea'),
(125, 37, 'club_name', 'Bannockburn'),
(126, 38, 'club_name', 'Barwon Heads'),
(127, 39, 'club_name', 'Bell Park'),
(128, 40, 'club_name', 'Belmont Lions / Newcomb'),
(129, 41, 'club_name', 'Belmont Lions'),
(130, 42, 'club_name', 'Colac'),
(131, 43, 'club_name', 'Corio'),
(132, 44, 'club_name', 'Drysdale Bennett'),
(133, 45, 'club_name', 'Drysdale Byrne'),
(134, 46, 'club_name', 'Drysdale Eddy'),
(135, 47, 'club_name', 'Drysdale Hall'),
(136, 48, 'club_name', 'Drysdale Hector'),
(137, 49, 'club_name', 'Drysdale'),
(138, 50, 'club_name', 'East Geelong'),
(139, 51, 'club_name', 'Geelong Amateur'),
(140, 52, 'club_name', 'Geelong West St Peters'),
(141, 53, 'club_name', 'Grovedale'),
(142, 54, 'club_name', 'Gwsp / Bannockburn'),
(143, 55, 'club_name', 'Inverleigh'),
(144, 56, 'club_name', 'Lara'),
(145, 57, 'club_name', 'Leopold'),
(146, 58, 'club_name', 'Modewarre'),
(147, 59, 'club_name', 'Newcomb'),
(148, 60, 'club_name', 'Newtown & Chilwell'),
(149, 61, 'club_name', 'North Geelong'),
(150, 62, 'club_name', 'North Shore'),
(151, 63, 'club_name', 'Ocean Grove'),
(152, 64, 'club_name', 'Ogcc'),
(153, 65, 'club_name', 'Portarlington'),
(154, 66, 'club_name', 'Queenscliff'),
(155, 67, 'club_name', 'South Barwon / Geelong Amateur'),
(156, 68, 'club_name', 'South Barwon'),
(157, 69, 'club_name', 'St Albans Allthorpe'),
(158, 70, 'club_name', 'St Albans Reid'),
(159, 71, 'club_name', 'St Albans'),
(160, 72, 'club_name', 'St Joseph''s Hill'),
(161, 73, 'club_name', 'St Joseph''s Podbury'),
(162, 74, 'club_name', 'St Joseph''s'),
(163, 75, 'club_name', 'St Mary''s'),
(164, 76, 'club_name', 'Tigers Gold'),
(165, 77, 'club_name', 'Torquay Bumpstead'),
(166, 78, 'club_name', 'Torquay Coles'),
(167, 79, 'club_name', 'Torquay Dunstan'),
(168, 80, 'club_name', 'Torquay Jones'),
(169, 81, 'club_name', 'Torquay Nairn'),
(170, 82, 'club_name', 'Torquay Papworth'),
(171, 83, 'club_name', 'Torquay Pyers'),
(172, 84, 'club_name', 'Torquay Scott'),
(173, 85, 'club_name', 'Torquay'),
(174, 86, 'club_name', 'Werribee Centrals'),
(175, 87, 'club_name', 'Winchelsea / Grovedale'),
(176, 88, 'club_name', 'Winchelsea'),
(177, 89, 'short_league_name', 'BFL'),
(178, 90, 'short_league_name', 'GDFL'),
(179, 91, 'short_league_name', 'GFL'),
(180, 92, 'short_league_name', '2 Umpires'),
(181, 93, 'short_league_name', 'BFL'),
(182, 94, 'short_league_name', 'GDFL'),
(183, 95, 'short_league_name', 'GFL'),
(184, 96, 'short_league_name', 'None'),
(185, 97, 'short_league_name', 'None'),
(186, 98, 'short_league_name', 'None'),
(187, 99, 'short_league_name', 'None'),
(188, 100, 'short_league_name', 'None'),
(189, 89, 'age_group', 'Seniors'),
(190, 90, 'age_group', 'Seniors'),
(191, 91, 'age_group', 'Seniors'),
(192, 92, 'age_group', 'Seniors'),
(193, 93, 'age_group', 'Reserves'),
(194, 94, 'age_group', 'Reserves'),
(195, 95, 'age_group', 'Reserves'),
(196, 96, 'age_group', 'Colts'),
(197, 97, 'age_group', 'Under 16'),
(198, 98, 'age_group', 'Under 14'),
(199, 99, 'age_group', 'Youth Girls'),
(200, 100, 'age_group', 'Junior Girls'),
(201, 101, 'short_league_name', 'BFL'),
(202, 102, 'short_league_name', 'GDFL'),
(203, 103, 'short_league_name', 'GFL'),
(204, 104, 'short_league_name', 'No.'),
(205, 105, 'short_league_name', 'BFL'),
(206, 106, 'short_league_name', 'GDFL'),
(207, 107, 'short_league_name', 'GFL'),
(208, 108, 'short_league_name', 'No.'),
(209, 109, 'short_league_name', 'BFL'),
(210, 110, 'short_league_name', 'GDFL'),
(211, 111, 'short_league_name', 'GFL'),
(212, 112, 'short_league_name', 'No.'),
(213, 113, 'short_league_name', 'Clubs'),
(214, 114, 'short_league_name', 'No.'),
(215, 115, 'short_league_name', 'Clubs'),
(216, 116, 'short_league_name', 'No.'),
(217, 117, 'short_league_name', 'Clubs'),
(218, 118, 'short_league_name', 'No.'),
(219, 101, 'umpire_type_age_group', 'No Senior Boundary'),
(220, 102, 'umpire_type_age_group', 'No Senior Boundary'),
(221, 103, 'umpire_type_age_group', 'No Senior Boundary'),
(222, 104, 'umpire_type_age_group', 'No Senior Boundary'),
(223, 105, 'umpire_type_age_group', 'No Senior Goal'),
(224, 106, 'umpire_type_age_group', 'No Senior Goal'),
(225, 107, 'umpire_type_age_group', 'No Senior Goal'),
(226, 108, 'umpire_type_age_group', 'No Senior Goal'),
(227, 109, 'umpire_type_age_group', 'No Reserve Goal'),
(228, 110, 'umpire_type_age_group', 'No Reserve Goal'),
(229, 111, 'umpire_type_age_group', 'No Reserve Goal'),
(230, 112, 'umpire_type_age_group', 'No Reserve Goal'),
(231, 113, 'umpire_type_age_group', 'No Colts Field'),
(232, 114, 'umpire_type_age_group', 'No Colts Field'),
(233, 115, 'umpire_type_age_group', 'No U16 Field'),
(234, 116, 'umpire_type_age_group', 'No U16 Field'),
(235, 117, 'umpire_type_age_group', 'No U14 Field'),
(236, 118, 'umpire_type_age_group', 'No U14 Field'),
(237, 119, 'umpire_type', 'Boundary'),
(238, 120, 'umpire_type', 'Boundary'),
(239, 121, 'umpire_type', 'Boundary'),
(240, 122, 'umpire_type', 'Boundary'),
(241, 123, 'umpire_type', 'Boundary'),
(242, 124, 'umpire_type', 'Boundary'),
(243, 125, 'umpire_type', 'Boundary'),
(244, 126, 'umpire_type', 'Boundary'),
(245, 127, 'umpire_type', 'Boundary'),
(246, 128, 'umpire_type', 'Boundary'),
(247, 129, 'umpire_type', 'Boundary'),
(248, 130, 'umpire_type', 'Field'),
(249, 131, 'umpire_type', 'Field'),
(250, 132, 'umpire_type', 'Field'),
(251, 133, 'umpire_type', 'Field'),
(252, 134, 'umpire_type', 'Field'),
(253, 135, 'umpire_type', 'Field'),
(254, 136, 'umpire_type', 'Field'),
(255, 137, 'umpire_type', 'Field'),
(256, 138, 'umpire_type', 'Field'),
(257, 139, 'umpire_type', 'Field'),
(258, 140, 'umpire_type', 'Field'),
(259, 141, 'umpire_type', 'Goal'),
(260, 142, 'umpire_type', 'Goal'),
(261, 143, 'umpire_type', 'Goal'),
(262, 144, 'umpire_type', 'Goal'),
(263, 145, 'umpire_type', 'Goal'),
(264, 146, 'umpire_type', 'Goal'),
(265, 147, 'umpire_type', 'Goal'),
(266, 148, 'umpire_type', 'Goal'),
(267, 149, 'umpire_type', 'Goal'),
(268, 150, 'umpire_type', 'Goal'),
(269, 151, 'umpire_type', 'Goal'),
(270, 119, 'age_group', 'Seniors'),
(271, 120, 'age_group', 'Seniors'),
(272, 121, 'age_group', 'Seniors'),
(273, 122, 'age_group', 'Reserves'),
(274, 123, 'age_group', 'Reserves'),
(275, 124, 'age_group', 'Reserves'),
(276, 125, 'age_group', 'Colts'),
(277, 126, 'age_group', 'Under 16'),
(278, 127, 'age_group', 'Under 14'),
(279, 128, 'age_group', 'Youth Girls'),
(280, 129, 'age_group', 'Junior Girls'),
(281, 130, 'age_group', 'Seniors'),
(282, 131, 'age_group', 'Seniors'),
(283, 132, 'age_group', 'Seniors'),
(284, 133, 'age_group', 'Reserves'),
(285, 134, 'age_group', 'Reserves'),
(286, 135, 'age_group', 'Reserves'),
(287, 136, 'age_group', 'Colts'),
(288, 137, 'age_group', 'Under 16'),
(289, 138, 'age_group', 'Under 14'),
(290, 139, 'age_group', 'Youth Girls'),
(291, 140, 'age_group', 'Junior Girls'),
(292, 141, 'age_group', 'Seniors'),
(293, 142, 'age_group', 'Seniors'),
(294, 143, 'age_group', 'Seniors'),
(295, 144, 'age_group', 'Reserves'),
(296, 145, 'age_group', 'Reserves'),
(297, 146, 'age_group', 'Reserves'),
(298, 147, 'age_group', 'Colts'),
(299, 148, 'age_group', 'Under 16'),
(300, 149, 'age_group', 'Under 14'),
(301, 150, 'age_group', 'Youth Girls'),
(302, 151, 'age_group', 'Junior Girls'),
(303, 119, 'short_league_name', 'BFL'),
(304, 120, 'short_league_name', 'GDFL'),
(305, 121, 'short_league_name', 'GFL'),
(306, 122, 'short_league_name', 'BFL'),
(307, 123, 'short_league_name', 'GDFL'),
(308, 124, 'short_league_name', 'GFL'),
(309, 125, 'short_league_name', 'None'),
(310, 126, 'short_league_name', 'None'),
(311, 127, 'short_league_name', 'None'),
(312, 128, 'short_league_name', 'None'),
(313, 129, 'short_league_name', 'None'),
(314, 130, 'short_league_name', 'BFL'),
(315, 131, 'short_league_name', 'GDFL'),
(316, 132, 'short_league_name', 'GFL'),
(317, 133, 'short_league_name', 'BFL'),
(318, 134, 'short_league_name', 'GDFL'),
(319, 135, 'short_league_name', 'GFL'),
(320, 136, 'short_league_name', 'None'),
(321, 137, 'short_league_name', 'None'),
(322, 138, 'short_league_name', 'None'),
(323, 139, 'short_league_name', 'None'),
(324, 140, 'short_league_name', 'None'),
(325, 141, 'short_league_name', 'BFL'),
(326, 142, 'short_league_name', 'GDFL'),
(327, 143, 'short_league_name', 'GFL'),
(328, 144, 'short_league_name', 'BFL'),
(329, 145, 'short_league_name', 'GDFL'),
(330, 146, 'short_league_name', 'GFL'),
(331, 147, 'short_league_name', 'None'),
(332, 148, 'short_league_name', 'None'),
(333, 149, 'short_league_name', 'None'),
(334, 150, 'short_league_name', 'None'),
(335, 151, 'short_league_name', 'None'),
(336, 152, 'short_league_name', 'BFL'),
(337, 153, 'short_league_name', 'GDFL'),
(338, 154, 'short_league_name', 'GFL'),
(339, 155, 'short_league_name', 'None'),
(340, 156, 'short_league_name', 'Total');

-- --------------------------------------------------------

--
-- Table structure for table `report_table`
--

CREATE TABLE IF NOT EXISTS `report_table` (
  `report_table_id` int(4) NOT NULL,
  `report_name` int(4) DEFAULT NULL,
  `table_name` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_table`
--

INSERT INTO `report_table` (`report_table_id`, `report_name`, `table_name`) VALUES
(1, 1, 'mv_report_01'),
(2, 2, 'mv_report_02'),
(3, 3, 'mv_report_03'),
(4, 4, 'mv_report_04'),
(5, 5, 'mv_report_05');

-- --------------------------------------------------------

--
-- Table structure for table `round`
--

CREATE TABLE IF NOT EXISTS `round` (
  `ID` int(11) NOT NULL,
  `round_number` int(11) DEFAULT NULL COMMENT 'The round number of the season.',
  `round_date` datetime DEFAULT NULL COMMENT 'The date this round starts on.',
  `season_id` int(11) DEFAULT NULL COMMENT 'The season this round belongs to.',
  `league_id` int(11) DEFAULT NULL COMMENT 'The league this round belonds to.'
) ENGINE=InnoDB AUTO_INCREMENT=19038 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `round`
--

DELETE FROM round;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `ID` int(11) NOT NULL,
  `team_name` varchar(100) DEFAULT NULL COMMENT 'The team name within a club.',
  `club_id` int(11) DEFAULT NULL COMMENT 'The club that this team belongs to.'
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`ID`, `team_name`, `club_id`) VALUES
(1, 'Anakie', 63),
(2, 'Anglesea', 64),
(3, 'Bannockburn', 65),
(4, 'Bannockburn 1', 65),
(5, 'Bannockburn 2', 65),
(6, 'Barwon Heads', 66),
(7, 'Bell Park', 67),
(8, 'Bell Park 1', 67),
(9, 'Bell Park 2', 67),
(10, 'Bell Post Hill', 68),
(11, 'Belmont Lions', 69),
(12, 'Belmont Lions / Newcomb', 70),
(13, 'Colac', 71),
(14, 'Corio', 72),
(15, 'Drysdale', 73),
(16, 'Drysdale Bennett', 74),
(17, 'Drysdale Byrne', 75),
(18, 'Drysdale Eddy', 76),
(19, 'Drysdale Hall', 77),
(20, 'Drysdale Hector', 78),
(21, 'Drysdale Hoyer', 79),
(22, 'East Geelong', 80),
(23, 'Geelong Amateur', 81),
(24, 'Geelong Amateur 1', 81),
(25, 'Geelong Amateur 2', 81),
(26, 'Geelong West', 82),
(27, 'Geelong West St Peters', 83),
(28, 'Geelong West St Peters 1', 83),
(29, 'Geelong West St Peters 2', 83),
(30, 'Grovedale', 84),
(31, 'Grovedale 1', 84),
(32, 'Grovedale 2', 84),
(33, 'Grovedale 3', 84),
(34, 'Gwsp', 85),
(35, 'Gwsp / Bannockburn', 86),
(36, 'Inverleigh', 87),
(37, 'Lara', 88),
(38, 'Lara 1', 88),
(39, 'Lara 2', 88),
(40, 'Leopold', 89),
(41, 'Leopold 1', 89),
(42, 'Leopold 2', 89),
(43, 'Modewarre', 90),
(44, 'Newcomb', 91),
(45, 'Newcomb Power', 92),
(46, 'Newtown & Chilwell', 93),
(47, 'Newtown & Chilwell 1', 93),
(48, 'Newtown & Chilwell 2', 93),
(49, 'North Geelong', 94),
(50, 'North Shore', 95),
(51, 'Ocean Grove', 96),
(52, 'Ocean Grove 1', 96),
(53, 'Ocean Grove 2', 96),
(54, 'Ogcc 1', 97),
(55, 'Ogcc 2', 97),
(56, 'Ogcc 3', 97),
(57, 'Portarlington', 98),
(58, 'Queenscliff', 99),
(59, 'South Barwon', 100),
(60, 'South Barwon / Geelong Amateur', 101),
(61, 'South Barwon 1', 100),
(62, 'South Barwon 2', 100),
(63, 'St Albans', 102),
(64, 'St Albans Allthorpe', 103),
(65, 'St Albans Reid', 104),
(66, 'St Joseph''s', 105),
(67, 'St Joseph''s 1', 105),
(68, 'St Joseph''s 2', 105),
(69, 'St Joseph''s 3', 105),
(70, 'St Joseph''s 4', 105),
(71, 'St Joseph''s Hill', 106),
(72, 'St Joseph''s Podbury', 107),
(73, 'St Mary''s', 108),
(74, 'St Mary''s 1', 108),
(75, 'St Mary''s 2', 108),
(76, 'St Mary''s 3', 108),
(77, 'Thomson', 109),
(78, 'Tigers Black', 110),
(79, 'Tigers Gold', 111),
(80, 'Torquay', 112),
(81, 'Torquay 1', 112),
(82, 'Torquay 2', 112),
(83, 'Torquay Bumpstead', 113),
(84, 'Torquay Coles', 114),
(85, 'Torquay Dunstan', 115),
(86, 'Torquay Jones', 116),
(87, 'Torquay Nairn', 117),
(88, 'Torquay Papworth', 118),
(89, 'Torquay Pyers', 119),
(90, 'Torquay Scott', 120),
(91, 'Werribee Centrals', 121),
(92, 'Winchelsea', 122),
(93, 'Winchelsea / Grovedale', 123);

-- --------------------------------------------------------

--
-- Table structure for table `test_report`
--


-- --------------------------------------------------------

--
-- Table structure for table `umpire`
--

CREATE TABLE IF NOT EXISTS `umpire` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13201 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `umpire`
--

DELETE FROM umpire;

-- --------------------------------------------------------

--
-- Table structure for table `umpire_name_type`
--

CREATE TABLE IF NOT EXISTS `umpire_name_type` (
  `ID` int(11) NOT NULL,
  `umpire_id` int(11) DEFAULT NULL,
  `umpire_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8449 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `umpire_name_type`
--

DELETE FROM umpire_name_type;

-- --------------------------------------------------------

--
-- Table structure for table `umpire_name_type_match`
--

CREATE TABLE IF NOT EXISTS `umpire_name_type_match` (
  `ID` int(11) NOT NULL,
  `umpire_name_type_id` int(11) DEFAULT NULL,
  `match_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26615 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `umpire_name_type_match`
--

DELETE FROM umpire_name_type_match;



-- --------------------------------------------------------

--
-- Table structure for table `umpire_type`
--

CREATE TABLE IF NOT EXISTS `umpire_type` (
  `ID` int(11) NOT NULL,
  `umpire_type_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `umpire_type`
--

INSERT INTO `umpire_type` (`ID`, `umpire_type_name`) VALUES
(1, 'Field'),
(2, 'Boundary'),
(3, 'Goal');

-- --------------------------------------------------------

--
-- Table structure for table `umpire_users`
--

CREATE TABLE IF NOT EXISTS `umpire_users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `umpire_users`
--

INSERT INTO `umpire_users` (`id`, `user_name`, `user_email`, `user_password`) VALUES
(1, 'bb', 'bb@bb.com', '4124bc0a9335c27f086f24ba207a4912');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `age_group`
--
ALTER TABLE `age_group`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `age_group_division`
--
ALTER TABLE `age_group_division`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_age_group_id_idx` (`age_group_id`), ADD KEY `fk_age_group_division_idx` (`division_id`);

--
-- Indexes for table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `competition_lookup`
--
ALTER TABLE `competition_lookup`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_comp_league_idx` (`league_id`), ADD KEY `fk_comp_season_idx` (`season_id`);

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ground`
--
ALTER TABLE `ground`
  ADD PRIMARY KEY (`ID`), ADD KEY `alternative_name` (`alternative_name`);

--
-- Indexes for table `imported_files`
--
ALTER TABLE `imported_files`
  ADD PRIMARY KEY (`imported_file_id`);

--
-- Indexes for table `league`
--
ALTER TABLE `league`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_leage_agd_idx` (`age_group_division_id`);

--
-- Indexes for table `match_import`
--
ALTER TABLE `match_import`
  ADD PRIMARY KEY (`ID`), ADD KEY `date` (`date`), ADD KEY `round` (`round`), ADD KEY `competition_name` (`competition_name`), ADD KEY `season` (`season`), ADD KEY `ground` (`ground`), ADD KEY `home_team` (`home_team`), ADD KEY `away_team` (`away_team`);

--
-- Indexes for table `match_played`
--
ALTER TABLE `match_played`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_match_round_idx` (`round_id`), ADD KEY `fk_match_ground_idx` (`ground_id`), ADD KEY `fk_match_team_idx` (`home_team_id`);

--
-- Indexes for table `match_staging`
--
ALTER TABLE `match_staging`
  ADD PRIMARY KEY (`appointments_id`);

--
-- Indexes for table `mv_report_01`
--
ALTER TABLE `mv_report_01`
  ADD KEY `idx_mv01_short_league_name` (`short_league_name`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`), ADD KEY `slug` (`slug`);

--
-- Indexes for table `report_column`
--
ALTER TABLE `report_column`
  ADD PRIMARY KEY (`report_column_id`);

--
-- Indexes for table `report_column_lookup`
--
ALTER TABLE `report_column_lookup`
  ADD PRIMARY KEY (`report_column_lookup_id`), ADD KEY `report_table_id` (`report_table_id`), ADD KEY `report_column_id` (`report_column_id`);

--
-- Indexes for table `report_column_lookup_display`
--
ALTER TABLE `report_column_lookup_display`
  ADD PRIMARY KEY (`report_column_lookup_display_id`), ADD KEY `report_column_id` (`report_column_id`);

--
-- Indexes for table `report_table`
--
ALTER TABLE `report_table`
  ADD PRIMARY KEY (`report_table_id`);

--
-- Indexes for table `round`
--
ALTER TABLE `round`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_round_season_idx` (`season_id`), ADD KEY `fk_round_league_idx` (`league_id`);

--
-- Indexes for table `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_team_club_idx` (`club_id`), ADD KEY `team_name` (`team_name`);

--
-- Indexes for table `test_report`
--
ALTER TABLE `test_report`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `umpire`
--
ALTER TABLE `umpire`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `umpire_name_type`
--
ALTER TABLE `umpire_name_type`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_unt_umpire_idx` (`umpire_id`), ADD KEY `fk_unt_ut_idx` (`umpire_type_id`);

--
-- Indexes for table `umpire_name_type_match`
--
ALTER TABLE `umpire_name_type_match`
  ADD PRIMARY KEY (`ID`), ADD KEY `fk_untm_match_idx` (`match_id`), ADD KEY `fk_untm_unt_idx` (`umpire_name_type_id`);

--
-- Indexes for table `umpire_type`
--
ALTER TABLE `umpire_type`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `umpire_users`
--
ALTER TABLE `umpire_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `age_group`
--
ALTER TABLE `age_group`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `age_group_division`
--
ALTER TABLE `age_group_division`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `imported_files`
--
ALTER TABLE `imported_files`
  MODIFY `imported_file_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `league`
--
ALTER TABLE `league`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `match_import`
--
ALTER TABLE `match_import`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32173;
--
-- AUTO_INCREMENT for table `match_played`
--
ALTER TABLE `match_played`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31906;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `round`
--
ALTER TABLE `round`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19038;
--
-- AUTO_INCREMENT for table `season`
--
ALTER TABLE `season`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `test_report`
--
ALTER TABLE `test_report`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5025;
--
-- AUTO_INCREMENT for table `umpire`
--
ALTER TABLE `umpire`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13201;
--
-- AUTO_INCREMENT for table `umpire_name_type`
--
ALTER TABLE `umpire_name_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8449;
--
-- AUTO_INCREMENT for table `umpire_name_type_match`
--
ALTER TABLE `umpire_name_type_match`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26615;
--
-- AUTO_INCREMENT for table `umpire_type`
--
ALTER TABLE `umpire_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `umpire_users`
--
ALTER TABLE `umpire_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `age_group_division`
--
ALTER TABLE `age_group_division`
ADD CONSTRAINT `fk_age_group_division` FOREIGN KEY (`division_id`) REFERENCES `division` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_age_group_id` FOREIGN KEY (`age_group_id`) REFERENCES `age_group` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `competition_lookup`
--
ALTER TABLE `competition_lookup`
ADD CONSTRAINT `fk_comp_league` FOREIGN KEY (`league_id`) REFERENCES `league` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_comp_season` FOREIGN KEY (`season_id`) REFERENCES `season` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `league`
--
ALTER TABLE `league`
ADD CONSTRAINT `fk_league_agd` FOREIGN KEY (`age_group_division_id`) REFERENCES `age_group_division` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `match_played`
--
ALTER TABLE `match_played`
ADD CONSTRAINT `fk_match_ground` FOREIGN KEY (`ground_id`) REFERENCES `ground` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_match_round` FOREIGN KEY (`round_id`) REFERENCES `round` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_match_team` FOREIGN KEY (`home_team_id`) REFERENCES `team` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `round`
--
ALTER TABLE `round`
ADD CONSTRAINT `fk_round_league` FOREIGN KEY (`league_id`) REFERENCES `league` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_round_season` FOREIGN KEY (`season_id`) REFERENCES `season` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
ADD CONSTRAINT `fk_team_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `umpire_name_type`
--
ALTER TABLE `umpire_name_type`
ADD CONSTRAINT `fk_unt_umpire` FOREIGN KEY (`umpire_id`) REFERENCES `umpire` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_unt_ut` FOREIGN KEY (`umpire_type_id`) REFERENCES `umpire_type` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `umpire_name_type_match`
--
ALTER TABLE `umpire_name_type_match`
ADD CONSTRAINT `fk_untm_match` FOREIGN KEY (`match_id`) REFERENCES `match_played` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_untm_unt` FOREIGN KEY (`umpire_name_type_id`) REFERENCES `umpire_name_type` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;


ALTER DATABASE umpire CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE age_group CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE age_group_division CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE club CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE competition_lookup CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE dates CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE division CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE ground CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE imported_files CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE league CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE match_import CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE match_played CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE match_staging CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_report_01 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_report_02 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_report_03 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_report_04 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_report_05 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE mv_summary_staging CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE news CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE report_column CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE report_column_lookup CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE report_column_lookup_display CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE report_table CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE round CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE season CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE team CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE test_report CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE umpire CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE umpire_name_type CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE umpire_name_type_match CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE umpire_type CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE umpire_users CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;


CREATE INDEX idx_matchimport_date ON umpire.match_import(date);
CREATE INDEX idx_matchimport_round ON umpire.match_import(round);
CREATE INDEX idx_matchimport_competition_name ON umpire.match_import(competition_name);
CREATE INDEX idx_matchimport_season ON umpire.match_import(season);
CREATE INDEX idx_matchimport_ground ON umpire.match_import(ground);
CREATE INDEX idx_matchimport_home_team ON umpire.match_import(home_team);
CREATE INDEX idx_matchimport_away_team ON umpire.match_import(away_team);
CREATE INDEX idx_team_team_name ON umpire.team(team_name);
CREATE INDEX idx_ground_alternative_name ON umpire.ground(alternative_name);
CREATE INDEX idx_mv01_short_league_name ON umpire.mv_report_01(short_league_name);
CREATE INDEX idx_mv01_fullname ON umpire.mv_report_01(full_name);