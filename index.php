<?php
require_once "Database.php";
require_once "ArtistController.php";
require_once "AlbumController.php";
require_once "TrackController.php";
require_once "PlaylistController.php";
require_once "GenresController.php";
require_once "Logger.php";

Logger::log();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));
$method = $_SERVER['REQUEST_METHOD'];

header("Content-Type: application/json");

switch ($uri[0]) {
    case 'artists':
        $controller = new ArtistController();
        if ($method === 'GET' && count($uri) === 1) {
            $controller->getAll();
        } elseif ($method === 'GET' && count($uri) === 2) {
            $controller->getById($uri[1]);
        } elseif ($method === 'POST' && count($uri) === 1) {
            $controller->create($_POST['name'] ?? null);
        } elseif ($method === 'DELETE' && count($uri) === 2) {
            $controller->delete($uri[1]);
        } elseif ($method === 'GET' && count($uri) === 3 && $uri[2] === 'albums') {
            $controller->getAlbumsByArtist($uri[1]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request"]);
        }
        break;

    case 'albums':
        $controller = new AlbumController();
        if ($method === 'GET' && count($uri) === 1) {
            $controller->getAll();
        } elseif ($method === 'GET' && count($uri) === 2) {
            $controller->getById($uri[1]);
        } elseif ($method === 'POST' && count($uri) === 1) {
            $controller->create($_POST['title'] ?? null, $_POST['artist_id'] ?? null);
        } elseif ($method === 'DELETE' && count($uri) === 2) {
            $controller->delete($uri[1]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request"]);
        }
        break;

    case 'tracks':
        $controller = new TrackController();
        if ($method === 'GET' && count($uri) === 2) {
            $controller->getById($uri[1]);
        } elseif ($method === 'POST' && count($uri) === 1) {
            $controller->create($_POST);
        } elseif ($method === 'DELETE' && count($uri) === 2) {
            $controller->delete($uri[1]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request"]);
        }
        break;


    case 'playlists':
        $controller = new PlaylistController();
        if ($method === 'GET' && count($uri) === 1) {
            $controller->getAll();
        } elseif ($method === 'POST' && count($uri) === 1) {
            $controller->create($_POST['name'] ?? null);
        } elseif ($method === 'DELETE' && count($uri) === 2) {
            $controller->delete($uri[1]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request"]);
        }
        break;
    case 'genres':
        $controller = new TrackController();
        if ($method === 'GET' && count($uri) === 1) {
            $controller->getGenres();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Not Found"]);
}
?>
