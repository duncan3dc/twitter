<?php

namespace duncan3dc\Twitter;

class Factory
{
    public function make(array $row)
    {
        $name = $row["type"];
        $name = ucfirst($name);
        $name = preg_replace_callback("/-([a-z])/", function ($match) {
            return strtoupper($match[1]);
        }, $name);

        $class = __NAMESPACE__ . "\\Posts\\" . $name;

        return new $class($row);
    }
}
