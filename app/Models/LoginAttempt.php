<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

/**
 * Mencatat login yang gagal untuk membatasi serangan brute force.
 *
 * Waktu disimpan sebagai DATETIME yang dihasilkan PHP (bukan CURRENT_TIMESTAMP)
 * supaya perbandingan tidak terpengaruh perbedaan timezone server MySQL.
 */
final class LoginAttempt extends Model
{
    /** Blokir sementara setelah sekian kali gagal pada satu akun. */
    private const MAX_FAILURES_PER_ACCOUNT = 5;

    /** Batas yang lebih longgar per alamat IP, untuk mengakomodasi jaringan bersama. */
    private const MAX_FAILURES_PER_IP = 20;

    /** Panjang jendela waktu perhitungan (menit). */
    private const DECAY_MINUTES = 15;

    /** Berapa lama riwayat percobaan disimpan sebelum dibersihkan (jam). */
    private const RETENTION_HOURS = 24;

    public function recordFailure(string $npm, string $ip): void
    {
        Database::run(
            'INSERT INTO login_attempts (npm, ip_address, attempted_at) VALUES (?, ?, ?)',
            [$npm, $ip, date('Y-m-d H:i:s')],
        );

        $this->prune();
    }

    /** Hapus riwayat kegagalan akun setelah login berhasil. */
    public function clearFailures(string $npm): void
    {
        Database::run('DELETE FROM login_attempts WHERE npm = ?', [$npm]);
    }

    public function isBlocked(string $npm, string $ip): bool
    {
        $cutoff = $this->cutoff();

        return $this->failures('npm', $npm, $cutoff) >= self::MAX_FAILURES_PER_ACCOUNT
            || $this->failures('ip_address', $ip, $cutoff) >= self::MAX_FAILURES_PER_IP;
    }

    /** Perkiraan lama tunggu (menit) sebelum percobaan berikutnya diizinkan. */
    public function blockedForMinutes(string $npm, string $ip): int
    {
        $cutoff = $this->cutoff();
        $column = $this->failures('npm', $npm, $cutoff) >= self::MAX_FAILURES_PER_ACCOUNT ? 'npm' : 'ip_address';
        $value  = $column === 'npm' ? $npm : $ip;
        $oldest = $this->oldestFailure($column, $value, $cutoff);

        if ($oldest === null) {
            return self::DECAY_MINUTES;
        }

        $remainingSeconds = (int) strtotime($oldest) + self::DECAY_MINUTES * 60 - time();

        return max(1, (int) ceil($remainingSeconds / 60));
    }

    /**
     * $column hanya berasal dari dua nilai tetap di dalam class ini (bukan input pengguna).
     */
    private function failures(string $column, string $value, string $cutoff): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM login_attempts WHERE ' . $column . ' = ? AND attempted_at >= ?',
            [$value, $cutoff],
        );
    }

    private function oldestFailure(string $column, string $value, string $cutoff): ?string
    {
        $oldest = Database::scalar(
            'SELECT MIN(attempted_at) FROM login_attempts WHERE ' . $column . ' = ? AND attempted_at >= ?',
            [$value, $cutoff],
        );

        return $oldest === null ? null : (string) $oldest;
    }

    private function cutoff(): string
    {
        return date('Y-m-d H:i:s', time() - self::DECAY_MINUTES * 60);
    }

    private function prune(): void
    {
        Database::run(
            'DELETE FROM login_attempts WHERE attempted_at < ?',
            [date('Y-m-d H:i:s', time() - self::RETENTION_HOURS * 3600)],
        );
    }
}
