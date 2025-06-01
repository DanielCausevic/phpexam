<?php
class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            self::$conn = new PDO("mysql:host=trolley.proxy.rlwy.net;port=21721;dbname=railway", "root", "GBxVbkmAGpfEcuFSYiOWJxOuBQKeiWxm");
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}
?>