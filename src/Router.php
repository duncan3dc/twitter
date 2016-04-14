<?php

namespace duncan3dc\Twitter;

use duncan3dc\Twitter\Controllers\Index;
use duncan3dc\Twitter\Controllers\Meta;
use duncan3dc\Twitter\Controllers\Posts;
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
        $this->setupRoutes();
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

        $this->routes->setStrategy(new Dispatcher);
    }


    public function setupRoutes()
    {
        $this->addRoute("/", [new Index, "home"]);

        $posts = new Posts;
        $this->addRoute("/get-posts", [$posts, "getPosts"]);
        $this->addRoute("/update-post", [$posts, "updatePost"]);

        $meta = new Meta;
        $this->addRoute("/get-user-data", [$meta, "getUserData"]);
        $this->addRoute("/get-unread-count", [$meta, "getUnreadCount"]);
        $this->addRoute("/get-hashtags", [$meta, "getHashtags"]);
    }


    protected function addRoute($path, callable $controller)
    {
        $this->routes->map(["GET", "POST"], $path, $controller);
    }


    public function dispatch()
    {
        return $this->routes->dispatch($this->container->get("request"), $this->container->get("response"));
    }
}
