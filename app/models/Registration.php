<?php

class Registration
{
    private $database_connection;

    public function __construct(Database_PDO $database) {
        $this->database_connection = $database->get_connection();
    }

    public function add_user_to_db(RegUser $user) {
        $password_hash = password_hash($user->get_password(), PASSWORD_DEFAULT);

        $query = $this->database_connection->prepare("INSERT INTO chat_users (username, login, registration_date) VALUES (:username, :login, FROM_UNIXTIME(:registration_date))");
        $query->execute(array('username' => $user->get_username(), 'login' => $user->get_login(), 'registration_date' => time()));

        $query = $this->database_connection->prepare("INSERT INTO chat_passwords (user_id, password_hash) VALUES ((SELECT id FROM chat_users WHERE login=:login), :password_hash)");
        $query->execute(array('login' => $user->get_login(), 'password_hash' => $password_hash));
    }
}
