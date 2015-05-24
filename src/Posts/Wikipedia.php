<?php

namespace duncan3dc\Twitter\Posts;

class Wikipedia extends AbstractPost
{
    public $hostname = "http://en.wikipedia.org/wiki/Special:Contributions/";
    public $avatar = "/images/wikipedia.png";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = $this->data["author"];
        $this->fullname = $this->data["author"];
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        $html = "<a href='" . $this->data["link"] . "'>" . $this->data["article"] . "</a><br>";
        $html .= "<i>" . htmlentities($this->data["comments"], \ENT_QUOTES) . "</i>";
        return $html;
    }
}
