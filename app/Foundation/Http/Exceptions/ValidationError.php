<?php

namespace App\Foundation\Http\Exceptions;

class ValidationError extends \Exception
{
    /**
     * ValidationError
     */
    public function __construct(public array $errors)
    {
        parent::__construct("Validation errors", 400);
    }
}