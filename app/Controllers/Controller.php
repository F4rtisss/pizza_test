<?php

namespace App\Controllers;

use App\Services\ResponseService;

/**
 * Базовый контроллер - от него наследуемся дальше
 */
class Controller
{
    /**
     * @return ResponseService
     */
    protected function response(): ResponseService
    {
        return new ResponseService();
    }
}