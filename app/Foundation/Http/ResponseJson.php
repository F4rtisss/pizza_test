<?php

namespace App\Foundation\Http;

class ResponseJson extends AbstractResponse
{
    /**
     * ResponseJson
     */
    public function __construct()
    {
        $this->setHeader('Content-Type', 'application/json');
    }

    /**
     * Отправить
     */
    public function send(): void
    {
        $this->applyHeaders();

        echo json_encode($this->getData());
    }
}