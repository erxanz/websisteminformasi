<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Hardened session wrapper: secure cookie flags, CSRF tokens and flash messages.
 */
final class Session
{
    private const FLASH_KEY = '_flash';
    private const CSRF_KEY  = '_csrf_token';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => self::isHttps(),
            'samesite' => 'Lax',
        ]);

        session_name('UNIVSESSID');
        session_start();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /** @param mixed $value */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /** @return mixed */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }

    private static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }

    // --- Flash messages -------------------------------------------------

    /** @param mixed $value */
    public static function flash(string $key, $value): void
    {
        $_SESSION[self::FLASH_KEY][$key] = $value;
    }

    /** @return mixed */
    public static function getFlash(string $key, $default = null)
    {
        if (!isset($_SESSION[self::FLASH_KEY][$key])) {
            return $default;
        }

        $value = $_SESSION[self::FLASH_KEY][$key];
        unset($_SESSION[self::FLASH_KEY][$key]);

        return $value;
    }

    // --- CSRF -----------------------------------------------------------

    public static function csrfToken(): string
    {
        if (empty($_SESSION[self::CSRF_KEY])) {
            $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF_KEY];
    }

    public static function verifyCsrf(?string $token): bool
    {
        $stored = $_SESSION[self::CSRF_KEY] ?? null;

        return is_string($token) && is_string($stored) && hash_equals($stored, $token);
    }
}
