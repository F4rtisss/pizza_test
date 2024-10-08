<?php

// Подключаем автозагрузчик
require '../vendor/autoload.php';

// Создаём приложение
$app = \App\Foundation\Application::create(dirname(__DIR__))->http();

