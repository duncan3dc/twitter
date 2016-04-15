<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Laravel\Blade;

class Index
{

    public function home()
    {
        return Blade::render("index");
    }
}
