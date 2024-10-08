<?php

namespace App\Repositories;

use App\Foundation\Repositories\DBRepository;
use App\Traits\Makeable;
use App\Traits\UniqueId;

class OrderRepository extends DBRepository
{
    use Makeable, UniqueId;

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
    public function store(): string
    {
        $this->insert("INSERT INTO `orders` (`id`) VALUES (?)", [$id = $this->getUniqueId('orders')]);

        return $id;
    }

    /**
     * Добавить товары в заказ
     */
    public function push(string $orderId, array $itemIds): void
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
    public function findById(string $id): mixed
    {
        $order = $this->first("SELECT * FROM `orders` WHERE `id` = ?", [$id]);

        if (! $order) {
            return $order;
        }

        $orderItems = $this->get("SELECT * FROM `order_items` WHERE `order_id` = ?", [$order['id']]);

        return array_merge($order, ['items' => array_column($orderItems, 'item_id')]);
    }

    /**
     * Установить заказу статус: готов
     */
    public function isDone(string $orderId): void
    {
        $this->update("UPDATE `orders` SET `is_done` = 1 WHERE `id` = ?", [$orderId]);
    }
}