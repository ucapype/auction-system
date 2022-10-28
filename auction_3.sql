/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : auction_3

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 26/11/2021 22:12:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for account
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account`  (
  `userId` int(8) NOT NULL AUTO_INCREMENT,
  `accountType` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `emailAddress` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `displayName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`userId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES (1, 'seller', 'test@example.com', '$2y$10$6Lc69l2HWpNGXdngUGg0JO5g.YlCvEIpyRShcSzAazpK4XM2sRXUu', 'test', 'test', '');
INSERT INTO `account` VALUES (2, 'seller', 'seller@example.com', '$2y$10$7ZyNMknhRMZaDjDxLwwTj.N66gUDVFQusrFFsrrLCtCzgOlLMUh2y', 'test', 'test', '');
INSERT INTO `account` VALUES (3, 'seller', 'test3@example.com', '$2y$10$n6Mc1pTlkQrN28ljLzdFl.0eK0.gl4d5sWM/WztULrbuRkMKhXuQK', 'test', 'test', '');
INSERT INTO `account` VALUES (4, 'seller', 'test2@example.com', '$2y$10$WNz5eZMVaT/EmLFRBVI52eRYZuSO9jBdm4my66GReyaQe4r3XkgmW', 'test2', 'test2', '');
INSERT INTO `account` VALUES (5, 'seller', 'test4@example.com', '$2y$10$XbeQXJMKMMkn0aj5TVsYW.euekg1LOCakw//KiZW3Lsx6LNsgWUe.', 'test4', 'test4', '');
INSERT INTO `account` VALUES (6, 'seller', 'test5@example.com', '$2y$10$jKW7gb1wrZGbisuHrr6tYefpfTjPJGo71PPv6H1qi/aQwFfLhZGSS', 'test5', 'test5', '');
INSERT INTO `account` VALUES (7, 'buyer', 'buyer@example.com', '$2y$10$s30OJMslckxPRJvLy8hJCOdsLaOnYcDtrrXu14AxEGwIYl5IpQO.a', 'buyer', 'buyer', '');
INSERT INTO `account` VALUES (8, 'buyer', 'test@buyer.com', '$2y$10$fkxLeQezkS1NvpfaDy892OVvPB9ekeI/03Cn.eHI.mphcFwxTFsmC', 'test', 'Buyer', '');

-- ----------------------------
-- Table structure for bid
-- ----------------------------
DROP TABLE IF EXISTS `bid`;
CREATE TABLE `bid`  (
  `bidId` int(8) NOT NULL AUTO_INCREMENT,
  `buyerId` int(8) NULL DEFAULT NULL,
  `bidPrice` decimal(10, 2) NULL DEFAULT NULL,
  `auctionId` int(8) NULL DEFAULT NULL,
  `bidTimeStamp` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`bidId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of bid
-- ----------------------------
INSERT INTO `bid` VALUES (5, 8, 133.00, 7, '2021-11-26 12:43:00');
INSERT INTO `bid` VALUES (3, 1, 126.00, 7, '2021-11-26 12:10:50');

