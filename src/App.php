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

    public static function getTypes()
    {
        $types = [];
        $query = "SELECT DISTINCT type FROM posts
                ORDER BY type";
        $result = Sql::cache($query);
        foreach ($result as $row) {
            $types[] = $row["type"];
        }
        return $types;
    }
}
