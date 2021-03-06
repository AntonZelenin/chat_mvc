<?php

class ChatWebSocketServer extends WebSocketServer
{
    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

    protected function process($user, $data)
    {
        $data = (json_decode($data));

        $recipient_id = $data->recipient_id;
        $message = $data->message;

        $recipient = $this->getUserByDatabaseId($recipient_id);

        if($recipient !== null) {
            $this->send($recipient, $message);
        } else {
            throw new Exception("Recipient is NULL", 1);
        }
    }

    protected function connected($user)
    {
        $headers = $user->get_headers();

        if (!isset($headers['cookie']) || !(new CookieChecker(new Database_PDO))->is_valid_cookie($headers['cookie'])) {
            $this->disconnect($user->get_socket());
        }

        $id = (new Converter(new Database_PDO))->id_by_cookie($headers['cookie']);
        $user->set_database_id($id);
    }

    protected function closed($user)
    {
        // Do nothing: This is where cleanup would go, in case the user had any sort of
        // open files or other objects associated with them.  This runs after the socket
        // has been closed, so there is no need to clean up the socket itself here.
    }
}
