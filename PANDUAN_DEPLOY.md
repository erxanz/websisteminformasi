================================================================================
      PANDUAN DEPLOY KE INFINITYFREE — LANGKAH DEMI LANGKAH
      Project: Sistem Informasi Akademik (Universitas Contoh)
================================================================================

Isi panduan:
  BAGIAN 1  Persiapan di komputer lokal
  BAGIAN 2  Siapkan hosting & database di InfinityFree
  BAGIAN 3  Upload file ke htdocs
  BAGIAN 4  Buat file .env di server
  BAGIAN 5  Set izin folder storage
  BAGIAN 6  Uji coba setelah deploy
  BAGIAN 7  Setelah berhasil (pembersihan & keamanan)
  BAGIAN 8  Troubleshooting (error yang sering muncul)
  BAGIAN 9  Checklist akhir


================================================================================
 BAGIAN 1 — PERSIAPAN DI KOMPUTER LOKAL
================================================================================

[ ] 1.1  Pastikan masalah benar-benar bisa jalan di lokal dulu.
         php -S 127.0.0.1:8000 -t public public/index.php

[ ] 1.2  Isi file .env sesuai kredensial database InfinityFree.
         Contoh isi .env:

             APP_NAME="Universitas Contoh"
             APP_ENV=production
             APP_DEBUG=false
             APP_TIMEZONE=Asia/Jakarta

             DB_HOST=sqlXXX.infinityfree.com
             DB_PORT=3306
             DB_DATABASE=if0_XXXXXXXX_universitas
             DB_USERNAME=if0_XXXXXXXX
             DB_PASSWORD=password_database_kamu

         CATATAN: APP_DEBUG=false supaya pesan error database tidak
         tampil ke pengunjung. Ini WAJIB untuk production.

[ ] 1.3  JANGAN upload file yang tidak perlu ke hosting:
         - .git/  (kalau ada)
         - file .log di storage/logs/
         (file .env JUSTRU HARUS diupload — lihat BAGIAN 4)


================================================================================
 BAGIAN 2 — SIAPKAN HOSTING & DATABASE DI INFINITYFREE
================================================================================

[ ] 2.1  Daftar / login ke InfinityFree, buat hosting account.
         Catat domain yang diberikan, misalnya:
             namakamu.infinityfreeapp.com

[ ] 2.2  Buka Control Panel (vPanel) -> menu "PHP Version".
         Pilih PHP 8.x (project ini butuh minimal PHP 7.4).
         Pastikan ekstensi ini aktif (biasanya sudah default):
             pdo_mysql, fileinfo, mbstring

[ ] 2.3  Buka vPanel -> "MySQL Databases" -> Create Database.
         Hasilnya kamu akan mendapat 4 informasi PENTING:

             MySQL Hostname : sqlXXX.infinityfree.com
             Database Name  : if0_XXXXXXXX_universitas
             Username       : if0_XXXXXXXX
             Password       : (password akun hosting kamu)

         Catat keempatnya. Ini yang nanti diisi ke file .env.

[ ] 2.4  Di halaman MySQL Databases, klik "Admin" / "phpMyAdmin"
         untuk database yang baru dibuat.

[ ] 2.5  Import struktur database:
         Cara A (paling cepat):
            - Klik tab "Import"
            - Choose file -> pilih  if0_42928294_universitas.sql
            - Klik Import / Go

         Cara B (lewat copy-paste):
            - Buka file KODE_MYSQL.txt
            - Klik tab "SQL" di phpMyAdmin
            - Copy-paste isi BAGIAN B (database baru), lalu Go

[ ] 2.6  Verifikasi: jalankan di tab SQL phpMyAdmin

             SHOW TABLES;

         Harus muncul 4 tabel:
             fakultas, login_attempts, mahasiswa, program_studi

         Kalau database SUDAH berisi data lama dan kamu tidak ingin
         menghapusnya, jalankan BAGIAN A dari KODE_MYSQL.txt saja.

CATATAN PENTING: hosting gratis InfinityFree TIDAK bisa diakses dari
luar (remote MySQL diblokir). Jadi database hanya bisa diisi lewat
phpMyAdmin di panel mereka — bukan dari aplikasi di komputer lokal.


================================================================================
 BAGIAN 3 — UPLOAD FILE KE HTDOCS
================================================================================

[ ] 3.1  Buka vPanel -> "Online File Manager", ATAU pakai FTP client
         (FileZilla) dengan data FTP dari menu "FTP Accounts":
             Host     : ftpupload.net (atau sesuai panel)
             Username : if0_XXXXXXXX
             Password : password akun hosting

[ ] 3.2  Masuk ke folder  htdocs  (ini document root kamu).
         Kalau ada file bawaan (default.php, index2.html, dll), HAPUS.

