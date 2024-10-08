<?php

namespace App\Repositories;

use App\Foundation\Repositories\DBRepository;
use App\Traits\Makeable;

class ItemRepository extends DBRepository
{
    use Makeable;

    /**
     * Получить все товары из БД
     */
    public function all(): false|array
    {
        return $this->get("SELECT * FROM `items`");
    }
}