<?php

class UserCookie
{
    private $user_id;
    private $token;
    private $cookie;
    private $expires_timestamp;

    public function __construct(int $user_id, string $token, int $expires_timestamp)
    {
        $this->user_id = $user_id;
        $this->token = $token;
        $this->expires_timestamp = $expires_timestamp;

        $user_cookie_creator = new UserCookieCreator;
        $this->cookie = $user_cookie_creator->create_cookie($user_id, $token);

        // $one_month_in_seconds = 60 * 60 * 24 * 30;
        // $this->set_expires($one_month_in_seconds);
    }

    public function get_user_id() : int
    {
        return $this->user_id;
    }

    public function get_token() : string
    {
        return $this->token;
    }

    public function get_token_sha256()
    {
        return hash('sha256', $this->token);
    }

    public function get_cookie() : string
    {
        return $this->cookie;
    }

    public function get_expires_timestamp()
    {
        return $this->expires_timestamp;
    }

    public function set_expires($period)
    {
        $this->expires = time() + $period;
    }

   //я так понимаю что вариант ниже плох тем, что если я внесу изменения в класс Hex_Token_Generator - может сломаться класс User_Cookie
   // public function __construct(int $user_id) {
   //     $this->user_id = $user_id;
   // }
   //
   // public function get_new_cookie() : string {
   //     $hex_token_generator = new Hex_Token_Generator;
   //     $token = $hex_token_generator->get_token();
   //
   //     return "sel$this->id"."token$token";
   // }

   //так что есть еще такой вариант
   // public function __construct(int $user_id) {
   //     $this->user_id = $user_id;
   // }
   //
   // public function get_cookie(string $token) : string {
   //     return "sel$this->id"."token$token";
   // }
}
