<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Laravel\Blade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Index
{

    public function home(ServerRequestInterface $request, ResponseInterface $response)
    {
        return Blade::render("index");
    }
}
