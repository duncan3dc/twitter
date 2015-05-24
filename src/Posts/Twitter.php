<?php

namespace duncan3dc\Twitter\Posts;

use duncan3dc\Helpers\Env;
use duncan3dc\Helpers\Image;
use duncan3dc\Twitter\Sql;

class Twitter extends AbstractPost
{
    public $hostname = "https://twitter.com/";

    public function __construct(array $row)
    {
        parent::__construct($row);

        if (isset($this->data["retweeted_status"])) {
            $this->retweet = $this->data;
            $this->data = $this->data["retweeted_status"];
        }

        $this->username = $this->data["user"]["screen_name"];
        $this->fullname = $this->data["user"]["name"];
        $this->avatar = $this->data["user"]["profile_image_url"];
        $this->link = "{$this->hostname}{$this->username}/status/{$this->post}";
    }


    public function getHtml()
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
            } elseif (preg_match("/https:\/\/twitter\.com\/[^\/]+\/status\/([0-9]+)/", $url["expanded_url"], $matches)) {
                $splice["type"] = "tweet";
                $splice["tweet"] = $matches[1];
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
                        if ($row) {
                            file_put_contents($fullpath, $row["data"]);
                        }
                    }
                    if (file_exists($fullpath)) {
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
                    } else {
                        $splice["fullsize"] = $media["media_url"];
                        $splice["src"] = $media["media_url"];
                    }
                }
            }
            $splices[] = $splice;
        }

        uksort($splices, function ($key1, $key2) use ($splices) {
            $pos1 = $splices[$key1]["start"];
            $pos2 = $splices[$key2]["start"];
            if ($pos1 < $pos2) {
                return -1;
            }
            if ($pos1 > $pos2) {
                return 1;
            }
            if ($key1 < $key2) {
                return -1;
            }
            if ($key1 > $key2) {
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

                case "tweet":
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
