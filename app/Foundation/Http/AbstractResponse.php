<?php

namespace App\Foundation\Http;

abstract class AbstractResponse
{
    /**
     * Данные для запроса
     */
    private mixed $data = null;

    /**
     * Заголовки ответа
     */
    private array $headers = [];

    /**
     * Статус ответа
     */
    private int $status = 200;

    /**
     * Установить заголовки
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Установить заголовок
     */
    public function setHeader(string $header, string $value): static
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Получить заголовки
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Установить статус запроса
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Получить статус
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Установить данные
     */
    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Получить данные
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Применить заголовки
     */
    protected function applyHeaders(): static
    {
        foreach ($this->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        return $this;
    }

    /**
     * Отправить запрос
     */
    abstract public function send(): void;
}