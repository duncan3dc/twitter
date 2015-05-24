<?php

namespace duncan3dc\Twitter;

class Factory
{
    public function make(array $row)
    {
        $class = __NAMESPACE__ . "\\Posts\\" . ucfirst($row["type"]);
        return new $class($row);
    }
}
