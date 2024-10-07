<?php

namespace App\Foundation;

use PDO;

final readonly class DB
{
    /**
     * Подключение к БД
     */
    public PDO $pdo;

    /**
     * DBService constructor
     */
    public function __construct($dbConfig)
    {
        $dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'] . ";charset=utf8";

        try {
            $this->pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ]);
        } catch (\PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
}