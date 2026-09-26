================================================================================
                        KODE MySQL — SIAP COPY-PASTE
              Sistem Informasi Akademik (database: universitas)
================================================================================

CARA PAKAI:
  1. Buka phpMyAdmin
  2. Pilih database: if0_42928294_universitas  (klik namanya di kiri)
  3. Klik tab "SQL"
  4. Pilih SALAH SATU bagian saja, lalu copy-paste isinya dan klik Go / Kirim

  PENTING — JANGAN SALAH PILIH:

  +---------------------+------------------------------------------------------+
  | BAGIAN A            | Database SUDAH ADA isinya. AMAN. Tidak menghapus     |
  | (yang kamu cari)    | data apa pun. Hanya menambah kolom profil.           |
  +---------------------+------------------------------------------------------+
  | BAGIAN B            | Database BARU / kosong. MENGHAPUS semua tabel lama!  |
  |                     | Pakai ini HANYA kalau datanya boleh hilang.           |
  +---------------------+------------------------------------------------------+

  Kalau kamu sudah pernah mengisi data mahasiswa, jalankan BAGIAN A.


================================================================================
 BAGIAN A — DATABASE SUDAH ADA ISI (AMAN, TIDAK MENGHAPUS DATA)
================================================================================
Menambahkan kolom profil: email, phone, photo.
Jalankan SEKALI saja. Kalau dijalankan dua kali akan muncul error
"Duplicate column name" / "Duplicate key name" — itu wajar, artinya sudah ada.


ALTER TABLE `mahasiswa`
  ADD COLUMN `email` VARCHAR(150) DEFAULT NULL AFTER `nama_mahasiswa`,
  ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL AFTER `alamat`,
  ADD COLUMN `photo` VARCHAR(255) DEFAULT NULL AFTER `phone`,
  ADD UNIQUE KEY `uq_mahasiswa_email` (`email`);



================================================================================
 BAGIAN B — DATABASE BARU / SETUP ULANG
================================================================================
   ####################################################################
   #  PERINGATAN: bagian ini menjalankan DROP TABLE.                  #
   #  SELURUH DATA pada tabel fakultas, program_studi, mahasiswa,     #
   #  dan login_attempts akan HILANG dan diganti data contoh.         #
   #  LEWATI bagian ini kalau kamu masih butuh data yang sekarang.    #
   ####################################################################

Password semua akun contoh di bawah adalah:  password123


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
-- 1. Tabel: fakultas
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
-- 2. Tabel: program_studi
--    FK -> fakultas : fakultas tidak bisa dihapus kalau masih dipakai (RESTRICT)
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
-- 3. Tabel: mahasiswa
--    PK  : npm
--    FK  : id_program_studi -> program_studi
--    password = hasil password_hash() (bcrypt)
--    Kolom profil yang bisa diubah sendiri: email, phone, alamat, photo
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
-- 4. Tabel: login_attempts
--    Mencatat login GAGAL untuk membatasi brute force.
--    Tanpa FK ke mahasiswa karena NPM yang dicoba bisa saja tidak terdaftar.
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



================================================================================
 BAGIAN C — VERIFIKASI (jalankan setelah BAGIAN A atau B)
================================================================================

-- Lihat semua kolom tabel mahasiswa (pastikan email, phone, photo sudah ada):
SHOW COLUMNS FROM `mahasiswa`;

-- Lihat semua tabel yang terbentuk (harusnya 4 tabel):
SHOW TABLES;

-- Sudah ada berapa mahasiswa:
SELECT COUNT(*) AS total_mahasiswa FROM `mahasiswa`;



================================================================================
 CATATAN PENTING
================================================================================
1. `email` dibuat UNIQUE, tetapi nilai NULL boleh berulang. Jadi kalau kamu
   memakai BAGIAN A pada database yang sudah berisi data, mahasiswa lama yang
   email-nya masih kosong tidak akan bentrok.

2. Tabel TIDAK memakai kolom `address`. Tabel `mahasiswa` sudah punya kolom
   `alamat`, jadi form Alamat di aplikasi memakai kolom itu. Membuat `address`
   baru hanya akan menduplikasi data.

3. Kolom `photo` menyimpan PATH RELATIF, contoh:
       profiles/003d4d8aa9e7a915324ead384d11c1a5.png
   Berkas aslinya ada di server pada folder:
       storage/uploads/profiles/
   Jangan mengisi kolom ini manual dengan path lain.

4. Semua tabel memakai ENGINE=InnoDB supaya FOREIGN KEY dan transaksi berjalan.
   Charset utf8mb4 supaya bisa menyimpan karakter Unicode penuh.

5. Password default data contoh (BAGIAN B) adalah:  password123
   Segera ganti lewat menu Profil Saya setelah login pertama kali.

6. File ini sama isinya dengan file dump `if0_42928294_universitas.sql`.
   Versi itu bisa langsung di-import lewat tab "Import" phpMyAdmin.
   File .txt ini disediakan supaya bisa di-copy-paste lewat tab "SQL".

7. Query yang dipakai aplikasi sudah memakai PDO prepared statement, jadi
   karakter seperti tanda kutip pada nama atau alamat tetap aman disimpan.
================================================================================
