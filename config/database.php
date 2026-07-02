<?php
// Configuration Database

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'rekaptugas');
}

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // In case of error, try without dbname first to allow setup scripts to run
            try {
                $dsn_no_db = 'mysql:host=' . $this->host . ';charset=utf8mb4';
                $this->dbh = new PDO($dsn_no_db, $this->user, $this->pass, $options);
            } catch (PDOException $ex) {
                die("Database Connection Error: " . $ex->getMessage());
            }
        }
    }

    public function getConnection() {
        return $this->dbh;
    }
}
