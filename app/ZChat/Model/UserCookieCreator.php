<?php

// abstract class Abstract_Cookie_Factory {
//     abstract function create_cookie() : string;
// }

class UserCookieCreator /*extends Abstract_Cookie_Factory*/ {
    public function create_cookie(int $id, string $token) : string
    {
        return "sel$id"."token$token";
    }
}
