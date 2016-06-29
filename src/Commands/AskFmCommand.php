<?php

namespace duncan3dc\Twitter\Commands;

use duncan3dc\Helpers\Helper;
use duncan3dc\Cmdr\Sql;
use duncan3dc\DomParser\HtmlParser;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AskFmCommand extends \duncan3dc\Console\Command
{
    const TYPE = "askfm";

    protected function configure()
    {
        $this
            ->setDescription("Download the question/answers from Ask.fm");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = $this->getSql();

        $result = $sql->selectAll("status", [
            "type"  =>  self::TYPE,
        ]);
        foreach ($result as $row) {
            $oldest = 0;

            $output->info($row["key"]);
            $html = Helper::curl([
                "url"       =>  "http://ask.fm/" . $row["key"],
                "cookies"   =>  "/tmp/ask-fm.cookies",
            ]);
            $parser = new HtmlParser($html);

            $fullname = trim($parser->getElementById("profileName")->getTag("span")->nodeValue);
            $avatar = $parser->getElementById("profilePicture")->getAttribute("data-url");

            $posts = [];

            foreach ($parser->getElementsByClassName("streamItem-answer") as $box) {
                $age = $box->getElementByClassName("streamItemsAge");

                $bits = explode("/", $age->getAttribute("href"));
                $id = array_pop($bits);

                $time = $age->getAttribute("data-hint");
                if (!$date = strtotime($time)) {
                    throw new \Exception("Failed to parse the time: {$time}");
                }

                if (!$oldest || $date < $oldest) {
                    $oldest = $date;
                }

                $params = [
                    "post"  =>  $id,
                    "type"  =>  self::TYPE,
                ];

                # If we've had this post already then ignore it
                if ($sql->exists("posts", $params)) {
                    continue;
                }

                $output->tab()->comment($id);

                $question = trim($box->getElementByClassName("streamItemContent-question")->nodeValue);
                $tag = $box->getElementByClassName("streamItemContent-answer");
                $answer = trim($tag->nodeValue);
                foreach ($tag->getTags("img") as $img) {
                    $answer .= "<br>" . $img->output();
                }

                $data = [
                    "username"  =>  $row["key"],
                    "fullname"  =>  $fullname,
                    "avatar"    =>  $avatar,
                    "link"      =>  "http://ask.fm/" . $row["key"] . "/answer/" . $id,
                    "question"  =>  $question,
                    "answer"    =>  $answer,
                ];
                $posts[] = array_merge($params, [
                    "date"      =>  $date,
                    "text"      =>  $question . "\n" . $answer,
                    "user"      =>  $row["key"],
                    "data"      =>  json_encode($data),
                    "status"    =>  1,
                ]);
            }

            $latest = 0;
            $posts = array_reverse($posts);
            foreach ($posts as $post) {
                if ($post["date"] == $latest) {
                    $post["date"]++;
                }
                $sql->insert("posts", $post);
                $latest = $post["date"];
            }

            if ($latest) {
                $sql->update("status", [
                    "value" =>  $latest,
                ], [
                    "type"  =>  self::TYPE,
                    "key"   =>  $row["key"],
                ]);
            }
        }
    }
}

