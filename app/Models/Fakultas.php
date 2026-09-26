<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class Fakultas extends Model
{
    public function total(): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM fakultas');
    }
}
