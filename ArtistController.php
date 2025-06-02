<?php
class ArtistController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM Artist");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Artist WHERE ArtistId = ?");
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

        $stmt = $this->db->prepare("INSERT INTO Artist (Name) VALUES (?)");
        $stmt->execute([$name]);

        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }
    public function delete($id) {
        // Check if artist has albums
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Album WHERE ArtistId = ?");
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
        echo json_encode(["error" => "Artist has albums and cannot be deleted"]);
        return;
    }

    // Delete artist
    $stmt = $this->db->prepare("DELETE FROM Artist WHERE ArtistId = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode(["success" => true]);
}

}
?>
