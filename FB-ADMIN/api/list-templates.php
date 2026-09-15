<?php

require_once dirname(__DIR__, 2) . '/FB-EDITOR/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$templatesDir = APP_ROOT . '/FB-EDITOR/templates';

$templates = [];

foreach (glob($templatesDir . '/*.json') as $file) {
    $templates[] = basename($file, '.json');
}

sort($templates);

echo json_encode([
    'success' => true,
    'templates' => $templates
]);