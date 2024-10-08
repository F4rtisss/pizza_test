<?php

namespace App\Traits;

use App\Foundation\Application;

trait Makeable
{
    /**
     * Создать объект
     */
    public static function make(): static
    {
        return Application::create()->make(static::class);
    }
}