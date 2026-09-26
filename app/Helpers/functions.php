<?php

declare(strict_types=1);

// Polyfills keep the codebase usable on PHP 7.4 shared hosting.
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

/**
 * Append a line to the application log file.
 */
function log_message(string $level, string $message): void
{
    $directory = BASE_PATH . '/storage/logs';

    if (!is_dir($directory)) {
        @mkdir($directory, 0775, true);
    }

    $line = sprintf("[%s] %s: %s%s", date('Y-m-d H:i:s'), $level, $message, PHP_EOL);

    @file_put_contents($directory . '/app.log', $line, FILE_APPEND | LOCK_EX);
}

/**
 * Escape a value for safe HTML output (XSS protection).
 */
function esc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build an application-relative URL.
 */
function url(string $path = ''): string
{
    return '/' . ltrim($path, '/');
}

/**
 * Format an ISO date as "15 April 2002".
 */
function format_date(?string $date, string $format = 'd F Y'): string
{
    if ($date === null || $date === '' || $date === '0000-00-00') {
        return '-';
    }

    $timestamp = strtotime($date);

    return $timestamp === false ? '-' : date($format, $timestamp);
}
