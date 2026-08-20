/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `tblLocationA`;
CREATE TABLE `tblLocationA` (
  `LocNo` varchar(50) NOT NULL,
  `LocCode` varchar(50) DEFAULT NULL,
  `LocDesc` varchar(50) DEFAULT NULL,
  `CreatedBy` int DEFAULT NULL,
  `DateCreated` datetime DEFAULT NULL,
  `UpdatedBy` int DEFAULT NULL,
  `DateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`LocNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tblLocationA` (`LocNo`, `LocCode`, `LocDesc`, `CreatedBy`, `DateCreated`, `UpdatedBy`, `DateUpdated`) VALUES
('Z00A-1101', 'Code1', 'HO-Renaissance', 1, '2025-09-10 08:51:02', NULL, NULL),
('Z00A-1102', 'Code9', 'HO-AnonasQC', 1, '2025-09-10 08:51:10', NULL, NULL),
('Z00A-1103', 'Code5', 'HO- B&G', 1, '2025-09-10 08:51:06', NULL, NULL),
('Z00C-1103', 'Code55', 'MiescorMarikina', 1, '2025-09-10 08:51:56', NULL, NULL),
('Z00E-1103', 'Code47', 'MeralcoAngono', 1, '2025-09-10 08:51:48', NULL, NULL),
('Z00J-1103', 'Code22', 'MeralcoBatangas', 1, '2025-09-10 08:51:23', NULL, NULL),
('Z00L-1102', 'Code61', 'DU-DasmaCavite', 1, '2025-09-10 08:51:04', NULL, NULL),
('Z00O-1101', 'Code45', 'PLDT- QC', 1, '2025-09-10 08:51:46', NULL, NULL),
('Z00O-1103', 'Code52', 'MeralcoDasma', 1, '2025-09-10 08:51:53', NULL, NULL),
('Z00R-1102', 'Code62', 'DU-Pasig', 1, '2025-09-10 08:51:05', NULL, NULL),
('Z00T-1101', 'Code54', 'Log-MarikinaCty', 1, '2025-09-10 08:51:55', NULL, NULL),
('Z00T-1103', 'Code69', 'MeralcoLucena', 1, '2025-09-10 08:51:12', NULL, NULL),
('Z00U-1101', 'Code20', 'DUS - QC', 1, '2025-09-10 08:51:21', NULL, NULL),
('Z00V-1101', 'Code4', 'DUS-Valenzuela', 1, '2025-09-10 08:51:05', NULL, NULL),
('Z00W-1103', 'Code39', 'MerlcoMndluyong', 1, '2025-09-10 08:51:40', NULL, NULL),
('Z00X-1103', 'Code21', 'Meralco Manila', 1, '2025-09-10 08:51:22', NULL, NULL),
('Z00Z-1101', 'Code12', 'DUS-Clark', 1, '2025-09-10 08:51:13', NULL, NULL),
('Z0A0-1101', 'Code13', 'DUS-Pasig', 1, '2025-09-10 08:51:14', NULL, NULL),
('Z0A0-1103', 'Code16', 'Meralco Ortigas', 1, '2025-09-10 08:51:17', NULL, NULL),
('Z0B0-1101', 'Code15', 'DUS-Manila', 1, '2025-09-10 08:51:16', NULL, NULL),
('Z0B0-1103', 'Code28', 'MeralcoPlaridel', 1, '2025-09-10 08:51:29', NULL, NULL),
('Z0C0-1103', 'Code65', 'Meralco QC', 1, '2025-09-10 08:51:08', NULL, NULL),
('Z0F0-1101', 'Code43', 'DUS-Dasmarinas', 1, '2025-09-10 08:51:44', NULL, NULL),
('Z0G0-1101', 'Code11', 'DUS-StaRosa', 1, '2025-09-10 08:51:12', NULL, NULL),
('Z0H0-1103', 'Code32', 'MeralcoSanPablo', 1, '2025-09-10 08:51:33', NULL, NULL),
('Z0J0-1101', 'Code36', 'Seconded MBI', 1, '2025-09-10 08:51:37', NULL, NULL),
('Z0L0-1103', 'Code46', 'Meralco StaRosa', 1, '2025-09-10 08:51:47', NULL, NULL),
('Z0M0-1103', 'Code50', 'MeralcoStaMaria', 1, '2025-09-10 08:51:51', NULL, NULL),
('Z0Q0-1103', 'Code24', 'MeralValenzuela', 1, '2025-09-10 08:51:25', NULL, NULL),
('Z0W0-1101', 'Code25', 'DUS-Plaridel', 1, '2025-09-10 08:51:26', NULL, NULL),
('Z0X0-1103', 'Code18', 'MeralcoSanJoaq', 1, '2025-09-10 08:51:19', NULL, NULL),
('Z0Y0-1103', 'Code26', 'MeralBalintawak', 1, '2025-09-10 08:51:27', NULL, NULL),
('Z0Z0-1103', 'Code34', 'VehicleLsKatips', 1, '2025-09-10 08:51:35', NULL, NULL),
('Z108-1102', 'Code35', 'Gcon-TayabasQue', 1, '2025-09-10 08:51:36', NULL, NULL),
('Z10A-1103', 'Code63', 'Meralco Taguig', 1, '2025-09-10 08:51:06', NULL, NULL),
('Z10B-1103', 'Code59', 'Meralco Pasig', 1, '2025-09-10 08:51:02', NULL, NULL),
('Z139-1102', 'Code60', 'STL-StaRosaLag', 1, '2025-09-10 08:51:03', NULL, NULL),
('Z140-1102', 'Code29', 'STL-Balintawak', 1, '2025-09-10 08:51:30', NULL, NULL),
('Z146-1102', 'Code48', 'Gcon-StoTomas', 1, '2025-09-10 08:51:49', NULL, NULL),
('Z156-1102', 'Code33', 'Gcon-KawitCavte', 1, '2025-09-10 08:51:34', NULL, NULL),
('Z158-1102', 'Code66', 'Gcon-Valenzuela', 1, '2025-09-10 08:51:09', NULL, NULL),
('Z159-1102', 'Code57', 'Gcon-Bustos', 1, '2025-09-10 08:51:58', NULL, NULL),
('Z160-1102', 'Code27', 'Gcon-Masinloc', 1, '2025-09-10 08:51:28', NULL, NULL),
('Z161-1102', 'Code40', 'Gcon-GapanNE', 1, '2025-09-10 08:51:41', NULL, NULL),
('Z2A0-1101', 'Code31', 'Telecom-QC', 1, '2025-09-10 08:51:32', NULL, NULL),
('Z2B0-1101', 'Code49', 'NGCP-Butuan', 1, '2025-09-10 08:51:50', NULL, NULL),
('Z2K0-1101', 'Code3', 'NGCP-Pitogo', 1, '2025-09-10 08:51:04', NULL, NULL),
('ZB00-1103', 'Code51', 'Meralco Masinag', 1, '2025-09-10 08:51:52', NULL, NULL),
('ZC00-1101', 'Code42', 'DUS-Mandaluyong', 1, '2025-09-10 08:51:43', NULL, NULL),
('ZE00-1101', 'Code41', 'DUS-Paranaque', 1, '2025-09-10 08:51:42', NULL, NULL),
('ZF00-1103', 'Code23', 'MeralcoParanque', 1, '2025-09-10 08:51:24', NULL, NULL),
('ZK00-1101', 'Code14', 'DUS-Angono', 1, '2025-09-10 08:51:15', NULL, NULL),
('ZX16-1101', 'Code7', 'NGCP-Bacolod', 1, '2025-09-10 08:51:08', NULL, NULL),
('ZX21-1101', 'Code37', 'Meco-SnIldfonso', 1, '2025-09-10 08:51:38', NULL, NULL),
('ZX28-1101', 'Code38', 'MECO-Aseana', 1, '2025-09-10 08:51:39', NULL, NULL),
('ZX38-1101', 'Code70', 'NGCP-BBtgnLeyte', 1, '2025-09-10 08:51:13', NULL, NULL),
('ZX39-1101', 'Code56', 'NGCP-Ormoc', 1, '2025-09-10 08:51:57', NULL, NULL),
('ZX42-1101', 'Code67', 'NGCP-Cebu', 1, '2025-09-10 08:51:10', NULL, NULL),
('ZX43-1101', 'Code68', 'NGCP-Mandaue', 1, '2025-09-10 08:51:11', NULL, NULL),
('ZX44-1101', 'Code58', 'NGCP-LapuLapu', 1, '2025-09-10 08:51:59', NULL, NULL),
('ZX45-1101', 'Code53', 'NGCP-AmlanNegOr', 1, '2025-09-10 08:51:54', NULL, NULL),
('ZX48-1101', 'Code2', 'GigaAce-Botolan', 1, '2025-09-10 08:51:03', NULL, NULL),
('ZX49-1101', 'Code10', 'GigaAce-Palauig', 1, '2025-09-10 08:51:11', NULL, NULL),
('ZX57-1101', 'Code64', 'Meco-SJDMBulacn', 1, '2025-09-10 08:51:07', NULL, NULL),
('ZX58-1101', 'Code6', 'TSPI-GapanNE', 1, '2025-09-10 08:51:07', NULL, NULL),
('ZX73-1101', 'Code44', 'SMC-MasinlocZam', 1, '2025-09-10 08:51:45', NULL, NULL),
('ZZ05-1101', 'Code19', 'UAM - QC', 1, '2025-09-10 08:51:20', NULL, NULL),
('ZZ45-1101', 'Code17', 'FAM-Pasig', 1, '2025-09-10 08:51:18', NULL, NULL),
('ZZ60-1101', 'Code30', 'NGCP-Naga Colon', 1, '2025-09-10 08:51:31', NULL, NULL),
('ZZ78-1101', 'Code8', 'NGCP-Colon Cebu', 1, '2025-09-10 08:51:09', NULL, NULL);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;