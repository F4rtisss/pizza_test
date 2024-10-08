<?php

namespace App\Foundation;

use FastRoute\RouteCollector;

class Route
{
    /**
     * Маршруты
     */
    private static array $routes = [];

    /**
     * Получить маршруты
     */
    public static function all(): array
    {
        return static::$routes;
    }

    /**
     * POST-Запрос
     */
    public static function post(string $path, string $class, string $method, array $middleware = []): void
    {
        static::addRoute('POST_', $path, $class, $method, $middleware);
    }

    /**
     * GET-Запрос
     */
    public static function get(string $path, string $class, string $method, array $middleware = []): void
    {
        static::addRoute('GET_', $path, $class, $method, $middleware);
    }

    /**
     * Добавить маршрут
     */
    private static function addRoute(string $method, string $path, string $class, string $classMethod, array $middleware = []): void
    {
        static::$routes[$method . $path] = [
            'handler' => sprintf('%s::%s', $class, $classMethod),
            'middleware' => $middleware,
        ];
    }
}