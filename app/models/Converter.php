<?php

class Converter
{
    private $database_connection;

    public function __construct(Database_PDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    public function id_by_login(string $login) : int
    {
        $query = $this->database_connection->prepare("SELECT id FROM chat_users WHERE login=:login");
        $query->execute(array('login' => $login));

        return $query->fetchColumn();
    }

    public function id_by_cookie(string $cookie) : int
    {
        //в базе лежит не просто токен, там блин sha256 от токена! переделать
        $token = hash('sha256', (new UserCookieParser)->get_user_token($cookie));

        $query = $this->database_connection->prepare("SELECT user_id FROM chat_cookies WHERE token=:token");
        $query->execute(array('token' => $token));

        return $query->fetchColumn();
    }
}
