<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Helpers\Session;
use duncan3dc\Laravel\Blade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Index
{

    public function home(ServerRequestInterface $request, ResponseInterface $response)
    {
        return Blade::render("index");
    }


    public function logout(ServerRequestInterface $request, ResponseInterface $response)
    {
        Session::destroy();
        return Blade::render("login");
    }
}
