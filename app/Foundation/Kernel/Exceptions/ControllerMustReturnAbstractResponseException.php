<?php

namespace App\Foundation\Kernel\Exceptions;

use App\Foundation\Http\AbstractResponse;

class ControllerMustReturnAbstractResponseException extends \Exception
{
    /**
     * ControllerMustReturnAbstractResponseException constructor
     */
    public function __construct(string $controller, mixed $returned = null)
    {
        parent::__construct(sprintf(
            '%s ожидалось: %s, вернул: %s',
            $controller,
            AbstractResponse::class,
            gettype($returned)
        ));
    }
}