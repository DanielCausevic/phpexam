<?php
class ArtistController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM artists");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM artists WHERE ArtistId = ?");
        $stmt->execute([$id]);
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($artist) {
            echo json_encode($artist);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Artist not found"]);
        }
    }

    public function create($name) {
        if (!$name) {
            http_response_code(400);
            echo json_encode(["error" => "Name is required"]);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO artists (Name) VALUES (?)");
        $stmt->execute([$name]);

        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }
}
?>