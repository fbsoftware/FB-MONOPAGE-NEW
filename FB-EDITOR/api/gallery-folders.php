<?php
require_once dirname(__DIR__) . '/config/app.php';
header('Content-Type: application/json');

// Cartella root del sito (FB-MONOPAGE-NEW), non editor
$baseDir = '../../assets/galleries';

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

error_log("Gallery folders found: " . implode(", ", $folders));

echo json_encode([
    'success' => true,
    'folders' => $folders
]);