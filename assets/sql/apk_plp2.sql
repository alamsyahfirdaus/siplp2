-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2022 at 03:14 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apk_plp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `akses_menu`
--

CREATE TABLE `akses_menu` (
  `id_akses_menu` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `jenis_pengguna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `akses_menu`
--

INSERT INTO `akses_menu` (`id_akses_menu`, `menu_id`, `jenis_pengguna_id`) VALUES
(1, 1, 1),
(6, 1, 3),
(7, 1, 4),
(8, 1, 5),
(20, 3, 1),
(4, 9, 1),
(2, 11, 1),
(5, 12, 1),
(17, 16, 1),
(18, 17, 3),
(19, 17, 4),
(21, 18, 5);

-- --------------------------------------------------------

--
-- Table structure for table `detail_penilaian`
--

CREATE TABLE `detail_penilaian` (
  `id_detail_penilaian` int(11) NOT NULL,
  `penilaian_id` int(11) NOT NULL,
  `komponen` mediumtext NOT NULL,
  `isi` int(11) DEFAULT NULL COMMENT 'aspek, indikator, pernyataan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_penilaian`
--

INSERT INTO `detail_penilaian` (`id_detail_penilaian`, `penilaian_id`, `komponen`, `isi`) VALUES
(51, 12, 'KEGIATAN PENDAHULUAN', 12),
(52, 12, 'KEGIATAN INTI', 12),
(53, 12, 'Apersepsi dan Motivasi', 51),
(54, 12, 'Mengaitkan tema/sub tema dengan pengalaman peserta didik atau pengetahuan sebelumnya', 53),
(55, 12, 'Mengajukan pertanyaan terkait tema/sub tema', 53),
(56, 12, 'Penerapan Strategi Pembelajaran yang Menarik', 52),
(57, 12, 'Menyediakan kegiatan yang memuat komponen eksplorasi', 56),
(58, 12, 'Menyediakan kegiatan yang memuat komponen elaborasi', 56),
(60, 10, 'Kedisiplinan', NULL),
(61, 10, 'Rasa tanggung jawab', NULL),
(62, 12, 'Mengaitkan tema/sub tema dengan kehidupan sehari-hari', 53),
(63, 12, 'Penyampaian Rencana Kegiatan', 51),
(64, 12, 'Menyampaikan tujuan pembelajaran', 63),
(65, 12, 'Menyampaikan metode pembelajaran', 63),
(66, 12, 'Menyediakan kegiatan yang memuat komponen konfirmasi', 56),
(67, 12, 'Pelaksanaan Rencana Pembelajaran', 52),
(68, 12, 'Melaksanakan pembelajaran sesuai dengan Kompetensi Dasar (KD) yang ditentukan', 67),
(69, 12, 'Melaksanakan kegiatan pembelajaran sesuai dengan alokasi waktu yang direncanakan', 67);

-- --------------------------------------------------------

--
-- Table structure for table `guru_pamong`
--

CREATE TABLE `guru_pamong` (
  `id_guru_pamong` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `sekolah_mitra_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guru_pamong`
--

INSERT INTO `guru_pamong` (`id_guru_pamong`, `pengguna_id`, `sekolah_mitra_id`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_penilaian`
--

CREATE TABLE `hasil_penilaian` (
  `id_hasil_penilaian` int(11) NOT NULL,
  `detail_penilaian_id` int(11) NOT NULL,
  `tahun_pelaksanaan_id` int(11) DEFAULT NULL,
  `id_pengguna_mhs` int(11) NOT NULL,
  `id_pengguna_penilai` int(11) DEFAULT NULL,
  `skor` int(11) DEFAULT NULL,
  `selesai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hasil_penilaian`
--

INSERT INTO `hasil_penilaian` (`id_hasil_penilaian`, `detail_penilaian_id`, `tahun_pelaksanaan_id`, `id_pengguna_mhs`, `id_pengguna_penilai`, `skor`, `selesai`) VALUES
(1, 54, 1, 9, 7, 4, 1),
(2, 55, 1, 9, 7, 4, 1),
(3, 62, 1, 9, 7, 4, 1),
(4, 64, 1, 9, 7, 4, 1),
(5, 65, 1, 9, 7, 4, 1),
(6, 57, 1, 9, 7, 4, 1),
(7, 58, 1, 9, 7, 4, 1),
(8, 66, 1, 9, 7, 4, 1),
(9, 68, 1, 9, 7, 4, 1),
(10, 69, 1, 9, 7, 4, 1),
(11, 60, 1, 10, 7, 4, 1),
(12, 61, 1, 10, 7, 4, 1),
(13, 60, 1, 9, 7, 4, NULL),
(14, 61, 1, 9, 7, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pengguna`
--

CREATE TABLE `jenis_pengguna` (
  `id_jenis_pengguna` int(11) NOT NULL,
  `jenis_pengguna` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jenis_pengguna`
--

INSERT INTO `jenis_pengguna` (`id_jenis_pengguna`, `jenis_pengguna`) VALUES
(1, 'Administrator'),
(2, 'Dekan'),
(3, 'Dosen'),
(4, 'Guru Pamong'),
(5, 'Mahasiswa');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_mahasiswa`
--

CREATE TABLE `kegiatan_mahasiswa` (
  `id_kegiatan_mahasiswa` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` longtext NOT NULL,
  `dokumentasi` varchar(32) DEFAULT NULL,
  `pengguna_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kegiatan_mahasiswa`
--

INSERT INTO `kegiatan_mahasiswa` (`id_kegiatan_mahasiswa`, `tanggal`, `kegiatan`, `dokumentasi`, `pengguna_id`) VALUES
(1, '2021-10-25', 'Perkenalan', '1636471496.png', 9),
(2, '2021-10-26', 'Masuk Kelas1', '1636545291.JPG', 9);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_mahasiswa`
--

CREATE TABLE `kelompok_mahasiswa` (
  `id_kelompok_mahasiswa` int(11) NOT NULL,
  `kelompok_sekolah_id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kelompok_mahasiswa`
--

INSERT INTO `kelompok_mahasiswa` (`id_kelompok_mahasiswa`, `kelompok_sekolah_id`, `pengguna_id`) VALUES
(111, 30, 9),
(105, 30, 10),
(110, 30, 24);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_sekolah`
--

CREATE TABLE `kelompok_sekolah` (
  `id_kelompok_sekolah` int(11) NOT NULL,
  `tahun_pelaksanaan_id` int(11) NOT NULL,
  `program_studi_id` int(11) NOT NULL,
  `sekolah_mitra_id` int(11) NOT NULL,
  `id_pengguna_dpl` int(11) NOT NULL,
  `id_pengguna_gpl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kelompok_sekolah`
--

INSERT INTO `kelompok_sekolah` (`id_kelompok_sekolah`, `tahun_pelaksanaan_id`, `program_studi_id`, `sekolah_mitra_id`, `id_pengguna_dpl`, `id_pengguna_gpl`) VALUES
(30, 1, 1, 1, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `angkatan` year(4) DEFAULT NULL,
  `kartu_rencana_studi` varchar(32) DEFAULT NULL,
  `kwitansi_pembayaran` varchar(32) DEFAULT NULL,
  `resume_pembekalan` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `pengguna_id`, `angkatan`, `kartu_rencana_studi`, `kwitansi_pembayaran`, `resume_pembekalan`) VALUES
(2, 9, 2017, NULL, NULL, 'harus mengikuti peraturan yang ada disekolah.'),
(3, 10, 2017, NULL, NULL, NULL),
(5, 24, 2017, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `menu` varchar(32) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `url` varchar(32) DEFAULT NULL,
  `sort_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `menu`, `icon`, `url`, `sort_by`) VALUES
(1, 'Beranda', 'fas fa-home', 'home', 1627294709),
(3, 'Pengaturan', 'fas fa-cogs', 'setting', 1627297027),
(9, 'Pengguna', 'fas fa-users', 'user', 1627294935),
(11, 'Master', 'fas fa-folder-open', 'master', 1627294787),
(12, 'Penilaian', 'fas fa-edit', 'evaluation', 1627296901),
(16, 'Keluar', 'fas fa-sign-out-alt', 'logout', 1636289674),
(17, 'Mahasiswa', 'fas fa-users', 'teacher', 1633617830),
(18, 'Kegiatan', 'fas fa-tasks', 'student', 1636289654);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int(11) NOT NULL,
  `nama` varchar(32) NOT NULL,
  `pengaturan` tinytext DEFAULT NULL,
  `gambar` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `nama`, `pengaturan`, `gambar`) VALUES
(1, 'NAMA_WEBSITE', 'SI PLP II', NULL),
(2, 'DESKRIPSI_WEBSITE', 'Sistem Informasi PLP II FKIP', NULL),
(3, 'NAMA_COPYRIGHT', 'Alamsyah Firdaus', NULL),
(4, 'LOGO_WEBSITE', NULL, '1636710608.png'),
(5, 'GAMBAR_LOGIN', NULL, NULL),
(6, 'SMTP_HOST', 'ssl://smtp.googlemail.com', NULL),
(7, 'SMTP_USER', 'firdaus.alamsyah317@gmail.com', NULL),
(8, 'SMTP_PASS', 'tasikmalaya', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `no_induk` varchar(32) DEFAULT NULL,
  `nama_lengkap` varchar(64) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `foto_profil` varchar(32) DEFAULT NULL,
  `telepon` varchar(16) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `jenis_pengguna_id` int(11) NOT NULL,
  `program_studi_id` int(11) DEFAULT NULL,
  `tanggal_pendaftaran` datetime DEFAULT NULL,
  `status_pendaftaran` int(11) DEFAULT NULL,
  `login_terakhir` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `no_induk`, `nama_lengkap`, `jenis_kelamin`, `foto_profil`, `telepon`, `email`, `password`, `jenis_pengguna_id`, `program_studi_id`, `tanggal_pendaftaran`, `status_pendaftaran`, `login_terakhir`) VALUES
(1, NULL, 'Alamsyah Firdaus', 'L', NULL, '089693839624', 'alamsyah.firdaus.af31@gmail.com', '85fcceafa13cbd523759a853ef5a89aeeeb78524', 1, NULL, NULL, 1, '2022-05-17 20:08:18'),
(3, '2833756656200002', 'Asep Solihin, S.T.', 'L', NULL, NULL, NULL, 'c64ffc8be322e58952ffeaaa11ad9a9e576e766c', 4, NULL, '2021-07-26 18:39:04', 1, '2022-05-17 20:07:53'),
(7, '0409088202', 'Alfadl Habibie, M.Ag.', 'L', NULL, NULL, NULL, 'da98087ee6d535a2e9965f03da2d2483d34b2ec4', 3, 1, '2021-07-26 18:40:49', 1, '2022-05-17 19:26:25'),
(9, 'C1783207002', 'Alamsyah Firdaus', 'L', '1638085862.jpg', NULL, NULL, '51e6a62ca557831c9b4c3b07d61d5885e324751f', 5, 1, '2021-07-26 18:40:49', 1, '2022-05-17 20:03:11'),
(10, 'C1783207003', 'Rendi Rahman', 'L', NULL, NULL, NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 5, 1, '2021-07-26 18:40:49', 1, NULL),
(24, 'C1783207012', 'Ruhiat Hidayatuloh', 'L', NULL, NULL, NULL, '0cc251b41b5a888ad99cf3df5aa3e492a8758630', 5, 1, NULL, 1, NULL),
(27, NULL, 'Admin1', NULL, '1633621530.png', NULL, 'admin@plp2gmail.com', '371624eed4bc10ae4d82f903400b323aa0ccb42f', 1, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `program_studi_id` int(11) NOT NULL,
  `jenis_pengguna_id` int(11) DEFAULT NULL,
  `penilaian` mediumtext NOT NULL,
  `petunjuk` longtext DEFAULT NULL,
  `skala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `program_studi_id`, `jenis_pengguna_id`, `penilaian`, `petunjuk`, `skala`) VALUES
(10, 1, 1, 'PENAMPILAN PERSONAL DAN SOSIAL', NULL, 4),
(12, 1, 1, 'PRAKTIK MENGAJAR TERBIMBING', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id_program_studi` int(11) NOT NULL,
  `program_studi` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id_program_studi`, `program_studi`) VALUES
(1, 'Pendidikan Teknologi Informasi'),
(8, 'Pendidikan Guru Sekolah Dasar'),
(9, 'Bimbingan Konseling');

-- --------------------------------------------------------

--
-- Table structure for table `sekolah_mitra`
--

CREATE TABLE `sekolah_mitra` (
  `id_sekolah_mitra` int(11) NOT NULL,
  `nama_sekolah` varchar(64) NOT NULL,
  `alamat_sekolah` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sekolah_mitra`
--

INSERT INTO `sekolah_mitra` (`id_sekolah_mitra`, `nama_sekolah`, `alamat_sekolah`) VALUES
(1, 'SMA Al Muttaqin Tasikmalaya', 'Jl. Ahmad Yani No.140, Sukamanah, Kec. Cipedes, Tasikmalaya, Jawa Barat 46175'),
(3, 'SMK Muhammadiyah Tasikmalaya', 'Jl. Rumah Sakit No.29, Empangsari, Kec. Tawang, Tasikmalaya, Jawa Barat 46113');

-- --------------------------------------------------------

--
-- Table structure for table `sub_menu`
--

CREATE TABLE `sub_menu` (
  `id_sub_menu` int(11) NOT NULL,
  `sub_menu` varchar(32) NOT NULL,
  `url` varchar(32) NOT NULL,
  `sort_by` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `aktivasi` int(11) NOT NULL COMMENT '1 = Aktif \r\n0 = Nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_menu`
--

INSERT INTO `sub_menu` (`id_sub_menu`, `sub_menu`, `url`, `sort_by`, `menu_id`, `aktivasi`) VALUES
(1, 'Menu', 'menu', 1618742807, 3, 1),
(2, 'Website', 'other', 1618742809, 3, 1),
(10, 'Administrator', 'administration', 1624543436, 9, 1),
(11, 'Dosen', 'lecturer', 1624543439, 9, 1),
(12, 'Mahasiswa', 'student', 1627294310, 9, 1),
(14, 'Guru Pamong', 'teacher', 1627294306, 9, 1),
(15, 'Sekolah Mitra', 'school', 1627713913, 11, 1),
(16, 'Program Studi', 'department', 1627294929, 11, 1),
(18, 'Instrumen Penilaian', 'instrument', 1627296949, 12, 1),
(20, 'Kelompok Mahasiswa', 'group', 1628513281, 11, 1),
(21, 'Tahun Pelaksanaan', 'year', 1628513273, 11, 1),
(22, 'Hasil Penilaian', 'score', 1629737130, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_pelaksanaan`
--

CREATE TABLE `tahun_pelaksanaan` (
  `id_tahun_pelaksanaan` int(11) NOT NULL,
  `tahun_pelaksanaan` year(4) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `status_pelaksanaan` int(11) DEFAULT NULL,
  `pendaftaran_mahasiswa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tahun_pelaksanaan`
--

INSERT INTO `tahun_pelaksanaan` (`id_tahun_pelaksanaan`, `tahun_pelaksanaan`, `tanggal_mulai`, `status_pelaksanaan`, `pendaftaran_mahasiswa`) VALUES
(1, 2021, '2021-10-25', NULL, NULL),
(14, 2022, '2022-09-01', 1, NULL),
(15, 2023, '2023-09-01', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akses_menu`
--
ALTER TABLE `akses_menu`
  ADD PRIMARY KEY (`id_akses_menu`),
  ADD KEY `menu_id` (`menu_id`,`jenis_pengguna_id`),
  ADD KEY `jenis_pengguna_id` (`jenis_pengguna_id`);

--
-- Indexes for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  ADD PRIMARY KEY (`id_detail_penilaian`),
  ADD KEY `penilaian_id` (`penilaian_id`);

--
-- Indexes for table `guru_pamong`
--
ALTER TABLE `guru_pamong`
  ADD PRIMARY KEY (`id_guru_pamong`),
  ADD KEY `pengguna_id` (`pengguna_id`,`sekolah_mitra_id`),
  ADD KEY `sekolah_mitra_id` (`sekolah_mitra_id`);

--
-- Indexes for table `hasil_penilaian`
--
ALTER TABLE `hasil_penilaian`
  ADD PRIMARY KEY (`id_hasil_penilaian`),
  ADD KEY `detail_penilaian_id` (`detail_penilaian_id`,`tahun_pelaksanaan_id`,`id_pengguna_mhs`,`id_pengguna_penilai`),
  ADD KEY `tahun_pelaksanaan_id` (`tahun_pelaksanaan_id`),
  ADD KEY `id_pengguna_mhs` (`id_pengguna_mhs`),
  ADD KEY `id_pengguna_penilai` (`id_pengguna_penilai`);

--
-- Indexes for table `jenis_pengguna`
--
ALTER TABLE `jenis_pengguna`
  ADD PRIMARY KEY (`id_jenis_pengguna`);

--
-- Indexes for table `kegiatan_mahasiswa`
--
ALTER TABLE `kegiatan_mahasiswa`
  ADD PRIMARY KEY (`id_kegiatan_mahasiswa`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indexes for table `kelompok_mahasiswa`
--
ALTER TABLE `kelompok_mahasiswa`
  ADD PRIMARY KEY (`id_kelompok_mahasiswa`),
  ADD KEY `kelompok_sekolah_id` (`kelompok_sekolah_id`,`pengguna_id`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indexes for table `kelompok_sekolah`
--
ALTER TABLE `kelompok_sekolah`
  ADD PRIMARY KEY (`id_kelompok_sekolah`),
  ADD KEY `tahun_pelaksanaan_id` (`tahun_pelaksanaan_id`,`program_studi_id`,`sekolah_mitra_id`,`id_pengguna_dpl`,`id_pengguna_gpl`),
  ADD KEY `program_studi_id` (`program_studi_id`),
  ADD KEY `sekolah_mitra_id` (`sekolah_mitra_id`),
  ADD KEY `id_pengguna_dpl` (`id_pengguna_dpl`),
  ADD KEY `id_pengguna_gpl` (`id_pengguna_gpl`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `no_induk` (`no_induk`,`telepon`,`email`),
  ADD KEY `jenis_pengguna_id` (`jenis_pengguna_id`,`program_studi_id`),
  ADD KEY `program_studi_id` (`program_studi_id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `program_studi_id` (`program_studi_id`,`jenis_pengguna_id`),
  ADD KEY `jenis_pengguna_id` (`jenis_pengguna_id`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_program_studi`);

--
-- Indexes for table `sekolah_mitra`
--
ALTER TABLE `sekolah_mitra`
  ADD PRIMARY KEY (`id_sekolah_mitra`);

--
-- Indexes for table `sub_menu`
--
ALTER TABLE `sub_menu`
  ADD PRIMARY KEY (`id_sub_menu`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `tahun_pelaksanaan`
--
ALTER TABLE `tahun_pelaksanaan`
  ADD PRIMARY KEY (`id_tahun_pelaksanaan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akses_menu`
--
ALTER TABLE `akses_menu`
  MODIFY `id_akses_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  MODIFY `id_detail_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `guru_pamong`
--
ALTER TABLE `guru_pamong`
  MODIFY `id_guru_pamong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hasil_penilaian`
--
ALTER TABLE `hasil_penilaian`
  MODIFY `id_hasil_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jenis_pengguna`
--
ALTER TABLE `jenis_pengguna`
  MODIFY `id_jenis_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kegiatan_mahasiswa`
--
ALTER TABLE `kegiatan_mahasiswa`
  MODIFY `id_kegiatan_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kelompok_mahasiswa`
--
ALTER TABLE `kelompok_mahasiswa`
  MODIFY `id_kelompok_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `kelompok_sekolah`
--
ALTER TABLE `kelompok_sekolah`
  MODIFY `id_kelompok_sekolah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id_program_studi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sekolah_mitra`
--
ALTER TABLE `sekolah_mitra`
  MODIFY `id_sekolah_mitra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_menu`
--
ALTER TABLE `sub_menu`
  MODIFY `id_sub_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tahun_pelaksanaan`
--
ALTER TABLE `tahun_pelaksanaan`
  MODIFY `id_tahun_pelaksanaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akses_menu`
--
ALTER TABLE `akses_menu`
  ADD CONSTRAINT `akses_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `akses_menu_ibfk_2` FOREIGN KEY (`jenis_pengguna_id`) REFERENCES `jenis_pengguna` (`id_jenis_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  ADD CONSTRAINT `detail_penilaian_ibfk_1` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id_penilaian`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guru_pamong`
--
ALTER TABLE `guru_pamong`
  ADD CONSTRAINT `guru_pamong_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guru_pamong_ibfk_2` FOREIGN KEY (`sekolah_mitra_id`) REFERENCES `sekolah_mitra` (`id_sekolah_mitra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasil_penilaian`
--
ALTER TABLE `hasil_penilaian`
  ADD CONSTRAINT `hasil_penilaian_ibfk_1` FOREIGN KEY (`detail_penilaian_id`) REFERENCES `detail_penilaian` (`id_detail_penilaian`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_penilaian_ibfk_2` FOREIGN KEY (`tahun_pelaksanaan_id`) REFERENCES `tahun_pelaksanaan` (`id_tahun_pelaksanaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_penilaian_ibfk_3` FOREIGN KEY (`id_pengguna_mhs`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_penilaian_ibfk_4` FOREIGN KEY (`id_pengguna_penilai`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatan_mahasiswa`
--
ALTER TABLE `kegiatan_mahasiswa`
  ADD CONSTRAINT `kegiatan_mahasiswa_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelompok_mahasiswa`
--
ALTER TABLE `kelompok_mahasiswa`
  ADD CONSTRAINT `kelompok_mahasiswa_ibfk_1` FOREIGN KEY (`kelompok_sekolah_id`) REFERENCES `kelompok_sekolah` (`id_kelompok_sekolah`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelompok_mahasiswa_ibfk_2` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelompok_sekolah`
--
ALTER TABLE `kelompok_sekolah`
  ADD CONSTRAINT `kelompok_sekolah_ibfk_1` FOREIGN KEY (`tahun_pelaksanaan_id`) REFERENCES `tahun_pelaksanaan` (`id_tahun_pelaksanaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelompok_sekolah_ibfk_2` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id_program_studi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelompok_sekolah_ibfk_3` FOREIGN KEY (`sekolah_mitra_id`) REFERENCES `sekolah_mitra` (`id_sekolah_mitra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelompok_sekolah_ibfk_4` FOREIGN KEY (`id_pengguna_dpl`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelompok_sekolah_ibfk_5` FOREIGN KEY (`id_pengguna_gpl`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id_program_studi`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pengguna_ibfk_2` FOREIGN KEY (`jenis_pengguna_id`) REFERENCES `jenis_pengguna` (`id_jenis_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id_program_studi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`jenis_pengguna_id`) REFERENCES `jenis_pengguna` (`id_jenis_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sub_menu`
--
ALTER TABLE `sub_menu`
  ADD CONSTRAINT `sub_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
