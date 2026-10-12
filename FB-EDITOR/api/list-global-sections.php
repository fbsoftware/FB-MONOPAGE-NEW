<?php

require_once __DIR__ . '/../config/app.php';

header('Content-Type: application/json');

$siteDir = APP_ROOT . '/data/site';

$globals = [];

$files = [
    'header' => 'Header',
    'footer' => 'Footer'
];

foreach ($files as $type => $name) {

    $path = $siteDir . '/' . $type . '.json';

    if (file_exists($path)) {

        $globals[] = [
            'type' => $type,
            'name' => $name
        ];

    }
}

echo json_encode([
    'success' => true,
    'globals' => $globals
]);