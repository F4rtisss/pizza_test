<?php

namespace App\Validations\Orders;

use App\Foundation\Exceptions\RecordNotFound;
use App\Foundation\Http\Request;
use App\Foundation\Http\Validation;
use App\Repositories\ItemRepository;
use App\Services\OrderService;

class ShowValidation extends Validation
{
    /**
     * @inheritdoc
     */
    public function rules(): void
    {
        /**
         * Валидируем поле: order_id
         */
        $this->required('id');
    }

    /**
     * @inheritdoc
     */
    public function afterValidation(): void
    {
        $order = OrderService::make()->findById($this->id);

        if (! $order) {
            throw new RecordNotFound();
        }

        Request::set('order', $order);
    }
}