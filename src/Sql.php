<?php

namespace duncan3dc\Twitter;

use duncan3dc\Helpers\Env;

class Sql
{
    const FETCH_ROW = \duncan3dc\SqlClass\Sql::FETCH_ROW;

    protected static $sql;


    public static function init()
    {
        if (!static::$sql) {
            static::$sql = new \duncan3dc\SqlClass\Sql([
                "hostname"  =>  Env::requireVar("hostname"),
                "username"  =>  Env::requireVar("username"),
                "password"  =>  Env::requireVar("password"),
                "database"  =>  "twitter",
            ]);
        }

        return static::$sql;
    }


    public static function query($query, array $params = null)
    {
        return static::init()->query($query, $params);
    }


    public static function select($table, array $where)
    {
        return static::init()->select($table, $where);
    }


    public static function selectAll($table, array $where)
    {
        return static::init()->selectAll($table, $where);
    }


    public static function update($table, array $set, array $where)
    {
        return static::init()->update($table, $set, $where);
    }
}
