/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : vueAdmin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 29/11/2019 18:52:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for vdl_admin
-- ----------------------------
DROP TABLE IF EXISTS `vdl_admin`;
CREATE TABLE `vdl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `user_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '账号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员登录密码',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员真实姓名',
  `time` datetime DEFAULT NULL COMMENT '添加时间',
  `tel` varchar(11) DEFAULT NULL COMMENT '电话',
  `headimg` varchar(255) DEFAULT './upload/admin/admin.jpg',
  `type` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '管理员类型',
  `type_id` int(11) DEFAULT NULL COMMENT 'id',
  `session_id` varchar(255) DEFAULT NULL COMMENT '最新一次登录session_id',
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of vdl_admin
-- ----------------------------
BEGIN;
INSERT INTO `vdl_admin` VALUES (1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '2019-11-20 13:12:23', '18200213228', './upload/admin/1553950868.jpg', 'admin', NULL, 'mka58vmvg5pejkjukp04vcjod09ihrj6', NULL);
INSERT INTO `vdl_admin` VALUES (106, 79, 'qqqqqq', '96e79218965eb72c92a549dd5a330112', '张三', '2019-09-18 16:39:21', '18200213229', './upload/admin/2019-11-27/20191127184916_.jpeg', 'admin', NULL, NULL, NULL);
INSERT INTO `vdl_admin` VALUES (111, 79, 'admins', '96e79218965eb72c92a549dd5a330112', '测试', '2019-11-25 17:55:10', '18200213225', './upload/admin/2019-11-25/1574675682.jpeg', 'admin', NULL, NULL, NULL);
INSERT INTO `vdl_admin` VALUES (112, 87, 'test', 'e10adc3949ba59abbe56e057f20f883e', '张二', '2019-11-26 17:00:00', '18200213239', './upload/admin/2019-11-26/1574759830.jpg', 'admin', NULL, NULL, NULL);
INSERT INTO `vdl_admin` VALUES (116, 88, 'qdvdl', 'e10adc3949ba59abbe56e057f20f883e', '测试', '2019-11-27 12:37:06', '18200213245', './upload/admin/2019-11-27/20191127123628_.jpeg', 'admin', NULL, NULL, NULL);
INSERT INTO `vdl_admin` VALUES (114, 87, 'sssdd', '96e79218965eb72c92a549dd5a330112', '张三三', '2019-11-26 17:13:26', '18200213298', './upload/admin/2019-11-27/20191127114808_.jpeg', 'admin', NULL, NULL, NULL);
INSERT INTO `vdl_admin` VALUES (115, 87, 'aaaaaa', '96e79218965eb72c92a549dd5a330112', '张无', '2019-11-26 17:23:47', '18200213290', './upload/admin/2019-11-27/20191127163508_.jpeg', 'admin', NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for vdl_article
-- ----------------------------
DROP TABLE IF EXISTS `vdl_article`;
CREATE TABLE `vdl_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文章标题',
  `creater` varchar(20) NOT NULL DEFAULT '' COMMENT '创建人',
  `issuer` varchar(20) NOT NULL DEFAULT '' COMMENT '发布人',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `issuer_time` datetime DEFAULT NULL COMMENT '发布时间',
  `state` varchar(3) NOT NULL DEFAULT '' COMMENT 'ok，on（已发布，未发布）',
  `content` longtext COMMENT '文章内容',
  `type` varchar(20) DEFAULT NULL COMMENT '自定义区分文章类型',
  `imgurl` varchar(255) DEFAULT NULL COMMENT '图片封面',
  `describe` varchar(255) DEFAULT NULL COMMENT '文章摘要，描述',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键词',
  `classify_one` varchar(11) DEFAULT NULL COMMENT '1分类id',
  `classify_two` varchar(11) DEFAULT NULL COMMENT '2分类id',
  `classify_three` varchar(11) DEFAULT NULL COMMENT '3分类id',
  `update_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COMMENT='图文，文章，协议表';

-- ----------------------------
-- Records of vdl_article
-- ----------------------------
BEGIN;
INSERT INTO `vdl_article` VALUES (251, '12313', '张三', '', '2019-11-28 18:39:22', NULL, 'on', '<p>3123123</p>', NULL, './upload/active/2019-11-28/1574937560.png', '1231231', '123123', NULL, NULL, NULL, '2019-11-28 18:39:22');
INSERT INTO `vdl_article` VALUES (250, '213', '张三', '张三', '2019-11-25 16:27:09', '2019-11-25 16:27:09', 'ok', '<p>1231231231231</p>', NULL, './upload/active/2019-11-25/1574670428.jpg', '123123213', '13123', NULL, NULL, NULL, '2019-11-25 16:27:09');
INSERT INTO `vdl_article` VALUES (249, '12313', '张三', '张三', '2019-11-25 16:26:55', '2019-11-25 16:26:55', 'ok', '<p>312312312321321</p>', NULL, './upload/active/2019-11-25/1574670413.jpg', '12312312312312312', '123213', NULL, NULL, NULL, '2019-11-25 16:26:55');
INSERT INTO `vdl_article` VALUES (248, '1231', '张三', '张三', '2019-11-25 16:26:42', '2019-11-25 16:26:42', 'ok', '<p>3123123</p>', NULL, './upload/active/2019-11-25/1574670401.jpeg', '312312312312', '31312', NULL, NULL, NULL, '2019-11-25 16:26:42');
INSERT INTO `vdl_article` VALUES (247, '12313123', '张三', '张三', '2019-11-25 14:10:32', '2019-11-25 14:10:32', 'ok', '<p>31231231</p>', NULL, './upload/active/2019-11-25/1574662229.jpg', '312312312312321', '1312312', NULL, NULL, NULL, '2019-11-25 14:10:32');
INSERT INTO `vdl_article` VALUES (245, '啥都发啥', '张三', '', '2019-11-21 19:04:16', NULL, 'on', '<p>啥都发啥阿斯顿发啥</p>', NULL, './upload/active/2019-11-21/1574334254.png', '啥地方', '啥都发啥', NULL, NULL, NULL, '2019-11-21 19:04:16');
INSERT INTO `vdl_article` VALUES (244, '啥都发啥地方', '张三', '张三', '2019-11-21 19:04:00', '2019-11-21 19:04:00', 'ok', '<p>都发啥阿斯顿 v啥都发啥</p>', NULL, './upload/active/2019-11-21/1574334238.jpeg', '12是d f gv f d sa f sa', '啥地方', NULL, NULL, NULL, '2019-11-21 19:04:00');
INSERT INTO `vdl_article` VALUES (246, '12313123', '张三', '', '2019-11-25 14:10:16', NULL, 'on', '<p>12312312312321</p>', NULL, './upload/active/2019-11-25/1574662213.jpeg', '12312312312', '123123', NULL, NULL, NULL, '2019-11-25 14:10:16');
INSERT INTO `vdl_article` VALUES (242, '1233123123112312312', '张三', '张三', '2019-11-21 15:00:21', '2019-11-21 18:27:22', 'ok', '<p>中欧测试机制阿斯顿发啥地方阿斯顿发啥地方阿斯顿发啥地方啥都发啥</p>', NULL, './upload/active/2019-11-21/1574319620.jpg', '123123123123321312312312312321', '123123123123123123', NULL, NULL, NULL, '2019-11-21 18:00:17');
INSERT INTO `vdl_article` VALUES (233, '中国3123123213', '张三', '张三', '2019-11-13 11:07:43', '2019-11-28 18:39:11', 'ok', '<p>啥都发啥发啥都发啥阿斯顿发321312</p>', NULL, './upload/active/2019-11-25/1574670440.jpeg', '31231', '1231', NULL, NULL, NULL, '2019-11-28 18:39:11');
INSERT INTO `vdl_article` VALUES (236, '231', '张三', '张三', '2019-11-21 14:49:21', '2019-11-21 14:49:21', 'ok', '<p>3123123123</p>', NULL, './upload/active/2019-11-21/1574318632.jpeg', '12312312', '3123123', NULL, NULL, NULL, '2019-11-21 14:49:21');
INSERT INTO `vdl_article` VALUES (237, '1231231231', '张三', '张三', '2019-11-21 14:50:12', '2019-11-21 14:50:12', 'ok', '<p>231231231231231</p>', NULL, './upload/active/2019-11-21/1574319008.jpeg', '12312312311231231', '31231233123213', NULL, NULL, NULL, '2019-11-21 14:50:12');
INSERT INTO `vdl_article` VALUES (238, '1231231231', '张三', '张三', '2019-11-21 14:50:12', '2019-11-21 14:50:12', 'ok', '<p>231231231231231</p>', NULL, './upload/active/2019-11-21/1574319008.jpeg', '12312312311231231', '31231233123213', NULL, NULL, NULL, '2019-11-21 14:50:12');
INSERT INTO `vdl_article` VALUES (239, '123', '张三', '', '2019-11-21 14:50:31', NULL, 'on', '<p>312312312123123123123123</p>', NULL, './upload/active/2019-11-21/1574319029.jpg', '312312312', '12312312', NULL, NULL, NULL, '2019-11-21 14:50:31');
INSERT INTO `vdl_article` VALUES (240, '123', '张三', '', '2019-11-21 14:58:27', NULL, 'on', '<p>32131231231231</p>', NULL, './upload/active/2019-11-21/1574319505.jpeg', '312312312312', '12312312', NULL, NULL, NULL, '2019-11-21 14:58:27');
COMMIT;

-- ----------------------------
-- Table structure for vdl_auth
-- ----------------------------
DROP TABLE IF EXISTS `vdl_auth`;
CREATE TABLE `vdl_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '管理地址名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '管理导航名称',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序值',
  `icon` varchar(255) DEFAULT '' COMMENT '小图标',
  `show` varchar(2) DEFAULT '2' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=353 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of vdl_auth
-- ----------------------------
BEGIN;
INSERT INTO `vdl_auth` VALUES (326, 'admin', '系统管理', 0, 0, 'el-icon-s-platform', '2');
INSERT INTO `vdl_auth` VALUES (327, 'admin', '管理员', 326, 0, 'el-icon-user-solid', '2');
INSERT INTO `vdl_auth` VALUES (328, 'role', '角色管理', 326, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (329, 'addAdmin', '添加', 327, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (330, 'editAdmin', '编辑', 327, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (331, 'delAdmin', '删除', 327, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (332, 'addRole', '添加', 328, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (333, 'editRole', '编辑', 328, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (334, 'delRole', '删除', 328, 0, 'el-icon-s-check', '2');
INSERT INTO `vdl_auth` VALUES (335, 'setAdmin', '系统设置', 326, 0, 'el-icon-setting', '2');
INSERT INTO `vdl_auth` VALUES (336, 'auth', '栏目管理', 326, 0, 'el-icon-s-operation', '2');
INSERT INTO `vdl_auth` VALUES (337, 'article', '内容管理', 0, 1, 'el-icon-user', '2');
INSERT INTO `vdl_auth` VALUES (338, 'article', '文章管理', 337, 0, 'sdfds', '2');
INSERT INTO `vdl_auth` VALUES (340, 'articleAdd', '添加', 338, 0, 'sdfsdfsdf', '2');
INSERT INTO `vdl_auth` VALUES (341, 'articleEdit', '编辑', 338, 0, 'qweqqweq', '2');
INSERT INTO `vdl_auth` VALUES (342, 'articleDel', '删除', 338, 0, 'qwqehgfh', '2');
INSERT INTO `vdl_auth` VALUES (343, 'classify', '分类管理', 337, 0, 'qweqeqweq', '2');
INSERT INTO `vdl_auth` VALUES (344, 'classifyAdd', '添加', 343, 0, 'sfsafdasfsadfasfdas', '2');
INSERT INTO `vdl_auth` VALUES (345, 'classifyEdit', '编辑', 343, 0, 'fasdfasfdasfdasf', '2');
INSERT INTO `vdl_auth` VALUES (346, 'classifyDel', '删除', 343, 0, 'fasdfasfdas', '2');
INSERT INTO `vdl_auth` VALUES (347, 'dome', 'Demo', 0, 0, 'el-icon-s-grid', '2');
INSERT INTO `vdl_auth` VALUES (348, 'demoForm', 'Form配置', 347, 0, 'el-icon-menu', '2');
INSERT INTO `vdl_auth` VALUES (349, 'demoUpload', '图片处理', 347, 0, 'el-icon-menu', '2');
INSERT INTO `vdl_auth` VALUES (350, 'demoTable', '表格数据', 347, 0, 'el-icon-menu', '2');
INSERT INTO `vdl_auth` VALUES (351, 'dpage', '分页', 347, 0, 'el-icon-menu', '2');
INSERT INTO `vdl_auth` VALUES (352, 'dimExport', '导入导出', 347, 0, 'el-icon-upload2', '2');
COMMIT;

-- ----------------------------
-- Table structure for vdl_log
-- ----------------------------
DROP TABLE IF EXISTS `vdl_log`;
CREATE TABLE `vdl_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `details` longtext NOT NULL COMMENT '操作详情',
  `time` datetime NOT NULL COMMENT '操作时间',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '操作类型',
  `ip` varchar(255) DEFAULT NULL COMMENT '操作ip',
  `name` varchar(25) DEFAULT NULL COMMENT '操作人姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2540 DEFAULT CHARSET=utf8 COMMENT='日志';

-- ----------------------------
-- Records of vdl_log
-- ----------------------------
BEGIN;
INSERT INTO `vdl_log` VALUES (2095, '管理员【张三】操作管理员登录,密码错误！', '2019-10-14 13:45:28', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2096, '管理员【张三】操作管理员登录,登录成功！', '2019-10-14 13:45:40', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2097, '管理员【张三】操作管理员登录,登录成功！', '2019-10-14 15:55:57', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2539, '管理员【张三】操作管理员登录,登录成功！', '2019-11-29 10:27:48', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2538, '管理员【张三】操作添加文章,《12313》添加成功！', '2019-11-28 18:39:22', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2537, '管理员【张三】操作修改文章,《中国3123123213》修改成功！', '2019-11-28 18:39:11', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2536, '管理员【张三】操作管理员登录,登录成功！', '2019-11-28 18:16:36', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2535, '管理员【张三】操作管理员登录,登录成功！', '2019-11-28 12:36:37', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2534, '管理员【张三】操作编辑角色,添加成功！', '2019-11-28 10:52:51', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2533, '管理员【张三】操作管理员登录,登录成功！', '2019-11-28 10:47:51', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2532, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 18:46:52', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2531, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 18:41:33', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2530, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 18:40:59', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2529, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 18:40:33', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2528, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 17:41:03', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2527, '管理员【张无】操作管理员登录,登录成功！', '2019-11-27 16:34:31', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2526, '管理员【张无】操作管理员登录,登录成功！', '2019-11-27 16:24:57', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2525, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 16:24:23', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2524, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 16:21:35', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2523, '管理员【张无】操作管理员登录,登录成功！', '2019-11-27 16:20:05', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2522, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 16:15:01', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2521, '管理员【张三】操作删除角色,删除成功！', '2019-11-27 16:12:41', '删除角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2520, '管理员【张三】操作删除角色,删除成功！', '2019-11-27 16:12:39', '删除角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2519, '管理员【张三】操作添加角色,添加成功！', '2019-11-27 16:09:16', '添加角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2518, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 16:05:25', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2517, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 16:05:17', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2516, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:57:41', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2515, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:57:36', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2514, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:57:32', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2513, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:56:21', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2512, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:55:31', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2511, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:55:26', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2510, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:55:21', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2509, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:55:16', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2508, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:53:45', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2507, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:53:33', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2506, '管理员【张三】操作编辑角色,添加成功！', '2019-11-27 15:53:30', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2505, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 15:39:51', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2504, '管理员【张无】操作管理员登录,登录成功！', '2019-11-27 15:39:25', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2503, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 12:40:43', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2502, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 12:39:13', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2501, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 12:38:45', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2500, '管理员【张无】操作管理员登录,登录成功！', '2019-11-27 12:37:51', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2499, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:37:34', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2498, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:37:17', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2497, '管理员【张三】操作添加管理员,添加成功！', '2019-11-27 12:37:06', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2496, '管理员【张三】操作添加管理员,手机号不能重复使用，请使用其他手机号！', '2019-11-27 12:37:01', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2495, '管理员【张三】操作添加管理员,手机号不能重复使用，请使用其他手机号！', '2019-11-27 12:36:51', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2494, '管理员【张三】操作添加管理员,账号已经存在，请使用其他账号！', '2019-11-27 12:36:32', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2493, '管理员【张三】操作删除管理员,删除成功！', '2019-11-27 12:35:52', '删除管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2492, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:31:07', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2491, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:31:05', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2490, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:31:03', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2489, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:31:00', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2488, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:28:09', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2487, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:28:02', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2486, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:27:58', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2485, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:27:56', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2484, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 12:02:03', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2483, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 11:48:59', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2482, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 11:48:09', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2481, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 11:46:53', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2480, '管理员【张三】操作修改管理员,修改成功！', '2019-11-27 11:46:35', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2479, '管理员【张三】操作添加角色,添加成功！', '2019-11-27 11:33:06', '添加角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2478, '管理员【张三】操作添加角色,添加成功！', '2019-11-27 11:32:29', '添加角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2477, '管理员【张三】操作管理员登录,登录成功！', '2019-11-27 10:47:59', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2476, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:55:16', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2475, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 18:44:36', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2474, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:43:39', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2473, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:42:59', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2472, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:40:57', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2471, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:38:36', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2470, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:37:11', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2469, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:35:02', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2468, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:07:06', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2467, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:03:29', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2466, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 18:01:55', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2465, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 18:01:50', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2464, '管理员【张三】操作编辑角色,添加成功！', '2019-11-26 18:01:31', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2463, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 18:01:14', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2462, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:49:12', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2461, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:48:48', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2460, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:48:45', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2459, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:48:40', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2458, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:47:57', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2457, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:43:46', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2456, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:39:19', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2455, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:39:04', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2454, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:38:48', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2453, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:38:10', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2452, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:37:54', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2451, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:33:59', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2450, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:33:38', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2449, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:33:33', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2448, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:33:23', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2447, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:33:18', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2446, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:32:58', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2445, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:32:35', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2444, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:32:16', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2443, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:31:58', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2442, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:31:53', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2441, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:31:36', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2440, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:31:19', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2439, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:31:02', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2438, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:30:33', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2437, '管理员【张无】操作管理员登录,密码错误！', '2019-11-26 17:30:28', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2436, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:30:10', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2435, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:26:53', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2434, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:26:16', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2433, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:25:42', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2432, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:25:20', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2431, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 17:25:04', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2430, '管理员【张无】操作管理员登录,登录成功！', '2019-11-26 17:24:36', '管理员登录', NULL, '张无');
INSERT INTO `vdl_log` VALUES (2429, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:24:15', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2428, '管理员【张三】操作添加管理员,添加成功！', '2019-11-26 17:23:47', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2427, '管理员【张三】操作添加管理员,手机号不能重复使用，请使用其他手机号！', '2019-11-26 17:23:41', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2426, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:23:05', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2425, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:22:04', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2424, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:21:48', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2423, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:19:59', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2422, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:19:49', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2421, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:19:35', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2420, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:19:33', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2419, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:18:38', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2418, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:17:24', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2417, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:17:20', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2416, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:17:17', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2415, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:17:12', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2414, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:17:00', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2413, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:16:42', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2412, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:16:15', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2411, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:15:07', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2410, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:15:04', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2409, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:14:58', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2408, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:14:38', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2407, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:14:35', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2406, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:14:30', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2405, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:14:22', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2404, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:13:44', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2403, '管理员【张三】操作添加管理员,添加成功！', '2019-11-26 17:13:26', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2402, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:06:08', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2401, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 17:06:02', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2400, '管理员【张三】操作添加管理员,添加成功！', '2019-11-26 17:05:58', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2399, '管理员【张三】操作添加管理员,添加成功！', '2019-11-26 17:00:00', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2398, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 16:58:18', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2397, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 16:58:16', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2396, '管理员【张三】操作修改管理员,修改成功！', '2019-11-26 16:58:08', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2395, '管理员【张三】操作管理员登录,登录成功！', '2019-11-26 11:51:40', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2394, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 18:12:37', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2393, '管理员【张三】操作管理员登录,密码错误！', '2019-11-25 18:12:25', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2392, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 18:11:30', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2391, '管理员【测试】操作管理员登录,登录成功！', '2019-11-25 17:55:33', '管理员登录', NULL, '测试');
INSERT INTO `vdl_log` VALUES (2390, '管理员【张三】操作添加管理员,添加成功！', '2019-11-25 17:55:10', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2389, '管理员【张三】操作添加管理员,手机号不能重复使用，请使用其他手机号！', '2019-11-25 17:54:58', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2388, '管理员【张三】操作添加管理员,账号已经存在，请使用其他账号！', '2019-11-25 17:54:48', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2387, '管理员【张三】操作添加管理员,账号已经存在，请使用其他账号！', '2019-11-25 17:54:43', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2386, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 17:35:17', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2385, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 17:33:03', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2384, '管理员【张三】操作删除管理员,删除成功！', '2019-11-25 17:26:21', '删除管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2383, '管理员【张三】操作删除管理员,删除成功！', '2019-11-25 17:26:19', '删除管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2382, '管理员【张三】操作删除管理员,删除成功！', '2019-11-25 17:26:13', '删除管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2381, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 17:10:47', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2380, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 17:02:27', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2379, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 16:57:18', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2378, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 16:56:32', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2377, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 16:55:13', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2376, '管理员【张三】操作修改文章,《中国3123123213》修改成功！', '2019-11-25 16:27:24', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2375, '管理员【张三】操作添加文章,《213》添加成功！', '2019-11-25 16:27:09', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2374, '管理员【张三】操作添加文章,《12313》添加成功！', '2019-11-25 16:26:55', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2373, '管理员【张三】操作添加文章,《1231》添加成功！', '2019-11-25 16:26:42', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2372, '管理员【张三】操作添加文章,《12313123》添加成功！', '2019-11-25 14:10:32', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2371, '管理员【张三】操作添加文章,《12313123》添加成功！', '2019-11-25 14:10:16', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2370, '管理员【张三】操作管理员登录,登录成功！', '2019-11-25 13:25:35', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2369, '管理员【张三】操作管理员登录,登录成功！', '2019-11-22 18:36:39', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2367, '管理员【张三】操作管理员登录,登录成功！', '2019-11-22 10:45:05', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2368, '管理员【张三】操作管理员登录,登录成功！', '2019-11-22 14:17:49', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2366, '管理员【张三】操作添加文章,《啥都发啥》添加成功！', '2019-11-21 19:04:16', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2365, '管理员【张三】操作添加文章,《啥都发啥地方》添加成功！', '2019-11-21 19:04:00', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2364, '管理员【张三】操作文章发布,《1233123123112312312》发布操作成功！', '2019-11-21 18:27:22', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2363, '管理员【张三】操作文章发布,《1233123123112312312》发布操作成功！', '2019-11-21 18:27:17', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2362, '管理员【张三】操作删除文章,删除成功！', '2019-11-21 18:27:15', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2361, '管理员【张三】操作删除文章,删除成功！', '2019-11-21 18:27:13', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2360, '管理员【张三】操作修改文章,《12331231231啥都发啥啥都发啥》修改成功！', '2019-11-21 18:25:13', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2359, '管理员【张三】操作修改文章,《12331231231》修改成功！', '2019-11-21 18:23:50', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2358, '管理员【张三】操作修改文章,《啥大发啥都发啥》修改成功！', '2019-11-21 18:23:45', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2357, '管理员【张三】操作修改文章,《啥大发啥都发啥》修改成功！', '2019-11-21 18:15:29', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2356, '管理员【张三】操作添加文章,《啥大发啥都发啥》添加成功！', '2019-11-21 18:15:24', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2355, '管理员【张三】操作修改文章,《12331231231》修改成功！', '2019-11-21 18:15:10', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2354, '管理员【张三】操作修改文章,《12331231231》修改成功！', '2019-11-21 18:00:41', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2353, '管理员【张三】操作修改文章,《12331231231》修改成功！', '2019-11-21 18:00:34', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2352, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 18:00:17', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2351, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 18:00:06', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2350, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 17:52:36', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2349, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 17:49:09', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2348, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 17:48:04', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2347, '管理员【张三】操作修改文章,《1233123123112312312》修改成功！', '2019-11-21 17:47:59', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2346, '管理员【张三】操作添加文章,《1233123123112312312》添加成功！', '2019-11-21 15:00:21', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2345, '管理员【张三】操作添加文章,《12331231231》添加成功！', '2019-11-21 15:00:04', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2344, '管理员【张三】操作添加文章,《123》添加成功！', '2019-11-21 14:58:27', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2343, '管理员【张三】操作添加文章,《123》添加成功！', '2019-11-21 14:50:31', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2342, '管理员【张三】操作添加文章,《1231231231》添加成功！', '2019-11-21 14:50:12', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2341, '管理员【张三】操作添加文章,《1231231231》添加成功！', '2019-11-21 14:50:12', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2340, '管理员【张三】操作添加文章,《231》添加成功！', '2019-11-21 14:49:21', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2339, '管理员【张三】操作编辑角色,添加成功！', '2019-11-21 10:49:18', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2338, '管理员【张三】操作管理员登录,登录成功！', '2019-11-21 10:48:00', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2337, '管理员【张三】操作管理员登录,登录成功！', '2019-11-20 10:22:54', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2336, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:43:31', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2335, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:36:11', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2334, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:36:06', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2333, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:35:59', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2332, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:34:23', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2331, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:20:09', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2330, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:19:58', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2329, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-19 15:19:52', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2328, '管理员【张三】操作管理员登录,登录成功！', '2019-11-19 14:37:48', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2327, '管理员【张三】操作删除文章,删除成功！', '2019-11-19 14:37:17', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2326, '管理员【张三】操作删除文章,删除成功！', '2019-11-19 14:32:49', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2324, '管理员【张三】操作编辑角色,添加成功！', '2019-11-18 17:48:29', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2325, '管理员【张三】操作管理员登录,登录成功！', '2019-11-19 11:14:06', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2323, '管理员【张三】操作修改管理员,修改成功！', '2019-11-18 17:39:05', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2322, '管理员【张三】操作管理员登录,登录成功！', '2019-11-18 17:38:52', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2321, '管理员【张三】操作编辑角色,添加成功！', '2019-11-18 17:38:21', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2319, '管理员【张三】操作编辑角色,添加成功！', '2019-11-18 17:22:34', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2320, '管理员【张三】操作编辑角色,添加成功！', '2019-11-18 17:38:15', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2318, '管理员【张三】操作管理员登录,登录成功！', '2019-11-18 11:19:31', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2317, '管理员【张三】操作管理员登录,登录成功！', '2019-11-18 11:12:54', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2316, '管理员【张三】操作管理员登录,登录成功！', '2019-11-14 10:51:33', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2315, '管理员【张三】操作添加文章,《3123safasdfasfas》添加成功！', '2019-11-13 18:05:39', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2314, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 17:42:21', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2313, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 17:37:04', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2312, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 17:21:38', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2311, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 17:01:11', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2310, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 17:01:07', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2309, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 17:00:30', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2308, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 17:00:30', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2307, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 17:00:28', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2306, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 16:54:39', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2305, '管理员【张三】操作修改文章,《1231啥发啥发啥啥地方》修改成功！', '2019-11-13 13:25:52', '修改文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2304, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 12:09:50', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2303, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:49', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2302, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:49', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2301, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 12:09:48', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2300, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 12:09:47', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2299, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:24', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2298, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 12:09:23', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2297, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:23', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2296, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:21', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2295, '管理员【张三】操作文章发布,《中国3123123213》发布操作成功！', '2019-11-13 12:09:20', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2294, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:09:18', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2293, '管理员【张三】操作文章发布,《1231啥发啥发啥啥地方》发布操作成功！', '2019-11-13 12:07:46', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2292, '管理员【张三】操作添加文章,《1231啥发啥发啥啥地方》添加成功！', '2019-11-13 11:30:44', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2291, '管理员【张三】操作添加文章,《中国3123123213》添加成功！', '2019-11-13 11:07:43', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2290, '管理员【张三】操作删除文章,删除成功！', '2019-11-13 11:07:32', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2289, '管理员【张三】操作添加文章,《测试我们的文章测试》添加成功！', '2019-11-13 10:36:38', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2288, '管理员【张三】操作管理员登录,登录成功！', '2019-11-13 10:35:33', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2287, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 15:20:18', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2286, '管理员【张三】操作管理员登录,登录成功！', '2019-11-07 15:04:44', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2285, '管理员【张三】操作添加文章,《这是仪表测试文章》添加成功！', '2019-11-07 14:54:16', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2284, '管理员【张三】操作编辑角色,添加成功！', '2019-11-07 14:49:39', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2283, '管理员【张三】操作编辑角色,添加成功！', '2019-11-07 14:49:34', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2282, '管理员【张三】操作修改管理员,修改成功！', '2019-11-07 14:41:56', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2281, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:27', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2280, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:26', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2279, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:25', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2278, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:24', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2277, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:23', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2276, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:23', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2275, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:22', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2274, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:21', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2273, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:21', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2272, '管理员【张三】操作删除文章,删除成功！', '2019-11-07 14:37:18', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2271, '管理员【张三】操作管理员登录,登录成功！', '2019-11-07 14:36:00', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2270, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:24:21', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2269, '管理员【张三】操作添加文章,《123131231》添加成功！', '2019-10-17 12:24:17', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2268, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:24:00', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2267, '管理员【张三】操作添加文章,《123133123123123》添加成功！', '2019-10-17 12:23:51', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2266, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:23:10', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2265, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:23:09', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2264, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:23:08', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2263, '管理员【张三】操作删除文章,删除成功！', '2019-10-17 12:23:07', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2262, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-17 12:22:57', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2261, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-17 12:22:55', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2260, '管理员【张三】操作文章发布,《23424423424234234》发布操作成功！', '2019-10-17 12:22:52', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2259, '管理员【张三】操作文章发布,《2434242423》发布操作成功！', '2019-10-17 12:22:51', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2258, '管理员【张三】操作文章发布,《2342342342342342》发布操作成功！', '2019-10-17 12:22:50', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2257, '管理员【张三】操作文章发布,《234243423423423423》发布操作成功！', '2019-10-17 12:22:49', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2256, '管理员【张三】操作文章发布,《213133123123123》发布操作成功！', '2019-10-17 12:22:48', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2255, '管理员【张三】操作文章发布,《3123123123123123》发布操作成功！', '2019-10-17 12:22:47', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2254, '管理员【张三】操作文章发布,《3131231231231231231》发布操作成功！', '2019-10-17 12:22:46', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2253, '管理员【张三】操作文章发布,《312312312312313212》发布操作成功！', '2019-10-17 12:22:45', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2252, '管理员【张三】操作文章发布,《213213123123123123》发布操作成功！', '2019-10-17 12:22:44', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2251, '管理员【张三】操作文章发布,《啥都发啥地方啥都发啥》发布操作成功！', '2019-10-17 12:22:43', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2250, '管理员【张三】操作文章发布,《啥都发大水发啥都发啥》发布操作成功！', '2019-10-17 12:22:41', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2249, '管理员【张三】操作文章发布,《啥都发啥都发了句哦啥的佛啊失败啥都发价阿斯顿发啥地》发布操作成功！', '2019-10-17 12:22:40', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2248, '管理员【张三】操作文章发布,《2342342342342342》发布操作成功！', '2019-10-17 12:22:29', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2247, '管理员【张三】操作文章发布,《234243423423423423》发布操作成功！', '2019-10-17 12:22:28', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2246, '管理员【张三】操作文章发布,《213133123123123》发布操作成功！', '2019-10-17 12:22:27', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2245, '管理员【张三】操作文章发布,《3123123123123123》发布操作成功！', '2019-10-17 12:22:26', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2244, '管理员【张三】操作文章发布,《3131231231231231231》发布操作成功！', '2019-10-17 12:22:26', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2243, '管理员【张三】操作文章发布,《312312312312313212》发布操作成功！', '2019-10-17 12:22:24', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2242, '管理员【张三】操作文章发布,《213213123123123123》发布操作成功！', '2019-10-17 12:22:24', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2241, '管理员【张三】操作文章发布,《啥都发大水发啥都发啥》发布操作成功！', '2019-10-17 12:22:23', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2240, '管理员【张三】操作文章发布,《啥都发啥地方啥都发啥》发布操作成功！', '2019-10-17 12:22:21', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2239, '管理员【张三】操作文章发布,《啥都发啥都发了句哦啥的佛啊失败啥都发价阿斯顿发啥地》发布操作成功！', '2019-10-17 12:22:21', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2238, '管理员【张三】操作文章发布,《啥都发啥都发了句哦啥的佛啊失败啥都发价阿斯顿发啥地》发布操作成功！', '2019-10-17 12:22:16', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2237, '管理员【张三】操作文章发布,《啥都发大水发啥都发啥》发布操作成功！', '2019-10-17 12:22:14', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2236, '管理员【张三】操作文章发布,《啥都发啥地方啥都发啥》发布操作成功！', '2019-10-17 12:22:14', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2235, '管理员【张三】操作文章发布,《213213123123123123》发布操作成功！', '2019-10-17 12:22:13', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2234, '管理员【张三】操作文章发布,《312312312312313212》发布操作成功！', '2019-10-17 12:22:12', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2233, '管理员【张三】操作文章发布,《213133123123123》发布操作成功！', '2019-10-17 12:22:11', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2232, '管理员【张三】操作文章发布,《3131231231231231231》发布操作成功！', '2019-10-17 12:22:11', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2231, '管理员【张三】操作文章发布,《3123123123123123》发布操作成功！', '2019-10-17 12:22:10', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2230, '管理员【张三】操作文章发布,《234243423423423423》发布操作成功！', '2019-10-17 12:22:09', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2229, '管理员【张三】操作文章发布,《2342342342342342》发布操作成功！', '2019-10-17 12:22:08', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2228, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-17 12:20:41', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2227, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-17 12:20:40', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2226, '管理员【张三】操作文章发布,《23424423424234234》发布操作成功！', '2019-10-17 12:20:40', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2225, '管理员【张三】操作文章发布,《2434242423》发布操作成功！', '2019-10-17 12:20:39', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2224, '管理员【张三】操作文章发布,《23424423424234234》发布操作成功！', '2019-10-17 12:20:35', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2223, '管理员【张三】操作文章发布,《2434242423》发布操作成功！', '2019-10-17 12:20:34', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2222, '管理员【张三】操作添加文章,《啥都发啥都发了句哦啥的佛啊失败啥都发价阿斯顿发啥地》添加成功！', '2019-10-17 12:20:18', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2221, '管理员【张三】操作添加文章,《啥都发啥地方啥都发啥》添加成功！', '2019-10-17 12:20:12', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2220, '管理员【张三】操作添加文章,《啥都发大水发啥都发啥》添加成功！', '2019-10-17 12:20:06', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2219, '管理员【张三】操作添加文章,《213213123123123123》添加成功！', '2019-10-17 12:17:15', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2218, '管理员【张三】操作添加文章,《312312312312313212》添加成功！', '2019-10-17 12:17:05', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2217, '管理员【张三】操作添加文章,《3131231231231231231》添加成功！', '2019-10-17 12:17:01', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2216, '管理员【张三】操作添加文章,《3123123123123123》添加成功！', '2019-10-17 12:16:57', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2215, '管理员【张三】操作添加文章,《213133123123123》添加成功！', '2019-10-17 12:16:55', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2214, '管理员【张三】操作添加文章,《234243423423423423》添加成功！', '2019-10-17 12:13:22', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2213, '管理员【张三】操作添加文章,《2342342342342342》添加成功！', '2019-10-17 12:13:06', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2212, '管理员【张三】操作添加文章,《2434242423》添加成功！', '2019-10-17 12:12:56', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2211, '管理员【张三】操作添加文章,《23424423424234234》添加成功！', '2019-10-17 12:12:53', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2210, '管理员【张三】操作管理员登录,登录成功！', '2019-10-17 10:33:37', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2209, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:57', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2208, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:56', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2207, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:55', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2206, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:54', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2205, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:53', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2204, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:52', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2203, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:51', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2202, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:50', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2201, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:49', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2200, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:49', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2199, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:46', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2198, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:45', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2197, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:44', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2196, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:43', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2195, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:41', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2194, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:40', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2193, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:38', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2192, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:25', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2191, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:16', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2190, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:14', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2189, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:12', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2188, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:10', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2187, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:32:07', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2186, '管理员【张三】操作文章发布,《啥都发啥都发啥都发啥地方啥都发啥》发布操作成功！', '2019-10-16 18:32:03', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2185, '管理员【张三】操作添加文章,《啥都发啥都发啥都发啥地方啥都发啥》添加成功！', '2019-10-16 18:32:01', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2184, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:53', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2183, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:52', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2182, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:51', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2181, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:50', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2180, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:47', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2179, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:31:46', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2178, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:31:45', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2177, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:31:44', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2176, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:31:43', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2175, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:41', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2174, '管理员【张三】操作文章发布,《啥都发啥地方》发布操作成功！', '2019-10-16 18:31:40', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2173, '管理员【张三】操作文章发布,《啥都发啥阿斯顿发》发布操作成功！', '2019-10-16 18:31:38', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2172, '管理员【张三】操作文章发布,《啥都发啥地方》发布操作成功！', '2019-10-16 18:31:37', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2171, '管理员【张三】操作文章发布,《啥发啥发啥都发啥》发布操作成功！', '2019-10-16 18:31:35', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2170, '管理员【张三】操作文章发布,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:31:33', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2169, '管理员【张三】操作文章发布,《啥都发啥地方》发布操作成功！', '2019-10-16 18:31:31', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2168, '管理员【张三】操作文章发布,《啥都发啥地方》发布操作成功！', '2019-10-16 18:31:30', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2167, '管理员【张三】操作文章发布,《啥都发啥阿斯顿发》发布操作成功！', '2019-10-16 18:31:24', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2166, '管理员【张三】操作添加文章,《啥发啥发啥都发啥》添加成功！', '2019-10-16 18:30:18', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2165, '管理员【张三】操作文章发布,《啥都发啥地方》发布操作成功！', '2019-10-16 18:30:00', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2164, '管理员【张三】操作文章发布,《啥都发啥阿斯顿发》发布操作成功！', '2019-10-16 18:30:00', '文章发布', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2163, '管理员【张三】操作发布文章操作,《啥都发啥地方》发布操作成功！', '2019-10-16 18:29:30', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2162, '管理员【张三】操作发布文章操作,《阿斯顿发啥地方》发布操作成功！', '2019-10-16 18:29:29', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2161, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:18', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2160, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:17', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2159, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:15', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2158, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:09', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2157, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:07', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2156, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:06', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2155, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:05', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2154, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:04', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2153, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:28:01', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2152, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:27:29', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2151, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:27:06', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2150, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:26:59', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2149, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:26:49', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2148, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:26:26', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2147, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:26:24', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2146, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:26:11', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2145, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:26:09', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2144, '管理员【张三】操作发布文章操作,发布操作成功！', '2019-10-16 18:26:01', '发布文章操作', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2143, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:18:04', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2142, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:17:44', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2141, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:17:36', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2140, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:17:17', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2139, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:17:16', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2138, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:17:15', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2137, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:16:53', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2136, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:16:51', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2135, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:16:45', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2134, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:15:35', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2133, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:32', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2132, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:31', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2131, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:25', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2130, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:23', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2129, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:11', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2128, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:08', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2127, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:15:06', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2126, '管理员【张三】操作删除文章,删除成功！', '2019-10-16 18:15:05', '删除文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2124, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:13:48', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2125, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:15:03', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2123, '管理员【张三】操作发布文章,发布成功！', '2019-10-16 18:13:44', '发布文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2122, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:13:42', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2121, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:10:53', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2120, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:07:55', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2119, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 18:05:36', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2117, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 13:15:46', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2118, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 13:16:47', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2116, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 13:15:33', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2115, '管理员【张三】操作添加管理员,添加成功！', '2019-10-16 11:57:31', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2114, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:24:50', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2113, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:24:33', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2112, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:20:15', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2111, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:15:07', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2110, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:14:54', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2109, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:10:53', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2108, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:10:43', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2107, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:09:53', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2106, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:09:29', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2104, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:09:09', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2105, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:09:21', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2103, '管理员【张三】操作添加文章,添加成功！', '2019-10-16 11:08:16', '添加文章', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2102, '管理员【张三】操作管理员登录,登录成功！', '2019-10-16 10:26:36', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2101, '管理员【张三】操作管理员登录,登录成功！', '2019-10-15 11:23:25', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2100, '管理员【张三】操作修改管理员,修改成功！', '2019-10-14 16:59:16', '修改管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2094, '管理员【张三】操作管理员登录,登录成功！', '2019-10-08 11:40:44', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2093, '管理员【张三】操作管理员登录,密码错误！', '2019-10-08 11:40:41', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2092, '管理员【张三】操作管理员登录,密码错误！', '2019-10-08 11:40:35', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2091, '管理员【张三】操作管理员登录,密码错误！', '2019-10-08 11:40:30', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2099, '管理员【张三】操作管理员登录,登录成功！', '2019-10-14 16:11:38', '管理员登录', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2098, '管理员【张三】操作编辑角色,添加成功！', '2019-10-14 16:03:01', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2088, '管理员【张三】操作编辑角色,添加成功！', '2019-09-29 11:17:30', '编辑角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2087, '管理员【张三】操作登录管理员,登录成功！', '2019-09-29 11:06:58', '登录管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2086, '管理员【张三】操作登录管理员,密码错误！', '2019-09-29 11:06:54', '登录管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2085, '管理员【张三】操作添加管理员,添加成功！', '2019-09-29 11:04:53', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2084, '管理员【张三】操作添加管理员,手机号不能重复使用，请使用其他手机号！', '2019-09-29 11:04:48', '添加管理员', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2083, '管理员【张三】操作添加角色,添加成功！', '2019-09-29 11:01:18', '添加角色', NULL, '张三');
INSERT INTO `vdl_log` VALUES (2082, '管理员【张三】操作添加角色,添加成功！', '2019-09-29 10:59:39', '添加角色', NULL, '张三');
COMMIT;

-- ----------------------------
-- Table structure for vdl_role
-- ----------------------------
DROP TABLE IF EXISTS `vdl_role`;
CREATE TABLE `vdl_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名称',
  `auth_id` varchar(10000) NOT NULL DEFAULT '' COMMENT '权限id',
  `remark` longtext COMMENT '角色备注',
  `time` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of vdl_role
-- ----------------------------
BEGIN;
INSERT INTO `vdl_role` VALUES (1, '管理员', '327,329,330,331,328,332,333,334,335,336,338', '系统最高权限', NULL);
INSERT INTO `vdl_role` VALUES (87, '角色名称', '338,327,340,328', '啥发啥', '2019-09-29 10:50:29');
INSERT INTO `vdl_role` VALUES (88, '添加角色', '338,340,341,342', '2313', '2019-09-29 11:01:18');
INSERT INTO `vdl_role` VALUES (89, '测试角色', '338,343,327', '测试', '2019-11-27 11:32:29');
INSERT INTO `vdl_role` VALUES (79, '操作管理员', '327,329,330,331,328,332,333,334,335,336,338,340,341,342,343,344,345,346,348,349,350,351,352', '是否发啥地方', '2019-09-23 11:15:19');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
