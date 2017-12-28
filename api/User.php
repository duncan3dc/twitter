<?php

namespace duncan3dc\Twitter;

use duncan3dc\Helpers\Env;
use duncan3dc\Sessions\Session;

class User
{

    public static function isLoggedIn()
    {
        if (Env::getVar("auto-login")) {
            return true;
        }

        static::checkLogin();

        $user = Session::get("user");
        $auth = Session::get("auth");

        if (!$auth || !$user) {
            return false;
        }

        if (!password_verify("twitter_{$user}", $auth)) {
            Session::destroy();
            return false;
        }

        return true;
    }


    protected static function checkLogin()
    {
        if (empty($_POST["username"])) {
            return;
        }

        if (empty($_POST["password"])) {
            return;
        }

        $username = $_POST["username"];
        $password = "twitter_{$username}_" . $_POST["password"];

        $users = Env::requireVar("users");

        if (empty($users[$username])) {
            return;
        }

        if (password_verify($password, $users[$username])) {
            Session::set("user", $username);
            Session::set("auth", password_hash("twitter_{$username}", \PASSWORD_DEFAULT));
        }
    }
}
