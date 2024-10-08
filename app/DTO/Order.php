<?php

namespace App\DTO;

readonly class Order
{
    /**
     * Order constructor
     */
    public function __construct(
        public string   $id,
        public array    $items,
        public bool     $is_done
    )
    {
    }
}