<?php

class LoginPasswordChecker
{

    private $database_connection;

    public function __construct(Database_PDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    public function get_password_hash_from_db($login)
    {
        $query = $this->database_connection->prepare("SELECT password_hash FROM chat_passwords WHERE user_id=(SELECT rowid FROM chat_users WHERE login=:login)");
        $query->execute(array('login' => $login));

        return $query->fetchColumn();
    }

    public function is_valid_login_password($login, $password) : bool
    {
        $password_hash = $this->get_password_hash_from_db($login);

        return password_verify($password, $password_hash);
    }

}
