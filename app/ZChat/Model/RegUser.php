<?php

class RegUser
{
    private $username;
    private $login;
    private $password;

    //вот еще вопрос. можно было сделать все через сеттеры. может использовать строителя для этого?) просто я его еще не расшарил))
    public function __construct($username, $login, $password)
    {
        $this->username = $username;
        $this->login = $login;
        $this->password = $password;
    }

    public function get_username()
    {
        return $this->username;
    }

    public function get_login()
    {
        return $this->login;
    }

    public function get_password()
    {
        return $this->password;
    }

}
