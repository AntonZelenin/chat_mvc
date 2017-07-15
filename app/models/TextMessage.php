<?php

class TextMessage
{
    private $text;
    private $sender_id;
    private $group_id;

    public set_sender_id(int $sender_id)
    {
        $this->sender_id = $sender_id;
    }

    public set_text(string $text)
    {
        $this->text = $text;
    }

    public set_group_id(int $group_id)
    {
        $this->group_id = $group_id;
    }

    public get_sender_id(int $sender_id) : int
    {
        return $this->sender_id;
    }

    public get_text(string $text) : string
    {
        return $this->text;
    }

    public get_group_id(int $group_id) : int
    {
        return $this->group_id;
    }
}
