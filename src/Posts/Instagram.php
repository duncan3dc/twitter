<?php

namespace duncan3dc\Twitter\Posts;

class Instagram extends AbstractPost
{
    public $hostname = "http://instagram.com";

    public function __construct(array $row)
    {
        parent::__construct($row);

        $this->username = $this->data["user"]["username"];
        $this->fullname = $this->data["user"]["full_name"];
        $this->avatar = $this->data["user"]["profile_picture"];
        $this->link = $this->data["link"];
    }

    public function getHtml()
    {
        $html = $this->data["caption"]["text"] . " <a href='" . $this->link . "'>" . $this->link . "</a>";

        if ($this->data["type"] === "image") {
            if ($url = $this->data["images"]["standard_resolution"]["url"]) {
                $html .= "<br>";
                $html .= "<a href='" . $url . "'>";
                    $html .= "<img class='postImage' src='" . $url . "'>";
                $html .= "</a>";
            }
        }

        return $html;
    }
}
