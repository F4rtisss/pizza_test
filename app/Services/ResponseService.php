<?php

namespace App\Services;

use App\Traits\Makeable;

class ResponseService
{
    use Makeable;

    /**
     * Запрос - успех
     */
    public function success(?string $message = null, mixed $data = null, int $status = 200): \App\Foundation\Http\ResponseJson
    {
        return $this->build(true, $status, $message, $data);
    }

    /**
     * Запрос - фэйл
     */
    public function error(?string $message = null, mixed $data = null, int $status = 400): \App\Foundation\Http\ResponseJson
    {
        return $this->build(false, $status, $message, $data);
    }

    /**
     * Построить запрос
     */
    public function build(bool $success, int $status, ?string $message = null, mixed $data = null): \App\Foundation\Http\ResponseJson
    {
        return response($data, $status);

//        return response([
//            'success' => $success,
//            'message' => $message,
//            'payload' => $data
//        ], $status);
    }
}