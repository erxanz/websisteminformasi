<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * Melayani foto profil dari storage/ yang tidak dapat diakses langsung lewat web.
 * Nama berkas selalu dihasilkan aplikasi, sehingga pola ketat di bawah ini sudah
 * cukup untuk mencegah path traversal.
 */
final class MediaController extends Controller
{
    private const MIME_TYPES = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
    ];

    public function profilePhoto(string $file): void
    {
        if (preg_match('/^([a-f0-9]{32})\.(jpg|jpeg|png|webp)$/', $file, $matches) !== 1) {
            $this->notFound();
        }

        $extension = strtolower($matches[2]);
        $path      = BASE_PATH . '/storage/uploads/profiles/' . $file;

        if (!is_file($path)) {
            $this->notFound();
        }

        header('Content-Type: ' . self::MIME_TYPES[$extension]);
        header('Content-Length: ' . (string) filesize($path));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=86400');

        readfile($path);
        exit;
    }
}
