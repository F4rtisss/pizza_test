<?php

namespace App\Controllers\API\V1;

use App\Controllers\Controller;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    private readonly OrderRepository $orderRepository;

    /**
     * OrderController constructor
     */
    public function __construct()
    {
        $this->orderRepository = OrderRepository::make();
    }

    /**
     * Создание заказа
     */
    public function store(): \App\Foundation\Http\ResponseJson
    {
        //
    }

    /**
     * Добавить товар в заказ
     */
    public function push(): \App\Foundation\Http\ResponseJson
    {
        //
    }

    /**
     * Получить информацию по заказу
     */
    public function show(array $params): \App\Foundation\Http\ResponseJson
    {
        //
    }

    /**
     * Установить статус - заказ готов
     */
    public function isDone(array $params): \App\Foundation\Http\ResponseJson
    {
        //
    }

    /**
     * Получить список заказов
     */
    public function all(): \App\Foundation\Http\ResponseJson
    {
        //
    }
}