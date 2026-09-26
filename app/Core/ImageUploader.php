<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Validasi dan penyimpanan foto profil.
 *
 * Tipe berkas ditentukan dari isi berkas (finfo + getimagesize), bukan dari
 * header Content-Type kiriman browser maupun ekstensi nama asli. Nama berkas
 * selalu dibuat ulang sehingga nama dari pengguna tidak pernah menyentuh disk.
 */
final class ImageUploader
{
    private const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /** 2 MB */
    private const MAX_SIZE_BYTES = 2097152;

    private string $directory;

    /** @var array<string, mixed> */
    private array $file;

    private ?string $error = null;

    private ?string $storedName = null;

    /** @param array<string, mixed> $file Satu entri dari $_FILES. */
    public function __construct(string $directory, array $file)
    {
        $this->directory = rtrim($directory, '/\\');
        $this->file      = $file;
    }

    /** true bila pengguna memang tidak memilih berkas apa pun. */
    public function isEmpty(): bool
    {
        return (int) ($this->file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function storedName(): ?string
    {
        return $this->storedName;
    }

    public function store(): bool
    {
        $errorCode = (int) ($this->file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($errorCode !== UPLOAD_ERR_OK) {
            $this->error = $this->messageForErrorCode($errorCode);

            return false;
        }

        $temporaryPath = (string) ($this->file['tmp_name'] ?? '');

        // Pastikan berkas benar-benar berasal dari proses upload PHP.
        if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
            $this->error = 'Berkas tidak valid.';

            return false;
        }

        $size = (int) ($this->file['size'] ?? 0);

        if ($size <= 0 || $size > self::MAX_SIZE_BYTES) {
            $this->error = 'Ukuran foto maksimal 2 MB.';

            return false;
        }

        if (!$this->hasAllowedExtension((string) ($this->file['name'] ?? ''))) {
            $this->error = 'Format foto harus JPG, JPEG, PNG, atau WEBP.';

            return false;
        }

        $mimeType = $this->detectMimeType($temporaryPath);

        if ($mimeType === null || !isset(self::MIME_EXTENSIONS[$mimeType])) {
            $this->error = 'Isi berkas bukan gambar yang didukung (JPG, PNG, atau WEBP).';

            return false;
        }

        // Memastikan berkas sungguh gambar yang dapat dibaca, bukan skrip berkedok gambar.
        if (@getimagesize($temporaryPath) === false) {
            $this->error = 'Berkas gambar tidak dapat dibaca.';

            return false;
        }

        if (!$this->ensureDirectory()) {
            $this->error = 'Folder penyimpanan foto tidak dapat diakses.';

            return false;
        }

        $fileName    = bin2hex(random_bytes(16)) . '.' . self::MIME_EXTENSIONS[$mimeType];
        $destination = $this->directory . '/' . $fileName;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            $this->error = 'Gagal menyimpan foto. Silakan coba lagi.';

            return false;
        }

        @chmod($destination, 0644);

        $this->storedName = $fileName;

        return true;
    }

    private function hasAllowedExtension(string $originalName): bool
    {
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

    private function detectMimeType(string $path): ?string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($path);

        return $mime === false ? null : (string) $mime;
    }

    private function ensureDirectory(): bool
    {
        if (is_dir($this->directory)) {
            return true;
        }

        return @mkdir($this->directory, 0755, true);
    }

    private function messageForErrorCode(int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Ukuran foto maksimal 2 MB.';
            case UPLOAD_ERR_PARTIAL:
                return 'Upload foto tidak selesai. Silakan coba lagi.';
            case UPLOAD_ERR_NO_FILE:
                return 'Tidak ada berkas yang dipilih.';
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                return 'Server tidak dapat menyimpan berkas. Hubungi administrator.';
            default:
                return 'Gagal mengunggah foto. Silakan coba lagi.';
        }
    }
}
