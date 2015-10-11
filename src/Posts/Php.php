<?php

namespace duncan3dc\Twitter\Posts;

use duncan3dc\DomParser\HtmlParser;

class Php extends AbstractPost
{
    public $hostname = "http://news.php.net";
    public $avatar = "/images/php.png";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $a = (new HtmlParser($this->data["description"]))->getTag("a");
        $email = $a->getAttribute("href");
        $email = str_replace("mailto:", "", $email);
        $email = str_replace("+dot+", ".", $email);
        $email = str_replace("+at+", "@", $email);
        $this->username = $email;
        $this->fullname = $a->nodeValue;
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        return $this->data["title"] . "<br><a href='" . $this->data["link"] . "'>" . $this->data["link"] . "</a>";
    }
}
