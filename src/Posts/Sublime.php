<?php

namespace duncan3dc\Twitter\Posts;

class Sublime extends AbstractPost
{
    public $hostname = "http://www.sublimetext.com";
    public $avatar = "/images/sublime.png";
    public $username = "forum";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->fullname = $this->data["username"];
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        return htmlentities($this->data["title"], \ENT_QUOTES) . "<br><a href='{$this->link}'>View Thread</a>";
    }
}
