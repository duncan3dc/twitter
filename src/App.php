<?php

namespace duncan3dc\Twitter;

use Zend\Diactoros\Response\SapiEmitter;

class App
{

    public function run()
    {
        $router = new Router;

        $response = $router->dispatch();

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
