<?php

abstract class DB {
    private static $pdo;
    private static $host = "localhost";
    private static $dbname = "nft_marketplace";
    private static $username = "root";
    private static $password = "";

    public  function connect() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4", self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public  function disconnect() {
        self::$pdo = null;
        echo "DB Connection closed";
    }
}