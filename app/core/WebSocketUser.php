<?php

class WebSocketUser {
    private $socket;
    public $id;
    private $headers = array();
    private $handshake = false;

    public $handlingPartialPacket = false;
    public $partialBuffer = "";

    public $sendingContinuous = false;
    public $partialMessage = "";

    public $hasSentClose = false;

    public function __construct($id, $socket)
    {
        $this->id = $id;
        $this->socket = $socket;
    }

    public function get_handshake() : string
    {
        return $this->handshake;
    }

    public function is_handshake() : bool
    {
        return ($this->handshake) ? true : false;
    }

    public function get_socket()
    {
        return $this->socket;
    }

    public function set_handshake($buffer)
    {
        $this->handshake = $buffer;
    }

    public function get_headers() : array
    {
        return $this->headers;
    }

    public function set_headers($headers)
    {
        $this->headers = $headers;
    }
}
