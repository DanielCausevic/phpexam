<?php

class GenresController {

    private $db;
    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }
    public function getGenres(){
        $stmt = $this->db->query("SELECT * FROM Genre");
        $stmt->$execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
?>