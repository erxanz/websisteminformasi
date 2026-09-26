<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\ImageUploader;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Mahasiswa;

/**
 * Profil mahasiswa yang sedang login. Tidak ada parameter NPM di URL, sehingga
 * mahasiswa secara struktural tidak bisa mengakses profil milik orang lain.
 */
final class ProfileController extends Controller
{
    private const PHOTO_FOLDER = 'profiles';

    /** Pola berkas yang dikelola aplikasi: profiles/<32 hex>.<ekstensi> */
    private const MANAGED_PHOTO_PATTERN = '/^profiles\/[a-f0-9]{32}\.(jpg|jpeg|png|webp)$/';

    public function show(): void
    {
        $user = $this->requireAuth();

        $this->view('profil/edit', [
            'pageTitle' => 'Profil Saya',
            'mhs'       => $this->profile((string) $user['npm']),
            'errors'    => Session::getFlash('errors', []),
        ]);
    }

    public function update(): void
    {
        $user = $this->requireAuth();

        $this->guardCsrf('/profil');

        $npm    = (string) $user['npm'];
        $nama   = trim((string) ($_POST['nama_mahasiswa'] ?? ''));
        $email  = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $phone  = trim((string) ($_POST['phone'] ?? ''));
        $alamat = trim((string) ($_POST['alamat'] ?? ''));

        $validator = Validator::make(
            ['nama_mahasiswa' => $nama, 'email' => $email, 'phone' => $phone, 'alamat' => $alamat],
            [
                'nama_mahasiswa' => 'required|max:100',
                'email'          => 'required|email|max:150',
                'phone'          => 'max:20',
                'alamat'         => 'max:1000',
            ],
            [
                'nama_mahasiswa' => 'Nama lengkap',
                'email'          => 'Email',
                'phone'          => 'Nomor HP',
                'alamat'         => 'Alamat',
            ],
        );

        $errors = $validator->errors();

        if (!isset($errors['phone']) && $phone !== '' && preg_match('/^[0-9+\-\s().]{6,20}$/', $phone) !== 1) {
            $errors['phone'] = 'Nomor HP hanya boleh berisi angka dan simbol + - ( ) .';
        }

        if (!isset($errors['email']) && (new Mahasiswa())->emailExists($email, $npm)) {
            $errors['email'] = 'Email tersebut sudah dipakai mahasiswa lain.';
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            $this->redirect('/profil');
        }

        (new Mahasiswa())->updateProfile(
            $npm,
            $nama,
            $email,
            $phone === '' ? null : $phone,
            $alamat === '' ? null : $alamat,
        );

        Session::flash('success', 'Data profil berhasil diperbarui.');
        $this->redirect('/profil');
    }

    public function updatePhoto(): void
    {
        $user = $this->requireAuth();

        $this->guardCsrf('/profil');

        $uploader = new ImageUploader(
            BASE_PATH . '/storage/uploads/' . self::PHOTO_FOLDER,
            is_array($_FILES['photo'] ?? null) ? $_FILES['photo'] : [],
        );

        if ($uploader->isEmpty()) {
            Session::flash('errors', ['photo' => 'Silakan pilih foto terlebih dahulu.']);
            $this->redirect('/profil');
        }

        if (!$uploader->store()) {
            Session::flash('errors', ['photo' => (string) $uploader->error()]);
            $this->redirect('/profil');
        }

        $npm    = (string) $user['npm'];
        $model  = new Mahasiswa();
        $before = $model->findWithRelations($npm);
        $oldPhoto = is_array($before) ? (string) ($before['photo'] ?? '') : '';

        $newPhoto = self::PHOTO_FOLDER . '/' . (string) $uploader->storedName();
        $model->updatePhoto($npm, $newPhoto);

        // Hapus foto lama hanya bila benar-benar berkas yang dikelola aplikasi.
        if ($oldPhoto !== '' && $oldPhoto !== $newPhoto && $this->isManagedPhoto($oldPhoto)) {
            @unlink(BASE_PATH . '/storage/uploads/' . $oldPhoto);
        }

        Session::flash('success', 'Foto profil berhasil diperbarui.');
        $this->redirect('/profil');
    }

    public function deletePhoto(): void
    {
        $user = $this->requireAuth();

        $this->guardCsrf('/profil');

        $npm     = (string) $user['npm'];
        $model   = new Mahasiswa();
        $current = $model->findWithRelations($npm);
        $photo   = is_array($current) ? (string) ($current['photo'] ?? '') : '';

        if ($photo === '') {
            Session::flash('errors', ['photo' => 'Tidak ada foto profil yang bisa dihapus.']);
            $this->redirect('/profil');
        }

        // Kosongkan kolom lebih dulu supaya data tidak menunjuk berkas yang sudah hilang.
        $model->updatePhoto($npm, null);

        if ($this->isManagedPhoto($photo)) {
            @unlink(BASE_PATH . '/storage/uploads/' . $photo);
        }

        Session::flash('success', 'Foto profil berhasil dihapus. Avatar kembali ke inisial nama Anda.');
        $this->redirect('/profil');
    }

    /** Tolak permintaan POST tanpa token CSRF yang sah. */
    private function guardCsrf(string $redirectTo): void
    {
        if (Session::verifyCsrf($_POST['_token'] ?? null)) {
            return;
        }

        // $_POST bisa kosong bila unggahan melebihi post_max_size PHP.
        $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
        $message = ($_POST === [] && $contentLength > 0)
            ? 'Berkas yang dikirim terlalu besar sehingga tidak sampai ke server. Maksimal 2 MB.'
            : 'Sesi Anda telah berakhir. Silakan coba lagi.';

        Session::flash('errors', ['form' => $message]);
        $this->redirect($redirectTo);
    }

    private function isManagedPhoto(string $photo): bool
    {
        return preg_match(self::MANAGED_PHOTO_PATTERN, $photo) === 1;
    }

    /** @return array<string, mixed> */
    private function profile(string $npm): array
    {
        $mahasiswa = (new Mahasiswa())->findWithRelations($npm);

        if ($mahasiswa === null) {
            Auth::logout();
            $this->redirect('/login');
        }

        return $mahasiswa;
    }
}
