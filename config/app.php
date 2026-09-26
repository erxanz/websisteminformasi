<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'name'     => Env::get('APP_NAME', 'Universitas Contoh'),
    'env'      => Env::get('APP_ENV', 'production'),
    'debug'    => Env::bool('APP_DEBUG', false),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Jakarta'),
];
