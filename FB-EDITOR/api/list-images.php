<?php
require_once dirname(__DIR__) . '/config/app.php';
header('Content-Type: application/json');

$imagesDir = APP_ROOT . '/assets/images';

if (!is_dir($imagesDir)) {
    echo json_encode([
        'success' => false,
        'error' => 'Cartella immagini non trovata'
    ]);
    exit;
}

$files = scandir($imagesDir);

$images = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) {
        $images[] = [
            'file' => 'assets/images/' . $file,
            'name' => $file
        ];
    }
}

echo json_encode([
    'success' => true,
    'images' => $images
]);