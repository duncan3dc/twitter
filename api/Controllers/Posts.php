<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Helpers\Dict;
use duncan3dc\Twitter\App;
use duncan3dc\Twitter\Factory;
use duncan3dc\Twitter\Sql;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Posts
{

    public function getPosts(ServerRequestInterface $request, ResponseInterface $response)
    {
        $exclude = Dict::post("posts", []);
        $status = round(Dict::post("status", 1));
        $delay = round(Dict::post("delay", 0));
        $types = Dict::post("types", []);

        $query = "SELECT * FROM posts
                WHERE status=?
                    AND type IN ?
                    AND date<?
                ORDER BY date, id
                LIMIT 20";
        $params = [
            $status,
            $types,
            time() - ($delay * 60),
        ];
        $result = Sql::query($query, $params);

        $factory = new Factory;

        $posts = [];
        foreach ($result as $row) {
            if (in_array($row["id"], $exclude)) {
                continue;
            }
            $post = $factory->make($row);
            $posts[] = [
                "id"    =>  $row["id"],
                "html"  =>  $post->render(),
            ];
        }

        return [
            "status"    =>  1,
            "posts"     =>  $posts,
            "unread"    =>  App::getUnreadCount(),
            "saved"     =>  App::getSavedCount(),
        ];
    }


    public function updatePost(ServerRequestInterface $request, ResponseInterface $response)
    {
        Sql::update("posts", [
            "status"    =>  Dict::post("status"),
        ], [
            "id"        =>  Dict::post("post"),
        ]);

        return [
            "status"    =>  1,
            "unread"    =>  App::getUnreadCount(),
            "saved"     =>  App::getSavedCount(),
        ];
    }
}
