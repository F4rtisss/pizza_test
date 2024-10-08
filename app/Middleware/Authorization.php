<?php

namespace App\Middleware;

use App\Foundation\Http\Request;

class Authorization
{
    /**
     * Ключ, который будет проверяться
     */
    private const string AUTH_HEADER = 'X-Auth-Key';

    /**
     * Обрабатываем приходящий запрос
     */
    public static function handle(array $vars)
    {
        if (! Request::hasHeader(static::AUTH_HEADER)) {
            return static::getFailedRequest();
        }

        if (Request::header(static::AUTH_HEADER) !== config('auth.api_key')) {
            return static::getFailedRequest();
        }
    }

    /**
     * Получить запрос с ошибкой
     */
    private static function getFailedRequest(): \App\Foundation\Http\ResponseJson
    {
        return response([
            'success' => false,
            'message' => 'Ошибка авторизации'
        ], 401);
    }
}