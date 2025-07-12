-- Membuat Database
CREATE DATABASE IF NOT EXISTS `aspirasi_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `aspirasi_db`;

-- Struktur Tabel `users`
-- Menyimpan data pengguna (mahasiswa dan dosen)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','dosen') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data contoh
-- Password untuk 'mahasiswa1' adalah 'pass123'
-- Password untuk 'dosen1' adalah 'dosen123'
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'mahasiswa1', '$2y$10$fW.Z2sE.Y9j9x8X.Y9j9xO.Y9j9xO.Y9j9xO.Y9j9xO.Y9j9xO.Y', 'mahasiswa'),
(2, 'dosen1', '$2y$10$gZ.Z2sE.Y9j9x8X.Y9j9xO.Y9j9xO.Y9j9xO.Y9j9xO.Y9j9xO.Y', 'dosen');


-- Struktur Tabel `aspirasi`
-- Menyimpan data aspirasi dari mahasiswa
CREATE TABLE `aspirasi` (
  `id_aspirasi` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `balasan` text DEFAULT NULL,
  `tanggal_balasan` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_aspirasi`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

