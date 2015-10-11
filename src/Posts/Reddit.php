<?php

namespace duncan3dc\Twitter\Posts;

class Reddit extends AbstractPost
{
    public $hostname = "http://reddit.com";
    public $avatar = "/images/reddit.png";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = $this->data["author"];
        $this->fullname = $this->data["author"];
        $this->link = $this->hostname . "/" . $this->data["permalink"];
        if (!in_array($this->data["thumbnail"], ["", "self", "default"], true)) {
            $this->avatar = $this->data["thumbnail"];
        }
    }

    public function getHtml()
    {
        return $this->data["title"] . "<br><a href='" . $this->link . "'>" . $this->link . "</a>";
    }
}
