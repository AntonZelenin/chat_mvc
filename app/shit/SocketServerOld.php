<?php
require_once(ROOT.'\\app\\core\\Autoload.php');

class SocketServer
{
    private $database_connection;

    private $socket;
    private $host = 'localhost'; //host
    private $null = NULL;
    private $port = '9000'; //port
    private $clients = array();
    private $socket_container;

    private $message_saver;

    public function __construct()
    {
        $this->database_connection = (new Database_PDO)->get_connection();
        $this->message_saver = new TextMessageSaver($this->database_connection);

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
        $this->socket_container = new ResourceContainer;
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
        	socket_select($changed, $null, $null, 0, 10);

        	//check for new socket
        	if ($this->is_new_socket($changed)) {
        		$new_socket = socket_accept($this->socket); //accpet new socket
        		$headers = socket_read($new_socket, 1024); //read data sent by the socket

                $headers = $this->parse_headers($headers);
        		$this->perform_handshaking($headers, $new_socket); //perform websocket handshake

                if (!isset($headers['Cookie']) || !(new CookieChecker($this->database_connection))->is_valid_cookie($headers['Cookie'])) {
                    socket_close($new_socket);
                    continue;
                }

                $this->clients[] = $new_socket;
                $this->socket_container->add_socket($new_socket);

        		//make room for new socket
        		$found_socket = array_search($this->socket, $changed);
        		unset($changed[$found_socket]);
        	}

        	//loop through all connected sockets
        	foreach ($changed as $changed_socket) {
        		while (socket_recv($changed_socket, $buf, 1024, 0) >= 1) {
        			$received_data = $this->unmask($buf);
        			$received_data = json_decode($received_data);

        			$message = new TextMessage;
                    $message->set_sender_id($received_data->sender_id);
                    $message->set_group_id($received_data->group_id);
                    $message->set_text($received_data->text);

                    $this->message_saver->to_database($message);

        			$response_text = $this->mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color, 'user_id' => $user_id)));
        			$this->send_message($response_text); //send data
        			break 2;
        		}

                $this->check_disconnected_clients($changed_socket);
        	}
        }
    }

    private function is_new_socket($changed) : bool
    {
        return (in_array($this->socket, $changed)) ? true : false;
    }

    private function parse_headers(string $recieved_header) : array
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

    private function perform_handshaking($recieved_headers, $client_conn)
    {
        $secKey = $recieved_headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "WebSocket-Origin: $this->host\r\n" .
        "WebSocket-Location: ws://$this->host:$this->port/demo/shout.php\r\n".
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn, $upgrade, strlen($upgrade));
    }

    private function send_message(string $message) : bool
    {
    	foreach ($this->clients as $changed_socket) {
    		@socket_write($changed_socket, $message, strlen($message));
    	}

    	return true;
    }

    private function send_message_to_users(array $users, string $message) : bool
    {
    	foreach ($users as $user) {
    		@socket_write($user, $message, strlen($message));
        }

    	return true;
    }

    private function unmask(string $text) : string
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

    private function mask(string $text) : string
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

    private function check_disconnected_clients($changed_socket)
    {
        $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
        if ($buf === false) {
            // check disconnected client
            // remove client for $this->clients array
            $found_socket = array_search($changed_socket, $this->clients);
            unset($this->clients[$found_socket]);
        }
    }

}
