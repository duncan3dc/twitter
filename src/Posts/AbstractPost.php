<?php

namespace duncan3dc\Twitter\Posts;

use duncan3dc\Laravel\Blade;
use duncan3dc\Serial\Json;

abstract class AbstractPost
{
    public $id;
    public $date;
    public $retweet;
    public $quoted;

    protected $post;
    protected $type;
    protected $data;

    public $hostname;
    public $username;
    public $fullname;
    public $avatar;
    public $link;


    public function __construct(array $row)
    {
        $this->id = $row["id"];
        $this->date = $row["date"];

        $this->post = $row["post"];
        $this->type = $row["type"];
        $this->data = Json::decode($row["data"]);
    }


    public function render()
    {
        return Blade::render("post", [
            "post"  =>  $this,
            "data"  =>  $this->data,
        ]);
    }


    public function getUserLink($user)
    {
        return "{$this->hostname}/{$user}";
    }


    public function safespace($content)
    {
        $content = preg_replace_callback("/  +/", function ($match) {
            return str_replace(" ", "&nbsp;", $match[0]);
        }, $content);

        return $content;
    }


    abstract public function getHtml();
}
