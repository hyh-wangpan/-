/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50623
Source Host           : localhost:3306
Source Database       : wangpan

Target Server Type    : MYSQL
Target Server Version : 50623
File Encoding         : 65001

Date: 2016-11-26 22:46:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(40) NOT NULL,
  `size` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`,`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of file
-- ----------------------------
INSERT INTO `file` VALUES ('1', 'SYSTEM_FILE', '0');

-- ----------------------------
-- Table structure for userfile
-- ----------------------------
DROP TABLE IF EXISTS `userfile`;
CREATE TABLE `userfile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `parentid` int(11) unsigned NOT NULL,
  `fileid` int(11) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filetype` tinyint(3) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `state` tinyint(2) unsigned NOT NULL,
  `share` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fileid` (`fileid`) USING BTREE,
  KEY `filename` (`userid`,`filename`) USING BTREE,
  KEY `filetype` (`userid`,`filetype`) USING BTREE,
  KEY `parentid` (`userid`,`parentid`,`state`) USING BTREE,
  KEY `state` (`userid`,`state`) USING BTREE,
  KEY `share` (`userid`,`share`) USING BTREE,
  CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userfile
-- ----------------------------
INSERT INTO `userfile` VALUES ('1', '1', '1', '1', 'SYSTEM_DISK', '0', '0000-00-00 00:00:00', '0', '0');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `nickname` varchar(64) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `registered` datetime NOT NULL,
  `totalsize` bigint(20) unsigned NOT NULL,
  `usedsize` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`,`email`,`nickname`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING HASH,
  UNIQUE KEY `nikename` (`nickname`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'SYSTEM', '0', 'root', '', '0000-00-00 00:00:00', '0', '0');
