<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Renders a view inside the shared layout and exposes small request helpers.
 */
final class View
{
    /** @param array<string, mixed> $data */
    public static function render(string $view, array $data = []): void
    {
        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException('View tidak ditemukan: ' . $view);
        }

        // Make request-scoped helpers available to every view.
        $data['currentPath']  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $data['csrfToken']    = Session::csrfToken();
        $data['flashSuccess'] = Session::getFlash('success');
        $data['flashError']   = Session::getFlash('error');

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require BASE_PATH . '/app/Views/layouts/header.php';
        echo $content;
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }
}
