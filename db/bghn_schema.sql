-- /****
-- *  Copyright (c) 2013, James Arnott (BadGopher)
-- *   All rights reserved.
-- *   
-- *   Redistribution and use in source and binary forms, with or without 
-- *   modification, are permitted provided that the following conditions are met:
-- *   
-- *   - Redistributions of source code must retain the above copyright notice, 
-- *     this list of conditions and the following disclaimer.
-- *   - Redistributions in binary form must reproduce the above copyright notice,
-- *     this list of conditions and the following disclaimer in the documentation
-- *     and/or other materials provided with the distribution.
-- *   - Neither the name of James Arnott (BadGopher) nor the names of its 
-- *     contributors may be used to endorse or promote products derived from this
-- *     software without specific prior written permission.
-- *
-- *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
-- *  AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
-- *  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
-- *  ARE DISCLAIMED.IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
-- *  LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
-- *  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
-- *  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
-- *  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
-- *  CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
-- *  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
-- *  POSSIBILITY OF SUCH DAMAGE.
-- ****/

-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2013 at 07:58 PM
-- Server version: 5.5.32-cll-lve
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bghn`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `UpdateHeadlinesTable`$$
CREATE DEFINER=`username`@`localhost` PROCEDURE `UpdateHeadlinesTable`()
BEGIN
    DECLARE isCurrentIdDone, currentFeedId INT DEFAULT 0;
    DECLARE feedIdCursor CURSOR FOR SELECT DISTINCT feedId FROM `feeds` WHERE `feedCategoryId` = 1 AND `activeFlag` = 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET isCurrentIdDone = 1;
    
    TRUNCATE TABLE `Headlines`;
    
    SET @insertSql = 'INSERT INTO `Headlines` SELECT null, `feedId`, `id`, `itemTitle`, `importTime` FROM `items` WHERE `feedId` = ? ORDER BY `importTime` DESC LIMIT 15';
    
    OPEN feedIdCursor;
    REPEAT
        FETCH feedIdCursor INTO currentFeedId;
        IF NOT isCurrentIdDone THEN
            BEGIN
                SET @currentFeedId = currentFeedId;
                PREPARE stmt FROM @insertSql;
                EXECUTE stmt USING @currentFeedId;
                DROP PREPARE stmt;
            END;
        END IF;
    UNTIL isCurrentIdDone END REPEAT;
    CLOSE feedIdCursor;

--    OPTIMIZE TABLE `Headlines`;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `feedCategories`
--

DROP TABLE IF EXISTS `feedCategories`;
CREATE TABLE IF NOT EXISTS `feedCategories` (
  `feedCategoryId` tinyint(2) unsigned NOT NULL,
  `Description` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`feedCategoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

DROP TABLE IF EXISTS `feeds`;
CREATE TABLE IF NOT EXISTS `feeds` (
  `feedId` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `feedSiteName` varchar(128) NOT NULL,
  `feedRssUrl` varchar(255) NOT NULL,
  `feedWebUrl` varchar(255) NOT NULL,
  `activeFlag` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `purgeFlag` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `feedCategoryId` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `DefaultOrder` mediumint(4) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`feedId`),
  KEY `feedCategoryId` (`feedCategoryId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Headlines`
--

DROP TABLE IF EXISTS `Headlines`;
CREATE TABLE IF NOT EXISTS `Headlines` (
  `idHeadlines` int(11) NOT NULL AUTO_INCREMENT,
  `FeedId` int(11) DEFAULT NULL,
  `ItemId` int(11) DEFAULT NULL,
  `ItemTitle` varchar(255) DEFAULT NULL,
  `ItemTime` datetime DEFAULT NULL,
  PRIMARY KEY (`idHeadlines`),
  KEY `Lookup` (`FeedId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `HomePageSites`
--
DROP VIEW IF EXISTS `HomePageSites`;
CREATE TABLE IF NOT EXISTS `HomePageSites` (
`Id` int(7) unsigned
,`SiteName` varchar(128)
,`SiteUrl` varchar(255)
,`DefaultOrder` mediumint(4)
);
-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `feedId` smallint(4) unsigned NOT NULL,
  `itemTitle` varchar(255) NOT NULL,
  `itemLink` varchar(255) NOT NULL,
  `importTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NoFeedDupes` (`feedId`,`itemTitle`(96)),
  UNIQUE KEY `NoReimports` (`itemLink`(96)),
  KEY `feedId` (`feedId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for view `HomePageSites`
--
DROP TABLE IF EXISTS `HomePageSites`;

CREATE ALGORITHM=UNDEFINED DEFINER=`username`@`localhost` SQL SECURITY DEFINER VIEW `HomePageSites` AS select `feeds`.`feedId` AS `Id`,`feeds`.`feedSiteName` AS `SiteName`,`feeds`.`feedWebUrl` AS `SiteUrl`,`feeds`.`DefaultOrder` AS `DefaultOrder` from `feeds` where ((`feeds`.`feedCategoryId` = 1) and (`feeds`.`activeFlag` = 1)) order by `feeds`.`DefaultOrder`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
