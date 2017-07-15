<?php

class TextMessageSaver
{
    private $database_connection;

    public function __construct(Database_PDO $database)
    {
        $this->database_connection = $database->get_connection();
    }

    public function to_database(TextMessage $message)
    {
        $query = $this->database_connection->prepare(
            "INSERT INTO chat_messages (group_id, content, sender_id)
            VALUES (:group_id, :content, :sender_id)"
        );

        $params = [
            'group_id' => $message->get_group_id(),
            'message_text' => $message->get_text();
            'sender_id' => $message->get_sender_id();
        ];

        $query->execute($params);

    }
}
