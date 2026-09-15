<?php

require_once dirname(__DIR__, 2) . '/FB-EDITOR/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$pages = [];

foreach (glob(DATA_DIR . '/*.json') as $file) {

    $name = basename($file, '.json');

    // escludi file tecnici
    if ($name === 'site-menu') {
        continue;
    }

    $pages[] = [
        "title" => ucwords(str_replace(["-", "_"], " ", $name)),
        "page"  => $name,
        "file"  => basename($file)
    ];
}

echo json_encode([
    "success" => true,
    "pages" => $pages
]);

exit;