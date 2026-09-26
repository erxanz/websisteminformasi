<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class Mahasiswa extends Model
{
    /** @return array<string, mixed>|null */
    public function findByNpm(string $npm): ?array
    {
        return Database::selectOne(
            'SELECT npm, nama_mahasiswa, password FROM mahasiswa WHERE npm = ? LIMIT 1',
            [$npm],
        );
    }

    /** Join mahasiswa with its program studi and fakultas. */
    private const RELATION_SELECT = 'SELECT m.*, p.nama_program_studi, f.nama_fakultas
        FROM mahasiswa m
        JOIN program_studi p ON m.id_program_studi = p.id_program_studi
        JOIN fakultas f ON p.id_fakultas = f.id_fakultas';

    /** @return array<string, mixed>|null */
    public function findWithRelations(string $npm): ?array
    {
        return Database::selectOne(self::RELATION_SELECT . ' WHERE m.npm = ? LIMIT 1', [$npm]);
    }

    /** Kolom yang ikut dicari saat pengguna mengetik kata kunci. */
    private const SEARCH_COLUMNS = ['m.npm', 'm.nama_mahasiswa', 'p.nama_program_studi', 'f.nama_fakultas'];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function paginateWithRelations(string $search, int $limit, int $offset): array
    {
        [$where, $params] = $this->searchClause($search);

        return Database::select(
            self::RELATION_SELECT . $where . ' ORDER BY m.npm ASC LIMIT ? OFFSET ?',
            array_merge($params, [$limit, $offset]),
        );
    }

    public function countWithRelations(string $search): int
    {
        [$where, $params] = $this->searchClause($search);

        return (int) Database::scalar(
            'SELECT COUNT(*) FROM mahasiswa m
             JOIN program_studi p ON m.id_program_studi = p.id_program_studi
             JOIN fakultas f ON p.id_fakultas = f.id_fakultas' . $where,
            $params,
        );
    }

    /**
     * Susun klausa WHERE untuk pencarian bebas.
     *
     * @return array{0: string, 1: array<int, string>}
     */
    private function searchClause(string $search): array
    {
        $search = trim($search);

        if ($search === '') {
            return ['', []];
        }

        // Netralkan karakter khusus LIKE agar tidak berubah menjadi wildcard.
        $term = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);

        $conditions = [];
        $params     = [];

        foreach (self::SEARCH_COLUMNS as $column) {
            $conditions[] = $column . ' LIKE ?';
            $params[]     = '%' . $term . '%';
        }

        return [' WHERE (' . implode(' OR ', $conditions) . ')', $params];
    }

    /** @return array<int, array<string, mixed>> */
    public function byProgramStudi(int $programStudiId): array
    {
        return Database::select(
            self::RELATION_SELECT . ' WHERE m.id_program_studi = ? ORDER BY m.npm ASC',
            [$programStudiId],
        );
    }

    public function total(): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM mahasiswa');
    }

    public function updateProfile(string $npm, string $nama, ?string $email, ?string $phone, ?string $alamat): void
    {
        Database::run(
            'UPDATE mahasiswa SET nama_mahasiswa = ?, email = ?, phone = ?, alamat = ? WHERE npm = ?',
            [$nama, $email, $phone, $alamat, $npm],
        );
    }

    public function updatePhoto(string $npm, ?string $photo): void
    {
        Database::run('UPDATE mahasiswa SET photo = ? WHERE npm = ?', [$photo, $npm]);
    }

    /** Email sudah dipakai mahasiswa lain? (npm milik sendiri dikecualikan) */
    public function emailExists(string $email, string $exceptNpm): bool
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM mahasiswa WHERE email = ? AND npm <> ?',
            [$email, $exceptNpm],
        ) > 0;
    }

    public function updatePassword(string $npm, string $plainPassword): void
    {
        Database::run(
            'UPDATE mahasiswa SET password = ? WHERE npm = ?',
            [password_hash($plainPassword, PASSWORD_DEFAULT), $npm],
        );
    }
}
