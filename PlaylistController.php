<?php
class PlaylistController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM playlists");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create($name) {
        if (!$name) {
            http_response_code(400);
            echo json_encode(["error" => "Name is required"]);
            return;
        }
        $stmt = $this->db->prepare("INSERT INTO playlists (Name) VALUES (?)");
        $stmt->execute([$name]);
        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM playlist_track WHERE PlaylistId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
            echo json_encode(["error" => "Playlist contains tracks and cannot be deleted"]);
            return;
        }
        $stmt = $this->db->prepare("DELETE FROM playlists WHERE PlaylistId = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true]);
    }
}
?>