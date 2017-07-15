<?php

class Home extends Controller
{

    public function index()
    {
        $database = new Database_PDO;
        $is_authorized = false;

        if (isset($_COOKIE['user_cookie'])) {
            $checker = new CookieChecker($database);

            $user_cookie = $_COOKIE['user_cookie'];
            $is_authorized = $checker->is_valid_cookie($user_cookie);
        }

        if (!$is_authorized) {
            $host = $_SERVER['HTTP_HOST'];
            header("Location: http://$host/public/login/auth");
            die;
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == 'DELETE'){
            $cookie_assigner = new CookieAssigner($database);
            $cookie_assigner->unset_cookie_and_remove_from_db($_COOKIE['user_cookie']);

            //это должно происходить в методе сверху. исправить
            unset($_COOKIE['user_cookie']);

            $host = $_SERVER['HTTP_HOST'];
            header("Location: http://$host/public/login");
            die;
        }

        require_once ROOT.'\\app\\views\\home.php';
    }
}
