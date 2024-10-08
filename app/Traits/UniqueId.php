<?php

namespace App\Traits;

use App\Helpers\Str;

trait UniqueId
{
    /**
     * Кол-во попыток создать уникальный ID
     */
    protected int $uniqueAttempts = 5;

    /**
     * Получить уникальный идентификатор
     */
    protected function getUniqueId(string $table, string $column = 'id'): string
    {
        for ($i = 0; $i < $this->uniqueAttempts; $i++) {
            $uniqueId = Str::random(15);

            $order = $this->first("SELECT * FROM `$table` WHERE $column = :column", ['column' => $uniqueId]);

            if (! $order) {
                return $uniqueId;
            }
        }

        throw new \Exception('Не удалось создать уникальный идентификатор');
    }
}