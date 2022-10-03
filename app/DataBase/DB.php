<?php
namespace App\DataBase;

use PDO;

class DB{
 /*   private static $host = "localhost";
    private static $user = "root";
    private static $pass = "root";
    private static $dbname = "gamedb";
    private static $charset = 'utf8mb4';*/

    public PDO $pdo;

    public function __construct($db, $username = 'root', $password = 'root', $host = 'localhost', $port = 3307, $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $options);
        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function run($sql, $args = NULL): bool|\PDOStatement
    {
        if (!$args)
        {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

}
