<?php

namespace duncan3dc\Twitter;

use duncan3dc\DomParser\HtmlParser;
use duncan3dc\Helpers\Env;
use duncan3dc\Helpers\Image;
use duncan3dc\Laravel\Blade;
use duncan3dc\Serial\Json;

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

            case "askfm":
                $this->hostname = "http://ask.fm/";
                $this->username = $this->data["username"];
                $this->fullname = $this->data["fullname"];
                $this->avatar = $this->data["avatar"];
                $this->link = $this->data["link"];
                $this->text = '"<i>' . $this->data["question"] . '</i>"<br>' . $this->data["answer"];
                break;

            case "instagram":
                $this->hostname = "http://instagram.com/";
                $this->username = $this->data["user"]["username"];
                $this->fullname = $this->data["user"]["full_name"];
                $this->avatar = $this->data["user"]["profile_picture"];
                $this->link = $this->data["link"];
                $this->text = $this->data["caption"]["text"] . " <a href='" . $this->link . "'>" . $this->link . "</a>";
                if ($this->data["type"] == "image") {
                    if ($url = $this->data["images"]["standard_resolution"]["url"]) {
                        $this->text .= "<br>";
                        $this->text .= "<a href='" . $url . "'>";
                            $this->text .= "<img class='postImage' src='" . $url . "'>";
                        $this->text .= "</a>";
                    }
                }
                break;

            case "php":
                $this->hostname = "http://news.php.net/";
                $a = (new HtmlParser($this->data["description"]))->getTag("a");
                $email = $a->getAttribute("href");
                $email = str_replace("mailto:", "", $email);
                $email = str_replace("+dot+", ".", $email);
                $email = str_replace("+at+", "@", $email);
                $this->username = $email;
                $this->fullname = $a->nodeValue;
                $this->avatar = "/images/php.png";
                $this->link = $this->data["link"];
                $this->text = $this->data["title"] . "<br><a href='" . $this->data["link"] . "'>" . $this->data["link"] . "</a>";
                break;

            case "reddit":
                $this->hostname = "http://reddit.com/";
                $this->username = $this->data["author"];
                $this->fullname = $this->data["author"];
                $this->avatar = in_array($this->data["thumbnail"], ["", "self", "default"]) ? "/images/reddit.png" : $this->data["thumbnail"];
                $this->link = $this->hostname . $this->data["permalink"];
                $this->text = $this->data["title"] . "<br><a href='" . $this->link . "'>" . $this->link . "</a>";
                break;

            case "twitter":
                $this->hostname = "https://twitter.com/";
                $this->username = $this->data["user"]["screen_name"];
                $this->fullname = $this->data["user"]["name"];
                $this->avatar = $this->data["user"]["profile_image_url"];
                $this->link = $this->hostname . $this->username . "/status/" . $this->data["id_str"];
                $this->text = $this->tweetMeta();
                break;

            case "wikipedia":
                $this->hostname = "http://en.wikipedia.org/wiki/Special:Contributions/";
                $this->username = $this->data["author"];
                $this->fullname = $this->data["author"];
                $this->avatar = "/images/wikipedia.png";
                $this->link = $this->data["link"];
                $this->text = "<a href='" . $this->data["link"] . "'>" . $this->data["article"] . "</a><br>";
                $this->text .= "<i>" . htmlentities($this->data["comments"], \ENT_QUOTES) . "</i>";
                break;

            case "sublime":
                $this->hostname = "http://www.sublimetext.com/";
                $this->username = "forum";
                $this->fullname = $this->data["username"];
                $this->avatar = "/images/sublime.png";
                $this->link = $this->data["link"];
                $this->text = $this->data["title"] . "<br><a href='{$this->link}'>VIew Thread</a>";
                break;
        }
    }


    public function render()
    {
        return Blade::render("post", [
            "post"  =>  $this,
            "data"  =>  $this->data,
        ]);
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
            $splice = [
                "type"  =>  "link",
                "start" =>  $url["indices"][0],
                "end"   =>  $url["indices"][1],
                "text"  =>  $url["display_url"],
                "url"   =>  $url["expanded_url"],
            ];
            if (isset($url["video"])) {
                $splice["type"] = "video";
                $splice["video"] = $url["video"];
            }
            $splices[] = $splice;
        }

        $medias = isset($this->data["entities"]["media"]) ? $this->data["entities"]["media"] : [];
        foreach ($medias as $media) {
            $splice = [
                "type"  =>  "link",
                "start" =>  $media["indices"][0],
                "end"   =>  $media["indices"][1],
                "text"  =>  $media["display_url"],
                "url"   =>  $media["media_url"],
            ];
            if ($media["type"] == "photo") {
                if ($image = $media["id_str"]) {
                    $splice["type"] = "image";
                    $path = "images/cache";
                    $fullpath = Env::path("web/" . $path . "/original/" . $image);
                    if (!file_exists($fullpath)) {
                        $row = Sql::select("postimages", [
                            "post"  =>  $this->post,
                            "image" =>  $image,
                        ]);
                        file_put_contents($fullpath, $row["data"]);
                    }
                    $splice["fullsize"] = Image::img([
                        "path"      =>  $path,
                        "basename"  =>  $image,
                    ]);
                    $splice["src"] = Image::img([
                        "path"      =>  $path,
                        "basename"  =>  $image,
                        "width"     =>  500,
                        "height"    =>  500,
                    ]);
                }
            }
            $splices[] = $splice;
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

                case "image":
                    $new = "<a href='" . $val["url"] . "'>" . $val["text"] . "</a>";
                    $append .= "<br>";
                    $append .= "<a href='" . $val["fullsize"] . "'>";
                        $append .= "<img class='postImage' src='" . $val["src"] . "'>";
                    $append .= "</a>";
                    break;

                case "video":
                    $new = "<a href='" . $val["url"] . "'>" . $val["text"] . "</a>";
                    $append .= "<br>";
                    $append .= $val["video"];
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
