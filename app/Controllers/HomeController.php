<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Paginator;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;

final class HomeController extends Controller
{
    /** Decorative color/icon palette for the program studi cards. */
    private const CARD_COLORS = ['bg-teal-500', 'bg-blue-600', 'bg-purple-600', 'bg-rose-500', 'bg-orange-500', 'bg-emerald-600', 'bg-indigo-600'];
    private const CARD_ICONS  = ['fa-chart-line', 'fa-laptop-code', 'fa-book-bookmark', 'fa-user-nurse', 'fa-users', 'fa-leaf', 'fa-gear'];

    /** Jumlah baris per halaman pada tabel data mahasiswa. */
    private const PER_PAGE = 10;

    /** Batas panjang kata kunci pencarian. */
    private const MAX_SEARCH_LENGTH = 50;

    public function index(): void
    {
        $mahasiswa    = new Mahasiswa();
        $programStudi = new ProgramStudi();

        $search = $this->searchTerm();

        $paginator = new Paginator(
            $mahasiswa->countWithRelations($search),
            self::PER_PAGE,
            (int) ($_GET['page'] ?? 1),
            $search === '' ? [] : ['q' => $search],
        );

        $this->view('home/index', [
            'pageTitle'      => 'Data Mahasiswa Per Program Studi',
            'totalMahasiswa' => $mahasiswa->total(),
            'totalProdi'     => $programStudi->total(),
            'totalFakultas'  => (new Fakultas())->total(),
            'prodiList'      => $programStudi->allWithStudentCount(),
            'mahasiswaList'  => $mahasiswa->paginateWithRelations($search, $paginator->perPage(), $paginator->offset()),
            'paginator'      => $paginator,
            'search'         => $search,
            'cardColors'     => self::CARD_COLORS,
            'cardIcons'      => self::CARD_ICONS,
        ]);
    }

    /** Ambil kata kunci dari query string dan batasi panjangnya. */
    private function searchTerm(): string
    {
        return mb_substr(trim((string) ($_GET['q'] ?? '')), 0, self::MAX_SEARCH_LENGTH);
    }
}
