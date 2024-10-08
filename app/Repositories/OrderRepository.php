<?php

namespace App\Repositories;

use App\Foundation\Repositories\DBRepository;
use App\Traits\Makeable;

class OrderRepository extends DBRepository
{
    use Makeable;

    /**
     * Получить данные с пагинацией
     */
    public function all(?bool $isDone = null): array|false
    {
        $sql = "SELECT `orders`.`id`, `orders`.`is_done` FROM `orders`";

        $params = [];
        if (! is_null($isDone)) {
            $sql .= " WHERE `orders`.`is_done` = ?";
            $params[] = $isDone;
        }

        return $this->get($sql, $params);
    }

    /**
     * Создать заказ
     */
    public function store(): int
    {
        return $this->insert("INSERT INTO `orders` (`is_done`) VALUES (?)", [0]);
    }

    /**
     * Добавить товары в заказ
     */
    public function push(int $orderId, array $itemIds): void
    {
        $placeholders = implode(',', array_fill(0, count($itemIds), '(?, ?)'));

        $params = [];
        /** @var int $itemId */
        foreach ($itemIds as $itemId) {
            $params[] = $orderId;
            $params[] = $itemId;
        }

        $this->insert("INSERT INTO `order_items` (`order_id`, `item_id`) VALUES $placeholders", $params);
    }

    /**
     * Найти заказ по ID
     */
    public function findById(int $id): mixed
    {
        $order = $this->first("SELECT * FROM `orders` WHERE `id` = ?", [$id]);

        if (! $order) {
            return $order;
        }

        $orderItems = $this->get("SELECT * FROM `order_items` WHERE `order_id` = ?", [$order['id']]);

        return array_merge($order, ['items' => array_column($orderItems, 'item_id')]);
    }
}