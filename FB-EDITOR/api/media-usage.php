<?php
// FB-EDITOR/api/media-usage.php — verifica riferimenti, sola lettura
require_once dirname(__DIR__) . '/config/app.php';
header('Content-Type: application/json; charset=utf-8');

function respondUsage(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respondUsage(405, ['success' => false, 'error' => 'Metodo non consentito']);
}

$path = $_GET['path'] ?? '';
if (!is_string($path) || !preg_match('~^assets/(images|galleries)/[a-zA-Z0-9_. /-]+\.(?:jpe?g|png|webp|gif|svg)$~i', $path) || str_contains($path, '..') || str_contains($path, '//')) {
    respondUsage(400, ['success' => false, 'error' => 'Percorso non valido']);
}

$root = realpath(APP_ROOT . '/assets/' . explode('/', $path)[1]);
$real = realpath(APP_ROOT . '/' . $path);
if ($root === false || $real === false || !is_file($real) || is_link(APP_ROOT . '/' . $path) || !str_starts_with($real, $root . DIRECTORY_SEPARATOR)) {
    respondUsage(404, ['success' => false, 'error' => 'Immagine non trovata']);
}

require_once dirname(__DIR__) . '/lib/media-usage-check.php';
respondUsage(200, checkMediaUsage($path));
