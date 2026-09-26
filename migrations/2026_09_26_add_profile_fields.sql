-- ============================================================================
--  Migration: kolom profil mahasiswa
--  Jalankan sekali pada database yang sudah ada (mis. lewat tab SQL phpMyAdmin).
--
--  Kolom yang ditambahkan:
--    email  -> alamat email (unik, boleh NULL)
--    phone  -> nomor HP
--    photo  -> path foto profil di storage/uploads/profiles/<32 hex>.<ext>
--
--  Catatan: alamat TIDAK memakai kolom baru `address` karena tabel mahasiswa
--  sudah memiliki kolom `alamat`. Menambah `address` akan menduplikasi data.
-- ============================================================================

ALTER TABLE `mahasiswa`
  ADD COLUMN `email` VARCHAR(150) DEFAULT NULL AFTER `nama_mahasiswa`,
  ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL AFTER `alamat`,
  ADD COLUMN `photo` VARCHAR(255) DEFAULT NULL AFTER `phone`,
  ADD UNIQUE KEY `uq_mahasiswa_email` (`email`);
