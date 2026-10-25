<?php

header('Content-Type: application/json; charset=utf-8');

$templatesDir =
    dirname(__DIR__, 2) .
    '/FB-EDITOR/templates';

$folders = [];

if (is_dir($templatesDir)) {

    foreach (scandir($templatesDir) as $item) {

        if (
            $item === '.' ||
            $item === '..'
        ) {
            continue;
        }

        $path = $templatesDir . '/' . $item;

        if (is_dir($path)) {
            $folders[] = $item;
        }
    }
}

sort($folders);

echo json_encode([
    'success' => true,
    'folders' => $folders
]);