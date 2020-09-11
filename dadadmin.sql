/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : dadadmin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 11/09/2020 09:59:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for da_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `da_admin_group`;
CREATE TABLE `da_admin_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 1,
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `sort` int(11) NULL DEFAULT 0,
  `purviews` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `purviews_half` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `purviews_all` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of da_admin_group
-- ----------------------------
INSERT INTO `da_admin_group` VALUES (3, '2019-12-25 14:26:19', 1, 1, 'cc', 0, NULL, '', '');
INSERT INTO `da_admin_group` VALUES (4, '2019-12-25 14:27:50', 1, 1, 'cc', 0, NULL, '', '');
INSERT INTO `da_admin_group` VALUES (5, '2019-12-25 14:30:01', 1, 1, 'c4', 0, '0a932d34232f3a83a4628d9ee73430e2,93cdd06d6ee8700b5cea1ac1a977638c', '75fe1ac81afb38c4104ecb8ea8825d7f', '0a932d34232f3a83a4628d9ee73430e2,93cdd06d6ee8700b5cea1ac1a977638c,75fe1ac81afb38c4104ecb8ea8825d7f');
INSERT INTO `da_admin_group` VALUES (6, '2020-01-10 13:10:00', 1, 1, '444', 0, NULL, NULL, NULL);
INSERT INTO `da_admin_group` VALUES (7, '2020-01-10 13:10:15', 0, 1, '测试', 0, 'menu_statistics,giveback_statistics,consume_statistics,adminpurview/get_menu,::givebackStatistics/.*|dept/.*,::consumeStatistics/.*|dept/.*', '', 'menu_statistics,giveback_statistics,consume_statistics,adminpurview/get_menu,::givebackStatistics/.*|dept/.*,::consumeStatistics/.*|dept/.*');
INSERT INTO `da_admin_group` VALUES (8, '2020-01-10 14:20:03', 1, 1, 'ccccc', 0, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for da_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `da_admin_log`;
CREATE TABLE `da_admin_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  `deleted` tinyint(1) NULL DEFAULT 0,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `params` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `opt_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `opt_show_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `opt_id` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `opt_name`(`opt_name`) USING BTREE,
  INDEX `action`(`action`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of da_admin_log
-- ----------------------------
INSERT INTO `da_admin_log` VALUES (1, '2020-06-03 10:19:04', 0, '登录', '', 'admin', '超级管理员', 1);
INSERT INTO `da_admin_log` VALUES (2, '2020-06-04 08:27:20', 0, '登录', '', 'admin', '超级管理员', 1);
INSERT INTO `da_admin_log` VALUES (3, '2020-07-21 14:40:39', 0, '登录', '', 'admin', '超级管理员', 1);

-- ----------------------------
-- Table structure for da_admin_purview
-- ----------------------------
DROP TABLE IF EXISTS `da_admin_purview`;
CREATE TABLE `da_admin_purview`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 1,
  `sort` int(11) NULL DEFAULT 0,
  `type` tinyint(1) NULL DEFAULT 0,
  `menu_name` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `uri` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `pid` int(11) NULL DEFAULT 0,
  `ico` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `uri_md5` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of da_admin_purview
-- ----------------------------
INSERT INTO `da_admin_purview` VALUES (1, '2019-12-25 14:31:31', 0, 1, 3, 1, '角色组', '角色组', 'admin_group', 2, '', '0a932d34232f3a83a4628d9ee73430e2');
INSERT INTO `da_admin_purview` VALUES (2, '2019-12-25 14:40:34', 0, 1, 0, 1, '系统管理', '系统管理', 'menu_houtaiyonghu', 0, '', '75fe1ac81afb38c4104ecb8ea8825d7f');
INSERT INTO `da_admin_purview` VALUES (3, '2019-12-25 14:43:40', 0, 1, 0, 1, '权限节点', '权限节点', 'admin_purview', 2, '', '93cdd06d6ee8700b5cea1ac1a977638c');
INSERT INTO `da_admin_purview` VALUES (4, '2019-12-25 14:44:08', 0, 1, 2, 1, '用户', '用户', 'admin_user', 2, '', '3c85cdebade1c51cf64ca9f3c09d182d');
INSERT INTO `da_admin_purview` VALUES (8, '2020-01-17 10:48:03', 0, 1, 0, 0, '基础功能', '基础功能', 'jichugongneng', 0, '', '325842d6f84ac21fba95d5a391ef7fc7');
INSERT INTO `da_admin_purview` VALUES (9, '2020-01-17 10:49:45', 0, 1, 0, 0, '获取菜单', '获取菜单', 'adminpurview/get_menu', 8, '', '8a5449eff2c8c8224de26103339992b0');
INSERT INTO `da_admin_purview` VALUES (13, '2020-01-17 11:27:04', 0, 1, 0, 0, '系统管理', '系统管理', 'houtaiyonghuguanli', 0, '', 'af1b2a69398cdff4fbbbd88e5685434c');
INSERT INTO `da_admin_purview` VALUES (14, '2020-01-17 11:27:59', 0, 1, 0, 0, '角色组', '角色组', '::admingroup/.*|adminpurview/.*', 13, '', '1c3d736f5e6581f1f585145d7e238a8f');
INSERT INTO `da_admin_purview` VALUES (15, '2020-01-17 11:29:12', 0, 1, 0, 0, '用户', '用户', '::adminuser/.*', 13, '', '175bb4754f47066043b34aad4c9c6909');
INSERT INTO `da_admin_purview` VALUES (16, '2020-01-17 11:29:31', 0, 1, 0, 0, '权限节点', '权限节点', '::adminpurview/.*', 13, '', '1d315f4eaf3c2576d3fb8ffd757e03d8');
INSERT INTO `da_admin_purview` VALUES (19, '2020-09-11 09:11:20', 0, 1, 0, 1, '用户日志', '用户日志', 'admin_log', 2, '', '498e63362d2e44ce404b61955d0ac85f');

-- ----------------------------
-- Table structure for da_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `da_admin_user`;
CREATE TABLE `da_admin_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NULL DEFAULT 0,
  `user_name` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `show_name` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `group_id` int(11) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 1,
  `dept` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '部门如 0:1,2:4,6:7,8,9',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of da_admin_user
-- ----------------------------
INSERT INTO `da_admin_user` VALUES (1, '2018-06-26 12:27:19', 0, 'admin', '4297f44b13955235245b2497399d7a93', '超级管理员', 7, 1, '0:0:0:0');
INSERT INTO `da_admin_user` VALUES (2, '2020-01-10 15:06:31', 1, 'aa', '4297f44b13955235245b2497399d7a93', '3333', 6, 0, '');
INSERT INTO `da_admin_user` VALUES (3, '2020-01-10 15:55:21', 1, 'wdf', '22c276a05aa7c90566ae2175bcc2a9b0', 'wer', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (4, '2020-01-10 15:55:27', 1, 'wdf', '22c276a05aa7c90566ae2175bcc2a9b0', 'wer', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (5, '2020-01-10 15:57:33', 1, '12', '2e0aca891f2a8aedf265edf533a6d9a8', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (6, '2020-01-10 15:57:36', 1, '12', '2e0aca891f2a8aedf265edf533a6d9a8', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (7, '2020-01-10 16:05:34', 1, '12', '2e0aca891f2a8aedf265edf533a6d9a8', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (8, '2020-01-10 16:14:44', 1, '123', '202cb962ac59075b964b07152d234b70', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (9, '2020-01-10 16:18:05', 1, '123', '202cb962ac59075b964b07152d234b70', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (10, '2020-01-10 16:27:05', 1, '123', '202cb962ac59075b964b07152d234b70', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (11, '2020-01-10 16:27:14', 1, '123', '202cb962ac59075b964b07152d234b70', '123', 5, 1, '');
INSERT INTO `da_admin_user` VALUES (12, '2020-01-13 11:07:44', 1, 'cc1', '310dcbbf4cce62f762a2aaa148d556bd', 'cc1', 5, 1, '1:70101:17010710');
INSERT INTO `da_admin_user` VALUES (13, '2020-01-17 10:00:40', 1, 'cc1', '4297f44b13955235245b2497399d7a93', '112', 7, 1, '2016,2017,2019:1:0:0');

-- ----------------------------
-- Table structure for da_config
-- ----------------------------
DROP TABLE IF EXISTS `da_config`;
CREATE TABLE `da_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NULL DEFAULT NULL,
  `create_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NULL DEFAULT 0,
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `value` varchar(555) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1089 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
