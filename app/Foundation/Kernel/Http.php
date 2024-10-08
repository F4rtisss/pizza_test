<?php

namespace App\Foundation\Kernel;

use App\Foundation\Application;
use App\Foundation\Exceptions\RecordNotFound;
use App\Foundation\Http\AbstractResponse;
use App\Foundation\Http\Exceptions\ValidationError;
use App\Foundation\Kernel\Exceptions\ControllerMustReturnAbstractResponseException;
use App\Foundation\Kernel\Interfaces\KernelContract;
use App\Foundation\Route;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class Http implements KernelContract
{
    /**
     * @inheritdoc
     */
    public function handle(): void
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $route) {
            foreach (Route::all() as $path => $handler) {
                [$method, $uri] = explode('_', $path);

                $route->addRoute($method, $uri, $handler);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                response([
                    'message' => '404 Not Found',
                    'success' => false
                ], 404)->send();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                response([
                    'message' => 'METHOD_NOT_ALLOWED, ALLOWED METHODS: ' . implode($routeInfo[1]),
                    'success' => false
                ], 405)->send();
                break;
            case Dispatcher::FOUND:
                ['handler' => $handler, 'middleware' => $middleware] = $routeInfo[1];
                $vars = $routeInfo[2];

                /** @var string $concrete */
                foreach ($middleware as $concrete) {
                    $response = $concrete::handle($vars);

                    if ($response instanceof AbstractResponse) {
                        $response->send();
                        return;
                    }
                }

                try {
                    $response = $this->runController($handler, $vars);

                    if (! $response instanceof AbstractResponse) {
                        throw new ControllerMustReturnAbstractResponseException($handler, $response);
                    }

                    $response->send();
                } catch (ValidationError $validation) {
                    response([
                        'success' => false,
                        'message' => 'Validation error, please check your request.',
                        'errors' => $validation->errors
                    ], 405)->send();
                } catch (RecordNotFound) {
                    response([
                        'success' => false,
                        'message' => 'Record not found.',
                    ], 404)->send();
                }

                break;
        }
    }

    /**
     * Запустить метод контроллера
     */
    private function runController(string $handler, array $args = []): mixed
    {
        [$controller, $method] = explode('::', $handler);

        return Application::create()->make($controller)->{$method}($args);
    }
}