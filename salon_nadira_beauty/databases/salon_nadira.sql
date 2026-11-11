-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2026 at 02:13 AM
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
-- Database: `salon_nadira`
--

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(10) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `deskripsi`, `harga`) VALUES
(1, 'creambath', 'perawatan rambut dan kulit kepala', 50),
(2, 'cuci/catok rambut', 'rambut bersih,rapi,dan tampak lebih indah dengan hasil catokan yang menawan', 35),
(3, 'potong rambut', 'potong dan merapikan rambut', 20),
(4, 'makeup wisuda', 'tampil cantik dan percaya diri dihari wisuda dengan riasan yang menawan.\r\n', 150),
(5, 'tuning rambut', 'percantik tampilan rambur dengan sentuhan warna yang menarik dn menawan', 350),
(6, 'bentuk alis', 'merapikan bentuk alis sesuai keinginan pelanggan', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(10) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_whatsapp` varchar(15) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `tanggal_reservasi` date NOT NULL,
  `pesan` text NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `nama_pelanggan`, `no_whatsapp`, `id_layanan`, `tanggal_reservasi`, `pesan`, `status`) VALUES
(8, 'dunia novel', '082393561936', 2, '2026-10-13', 'potong rambut jdi super hiro', 'Menunggu'),
(11, 'idaaa', '082393561936', 4, '2026-10-27', '...', 'Selesai'),
(12, 'ida', '6285168716705', 5, '2026-10-28', '...', 'Menunggu'),
(13, 'milaa', '6285168725084', 4, '2026-10-28', 'untuk make up wisuda kaka saya', 'Dikonfirmasi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
