<?php
class Logger {
    public static function log() {
        $log = "[" . date("Y-m-d H:i:s") . "] " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . "\n";
        file_put_contents("api.log", $log, FILE_APPEND);
    }
}
?>