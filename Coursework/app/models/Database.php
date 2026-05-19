<?php
// app/models/Database.php

class Database {
    private static $dbHost = 'localhost';
    private static $dbName = 'news_db';
    private static $dbUser = 'root';
    private static $dbPass = '';
    private static $cont   = null;

    // Забороняємо створення обʼєктів цього класу зовні
    private function __construct() { }

    public static function connect() {
        if (self::$cont === null) {
            try {
                $dsn = 'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName . ';charset=utf8';
                self::$cont = new PDO($dsn, self::$dbUser, self::$dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die('Database Connection Error: ' . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }
}
