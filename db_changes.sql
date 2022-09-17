/*DB CHANGES MADE ON 13-01-2022*/
-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2022 at 11:37 AM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mereid21_leadcrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitytype`
--

DROP TABLE IF EXISTS `activitytype`;
CREATE TABLE IF NOT EXISTS `activitytype` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activitytype`
--

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES
(1, 'Auto Asigned', '2022-01-13 22:36:52'),
(2, 'Call', '2022-01-13 22:36:52'),
(3, 'Lead Assigned By Regional Manager', '2022-01-13 22:37:13'),
(4, 'Lead Status Changed', '2022-01-13 22:37:13'),
(5, 'Lead Transferred', '2022-01-13 22:37:27'),
(6, 'Meeting', '2022-01-13 22:37:27'),
(7, 'New Remarks Added', '2022-01-13 22:37:40'),
(8, 'Opportunity Status Changed', '2022-01-13 22:37:40');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/*DB CHANGES MADE ON 14-01-2022*/
ALTER TABLE `leaddata` CHANGE `id` `id` BIGINT NOT NULL AUTO_INCREMENT;

/*DB CHANGES MADE ON 16-01-2022*/
ALTER TABLE `leaddata` ADD `status` ENUM('Open','Closed') NOT NULL DEFAULT 'Open' AFTER `creaedtme`;

ALTER TABLE `leaddata` CHANGE `leaddate` `leaddate` DATETIME NULL DEFAULT NULL;

ALTER TABLE `leaddata` ADD `country` ENUM('India','United States') NOT NULL DEFAULT 'India' AFTER `status`;

COMMIT;

/*DB CHANGES MADE ON 18-01-2022 */
ALTER TABLE `leaddata` ADD `accompanied_by_rh` ENUM('No','Yes') NULL AFTER `country`, ADD `accompanied_by_sh` ENUM('No','Yes') NULL AFTER `accompanied_by_rh`, ADD `accompanied_by_others` ENUM('No','Yes') NULL AFTER `accompanied_by_sh`, ADD `accompanied_by_ph` VARCHAR(100) NULL AFTER `accompanied_by_others`;

ALTER TABLE leaddata DROP COLUMN accompanied_by_rh, DROP COLUMN accompanied_by_sh, DROP COLUMN accompanied_by_others, DROP COLUMN accompanied_by_ph;

ALTER TABLE `activitydata` ADD `accompanied_by_rh` ENUM('No','Yes') NULL AFTER `link_to`, ADD `accompanied_by_sh` ENUM('No','Yes') NULL AFTER `accompanied_by_rh`, ADD `accompanied_by_others` ENUM('No','Yes') NULL AFTER `accompanied_by_sh`, ADD `accompanied_by_ph` VARCHAR(100) NULL AFTER `accompanied_by_others`;

/*DB CHANGES MADE ON 23-01-2022 */
ALTER TABLE `leaddata` ADD `is_opportunity` ENUM('0','1') NOT NULL AFTER `country`, ADD `opp_status` ENUM('WIN','Shelved','Lost','WIP') NULL DEFAULT NULL AFTER `is_opportunity`, ADD `expected_date_of_closure` DATE NULL DEFAULT NULL AFTER `opp_status`, ADD `order_closed_date` DATE NULL DEFAULT NULL AFTER `expected_date_of_closure`, ADD `expected_value` INT NULL DEFAULT NULL AFTER `order_closed_date`, ADD `closed_value` INT NULL DEFAULT NULL AFTER `expected_value`, ADD `remarks` TEXT NULL DEFAULT NULL AFTER `closed_value`;

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Lead Converted Into Opportunity', CURRENT_TIMESTAMP);

ALTER TABLE `leaddata` ADD `rm_remarks` TEXT NULL AFTER `remarks`;

/*DB CHANGES MADE ON 24-01-2022 */
ALTER TABLE `activitydata` ADD `followup_date` DATE NULL AFTER `accompanied_by_ph`;

