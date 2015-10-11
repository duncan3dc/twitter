<?php

namespace duncan3dc\Twitter\Posts;

use duncan3dc\Laravel\Blade;
use duncan3dc\Serial\Json;

abstract class AbstractPost
{
    public $id;
    public $date;
    public $retweet;

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
        $this->retweet = false;

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


    abstract public function getHtml();
}
