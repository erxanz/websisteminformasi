<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Base controller with rendering and redirect helpers.
 */
abstract class Controller
{
    /** @param array<string, mixed> $data */
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    /** Stop the request with a 404 page. */
    protected function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404');
        exit;
    }

    /**
     * Redirect guests to the login page, otherwise return the authenticated user.
     *
     * @return array<string, mixed>
     */
    protected function requireAuth(): array
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirect('/login');
        }

        return $user;
    }
}
