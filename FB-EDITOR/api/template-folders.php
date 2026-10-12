<?php

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json');

$baseDir = dirname(__DIR__) . '/templates';

$folders = [];

if (is_dir($baseDir)) {

    foreach (scandir($baseDir) as $item) {

        if ($item === '.' || $item === '..') {
            continue;
        }

        if (is_dir($baseDir . '/' . $item)) {
            $folders[] = $item;
        }
    }
}

sort($folders);

error_log(
    "Template folders found: " .
    implode(", ", $folders)
);

echo json_encode([
    'success' => true,
    'folders' => $folders
]);