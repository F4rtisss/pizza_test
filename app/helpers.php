<?php

use JetBrains\PhpStorm\NoReturn;

if (! function_exists('env')) {
    /**
     * Получить значение из environment
     */
    function env(string $key, mixed $default = null): mixed
    {
        if (! array_key_exists($key, $_ENV)) {
            return $default;
        }

        return $_ENV[$key];
    }
}

if (! function_exists('config')) {
    /**
     * Получить значение из конфига
     */
    function config(string $key, mixed $default = null): mixed
    {
        return \App\Foundation\Config::get($key, $default);
    }
}

if (! function_exists('response')) {
    /**
     * Сформировать JSON-ответ
     */
    function response(mixed $data = null, int $status = 200): \App\Foundation\Http\ResponseJson
    {
        return (new \App\Foundation\Http\ResponseJson())
            ->setStatus($status)
            ->setData($data);
    }
}

if (! function_exists('dd')) {
    /**
     * @param mixed $value
     * @param ...$values
     * @return void
     */
    #[NoReturn]
    function dd(mixed $value, ...$values): void
    {
        var_dump($value, ...$values);
        exit;
    }
}