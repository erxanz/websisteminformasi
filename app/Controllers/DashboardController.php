<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Mahasiswa;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $sessionUser = $this->requireAuth();

        $mahasiswa = (new Mahasiswa())->findWithRelations((string) $sessionUser['npm']);

        // Session points to a record that no longer exists.
        if ($mahasiswa === null) {
            Auth::logout();
            $this->redirect('/login');
        }

        // Keep npm available even though the relation query does not select it twice.
        $mahasiswa['npm'] = $sessionUser['npm'];

        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard Mahasiswa',
            'mhs'       => $mahasiswa,
        ]);
    }
}
