<?php

namespace duncan3dc\Twitter\Posts;

use function implode;

class Albums extends AbstractPost
{
    public $hostname = "http://twitter.com";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = $row["user"];
        $this->fullname = $this->data["title"];
        $this->link = $this->data["link"];
        $this->avatar = $this->data["image"];
    }

    public function getHtml()
    {
        return implode(", ", $this->data->categories->asArray()) . "<br><a href='" . $this->link . "'>" . $this->link . "</a>";
    }
}
