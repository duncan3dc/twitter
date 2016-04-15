<?php

namespace duncan3dc\Twitter;

use duncan3dc\Laravel\Blade;
use duncan3dc\Sessions\Session;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class App
{

    public function run()
    {
        Session::name("twitter");

        if (User::isLoggedIn()) {
            $router = new Router;
            $response = $router->dispatch();
        } else {
            $response = new Response;
            $response->getBody()->write(Blade::render("login"));
        }

        $emitter = new SapiEmitter;
        $emitter->emit($response);
    }


    public static function getUnreadCount()
    {
        $query = "SELECT COUNT(*) FROM posts
                WHERE status = 1";
        return round(Sql::query($query)->fetch(Sql::FETCH_ROW)[0]);
    }

    public static function getSavedCount()
    {
        $query = "SELECT COUNT(*) FROM posts
                WHERE status = 2";
        return round(Sql::query($query)->fetch(Sql::FETCH_ROW)[0]);
    }

    public static function getTypes()
    {
        $types = [];
        $query = "SELECT DISTINCT type FROM posts
                ORDER BY type";
        $result = Sql::cache($query);
        foreach ($result as $row) {
            $types[] = $row["type"];
        }
        return $types;
    }
}
