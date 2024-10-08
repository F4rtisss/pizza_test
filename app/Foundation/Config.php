<?php

namespace App\Foundation;

class Config
{
    /**
     * Загруженные файлы
     */
    private static array $config = [];

    /**
     * Загрузить конфиги
     */
    public static function load(string $path): void
    {
        foreach (File::get($path) as $file) {
            $key = static::formatKey($file['filename']);

            if (! array_key_exists($key, self::$config)) {
                static::$config[static::formatKey($file['filename'])] = File::require($file['path']);
            }
        }
    }

    /**
     * Убираем лишнее
     */
    protected static function formatKey(string $key): string
    {
        return str_replace('.php', '', $key);
    }

    /**
     * Установить значение
     */
    public static function set(string $key, mixed $value): void
    {
        static::$config[$key] = $value;
    }

    /**
     * Получить значение из конфига
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::getValueByPath($key) ?? $default;
    }

    /**
     * Получить значение по пути
     */
    private static function getValueByPath(string $path): mixed
    {
        $keys = explode('.', $path);
        $array = static::$config;

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $array = $array[$key];
            } else {
                return null;
            }
        }

        return $array;
    }
}