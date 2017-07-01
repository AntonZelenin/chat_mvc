<?php

class HexTokenGenerator extends TokenGenerator
{

    public function generate(int $length = 20) : string
    {
        return bin2hex(random_bytes($length));
    }
}
