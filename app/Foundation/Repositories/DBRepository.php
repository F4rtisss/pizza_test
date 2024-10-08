<?php

namespace App\Foundation\Repositories;

use App\Foundation\Application;
use App\Foundation\DB;

abstract class DBRepository
{
    /**
     * @var DB
     */
    private DB $DBService;

    /**
     * DBRepository constructor
     */
    public function __construct()
    {
        $this->DBService = Application::create()->make(DB::class);
    }

    /**
     * Получить одну запись
     */
    public function first(string $sql, array $params = null): mixed
    {
        $state = $this->DBService->pdo->prepare($sql);
        $state->execute($params);

        return $state->fetch();
    }

    /**
     * Получить все записи
     */
    public function get(string $sql, array $params = null): array|false
    {
        $state = $this->DBService->pdo->prepare($sql);
        $state->execute($params);

        return $state->fetchAll();
    }

    /**
     * Вставка
     */
    public function insert(string $sql, array $params = []): int
    {
        $state = $this->DBService->pdo->prepare($sql);
        $state->execute($params);

        return (int) $this->DBService->pdo->lastInsertId();
    }

    /**
     * Обновить
     */
    public function update(string $sql, array $params = []): void
    {
        $state = $this->DBService->pdo->prepare($sql);
        $state->execute($params);
    }
}