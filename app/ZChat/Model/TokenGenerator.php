<?php

abstract class TokenGenerator
{
    abstract function generate(int $length) : string;
}
