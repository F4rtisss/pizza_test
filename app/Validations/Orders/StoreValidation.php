<?php

namespace App\Validations\Orders;

use App\Foundation\Http\Request;
use App\Foundation\Http\Validation;
use App\Repositories\ItemRepository;
use App\Validations\Orders\Fields\ItemIdsField;

class StoreValidation extends Validation
{
    /**
     * @inheritdoc
     */
    public function rules(): void
    {
        /**
         * Валидируем поле: items
         */
        $this->required('items')
            ->isArray('items')
            ->min('items', 1)
            ->max('items', 5000);
    }

    /**
     * @inheritdoc
     */
    public function afterValidation(): void
    {
        ItemIdsField::validate($this);
    }
}