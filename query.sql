-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 16, 2020 at 12:00 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`) VALUES
(1, 'Baju'),
(2, 'Celana');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_harga`
--

CREATE TABLE `daftar_harga` (
  `id_harga` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_kategori` char(1) DEFAULT NULL,
  `id_servis` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daftar_harga`
--

INSERT INTO `daftar_harga` (`id_harga`, `id_barang`, `id_kategori`, `id_servis`, `harga`) VALUES
(1, 1, 'k', 1, 10000),
(2, 1, 's', 1, 2000),
(3, 2, 'k', 1, 8000),
(4, 2, 's', 1, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_harga` int(11) NOT NULL,
  `banyak` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `sub_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_transaksi`, `id_harga`, `banyak`, `harga`, `sub_total`) VALUES
(1, 1, 1, 10000, 10000),
(1, 2, 1, 2000, 2000),
(1, 3, 1, 8000, 8000),
(1, 4, 1, 1500, 1500),
(2, 1, 1, 10000, 10000),
(2, 3, 1, 8000, 8000);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` char(1) NOT NULL,
  `nama_kategori` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
('k', 'Kiloan'),
('s', 'Satuan');

-- --------------------------------------------------------

--
-- Table structure for table `saran_komplain`
--

CREATE TABLE `saran_komplain` (
  `id` int(11) NOT NULL,
  `tgl` datetime DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `tipe` char(1) DEFAULT NULL,
  `id_member` int(11) DEFAULT NULL,
  `balasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `servis`
--

CREATE TABLE `servis` (
  `id_servis` int(11) NOT NULL,
  `nama_servis` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `servis`
--

INSERT INTO `servis` (`id_servis`, `nama_servis`) VALUES
(1, 'Cuci'),
(2, 'Setrika');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id_status`, `nama_status`) VALUES
(1, 'Belum Dikerjakan'),
(2, 'Sedang Dikerjakan'),
(3, 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tgl_masuk` datetime DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `id_member` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `potongan` int(11) DEFAULT 0,
  `total_harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tgl_masuk`, `id_status`, `id_member`, `id_admin`, `tgl_selesai`, `potongan`, `total_harga`) VALUES
(1, '2020-08-16 17:53:20', 1, 2, 1, NULL, 0, 21500),
(2, '2020-08-16 17:58:13', 1, 2, 1, NULL, 10000, 8000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` char(1) DEFAULT NULL,
  `nama` varchar(128) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `alamat` varchar(128) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `profil` varchar(64) DEFAULT NULL,
  `poin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `nama`, `jenis_kelamin`, `alamat`, `no_telp`, `profil`, `poin`) VALUES
(1, 'admin@laundryxyz.com', '$2y$10$IxYfwWgcK8av8ZA1oFazT.Z9fPFe/k4J4Mkw.zUSyxhnH6hkjm.iW', '1', 'Admin Laundry', 'L', 'Muding', '081999999999', 'default.jpg', 75),
(2, 'andikatedja@yahoo.com', '$2y$10$0gWBafiCrZi840sD.FT/q.UN4WPEJyFVtgO/y6HJzK8EfVxQ4Fx0u', '2', 'Andika Tedja', 'L', 'Muding', '081999999999', '2.png', 76);

-- --------------------------------------------------------

--
-- Table structure for table `users_vouchers`
--

CREATE TABLE `users_vouchers` (
  `id` int(11) NOT NULL,
  `id_voucher` int(11) DEFAULT NULL,
  `id_member` int(11) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_vouchers`
--

INSERT INTO `users_vouchers` (`id`, `id_voucher`, `id_member`, `used`) VALUES
(1, 2, 2, 1),
(2, 1, 2, NULL),
(3, 2, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id_voucher` int(11) NOT NULL,
  `nama_voucher` varchar(128) DEFAULT NULL,
  `potongan` int(11) DEFAULT NULL,
  `poin_need` int(11) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT NULL,
  `keterangan` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id_voucher`, `nama_voucher`, `potongan`, `poin_need`, `aktif`, `keterangan`) VALUES
(1, 'Potongan 5.000', 5000, 5, 1, 'Mendapatkan potongan harga 5.000 dari total transaksi'),
(2, 'Potongan 10.000', 10000, 10, 1, 'Mendapatkan potongan harga 10.000 dari total transaksi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `daftar_harga`
--
ALTER TABLE `daftar_harga`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_servis` (`id_servis`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_transaksi`,`id_harga`),
  ADD KEY `id_harga` (`id_harga`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `saran_komplain`
--
ALTER TABLE `saran_komplain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `servis`
--
ALTER TABLE `servis`
  ADD PRIMARY KEY (`id_servis`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_vouchers`
--
ALTER TABLE `users_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_voucher` (`id_voucher`),
  ADD KEY `id_member` (`id_member`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id_voucher`),
  ADD UNIQUE KEY `potongan` (`potongan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `daftar_harga`
--
ALTER TABLE `daftar_harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `saran_komplain`
--
ALTER TABLE `saran_komplain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servis`
--
ALTER TABLE `servis`
  MODIFY `id_servis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_vouchers`
--
ALTER TABLE `users_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id_voucher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_harga`
--
ALTER TABLE `daftar_harga`
  ADD CONSTRAINT `daftar_harga_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `daftar_harga_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `daftar_harga_ibfk_3` FOREIGN KEY (`id_servis`) REFERENCES `servis` (`id_servis`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_harga`) REFERENCES `daftar_harga` (`id_harga`);

--
-- Constraints for table `saran_komplain`
--
ALTER TABLE `saran_komplain`
  ADD CONSTRAINT `saran_komplain_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_vouchers`
--
ALTER TABLE `users_vouchers`
  ADD CONSTRAINT `users_vouchers_ibfk_1` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`),
  ADD CONSTRAINT `users_vouchers_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
