<?php

class Home extends Controller
{

    public function index()
    {
        $database = new Database_PDO;

        $checker = new CookieChecker($database);

        $user_cookie = $_COOKIE['user_cookie'];
        $is_authorized = $checker->is_valid_cookie($user_cookie);

        if (!$is_authorized) {
            header("Location: ..\\public\\login");
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == 'DELETE'){
            $cookie_assigner = new CookieAssigner($database);
            $cookie_assigner->unset_cookie_and_remove_from_db($_COOKIE['user_cookie']);

            header("Location: ..\\public\\login");
        }

        require_once ROOT.'\\app\\views\\home.php';
    }
}
