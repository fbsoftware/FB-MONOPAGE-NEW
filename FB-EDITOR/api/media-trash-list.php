<?php
// FB-EDITOR/api/media-trash-list.php
// Elenco immagini nel cestino - sola lettura.

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function trashListReply(int $status, array $data): void {
    http_response_code($status);
    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

if (!in_array(
    $_SERVER['REMOTE_ADDR'] ?? '',
    ['127.0.0.1', '::1'],
    true
)) {
    trashListReply(403, [
        'success' => false,
        'error' => 'Disponibile solo da localhost'
    ]);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    trashListReply(405, [
        'success' => false,
        'error' => 'Metodo non consentito'
    ]);
}

$trashDir = APP_ROOT . '/data/media-trash';
$items = [];

if (!is_dir($trashDir)) {
    trashListReply(200, [
        'success' => true,
        'count' => 0,
        'images' => []
    ]);
}

if (!is_readable($trashDir)) {
    trashListReply(500, [
        'success' => false,
        'error' => 'Cestino non leggibile'
    ]);
}

foreach (new DirectoryIterator($trashDir) as $entry) {
    if ($entry->isDot() || $entry->isLink()) {
        continue;
    }

    if (!$entry->isFile() ||
        strtolower($entry->getExtension()) !== 'json') {
        continue;
    }

    $id = $entry->getBasename('.json');

    if (!preg_match('/^[a-f0-9]{32}$/D', $id)) {
        continue;
    }

    $bin = $trashDir . '/' . $id . '.bin';

    if (!is_file($bin) || is_link($bin)) {
        continue;
    }

    $content = @file_get_contents($entry->getPathname());

    if ($content === false) {
        continue;
    }

    $data = json_decode($content, true);

    if (!is_array($data) ||
        ($data['id'] ?? null) !== $id) {
        continue;
    }

    $path = $data['original_path'] ?? '';

    if (!is_string($path) ||
        !preg_match(
            '~^assets/(images|galleries)/[a-zA-Z0-9_./ -]+\.(jpe?g|png|webp|gif|svg)$~iD',
            $path
        ) ||
        str_contains($path, '..') ||
        str_contains($path, '//') ||
        str_contains($path, '/./')) {
        continue;
    }

    $items[] = [
        'id' => $id,
        'name' => basename($path),
        'original_path' => $path,
        'deleted_at' => $data['deleted_at'] ?? '',
        'size' => filesize($bin)
    ];
}

usort($items, function ($a, $b) {
    return strcmp(
        $b['deleted_at'],
        $a['deleted_at']
    );
});

trashListReply(200, [
    'success' => true,
    'count' => count($items),
    'images' => $items
]);
