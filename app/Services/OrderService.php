<?php

namespace App\Services;

use App\DTO\Order;
use App\Repositories\OrderRepository;
use App\Traits\Makeable;

class OrderService
{
    use Makeable;

    /**
     * @var OrderRepository
     */
    private readonly OrderRepository $orderRepository;

    /**
     * OrderService constructor
     */
    public function __construct()
    {
        $this->orderRepository = OrderRepository::make();
    }

    /**
     * Получить список заказов с фильтрацией
     */
    public function all(?bool $isDone = null): false|array
    {
        $orders = $this->orderRepository->all($isDone);

        if (! $orders) {
            return [];
        }

        return array_map(static fn (array $order) => [
            'order_id' => $order['id'],
            'done' => (bool) $order['is_done'],
        ], $orders);
    }

    /**
     * Получить заказ по ID
     */
    public function findById(string $orderId)
    {
        return $this->orderRepository->findById($orderId);
    }

    /**
     * Оформить заказ
     */
    public function order(array $itemIds): Order
    {
        $orderId = $this->orderRepository->store();

        /**
         * Добаляем товары в заказ
         */
        $this->orderRepository->push($orderId, $itemIds);

        return new Order($orderId, $itemIds, false);
    }
}