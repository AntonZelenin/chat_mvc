<?php
define('ROOT', __DIR__.'\\..');
require_once(ROOT.'\\core\\Autoload.php');

class SocketServer
{
    private $socket;
    private const $host = 'localhost'; //host
    private const $port = '9000'; //port
    private $clients = array();
    private $socket_container;

    public function __construct()
    {
        //Create TCP/IP sream socket
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        //reuseable port
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

        //bind socket to specified host
        socket_bind($this->socket, 0, $this->port);

        //listen to port
        socket_listen($this->socket);

        //create & add listning socket to the list
        $this->clients[] = $this->socket;
        $this->resource_container = new ResourceContainer;
    }

    public function __destruct()
    {
        // close the listening socket
        socket_close($this->socket);
    }

    public function start()
    {
        while (true) {
        	//manage multipal connections
        	$changed = $this->clients;

        	//returns the socket resources in $changed array
        	socket_select($changed, NULL, NULL, 0, 10);

        	//check for new socket
        	if ($this->is_new_socket()) {
        		$new_socket = socket_accept($this->socket); //accpet new socket
        		$this->clients[] = $new_socket; //add socket to client array

        		$headers = socket_read($new_socket, 1024); //read data sent by the socket

                $headers = $this->parse_headers($headers);

        		$this->perform_handshaking($headers, $new_socket); //perform websocket handshake
                // if (!isset($headers['Cookie']) // (new CookieChecker)->is_valid_cookie($headers['Cookie'])) {
                //     что-то плохое
                // }

                $this->socket_container->add_socket($new_socket);

        		//make room for new socket
        		$found_socket = array_search($this->socket, $changed);
        		unset($changed[$found_socket]);
        	}

        	//loop through all connected sockets
        	foreach ($changed as $changed_socket) {

        		while (socket_recv($changed_socket, $buf, 1024, 0) >= 1) {
        			$received_text = $this->unmask($buf);
        			$tst_msg = json_decode($received_text);
        			$user_name = $tst_msg->name;
        			$user_message = $tst_msg->message;
                    $user_color = $tst_msg->color;
                    $user_id = $tst_msg->user_id;

        			$response_text = $this->mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color, 'user_id' => $user_id)));
        			// $this->send_message($response_text); //send data
                    $this->send_message_to_user($user_id, $response_text);
        			break 2; //exist this loop
        		}

        		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
        		if ($buf === false) {
                    // check disconnected client
        			// remove client for $this->clients array
        			$found_socket = array_search($changed_socket, $this->clients);
        			unset($this->clients[$found_socket]);
        		}
        	}
        }
    }

    private function is_new_socket() : bool
    {
        return (in_array($this->socket, $changed)) ? true : false;
    }

    private function parse_headers($recieved_header)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $recieved_header);

        foreach ($lines as $line) {
            $line = chop($line);

            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        return $headers;
    }

    private function perform_handshaking($receved_header, $client_conn)
    {
        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        //hand shaking header
        $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "WebSocket-Origin: $this->host\r\n" .
        "WebSocket-Location: ws://$this->host:$port/demo/shout.php\r\n".
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn,$upgrade,strlen($upgrade));
    }

    private function check_credetenials

    private function send_message($msg)
    {
    	foreach ($this->clients as $changed_socket) {
    		@socket_write($changed_socket, $msg, strlen($msg));
    	}

    	return true;
    }

    private function send_message_to_users($users, $message)
    {
    	foreach ($users as $user) {
    		@socket_write($user, $message, strlen($msg));
        }

    	return true;
    }


    //Unmask incoming framed message
    private function unmask($text)
    {
    	$length = ord($text[1]) & 127;

    	if ($length == 126) {
    		$masks = substr($text, 4, 4);
    		$data = substr($text, 8);
    	} elseif ($length == 127) {
    		$masks = substr($text, 10, 4);
    		$data = substr($text, 14);
    	} else {
    		$masks = substr($text, 2, 4);
    		$data = substr($text, 6);
    	}

    	$text = "";
    	for ($i = 0; $i < strlen($data); ++$i) {
    		$text .= $data[$i] ^ $masks[$i%4];
    	}

    	return $text;
    }

    //Encode message for transfer to client.
    private function mask($text)
    {
    	$b1 = 0x80 | (0x1 & 0x0f);
    	$length = strlen($text);

    	if ($length <= 125) {
    		$header = pack('CC', $b1, $length);
        } elseif ($length > 125 && $length < 65536) {
    		$header = pack('CCn', $b1, 126, $length);
        } elseif ($length >= 65536) {
            $header = pack('CCNN', $b1, 127, $length);
        }

    	return $header.$text;
    }

}

(new SocketServer)->start();
