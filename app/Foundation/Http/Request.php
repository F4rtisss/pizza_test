<?php

namespace App\Foundation\Http;

class Request
{
    /**
     * @var array
     */
    private static array $bag = [];

    /**
     * @param string $key
     * @param $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::$bag[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        return self::$bag[$key] ?? null;
    }

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
     * Есть ли заголовок в запросе
     */
    public static function hasHeader(string $key): bool
    {
        return array_key_exists($key, getallheaders());
    }

    /**
     * Получить значение из заголовка
     */
    public static function header(string $key, mixed $default = null): mixed
    {
        $headers = getallheaders();

        return $headers[$key] ?? $default;
    }

    /**
     * Получить все данные из запроса
     */
    public static function all(): array
    {
        return array_merge($_POST, json_decode(file_get_contents('php://input'), true));
    }
}