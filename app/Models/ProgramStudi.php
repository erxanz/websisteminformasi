<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class ProgramStudi extends Model
{
    /** @return array<int, array<string, mixed>> */
    public function allWithStudentCount(): array
    {
        return Database::select(
            'SELECT p.id_program_studi, p.nama_program_studi, COUNT(m.npm) AS jumlah_mhs
             FROM program_studi p
             LEFT JOIN mahasiswa m ON p.id_program_studi = m.id_program_studi
             GROUP BY p.id_program_studi, p.nama_program_studi
             ORDER BY p.nama_program_studi ASC',
        );
    }

    /** Satu program studi beserta fakultas dan jumlah mahasiswanya. */
    public function find(int $id): ?array
    {
        return Database::selectOne(
            'SELECT p.id_program_studi, p.nama_program_studi, f.nama_fakultas, COUNT(m.npm) AS jumlah_mhs
             FROM program_studi p
             JOIN fakultas f ON p.id_fakultas = f.id_fakultas
             LEFT JOIN mahasiswa m ON p.id_program_studi = m.id_program_studi
             WHERE p.id_program_studi = ?
             GROUP BY p.id_program_studi, p.nama_program_studi, f.nama_fakultas',
            [$id],
        );
    }

    public function total(): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM program_studi');
    }
}
