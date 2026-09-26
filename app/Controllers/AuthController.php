<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\LoginAttempt;
use App\Models\Mahasiswa;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', [
            'pageTitle' => 'Login Mahasiswa',
            'errors'    => Session::getFlash('errors', []),
            'oldNpm'    => Session::getFlash('old_npm', ''),
        ]);
    }

    public function login(): void
    {
        if (!Session::verifyCsrf($_POST['_token'] ?? null)) {
            Session::flash('errors', ['form' => 'Sesi Anda telah berakhir. Silakan coba lagi.']);
            $this->redirect('/login');
        }

        $npm      = trim((string) ($_POST['npm'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $validator = Validator::make(
            ['npm' => $npm, 'password' => $password],
            ['npm' => 'required|numeric|max:20', 'password' => 'required|max:72'],
            ['npm' => 'NPM', 'password' => 'Password'],
        );

        if ($validator->fails()) {
            Session::flash('errors', $validator->errors());
            Session::flash('old_npm', $npm);
            $this->redirect('/login');
        }

        $throttle = new LoginAttempt();
        $ip       = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Batasi brute force sebelum kredensial diperiksa sama sekali.
        if ($throttle->isBlocked($npm, $ip)) {
            Session::flash('errors', [
                'login' => sprintf(
                    'Terlalu banyak percobaan login. Silakan coba lagi dalam %d menit.',
                    $throttle->blockedForMinutes($npm, $ip),
                ),
            ]);
            Session::flash('old_npm', $npm);
            $this->redirect('/login');
        }

        if (!Auth::attempt($npm, $password)) {
            $throttle->recordFailure($npm, $ip);

            // Keep the message generic so it cannot be used to enumerate accounts.
            Session::flash('errors', ['login' => 'NPM atau Password yang Anda masukkan salah!']);
            Session::flash('old_npm', $npm);
            $this->redirect('/login');
        }

        $throttle->clearFailures($npm);

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        if (!Session::verifyCsrf($_POST['_token'] ?? null)) {
            $this->redirect('/dashboard');
        }

        Auth::logout();
        $this->redirect('/login');
    }

    public function showChangePassword(): void
    {
        $this->requireAuth();

        $this->view('auth/change-password', [
            'pageTitle' => 'Ganti Password',
            'errors'    => Session::getFlash('errors', []),
        ]);
    }

    public function changePassword(): void
    {
        $user = $this->requireAuth();

        if (!Session::verifyCsrf($_POST['_token'] ?? null)) {
            Session::flash('errors', ['form' => 'Sesi Anda telah berakhir. Silakan coba lagi.']);
            $this->redirect('/ubah-password');
        }

        $current = (string) ($_POST['current_password'] ?? '');
        $new     = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['new_password_confirmation'] ?? '');

        $validator = Validator::make(
            ['current_password' => $current, 'new_password' => $new, 'confirmation' => $confirm],
            ['current_password' => 'required', 'new_password' => 'required|min:8|max:72', 'confirmation' => 'required'],
            ['current_password' => 'Password saat ini', 'new_password' => 'Password baru', 'confirmation' => 'Konfirmasi password baru'],
        );

        $errors = $validator->errors();

        if ($confirm !== $new) {
            $errors['confirmation'] = 'Konfirmasi password baru tidak cocok.';
        }

        $mahasiswa = (new Mahasiswa())->findByNpm((string) $user['npm']);

        if ($mahasiswa === null || !password_verify($current, (string) $mahasiswa['password'])) {
            $errors['current_password'] = 'Password saat ini salah.';
        } elseif ($new === $current) {
            $errors['new_password'] = 'Password baru harus berbeda dari password saat ini.';
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            $this->redirect('/ubah-password');
        }

        (new Mahasiswa())->updatePassword((string) $user['npm'], $new);

        // Rotate the session id so older sessions cannot keep the old credentials.
        Session::regenerate();

        Session::flash('success', 'Password berhasil diperbarui.');
        $this->redirect('/dashboard');
    }
}
