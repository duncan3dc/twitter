<?php

namespace duncan3dc\Twitter;

use duncan3dc\Helpers\Dict;
use duncan3dc\Helpers\Helper;
use duncan3dc\Helpers\Json;

require(__DIR__ . "/../vendor/autoload.php");

switch ($_GET["action"]) {


    case "getUserData":
        $data = [];

        $result = Sql::selectAll("status", [
            "type"      =>  "twitter",
        ]);
        foreach ($result as $row) {
            $data[$row["key"]] = $row["value"];
        }

        echo Json::encode([
            "status"    =>  1,
            "userdata"  =>  $data,
        ]);
        break;


    case "getUnreadCount":
        echo Json::encode([
            "status"    =>  1,
            "unread"    =>  App::getUnreadCount(),
        ]);
        break;


    case "getPosts":
        $exclude = Dict::post("posts", []);
        $status = round(Dict::post("status", 0));

        $query = "SELECT * FROM posts
                WHERE status=?
                ORDER BY date, id
                LIMIT 20";
        $params = [
            $status,
        ];
        $result = Sql::query($query, $params);

        $posts = [];
        foreach ($result as $row) {
            if (in_array($row["id"], $exclude)) {
                continue;
            }
            $post = new Post($row);
            $posts[] = [
                "id"    =>  $row["id"],
                "html"  =>  $post->html(),
            ];
        }

        echo Json::encode([
            "status"    =>  1,
            "posts"     =>  $posts,
            "unread"    =>  App::getUnreadCount(),
        ]);
        break;


    case "updatePost":
        Sql::update("posts", [
            "status"    =>  $_POST["status"],
        ], [
            "id"        =>  $_POST["post"],
        ]);

        echo Json::encode([
            "status"    =>  1,
            "unread"    =>  App::getUnreadCount(),
        ]);
        break;
}