[ ] 3.3  Upload SELURUH isi project ke dalam htdocs, sehingga
         strukturnya menjadi:

             htdocs/
             ├── .env                <-- dibuat di BAGIAN 4
             ├── .htaccess           <-- PENTING, sering terlewat!
             ├── app/
             ├── config/
             ├── migrations/
             ├── public/
             ├── routes/
             ├── storage/
             ├── .gitignore
             └── (file .txt dan .sql)

   ###  PERHATIAN PALING SERING BIKIN GAGAL  ###
   File .htaccess namanya diawali titik, jadi:
     - FileZilla   : Server -> Force showing hidden files (centang)
     - File Manager: aktifkan opsi "show hidden files"
   Kalau .htaccess tidak terupload, SEMUA halaman akan 404.


================================================================================
 BAGIAN 4 — BUAT FILE .env DI SERVER
================================================================================

[ ] 4.1  Lewat File Manager: klik kanan -> New File -> beri nama  .env
         (tanpa ekstensi apa pun, hanya ".env").

[ ] 4.2  Edit isinya dan isi sesuai data dari langkah 2.3:

             APP_NAME="Universitas Contoh"
             APP_ENV=production
             APP_DEBUG=false
             APP_TIMEZONE=Asia/Jakarta

             DB_HOST=sqlXXX.infinityfree.com
             DB_PORT=3306
             DB_DATABASE=if0_XXXXXXXX_universitas
             DB_USERNAME=if0_XXXXXXXX
             DB_PASSWORD=password_database_kamu

[ ] 4.3  Simpan.

CATATAN:
  - Jangan menambah tanda kutip pada password kecuali memang ada
    tanda kutip di passwordnya.
  - File .env sudah diblokir oleh .htaccess, jadi tidak bisa dibaca
    dari browser. Jangan menaruhnya di dalam folder public/.


================================================================================
 BAGIAN 5 — SET IZIN FOLDER STORAGE
================================================================================

Aplikasi menulis ke storage/ untuk log dan foto profil. Folder ini
harus bisa ditulis oleh PHP.

[ ] 5.1  Pastikan folder ini ada di server:
             storage/logs/
             storage/uploads/profiles/

         Kalau ada yang hilang, buat lewat File Manager
         (folder kosong kadang tidak terupload oleh FTP).

[ ] 5.2  Set permission (klik kanan -> Permissions / CHMOD):
             storage            -> 755  (kalau gagal upload, coba 777)
             storage/logs       -> 755
             storage/uploads    -> 755
             storage/uploads/profiles -> 755

[ ] 5.3  Jangan set 777 kalau tidak perlu. Mulai dari 755, dan naikkan
         ke 777 hanya bila muncul pesan "Folder penyimpanan foto tidak
         dapat diakses" atau log gagal ditulis.


================================================================================
 BAGIAN 6 — UJI COBA SETELAH DEPLOY
================================================================================

Buka domain kamu, lalu periksa satu per satu:

[ ] 6.1  Halaman utama (/) terbuka, statistik & tabel mahasiswa tampil.
         -> Kalau tampil halaman "Terjadi Kesalahan": kredensial .env salah.

[ ] 6.2  Pencarian & pagination bekerja:
             /?q=ahmad
             /?page=1

[ ] 6.3  Halaman detail program studi bekerja:
             /program-studi/1
             /program-studi/999   -> harus tampil 404

[ ] 6.4  Login dengan akun contoh:
             NPM      : 2101010001
             Password : password123
         -> Kalau gagal, cek blok BAGIAN B sudah diimport (password bcrypt).

[ ] 6.5  Dashboard tampil dan menampilkan data mahasiswa yang login.

[ ] 6.6  Halaman Profil Saya (/profil):
         - ubah nama / email / nomor HP / alamat -> simpan -> muncul
           notifikasi hijau "Data profil berhasil diperbarui."
         - upload foto JPG/PNG/WEBP maksimal 2 MB -> foto tampil
         - coba upload file .txt atau file lebih dari 2 MB
           -> harus DITOLAK dengan pesan yang jelas

[ ] 6.7  Ubah password (/ubah-password):
         - masukkan password lama, password baru 8+ karakter, konfirmasi sama
         - setelah sukses, logout lalu login dengan password baru
         - KEMBALIKAN ke password semula atau catat password barunya

[ ] 6.8  Anti brute force: coba login salah 6 kali berturut-turut.
         Percobaan ke-6 harus ditolak dengan pesan
         "Terlalu banyak percobaan login... menit."

[ ] 6.9  Batas file upload PHP: pastikan hosting mengizinkan minimal
         2 MB. Kalau upload gagal walau file < 2 MB, cek
         upload_max_filesize & post_max_size di panel hosting.


================================================================================
 BAGIAN 7 — SETELAH BERHASIL (PEMBERSIHAN & KEAMANAN)
================================================================================

