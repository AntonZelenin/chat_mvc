<?php

class HexTokenGenerator extends TokenGenerator
{

    public function get_token(int $length = 20) : string
    {
        return bin2hex(random_bytes($length));
    }
}
