<?php

namespace App\Foundation\Http;

class Request
{
    /**
     * Получить GET-Параметр
     */
    public static function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Получить значение из POST
     */
    public static function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Получить значение из заголовка
     */
    public static function header(string $key, mixed $default = null): mixed
    {
        $headers = getallheaders();

        return $headers[$key] ?? $default;
    }
}