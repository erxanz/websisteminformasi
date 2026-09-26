-- ============================================================================
--  Database : universitas (Sistem Informasi Akademik)
--  Engine   : InnoDB  (mendukung FOREIGN KEY + transaksi)
--  Charset  : utf8mb4 / utf8mb4_unicode_ci  (Unicode penuh)
--
--  PENTING:
--  Password pada data contoh sudah di-hash dengan password_hash() (bcrypt),
--  BUKAN lagi SHA256. Password default semua mahasiswa adalah:
--
--      password123
--
--  Segera ganti setelah login pertama kali.
-- ============================================================================

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';
SET NAMES utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `login_attempts`;
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `program_studi`;
DROP TABLE IF EXISTS `fakultas`;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
-- Tabel: fakultas
-- ----------------------------------------------------------------------------
CREATE TABLE `fakultas` (
  `id_fakultas`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_fakultas` VARCHAR(100) NOT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_fakultas`),
  UNIQUE KEY `uq_fakultas_nama` (`nama_fakultas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fakultas` (`id_fakultas`, `nama_fakultas`) VALUES
(1, 'Teknik'),
(2, 'Ekonomi dan Bisnis'),
(3, 'Keguruan dan Ilmu Pendidikan');

-- ----------------------------------------------------------------------------
-- Tabel: program_studi
--   FK -> fakultas : hapus/ubah fakultas ikut memperbarui program studi,
--   tetapi fakultas yang masih dipakai tidak boleh dihapus (RESTRICT).
-- ----------------------------------------------------------------------------
CREATE TABLE `program_studi` (
  `id_program_studi`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_program_studi` VARCHAR(100) NOT NULL,
  `id_fakultas`        INT UNSIGNED NOT NULL,
  `created_at`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_program_studi`),
  UNIQUE KEY `uq_prodi_nama` (`nama_program_studi`),
  KEY `idx_prodi_fakultas` (`id_fakultas`),
  CONSTRAINT `fk_prodi_fakultas`
    FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id_fakultas`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `program_studi` (`id_program_studi`, `nama_program_studi`, `id_fakultas`) VALUES
(1, 'Teknik Informatika', 1),
(2, 'Teknik Sipil', 1),
(3, 'Manajemen', 2);

-- ----------------------------------------------------------------------------
-- Tabel: mahasiswa
--   PK  : npm
--   FK  : id_program_studi -> program_studi
--   password menyimpan hasil password_hash() (bcrypt, 60+ karakter)
--   email/phone/photo adalah kolom profil yang bisa diubah mahasiswa sendiri.
--   Alamat memakai kolom `alamat` yang sudah ada (tidak ada kolom `address` ganda).
-- ----------------------------------------------------------------------------
CREATE TABLE `mahasiswa` (
  `npm`              VARCHAR(20) NOT NULL,
  `nama_mahasiswa`   VARCHAR(100) NOT NULL,
  `email`            VARCHAR(150) DEFAULT NULL,
  `jenis_kelamin`    ENUM('L','P') NOT NULL,
  `tempat_lahir`     VARCHAR(50) DEFAULT NULL,
  `tanggal_lahir`    DATE DEFAULT NULL,
  `tanggal_masuk`    DATE DEFAULT NULL,
  `alamat`           TEXT DEFAULT NULL,
  `phone`            VARCHAR(20) DEFAULT NULL,
  `photo`            VARCHAR(255) DEFAULT NULL,
  `password`         VARCHAR(255) NOT NULL,
  `id_program_studi` INT UNSIGNED NOT NULL,
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`npm`),
  UNIQUE KEY `uq_mahasiswa_email` (`email`),
  KEY `idx_mahasiswa_prodi` (`id_program_studi`),
  KEY `idx_mahasiswa_nama` (`nama_mahasiswa`),
  CONSTRAINT `fk_mahasiswa_prodi`
    FOREIGN KEY (`id_program_studi`) REFERENCES `program_studi` (`id_program_studi`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `mahasiswa`
  (`npm`, `nama_mahasiswa`, `email`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `tanggal_masuk`, `alamat`, `phone`, `photo`, `password`, `id_program_studi`)
VALUES
('2101010001', 'Ahmad Rizky', 'ahmad.rizky@universitascontoh.ac.id', 'L', 'Bengkulu', '2002-04-15', '2021-09-01', 'Jl. Suprapto No. 12, Bengkulu', '081200000001', NULL, '$2y$10$wP0VNHT7.MjITHXmAMlEKu4QEf6p3wKUY5oEKssNm8Hx6q0ReWlsi', 1),
('2101010002', 'Anisa Putri', 'anisa.putri@universitascontoh.ac.id', 'P', 'Curup', '2003-08-22', '2021-09-01', 'Jl. Merdeka No. 45, Bengkulu', '081200000002', NULL, '$2y$10$zK6jE/5g9V2oKtQ7bPAbZu5wY2h14uyPqTQEvXXUCFcRilf4xj8A6', 1),
('2101010003', 'Budi Santoso', 'budi.santoso@universitascontoh.ac.id', 'L', 'Argamakmur', '2002-11-10', '2021-09-01', 'Jl. Salak No. 08, Bengkulu', '081200000003', NULL, '$2y$10$u8rpnzZUW5wx/66qE..45egWhKWNoJY9A/QWuvkzrUwQ5tkSqZpA6', 1),
('2101010004', 'Dina Lestari', 'dina.lestari@universitascontoh.ac.id', 'P', 'Manna', '2003-02-05', '2021-09-01', 'Jl. Danau No. 23, Bengkulu', '081200000004', NULL, '$2y$10$PMvB53ApE7gV7St64hm/Ge9/L25/1/mXjX2ZovMGlfn.hx0KZ1/b2', 1),
('2101010005', 'Fajar Ramadhan', 'fajar.ramadhan@universitascontoh.ac.id', 'L', 'Bengkulu', '2002-06-18', '2021-09-01', 'Jl. Mahakam No. 17, Bengkulu', NULL, NULL, '$2y$10$HkysL3QoiCPK5yug2Q2/H.4M/iQCasNA0Ay18EYDdSrzKpLzioGry', 1);

-- ----------------------------------------------------------------------------
-- Tabel: login_attempts
--   Menyimpan login yang GAGAL untuk membatasi serangan brute force.
--   Tanpa FOREIGN KEY ke mahasiswa karena NPM yang dicoba bisa saja tidak
--   terdaftar. Riwayat lebih lama dari 24 jam dibersihkan otomatis oleh aplikasi.
-- ----------------------------------------------------------------------------
CREATE TABLE `login_attempts` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `npm`          VARCHAR(20) NOT NULL,
  `ip_address`   VARCHAR(45) NOT NULL,
  `attempted_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_attempts_npm_time` (`npm`, `attempted_at`),
  KEY `idx_attempts_ip_time` (`ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
