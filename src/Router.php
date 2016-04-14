<?php

namespace duncan3dc\Twitter;

use League\Container\Container;
use League\Route\RouteCollection;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class Router
{
    private $container;
    private $routes;


    public function __construct()
    {
        $this->setupContainer();
        $this->setupRouter();
    }


    private function setupContainer()
    {
        $this->container = new Container;

        $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $this->container->share("request", $request);

        $this->container->share("response", Response::class);
    }


    public function setupRouter()
    {
        $this->routes = new RouteCollection($this->container);
    }


    public function dispatch()
    {
        return $this->routes->dispatch($this->container->get("request"), $this->container->get("response"));
    }
}
