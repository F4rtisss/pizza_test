<?php

namespace App\Foundation;

use App\Foundation\Exceptions\AbstractClassNotFoundException;
use Dotenv\Dotenv;

class Application
{
    /**
     * Название приложения
     */
    public const string NAME = 'FortisApplication';

    /**
     * Версия приложения
     */
    public const string VERSION = '1.0.0';

    /**
     * Реализуем singleton
     */
    private static ?Application $application = null;

    /**
     * @var Container
     */
    private Container $container;

    /**
     * Путь до корня приложения
     */
    private string $pathApplication;

    /**
     * Application constructor
     */
    private function __construct(string $path)
    {
        $this->pathApplication = $path;
        $this->container = new Container();

        $this->boot();
    }

    /**
     * @param string $path
     * @return Application
     */
    public static function create(string $path = ''): Application
    {
        if (self::$application === null) {
            self::$application = new Application($path);
        }

        return self::$application;
    }

    /**
     * Инициализация приложения
     */
    private function boot(): void
    {
        /**
         * Инициализация DotEnv
         */
        Dotenv::createImmutable($this->pathApplication)->load();

        /**
         * Регистрация БД
         */
        $this->singleton(DB::class, static fn () => new DB([
            'host' => $_ENV['DB_HOST'],
            'dbname' => $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD']
        ]));
    }

    /**
     * @param string $abstract
     * @param \Closure|null $closure
     * @return Application
     */
    public function singleton(string $abstract, ?\Closure $closure): Application
    {
        $this->container->singleton($abstract, $closure);

        return $this;
    }

    /**
     * @param string $abstract
     * @param \Closure|null $closure
     * @return Application
     */
    public function bind(string $abstract, ?\Closure $closure): Application
    {
        $this->container->bind($abstract, $closure);

        return $this;
    }

    /**
     * @param string $abstract
     * @return mixed
     * @throws AbstractClassNotFoundException
     */
    public function make(string $abstract): mixed
    {
        return $this->container->make($abstract);
    }
}