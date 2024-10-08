<?php

namespace App\Foundation\Kernel\Interfaces;

interface KernelContract
{
    /**
     * Поймать вызов
     */
    public function handle();
}