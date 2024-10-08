<?php

namespace App\Foundation;

use App\Foundation\Exceptions\AbstractClassNotFoundException;
use App\Foundation\Kernel\Http;
use App\Foundation\Kernel\Interfaces\KernelContract;
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
        Dotenv::createImmutable($this->getPathTo())->load();

        /**
         * Инициализация конфига
         */
        Config::load($this->getPathTo('/config'));

        /**
         * Регистрация БД
         */
        $this->singleton(DB::class, static fn () => new DB([
            'host' => config('database.host'),
            'dbname' => config('database.db'),
            'user' => config('database.username'),
            'password' => config('database.password')
        ]));
    }

    /**
     * Получить путь до папки с корня проекта
     */
    public function getPathTo(string $dir = ''): string
    {
        return $this->pathApplication . $dir;
    }

    /**
     * Поймать http-запрос
     */
    public function http(): void
    {
        foreach (File::get($this->getPathTo('/routes')) as $file) {
            File::require($file['path']);
        }

        /** @var KernelContract $handler */
        $handler = Application::make(Http::class);

        $handler->handle();
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