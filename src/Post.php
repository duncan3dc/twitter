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
                $this->text = $this->tweetMeta();
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


    public function tweetMeta()
    {
        $splices = [];

        foreach ($this->data["entities"]["hashtags"] as $hashtag) {
            $splices[] = [
                "type"  =>  "hashtag",
                "start" =>  $hashtag["indices"][0],
                "end"   =>  $hashtag["indices"][1],
                "text"  =>  $hashtag["text"],
            ];
        }

        foreach ($this->data["entities"]["user_mentions"] as $mention) {
            $splices[] = [
                "type"  =>  "mention",
                "start" =>  $mention["indices"][0],
                "end"   =>  $mention["indices"][1],
                "text"  =>  $mention["screen_name"],
            ];
        }

        foreach ($this->data["entities"]["urls"] as $url) {
            $splices[] = [
                "type"  =>  "link",
                "start" =>  $url["indices"][0],
                "end"   =>  $url["indices"][1],
                "text"  =>  $url["display_url"],
                "url"   =>  $url["expanded_url"],
            ];
        }

        $medias = isset($this->data["entities"]["media"]) ? $this->data["entities"]["media"] : [];
        foreach ($medias as $media) {
            $splices[] = [
                "type"  =>  "link",
                "start" =>  $media["indices"][0],
                "end"   =>  $media["indices"][1],
                "text"  =>  $media["display_url"],
                "url"   =>  $media["media_url"],
            ];
        }

        usort($splices, function($val1, $val2) {
            if ($val1["start"] < $val2["start"]) {
                return -1;
            }
            if ($val1["start"] > $val2["start"]) {
                return 1;
            }
            return 0;
        });

        $text = $this->data["text"];
        $append = "";
        $adjust = 0;
        foreach ($splices as $val) {

            switch ($val["type"]) {

                case "hashtag";
                    $hashtag = "#" . $val["text"];
                    $new = "<a href='" . $this->hostname . "search?q=" . urlencode($hashtag) . "'>" . $hashtag . "</a>";
                    break;

                case "mention":
                    $new = "<a href='" . $this->hostname . $val["text"] . "'>@" . $val["text"] . "</a>";
                    break;

                case "link":
                    $new = "<a href='" . $val["url"] . "'>" . $val["text"] . "</a>";
                    break;

                default:
                    $new = $val["text"];
            }

            $startPos = $val["start"];
            $endPos = $val["end"];
            $diff = $endPos - $startPos;

            $start = mb_substr($text, 0, $startPos + $adjust);
            $end = mb_substr($text, $endPos + $adjust);

            $adjust += (mb_strlen($new) - $diff);

            $text = $start . $new . $end;
        }
        $text .= $append;

        return nl2br($text);
    }
}
