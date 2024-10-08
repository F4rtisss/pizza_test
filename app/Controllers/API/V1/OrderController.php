<?php

namespace App\Controllers\API\V1;

use App\Controllers\Controller;
use App\Foundation\Exceptions\RecordNotFound;
use App\Foundation\Http\Exceptions\ValidationError;
use App\Foundation\Http\Request;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use App\Validations\Orders\IsDoneValidation;
use App\Validations\Orders\PushValidation;
use App\Validations\Orders\ShowValidation;
use App\Validations\Orders\StoreValidation;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private readonly OrderService $orderService;

    /**
     * @var OrderRepository
     */
    private readonly OrderRepository $orderRepository;

    /**
     * OrderController constructor
     */
    public function __construct()
    {
        $this->orderService = OrderService::make();
        $this->orderRepository = OrderRepository::make();
    }

    /**
     * Получить список заказов
     */
    public function all(): \App\Foundation\Http\ResponseJson
    {
        return $this->response()->success(
            data: $this->orderService->all(Request::query('done'))
        );
    }

    /**
     * Получить информацию по заказу
     */
    public function show(array $params): \App\Foundation\Http\ResponseJson
    {
        ShowValidation::make($params);

        $order = Request::get('order');

        return $this->response()->success(
            data: [
                'order_id' => $order['id'],
                'items' => $order['items'],
                'done' => (bool) $order['is_done']
            ]
        );
    }

    /**
     * Создание заказа
     */
    public function store(): \App\Foundation\Http\ResponseJson
    {
        $validated = StoreValidation::make();

        $order = $this->orderService->order($validated['items']);

        return $this->response()->success(
            'Заказ создан',
            [
                'order_id' => $order->id,
                'items' => $order->items,
                'done' => $order->is_done
            ]
        );
    }

    /**
     * Добавить товар в заказ
     */
    public function push(array $params): \App\Foundation\Http\ResponseJson
    {
        $validated = PushValidation::make($params);
        $order = Request::get('order');

        if ($order['is_done']) {
            return $this->response()->error('Нельзя добавлять товары в завершённый заказ');
        }

        $this->orderRepository->push($order['id'], $validated['items']);

        return $this->response()->success('Успех!');
    }

    /**
     * Установить статус - заказ готов
     */
    public function isDone(array $params): \App\Foundation\Http\ResponseJson
    {
        IsDoneValidation::make($params);
        $order = Request::get('order');

        if ($order['is_done']) {
            return $this->response()->error('Нельзя добавлять товары в завершённый заказ');
        }

        return $this->response()->success('Успех');
    }
}