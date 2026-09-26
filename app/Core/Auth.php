<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Mahasiswa;

/**
 * Handles credential verification and the authenticated user's session state.
 */
final class Auth
{
    private const SESSION_KEY = 'auth_npm';

    private static ?array $cachedUser = null;

    private static bool $resolved = false;

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        if (self::$resolved) {
            return self::$cachedUser;
        }

        self::$resolved = true;
        $npm = Session::get(self::SESSION_KEY);

        if (!is_string($npm) || $npm === '') {
            return self::$cachedUser = null;
        }

        return self::$cachedUser = (new Mahasiswa())->findByNpm($npm);
    }

    public static function attempt(string $npm, string $password): bool
    {
        $mahasiswa = (new Mahasiswa())->findByNpm($npm);

        if ($mahasiswa === null || !password_verify($password, (string) $mahasiswa['password'])) {
            return false;
        }

        // Upgrade legacy/weaker hashes transparently on successful login.
        if (password_needs_rehash((string) $mahasiswa['password'], PASSWORD_DEFAULT)) {
            (new Mahasiswa())->updatePassword($npm, $password);
        }

        self::login($npm);

        return true;
    }

    public static function login(string $npm): void
    {
        // Prevent session fixation by rotating the id on privilege change.
        Session::regenerate();
        Session::set(self::SESSION_KEY, $npm);

        self::$cachedUser = null;
        self::$resolved   = false;
    }

    public static function logout(): void
    {
        self::$cachedUser = null;
        self::$resolved   = false;

        Session::destroy();
    }
}
