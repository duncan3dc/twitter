<?php

namespace duncan3dc\Twitter;

use duncan3dc\Helpers\Dict;
use duncan3dc\Helpers\Helper;
use duncan3dc\Helpers\Json;

require(__DIR__ . "/../vendor/autoload.php");

switch ($_GET["action"]) {


    case "getUserData":
        $data = [];

        $result = Sql::selectAll("status", [
            "type"      =>  "twitter",
        ]);
        foreach ($result as $row) {
            $data[$row["key"]] = $row["value"];
        }

        echo Json::encode([
            "status"    =>  1,
            "userdata"  =>  $data,
        ]);
        break;
}
