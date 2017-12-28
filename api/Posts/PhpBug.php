<?php

namespace duncan3dc\Twitter\Posts;

class PhpBug extends AbstractPost
{
    public $hostname = "https://bugs.php.net";
    public $avatar = "/images/php-bug.png";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = "bugs.php.net";
        $this->fullname = "Bug Report";
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        return $this->data["title"] . "<br><a href='" . $this->data["link"] . "'>" . $this->data["link"] . "</a>";
    }
}
