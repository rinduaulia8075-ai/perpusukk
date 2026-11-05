-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Nov 2026 pada 03.03
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`admin_id`, `nama`, `username`, `password`) VALUES
(1, 'Rindu Aulia', 'rindu', 'rindu123'),
(2, 'Admin Sistem / Tambahan', 'admin2', 'password123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `anggota_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `telpon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`anggota_id`, `nama`, `jenis_kelamin`, `tempat_lahir`, `telpon`, `alamat`, `admin_id`, `username`, `password`) VALUES
(8, 'mutiara', 'Perempuan', NULL, '0987654321', 'padalarang', 1, 'muti11', 'muti11'),
(11, 'dewi', 'Perempuan', 'swiss', '0987654321', 'cimindi', 1, 'dewi11', 'dewi11'),
(12, 'anggota 1', 'Laki-laki', 'padalarang', '0987654321', 'a', 1, 'anggota1', 'qnggota1'),
(13, 'karin', 'Perempuan', NULL, '0987654321', 'Jl. Mawar No. 13', 1, 'karin11', '$2y$10$lWB5Mohgo6v7N8FmLHDFPu3hMYPfZdXJ5WB5DPUGUHMeNa.fQQzWe');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `buku_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `penerbit_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `ISBN` varchar(30) DEFAULT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `jumlah_halaman` int(11) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `tahun_terbit` year(4) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`buku_id`, `judul`, `kategori_id`, `penerbit_id`, `admin_id`, `ISBN`, `penulis`, `jumlah_halaman`, `stok`, `tahun_terbit`, `sinopsis`, `gambar`) VALUES
(1, 'Laskar Pelangi', 1, 1, 1, '9789793062792', 'Andrea Hirata', 529, 5, '2005', 'Novel yang menceritakan perjuangan dan persahabatan anak-anak dalam meraih pendidikan.', NULL),
(2, 'Pemrograman Web Dasar', 2, 2, 2, '9786020481234', 'Budi Raharjo', 320, 10, '2023', 'Buku pembelajaran dasar pemrograman web menggunakan teknologi HTML, CSS, PHP, dan database.', NULL),
(22, 'kancil', 3, 1, 1, '12223123', 'rindu', 133, 100, '2009', 'horror', 'Learn How to Draw a Book and a Stack of Books Step by Step.jfif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `histori`
--

CREATE TABLE `histori` (
  `histori_id` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `tanggal_histori` datetime NOT NULL DEFAULT current_timestamp(),
  `jenis_aktivitas` enum('Peminjaman','Pengembalian','Kerusakan','Terlambat') NOT NULL,
  `denda` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `histori`
--

INSERT INTO `histori` (`histori_id`, `peminjaman_id`, `tanggal_histori`, `jenis_aktivitas`, `denda`) VALUES
(5, 1, '2026-09-24 06:53:08', 'Pengembalian', 9000.00),
(6, 9, '2026-09-25 13:11:39', 'Kerusakan', 5000.00),
(7, 16, '2026-10-19 08:01:14', 'Peminjaman', 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`, `admin_id`) VALUES
(1, 'Fiksi', 1),
(2, 'Teknologi', 2),
(3, 'Horror', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `peminjaman_id` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `lama_pinjam` int(11) NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('menunggu','Dipinjam','Sudah Dikembalikan','DITOLAK') NOT NULL DEFAULT 'menunggu',
  `anggota_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`peminjaman_id`, `tanggal_pinjam`, `lama_pinjam`, `tanggal_jatuh_tempo`, `keterangan`, `status`, `anggota_id`, `admin_id`, `buku_id`) VALUES
(1, '2026-09-08', 7, '2026-09-15', 'Untuk tugas sekolah', 'Sudah Dikembalikan', 1, 1, 1),
(9, '2026-09-25', 111, '2027-01-14', '111', 'Sudah Dikembalikan', 9, 1, 22),
(13, '2026-09-30', 1, '2026-10-01', 'belajar', 'DITOLAK', 8, 1, 1),
(15, '2026-09-23', 9, '2026-11-18', 'l,l,', 'Sudah Dikembalikan', 11, 1, 2),
(16, '2026-10-18', 7, '2026-10-25', '', 'Sudah Dikembalikan', 13, 1, 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbit`
--

CREATE TABLE `penerbit` (
  `penerbit_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penerbit`
--

INSERT INTO `penerbit` (`penerbit_id`, `nama`, `admin_id`) VALUES
(1, 'Gramedia Pustaka Utama', 1),
(2, 'Elex Media Komputindo', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `pengembalian_id` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`pengembalian_id`, `peminjaman_id`, `tanggal_kembali`, `petugas_id`, `admin_id`) VALUES
(1, 3, '2026-09-13', 1, 2),
(5, 9, '2026-09-25', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `petugas_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`petugas_id`, `admin_id`, `nama`, `username`, `password`) VALUES
(1, 1, 'Dina Permata', 'dina', 'dina123'),
(2, 2, 'Fajar Nugraha', 'fajar', 'fajar123'),
(9, 1, 'arin', 'arin123', 'arin123');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`anggota_id`),
  ADD KEY `fk_anggota_admin` (`admin_id`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`buku_id`),
  ADD KEY `fk_buku_kategori` (`kategori_id`),
  ADD KEY `fk_buku_penerbit` (`penerbit_id`),
  ADD KEY `fk_buku_admin` (`admin_id`);

--
-- Indeks untuk tabel `histori`
--
ALTER TABLE `histori`
  ADD PRIMARY KEY (`histori_id`),
  ADD KEY `fk_histori_peminjaman` (`peminjaman_id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD KEY `fk_kategori_admin` (`admin_id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`peminjaman_id`),
  ADD KEY `fk_peminjaman_anggota` (`anggota_id`),
  ADD KEY `fk_peminjaman_admin` (`admin_id`),
  ADD KEY `fk_peminjaman_buku` (`buku_id`);

--
-- Indeks untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`penerbit_id`),
  ADD KEY `fk_penerbit_admin` (`admin_id`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`pengembalian_id`),
  ADD KEY `fk_pengembalian_peminjaman` (`peminjaman_id`),
  ADD KEY `fk_pengembalian_petugas` (`petugas_id`),
  ADD KEY `fk_pengembalian_admin` (`admin_id`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`petugas_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_petugas_admin` (`admin_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `anggota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `buku_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `histori`
--
ALTER TABLE `histori`
  MODIFY `histori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `peminjaman_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `penerbit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `pengembalian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `petugas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `fk_anggota_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_buku_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buku_penerbit` FOREIGN KEY (`penerbit_id`) REFERENCES `penerbit` (`penerbit_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `histori`
--
ALTER TABLE `histori`
  ADD CONSTRAINT `fk_histori_peminjaman` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`peminjaman_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
