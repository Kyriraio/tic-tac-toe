<?php
namespace App\DataBase;

use PDO;

abstract class DataBase{
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "root";
    private static $dbname = "gamedb";
    private static $charset = 'utf8mb4';

    public static function connect(){
        $dsn = "mysql:host=".self::$host.";port=3307;dbname=".self::$dbname.";charset=".self::$charset;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            return new PDO($dsn, self::$user, self::$pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

    }
}
