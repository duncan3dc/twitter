<?php

namespace duncan3dc\Twitter;

require __DIR__ . "/../vendor/autoload.php";

header("Access-Control-Allow-Origin: *");

(new App)
    ->run();
