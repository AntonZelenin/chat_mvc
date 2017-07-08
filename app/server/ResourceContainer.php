<?php

class ResourceContainer
{
    private $clients_id = [];

    public function add_socket($string)
    {
        $key = null;

        if (!$key || array_key_exists($key, $this->clients_id)) {
            $key = rand(1000, 9999);
        }

        $this->clients_id[$key] = $string;
    }

    public function key_to_string($key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
    }

    public function string_to_key($string)
    {
        $temp = array_flip($this->clients_id);
        if (isset($temp[$string])) {
            return $temp[$string];
        }
    }
}
