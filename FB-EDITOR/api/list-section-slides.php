<?php

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json');

$folder = $_GET['folder'] ?? '';
$folder = trim($folder, '/');

if ($folder === '') {
    echo json_encode([
        'success' => false,
        'error' => 'Cartella non specificata'
    ]);
    exit;
}

$baseDir =
    dirname(__DIR__) .
    '/templates/' .
    $folder;

if (!is_dir($baseDir)) {
    echo json_encode([
        'success' => false,
        'error' => 'Cartella non trovata'
    ]);
    exit;
}

$files = [];

foreach (scandir($baseDir) as $file) {

    if (
        $file === '.' ||
        $file === '..'
    ) {
        continue;
    }

    $extension =
        strtolower(
            pathinfo($file, PATHINFO_EXTENSION)
        );

    if ($extension === 'json') {
        $files[] = $file;
    }
}

sort($files, SORT_NATURAL | SORT_FLAG_CASE);

echo json_encode([
    'success' => true,
    'files' => $files
]);