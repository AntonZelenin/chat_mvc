<?php

class Registration
{
    private $database_connection;

    public function __construct(Database_PDO $database) {
        $this->database_connection = $database->get_connection();
    }

    public function add_user_to_db(User $user) {
        $password_hash = password_hash($user->get_password(), PASSWORD_DEFAULT);

        $query = $this->database_connection->prepare("INSERT INTO chat_users (username, login) VALUES (:username, :login)");
        $query->execute(array('username' => $user->get_username(), 'login' => $user->get_login()));

        $query = $this->database_connection->prepare("INSERT INTO chat_passwords (user_id, password_hash) VALUES ((SELECT rowid FROM chat_users WHERE login=:login), :password_hash)");
        $query->execute(array('login' => $user->get_login(), 'password_hash' => $password_hash));
    }
}
