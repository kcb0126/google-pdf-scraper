/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100133
 Source Host           : localhost:3306
 Source Schema         : db_pdf_scraper

 Target Server Type    : MySQL
 Target Server Version : 100133
 File Encoding         : 65001

 Date: 22/12/2018 01:29:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_documents
-- ----------------------------
DROP TABLE IF EXISTS `tb_documents`;
CREATE TABLE `tb_documents`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `format` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ex_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_documents
-- ----------------------------
INSERT INTO `tb_documents` VALUES (1, 'https://drive.google.com/file/d/1-Fmug3gSar6KKb0KsF4Glsvqfhuge4In/view', NULL, NULL);
INSERT INTO `tb_documents` VALUES (2, 'https://drive.google.com/file/d/1xJkR2JRYMm0gLcysdTPN3qd5RE7IFDoS/view', NULL, NULL);
INSERT INTO `tb_documents` VALUES (3, 'https://drive.google.com/file/d/17S7_MJ7p0hBgO3byt6bVfJPy-VYowNnv/view', NULL, NULL);

-- ----------------------------
-- Table structure for tb_ex_codes
-- ----------------------------
DROP TABLE IF EXISTS `tb_ex_codes`;
CREATE TABLE `tb_ex_codes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ex_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `begin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_ex_codes
-- ----------------------------
INSERT INTO `tb_ex_codes` VALUES (1, 'EX_Amount', 'Transfer of Ownership', 'Tax Map Reference');
INSERT INTO `tb_ex_codes` VALUES (2, 'EX_Amount', 'principal amount of', 'from Grantor');
INSERT INTO `tb_ex_codes` VALUES (3, 'EX_Block', 'BLOCK:', 'LOT:');
INSERT INTO `tb_ex_codes` VALUES (4, 'EX_Block', 'Block:', 'Assessed Owner:');
INSERT INTO `tb_ex_codes` VALUES (5, 'EX_Dated', 'This Deed is made on', 'BETWEEN');
INSERT INTO `tb_ex_codes` VALUES (6, 'EX_FLOOD_DATED', 'Date of Determination', 'Search Number');
INSERT INTO `tb_ex_codes` VALUES (7, 'EX_InstRef1Number', 'Book', 'Page');
INSERT INTO `tb_ex_codes` VALUES (8, 'EX_InstRef1Number', 'BOOK:', 'PAGE:');
INSERT INTO `tb_ex_codes` VALUES (9, 'EX_InstRef1SubNumber', 'PAGE:', 'Total');
INSERT INTO `tb_ex_codes` VALUES (10, 'EX_InstRef1SubNumber', 'Page', 'No. Pages');
INSERT INTO `tb_ex_codes` VALUES (11, 'EX_InstRef2Number', 'INSTRUMENT NUMBER', 'RECORDED');
INSERT INTO `tb_ex_codes` VALUES (12, 'EX_InstRef2Number', 'Control #', 'INST#');
INSERT INTO `tb_ex_codes` VALUES (13, 'EX_Legal', 'BEGINNING', 'BEGINNING');
INSERT INTO `tb_ex_codes` VALUES (14, 'EX_Lot', 'LOT:', 'STREET NUMBER & NAME:');
INSERT INTO `tb_ex_codes` VALUES (15, 'EX_Party1', 'The word \"Grantor” means', 'Guarantor');
INSERT INTO `tb_ex_codes` VALUES (16, 'EX_Party1', 'BETWEEN', 'whose address is');
INSERT INTO `tb_ex_codes` VALUES (17, 'EX_Party2', 'The word \"Lender\" means', '.');
INSERT INTO `tb_ex_codes` VALUES (18, 'EX_Party2', 'AND', 'whose');
INSERT INTO `tb_ex_codes` VALUES (19, 'EX_Results', 'Findings:', 'Dated:');
INSERT INTO `tb_ex_codes` VALUES (20, 'EX_Statusdate', 'Date :', 'Time :');
INSERT INTO `tb_ex_codes` VALUES (21, 'EX_TIDELAND_DATED', 'ACCORDING TO THE TOWN RECORDS DATING', 'Page');
INSERT INTO `tb_ex_codes` VALUES (22, 'EX_TitleNo', 'Reference #:', 'Order #:');
INSERT INTO `tb_ex_codes` VALUES (23, 'Legal Description', 'BEGINNING at a point', 'BEGINNING');

-- ----------------------------
-- Table structure for tb_formats
-- ----------------------------
DROP TABLE IF EXISTS `tb_formats`;
CREATE TABLE `tb_formats`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `begin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_formats
-- ----------------------------
INSERT INTO `tb_formats` VALUES (1, 'Deed', 'This Deed is made on', NULL);
INSERT INTO `tb_formats` VALUES (2, 'Deed', 'This Deed', NULL);
INSERT INTO `tb_formats` VALUES (3, 'Deed', 'Made this', NULL);
INSERT INTO `tb_formats` VALUES (4, 'Deed', 'This deed made the', NULL);
INSERT INTO `tb_formats` VALUES (5, 'Deed', 'Index', 'DEEDS');
INSERT INTO `tb_formats` VALUES (6, 'Deed', 'Indenture', NULL);
INSERT INTO `tb_formats` VALUES (7, 'County Search Request', 'COUNTY SEARCH REQUEST', NULL);
INSERT INTO `tb_formats` VALUES (8, 'County Search Request', 'COUNTY SEARCH REQUEST', NULL);
INSERT INTO `tb_formats` VALUES (9, 'Flood Search', 'STANDARD FLOOD HAZARD DETERMINATION FORM (SFHDF)', NULL);
INSERT INTO `tb_formats` VALUES (10, 'Mortgage', 'THIS MORTGAGE', NULL);
INSERT INTO `tb_formats` VALUES (11, 'Mortgage', 'Document Type', 'Mortgage');
INSERT INTO `tb_formats` VALUES (12, 'Tax Search', 'NEW JERSEY TAX & ASSESSMENT SEARCH', NULL);
INSERT INTO `tb_formats` VALUES (13, 'Tideland Search', 'Tidelands Search Certificate', NULL);
INSERT INTO `tb_formats` VALUES (14, 'Tideland Search', 'Tidelands Search Certificate', NULL);
INSERT INTO `tb_formats` VALUES (15, 'Tideland Search', 'TIDELAND SEARCH CERTIFICATE', NULL);

SET FOREIGN_KEY_CHECKS = 1;
