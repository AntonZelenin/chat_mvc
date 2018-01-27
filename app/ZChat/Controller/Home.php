<?php namespace ZChat\Controller;

use \ZChat\Core\DatabasePDO as DatabasePDO;

/**
 * Class Home
 * @package ZChat\Controller
 */
class Home
{
    /**
     *
     */
    public function index()
    {
        $database = new DatabasePDO();
        $isAuthorized = false;

        if (isset($_COOKIE['user_cookie'])) {
            $checker = new CookieChecker($database);

            $user_cookie = $_COOKIE['user_cookie'];
            $isAuthorized = $checker->is_valid_cookie($user_cookie);
        }

        if (!$isAuthorized) {
            $host = $_SERVER['HTTP_HOST'];
            header("Location: http://$host/chat/login/auth");
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

        $this->view('home');
    }
}
