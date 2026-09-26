<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Maps HTTP verb + path to a controller action. Supports {param} placeholders.
 */
final class Router
{
    /** @var array<string, array<string, array{handler: array{class-string, string}, pattern: string}>> */
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    /** @param array{class-string, string} $handler */
    private function add(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'pattern' => $this->toPattern($path),
        ];
    }

    /** Convert "/mahasiswa/{npm}" into a regex. */
    private function toPattern(string $path): string
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+}#', '([^/]+)', $path);

        return '#^' . rtrim((string) $pattern, '/') . '/?$#';
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches) !== 1) {
                continue;
            }

            array_shift($matches);
            [$class, $action] = $route['handler'];

            (new $class())->{$action}(...$matches);

            return;
        }

        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404');
    }
}
