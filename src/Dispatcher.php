<?php

namespace duncan3dc\Twitter;

use duncan3dc\Serial\Json;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Route;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCallable(Route $route, array $vars)
    {
        return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($route, $vars) {
            $data = call_user_func_array($route->getCallable(), [$request, $response, $vars]);

            if ($data instanceof ResponseInterface) {
                $response = $data;
            } else {
                if (is_array($data)) {
                    $data = Json::encode($data);
                }
                $response->getBody()->write($data);
            }

            return $next($request, $response);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFoundDecorator(NotFoundException $exception)
    {
        throw $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception)
    {
        throw $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionDecorator(\Exception $exception)
    {
        throw $exception;
    }
}
