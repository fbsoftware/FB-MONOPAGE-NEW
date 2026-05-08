<?php
header('Content-Type: application/json');

$rootDir = dirname(__DIR__);
$templateDir = $rootDir . '/data/templates/sections';

$templates = [];

if (is_dir($templateDir)) {
    foreach (glob($templateDir . '/*.json') as $file) {
        $json = file_get_contents($file);
        $data = json_decode($json, true);

        $templates[] = [
            'name' => $data['name'] ?? basename($file, '.json'),
            'file' => basename($file)
        ];
    }
}

echo json_encode([
    'success' => true,
    'templates' => $templates
]);
exit;