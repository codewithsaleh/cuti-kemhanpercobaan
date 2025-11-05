-- phpMyAdmin SQL Dump
-- Database Cuti Kementerian Pertahanan
-- Updated: 2024 - FIXED VERSION
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
-- STEP 1: Create base tables
-- --------------------------------------------------------

DROP TABLE IF EXISTS `jenis_kelamin`;
CREATE TABLE `jenis_kelamin` (
  `id_jenis_kelamin` int NOT NULL AUTO_INCREMENT,
  `jenis_kelamin` varchar(20) NOT NULL,
  PRIMARY KEY (`id_jenis_kelamin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `jenis_kelamin` (`id_jenis_kelamin`, `jenis_kelamin`) VALUES
(1, 'Laki-Laki'),
(2, 'Perempuan');

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
(3, 'Izin Cuti Ditolak', 'danger'),
(4, 'Menunggu Admin', 'info');

DROP TABLE IF EXISTS `jenis_cuti`;
CREATE TABLE `jenis_cuti` (
  `id_jenis_cuti` int NOT NULL AUTO_INCREMENT,
  `nama_cuti` varchar(100) NOT NULL,
  `kode_cuti` varchar(20) DEFAULT NULL,
  `max_hari` int DEFAULT NULL,
  `keterangan` text,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jenis_cuti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `jenis_cuti` (`id_jenis_cuti`, `nama_cuti`, `kode_cuti`, `max_hari`, `keterangan`, `is_active`) VALUES
(1, 'Cuti Tahunan', 'CT', 12, 'Cuti tahunan reguler sesuai ketentuan', 1),
(2, 'Cuti Sakit', 'CS', 14, 'Cuti karena sakit dengan surat keterangan dokter', 1),
(3, 'Cuti Sakit Keluarga', 'CSK', 7, 'Cuti untuk merawat anggota keluarga yang sakit', 1),
(4, 'Cuti Melahirkan', 'CM', 90, 'Cuti melahirkan untuk pegawai perempuan', 1),
(5, 'Cuti Khusus', 'CK', 3, 'Cuti untuk acara khusus (pernikahan, kematian, dll)', 1),
(6, 'Cuti Bersama', 'CB', NULL, 'Cuti bersama hari libur nasional', 1),
(7, 'Cuti Diluar Tanggungan Negara', 'CDTN', NULL, 'Cuti tanpa menerima gaji', 1);

-- --------------------------------------------------------
-- Table: user_detail (DENGAN id_atasan dan jatah_cuti)
-- --------------------------------------------------------

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
  `id_atasan` varchar(256) DEFAULT NULL,
  `sisa_cuti` int DEFAULT 12,
  `jatah_cuti` int DEFAULT 12,
  `tahun_cuti` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user_detail`),
  UNIQUE KEY `nip` (`nip`),
  KEY `idx_jenis_kelamin` (`id_jenis_kelamin`),
  KEY `idx_atasan` (`id_atasan`),
  CONSTRAINT `fk_jenis_kelamin` FOREIGN KEY (`id_jenis_kelamin`) REFERENCES `jenis_kelamin` (`id_jenis_kelamin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_detail` (`id_user_detail`, `nama_lengkap`, `id_jenis_kelamin`, `no_telp`, `alamat`, `nip`, `pangkat`, `jabatan`, `id_atasan`, `sisa_cuti`, `jatah_cuti`, `tahun_cuti`) VALUES
('f5972fbf4ef53843c1e12c3ae99e5005', 'Super Administrator', 1, '08987654321', 'Kantor Pusat Kementerian Pertahanan', '99.111.000', 'Eselon I', 'Super Administrator', NULL, 12, 12, YEAR(CURDATE())),
('134e349e4f50a051d8ca3687d6a7de1a', 'Administrator Sistem', 1, '08123456789', 'Kantor Pusat Kementerian Pertahanan', '99.111.001', 'Eselon II', 'Administrator Sistem', 'f5972fbf4ef53843c1e12c3ae99e5005', 12, 12, YEAR(CURDATE())),
('98eb4077470a60a0fe0f7b9d01755557', 'Tri Hartono', 1, '0895331309434', 'Jln. Sudirman No. 45 Jakarta Pusat', '99.111.004', 'Pelaksana', 'Staff IT Support', '134e349e4f50a051d8ca3687d6a7de1a', 9, 12, YEAR(CURDATE())),
('c551fc8847d29dc25a23db5d2cdb941b', 'Siti Aminah', 2, '08567891234', 'Jln. Gatot Subroto No. 12 Jakarta Selatan', '99.111.005', 'Pelaksana', 'Staff Administrasi', '134e349e4f50a051d8ca3687d6a7de1a', 9, 12, YEAR(CURDATE())),
('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 'Budi Santoso', 1, '08123459876', 'Jln. Merdeka No. 100 Jakarta Pusat', '99.111.006', 'Pelaksana', 'Staff Keuangan', '134e349e4f50a051d8ca3687d6a7de1a', 12, 12, YEAR(CURDATE()));

-- Add FK for atasan after all data inserted
ALTER TABLE `user_detail`
  ADD CONSTRAINT `fk_atasan` FOREIGN KEY (`id_atasan`) REFERENCES `user_detail` (`id_user_detail`) ON DELETE SET NULL ON UPDATE CASCADE;

-- --------------------------------------------------------
-- Table: user
-- --------------------------------------------------------

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

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `id_user_level`, `id_user_detail`, `is_active`) VALUES
('f5972fbf4ef53843c1e12c3ae99e5005', 'superadmin', '482c811da5d5b4bc6d497ffa98491e38', 'superadmin@kemhan.go.id', 3, 'f5972fbf4ef53843c1e12c3ae99e5005', 1),
('134e349e4f50a051d8ca3687d6a7de1a', 'admin', '482c811da5d5b4bc6d497ffa98491e38', 'admin@kemhan.go.id', 2, '134e349e4f50a051d8ca3687d6a7de1a', 1),
('98eb4077470a60a0fe0f7b9d01755557', 'user', '482c811da5d5b4bc6d497ffa98491e38', 'user@kemhan.go.id', 1, '98eb4077470a60a0fe0f7b9d01755557', 1),
('c551fc8847d29dc25a23db5d2cdb941b', 'pegawai1', '482c811da5d5b4bc6d497ffa98491e38', 'pegawai1@kemhan.go.id', 1, 'c551fc8847d29dc25a23db5d2cdb941b', 1),
('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 'pegawai2', '482c811da5d5b4bc6d497ffa98491e38', 'pegawai2@kemhan.go.id', 1, 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 1);

-- --------------------------------------------------------
-- Table: cuti
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cuti`;
CREATE TABLE `cuti` (
  `id_cuti` varchar(30) NOT NULL,
  `id_user` varchar(256) NOT NULL,
  `id_jenis_cuti` int DEFAULT NULL,
  `alasan` text NOT NULL,
  `tgl_diajukan` date NOT NULL,
  `mulai` date NOT NULL,
  `berakhir` date NOT NULL,
  `id_status_cuti` int NOT NULL,
  `perihal_cuti` varchar(100) NOT NULL,
  `tujuan` varchar(255) DEFAULT NULL,
  `berkendaraan` varchar(100) DEFAULT NULL,
  `pengikut` varchar(255) DEFAULT NULL,
  `keperluan` text,
  `alasan_verifikasi` text,
  `catatan_atasan` text,
  `catatan_admin` text,
  `approved_by` varchar(256) DEFAULT NULL,
  `approved_by_atasan` varchar(256) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_at_atasan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cuti`),
  KEY `idx_user` (`id_user`),
  KEY `idx_jenis_cuti` (`id_jenis_cuti`),
  KEY `idx_status` (`id_status_cuti`),
  KEY `idx_tgl_diajukan` (`tgl_diajukan`),
  KEY `fk_approved_by` (`approved_by`),
  KEY `fk_approved_by_atasan` (`approved_by_atasan`),
  CONSTRAINT `fk_cuti_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cuti_jenis` FOREIGN KEY (`id_jenis_cuti`) REFERENCES `jenis_cuti` (`id_jenis_cuti`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cuti_status` FOREIGN KEY (`id_status_cuti`) REFERENCES `status_cuti` (`id_status_cuti`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_approved_by_atasan` FOREIGN KEY (`approved_by_atasan`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cuti` (`id_cuti`, `id_user`, `id_jenis_cuti`, `alasan`, `tgl_diajukan`, `mulai`, `berakhir`, `id_status_cuti`, `perihal_cuti`, `tujuan`, `berkendaraan`, `pengikut`, `keperluan`, `alasan_verifikasi`, `catatan_atasan`, `approved_by_atasan`, `approved_at_atasan`) VALUES
('cuti-99215', '98eb4077470a60a0fe0f7b9d01755557', 3, 'Menemani ibu yang sakit di rumah sakit', '2024-01-15', '2024-01-20', '2024-01-27', 4, 'Cuti Sakit Keluarga', 'RS Cipto Mangunkusumo Jakarta', 'Mobil Pribadi', 'Istri', 'Merawat ibu yang dirawat di RS', NULL, 'Disetujui atasan, menunggu persetujuan admin', '134e349e4f50a051d8ca3687d6a7de1a', '2024-01-16 08:30:00'),
('cuti-060ae', 'c551fc8847d29dc25a23db5d2cdb941b', 2, 'Sakit demam dan flu', '2024-02-10', '2024-02-12', '2024-02-14', 2, 'Cuti Sakit', 'Rumah', 'Tidak', 'Tidak ada', 'Istirahat di rumah', 'Disetujui dengan surat keterangan dokter', 'Telah diperiksa dan disetujui', '134e349e4f50a051d8ca3687d6a7de1a', '2024-02-11 09:15:00'),
('cuti-714f0', '98eb4077470a60a0fe0f7b9d01755557', 1, 'Liburan keluarga ke Bali', '2024-03-01', '2024-03-10', '2024-03-15', 1, 'Cuti Tahunan', 'Bali', 'Pesawat', 'Istri dan 2 anak', 'Liburan keluarga', NULL, NULL, NULL, NULL),
('cuti-ede81', 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 5, 'Acara keluarga (pernikahan)', '2024-03-05', '2024-03-20', '2024-03-22', 1, 'Cuti Khusus', 'Bandung', 'Mobil Pribadi', 'Istri', 'Menghadiri pernikahan saudara', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------

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
-- STEP 2: Update Views (FIXED) - Tambahkan jatah_cuti
-- --------------------------------------------------------

DROP VIEW IF EXISTS `view_user_complete`;
CREATE VIEW `view_user_complete` AS
SELECT 
    u.id_user,
    u.username,
    u.email,
    u.is_active,
    u.last_login,
    u.created_at as user_created_at,
    ul.id_user_level,
    ul.user_level,
    ul.description as level_description,
    ud.id_user_detail,
    ud.nama_lengkap,
    ud.nip,
    ud.pangkat,
    ud.jabatan,
    ud.id_atasan,
    ud.sisa_cuti,
    ud.jatah_cuti,
    ud.tahun_cuti,
    ud.no_telp,
    ud.alamat,
    ud.foto,
    jk.id_jenis_kelamin,
    jk.jenis_kelamin,
    atasan_detail.nama_lengkap as nama_atasan,
    atasan_detail.nip as nip_atasan,
    atasan_detail.jabatan as jabatan_atasan
FROM user u
LEFT JOIN user_level ul ON u.id_user_level = ul.id_user_level
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN jenis_kelamin jk ON ud.id_jenis_kelamin = jk.id_jenis_kelamin
LEFT JOIN user_detail atasan_detail ON ud.id_atasan = atasan_detail.id_user_detail;

CREATE OR REPLACE VIEW `view_cuti_complete` AS
SELECT 
    c.id_cuti,
    c.id_user,
    c.alasan,
    c.tgl_diajukan,
    c.mulai,
    c.berakhir,
    c.perihal_cuti,
    c.tujuan,
    c.berkendaraan,
    c.pengikut,
    c.keperluan,
    c.alasan_verifikasi,
    c.catatan_atasan,
    c.catatan_admin,
    c.approved_at,
    c.approved_at_atasan,
    c.created_at,
    DATEDIFF(c.berakhir, c.mulai) + 1 as jumlah_hari,
    jc.id_jenis_cuti,
    jc.nama_cuti,
    jc.kode_cuti,
    sc.id_status_cuti,
    sc.status_cuti,
    sc.color_class,
    u.username,
    ud.nama_lengkap,
    ud.nip,
    ud.pangkat,
    ud.jabatan,
    approver.nama_lengkap as approved_by_name,
    approver_user.username as approved_by_username,
    atasan.nama_lengkap as approved_by_atasan_name,
    atasan_user.username as approved_by_atasan_username
FROM cuti c
LEFT JOIN jenis_cuti jc ON c.id_jenis_cuti = jc.id_jenis_cuti
LEFT JOIN status_cuti sc ON c.id_status_cuti = sc.id_status_cuti
LEFT JOIN user u ON c.id_user = u.id_user
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN user approver_user ON c.approved_by = approver_user.id_user
LEFT JOIN user_detail approver ON approver_user.id_user_detail = approver.id_user_detail
LEFT JOIN user atasan_user ON c.approved_by_atasan = atasan_user.id_user
LEFT JOIN user_detail atasan ON atasan_user.id_user_detail = atasan.id_user_detail;

CREATE OR REPLACE VIEW `view_percakapan` AS
SELECT 
    p.id_percakapan,
    p.pesan,
    p.is_read,
    p.read_at,
    p.created_at,
    p.id_user_pengirim,
    pengirim.username as username_pengirim,
    pengirim_detail.nama_lengkap as nama_pengirim,
    pengirim_detail.foto as foto_pengirim,
    pengirim_detail.jabatan as jabatan_pengirim,
    p.id_user_penerima,
    penerima.username as username_penerima,
    penerima_detail.nama_lengkap as nama_penerima,
    penerima_detail.foto as foto_penerima,
    penerima_detail.jabatan as jabatan_penerima
FROM percakapan p
LEFT JOIN user pengirim ON p.id_user_pengirim = pengirim.id_user
LEFT JOIN user_detail pengirim_detail ON pengirim.id_user_detail = pengirim_detail.id_user_detail
LEFT JOIN user penerima ON p.id_user_penerima = penerima.id_user
LEFT JOIN user_detail penerima_detail ON penerima.id_user_detail = penerima_detail.id_user_detail;

CREATE OR REPLACE VIEW `view_list_kontak_chat` AS
SELECT DISTINCT
    u.id_user,
    u.username,
    u.email,
    ud.nama_lengkap,
    ud.nip,
    ud.jabatan,
    ud.foto,
    ul.user_level,
    COALESCE(
        (SELECT COUNT(*) 
         FROM percakapan p 
         WHERE p.id_user_pengirim = u.id_user 
         AND p.is_read = 0), 
        0
    ) as unread_count,
    COALESCE(
        (SELECT MAX(p2.created_at) 
         FROM percakapan p2 
         WHERE p2.id_user_pengirim = u.id_user 
         OR p2.id_user_penerima = u.id_user),
        u.created_at
    ) as last_message_time
FROM user u
LEFT JOIN user_detail ud ON u.id_user_detail = ud.id_user_detail
LEFT JOIN user_level ul ON u.id_user_level = ul.id_user_level
WHERE u.is_active = 1
ORDER BY last_message_time DESC;

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
    
    IF NEW.id_status_cuti = 2 AND OLD.id_status_cuti != 2 THEN
        SET leave_days = DATEDIFF(NEW.berakhir, NEW.mulai) + 1;
        UPDATE user_detail 
        SET sisa_cuti = sisa_cuti - leave_days
        WHERE id_user_detail = (SELECT id_user_detail FROM user WHERE id_user = NEW.id_user);
    END IF;
    
    IF OLD.id_status_cuti = 2 AND NEW.id_status_cuti != 2 THEN
        SET leave_days = DATEDIFF(OLD.berakhir, OLD.mulai) + 1;
        UPDATE user_detail 
        SET sisa_cuti = sisa_cuti + leave_days
        WHERE id_user_detail = (SELECT id_user_detail FROM user WHERE id_user = NEW.id_user);
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
    SET sisa_cuti = default_leave, tahun_cuti = current_year
    WHERE id_user_detail IN (SELECT id_user_detail FROM user WHERE id_user_level = 1);
    
    UPDATE settings SET value = current_year WHERE `key` = 'last_reset_year';
    
    SELECT CONCAT('Reset cuti berhasil untuk tahun ', current_year) as message;
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
-- STEP 5: Audit Log Table
-- --------------------------------------------------------

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
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

-- --------------------------------------------------------
-- Table: percakapan (BARU - untuk fitur pesan/chat)
-- --------------------------------------------------------

DROP TABLE IF EXISTS `percakapan`;
CREATE TABLE `percakapan` (
  `id_percakapan` varchar(256) NOT NULL,
  `id_user_pengirim` varchar(256) NOT NULL,
  `id_user_penerima` varchar(256) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_percakapan`),
  KEY `idx_pengirim` (`id_user_pengirim`),
  KEY `idx_penerima` (`id_user_penerima`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_percakapan_pengirim` FOREIGN KEY (`id_user_pengirim`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_percakapan_penerima` FOREIGN KEY (`id_user_penerima`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data sample percakapan
INSERT INTO `percakapan` (`id_percakapan`, `id_user_pengirim`, `id_user_penerima`, `pesan`, `is_read`, `created_at`) VALUES
('pesan-001', '98eb4077470a60a0fe0f7b9d01755557', '134e349e4f50a051d8ca3687d6a7de1a', 'Selamat pagi, saya ingin bertanya tentang pengajuan cuti saya', 1, '2024-01-10 08:30:00'),
('pesan-002', '134e349e4f50a051d8ca3687d6a7de1a', '98eb4077470a60a0fe0f7b9d01755557', 'Pagi, silakan diajukan saja melalui sistem', 1, '2024-01-10 09:00:00'),
('pesan-003', 'c551fc8847d29dc25a23db5d2cdb941b', '134e349e4f50a051d8ca3687d6a7de1a', 'Pak/Bu, bagaimana status cuti saya yang kemarin?', 1, '2024-02-11 10:15:00'),
('pesan-004', '134e349e4f50a051d8ca3687d6a7de1a', 'c551fc8847d29dc25a23db5d2cdb941b', 'Sudah disetujui, silakan cek di menu Cuti', 1, '2024-02-11 11:00:00'),
('pesan-005', 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', '134e349e4f50a051d8ca3687d6a7de1a', 'Mohon info terkait sisa jatah cuti saya', 0, '2024-03-15 14:20:00');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ========================================
-- INFORMASI LOGIN
-- ========================================
-- Super Admin: superadmin / password123
-- Admin: admin / password123
-- Pegawai: user / password123
-- Pegawai: pegawai1 / password123
-- Pegawai: pegawai2 / password123
-- ========================================
