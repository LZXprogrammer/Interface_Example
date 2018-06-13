/*
Navicat MySQL Data Transfer

Source Server         : LZX
Source Server Version : 50719
Source Host           : 192.168.10.10:3306
Source Database       : ad_show_control_db

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-06-07 14:45:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for promote_ad_info
-- ----------------------------
DROP TABLE IF EXISTS `promote_ad_info`;
CREATE TABLE `promote_ad_info` (
  `ad_id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告唯一ID',
  `statue` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1 测试 2 上线',
  `country_code` char(10) COLLATE utf8_unicode_ci DEFAULT 'default' COMMENT '国家代码',
  `iconUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标 URL 地址',
  `imageUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片 URL 地址',
  `adImageUrl` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1200 * 628 图片资源 url',
  `bannerImageUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT 'banner 图片资源 url',
  `adTarget` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '打开广告的链接地址',
  `adStarRate` tinyint(4) NOT NULL DEFAULT '0' COMMENT '广告星级比率 1-5 ',
  `adTitle` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告标题',
  `adBody` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '广告描述信息',
  `callAction` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '按钮显示文本',
  `sourceType` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'down' COMMENT '资源类型',
  `update_time` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`ad_id`),
  UNIQUE KEY `ad_id` (`ad_id`),
  KEY `update_time` (`update_time`),
  KEY `adStarRate` (`adStarRate`),
  KEY `adTitle` (`adTitle`),
  KEY `sourceType` (`sourceType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='广告信息内容表';

-- ----------------------------
-- Table structure for promote_app_adunit_info
-- ----------------------------
DROP TABLE IF EXISTS `promote_app_adunit_info`;
CREATE TABLE `promote_app_adunit_info` (
  `ad_unit_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '版位ID',
  `ad_unit_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '版位名称',
  `app_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '应用名称',
  `package_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '包名',
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版位描述',
  `update_time` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`ad_unit_id`),
  UNIQUE KEY `pkg_ad_unit_id` (`package_name`,`ad_unit_id`),
  KEY `package_name` (`package_name`),
  KEY `ad_unit_id` (`ad_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='app和版位对应表';

-- ----------------------------
-- Table structure for promote_adunit_adinfo_relation
-- ----------------------------
DROP TABLE IF EXISTS `promote_adunit_adinfo_relation`;
CREATE TABLE `promote_adunit_adinfo_relation` (
  `ad_unit_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '版位ID',
  `ad_id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告唯一ID',
  PRIMARY KEY (`ad_unit_id`,`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='版位和广告关系表';
