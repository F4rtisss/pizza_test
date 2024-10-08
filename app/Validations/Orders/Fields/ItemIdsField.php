<?php

namespace App\Validations\Orders\Fields;

use App\Foundation\Http\Validation;
use App\Repositories\ItemRepository;

class ItemIdsField
{
    /**
     * Валидация поля
     */
    public static function validate(Validation $validation): void
    {
        /**
         * Дополнительная провекрка, что такое товар есть в БД
         */
        $itemIds = array_column(ItemRepository::make()->all(), 'id');

        /** @var int $itemId */
        foreach ($validation->items as $index => $itemId) {
            if (! is_int($itemId)) {
                $validation->setError('items.' . $index, 'Массив должен состоять из чисел');
                continue;
            }

            if (! in_array($itemId, $itemIds)) {
                $validation->setError('items.' . $itemId, 'Такого товара нет');
            }
        }
    }
}