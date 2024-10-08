<?php

namespace App\Foundation\Exceptions;

use Throwable;

class RecordNotFound extends \Exception
{
    /**
     * RecordNotFound constructor
     */
    public function __construct()
    {
        parent::__construct("Страница не найдена");
    }
}