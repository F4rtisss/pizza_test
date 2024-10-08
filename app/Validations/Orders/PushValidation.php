<?php

namespace App\Validations\Orders;

use App\Foundation\Http\Request;
use App\Validations\Orders\Fields\ItemIdsField;

class PushValidation extends ShowValidation
{
    /**
     * @inheritdoc
     */
    public function rules(): void
    {
        parent::rules();

        /**
         * Валидируем поле items
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
        parent::afterValidation();

        if (Request::get('order')) {
            ItemIdsField::validate($this);
        }
    }
}