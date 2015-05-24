<?php

namespace duncan3dc\Twitter\Posts;

class Google extends AbstractPost
{
    public $hostname = "https://groups.google.com/";
    public $avatar = "/images/google.png";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = str_replace(" ", "", $this->data["author"]);
        $this->fullname = $this->data["author"];
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        $html = "<b><a href='{$this->link}'>" . $this->data["title"] . "</a></b>";
        $html .= "<br>";
        $html .= "<i>" . $this->data["content"] . "&hellip;</i>";
        return $html;
    }
}
