-- phpMyAdmin SQL Dump
-- Database Cuti Kementerian Pertahanan
-- Updated: 2024
-- Includes complete data for all user roles

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cuti`
--
DROP DATABASE IF EXISTS `db_cuti`;
CREATE DATABASE IF NOT EXISTS `db_cuti` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_cuti`;

-- --------------------------------------------------------
-- STEP 1: Create base tables without foreign keys
-- --------------------------------------------------------

--
-- Table: jenis_kelamin
--
DROP TABLE IF EXISTS `jenis_kelamin`;
CREATE TABLE `jenis_kelamin` (
  `id_jenis_kelamin` int NOT NULL AUTO_INCREMENT,
  `jenis_kelamin` varchar(20) NOT NULL,
  PRIMARY KEY (`id_jenis_kelamin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `jenis_kelamin` (`id_jenis_kelamin`, `jenis_kelamin`) VALUES
(1, 'Laki-Laki'),
(2, 'Perempuan');

--
-- Table: user_level
--
DROP TABLE IF EXISTS `user_level`;
CREATE TABLE `user_level` (
  `id_user_level` int NOT NULL AUTO_INCREMENT,
  `user_level` varchar(30) NOT NULL,
  `description` text,
  `permissions` json,
  PRIMARY KEY (`id_user_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_level` (`id_user_level`, `user_level`, `description`, `permissions`) VALUES
(1, 'pegawai', 'Pegawai biasa yang dapat mengajukan cuti', '["view_own_cuti", "create_cuti", "edit_own_cuti"]'),
(2, 'admin', 'Administrator yang dapat mengelola cuti pegawai', '["view_all_cuti", "approve_cuti", "reject_cuti", "manage_users"]'),
(3, 'super admin', 'Super Administrator dengan akses penuh', '["full_access", "manage_settings", "manage_admins", "system_config"]');

--
-- Table: status_cuti
--
DROP TABLE IF EXISTS `status_cuti`;
CREATE TABLE `status_cuti` (
  `id_status_cuti` int NOT NULL AUTO_INCREMENT,
  `status_cuti` varchar(50) NOT NULL,
  `color_class` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_status_cuti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `status_cuti` (`id_status_cuti`, `status_cuti`, `color_class`) VALUES
(1, 'Menunggu Konfirmasi', 'warning'),
(2, 'Izin Cuti Diterima', 'success'),
(3, 'Izin Cuti Ditolak', 'danger');

--
-- Table: user_detail
--
DROP TABLE IF EXISTS `user_detail`;
CREATE TABLE `user_detail` (
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
  KEY `idx_jenis_kelamin` (`id_jenis_kelamin`),
  CONSTRAINT `fk_jenis_kelamin` FOREIGN KEY (`id_jenis_kelamin`) REFERENCES `jenis_kelamin` (`id_jenis_kelamin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_detail` (`id_user_detail`, `nama_lengkap`, `id_jenis_kelamin`, `no_telp`, `alamat`, `nip`, `pangkat`, `jabatan`, `sisa_cuti`, `tahun_cuti`) VALUES
('f5972fbf4ef53843c1e12c3ae99e5005', 'Super Administrator', 1, '08987654321', 'Kantor Pusat Kementerian Pertahanan', '99.111.000', 'Eselon I', 'Super Administrator', 12, YEAR(CURDATE())),
('134e349e4f50a051d8ca3687d6a7de1a', 'Administrator Sistem', 1, '08123456789', 'Kantor Pusat Kementerian Pertahanan', '99.111.001', 'Eselon II', 'Administrator Sistem', 12, YEAR(CURDATE())),
('98eb4077470a60a0fe0f7b9d01755557', 'Tri Hartono', 1, '0895331309434', 'Jln. Sudirman No. 45 Jakarta Pusat', '99.111.004', 'Pelaksana', 'Staff IT Support', 12, YEAR(CURDATE())),
('c551fc8847d29dc25a23db5d2cdb941b', 'Siti Aminah', 2, '08567891234', 'Jln. Gatot Subroto No. 12 Jakarta Selatan', '99.111.005', 'Pelaksana', 'Staff Administrasi', 12, YEAR(CURDATE())),
('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 'Budi Santoso', 1, '08123459876', 'Jln. Merdeka No. 100 Jakarta Pusat', '99.111.006', 'Pelaksana', 'Staff Keuangan', 12, YEAR(CURDATE()));

--
-- Table: user
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
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
  KEY `idx_user_detail` (`id_user_detail`),
  CONSTRAINT `fk_user_level` FOREIGN KEY (`id_user_level`) REFERENCES `user_level` (`id_user_level`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_user_detail` FOREIGN KEY (`id_user_detail`) REFERENCES `user_detail` (`id_user_detail`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Password untuk semua user: "password123" (gunakan MD5 atau bcrypt sesuai sistem Anda)
-- MD5: 482c811da5d5b4bc6d497ffa98491e38
-- Jika menggunakan md5 PHP: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `id_user_level`, `id_user_detail`, `is_active`) VALUES
('f5972fbf4ef53843c1e12c3ae99e5005', 'superadmin', '482c811da5d5b4bc6d497ffa98491e38', 'superadmin@kemhan.go.id', 3, 'f5972fbf4ef53843c1e12c3ae99e5005', 1),
('134e349e4f50a051d8ca3687d6a7de1a', 'admin', '482c811da5d5b4bc6d497ffa98491e38', 'admin@kemhan.go.id', 2, '134e349e4f50a051d8ca3687d6a7de1a', 1),
('98eb4077470a60a0fe0f7b9d01755557', 'user', '482c811da5d5b4bc6d497ffa98491e38', 'user@kemhan.go.id', 1, '98eb4077470a60a0fe0f7b9d01755557', 1),
('c551fc8847d29dc25a23db5d2cdb941b', 'pegawai1', '482c811da5d5b4bc6d497ffa98491e38', 'pegawai1@kemhan.go.id', 1, 'c551fc8847d29dc25a23db5d2cdb941b', 1),
('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 'pegawai2', '482c811da5d5b4bc6d497ffa98491e38', 'pegawai2@kemhan.go.id', 1, 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 1);

--
-- Table: cuti
--
DROP TABLE IF EXISTS `cuti`;
CREATE TABLE `cuti` (
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
  KEY `fk_approved_by` (`approved_by`),
  CONSTRAINT `fk_cuti_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cuti_status` FOREIGN KEY (`id_status_cuti`) REFERENCES `status_cuti` (`id_status_cuti`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cuti` (`id_cuti`, `id_user`, `alasan`, `tgl_diajukan`, `mulai`, `berakhir`, `id_status_cuti`, `perihal_cuti`, `alasan_verifikasi`, `approved_by`, `approved_at`) VALUES
('cuti-99215', '98eb4077470a60a0fe0f7b9d01755557', 'Menemani ibu yang sakit di rumah sakit', '2024-01-15', '2024-01-20', '2024-01-27', 2, 'Cuti Sakit Keluarga', 'Disetujui sesuai prosedur', '134e349e4f50a051d8ca3687d6a7de1a', '2024-01-16 08:30:00'),
('cuti-060ae', 'c551fc8847d29dc25a23db5d2cdb941b', 'Sakit demam dan flu', '2024-02-10', '2024-02-12', '2024-02-14', 2, 'Cuti Sakit', 'Disetujui dengan surat keterangan dokter', '134e349e4f50a051d8ca3687d6a7de1a', '2024-02-11 09:15:00'),
('cuti-714f0', '98eb4077470a60a0fe0f7b9d01755557', 'Liburan keluarga ke Bali', '2024-03-01', '2024-03-10', '2024-03-15', 1, 'Cuti Tahunan', NULL, NULL, NULL),
('cuti-ede81', 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 'Acara keluarga (pernikahan)', '2024-03-05', '2024-03-20', '2024-03-22', 1, 'Cuti Khusus', NULL, NULL, NULL);

--
-- Table: settings
--
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` text,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` (`key`, `value`, `description`) VALUES
('reset_mode', 'manual', 'Mode reset cuti: manual atau automatic'),
('last_reset_year', YEAR(CURDATE()), 'Tahun terakhir reset cuti dilakukan'),
('default_cuti_tahunan', '12', 'Jumlah cuti tahunan default untuk pegawai'),
('max_cuti_berturut', '7', 'Maksimal hari cuti berturut-turut'),
('app_name', 'Sistem Cuti Kemhan', 'Nama aplikasi'),
('app_version', '2.0', 'Versi aplikasi');

-- --------------------------------------------------------
-- STEP 2: Create Views
-- --------------------------------------------------------

CREATE OR REPLACE VIEW `view_user_complete` AS
SELECT 
    u.id_user,
    u.username,
    u.email,
    u.is_active,
    u.last_login,
    ul.id_user_level,
    ul.user_level,
    ul.description as level_description,
    ud.id_user_detail,
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
    c.id_user,
    c.alasan,
    c.tgl_diajukan,
    c.mulai,
    c.berakhir,
    c.perihal_cuti,
    c.alasan_verifikasi,
    c.approved_at,
    c.created_at,
    DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
    sc.id_status_cuti,
    sc.status_cuti,
    sc.color_class,
    u.username,
    ud.nama_lengkap,
    ud.nip,
    ud.pangkat,
    ud.jabatan,
    approver.nama_lengkap as approved_by_name,
    approver_user.username as approved_by_username
FROM cuti c
LEFT JOIN status_cuti sc ON c.id_status_cuti = sc.id_status_cuti
LEFT JOIN user u ON c.id_user = u.id_user
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN user approver_user ON c.approved_by = approver_user.id_user
LEFT JOIN user_detail approver ON approver_user.id_user_detail = approver.id_user_detail;

-- --------------------------------------------------------
-- STEP 3: Create Triggers
-- --------------------------------------------------------

DELIMITER $$

DROP TRIGGER IF EXISTS `tr_cuti_approved`$$
CREATE TRIGGER `tr_cuti_approved` 
AFTER UPDATE ON `cuti`
FOR EACH ROW
BEGIN
    DECLARE leave_days INT;
    
    -- When status changes to approved (2)
    IF NEW.id_status_cuti = 2 AND OLD.id_status_cuti != 2 THEN
        SET leave_days = DATEDIFF(NEW.berakhir, NEW.mulai) + 1;
        
        UPDATE user_detail 
        SET sisa_cuti = sisa_cuti - leave_days
        WHERE id_user_detail = (
            SELECT id_user_detail 
            FROM user 
            WHERE id_user = NEW.id_user
        );
    END IF;
    
    -- When status changes from approved to something else
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

-- --------------------------------------------------------
-- STEP 4: Create Stored Procedures
-- --------------------------------------------------------

DROP PROCEDURE IF EXISTS `sp_reset_annual_leave`$$
CREATE PROCEDURE `sp_reset_annual_leave`()
BEGIN
    DECLARE current_year INT;
    DECLARE default_leave INT;
    
    SET current_year = YEAR(CURDATE());
    SET default_leave = (SELECT CAST(value AS UNSIGNED) FROM settings WHERE `key` = 'default_cuti_tahunan');
    
    UPDATE user_detail 
    SET sisa_cuti = default_leave,
        tahun_cuti = current_year
    WHERE id_user_detail IN (
        SELECT id_user_detail 
        FROM user 
        WHERE id_user_level = 1
    );
    
    UPDATE settings 
    SET value = current_year 
    WHERE `key` = 'last_reset_year';
    
    SELECT CONCAT('Reset cuti berhasil untuk tahun ', current_year, '. Total pegawai: ', ROW_COUNT()) as message;
END$$

DROP PROCEDURE IF EXISTS `sp_get_user_stats`$$
CREATE PROCEDURE `sp_get_user_stats`(IN user_id VARCHAR(256))
BEGIN
    SELECT 
        u.username,
        ud.nama_lengkap,
        ud.sisa_cuti,
        ud.tahun_cuti,
        COUNT(CASE WHEN c.id_status_cuti = 1 THEN 1 END) as pending_cuti,
        COUNT(CASE WHEN c.id_status_cuti = 2 THEN 1 END) as approved_cuti,
        COUNT(CASE WHEN c.id_status_cuti = 3 THEN 1 END) as rejected_cuti,
        SUM(CASE WHEN c.id_status_cuti = 2 THEN DATEDIFF(c.berakhir, c.mulai) + 1 ELSE 0 END) as total_hari_cuti
    FROM user u
    LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
    LEFT JOIN cuti c ON u.id_user = c.id_user AND YEAR(c.tgl_diajukan) = YEAR(CURDATE())
    WHERE u.id_user = user_id
    GROUP BY u.id_user;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- STEP 5: Insert audit log
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(256) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` varchar(100) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ========================================
-- INFORMASI LOGIN
-- ========================================
-- Super Admin:
--   Username: superadmin
--   Password: password123
--   Email: superadmin@kemhan.go.id
--
-- Admin:
--   Username: admin
--   Password: password123
--   Email: admin@kemhan.go.id
--
-- Pegawai 1:
--   Username: user
--   Password: password123
--   Email: user@kemhan.go.id
--
-- Pegawai 2:
--   Username: pegawai1
--   Password: password123
--   Email: pegawai1@kemhan.go.id
--
-- Pegawai 3:
--   Username: pegawai2
--   Password: password123
--   Email: pegawai2@kemhan.go.id
-- ========================================
