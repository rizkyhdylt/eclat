-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 04:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eclat`
--

-- --------------------------------------------------------

--
-- Table structure for table `kontrakan`
--

CREATE TABLE `kontrakan` (
  `id_kontrakan` int(11) NOT NULL,
  `tipe_kamar` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontrakan`
--

INSERT INTO `kontrakan` (`id_kontrakan`, `tipe_kamar`, `harga`) VALUES
(1, 'Kamar A001', 1100000),
(2, 'Kamar A002', 1000000),
(3, 'Kamar A003', 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_penyewa` int(11) DEFAULT NULL,
  `id_kontrakan` int(11) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_penyewa`, `id_kontrakan`, `bulan`, `tahun`, `jatuh_tempo`, `tanggal_bayar`, `status`) VALUES
(1, 1, 1, 'Januari', 2026, '2026-01-08', '2026-01-14', 'Terlambat'),
(2, 3, 1, 'Januari', 2026, '2026-01-08', '2026-01-10', 'Terlambat'),
(3, 2, 1, 'Januari', 2026, '2026-01-08', '2026-01-05', 'Lunas'),
(4, 5, 2, 'Januari', 2026, '2026-01-08', '2026-01-12', 'Terlambat'),
(5, 4, 2, 'Januari', 2026, '2026-01-08', '2026-01-08', 'Lunas'),
(6, 2, 1, 'Januari', 2026, '2026-01-08', '2026-01-13', 'Terlambat'),
(7, 2, 1, 'Januari', 2026, '2026-01-08', '2026-01-09', 'Terlambat'),
(8, 2, 1, 'Januari', 2026, '2026-01-08', '2026-01-11', 'Terlambat');

-- --------------------------------------------------------

--
-- Table structure for table `penyewa`
--

CREATE TABLE `penyewa` (
  `id_penyewa` int(11) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyewa`
--

INSERT INTO `penyewa` (`id_penyewa`, `nama_penyewa`, `no_hp`, `alamat`) VALUES
(1, 'RIzky Hidayatullah', '0823165464561', 'ciputat timur'),
(2, 'Nada', '08465135135', 'Jakarta Selatan'),
(3, 'Irvan', '0845132131', 'Depok'),
(4, 'Haris', '08515132132', 'Pondok Petir'),
(5, 'Faatir', '084651313', 'Bekasi');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '$2y$10$Oi.aItsp2jehaGAAkUpbA.EuCZfrvpSwyUxNunkDciWxHYFg7oRMq', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kontrakan`
--
ALTER TABLE `kontrakan`
  ADD PRIMARY KEY (`id_kontrakan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `penyewa`
--
ALTER TABLE `penyewa`
  ADD PRIMARY KEY (`id_penyewa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kontrakan`
--
ALTER TABLE `kontrakan`
  MODIFY `id_kontrakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penyewa`
--
ALTER TABLE `penyewa`
  MODIFY `id_penyewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
