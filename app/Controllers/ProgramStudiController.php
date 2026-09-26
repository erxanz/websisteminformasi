<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;

final class ProgramStudiController extends Controller
{
    public function show(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->notFound();
        }

        $programStudi = (new ProgramStudi())->find((int) $id);

        if ($programStudi === null) {
            $this->notFound();
        }

        $this->view('prodi/show', [
            'pageTitle'     => (string) $programStudi['nama_program_studi'],
            'programStudi'  => $programStudi,
            'mahasiswaList' => (new Mahasiswa())->byProgramStudi((int) $id),
        ]);
    }
}
