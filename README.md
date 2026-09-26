# Sistem Informasi Akademik — Universitas Contoh

Aplikasi PHP native dengan arsitektur MVC, PDO prepared statement, dan struktur
folder yang mudah dikembangkan.

## Struktur

```bash
app/
├── Controllers/   HomeController, AuthController, DashboardController,
│                  ProfileController, ProgramStudiController, MediaController
├── Models/        Mahasiswa, ProgramStudi, Fakultas, LoginAttempt (+ Model dasar)
├── Views/         layouts/, partials/, home/, prodi/, profil/, auth/, dashboard/, errors/
├── Core/          Database, Router, Controller, Model, View, Auth, Session, Validator, Paginator, ImageUploader, Env
└── Helpers/       functions.php
config/            app.php, database.php
routes/            web.php
migrations/        migration SQL untuk database yang sudah berjalan
KODE_MYSQL.md      semua kode MySQL siap copy-paste ke tab SQL phpMyAdmin
PANDUAN_DEPLOY.md  panduan deployment langkah demi langkah (InfinityFree)
public/            index.php (front controller), .htaccess
storage/logs/      app.log (dibuat otomatis saat ada error)
storage/uploads/profiles/  foto profil (tidak dapat diakses langsung)
.env               kredensial (jangan di-commit)
```

## Rute

| Method | URL          | Aksi                          |
|--------|--------------|-------------------------------|
| GET    | `/`          | Landing page + data mahasiswa |
| GET    | `/program-studi/{id}` | Detail prodi + daftar mahasiswanya |
| GET    | `/login`     | Form login                    |
| POST   | `/login`     | Proses login                  |
| POST   | `/logout`    | Logout (CSRF-protected)       |
| GET    | `/dashboard` | Dashboard (wajib login)       |
| GET    | `/profil`    | Profil mahasiswa yang login   |
| POST   | `/profil`    | Simpan perubahan data diri    |
| POST   | `/profil/foto` | Unggah foto profil          |
| POST   | `/profil/foto/hapus` | Hapus foto, kembali ke avatar default |
| GET    | `/media/profil/{file}` | Menyajikan foto profil |
| GET    | `/ubah-password` | Form ganti password        |
| POST   | `/ubah-password` | Simpan password baru       |

## Menjalankan secara lokal

```bash
cp .env.example .env      # lalu isi kredensial database
php -S 127.0.0.1:8000 -t public public/index.php
```

Buka `http://127.0.0.1:8000`.

## Profil mahasiswa & upload foto

Halaman `/profil` hanya dapat diakses mahasiswa yang sudah login. NPM **tidak pernah**
ada di URL, jadi mahasiswa secara struktural tidak bisa membuka atau mengubah profil
orang lain — data selalu diambil dari session, bukan dari parameter request.

Kolom yang bisa diubah sendiri: nama, email, nomor HP, alamat, dan foto.
NPM, program studi, dan fakultas bersifat read-only — tidak ada fitur untuk
mengubahnya lewat aplikasi.

### Alur upload foto

1. Form mengirim `multipart/form-data` ke `POST /profil/foto` beserta token CSRF.
2. `ImageUploader` memvalidasi: kode error upload, `is_uploaded_file()`, ukuran ≤ 2 MB,
   ekstensi nama asli, MIME asli dari `finfo`, dan `getimagesize()`.
3. Nama berkas dibuat ulang: `bin2hex(random_bytes(16))` + ekstensi dari MIME terdeteksi.
   Nama asli dari pengguna tidak pernah menyentuh disk.
4. Berkas dipindahkan ke `storage/uploads/profiles/` dan path-nya disimpan di kolom `photo`.
5. Foto lama dihapus **hanya** jika polanya cocok dengan berkas yang dikelola aplikasi
   (`profiles/<32 hex>.<ext>`), sehingga nilai kolom yang dimanipulasi tidak bisa
   menghapus berkas lain di server.
6. Tombol "Hapus Foto" (muncul hanya jika foto ada) mengosongkan kolom `photo`
   lalu menghapus berkasnya, sehingga tampilan kembali ke avatar inisial.

Folder `storage/` sudah diblokir oleh `.htaccess` di root, sehingga foto dilayani
lewat rute `GET /media/profil/{file}` yang hanya menerima nama berkas dengan pola ketat.

## Pencarian & pagination

Tabel data mahasiswa di landing page mendukung pencarian dan pagination lewat query string:

- `/?q=budi` — cari pada kolom NPM, nama mahasiswa, program studi, dan fakultas
- `/?q=budi&page=2` — halaman ke-2 dari hasil pencarian

10 baris per halaman (`HomeController::PER_PAGE`). Karakter `%` dan `_` pada kata kunci
 di-escape sehingga tidak diperlakukan sebagai wildcard.

## Batas percobaan login (anti brute force)

Setiap login gagal dicatat di tabel `login_attempts`. Login diblokir sementara jika:

- satu **akun** gagal 5 kali dalam 15 menit, atau
- satu **alamat IP** gagal 20 kali dalam 15 menit (batas lebih longgar untuk jaringan bersama).

Riwayat kegagalan sebuah akun dihapus setelah login berhasil. Catatan lama (>24 jam)
 dibersihkan otomatis. Angkanya bisa diubah lewat konstanta di `app/Models/LoginAttempt.php`.

> **Penting:** tabel `login_attempts` adalah tabel baru. Import ulang
> `if0_42928294_universitas.sql` (atau jalankan blok `CREATE TABLE login_attempts` saja).

## Keamanan

- Password disimpan dengan `password_hash()` (bcrypt), bukan SHA256.
- Semua query memakai PDO prepared statement (anti SQL Injection).
- Output di-escape dengan `esc()` (anti XSS).
- Form login & logout diproteksi CSRF token.
- Session di-`regenerate_id()` saat login dan setiap kali password diubah.
- Ganti password mewajibkan verifikasi password lama dan konfirmasi password baru.
- Login dibatasi 5 kegagalan per akun / 20 per IP dalam 15 menit (anti brute force).
- Upload foto: MIME diperiksa dari isi berkas, nama file digenerate ulang, dan folder
  unggahan tidak dapat dieksekusi maupun diakses langsung.
- Semua form profil dilindungi token CSRF.
- Error ditampilkan generik; detailnya ditulis ke `storage/logs/app.log`.

## Kebutuhan minimum

PHP 7.4+ dengan ekstensi PDO MySQL, fileinfo, dan mbstring.

> Database yang sudah berjalan perlu menambahkan kolom profil sekali saja:
> jalankan `migrations/2026_09_26_add_profile_fields.sql` (atau buka
> `KODE_MYSQL.md` -> **BAGIAN A**, aman, tidak menghapus data).
> Untuk database baru, jalankan **BAGIAN B** pada `KODE_MYSQL.md` atau
> import `if0_42928294_universitas.sql`.