[ ] 7.1  HAPUS file yang tidak diperlukan di server (mengurangi risiko
         dan menghemat ruang). File-file ini sudah diblokir .htaccess,
         tapi lebih aman dihapus saja:
             if0_42928294_universitas.sql
             KODE_MYSQL.txt
             PANDUAN_DEPLOY.txt
             README.md
             .gitignore
             .env.example
             folder migrations/  (hanya untuk setup, tidak dipakai aplikasi)

[ ] 7.2  Pastikan APP_DEBUG=false di .env.
         Kalau true, pengunjung bisa melihat detail error dan struktur
         kode kamu.

[ ] 7.3  Segera GANTI password semua akun contoh (password123).
         Caranya: login -> Profil Saya -> Ubah Password.

[ ] 7.4  Bersihkan data contoh yang tidak dipakai (opsional):
             - hapus baris mahasiswa contoh lewat phpMyAdmin
             - atau ubah email/nama/alamatnya sesuai data sebenarnya

[ ] 7.5  Cek folder storage/logs. Kalau ada isi app.log, berarti ada
         error yang tercatat. Buka dan perbaiki penyebabnya.


================================================================================
 BAGIAN 8 — TROUBLESHOOTING
================================================================================

--- Semua halaman 404 Not Found -------------------------------------------
  Penyebab tersering: file .htaccess TIDAK terupload (file tersembunyi).
  Cek: File Manager -> aktifkan "show hidden files" -> pastikan .htaccess
  ada di htdocs (bukan hanya di htdocs/public).
  Alternatif cek: coba buka /public/index.php langsung dari browser.
  Kalau itu terbuka, berarti masalahnya di .htaccess root.

--- Halaman "Terjadi Kesalahan" (kode 500) --------------------------------
  Aplikasi menulis detail ke storage/logs/app.log. Buka file itu.
  Penyebab umum:
    1. Kredensial .env salah -> "Koneksi database gagal."
    2. Kolom database belum lengkap -> error SQL
       (email/phone/photo belum ada) -> jalankan BAGIAN A.
    3. Folder storage/ tidak bisa ditulis -> permission.

--- "Koneksi database gagal" ----------------------------------------------
  - Pastikan DB_HOST berbentuk sqlXXX.infinityfree.com (bukan localhost).
  - Pastikan DB_DATABASE & DB_USERNAME persis seperti di panel
    (keduanya biasanya diawali if0_).
  - Password database = password akun hosting.

--- Blank putih / tidak ada pesan apa pun ---------------------------------
  Biasanya error fatal PHP. Set sementara APP_DEBUG=true untuk melihat
  pesannya, lalu KEMBALIKAN ke false setelah selesai.

--- Upload foto gagal padahal ukurannya kecil ----------------------------
  - Cek izin storage/uploads/profiles (coba 777).
  - Cek upload_max_filesize dan post_max_size di panel hosting.
  - Cek pesan yang muncul: kalau "Berkas yang dikirim terlalu besar
    sehingga tidak sampai ke server", berarti batas PHP hosting yang
    memblokir, bukan aplikasi.

--- Login selalu gagal padahal NPM & password benar ------------------------
  Cek tabel login_attempts. Kalau ada banyak baris untuk NPM/IP kamu,
  hapus dulu untuk mereset blokir:
      DELETE FROM login_attempts;
  Blokir otomatis hilang sendiri setelah 15 menit.

--- Session login cepat hilang / ter-logout sendiri -----------------------
  Pastikan tidak ada dua file index.php yang saling menimpa, dan
  folder session hosting tidak penuh. Logout lalu login ulang.

--- Foto profil tidak muncul (ikon gambar rusak) --------------------------
  Cek Database: SELECT npm, photo FROM mahasiswa LIMIT 5;
  Nilai photo harus berbentuk  profiles/<32 karakter heksa>.<ext>
  Kalau isinya path lain, kosongkan saja:
      UPDATE mahasiswa SET photo = NULL WHERE npm = '2101010001';


================================================================================
 BAGIAN 9 — CHECKLIST AKHIR
================================================================================

[ ] .htaccess sudah ada di htdocs
[ ] .env sudah dibuat di htdocs dengan kredensial yang benar
[ ] APP_DEBUG=false
[ ] 4 tabel sudah ada di database
[ ] storage/logs dan storage/uploads/profiles bisa ditulis
[ ] Halaman utama, pencarian, dan detail prodi tampil normal
[ ] Login berhasil dan dashboard menampilkan data yang benar
[ ] Ubah data profil + upload foto berhasil
[ ] File > 2 MB dan file non-gambar ditolak
[ ] Ubah password berhasil, lalu login ulang dengan password baru
[ ] Password default (password123) sudah diganti
[ ] File .sql / .txt / .md sudah dihapus dari server
[ ] storage/logs/app.log kosong (tidak ada error tercatat)

================================================================================
