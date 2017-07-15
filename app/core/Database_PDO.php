<?php

class Database_PDO {
    // PHP Data Objects extansion
    private $connection = null;

    public function __construct() {
        // $conf = parse_ini_file('c:\wamp64\www\chat_mvc\app\config\db_params.ini', true);
        $conf = parse_ini_file(ROOT.'\\app\\config\\db_params.ini', true);
        $conf = $conf['chat_database'];

        $host = $conf['host'];
        $database = $conf['database'];
        $user = $conf['user'];
        $password = $conf['password'];
        $charset = $conf['charset'];

        $dsn = "mysql:host=$host; dbname=$database; charset=$charset";
        $opt = [
            // Самое главное - режим выдачи ошибок надо задавать только в виде исключений.
            // - Во-первых, потому что во всех остальных режимах PDO не сообщает об ошибке ничего внятного,
            // - во-вторых, потому что исключение всегда содержит в себе незаменимый stack trace,
            // - в-третьих - исключения чрезвычайно удобно обрабатывать.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Плюс очень удобно задать FETCH_MODE по умолчанию, чтобы не писать его в КАЖДОМ запросе
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // эмуляция подготовленных выражений
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new PDO($dsn, $user, $password, $opt);
    }

    public function query($query){
        return $this->connection->query($query);
    }

    function get_connection() {
        return $this->connection;
    }

    function __destruct() {
        $this->connection = null;
    }

}
