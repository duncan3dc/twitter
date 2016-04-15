<?php

namespace duncan3dc\Twitter\Controllers;

use duncan3dc\Twitter\App;
use duncan3dc\Twitter\Sql;

class Meta
{

    public function getUserData()
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


    public function getUnreadCount()
    {
        return [
            "status"    =>  1,
            "unread"    =>  App::getUnreadCount(),
            "saved"     =>  App::getSavedCount(),
        ];
    }


    public function getHashtags()
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
