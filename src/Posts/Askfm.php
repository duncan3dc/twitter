<?php

namespace duncan3dc\Twitter\Posts;

class Askfm extends AbstractPost
{
    public $hostname = "http://ask.fm/";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = $this->data["username"];
        $this->fullname = $this->data["fullname"];
        $this->avatar = $this->data["avatar"];
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        return '"<i>' . $this->data["question"] . '</i>"<br>' . $this->data["answer"];
    }
}
