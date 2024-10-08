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
    public static function post(string $path, string $class, string $method): void
    {
        static::addRoute('POST_', $path, $class, $method);
    }

    /**
     * GET-Запрос
     */
    public static function get(string $path, string $class, string $method): void
    {
        static::addRoute('GET_', $path, $class, $method);
    }

    /**
     * Добавить маршрут
     */
    private static function addRoute(string $method, string $path, string $class, string $classMethod): void
    {
        static::$routes[$method . $path] = sprintf('%s::%s', $class, $classMethod);
    }
}