-- ----------------------------
-- Table structure for itemauction
-- ----------------------------
DROP TABLE IF EXISTS `itemauction`;
CREATE TABLE `itemauction`  (
  `auctionId` int(8) NOT NULL AUTO_INCREMENT,
  `auctionTitle` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `startDate` datetime(0) NULL DEFAULT NULL,
  `endDate` datetime(0) NULL DEFAULT NULL,
  `auctionStatus` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `startingPrice` decimal(10, 2) NULL DEFAULT NULL,
  `reservePrice` decimal(10, 2) NULL DEFAULT NULL,
  `sellerId` int(8) NULL DEFAULT NULL,
  `buyerId` int(8) NULL DEFAULT NULL,
  `bidPrice` decimal(10, 2) NULL DEFAULT NULL,
  `itemDescription` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `itemCondition` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `images` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `category` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bid_number` int(8) NOT NULL DEFAULT 0,
  PRIMARY KEY (`auctionId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of itemauction
-- ----------------------------
INSERT INTO `itemauction` VALUES (3, 'test', '2021-11-10 15:02:00', '2021-11-10 20:59:00', 'Ended', 123.00, 123.00, 1, NULL, 123.00, 'test', NULL, NULL, 'all', 1);
INSERT INTO `itemauction` VALUES (4, '123', '2021-11-10 18:59:00', '2021-11-10 20:59:00', 'Ended', 123.00, 123.00, 3, NULL, 123.00, '123', NULL, NULL, 'tops', 1);
INSERT INTO `itemauction` VALUES (5, 'test2', '2021-11-16 01:36:00', '2021-11-16 04:36:00', 'Ended', 1231.00, 123.00, 3, NULL, 1231.00, 'test2', NULL, NULL, 'bottoms', 1);
INSERT INTO `itemauction` VALUES (6, 'test5', '2021-11-16 02:50:00', '2021-11-16 03:50:00', 'Ended', 123.00, 123.00, 6, NULL, 123.00, 'test5', NULL, NULL, 'dresses', 1);
INSERT INTO `itemauction` VALUES (7, 'abc', '2021-11-16 00:59:00', '2021-11-26 17:59:00', 'Active', 123.00, 123.00, 1, 8, 133.00, 'abc', NULL, NULL, 'shoes', 1);
INSERT INTO `itemauction` VALUES (8, 'expired', '2021-11-16 13:28:00', '2021-11-17 04:28:00', 'Ended', 1234.00, 1234.00, 1, NULL, 1234.00, 'expired', NULL, NULL, 'accessories', 1);
INSERT INTO `itemauction` VALUES (9, 'available', '2021-11-17 13:29:00', '2021-11-17 18:29:00', 'Cancelled', 3245.00, 3246.00, 1, NULL, 3245.00, 'available', NULL, NULL, 'shoes', 1);
INSERT INTO `itemauction` VALUES (10, 'test_time', '2021-11-16 13:39:00', '2021-11-19 13:39:00', 'Cancelled', 345.00, 4567.00, 1, NULL, 345.00, 'test_time', NULL, NULL, 'with', 1);
INSERT INTO `itemauction` VALUES (11, 'test_type_11', '2021-11-15 13:50:00', '2021-11-22 13:50:00', 'Cancelled', 234.00, 345.00, 1, NULL, 234.00, 'test type', NULL, NULL, 'with', 1);
INSERT INTO `itemauction` VALUES (12, 'test_type_12', '2021-11-15 13:50:00', '2021-11-22 13:50:00', 'Cancelled', 234.00, 345.00, 1, NULL, 234.00, 'test type', NULL, NULL, 'with', 1);
INSERT INTO `itemauction` VALUES (13, 'test_type_13', '2021-11-15 13:50:00', '2021-11-22 13:50:00', 'Cancelled', 234.00, 345.00, 1, NULL, 234.00, 'test type', NULL, NULL, 'with', 1);
INSERT INTO `itemauction` VALUES (14, 'test_display', '2021-11-19 13:05:00', '2021-11-20 13:05:00', 'Cancelled', 123.00, 2345.00, 1, NULL, 123.00, 'display', NULL, NULL, 'fill', 0);
INSERT INTO `itemauction` VALUES (15, 'test_display2', '2021-11-19 13:06:00', '2021-11-20 01:06:00', 'Cancelled', 234.00, 355.00, 1, NULL, 234.00, 'display2', NULL, NULL, 'with', 0);
INSERT INTO `itemauction` VALUES (16, 'test_display3', '2021-11-19 13:06:00', '2021-11-20 13:06:00', 'Cancelled', 234.00, 345.00, 1, NULL, 234.00, 'display3', NULL, NULL, 'with', 0);

-- ----------------------------
-- Table structure for watchlist
-- ----------------------------
DROP TABLE IF EXISTS `watchlist`;
CREATE TABLE `watchlist`  (
  `watch_list_id` int(8) NOT NULL AUTO_INCREMENT,
  `userId` int(8) NOT NULL,
  `auctionId` int(8) NOT NULL,
  PRIMARY KEY (`watch_list_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 25 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of watchlist
-- ----------------------------
INSERT INTO `watchlist` VALUES (12, 8, 13);
INSERT INTO `watchlist` VALUES (20, 7, 7);
INSERT INTO `watchlist` VALUES (18, 8, 11);
INSERT INTO `watchlist` VALUES (15, 8, 7);

SET FOREIGN_KEY_CHECKS = 1;
