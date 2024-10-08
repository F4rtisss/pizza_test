<?php

namespace App\Foundation;

class File
{
    /**
     * Получить список файлов в директории
     *
     * @return array{filename: string, path: string}
     */
    public static function get(string $path): array
    {
        $files = [];

        if (is_dir($path)) {
            if ($handle = opendir($path)) {
                while (false !== ($entry = readdir($handle))) {
                    $fullPath = $path . DIRECTORY_SEPARATOR . $entry;

                    if (is_file($fullPath) && pathinfo($entry, PATHINFO_EXTENSION) === 'php') {
                        $files[] = [
                            'filename' => $entry,
                            'path' => $fullPath
                        ];
                    }
                }

                closedir($handle);
            }
        }

        return $files;
    }

    /**
     * Подключить файл
     */
    public static function require(string $filepath): mixed
    {
        return require_once $filepath;
    }
}