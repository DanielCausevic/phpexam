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
            FROM Track t
            JOIN Genre g ON t.GenreId = g.GenreId
            JOIN MediaType m ON t.MediaTypeId = m.MediaTypeId
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
    public function getGenres(){
        
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
            INSERT INTO Track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'], $data['album_id'], $data['media_type_id'], $data['genre_id'],
            $data['composer'], $data['milliseconds'], $data['bytes'], $data['unit_price']
        ]);
        http_response_code(201);
        echo json_encode(["success" => true, "id" => $this->db->lastInsertId()]);
    }
    public function delete($id) {
    // Check if the track is part of any playlist
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM PlaylistTrack WHERE TrackId = ?");
    $stmt->execute([$id]);

    if ($stmt->fetchColumn() > 0) {
        http_response_code(400);
        echo json_encode(["error" => "Track is in a playlist and cannot be deleted"]);
        return;
    }

    // Delete the track
    $stmt = $this->db->prepare("DELETE FROM Track WHERE TrackId = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode(["success" => true]);
}

}
?>
