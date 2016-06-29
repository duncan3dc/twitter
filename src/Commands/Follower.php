<?php

namespace duncan3dc\Twitter\Commands;

class Follower
{
    private $type;
    private $handle;
    private $latest;


    public static function getAll($type, Sql $sql)
    {
        $result = $this->sql->selectAll("followers", [
            "type"  =>  $type,
        ]);
        foreach ($result as $row) {
            $follower = new static($type, $row["key"], Sql $sql);
            $follower->latest = $row["latest"];
            yield $follower;
        }
    }


    public function __construct($type, $handle, Sql $sql)
    {
        $this->type = $type;
        $this->handle = $handle;
        $this->sql = $sql;
    }


    public function getType()
    {
        return $this->type;
    }


    public function getHandle()
    {
        return $handle;
    }


    public function getLatest()
    {
        if ($this->latest !== null) {
            return $this->latest;
        }

        $row = $this->sql->select("followers", [
            "type"      =>  $this->type,
            "handle"    =>  $this->handle,
        ]);
        if ($row) {
            $this->latest = $row["latest"];
        } else {
            $this->latest = "";
        }

        return $this->latest;
    }
}
