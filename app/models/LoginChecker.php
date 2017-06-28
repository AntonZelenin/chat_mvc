<?php

class LoginChecker
{

    private $database_connection;

    public function __construct(Database_PDO $database) {
        $this->database_connection = $database->get_connection();
    }

    public function is_login_available(string $login) : bool {
        $query = $this->database_connection->prepare("SELECT COUNT(rowid) FROM chat_passwords WHERE user_id=(SELECT rowid FROM chat_users WHERE login=:login)");
        $query->execute(array('login' => $login));

        return ($query->fetchColumn() == 0) ? true: false;
    }
}
