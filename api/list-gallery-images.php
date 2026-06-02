<?php

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json');

$folder = $_GET['folder'] ?? 'portfolio';
$folder = trim($folder, '/');
$folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', $folder);

$galleryDir = APP_ROOT . '/assets/galleries/' . $folder;

$images = [];

if (is_dir($galleryDir)) {

    $files = scandir($galleryDir);
    sort($files);

    foreach ($files as $file) {

        if ($file === '.' || $file === '..') {
            continue;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $images[] = ASSETS_URL . '/galleries/' . $folder . '/' . $file;
        }
    }
}

echo json_encode([
    'success' => true,
    'folder' => $folder,
    'images' => $images
]);