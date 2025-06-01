<?php
class TrackController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        header("Content-Type: application/json");
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT t.Name, t.Composer, g.Name AS Genre, m.Name AS MediaType
            FROM tracks t
            JOIN genres g ON t.GenreId = g.GenreId
            JOIN media_types m ON t.MediaTypeId = m.MediaTypeId
            WHERE t.TrackId = ?
        ");
        $stmt->execute([$id]);
        $track = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($track) {
            echo json_encode($track);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Track not found"]);
        }
    }

    public function create($data) {
        $required = ['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(["error" => "Missing field: $field"]);
                return;
            }
        }
        $stmt = $this->db->prepare("
            INSERT INTO tracks (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'], $data['album_id'], $data['media_type_id'], $data['genre_id'],
            $data['composer'], $data['milliseconds'], $data['bytes'], $data['unit_price']
        ]);
        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }
}
?>