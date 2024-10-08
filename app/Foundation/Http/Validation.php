<?php

namespace App\Foundation\Http;

use App\Foundation\Http\Exceptions\ValidationError;

abstract class Validation
{
    /**
     * Массив с ошибками
     */
    private array $errors = [];

    /**
     * Данные прошедшие проверку
     */
    private array $safety = [];

    /**
     * Validation constructor
     */
    public function __construct(private readonly array $data)
    {
    }

    /**
     * Поле обязательно должно присутствовать в запросе
     */
    protected function required(string $key): static
    {
        if (! array_key_exists($key, $this->data)) {
            $this->setError($key, 'Поле является обязательным.');

            /**
             * Остальная проверка не имеет смысла
             */
            $this->throwValidationError();
        } else {
            $this->safety[$key] = $this->data[$key];
        }

        return $this;
    }

    /**
     * Поле должно быть массивом
     */
    protected function isArray(string $key): static
    {
        if (array_key_exists($key, $this->data) && ! is_array($this->data[$key])) {
            $this->setError($key, 'Поле должно быть массивом.');
        } else {
            $this->safety[$key] = $this->data[$key];
        }

        return $this;
    }

    /**
     * Проверка на минимум
     */
    protected function min(string $key, int $min): static
    {
        if (array_key_exists($key, $this->data)) {
            if (is_array($this->data[$key])) {
                if (count($this->data[$key]) < $min) {
                    $this->setError($key, 'Размер массива должен не меньше: ' . $min);
                    $this->throwValidationError();
                }
            }

            if (is_numeric($this->data[$key])) {
                if ($this->data[$key] < $min) {
                    $this->setError($key, 'Значение должно быть не меньше: ' . $min);
                    $this->throwValidationError();
                }
            }
        }

        $this->safety[$key] = $this->data[$key];

        return $this;
    }

    /**
     * Проверка на максимум
     */
    protected function max(string $key, int $max): static
    {
        if (array_key_exists($key, $this->data)) {
            if (is_array($this->data[$key])) {
                if (count($this->data[$key]) > $max) {
                    $this->setError($key, 'Размер массива должен быть не больше: ' . $max);
                    $this->throwValidationError();
                }
            }

            if (is_numeric($this->data[$key])) {
                if ($this->data[$key] > $max) {
                    $this->setError($key, 'Значение должно быть не больше: ' . $max);
                    $this->throwValidationError();
                }
            }
        }

        $this->safety[$key] = $this->data[$key];

        return $this;
    }

    /**
     * Поле должно быть числом
     */
    protected function isNumeric(string $key): static
    {
        if (array_key_exists($key, $this->data) && ! is_numeric($this->data[$key])) {
            $this->setError($key, 'Поле должны быть числом!');
        } else {
            $value = $this->data[$key];

            if (! str_contains($value, '.')) {
                $value = (int) $value;
            } else {
                $value = (float) $value;
            }

            $this->safety[$key] = $value;
        }

        return $this;
    }

    /**
     * Значение есть в массиве
     */
    public function in(string $key, array $values): static
    {
        if (array_key_exists($key, $this->data) && in_array($this->data[$key], $values)) {
            $this->setError($key, 'Поле должны быть числом!');
        } else {
            $this->safety[$key] = $this->data[$key];
        }

        return $this;
    }

    /**
     * Установить ошибку
     */
    public function setError(string $key, string $message): static
    {
        $this->errors[$key][] = $message;

        return $this;
    }

    /**
     * Есть ли ошибки в запросе
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Получить ошибки
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Опроеделеяем правила
     */
    abstract public function rules(): void;

    /**
     * Выполнить дополнительную проверку после валидации
     */
    abstract public function afterValidation(): void;

    /**
     * Вызывать ошибку
     */
    private function throwValidationError(): void
    {
        throw new ValidationError($this->errors);
    }

    /**
     * Выполнить прооверку
     */
    public function validate(): array
    {
        $this->rules();

        if ($this->hasErrors()) {
            $this->throwValidationError();
        }

        $this->afterValidation();

        if ($this->hasErrors()) {
            $this->throwValidationError();
        }

        return $this->safety;
    }

    /**
     * Статический вызов метода
     */
    public static function make(array $data = []): array
    {
        return (new static(array_merge(Request::all(), $data)))->validate();
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        $this->safety[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->safety[$name] ?? null;
    }
}