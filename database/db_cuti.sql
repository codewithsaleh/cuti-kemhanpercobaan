-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 12:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_user_stats` (IN `user_id` VARCHAR(256))   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reset_annual_leave` ()   BEGIN
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

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` varchar(256) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` varchar(100) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_pengirim` varchar(256) NOT NULL,
  `id_penerima` varchar(256) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

CREATE TABLE `cuti` (
  `id_cuti` varchar(30) NOT NULL,
  `id_user` varchar(256) NOT NULL,
  `id_jenis_cuti` int(11) DEFAULT NULL,
  `alasan` text NOT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `tgl_diajukan` date NOT NULL,
  `mulai` date NOT NULL,
  `berakhir` date NOT NULL,
  `id_status_cuti` int(11) NOT NULL,
  `status_terakhir` int(11) DEFAULT NULL,
  `tahun_reset` year(4) DEFAULT NULL,
  `rejected_by` varchar(50) DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `perihal_cuti` varchar(100) NOT NULL,
  `tujuan` varchar(255) DEFAULT NULL,
  `berkendaraan` varchar(100) DEFAULT NULL,
  `pengikut` varchar(255) DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `alasan_verifikasi` text DEFAULT NULL,
  `catatan_atasan` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `approved_by` varchar(256) DEFAULT NULL,
  `approved_by_atasan` varchar(256) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_at_atasan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuti`
--

