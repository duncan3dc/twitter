<?php

namespace duncan3dc\Twitter;

use duncan3dc\DomParser\HtmlParser;
use duncan3dc\Helpers\Env;
use duncan3dc\Helpers\Helper;
use duncan3dc\Helpers\Image;
use duncan3dc\Helpers\Json;

mb_internal_encoding("UTF-8");

class Post
{
    public  $id;
    public  $post;
    public  $date;
    public  $type;
    private $data;
    public  $retweet;

    public  $hostname;
    public  $username;
    public  $fullname;
    public  $avatar;
    public  $link;
    public  $text;


    public function __construct($row)
    {
        $this->id = $row["id"];
        $this->post = $row["post"];
        $this->date = $row["date"];
        $this->type = $row["type"];
        $this->data = Json::decode($row["data"]);

        $this->retweet = false;
        if ($this->type == "twitter" && isset($this->data["retweeted_status"])) {
            $this->retweet = $this->data;
            $this->data = $this->data["retweeted_status"];
        }

        switch ($this->type) {
            case "twitter":
                $this->hostname = "https://twitter.com/";
                $this->username = $this->data["user"]["screen_name"];
                $this->fullname = $this->data["user"]["name"];
                $this->avatar = $this->data["user"]["profile_image_url"];
                $this->link = $this->hostname . $this->username . "/status/" . $this->data["id_str"];
                $this->text = $this->data["text"];
                break;
        }
    }


    public function html()
    {
        $content = "<div id='postContainer_" . $this->id . "' data-post='" . $this->id . "' class='postContainer js-stream-item stream-item stream-item expanding-stream-item'>";
            $content .= "<div class='tweet original-tweet js-stream-tweet'>";
                $content .= "<div class='content'>";
                    $content .= "<img class='actionPost' data-post='" . $this->id . "' data-status='0' src='/images/x.gif'>";
                    $content .= "<img class='actionPost' data-post='" . $this->id . "' data-status='2' src='/images/save.png'>";
                    $content .= "<div class='stream-item-header'>";
                        $content .= "<small class='time'>";
                            if ($this->retweet) {
                                $content .= date("d/m/y H:i:s", strtotime($this->data["created_at"]));
                                $content .= "<br>";
                            }
                            $content .= "<a href='" . $this->link . "'>";
                                $content .= date("d/m/y H:i:s", $this->date);
                            $content .= "</a>";
                        $content .= "</small>";
                        $content .= "<a class='account-group js-account-group js-action-profile js-user-profile-link js-nav' href='" . $this->hostname . $this->username . "'>";
                            $content .= "<img class='avatar js-action-profile-avatar' src='" . $this->avatar . "'>";
                            $content .= "<strong class='fullname'>" . $this->fullname . "</strong>";
                            $content .= "&nbsp;";
                            $content .= "<span class='username'>@" . $this->username . "</span>";
                        $content .= "</a>";
                    $content .= "</div>";
                    $content .= "<p class='js-tweet-text'>";
                        $content .= $this->text;
                    $content .= "</p>";
                    $content .= "<div class='stream-item-footer'>";
                        $content .= "<div class='context'>";
                            $content .= "<span class='with-icn'>";
                                if ($this->retweet) {
                                    $content .= "<span class='js-retweet-text'>";
                                        $content .= "Retweeted by ";
                                        $content .= $this->retweet["user"]["name"] . " ";
                                        $content .= "(<a class='pretty-link js-user-profile-link' href='" . $this->hostname . $this->retweet["user"]["screen_name"] . "'>";
                                            $content .= "@" . $this->retweet["user"]["screen_name"];
                                        $content .= "</a>)";
                                    $content .= "</span>";
                                }
                            $content .= "</span>";
                        $content .= "</div>";
                    $content .= "</div>";
                $content .= "</div>";
            $content .= "</div>";
        $content .= "</div>";

        return $content;
    }
}
