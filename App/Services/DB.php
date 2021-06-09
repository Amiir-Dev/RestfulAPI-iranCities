<?php

namespace App\Services;

class DB extends \PDO
{
    private static $host = '127.0.0.1';
    private static $dbName = 'iran';
    private static $username = 'root';
    private static $pass = '';

    public static function connect()
    {
        try {
            return new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbName, self::$username, self::$pass);
        } catch (\PDOException $e) {
            return $e;
        }
    }
}
