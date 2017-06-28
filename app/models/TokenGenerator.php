<?php

abstract class TokenGenerator
{
    abstract function get_token(int $length) : string;
}
