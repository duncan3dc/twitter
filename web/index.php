<?php

namespace duncan3dc\Twitter;

use duncan3dc\Serial\Json;
use duncan3dc\Twitter\Controllers\Index;
use duncan3dc\Twitter\Controllers\Meta;
use duncan3dc\Twitter\Controllers\Posts;

require(__DIR__ . "/../vendor/autoload.php");

switch ($_SERVER["REQUEST_URI"]) {


    case "/":
        $data = (new Index)->home();
        break;


    case "/get-user-data":
        $data = (new Meta)->getUserData();
        break;


    case "/get-unread-count":
        $data = (new Meta)->getUnreadCount();
        break;


    case "/get-posts":
        $data = (new Posts)->getPosts();
        break;


    case "/update-post":
        $data = (new Posts)->updatePost();
        break;


    case "/get-hashtags":
        $data = (new Meta)->getHashtags();
        break;


    default:
        throw new \Exception("Unknown request");
}


if (is_array($data)) {
        $data = Json::encode($data);
}
echo $data;
