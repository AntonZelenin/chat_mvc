<?php namespace ZChat\Core;

/**
 * Class DatabasePDO
 * @package ZChat\Core
 */
class DatabasePDO {
    // PHP Data Objects extension

    /**
     * @var null|\PDO
     */
    protected $connection = null;

    /**
     * DatabasePDO constructor.
     */
    public function __construct() {
        $conf = parse_ini_file(ROOT.'/app/config/db_params.ini', true)['chat_database'];

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
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            // Плюс очень удобно задать FETCH_MODE по умолчанию, чтобы не писать его в КАЖДОМ запросе
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            // эмуляция подготовленных выражений
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new \PDO($dsn, $user, $password, $opt);
    }

    /**
     * @param string $query
     * @return \PDOStatement
     */
    public function query(string $query){
        return $this->connection->query($query);
    }

    /**
     * @return null|\PDO
     */
    function get_connection() {
        return $this->connection;
    }
}
