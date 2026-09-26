<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * PDO wrapper. A single connection is reused for the whole request.
 */
final class Database
{
    private static ?PDO $connection = null;

    /** @var array<string, mixed> */
    private static array $config = [];

    private function __construct()
    {
    }

    /** @param array<string, mixed> $config */
    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        if (self::$config === []) {
            throw new RuntimeException('Konfigurasi database belum dimuat.');
        }

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            self::$config['driver'],
            self::$config['host'],
            self::$config['port'],
            self::$config['database'],
            self::$config['charset'],
        );

        try {
            self::$connection = new PDO(
                $dsn,
                (string) self::$config['username'],
                (string) self::$config['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Use real prepared statements (not client-side emulation).
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ],
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Koneksi database gagal.', 0, $e);
        }

        return self::$connection;
    }

    /** Run a prepared statement and return the statement. */
    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $statement = self::connection()->prepare($sql);

        // Binding tipe secara eksplisit penting untuk placeholder LIMIT/OFFSET,
        // yang akan ditolak MySQL bila dikirim sebagai string.
        foreach (array_values($params) as $index => $value) {
            $statement->bindValue($index + 1, $value, self::paramType($value));
        }

        $statement->execute();

        return $statement;
    }

    private static function paramType($value): int
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        }

        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }

        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    public static function select(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    public static function selectOne(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    /** @return mixed */
    public static function scalar(string $sql, array $params = [])
    {
        $value = self::run($sql, $params)->fetchColumn();

        return $value === false ? null : $value;
    }
}
