<?php
class AlbumController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT Album.AlbumId, Album.Title, Artist.Name AS Artist
            FROM Album
            JOIN Artist ON Album.ArtistId = Artist.ArtistId
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT Album.AlbumId, Album.Title, Artist.Name AS Artist
            FROM Album
            JOIN Artist ON Album.ArtistId = Artist.ArtistId
            WHERE Album.AlbumId = ?
        ");
        $stmt->execute([$id]);
        $album = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($album) {
            echo json_encode($album);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Album not found"]);
        }
    }

    public function create($title, $artist_id) {
        if (!$title || !$artist_id) {
            http_response_code(400);
            echo json_encode(["error" => "Title and artist_id are required"]);
            return;
        }
        $stmt = $this->db->prepare("INSERT INTO Album (Title, ArtistId) VALUES (?, ?)");
        $stmt->execute([$title, $artist_id]);
        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Track WHERE AlbumId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
            echo json_encode(["error" => "Album has tracks and cannot be deleted"]);
            return;
        }
        $stmt = $this->db->prepare("DELETE FROM Album WHERE AlbumId = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true]);
    }
}
?>
