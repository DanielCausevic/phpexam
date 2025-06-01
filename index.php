<?php
require_once "Database.php";
require_once "ArtistController.php";
require_once "Logger.php";

Logger::log();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

$method = $_SERVER['REQUEST_METHOD'];

if ($uri[0] !== 'artists') {
    http_response_code(404);
    echo json_encode(["error" => "Not Found"]);
    exit;
}

$controller = new ArtistController();

if ($method === 'GET' && count($uri) === 1) {
    $controller->getAll();
} elseif ($method === 'GET' && count($uri) === 2) {
    $controller->getById($uri[1]);
} elseif ($method === 'POST' && count($uri) === 1) {
    $controller->create($_POST['name'] ?? null);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Bad Request"]);
}
?>