<?php

class UserCookieParser
{
    public function getUserId($user_cookie)
    {
        if (preg_match('/sel(.+)token/', $user_cookie, $temp)) {
            return $temp[1];
        } else {
            // throw new Exception("Couldn't get user_id from user_cookie: ".$this->conn->connect_error . "\r\n");
            return false;
        }
    }

    public function getUserToken($user_cookie)
    {
        if (preg_match('/token(.+)/', $user_cookie, $temp)) {
            return $temp[1];
        } else {
            //throw new Exception("Couldn't get user_token from user_cookie\r\n");
            return false;
        }
    }

}
