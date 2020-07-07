/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : cms_qlsite

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-07-07 23:50:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tbl_sites
-- ----------------------------
DROP TABLE IF EXISTS `tbl_sites`;
CREATE TABLE `tbl_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_descript` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` text COLLATE utf8_unicode_ci NOT NULL,
  `footer` text COLLATE utf8_unicode_ci NOT NULL,
  `skype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cdate` int(11) DEFAULT NULL,
  `active_date` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `is_trash` tinyint(4) DEFAULT '0',
  `isactive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tbl_sites
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_users
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(300) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `gsecret` varchar(50) DEFAULT NULL,
  `isfa2` tinyint(4) DEFAULT '0',
  `isadmin` tinyint(4) DEFAULT '0',
  `cdate` int(11) DEFAULT NULL,
  `is_trash` tinyint(4) DEFAULT '0',
  `isactive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_users
-- ----------------------------
INSERT INTO `tbl_users` VALUES ('nxtuyen.pro@gmail.com', 'a25342213446980b5f750b37daed053ce347ec766265b78aa81d90c8217e5e39|cdf4a007e2b02a0c49fc9b7ccfbb8a10c644f635e1765dcf2a7ab794ddc7edac', 'Nguyễn Xuân Tuyền', '0936831277', 'nxtuyen.pro@gmail.com', '1', null, '1', '1', '1586660652', '0', '1');
INSERT INTO `tbl_users` VALUES ('tranviethiepdz@gmail.com', 'd93fc24a5f68f2d6621e2d3aff26b5600f38f1b6876ff04f0070b38a54b2d2f8|cdf4a007e2b02a0c49fc9b7ccfbb8a10c644f635e1765dcf2a7ab794ddc7edac', 'Trần Hiệp', '096.954.990', 'tranviethiepdz@gmail.com', '2', null, '0', '1', '1591860947', '0', '1');

-- ----------------------------
-- Table structure for tbl_user_group
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_group`;
CREATE TABLE `tbl_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `par_id` int(11) DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `intro` text COLLATE utf8_unicode_ci,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isadmin` int(11) DEFAULT '1',
  `isactive` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_active` (`isactive`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tbl_user_group
-- ----------------------------
INSERT INTO `tbl_user_group` VALUES ('1', '0', 'Origin', '', '1', '1', '1');
INSERT INTO `tbl_user_group` VALUES ('2', '1', 'Team công nghệ', '', '1_2', '1', '1');

-- ----------------------------
-- Table structure for tbl_user_login
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_login`;
CREATE TABLE `tbl_user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `isactive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx` (`isactive`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tbl_user_login
-- ----------------------------
INSERT INTO `tbl_user_login` VALUES ('1', 'tranviethiepdz@gmail.com', '1593529748', '1593529748', '1');
INSERT INTO `tbl_user_login` VALUES ('2', 'tranviethiepdz@gmail.com', '1593542323', '1593542323', '1');
INSERT INTO `tbl_user_login` VALUES ('3', 'tranviethiepdz@gmail.com', '1593542325', '1593542325', '1');
INSERT INTO `tbl_user_login` VALUES ('4', 'tranviethiepdz@gmail.com', '1593699816', '1593699816', '1');
INSERT INTO `tbl_user_login` VALUES ('5', 'tranviethiepdz@gmail.com', '1594054321', '1594054321', '1');
