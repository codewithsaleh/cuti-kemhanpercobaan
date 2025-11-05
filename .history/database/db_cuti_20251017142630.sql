-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 12, 2023 at 01:21 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Checkpoint: Konfigurasi awal MySQL untuk kompatibilitas
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cuti`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

DROP TABLE IF EXISTS `cuti`;
CREATE TABLE IF NOT EXISTS `cuti` (
  `id_cuti` varchar(30) NOT NULL,
  `id_user` varchar(256) NOT NULL,
  `alasan` text NOT NULL,
  `tgl_diajukan` date NOT NULL,
  `mulai` date NOT NULL,
  `berakhir` date NOT NULL,
  `id_status_cuti` int NOT NULL,
  `perihal_cuti` varchar(100) NOT NULL,
  `alasan_verifikasi` text,
  `approved_by` varchar(256) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cuti`),
  KEY `idx_user` (`id_user`),
  KEY `idx_status` (`id_status_cuti`),
  KEY `idx_tgl_diajukan` (`tgl_diajukan`),
  KEY `fk_approved_by` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuti`
--

INSERT INTO `cuti` (`id_cuti`, `id_user`, `alasan`, `tgl_diajukan`, `mulai`, `berakhir`, `id_status_cuti`, `perihal_cuti`, `alasan_verifikasi`) VALUES
('cuti-060ae', 'c551fc8847d29dc25a23db5d2cdb941b', 'Cuti Sakit SAkit', '2022-08-06', '2022-08-04', '2022-08-17', 2, 'Cuti Sakit', 'YEs'),
('cuti-714f0', '98eb4077470a60a0fe0f7b9d01755557', 'Karena ibu saya sakit', '2022-06-15', '2022-06-12', '2022-06-30', 1, 'Cuti Libur', NULL),
('cuti-99215', '98eb4077470a60a0fe0f7b9d01755557', 'menemani ibu saya yang sakit, sekarang beliau masih berada dirumah sakit dan butuh saya temani selama seminggu.', '2022-06-06', '2022-06-06', '2022-06-15', 2, 'berobat', NULL),
('cuti-ede81', '98eb4077470a60a0fe0f7b9d01755557', 'Liburan ke lampung', '2022-06-21', '2022-06-21', '2022-06-21', 2, 'Cuti Libur', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kelamin`
--

