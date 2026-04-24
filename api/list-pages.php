<?php
header('Content-Type: application/json');

$rootDir = dirname(__DIR__);
$dataDir = $rootDir . '/data';

if (!is_dir($dataDir)) {
    echo json_encode([
        'success' => false,
        'error' => 'Cartella data non trovata'
    ]);
    exit;
}

$files = scandir($dataDir);
$pages = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    if (pathinfo($file, PATHINFO_EXTENSION) !== 'json') continue;

    if ($file === 'site-menu.json') continue;

    $slug = pathinfo($file, PATHINFO_FILENAME);

    $pages[] = [
        'title' => ucwords(str_replace(['-', '_'], ' ', $slug)),
        'page' => $slug,
        'file' => $file
    ];
}

echo json_encode([
    'success' => true,
    'pages' => $pages
]);
exit;