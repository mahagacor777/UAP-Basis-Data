-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2026 at 12:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simdk`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

CREATE TABLE `cuti` (
  `id_cuti` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `jenis_cuti` enum('tahunan','sakit','melahirkan','penting','khusus') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jumlah_hari` tinyint NOT NULL,
  `alasan` text,
  `status` enum('menunggu','disetujui','ditolak','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `disetujui_oleh` int DEFAULT NULL,
  `tanggal_approval` datetime DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cuti`
--

INSERT INTO `cuti` (`id_cuti`, `id_karyawan`, `jenis_cuti`, `tanggal_mulai`, `tanggal_selesai`, `jumlah_hari`, `alasan`, `status`, `disetujui_oleh`, `tanggal_approval`, `dibuat_pada`) VALUES
(1, 4, 'tahunan', '2026-06-10', '2026-06-12', 3, 'Acara keluarga', 'disetujui', NULL, NULL, '2026-06-04 19:25:56'),
(2, 8, 'tahunan', '2026-06-09', '2026-06-11', 3, 'Acara keluarga', 'ditolak', NULL, NULL, '2026-06-04 19:25:56'),
(3, 12, 'tahunan', '2026-06-08', '2026-06-10', 3, 'Acara keluarga', 'menunggu', NULL, NULL, '2026-06-04 19:25:56'),
(4, 16, 'tahunan', '2026-06-07', '2026-06-09', 3, 'Acara keluarga', 'disetujui', NULL, NULL, '2026-06-04 19:25:56'),
(5, 20, 'tahunan', '2026-06-06', '2026-06-08', 3, 'Acara keluarga', 'ditolak', NULL, NULL, '2026-06-04 19:25:56'),
(6, 24, 'tahunan', '2026-06-10', '2026-06-12', 3, 'Acara keluarga', 'menunggu', NULL, NULL, '2026-06-04 19:25:56'),
(7, 28, 'tahunan', '2026-06-09', '2026-06-11', 3, 'Acara keluarga', 'disetujui', NULL, NULL, '2026-06-04 19:25:56'),
(8, 32, 'tahunan', '2026-06-08', '2026-06-10', 3, 'Acara keluarga', 'ditolak', NULL, NULL, '2026-06-04 19:25:56'),
(9, 36, 'tahunan', '2026-06-07', '2026-06-09', 3, 'Acara keluarga', 'menunggu', NULL, NULL, '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id_departemen` int NOT NULL,
  `kode_dept` varchar(10) NOT NULL,
  `nama_departemen` varchar(100) NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id_departemen`, `kode_dept`, `nama_departemen`, `lokasi`, `dibuat_pada`, `diubah_pada`) VALUES
(1, 'IT', 'Teknologi Informasi', 'Gedung A, Lt. 3', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(2, 'HRD', 'Sumber Daya Manusia', 'Gedung A, Lt. 2', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(3, 'FIN', 'Keuangan', 'Gedung B, Lt. 1', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(4, 'MKT', 'Pemasaran', 'Gedung B, Lt. 2', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(5, 'OPS', 'Operasional', 'Gedung C, Lt. 1', '2026-06-04 19:25:56', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `kode_jabatan` varchar(10) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `level_jabatan` varchar(20) NOT NULL,
  `gaji_pokok_min` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gaji_pokok_max` decimal(15,2) NOT NULL DEFAULT '0.00',
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `kode_jabatan`, `nama_jabatan`, `level_jabatan`, `gaji_pokok_min`, `gaji_pokok_max`, `dibuat_pada`, `diubah_pada`) VALUES
(1, 'M-IT', 'Manajer IT', 'manajer', '12000000.00', '18000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(2, 'M-HRD', 'Manajer HRD', 'manajer', '10000000.00', '15000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(3, 'M-FIN', 'Manajer Keuangan', 'manajer', '11000000.00', '16000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(4, 'M-MKT', 'Manajer Marketing', 'manajer', '10000000.00', '15000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(5, 'S-IT', 'Software Engineer', 'staff', '6000000.00', '11000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(6, 'A-IT', 'System Administrator', 'staff', '5500000.00', '10000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(7, 'S-HRD', 'HR Specialist', 'staff', '5000000.00', '8500000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(8, 'S-FIN', 'Accountant', 'staff', '5500000.00', '9000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(9, 'S-MKT', 'Sales Executive', 'staff', '4500000.00', '8000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(10, 'S-OPS', 'Staff Operasional', 'staff', '4000000.00', '7000000.00', '2026-06-04 19:25:56', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int NOT NULL,
  `nik_ktp` varchar(20) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_aktif` enum('aktif','tidak_aktif','pensiun','resign') NOT NULL DEFAULT 'aktif',
  `id_departemen` int NOT NULL,
  `id_jabatan` int NOT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nik_ktp`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_telepon`, `email`, `tanggal_masuk`, `status_aktif`, `id_departemen`, `id_jabatan`, `foto_url`, `dibuat_pada`, `diubah_pada`) VALUES
(1, '3273012345670001', 'Ical', 'L', 'Jakarta', '2003-06-04', 'Jl. Ical No. 10, Jakarta', '081200000000', 'ical@email.com', '2025-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(2, '3273012345670002', 'Hiro', 'L', 'Jakarta', '2002-06-04', 'Jl. Hiro No. 11, Jakarta', '081200000001', 'hiro@email.com', '2024-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(3, '3273012345670003', 'Mayang', 'P', 'Jakarta', '2001-06-04', 'Jl. Mayang No. 12, Jakarta', '081200000002', 'mayang@email.com', '2023-06-04', 'aktif', 2, 2, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(4, '3273012345670004', 'Ajeng', 'P', 'Jakarta', '2000-06-04', 'Jl. Ajeng No. 13, Jakarta', '081200000003', 'ajeng@email.com', '2022-06-04', 'aktif', 3, 3, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(5, '3273012345670005', 'Isma', 'P', 'Jakarta', '1999-06-04', 'Jl. Isma No. 14, Jakarta', '081200000004', 'isma@email.com', '2025-06-04', 'aktif', 2, 7, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(6, '3273012345670006', 'Grace', 'P', 'Jakarta', '1998-06-04', 'Jl. Grace No. 15, Jakarta', '081200000005', 'grace@email.com', '2024-06-04', 'aktif', 2, 7, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(7, '3273012345670007', 'Julia', 'P', 'Jakarta', '1997-06-04', 'Jl. Julia No. 16, Jakarta', '081200000006', 'julia@email.com', '2023-06-04', 'aktif', 3, 8, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(8, '3273012345670008', 'Habiburahman', 'L', 'Jakarta', '1996-06-04', 'Jl. Habiburahman No. 17, Jakarta', '081200000007', 'habiburahman@email.com', '2022-06-04', 'aktif', 1, 6, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(9, '3273012345670009', 'Kineta', 'P', 'Jakarta', '1995-06-04', 'Jl. Kineta No. 18, Jakarta', '081200000008', 'kineta@email.com', '2025-06-04', 'aktif', 4, 4, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(10, '3273012345670010', 'Maya', 'P', 'Jakarta', '1994-06-04', 'Jl. Maya No. 19, Jakarta', '081200000009', 'maya@email.com', '2024-06-04', 'aktif', 4, 9, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(11, '3273012345670011', 'Husen', 'L', 'Jakarta', '1993-06-04', 'Jl. Husen No. 20, Jakarta', '081200000010', 'husen@email.com', '2023-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(12, '3273012345670012', 'Aldi', 'L', 'Jakarta', '1992-06-04', 'Jl. Aldi No. 21, Jakarta', '081200000011', 'aldi@email.com', '2022-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(13, '3273012345670013', 'Khoirotun', 'P', 'Jakarta', '1991-06-04', 'Jl. Khoirotun No. 22, Jakarta', '081200000012', 'khoirotun@email.com', '2025-06-04', 'aktif', 3, 8, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(14, '3273012345670014', 'Ikhsyan', 'L', 'Jakarta', '1990-06-04', 'Jl. Ikhsyan No. 23, Jakarta', '081200000013', 'ikhsyan@email.com', '2024-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(15, '3273012345670015', 'Riska', 'P', 'Jakarta', '1989-06-04', 'Jl. Riska No. 24, Jakarta', '081200000014', 'riska@email.com', '2023-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(16, '3273012345670016', 'Ratu', 'P', 'Jakarta', '2003-06-04', 'Jl. Ratu No. 25, Jakarta', '081200000015', 'ratu@email.com', '2022-06-04', 'aktif', 2, 7, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(17, '3273012345670017', 'Nia', 'P', 'Jakarta', '2002-06-04', 'Jl. Nia No. 26, Jakarta', '081200000016', 'nia@email.com', '2025-06-04', 'aktif', 4, 9, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(18, '3273012345670018', 'Melan', 'P', 'Jakarta', '2001-06-04', 'Jl. Melan No. 27, Jakarta', '081200000017', 'melan@email.com', '2024-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(19, '3273012345670019', 'Nadia', 'P', 'Jakarta', '2000-06-04', 'Jl. Nadia No. 28, Jakarta', '081200000018', 'nadia@email.com', '2023-06-04', 'aktif', 4, 9, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(20, '3273012345670020', 'Tety', 'P', 'Jakarta', '1999-06-04', 'Jl. Tety No. 29, Jakarta', '081200000019', 'tety@email.com', '2022-06-04', 'aktif', 3, 8, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(21, '3273012345670021', 'Siti', 'P', 'Jakarta', '1998-06-04', 'Jl. Siti No. 30, Jakarta', '081200000020', 'siti@email.com', '2025-06-04', 'aktif', 2, 7, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(22, '3273012345670022', 'Revan', 'L', 'Jakarta', '1997-06-04', 'Jl. Revan No. 31, Jakarta', '081200000021', 'revan@email.com', '2024-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(23, '3273012345670023', 'Myranda', 'P', 'Jakarta', '1996-06-04', 'Jl. Myranda No. 32, Jakarta', '081200000022', 'myranda@email.com', '2023-06-04', 'aktif', 4, 9, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(24, '3273012345670024', 'Jeny', 'P', 'Jakarta', '1995-06-04', 'Jl. Jeny No. 33, Jakarta', '081200000023', 'jeny@email.com', '2022-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(25, '3273012345670025', 'Sari', 'P', 'Jakarta', '1994-06-04', 'Jl. Sari No. 34, Jakarta', '081200000024', 'sari@email.com', '2025-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(26, '3273012345670026', 'Helsa', 'P', 'Jakarta', '1993-06-04', 'Jl. Helsa No. 35, Jakarta', '081200000025', 'helsa@email.com', '2024-06-04', 'aktif', 3, 8, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(27, '3273012345670027', 'Khalid', 'L', 'Jakarta', '1992-06-04', 'Jl. Khalid No. 36, Jakarta', '081200000026', 'khalid@email.com', '2023-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(28, '3273012345670028', 'Febian', 'L', 'Jakarta', '1991-06-04', 'Jl. Febian No. 37, Jakarta', '081200000027', 'febian@email.com', '2022-06-04', 'aktif', 4, 9, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(29, '3273012345670029', 'Suhaira', 'P', 'Jakarta', '1990-06-04', 'Jl. Suhaira No. 38, Jakarta', '081200000028', 'suhaira@email.com', '2025-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(30, '3273012345670030', 'Bintang', 'L', 'Jakarta', '1989-06-04', 'Jl. Bintang No. 39, Jakarta', '081200000029', 'bintang@email.com', '2024-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(31, '3273012345670031', 'Michael', 'L', 'Jakarta', '2003-06-04', 'Jl. Michael No. 40, Jakarta', '081200000030', 'michael@email.com', '2023-06-04', 'aktif', 1, 5, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(32, '3273012345670032', 'Nabil', 'L', 'Jakarta', '2002-06-04', 'Jl. Nabil No. 41, Jakarta', '081200000031', 'nabil@email.com', '2022-06-04', 'aktif', 1, 6, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(33, '3273012345670033', 'Arga', 'L', 'Jakarta', '2001-06-04', 'Jl. Arga No. 42, Jakarta', '081200000032', 'arga@email.com', '2025-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(34, '3273012345670034', 'Salsabil', 'P', 'Jakarta', '2000-06-04', 'Jl. Salsabil No. 43, Jakarta', '081200000033', 'salsabil@email.com', '2024-06-04', 'aktif', 2, 7, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(35, '3273012345670035', 'Wenno', 'L', 'Jakarta', '1999-06-04', 'Jl. Wenno No. 44, Jakarta', '081200000034', 'wenno@email.com', '2023-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(36, '3273012345670036', 'Fajri', 'L', 'Jakarta', '1998-06-04', 'Jl. Fajri No. 45, Jakarta', '081200000035', 'fajri@email.com', '2022-06-04', 'aktif', 5, 10, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran`
--

CREATE TABLE `kehadiran` (
  `id_kehadiran` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpa','wfh','dinas_luar') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kehadiran`
--

INSERT INTO `kehadiran` (`id_kehadiran`, `id_karyawan`, `tanggal`, `jam_masuk`, `jam_keluar`, `status`, `keterangan`, `dibuat_pada`) VALUES
(1, 1, '2026-06-04', '07:46:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(2, 1, '2026-06-03', '07:46:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(3, 1, '2026-06-02', '07:46:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(4, 1, '2026-06-01', '07:46:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(5, 1, '2026-05-29', '07:46:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(6, 2, '2026-06-04', '07:47:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(7, 2, '2026-06-03', '07:47:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(8, 2, '2026-06-02', '07:47:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(9, 2, '2026-06-01', '07:47:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(10, 2, '2026-05-29', '07:47:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(11, 3, '2026-06-04', '07:48:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(12, 3, '2026-06-03', '07:48:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(13, 3, '2026-06-02', '07:48:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(14, 3, '2026-06-01', '07:48:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(15, 3, '2026-05-29', '07:48:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(16, 4, '2026-06-04', '07:49:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(17, 4, '2026-06-03', '07:49:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(18, 4, '2026-06-02', '07:49:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(19, 4, '2026-06-01', '07:49:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(20, 4, '2026-05-29', '07:49:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(21, 5, '2026-06-04', '07:50:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(22, 5, '2026-06-03', '07:50:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(23, 5, '2026-06-02', '07:50:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(24, 5, '2026-06-01', '07:50:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(25, 5, '2026-05-29', '07:50:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(26, 6, '2026-06-04', '07:51:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(27, 6, '2026-06-03', '07:51:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(28, 6, '2026-06-02', '07:51:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(29, 6, '2026-06-01', '07:51:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(30, 6, '2026-05-29', '07:51:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(31, 7, '2026-06-04', '07:52:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(32, 7, '2026-06-03', '07:52:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(33, 7, '2026-06-02', '07:52:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(34, 7, '2026-06-01', '07:52:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(35, 7, '2026-05-29', '07:52:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(36, 8, '2026-06-04', '07:53:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(37, 8, '2026-06-03', '07:53:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(38, 8, '2026-06-02', '07:53:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(39, 8, '2026-06-01', '07:53:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(40, 8, '2026-05-29', '07:53:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(41, 9, '2026-06-04', '07:54:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(42, 9, '2026-06-03', '07:54:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(43, 9, '2026-06-02', '07:54:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(44, 9, '2026-06-01', '07:54:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(45, 9, '2026-05-29', '07:54:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(46, 10, '2026-06-04', '07:55:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(47, 10, '2026-06-03', '07:55:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(48, 10, '2026-06-02', '07:55:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(49, 10, '2026-06-01', '07:55:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(50, 10, '2026-05-29', '07:55:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(51, 11, '2026-06-04', '07:56:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(52, 11, '2026-06-03', '07:56:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(53, 11, '2026-06-02', '07:56:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(54, 11, '2026-06-01', '07:56:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(55, 11, '2026-05-29', '07:56:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(56, 12, '2026-06-04', '07:57:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(57, 12, '2026-06-03', '07:57:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(58, 12, '2026-06-02', '07:57:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(59, 12, '2026-06-01', '07:57:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(60, 12, '2026-05-29', NULL, NULL, 'sakit', 'Sakit demam', '2026-06-04 19:25:56'),
(61, 13, '2026-06-04', '07:58:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(62, 13, '2026-06-03', '07:58:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(63, 13, '2026-06-02', '07:58:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(64, 13, '2026-06-01', '07:58:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(65, 13, '2026-05-29', '07:58:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(66, 14, '2026-06-04', '07:45:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(67, 14, '2026-06-03', '07:45:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(68, 14, '2026-06-02', '07:45:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(69, 14, '2026-06-01', '07:45:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(70, 14, '2026-05-29', '07:45:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(71, 15, '2026-06-04', '07:46:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(72, 15, '2026-06-03', '07:46:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(73, 15, '2026-06-02', '07:46:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(74, 15, '2026-06-01', NULL, NULL, 'izin', 'Kepentingan keluarga', '2026-06-04 19:25:56'),
(75, 15, '2026-05-29', '07:46:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(76, 16, '2026-06-04', '07:47:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(77, 16, '2026-06-03', '07:47:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(78, 16, '2026-06-02', '07:47:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(79, 16, '2026-06-01', '07:47:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(80, 16, '2026-05-29', '07:47:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(81, 17, '2026-06-04', '07:48:00', '17:22:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(82, 17, '2026-06-03', '07:48:00', '17:22:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(83, 17, '2026-06-02', '07:48:00', '17:22:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(84, 17, '2026-06-01', '07:48:00', '17:22:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(85, 17, '2026-05-29', '07:48:00', '17:22:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(86, 18, '2026-06-04', '07:49:00', '17:23:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(87, 18, '2026-06-03', '07:49:00', '17:23:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(88, 18, '2026-06-02', '07:49:00', '17:23:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(89, 18, '2026-06-01', '07:49:00', '17:23:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(90, 18, '2026-05-29', '07:49:00', '17:23:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(91, 19, '2026-06-04', '07:50:00', '17:24:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(92, 19, '2026-06-03', '07:50:00', '17:24:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(93, 19, '2026-06-02', '07:50:00', '17:24:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(94, 19, '2026-06-01', '07:50:00', '17:24:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(95, 19, '2026-05-29', '07:50:00', '17:24:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(96, 20, '2026-06-04', '07:51:00', '17:05:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(97, 20, '2026-06-03', '07:51:00', '17:05:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(98, 20, '2026-06-02', '07:51:00', '17:05:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(99, 20, '2026-06-01', '07:51:00', '17:05:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(100, 20, '2026-05-29', '07:51:00', '17:05:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(101, 21, '2026-06-04', '07:52:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(102, 21, '2026-06-03', '07:52:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(103, 21, '2026-06-02', '07:52:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(104, 21, '2026-06-01', '07:52:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(105, 21, '2026-05-29', '07:52:00', '17:06:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(106, 22, '2026-06-04', '07:53:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(107, 22, '2026-06-03', '07:53:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(108, 22, '2026-06-02', '07:53:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(109, 22, '2026-06-01', '07:53:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(110, 22, '2026-05-29', '07:53:00', '17:07:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(111, 23, '2026-06-04', '07:54:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(112, 23, '2026-06-03', '07:54:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(113, 23, '2026-06-02', '07:54:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(114, 23, '2026-06-01', '07:54:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(115, 23, '2026-05-29', '07:54:00', '17:08:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(116, 24, '2026-06-04', '07:55:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(117, 24, '2026-06-03', '07:55:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(118, 24, '2026-06-02', '07:55:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(119, 24, '2026-06-01', '07:55:00', '17:09:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(120, 24, '2026-05-29', NULL, NULL, 'sakit', 'Sakit demam', '2026-06-04 19:25:56'),
(121, 25, '2026-06-04', '07:56:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(122, 25, '2026-06-03', '07:56:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(123, 25, '2026-06-02', '07:56:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(124, 25, '2026-06-01', '07:56:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(125, 25, '2026-05-29', '07:56:00', '17:10:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(126, 26, '2026-06-04', '07:57:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(127, 26, '2026-06-03', '07:57:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(128, 26, '2026-06-02', '07:57:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(129, 26, '2026-06-01', '07:57:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(130, 26, '2026-05-29', '07:57:00', '17:11:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(131, 27, '2026-06-04', '07:58:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(132, 27, '2026-06-03', '07:58:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(133, 27, '2026-06-02', '07:58:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(134, 27, '2026-06-01', '07:58:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(135, 27, '2026-05-29', '07:58:00', '17:12:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(136, 28, '2026-06-04', '07:45:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(137, 28, '2026-06-03', '07:45:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(138, 28, '2026-06-02', '07:45:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(139, 28, '2026-06-01', '07:45:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(140, 28, '2026-05-29', '07:45:00', '17:13:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(141, 29, '2026-06-04', '07:46:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(142, 29, '2026-06-03', '07:46:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(143, 29, '2026-06-02', '07:46:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(144, 29, '2026-06-01', '07:46:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(145, 29, '2026-05-29', '07:46:00', '17:14:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(146, 30, '2026-06-04', '07:47:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(147, 30, '2026-06-03', '07:47:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(148, 30, '2026-06-02', '07:47:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(149, 30, '2026-06-01', NULL, NULL, 'izin', 'Kepentingan keluarga', '2026-06-04 19:25:56'),
(150, 30, '2026-05-29', '07:47:00', '17:15:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(151, 31, '2026-06-04', '07:48:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(152, 31, '2026-06-03', '07:48:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(153, 31, '2026-06-02', '07:48:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(154, 31, '2026-06-01', '07:48:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(155, 31, '2026-05-29', '07:48:00', '17:16:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(156, 32, '2026-06-04', '07:49:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(157, 32, '2026-06-03', '07:49:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(158, 32, '2026-06-02', '07:49:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(159, 32, '2026-06-01', '07:49:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(160, 32, '2026-05-29', '07:49:00', '17:17:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(161, 33, '2026-06-04', '07:50:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(162, 33, '2026-06-03', '07:50:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(163, 33, '2026-06-02', '07:50:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(164, 33, '2026-06-01', '07:50:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(165, 33, '2026-05-29', '07:50:00', '17:18:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(166, 34, '2026-06-04', '07:51:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(167, 34, '2026-06-03', '07:51:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(168, 34, '2026-06-02', '07:51:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(169, 34, '2026-06-01', '07:51:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(170, 34, '2026-05-29', '07:51:00', '17:19:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(171, 35, '2026-06-04', '07:52:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(172, 35, '2026-06-03', '07:52:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(173, 35, '2026-06-02', '07:52:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(174, 35, '2026-06-01', '07:52:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(175, 35, '2026-05-29', '07:52:00', '17:20:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(176, 36, '2026-06-04', '07:53:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(177, 36, '2026-06-03', '07:53:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(178, 36, '2026-06-02', '07:53:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(179, 36, '2026-06-01', '07:53:00', '17:21:00', 'hadir', 'Hadir tepat waktu', '2026-06-04 19:25:56'),
(180, 36, '2026-05-29', NULL, NULL, 'sakit', 'Sakit demam', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `kontrak_kerja`
--

CREATE TABLE `kontrak_kerja` (
  `id_kontrak` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `jenis_kontrak` enum('PKWT','PKWTT','Magang','Outsourcing') NOT NULL,
  `nomor_kontrak` varchar(50) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_kontrak` enum('aktif','berakhir','diperbarui') NOT NULL DEFAULT 'aktif',
  `catatan` text,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kontrak_kerja`
--

INSERT INTO `kontrak_kerja` (`id_kontrak`, `id_karyawan`, `jenis_kontrak`, `nomor_kontrak`, `tanggal_mulai`, `tanggal_selesai`, `status_kontrak`, `catatan`, `dibuat_pada`) VALUES
(1, 1, 'PKWTT', 'CTR/2026/06/0001', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Ical', '2026-06-04 19:25:56'),
(2, 2, 'PKWT', 'CTR/2026/06/0002', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Hiro', '2026-06-04 19:25:56'),
(3, 3, 'PKWTT', 'CTR/2026/06/0003', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Mayang', '2026-06-04 19:25:56'),
(4, 4, 'PKWT', 'CTR/2026/06/0004', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Ajeng', '2026-06-04 19:25:56'),
(5, 5, 'PKWTT', 'CTR/2026/06/0005', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Isma', '2026-06-04 19:25:56'),
(6, 6, 'PKWT', 'CTR/2026/06/0006', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Grace', '2026-06-04 19:25:56'),
(7, 7, 'PKWTT', 'CTR/2026/06/0007', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Julia', '2026-06-04 19:25:56'),
(8, 8, 'PKWT', 'CTR/2026/06/0008', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Habiburahman', '2026-06-04 19:25:56'),
(9, 9, 'PKWTT', 'CTR/2026/06/0009', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Kineta', '2026-06-04 19:25:56'),
(10, 10, 'PKWT', 'CTR/2026/06/0010', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Maya', '2026-06-04 19:25:56'),
(11, 11, 'PKWTT', 'CTR/2026/06/0011', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Husen', '2026-06-04 19:25:56'),
(12, 12, 'PKWT', 'CTR/2026/06/0012', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Aldi', '2026-06-04 19:25:56'),
(13, 13, 'PKWTT', 'CTR/2026/06/0013', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Khoirotun', '2026-06-04 19:25:56'),
(14, 14, 'PKWT', 'CTR/2026/06/0014', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Ikhsyan', '2026-06-04 19:25:56'),
(15, 15, 'PKWTT', 'CTR/2026/06/0015', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Riska', '2026-06-04 19:25:56'),
(16, 16, 'PKWT', 'CTR/2026/06/0016', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Ratu', '2026-06-04 19:25:56'),
(17, 17, 'PKWTT', 'CTR/2026/06/0017', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Nia', '2026-06-04 19:25:56'),
(18, 18, 'PKWT', 'CTR/2026/06/0018', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Melan', '2026-06-04 19:25:56'),
(19, 19, 'PKWTT', 'CTR/2026/06/0019', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Nadia', '2026-06-04 19:25:56'),
(20, 20, 'PKWT', 'CTR/2026/06/0020', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Tety', '2026-06-04 19:25:56'),
(21, 21, 'PKWTT', 'CTR/2026/06/0021', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Siti', '2026-06-04 19:25:56'),
(22, 22, 'PKWT', 'CTR/2026/06/0022', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Revan', '2026-06-04 19:25:56'),
(23, 23, 'PKWTT', 'CTR/2026/06/0023', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Myranda', '2026-06-04 19:25:56'),
(24, 24, 'PKWT', 'CTR/2026/06/0024', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Jeny', '2026-06-04 19:25:56'),
(25, 25, 'PKWTT', 'CTR/2026/06/0025', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Sari', '2026-06-04 19:25:56'),
(26, 26, 'PKWT', 'CTR/2026/06/0026', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Helsa', '2026-06-04 19:25:56'),
(27, 27, 'PKWTT', 'CTR/2026/06/0027', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Khalid', '2026-06-04 19:25:56'),
(28, 28, 'PKWT', 'CTR/2026/06/0028', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Febian', '2026-06-04 19:25:56'),
(29, 29, 'PKWTT', 'CTR/2026/06/0029', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Suhaira', '2026-06-04 19:25:56'),
(30, 30, 'PKWT', 'CTR/2026/06/0030', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Bintang', '2026-06-04 19:25:56'),
(31, 31, 'PKWTT', 'CTR/2026/06/0031', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Michael', '2026-06-04 19:25:56'),
(32, 32, 'PKWT', 'CTR/2026/06/0032', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Nabil', '2026-06-04 19:25:56'),
(33, 33, 'PKWTT', 'CTR/2026/06/0033', '2025-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Arga', '2026-06-04 19:25:56'),
(34, 34, 'PKWT', 'CTR/2026/06/0034', '2024-06-04', '2026-06-04', 'aktif', 'Kontrak kerja karyawan Salsabil', '2026-06-04 19:25:56'),
(35, 35, 'PKWTT', 'CTR/2026/06/0035', '2023-06-04', NULL, 'aktif', 'Kontrak kerja karyawan Wenno', '2026-06-04 19:25:56'),
(36, 36, 'PKWT', 'CTR/2026/06/0036', '2022-06-04', '2024-06-04', 'aktif', 'Kontrak kerja karyawan Fajri', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan_karyawan`
--

CREATE TABLE `pendidikan_karyawan` (
  `id_pendidikan` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `jenjang` enum('SD','SMP','SMA/SMK','D1','D2','D3','S1','S2','S3') NOT NULL,
  `nama_institusi` varchar(150) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `tahun_masuk` year NOT NULL,
  `tahun_lulus` year DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pendidikan_karyawan`
--

INSERT INTO `pendidikan_karyawan` (`id_pendidikan`, `id_karyawan`, `jenjang`, `nama_institusi`, `jurusan`, `tahun_masuk`, `tahun_lulus`, `ipk`) VALUES
(1, 1, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2018, 2021, NULL),
(2, 1, 'S1', 'Institut Teknologi Bandung', 'Ilmu Komputer', 2021, 2025, '3.10'),
(3, 2, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2017, 2020, NULL),
(4, 2, 'S1', 'Universitas Gadjah Mada', 'Sistem Informasi', 2020, 2024, '3.20'),
(5, 3, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2016, 2019, NULL),
(6, 3, 'S1', 'Universitas Padjadjaran', 'Akuntansi', 2019, 2023, '3.30'),
(7, 4, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2015, 2018, NULL),
(8, 4, 'S1', 'Universitas Diponegoro', 'Manajemen', 2018, 2022, '3.40'),
(9, 5, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2014, 2017, NULL),
(10, 5, 'S1', 'Universitas Airlangga', 'Psikologi', 2017, 2021, '3.50'),
(11, 6, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2013, 2016, NULL),
(12, 6, 'S1', 'Universitas Brawijaya', 'Ilmu Komunikasi', 2016, 2020, '3.60'),
(13, 7, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2012, 2015, NULL),
(14, 7, 'S1', 'Institut Teknologi Sepuluh Nopember', 'Teknik Industri', 2015, 2019, '3.70'),
(15, 8, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2011, 2014, NULL),
(16, 8, 'S1', 'Universitas Indonesia', 'Teknik Informatika', 2014, 2018, '3.80'),
(17, 9, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2010, 2013, NULL),
(18, 9, 'S1', 'Institut Teknologi Bandung', 'Ilmu Komputer', 2013, 2017, '3.90'),
(19, 10, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2009, 2012, NULL),
(20, 10, 'S1', 'Universitas Gadjah Mada', 'Sistem Informasi', 2012, 2016, '3.00'),
(21, 11, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2008, 2011, NULL),
(22, 11, 'S1', 'Universitas Padjadjaran', 'Akuntansi', 2011, 2015, '3.10'),
(23, 12, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2007, 2010, NULL),
(24, 12, 'S1', 'Universitas Diponegoro', 'Manajemen', 2010, 2014, '3.20'),
(25, 13, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2006, 2009, NULL),
(26, 13, 'S1', 'Universitas Airlangga', 'Psikologi', 2009, 2013, '3.30'),
(27, 14, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2005, 2008, NULL),
(28, 14, 'S1', 'Universitas Brawijaya', 'Ilmu Komunikasi', 2008, 2012, '3.40'),
(29, 15, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2004, 2007, NULL),
(30, 15, 'S1', 'Institut Teknologi Sepuluh Nopember', 'Teknik Industri', 2007, 2011, '3.50'),
(31, 16, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2018, 2021, NULL),
(32, 16, 'S1', 'Universitas Indonesia', 'Teknik Informatika', 2021, 2025, '3.60'),
(33, 17, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2017, 2020, NULL),
(34, 17, 'S1', 'Institut Teknologi Bandung', 'Ilmu Komputer', 2020, 2024, '3.70'),
(35, 18, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2016, 2019, NULL),
(36, 18, 'S1', 'Universitas Gadjah Mada', 'Sistem Informasi', 2019, 2023, '3.80'),
(37, 19, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2015, 2018, NULL),
(38, 19, 'S1', 'Universitas Padjadjaran', 'Akuntansi', 2018, 2022, '3.90'),
(39, 20, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2014, 2017, NULL),
(40, 20, 'S1', 'Universitas Diponegoro', 'Manajemen', 2017, 2021, '3.00'),
(41, 21, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2013, 2016, NULL),
(42, 21, 'S1', 'Universitas Airlangga', 'Psikologi', 2016, 2020, '3.10'),
(43, 22, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2012, 2015, NULL),
(44, 22, 'S1', 'Universitas Brawijaya', 'Ilmu Komunikasi', 2015, 2019, '3.20'),
(45, 23, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2011, 2014, NULL),
(46, 23, 'S1', 'Institut Teknologi Sepuluh Nopember', 'Teknik Industri', 2014, 2018, '3.30'),
(47, 24, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2010, 2013, NULL),
(48, 24, 'S1', 'Universitas Indonesia', 'Teknik Informatika', 2013, 2017, '3.40'),
(49, 25, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2009, 2012, NULL),
(50, 25, 'S1', 'Institut Teknologi Bandung', 'Ilmu Komputer', 2012, 2016, '3.50'),
(51, 26, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2008, 2011, NULL),
(52, 26, 'S1', 'Universitas Gadjah Mada', 'Sistem Informasi', 2011, 2015, '3.60'),
(53, 27, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2007, 2010, NULL),
(54, 27, 'S1', 'Universitas Padjadjaran', 'Akuntansi', 2010, 2014, '3.70'),
(55, 28, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2006, 2009, NULL),
(56, 28, 'S1', 'Universitas Diponegoro', 'Manajemen', 2009, 2013, '3.80'),
(57, 29, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2005, 2008, NULL),
(58, 29, 'S1', 'Universitas Airlangga', 'Psikologi', 2008, 2012, '3.90'),
(59, 30, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2004, 2007, NULL),
(60, 30, 'S1', 'Universitas Brawijaya', 'Ilmu Komunikasi', 2007, 2011, '3.00'),
(61, 31, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2018, 2021, NULL),
(62, 31, 'S1', 'Institut Teknologi Sepuluh Nopember', 'Teknik Industri', 2021, 2025, '3.10'),
(63, 32, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2017, 2020, NULL),
(64, 32, 'S1', 'Universitas Indonesia', 'Teknik Informatika', 2020, 2024, '3.20'),
(65, 33, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2016, 2019, NULL),
(66, 33, 'S1', 'Institut Teknologi Bandung', 'Ilmu Komputer', 2019, 2023, '3.30'),
(67, 34, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2015, 2018, NULL),
(68, 34, 'S1', 'Universitas Gadjah Mada', 'Sistem Informasi', 2018, 2022, '3.40'),
(69, 35, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2014, 2017, NULL),
(70, 35, 'S1', 'Universitas Padjadjaran', 'Akuntansi', 2017, 2021, '3.50'),
(71, 36, 'SMA/SMK', 'SMAN 1 Jakarta', 'IPA', 2013, 2016, NULL),
(72, 36, 'S1', 'Universitas Diponegoro', 'Manajemen', 2016, 2020, '3.60');

-- --------------------------------------------------------

--
-- Table structure for table `penggajian`
--

CREATE TABLE `penggajian` (
  `id_penggajian` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `periode_bulan` tinyint NOT NULL,
  `periode_tahun` year NOT NULL,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_tunjangan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `potongan_absen` decimal(15,2) NOT NULL DEFAULT '0.00',
  `potongan_lain` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gaji_bersih` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tanggal_bayar` date DEFAULT NULL,
  `status_bayar` enum('belum_dibayar','sudah_dibayar') NOT NULL DEFAULT 'belum_dibayar',
  `catatan` text,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggajian`
--

INSERT INTO `penggajian` (`id_penggajian`, `id_karyawan`, `periode_bulan`, `periode_tahun`, `gaji_pokok`, `total_tunjangan`, `potongan_absen`, `potongan_lain`, `gaji_bersih`, `tanggal_bayar`, `status_bayar`, `catatan`, `dibuat_pada`) VALUES
(1, 1, 5, 2026, '8500000.00', '700000.00', '0.00', '0.00', '9200000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(2, 2, 5, 2026, '8200000.00', '900000.00', '0.00', '0.00', '9100000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(3, 3, 5, 2026, '12500000.00', '1100000.00', '0.00', '0.00', '13600000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(4, 4, 5, 2026, '13500000.00', '1300000.00', '0.00', '0.00', '14800000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(5, 5, 5, 2026, '6000000.00', '500000.00', '0.00', '0.00', '6500000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(6, 6, 5, 2026, '6100000.00', '700000.00', '0.00', '0.00', '6800000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(7, 7, 5, 2026, '6800000.00', '900000.00', '0.00', '0.00', '7700000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(8, 8, 5, 2026, '7500000.00', '1100000.00', '0.00', '0.00', '8600000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(9, 9, 5, 2026, '12000000.00', '1300000.00', '0.00', '0.00', '13300000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(10, 10, 5, 2026, '5200000.00', '500000.00', '150000.00', '0.00', '5550000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(11, 11, 5, 2026, '4500000.00', '700000.00', '0.00', '0.00', '5200000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(12, 12, 5, 2026, '4600000.00', '900000.00', '0.00', '0.00', '5500000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(13, 13, 5, 2026, '6500000.00', '1100000.00', '0.00', '0.00', '7600000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(14, 14, 5, 2026, '7000000.00', '1300000.00', '0.00', '0.00', '8300000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(15, 15, 5, 2026, '4400000.00', '500000.00', '0.00', '0.00', '4900000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(16, 16, 5, 2026, '5800000.00', '700000.00', '0.00', '0.00', '6500000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(17, 17, 5, 2026, '5000000.00', '900000.00', '0.00', '0.00', '5900000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(18, 18, 5, 2026, '4400000.00', '1100000.00', '0.00', '0.00', '5500000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(19, 19, 5, 2026, '5100000.00', '1300000.00', '0.00', '0.00', '6400000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(20, 20, 5, 2026, '6400000.00', '500000.00', '150000.00', '0.00', '6750000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(21, 21, 5, 2026, '5700000.00', '700000.00', '0.00', '0.00', '6400000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(22, 22, 5, 2026, '7200000.00', '900000.00', '0.00', '0.00', '8100000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(23, 23, 5, 2026, '5300000.00', '1100000.00', '0.00', '0.00', '6400000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(24, 24, 5, 2026, '4500000.00', '1300000.00', '0.00', '0.00', '5800000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(25, 25, 5, 2026, '4400000.00', '500000.00', '0.00', '0.00', '4900000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(26, 26, 5, 2026, '6300000.00', '700000.00', '0.00', '0.00', '7000000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(27, 27, 5, 2026, '7100000.00', '900000.00', '0.00', '0.00', '8000000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(28, 28, 5, 2026, '4900000.00', '1100000.00', '0.00', '0.00', '6000000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(29, 29, 5, 2026, '4300000.00', '1300000.00', '0.00', '0.00', '5600000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(30, 30, 5, 2026, '4500000.00', '500000.00', '150000.00', '0.00', '4850000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(31, 31, 5, 2026, '7600000.00', '700000.00', '0.00', '0.00', '8300000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(32, 32, 5, 2026, '6000000.00', '900000.00', '0.00', '0.00', '6900000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(33, 33, 5, 2026, '4600000.00', '1100000.00', '0.00', '0.00', '5700000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(34, 34, 5, 2026, '5900000.00', '1300000.00', '0.00', '0.00', '7200000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(35, 35, 5, 2026, '4400000.00', '500000.00', '0.00', '0.00', '4900000.00', '2026-05-28', 'sudah_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56'),
(36, 36, 5, 2026, '4500000.00', '700000.00', '0.00', '0.00', '5200000.00', NULL, 'belum_dibayar', 'Gaji periode Mei 2026', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `id_karyawan` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','hrd','manajer','karyawan') NOT NULL DEFAULT 'karyawan',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `terakhir_login` datetime DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `id_karyawan`, `username`, `password_hash`, `role`, `status_aktif`, `terakhir_login`, `dibuat_pada`, `diubah_pada`) VALUES
(1, 1, 'ical', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'admin', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(2, 2, 'hiro', '$2y$10$oIBDe9C/5v3xdDqPMckqd.HcvXXvgRUB0bijH73U8jwtjPJGcj3zi', 'admin', 1, '2026-06-04 19:32:51', '2026-06-04 19:25:56', '2026-06-04 19:32:51'),
(3, 3, 'mayang', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'manajer', 1, '2026-06-04 19:28:55', '2026-06-04 19:25:56', '2026-06-04 19:28:55'),
(4, 4, 'ajeng', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'manajer', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(5, 5, 'isma', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(6, 6, 'grace', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(7, 7, 'julia', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(8, 8, 'habib', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(9, 9, 'kineta', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'manajer', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(10, 10, 'maya', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(11, 11, 'husen', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(12, 12, 'aldi', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(13, 13, 'khoirotun', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(14, 14, 'ikhsyan', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(15, 15, 'riska', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(16, 16, 'ratu', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(17, 17, 'nia', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(18, 18, 'melan', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(19, 19, 'nadia', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(20, 20, 'tety', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(21, 21, 'siti', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(22, 22, 'revan', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(23, 23, 'myranda', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(24, 24, 'jeny', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(25, 25, 'sari', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(26, 26, 'helsa', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(27, 27, 'khalid', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(28, 28, 'febian', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(29, 29, 'suhaira', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(30, 30, 'bintang', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, '2026-06-04 19:29:51', '2026-06-04 19:25:56', '2026-06-04 19:29:51'),
(31, 31, 'michael', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(32, 32, 'nabil', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(33, 33, 'arga', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(34, 34, 'salsabil', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(35, 35, 'wenno', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56'),
(36, 36, 'fajri', '$2y$10$vr5KALrCSiH4rxBp37FovOWY.nnRDCIAx33cyn17K6iNNNE26J2bi', 'karyawan', 1, NULL, '2026-06-04 19:25:56', '2026-06-04 19:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `tunjangan`
--

CREATE TABLE `tunjangan` (
  `id_tunjangan` int NOT NULL,
  `id_jabatan` int NOT NULL,
  `nama_tunjangan` varchar(100) NOT NULL,
  `jenis` enum('tetap','tidak_tetap') NOT NULL DEFAULT 'tetap',
  `nominal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tunjangan`
--

INSERT INTO `tunjangan` (`id_tunjangan`, `id_jabatan`, `nama_tunjangan`, `jenis`, `nominal`, `keterangan`) VALUES
(1, 1, 'Tunjangan Jabatan', 'tetap', '2000000.00', 'Tunjangan manajer IT'),
(2, 1, 'Tunjangan Komunikasi', 'tidak_tetap', '500000.00', 'Pulsa & Internet'),
(3, 2, 'Tunjangan Jabatan', 'tetap', '1500000.00', 'Tunjangan manajer HRD'),
(4, 3, 'Tunjangan Jabatan', 'tetap', '1800000.00', 'Tunjangan manajer keuangan'),
(5, 4, 'Tunjangan Jabatan', 'tetap', '1500000.00', 'Tunjangan manajer marketing'),
(6, 5, 'Tunjangan Keahlian', 'tetap', '1000000.00', 'Sertifikasi programmer'),
(7, 6, 'Tunjangan Keahlian', 'tetap', '800000.00', 'Sertifikasi sysadmin'),
(8, 7, 'Tunjangan Makan', 'tidak_tetap', '300000.00', 'Transport dan makan'),
(9, 8, 'Tunjangan Profesi', 'tetap', '500000.00', 'Sertifikasi Akuntan'),
(10, 9, 'Tunjangan Transport', 'tidak_tetap', '600000.00', 'Uang bensin operasional'),
(11, 10, 'Tunjangan Makan', 'tidak_tetap', '300000.00', 'Uang makan harian');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id_cuti`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id_departemen`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD KEY `id_jabatan` (`id_jabatan`),
  ADD KEY `id_departemen` (`id_departemen`);

--
-- Indexes for table `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD PRIMARY KEY (`id_kehadiran`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `kontrak_kerja`
--
ALTER TABLE `kontrak_kerja`
  ADD PRIMARY KEY (`id_kontrak`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `pendidikan_karyawan`
--
ALTER TABLE `pendidikan_karyawan`
  ADD PRIMARY KEY (`id_pendidikan`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`id_penggajian`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `tunjangan`
--
ALTER TABLE `tunjangan`
  ADD PRIMARY KEY (`id_tunjangan`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cuti`
--
ALTER TABLE `cuti`
  MODIFY `id_cuti` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id_departemen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `kehadiran`
--
ALTER TABLE `kehadiran`
  MODIFY `id_kehadiran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `kontrak_kerja`
--
ALTER TABLE `kontrak_kerja`
  MODIFY `id_kontrak` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pendidikan_karyawan`
--
ALTER TABLE `pendidikan_karyawan`
  MODIFY `id_pendidikan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `penggajian`
--
ALTER TABLE `penggajian`
  MODIFY `id_penggajian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tunjangan`
--
ALTER TABLE `tunjangan`
  MODIFY `id_tunjangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cuti`
--
ALTER TABLE `cuti`
  ADD CONSTRAINT `cuti_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`),
  ADD CONSTRAINT `karyawan_ibfk_2` FOREIGN KEY (`id_departemen`) REFERENCES `departemen` (`id_departemen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD CONSTRAINT `kehadiran_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kontrak_kerja`
--
ALTER TABLE `kontrak_kerja`
  ADD CONSTRAINT `kontrak_kerja_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendidikan_karyawan`
--
ALTER TABLE `pendidikan_karyawan`
  ADD CONSTRAINT `pendidikan_karyawan_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD CONSTRAINT `penggajian_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tunjangan`
--
ALTER TABLE `tunjangan`
  ADD CONSTRAINT `tunjangan_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
