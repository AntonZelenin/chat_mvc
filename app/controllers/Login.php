<?php

class Login extends Controller
{
    public function index()
    {
        $database = new Database_PDO;

        if (isset($_COOKIE)) {
            $checker = new CookieChecker($database);

            $user_cookie = $_COOKIE['user_cookie'];

            if ($checker->is_valid_cookie($user_cookie)) {
                header("Location: ..\\public\\home");
            }
        }

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $checker = new LoginPasswordChecker($database);

            if ($checker->is_login_password_valid($login, $password)) {
                $t = new LoginToId($database);
                $user_id = $t->id_by_login($_POST['reg_login']);

                $token_gen = new HexTokenGenerator;
                $token = $token_gen->generate();

                $one_month_in_seconds = 60 * 60 * 24 * 30;
                $month_later = time() + $one_month_in_seconds;

                $user_cookie = new UserCookie($user_id, $token, $month_later);

                $cookie_assigner = new CookieAssigner($database);
                $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

                header('Location: ..\\public\\home');
            }
        } elseif (isset($_POST['reg_login']) && isset($_POST['reg_password']) && isset($_POST['reg_username'])) {
            $user = new RegUser($_POST['reg_username'], $_POST['reg_login'], $_POST['reg_password']);

            $reg = new Registration($database);
            $reg->add_user_to_db($user);

            $t = new LoginToId($database);
            $user_id = $t->id_by_login($_POST['reg_login']);

            $token_gen = new HexTokenGenerator;
            $token = $token_gen->generate();

            $one_month_in_seconds = 60 * 60 * 24 * 30;
            $month_later = time() + $one_month_in_seconds;

            $user_cookie = new UserCookie($user_id, $token, $month_later);

            $cookie_assigner = new CookieAssigner($database);
            $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

            header("Location: ..\\public\\home");
        }

        require_once ROOT.'\\app\\views\\login.php';
    }
}
