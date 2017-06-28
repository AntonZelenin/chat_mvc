<?php

class CookieAssigner
{

//вот тута нипанятна какого хера в базу кладется sha256 от куки. хрен заметишь)) надо переделать

    private  $database_connection;

    public function __construct(Database_PDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    //выполняет два действия, нарушает правило. потому что они ОБЯЗАТЕЛЬНО должны выполняться в паре. норм?)
    public function set_cookie_and_add_to_db(User_Cookie $user_cookie)
    {
        setcookie('user_cookie', $user_cookie->get_cookie(), $user_cookie->get_expires_timestamp(), '/');

        $this->add_cookie_to_db($user_cookie);
    }

    public function unset_cookie_and_remove_from_db($cookie)
    {
        unset($_COOKIE['user_cookie']);

        $this->remove_cookie_from_db($cookie);
    }

// }

// class Cookie_Saver extends Cookie_Assigner
// {

    // public function __construct(Database_PDO $database)
    // {
    //     $this->database_connection = $database->get_connection();
    // }

    private function add_cookie_to_db(User_Cookie $cookie)
    {
        $query = $this->database_connection->prepare('INSERT INTO chat_cookies (user_id, token, expires) VALUES (:user_id, :token, FROM_UNIXTIME(:expires))');
        $query->execute(array('user_id' => $cookie->get_user_id(), 'token' => $cookie->get_token_sha256(), 'expires' => $cookie->get_expires_timestamp()));
    }

    private function remove_cookie_from_db(string $cookie)
    {
        $cookie_parser = new User_Cookie_Parser;
        $user_id = $cookie_parser->get_user_id($cookie);
        $token = $cookie_parser->get_user_token($cookie);

        $query = $this->database_connection->prepare("DELETE FROM chat_cookies WHERE user_id=:user_id AND token=:token");
        $query->execute(array('user_id' => $user_id, ':token' => hash('sha256', $token)));
    }

}