/*DB CHANGES MADE ON 25-01-2022 */
ALTER TABLE `activitydata` ADD `lead_status` VARCHAR(10) NULL AFTER `followup_date`, ADD `opportunity_status` VARCHAR(100) NULL AFTER `lead_status`;

/*DB CHANGES MADE ON 26-01-2022 */
ALTER TABLE `activitydata` CHANGE `from_date` `from_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `activitydata` CHANGE `to_date` `to_date` DATETIME NULL DEFAULT NULL;

/*DB CHANGES MADE ON 28-01-2022 */
INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Email', CURRENT_TIMESTAMP);

/*DB CHANGES MADE ON 08-02-2022 */
ALTER TABLE `sourcevalue` ADD `source_type_id` INT NOT NULL AFTER `id`;

/*DB CHANGES MADE ON 12-02-2022 */
RENAME TABLE `activitydata` TO `activities`;

ALTER TABLE `activities` CHANGE `activitydata` `activity_details` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

RENAME TABLE `leaddata` TO `leads`;

ALTER TABLE `leads` CHANGE `firstprefix` `salutation` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `firstname` `first_name` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `lasname` `last_name` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `mobilenumber` `contact_number` VARCHAR(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `emailid` `email_id` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `compittorname` `competitor_name` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `accountype` `account_type` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `ndustrytype` `industry_type` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `desgnnaion` `designation` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `salesperson` `sales_person` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `leaddetails` `lead_details` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `sourcetype` `source_type` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `sourcevalue` `source_value` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `producttype` `product_type` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `leadstatus` `lead_status` INT(2) NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `subproduct` `sub_product` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `paymenmtype` `payment_type` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `channelparner` `channel_partner` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `channelcompetitor` `channel_competitor` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `leadtype` `lead_type` INT(2) NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `addressdaa` `address` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `stattedatya` `state_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `citydata` `city_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `activiystatus` `activity_status` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `oportunitystatus` `opportunity_status` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `leaddate` `lead_date` DATETIME NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `createdy` `created_by` INT(11) NULL DEFAULT NULL AFTER `rm_remarks`;

ALTER TABLE `leads` CHANGE `leadclosedby` `lead_closed_by` INT(11) NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `leadclosedtime` `lead_closed_time` DATETIME NULL DEFAULT NULL;

ALTER TABLE `leads` CHANGE `creaedtme` `created_time` DATETIME NULL DEFAULT NULL AFTER `created_by`;

-- Position Changed
ALTER TABLE `leads` CHANGE `country` `country` ENUM('India','United States') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'India' AFTER `city_id`;
ALTER TABLE `leads` CHANGE `state_id` `state_id` INT(11) NULL DEFAULT NULL AFTER `city_id`;

/*DB CHANGES MADE ON 13-02-2022 */
INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Opportunity Deleted', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Price Negotiation', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Pending at Client Legal', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Pending at VCon Legal', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Proposal Shared', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Recharged or Billed', CURRENT_TIMESTAMP);

INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES (NULL, 'Activity Closed', CURRENT_TIMESTAMP);

-- Setting sourcevalue table
TRUNCATE sourcevalue;
INSERT INTO `sourcevalue` (`id`, `source_type_id`, `sourcevalue`, `createdby`, `createdtime`) VALUES
(1, 5, 'Self', 1, '2021-12-28 06:04:29'),
(2, 7, 'Contact Us', 1, '2021-12-30 03:13:05'),
(3, 7, 'Landing Page', 1, '2022-01-18 06:09:56'),
(4, 1, 'Regional Head', 1, '2022-01-18 06:11:00'),
(5, 1, 'Newspaper', 1, '2022-01-18 06:11:10'),
(6, 1, '3rd party Database', 1, '2022-01-18 06:11:35'),
(7, 1, 'Sales Head', 1, '2022-01-18 06:13:26'),
(8, 1, 'Employee', 1, '2022-01-18 06:13:34'),
(9, 8, 'Channel Partner', 1, '2022-01-31 01:03:25');

/*DB CHANGES MADE ON 18-02-2022 */
INSERT INTO `activitytype` (`id`, `activity_name`, `created`) VALUES 
(NULL, 'Technical Issues', CURRENT_TIMESTAMP), 
(NULL, 'Revisit', CURRENT_TIMESTAMP);

/*DB CHANGES MADE ON 23-02-2022 */
ALTER TABLE `leads` ADD `opportunity_creation_date` DATETIME NULL DEFAULT NULL AFTER `is_opportunity`;
ALTER TABLE `leads` ADD `opportunity_closure_date` DATETIME NULL AFTER `opportunity_creation_date`;

/*DB CHANGES MADE ON 07-03-2022 */
DELETE FROM leads WHERE id IN(1,2,3,4,5,6,10,12,13,14,15,17,18,19,20,21,36,171,218);
DELETE FROM activities WHERE leadid IN(1,2,3,4,5,6,10,12,13,14,15,17,18,19,20,21,36,171,218);

/*DB CHANGES MADE ON 09-03-2022 */
ALTER TABLE `activities` ADD `followup_time` TIME NULL AFTER `followup_date`;

UPDATE `activities` SET from_date = createdtime WHERE from_date is null;

/*DB CHANGES MADE ON 25-03-2022 */
/* ORDER CLOSED VALUE UPDATED*/
UPDATE leads SET closed_value = 11500 WHERE id = 357;
UPDATE leads SET closed_value = 40000 WHERE id = 500;
UPDATE leads SET closed_value = 15000 WHERE id = 79;
UPDATE leads SET closed_value = 10000 WHERE id = 467;
UPDATE leads SET closed_value = 11000 WHERE id = 48;
UPDATE leads SET closed_value = 11000 WHERE id = 50;
UPDATE leads SET closed_value = 11000 WHERE id = 207;
UPDATE leads SET closed_value = 5000 WHERE id = 221;
UPDATE leads SET closed_value = 50000 WHERE id = 244;
UPDATE leads SET closed_value = 50000 WHERE id = 786;
UPDATE leads SET closed_value = 12000 WHERE id = 812;
UPDATE leads SET closed_value = 1000 WHERE id = 849;
UPDATE leads SET closed_value = 12500 WHERE id = 7;

/*DB CHANGES MADE ON 29-03-2022 */
/* Function Created for Proper Case */
DELIMITER $$
CREATE FUNCTION `proper_case`(str varchar(128)) RETURNS varchar(128) CHARSET utf8
BEGIN
DECLARE n, pos INT DEFAULT 1;
DECLARE sub, proper VARCHAR(128) DEFAULT '';

if length(trim(str)) > 0 then
    WHILE pos > 0 DO
        set pos = locate(' ',trim(str),n);
        if pos = 0 then
            set sub = lower(trim(substr(trim(str),n)));
        else
            set sub = lower(trim(substr(trim(str),n,pos-n)));
        end if;

        set proper = concat_ws(' ', proper, concat(upper(left(sub,1)),substr(sub,2)));
        set n = pos + 1;
    END WHILE;
end if;

RETURN trim(proper);
END$$
DELIMITER ;

UPDATE leads set organisation = proper_case(organisation);
UPDATE leads set first_name = proper_case(first_name);
UPDATE leads set last_name = proper_case(last_name);
UPDATE leads set channel_competitor = proper_case(channel_competitor);

ALTER TABLE `leads` ADD `annual_budget` INT NULL AFTER `closed_value`;

ALTER TABLE `leads` ADD `opp_status2` ENUM('Win','WIP','Lost','Shelved') NULL AFTER `opp_status`;

UPDATE `leads` SET opp_status2 = CASE WHEN opp_status = 'WIN' THEN 'Win' ELSE opp_status END;

ALTER TABLE `leads` DROP COLUMN opp_status;

ALTER TABLE `leads` CHANGE `opp_status2` `opp_status` ENUM('Win','WIP','Lost','Shelved') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

