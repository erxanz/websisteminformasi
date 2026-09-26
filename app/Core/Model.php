<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Thin base class for models: exposes the shared PDO connection.
 */
abstract class Model
{
    protected \PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }
}
