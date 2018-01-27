<?php

class LoginPasswordChecker
{

    private $database_connection;

    public function __construct(DatabasePDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    public function get_password_hash_from_db($login)
    {
        $query = $this->database_connection->prepare("SELECT password_hash FROM chat_passwords WHERE user_id=(SELECT id FROM chat_users WHERE login=:login)");
        $query->execute(array('login' => $login));

        return $query->fetchColumn();
    }

    public function is_login_password_valid($login, $password) : bool
    {
        $password_hash = $this->get_password_hash_from_db($login);

        return password_verify($password, $password_hash);
    }

}
