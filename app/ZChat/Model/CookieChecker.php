<?php

class CookieChecker
{
    private $database_connection;

    public function __construct(DatabasePDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    public function is_valid_cookie(string $user_cookie) : bool
    {
        $cookie_parser = new UserCookieParser;
        $user_id = $cookie_parser->get_user_id($user_cookie);
        $user_token = $cookie_parser->get_user_token($user_cookie);

        $user_token_sha256 = hash('sha256', $user_token);

        $database_tokens_sha256 = $this->get_tokens_sha256_from_db($user_id);

        return $this->token_exists($user_token_sha256, $database_tokens_sha256);
    }

    private function get_tokens_sha256_from_db(int $user_id)
    {
        $query = $this->database_connection->prepare("SELECT token FROM chat_cookies WHERE user_id=:user_id");
        $query->execute(array('user_id' => $user_id));

        return $query->fetchAll();
    }

    private function token_exists($user_token_sha256, $database_tokens_sha256)
    {

        // print_r($database_tokens_sha256);

        foreach ($database_tokens_sha256 as $key => $token_sha256) {
            if (hash_equals($token_sha256['token'], $user_token_sha256)) {
                return true;
            }
        }

        return false;
    }

}
