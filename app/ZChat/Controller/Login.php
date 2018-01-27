<?php namespace ZChat\Controller;

/**
 * Class Login
 * @package ZChat\Controller
 */
class Login
{
    /**
     * @var string
     */
    protected $template = "login.html";

    /**
     *
     */
    public function index()
    {
        header("Location: /chat/login/auth");
        die;
    }

    /**
     *
     */
    public function auth()
    {
        if (isset($_COOKIE['user_cookie'])) {
            $checker = new CookieChecker(new DatabasePDO);

            $userCookie = $_COOKIE['user_cookie'];

            if ($checker->is_valid_cookie($userCookie)) {
                header("Location: /chat/home");

                die;
            }
        }

        if (file_exists(ROOT . "/app/views/{$this->template}")) {
            require ROOT . "/app/views/{$this->template}";
        }
    }

    /**
     *
     */
    public function enter()
    {
        if (!isset($_POST['login']) || !isset($_POST['password'])) {
            if (file_exists(ROOT . "/app/views/{$this->template}")) {
                require ROOT . "/app/views/{$this->template}";
            }

            return;
        }

        $login = strip_tags($_POST['login']);
        $login = htmlspecialchars($login);

        $password = strip_tags($_POST['password']);
        $password = htmlspecialchars($password);

        $checker = new LoginPasswordChecker(new DatabasePDO);

        if ($checker->is_login_password_valid($login, $password)) {
            $user_id = (new Converter(new DatabasePDO))->id_by_login($_POST['login']);

            // print_r($user_id); die;

            $token_gen = new HexTokenGenerator;
            $token = $token_gen->generate();

            $one_month_in_seconds = 60 * 60 * 24 * 30;
            $month_later = time() + $one_month_in_seconds;

            $user_cookie = new UserCookie($user_id, $token, $month_later);

            $cookie_assigner = new CookieAssigner(new DatabasePDO);
            $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

            $host = $_SERVER['HTTP_HOST'];
            header("Location: http://$host/public/home");
            die;
        } else {
//            header("Location: /chat/login/auth");

            return;
        }

        // require_once ROOT.'\\app\\views\\login.php';
    }

    /**
     *
     */
    public function register()
    {
        if (isset($_POST['reg_login']) && isset($_POST['reg_password']) && isset($_POST['reg_username'])) {
           $user = new RegUser($_POST['reg_username'], $_POST['reg_login'], $_POST['reg_password']);

           $reg = new Registration(new DatabasePDO);
           $reg->add_user_to_db($user);

           $t = new LoginToId(new DatabasePDO);
           $user_id = $t->id_by_login($_POST['reg_login']);

           $token_gen = new HexTokenGenerator;
           $token = $token_gen->generate();

           $one_month_in_seconds = 60 * 60 * 24 * 30;
           $month_later = time() + $one_month_in_seconds;

           $user_cookie = new UserCookie($user_id, $token, $month_later);

           $cookie_assigner = new CookieAssigner(new DatabasePDO);
           $cookie_assigner->set_cookie_and_add_to_db($user_cookie);

           echo true;

        //    $host = $_SERVER['HTTP_HOST'];
        //    header("Location: http://$host/public/home");
        //    die;
       }
    }
}
