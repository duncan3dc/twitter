<?php

namespace duncan3dc\Twitter;

class App
{

    public static function getUnreadCount()
    {
        $query = "SELECT COUNT(*) FROM posts
                WHERE status=1";
        return round(Sql::query($query)->fetch(Sql::FETCH_ROW)[0]);
    }
}
