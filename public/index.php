<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\Env;
use App\Core\Router;
use App\Core\Session;
use App\Core\View;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Helpers/functions.php';

// PSR-4 style autoloader for the App\ namespace (no Composer required).
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file     = BASE_PATH . '/app/' . $relative . '.php';

    if (is_file($file)) {
        require $file;
    }
});

$config = ['debug' => false];

try {
    Env::load(BASE_PATH . '/.env');

    $appConfig = require BASE_PATH . '/config/app.php';
    $config    = $appConfig;

    date_default_timezone_set($appConfig['timezone']);
    error_reporting(E_ALL);
    ini_set('display_errors', $appConfig['debug'] ? '1' : '0');

    Database::configure(require BASE_PATH . '/config/database.php');
    Session::start();

    $router = new Router();
    require BASE_PATH . '/routes/web.php';

    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $e) {
    log_message('ERROR', sprintf('%s: %s in %s:%d', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));

    http_response_code(500);

    if (!empty($config['debug'])) {
        echo '<pre>' . esc($e->getMessage()) . "\n" . esc($e->getTraceAsString()) . '</pre>';
    } else {
        try {
            View::render('errors/500');
        } catch (Throwable $renderError) {
            log_message('ERROR', 'Gagal render halaman error: ' . $renderError->getMessage());
            echo 'Terjadi kesalahan pada server.';
        }
    }
}
