<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Twitter\App;
use duncan3dc\Twitter\Sql;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Meta
{

    public function getUserData(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = [];

        $result = Sql::selectAll("status", [
            "type"      =>  "twitter",
        ]);
        foreach ($result as $row) {
            $data[$row["key"]] = $row["value"];
        }

        return [
            "status"    =>  1,
            "userdata"  =>  $data,
        ];
    }


    public function getUnreadCount(ServerRequestInterface $request, ResponseInterface $response)
    {
        return [
            "status"    =>  1,
            "unread"    =>  App::getUnreadCount(),
            "saved"     =>  App::getSavedCount(),
        ];
    }


    public function getHashtags(ServerRequestInterface $request, ResponseInterface $response)
    {
        $hashtags = [];

        $query = "SELECT hashtag FROM hashtags
                GROUP BY hashtag
                ORDER BY COUNT(*) DESC, MAX(date) DESC
                LIMIT 10";
        $result = Sql::query($query);
        foreach ($result as $row) {
            $hashtags[] = $row["hashtag"];
        }

        return [
            "status"    =>  1,
            "hashtags"  =>  $hashtags,
        ];
    }
}