DROP TABLE IF EXISTS `jenis_kelamin`;
CREATE TABLE IF NOT EXISTS `jenis_kelamin` (
  `id_jenis_kelamin` int NOT NULL AUTO_INCREMENT,
  `jenis_kelamin` varchar(20) NOT NULL,
  PRIMARY KEY (`id_jenis_kelamin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_kelamin`
--

INSERT INTO `jenis_kelamin` (`id_jenis_kelamin`, `jenis_kelamin`) VALUES
(1, 'Laki-Laki'),
(2, 'Perempuan');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data default untuk reset cuti
--

INSERT INTO `settings` (`key`, `value`) VALUES
('reset_mode', 'manual'),
('last_reset_year', '2024'),
('default_cuti_tahunan', '12'),
('max_cuti_berturut', '7')
ON DUPLICATE KEY UPDATE value = VALUES(value);

-- --------------------------------------------------------

--
-- Table structure for table `status_cuti`
--

DROP TABLE IF EXISTS `status_cuti`;
CREATE TABLE IF NOT EXISTS `status_cuti` (
  `id_status_cuti` int NOT NULL AUTO_INCREMENT,
  `status_cuti` varchar(50) NOT NULL,
  `color_class` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_status_cuti`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_cuti`
--

INSERT INTO `status_cuti` (`id_status_cuti`, `status_cuti`, `color_class`) VALUES
(1, 'Menunggu Konfirmasi', 'warning'),
(2, 'Izin Cuti Diterima', 'success'),
(3, 'Izin Cuti Ditolak', 'danger');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` varchar(256) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_user_level` int NOT NULL,
  `id_user_detail` varchar(256) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_user_level` (`id_user_level`),
  KEY `idx_user_detail` (`id_user_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user` - Updated with stronger password hashing
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `id_user_level`, `id_user_detail`, `is_active`) VALUES
('134e349e4f50a051d8ca3687d6a7de1a', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin_utama@gmail.com', 2, '134e349e4f50a051d8ca3687d6a7de1a', 1),
('98eb4077470a60a0fe0f7b9d01755557', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user@gmail.com', 1, '98eb4077470a60a0fe0f7b9d01755557', 1),
('f5972fbf4ef53843c1e12c3ae99e5005', 'superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin@gmail.com', 3, 'f5972fbf4ef53843c1e12c3ae99e5005', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_detail`
--

DROP TABLE IF EXISTS `user_detail`;
CREATE TABLE IF NOT EXISTS `user_detail` (
  `id_user_detail` varchar(256) NOT NULL,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `id_jenis_kelamin` int DEFAULT NULL,
  `no_telp` varchar(30) DEFAULT NULL,
  `alamat` text,
  `nip` varchar(50) DEFAULT NULL,
  `pangkat` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `sisa_cuti` int DEFAULT 12,
  `tahun_cuti` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user_detail`),
  UNIQUE KEY `nip` (`nip`),
  KEY `idx_jenis_kelamin` (`id_jenis_kelamin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_detail`
--

INSERT INTO `user_detail` (`id_user_detail`, `nama_lengkap`, `id_jenis_kelamin`, `no_telp`, `alamat`, `nip`, `pangkat`, `jabatan`, `sisa_cuti`, `tahun_cuti`) VALUES
('134e349e4f50a051d8ca3687d6a7de1a', 'Administrator', 1, '08123456789', 'Kantor Pusat', '99.111.001', 'Administrator', 'Admin Sistem', 12, 2024),
('98eb4077470a60a0fe0f7b9d01755557', 'Tri Hartono', 1, '0895331309434', 'Jln. Kata siapa Saja Bener', '99.111.004', 'Karyawan Tetap', 'Head of IT - Support', 12, 2024),
('f5972fbf4ef53843c1e12c3ae99e5005', 'Super Administrator', 1, '08987654321', 'Kantor Pusat', '99.111.000', 'Super Admin', 'Super Administrator', 12, 2024);

-- --------------------------------------------------------

--
-- Table structure for table `user_level`
--

DROP TABLE IF EXISTS `user_level`;
CREATE TABLE IF NOT EXISTS `user_level` (
  `id_user_level` int NOT NULL AUTO_INCREMENT,
  `user_level` varchar(30) NOT NULL,
  `description` text,
  `permissions` json,
  PRIMARY KEY (`id_user_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_level`
--

INSERT INTO `user_level` (`id_user_level`, `user_level`, `description`, `permissions`) VALUES
(1, 'pegawai', 'Pegawai biasa yang dapat mengajukan cuti', '["view_own_cuti", "create_cuti", "edit_own_cuti"]'),
(2, 'admin', 'Administrator yang dapat mengelola cuti pegawai', '["view_all_cuti", "approve_cuti", "reject_cuti", "manage_users"]'),
(3, 'super admin', 'Super Administrator dengan akses penuh', '["full_access", "manage_settings", "manage_admins", "system_config"]');

-- --------------------------------------------------------

--
-- Add Foreign Key Constraints
--

ALTER TABLE `cuti`
  ADD CONSTRAINT `fk_cuti_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cuti_status` FOREIGN KEY (`id_status_cuti`) REFERENCES `status_cuti` (`id_status_cuti`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_level` FOREIGN KEY (`id_user_level`) REFERENCES `user_level` (`id_user_level`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_detail` FOREIGN KEY (`id_user_detail`) REFERENCES `user_detail` (`id_user_detail`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_detail`
  ADD CONSTRAINT `fk_jenis_kelamin` FOREIGN KEY (`id_jenis_kelamin`) REFERENCES `jenis_kelamin` (`id_jenis_kelamin`) ON DELETE SET NULL ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Create Views for easier data access
--

CREATE OR REPLACE VIEW `view_user_complete` AS
SELECT 
    u.id_user,
    u.username,
    u.email,
    u.is_active,
    u.last_login,
    ul.user_level,
    ul.description as level_description,
    ud.nama_lengkap,
    ud.nip,
    ud.pangkat,
    ud.jabatan,
    ud.sisa_cuti,
    ud.tahun_cuti,
    jk.jenis_kelamin,
    ud.no_telp,
    ud.alamat,
    ud.foto
FROM user u
LEFT JOIN user_level ul ON u.id_user_level = ul.id_user_level
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN jenis_kelamin jk ON ud.id_jenis_kelamin = jk.id_jenis_kelamin;

CREATE OR REPLACE VIEW `view_cuti_complete` AS
SELECT 
    c.id_cuti,
    c.alasan,
    c.tgl_diajukan,
    c.mulai,
    c.berakhir,
    c.perihal_cuti,
    c.alasan_verifikasi,
    c.approved_at,
    DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
    sc.status_cuti,
    sc.color_class,
    u.username,
    ud.nama_lengkap,
    ud.nip,
    ud.pangkat,
    ud.jabatan,
    approver.nama_lengkap as approved_by_name
FROM cuti c
LEFT JOIN status_cuti sc ON c.id_status_cuti = sc.id_status_cuti
LEFT JOIN user u ON c.id_user = u.id_user
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN user approver_user ON c.approved_by = approver_user.id_user
LEFT JOIN user_detail approver ON approver_user.id_user_detail = approver.id_user_detail;

-- --------------------------------------------------------

--
-- Create Triggers for automatic leave balance management
--

DELIMITER $$

CREATE TRIGGER `tr_cuti_approved` 
AFTER UPDATE ON `cuti`
FOR EACH ROW
BEGIN
    DECLARE leave_days INT;
    
    -- Only process when status changes to approved (id_status_cuti = 2)
    IF NEW.id_status_cuti = 2 AND OLD.id_status_cuti != 2 THEN
        -- Calculate leave days
        SET leave_days = DATEDIFF(NEW.berakhir, NEW.mulai) + 1;
        
        -- Deduct from user's leave balance
        UPDATE user_detail 
        SET sisa_cuti = sisa_cuti - leave_days
        WHERE id_user_detail = (
            SELECT id_user_detail 
            FROM user 
            WHERE id_user = NEW.id_user
        );
    END IF;
    
    -- If status changes from approved to something else, restore balance
    IF OLD.id_status_cuti = 2 AND NEW.id_status_cuti != 2 THEN
        SET leave_days = DATEDIFF(OLD.berakhir, OLD.mulai) + 1;
        
        UPDATE user_detail 
        SET sisa_cuti = sisa_cuti + leave_days
        WHERE id_user_detail = (
            SELECT id_user_detail 
            FROM user 
            WHERE id_user = NEW.id_user
        );
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Create procedure for annual leave reset
--

DELIMITER $$

CREATE PROCEDURE `sp_reset_annual_leave`()
BEGIN
    DECLARE current_year INT;
    SET current_year = YEAR(CURDATE());
    
    -- Reset all employee leave balances
    UPDATE user_detail 
    SET sisa_cuti = (
        SELECT CAST(value AS UNSIGNED) 
        FROM settings 
        WHERE `key` = 'default_cuti_tahunan'
    ),
    tahun_cuti = current_year
    WHERE id_user_detail IN (
        SELECT id_user_detail 
        FROM user 
        WHERE id_user_level = 1 -- Only employees
    );
    
    -- Update last reset year in settings
    UPDATE settings 
    SET value = current_year 
    WHERE `key` = 'last_reset_year';
    
    SELECT CONCAT('Leave balance reset completed for year ', current_year) as message;
END$$

DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
