<?php
require_once dirname(__DIR__) . '/config/app.php';
header('Content-Type: application/json');

// se APP_ROOT punta alla root del sito:
$baseDir = APP_ROOT . '/assets/galleries';

$folders = [];

if (is_dir($baseDir)) {
    foreach (scandir($baseDir) as $item) {
        if ($item === '.' || $item === '..') continue;
        if (is_dir($baseDir . '/' . $item)) {
            $folders[] = $item;
        }
    }
}

sort($folders);

echo json_encode([
    'success' => true,
    'folders' => $folders
]);