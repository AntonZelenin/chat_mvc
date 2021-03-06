<?php

class Login extends Controller
{
    public function index()
    {
        $host = $_SERVER['HTTP_HOST'];
        header("Location: http://$host/public/login/auth");
        die;
    }

    public function auth()
    {
        if (isset($_COOKIE['user_cookie'])) {
            $checker = new CookieChecker(new Database_PDO);
            // $checker = $this->model('CookieChecker');

            $user_cookie = $_COOKIE['user_cookie'];

            if ($checker->is_valid_cookie($user_cookie)) {
                $host = $_SERVER['HTTP_HOST'];
                header("Location: http://$host/public/home");

                die;
            }
        }

        $this->view('login');
    }

    public function enter()
    {
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = strip_tags($_POST['login']);
            $login = htmlspecialchars($login);

            $password = strip_tags($_POST['password']);
            $password = htmlspecialchars($password);

            $checker = new LoginPasswordChecker(new Database_PDO);

            if ($checker->is_login_password_valid($login, $password)) {
                $user_id = (new Converter(new Database_PDO))->id_by_login($_POST['login']);

                // print_r($user_id); die;

                $token_gen = new HexTokenGenerator;
                $token = $token_gen->generate();

                $one_month_in_seconds = 60 * 60 * 24 * 30;
                $month_later = time() + $one_month_in_seconds;

                $user_cookie = new UserCookie($user_id, $token, $month_later);

                $cookie_assigner = new CookieAssigner(new Database_PDO);
                $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

                $host = $_SERVER['HTTP_HOST'];
                header("Location: http://$host/public/home");
                die;
            } else {
                $host = $_SERVER['HTTP_HOST'];
                header("Location: http://$host/public/login/auth");
                die;
            }
        } else {
            echo "ne enter";
            $host = $_SERVER['HTTP_HOST'];
            header("Location: http://$host/public/login/auth");
            die;
        }

        // require_once ROOT.'\\app\\views\\login.php';
    }

    public function register()
    {
        if (isset($_POST['reg_login']) && isset($_POST['reg_password']) && isset($_POST['reg_username'])) {
           $user = new RegUser($_POST['reg_username'], $_POST['reg_login'], $_POST['reg_password']);

           $reg = new Registration(new Database_PDO);
           $reg->add_user_to_db($user);

           $t = new LoginToId(new Database_PDO);
           $user_id = $t->id_by_login($_POST['reg_login']);

           $token_gen = new HexTokenGenerator;
           $token = $token_gen->generate();

           $one_month_in_seconds = 60 * 60 * 24 * 30;
           $month_later = time() + $one_month_in_seconds;

           $user_cookie = new UserCookie($user_id, $token, $month_later);

           $cookie_assigner = new CookieAssigner(new Database_PDO);
           $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

           echo true;

        //    $host = $_SERVER['HTTP_HOST'];
        //    header("Location: http://$host/public/home");
        //    die;
       }
    }
}
