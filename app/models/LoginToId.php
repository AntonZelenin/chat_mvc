<?php

class LoginToId
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
}
