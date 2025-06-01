<?php
class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            self::$conn = new PDO("mysql:host=localhost;dbname=chinook", "root", "");
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}
?>