INSERT INTO `cuti` (`id_cuti`, `id_user`, `id_jenis_cuti`, `alasan`, `alasan_penolakan`, `tgl_diajukan`, `mulai`, `berakhir`, `id_status_cuti`, `status_terakhir`, `tahun_reset`, `rejected_by`, `rejected_at`, `perihal_cuti`, `tujuan`, `berkendaraan`, `pengikut`, `keperluan`, `alasan_verifikasi`, `catatan_atasan`, `catatan_admin`, `approved_by`, `approved_by_atasan`, `approved_at`, `approved_at_atasan`, `created_at`, `updated_at`) VALUES
('cuti-760b8', 'user-0d62dd8dd0', 1, 'Ada', NULL, '2025-11-01', '2025-11-14', '2025-11-15', 1, NULL, NULL, NULL, NULL, 'Cuti Tahunan', 'Jakarta', 'Motor Pribadi', 'Keluarga', 'Ada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-01 03:30:39', '2025-11-01 03:30:39'),
('cuti-b29fa', 'user-a8d4739803', 1, 'ada', NULL, '2025-10-30', '2025-10-31', '2025-10-31', 2, NULL, NULL, NULL, NULL, 'Cuti Tahunan', 'Semarang', 'Mobil Pribadi', 'Keluarga', 'ada', 'ok', NULL, NULL, '134e349e4f50a051d8ca3687d6a7de1a', 'user-90235666de', '2025-10-30 10:50:22', '2025-10-30 10:50:08', '2025-10-30 10:48:10', '2025-10-30 10:50:22'),
('cuti-b9944', 'user-a8d4739803', 1, 'A', NULL, '2025-10-27', '2025-10-29', '2025-10-29', 5, 2, '2025', NULL, NULL, 'Cuti Tahunan', 'Medan', 'Mobil Pribadi', 'Keluarga', 'a', 'Admin Oke', NULL, NULL, '134e349e4f50a051d8ca3687d6a7de1a', 'user-90235666de', '2025-10-27 17:02:54', '2025-10-27 17:02:18', '2025-10-27 17:01:35', '2025-10-27 17:03:10'),
('cuti-d1a52', 'user-2202a071a7', 1, 'C', NULL, '2025-10-30', '2025-10-31', '2025-10-31', 2, NULL, NULL, NULL, NULL, 'Cuti Tahunan', 'Bandung', 'Motor Pribadi', 'Keuarga', 'ada', 'adad', NULL, NULL, '134e349e4f50a051d8ca3687d6a7de1a', 'user-57b67d6fcd', '2025-10-30 11:11:13', '2025-10-30 11:10:41', '2025-10-30 11:10:15', '2025-10-30 11:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_cuti`
--

CREATE TABLE `jenis_cuti` (
  `id_jenis_cuti` int(11) NOT NULL,
  `nama_cuti` varchar(100) NOT NULL,
  `kode_cuti` varchar(20) DEFAULT NULL,
  `max_hari` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_cuti`
--

INSERT INTO `jenis_cuti` (`id_jenis_cuti`, `nama_cuti`, `kode_cuti`, `max_hari`, `keterangan`, `is_active`, `created_at`) VALUES
(1, 'Cuti Tahunan', 'CT', 12, 'Cuti tahunan reguler sesuai ketentuan', 1, '2025-10-21 15:45:37'),
(2, 'Cuti Sakit', 'CS', 14, 'Cuti karena sakit dengan surat keterangan dokter', 1, '2025-10-21 15:45:37'),
(3, 'Cuti Sakit Keluarga', 'CSK', 7, 'Cuti untuk merawat anggota keluarga yang sakit', 1, '2025-10-21 15:45:37'),
(4, 'Cuti Melahirkan', 'CM', 90, 'Cuti melahirkan untuk pegawai perempuan', 1, '2025-10-21 15:45:37'),
(5, 'Cuti Khusus', 'CK', 3, 'Cuti untuk acara khusus (pernikahan, kematian, dll)', 1, '2025-10-21 15:45:37'),
(6, 'Cuti Bersama', 'CB', NULL, 'Cuti bersama hari libur nasional', 1, '2025-10-21 15:45:37'),
(7, 'Cuti Diluar Tanggungan Negara', 'CDTN', NULL, 'Cuti tanpa menerima gaji', 1, '2025-10-21 15:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kelamin`
--

CREATE TABLE `jenis_kelamin` (
  `id_jenis_kelamin` int(11) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'reset_mode', 'manual', 'Mode reset cuti: manual atau automatic', '2025-10-21 15:45:38', '2025-10-26 08:41:13'),
(2, 'last_reset_year', '2025', 'Tahun terakhir reset cuti dilakukan', '2025-10-21 15:45:38', '2025-10-29 02:28:27'),
(3, 'default_cuti_tahunan', '12', 'Jumlah cuti tahunan default untuk pegawai', '2025-10-21 15:45:38', '2025-10-21 15:45:38'),
(4, 'max_cuti_berturut', '7', 'Maksimal hari cuti berturut-turut', '2025-10-21 15:45:38', '2025-10-21 15:45:38'),
(5, 'app_name', 'Sistem Cuti Kemhan', 'Nama aplikasi', '2025-10-21 15:45:38', '2025-10-21 15:45:38'),
(6, 'app_version', '2.0', 'Versi aplikasi', '2025-10-21 15:45:38', '2025-10-21 15:45:38');

-- --------------------------------------------------------

--
-- Table structure for table `status_cuti`
--

CREATE TABLE `status_cuti` (
  `id_status_cuti` int(11) NOT NULL,
  `status_cuti` varchar(50) NOT NULL,
  `color_class` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_cuti`
--

INSERT INTO `status_cuti` (`id_status_cuti`, `status_cuti`, `color_class`) VALUES
(1, 'Menunggu Konfirmasi', 'warning'),
(2, 'Izin Cuti Diterima', 'success'),
(3, 'Izin Cuti Ditolak', 'danger'),
(4, 'Menunggu Admin', 'info'),
(5, 'Diarsipkan', 'secondary');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` varchar(256) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_user_level` int(11) NOT NULL,
  `id_user_detail` varchar(256) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_atasan` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `id_user_level`, `id_user_detail`, `is_active`, `last_login`, `created_at`, `updated_at`, `id_atasan`) VALUES
('134e349e4f50a051d8ca3687d6a7de1a', 'admin', '$2y$10$oyH8.V90wra0vUeB0hU2KOZ5GZBGv.qk2BWA2dDiZVeaNEBxVwKPW', 'admin@kemhan.go.id', 2, '134e349e4f50a051d8ca3687d6a7de1a', 1, NULL, '2025-10-21 15:45:38', '2025-10-29 02:20:33', NULL),
('user-0d62dd8dd0', '223304', '$2y$10$SBa7OJ6h1yy.x1.7xQYOBuTa4TV1flNFsxOWJ2bvyHtOjdkrKXA.2', 'direkanggaran2@gmail.com', 1, 'ud-8ac7c90d17', 1, NULL, '2025-11-01 03:29:52', '2025-11-01 04:39:58', 'user-90235666de'),
('user-2202a071a7', '223302', '$2y$10$1757skF0ocgsfNtIqjD/qe9m.PNBvI3NH7aLF6fOoFup0MJhbsZzO', 'pegawaidirsdm@gmail.com', 1, 'ud-b744f10e9e', 1, NULL, '2025-10-30 11:09:33', '2025-10-30 11:09:33', 'user-57b67d6fcd'),
('user-2d7b5e5fb1', '221103', '$2y$10$EUcGVvjs0uaGqh.nkNYpX.98f8kzYX0Lq7ZP.up47RTNODnDG5MaK', 'birokepegawaian@gmail.com', 3, 'ud-57793b9bd8', 1, NULL, '2025-10-22 12:00:57', '2025-10-29 12:30:46', NULL),
('user-40f33343fd', '198003031234567892', '$2y$10$CwS7VjS65mb/rHDwIDvKiOn/K9bM.xuaZgkW8pVHgPMo3o/ckpo2i', 'budi.santoso@kemhan.go.id', 1, 'ud-9d5743b079', 1, NULL, '2025-11-03 12:04:02', '2025-11-03 12:04:02', NULL),
('user-57b67d6fcd', '221102', '$2y$10$h8iZEYfO.MkjNBnQwsgSGexgCRQCif4xrIDqbjlplKD1ApDzURGrq', 'dirsdm@gmail.com', 3, 'ud-b069510499', 1, NULL, '2025-10-30 11:07:38', '2025-10-30 17:32:26', NULL),
('user-666f2653c9', '198504041234567893', '$2y$10$DJ.1G36Cn3rmObsGJT8JZe3sRNzJAvwtTEbI9VmpfRlU./ibbIya.', 'sari.dewi@kemhan.go.id', 1, 'ud-9ee368bd8f', 1, NULL, '2025-11-03 12:04:02', '2025-11-03 12:04:02', NULL),
('user-86cce487f7', '223303', '$2y$10$52BAN0nnL3NDbRqMpWv1f.HjWlCpcy1ApHcwiTcJVxBvx9RZvyya.', 'pegbirokepegawaian@gmail.com', 1, 'ud-af088fa6a3', 1, NULL, '2025-10-30 17:33:46', '2025-10-30 17:33:46', 'user-2d7b5e5fb1'),
('user-90235666de', '221101', '$2y$10$B6UOApuGUOabrf4Uu5D/ueEEfZrI9JjWJlFAUJj1Z95umWu1eIZGm', 'direkanggaran@gmail.com', 3, 'ud-b953b267d9', 1, NULL, '2025-10-27 05:54:59', '2025-10-29 12:30:53', NULL),
('user-a8d4739803', '223301', '$2y$10$npbv8UwOtoAGSRWcX2dGJ.wAqI2hYk2c.mxDkOLWjvjmaJjo1Yt1m', 'pegawaidiraanggaran@gmail.com', 1, 'ud-d848ed4add', 1, NULL, '2025-10-27 10:13:25', '2025-10-30 17:32:10', 'user-90235666de');

-- --------------------------------------------------------

--
-- Table structure for table `user_detail`
--

CREATE TABLE `user_detail` (
  `id_user_detail` varchar(256) NOT NULL,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `id_jenis_kelamin` int(11) DEFAULT NULL,
  `no_telp` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `pangkat` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `id_atasan` varchar(256) DEFAULT NULL,
  `sisa_cuti` int(11) DEFAULT 12,
  `jatah_cuti` int(11) DEFAULT 12,
  `tahun_cuti` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_detail`
--

INSERT INTO `user_detail` (`id_user_detail`, `nama_lengkap`, `id_jenis_kelamin`, `no_telp`, `alamat`, `nip`, `pangkat`, `jabatan`, `id_atasan`, `sisa_cuti`, `jatah_cuti`, `tahun_cuti`, `foto`, `created_at`, `updated_at`) VALUES
('134e349e4f50a051d8ca3687d6a7de1a', 'Administrator Sistem', 1, '08123456789', 'Kantor Pusat Kementerian Pertahanan, Indonesia', '99.111.001', 'Pembina Utama (IV/e)', 'Administrator Sistem', NULL, 12, 12, 2025, NULL, '2025-10-21 15:45:38', '2025-10-29 02:19:27'),
('ud-57793b9bd8', 'Biro Kepegawaian', 1, '1234567890', 'Gedung D.I Panjaitan Jalan Tanah Abang Timur No. 7 3 3, RT.3/RW.3, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10150.', '221103', 'Pembina (IV/a)', 'Pimpinan', NULL, 12, 12, 2025, NULL, '2025-10-22 12:00:57', '2025-10-27 05:59:17'),
('ud-8ac7c90d17', 'Pegawai Dir Anggaran Pertahanan 2', 2, '1234567891', 'Ada', '223304', 'Pembina Tingkat I (IV/b)', 'Kasubag', NULL, 12, 12, 2025, NULL, '2025-11-01 03:29:51', '2025-11-01 04:39:58'),
('ud-9d5743b079', 'Budi Santoso', 1, '081234567892', 'Jl. Gatot Subroto No. 3, Jakarta Barat', '198003031234567892', 'Letkol', 'Kasubag', NULL, 12, 12, 2025, NULL, '2025-11-03 12:04:02', '2025-11-03 12:04:02'),
('ud-9ee368bd8f', 'Sari Dewi', 2, '081234567893', 'Jl. MH Thamrin No. 4, Jakarta Utara', '198504041234567893', 'Kapten', 'Staff Operasional', NULL, 12, 12, 2025, NULL, '2025-11-03 12:04:02', '2025-11-03 12:04:02'),
('ud-af088fa6a3', 'Pegawai Biro Kepegawaian', 2, '0895331309434', 'Jakarta', '223303', 'Penata Tingkat I (III/d)', 'Staf Administrasi', NULL, 12, 12, 2025, NULL, '2025-10-30 17:33:46', '2025-10-30 17:33:46'),
('ud-b069510499', 'Direktorat Sumber Daya Manusia (SDM)', 1, '089876543210', 'ada', '221102', 'Penata Muda (III/a)', 'Pimpinan', NULL, 12, 12, 2025, NULL, '2025-10-30 11:07:38', '2025-10-30 17:32:26'),
('ud-b744f10e9e', 'Pegawai Dir Sumber Daya Manusia (SDM)', 1, '1234567890', 'ada', '223302', 'Pembina Tingkat I (IV/b)', 'Staf Administrasi', NULL, 11, 12, 2025, NULL, '2025-10-30 11:09:33', '2025-10-30 11:11:13'),
('ud-b953b267d9', 'Direktorat Anggaran Pertahanan', 1, '1234567899', 'Alamat Dir Anggaran Pertahanan', '221101', 'Penata Muda (III/a)', 'Pimpinan', NULL, 12, 12, 2025, NULL, '2025-10-27 05:54:59', '2025-10-28 15:43:05'),
('ud-d848ed4add', 'Pegawai Dir Anggaran Pertahanan', 1, '1234567890', 'Jakarta Utara', '223301', 'Penata Muda (III/a)', 'Staf Kantor', NULL, 11, 12, 2025, NULL, '2025-10-27 10:13:24', '2025-10-30 17:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_level`
--

CREATE TABLE `user_level` (
  `id_user_level` int(11) NOT NULL,
  `user_level` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_level`
--

INSERT INTO `user_level` (`id_user_level`, `user_level`, `description`, `permissions`) VALUES
(1, 'pegawai', 'Pegawai biasa yang dapat mengajukan cuti', '[\"view_own_cuti\", \"create_cuti\", \"edit_own_cuti\"]'),
(2, 'admin', 'Administrator yang dapat mengelola cuti pegawai', '[\"view_all_cuti\", \"approve_cuti\", \"reject_cuti\", \"manage_users\"]'),
(3, 'super admin', 'Super Administrator dengan akses penuh', '[\"full_access\", \"manage_settings\", \"manage_admins\", \"system_config\"]');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_list_kontak_chat`
-- (See below for the actual view)
--
CREATE TABLE `view_list_kontak_chat` (
`id_user` varchar(256)
,`username` varchar(30)
,`email` varchar(50)
,`nama_lengkap` varchar(50)
,`nip` varchar(50)
,`jabatan` varchar(50)
,`user_level` varchar(30)
,`id_user_level` int(11)
,`is_active` tinyint(1)
,`last_message_time` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_complete`
-- (See below for the actual view)
--
CREATE TABLE `view_user_complete` (
`id_user` varchar(256)
,`username` varchar(30)
,`email` varchar(50)
,`id_user_level` int(11)
,`id_atasan` varchar(256)
,`is_active` tinyint(1)
,`id_user_detail` varchar(256)
,`nama_lengkap` varchar(50)
,`nip` varchar(50)
,`pangkat` varchar(50)
,`jabatan` varchar(50)
,`sisa_cuti` int(11)
,`jatah_cuti` int(11)
,`tahun_cuti` int(11)
,`foto` varchar(255)
,`no_telp` varchar(30)
,`alamat` text
,`id_jenis_kelamin` int(11)
,`user_level` varchar(30)
,`nama_atasan` varchar(50)
,`jenis_kelamin` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `view_list_kontak_chat`
--
DROP TABLE IF EXISTS `view_list_kontak_chat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_list_kontak_chat`  AS SELECT `u`.`id_user` AS `id_user`, `u`.`username` AS `username`, `u`.`email` AS `email`, `ud`.`nama_lengkap` AS `nama_lengkap`, `ud`.`nip` AS `nip`, `ud`.`jabatan` AS `jabatan`, `ul`.`user_level` AS `user_level`, `u`.`id_user_level` AS `id_user_level`, `u`.`is_active` AS `is_active`, (select max(`chat`.`created_at`) from `chat` where `chat`.`id_pengirim` = `u`.`id_user` or `chat`.`id_penerima` = `u`.`id_user`) AS `last_message_time` FROM ((`user` `u` left join `user_detail` `ud` on(`u`.`id_user_detail` = `ud`.`id_user_detail`)) left join `user_level` `ul` on(`u`.`id_user_level` = `ul`.`id_user_level`)) WHERE `u`.`is_active` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_complete`
--
DROP TABLE IF EXISTS `view_user_complete`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_complete`  AS SELECT `u`.`id_user` AS `id_user`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`id_user_level` AS `id_user_level`, `u`.`id_atasan` AS `id_atasan`, `u`.`is_active` AS `is_active`, `ud`.`id_user_detail` AS `id_user_detail`, `ud`.`nama_lengkap` AS `nama_lengkap`, `ud`.`nip` AS `nip`, `ud`.`pangkat` AS `pangkat`, `ud`.`jabatan` AS `jabatan`, `ud`.`sisa_cuti` AS `sisa_cuti`, `ud`.`jatah_cuti` AS `jatah_cuti`, `ud`.`tahun_cuti` AS `tahun_cuti`, `ud`.`foto` AS `foto`, `ud`.`no_telp` AS `no_telp`, `ud`.`alamat` AS `alamat`, `ud`.`id_jenis_kelamin` AS `id_jenis_kelamin`, `ul`.`user_level` AS `user_level`, `atasan`.`nama_lengkap` AS `nama_atasan`, `jk`.`jenis_kelamin` AS `jenis_kelamin` FROM (((((`user` `u` left join `user_detail` `ud` on(`u`.`id_user_detail` = `ud`.`id_user_detail`)) left join `user_level` `ul` on(`u`.`id_user_level` = `ul`.`id_user_level`)) left join `user` `atasan_user` on(`u`.`id_atasan` = `atasan_user`.`id_user`)) left join `user_detail` `atasan` on(`atasan_user`.`id_user_detail` = `atasan`.`id_user_detail`)) left join `jenis_kelamin` `jk` on(`ud`.`id_jenis_kelamin` = `jk`.`id_jenis_kelamin`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`);

--
-- Indexes for table `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id_cuti`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_jenis_cuti` (`id_jenis_cuti`),
  ADD KEY `idx_status` (`id_status_cuti`),
  ADD KEY `idx_tgl_diajukan` (`tgl_diajukan`),
  ADD KEY `fk_approved_by` (`approved_by`),
  ADD KEY `fk_approved_by_atasan` (`approved_by_atasan`);

--
-- Indexes for table `jenis_cuti`
--
ALTER TABLE `jenis_cuti`
  ADD PRIMARY KEY (`id_jenis_cuti`);

--
-- Indexes for table `jenis_kelamin`
--
ALTER TABLE `jenis_kelamin`
  ADD PRIMARY KEY (`id_jenis_kelamin`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `status_cuti`
--
ALTER TABLE `status_cuti`
  ADD PRIMARY KEY (`id_status_cuti`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_level` (`id_user_level`),
  ADD KEY `idx_user_detail` (`id_user_detail`),
  ADD KEY `idx_atasan` (`id_atasan`);

--
-- Indexes for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`id_user_detail`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `idx_jenis_kelamin` (`id_jenis_kelamin`),
  ADD KEY `idx_atasan` (`id_atasan`);

--
-- Indexes for table `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id_user_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_cuti`
--
ALTER TABLE `jenis_cuti`
  MODIFY `id_jenis_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_kelamin`
--
ALTER TABLE `jenis_kelamin`
  MODIFY `id_jenis_kelamin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `status_cuti`
--
ALTER TABLE `status_cuti`
  MODIFY `id_status_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_level`
--
ALTER TABLE `user_level`
  MODIFY `id_user_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_pengirim`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_penerima`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `cuti`
--
ALTER TABLE `cuti`
  ADD CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_approved_by_atasan` FOREIGN KEY (`approved_by_atasan`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cuti_jenis` FOREIGN KEY (`id_jenis_cuti`) REFERENCES `jenis_cuti` (`id_jenis_cuti`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cuti_status` FOREIGN KEY (`id_status_cuti`) REFERENCES `status_cuti` (`id_status_cuti`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cuti_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_atasan` FOREIGN KEY (`id_atasan`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_detail` FOREIGN KEY (`id_user_detail`) REFERENCES `user_detail` (`id_user_detail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_level` FOREIGN KEY (`id_user_level`) REFERENCES `user_level` (`id_user_level`) ON UPDATE CASCADE;

--
-- Constraints for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD CONSTRAINT `fk_atasan` FOREIGN KEY (`id_atasan`) REFERENCES `user_detail` (`id_user_detail`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jenis_kelamin` FOREIGN KEY (`id_jenis_kelamin`) REFERENCES `jenis_kelamin` (`id_jenis_kelamin`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
