<?php

namespace duncan3dc\Twitter;

use duncan3dc\Helpers\Env;
use duncan3dc\Laravel\Blade;
use duncan3dc\Sessions\Session;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class App
{

    public function run()
    {
        if (Env::getVar("show-errors")) {
            ob_start();
            $whoops = new \Whoops\Run;

            $htmlHandler = new \Whoops\Handler\PrettyPageHandler;
            $htmlHandler->setEditor("sublime");
            $whoops->pushHandler($htmlHandler);

            if (\Whoops\Util\Misc::isAjaxRequest()) {
                $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
            }

            $whoops->register();
        }

        try {
            $this->bootstrap();
            $this->handleRequest();
        } catch (\Throwable $e) {
            if (Env::getVar("show-errors")) {
                throw $e;
            }

            $error = $e->getMessage();
            try {
                echo Blade::render("error", [
                    "error" =>  $error,
                ]);
            } catch (\Throwable $e) {
                echo $error;
            }
        }
    }


    private function bootstrap()
    {
        Session::name("twitter");
    }


    private function handleRequest()
    {
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
