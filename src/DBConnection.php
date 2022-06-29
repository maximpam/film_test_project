<?php

namespace App;

use PDO;

class DBConnection
{
    public static string $host;
    public static string $user;
    public static string $password;
    public static string $db_name;
    public static PDO $pdo;

    public function __construct()
    {
        $config = include('config.php');
        self::$host = $config['host'];
        self::$user = $config['user'];
        self::$password = $config['password'];
        self::$db_name = $config['db_name'];

        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db_name;
        self::$pdo = new PDO($dsn, self::$user, self::$password);
        self::$pdo->exec("set names utf8mb4");
    }


}