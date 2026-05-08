<?php
header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || empty($data['name']) || empty($data['section'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati mancanti'
    ]);
    exit;
}

$name = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $data['name']);

$rootDir = dirname(__DIR__);
$templateDir = $rootDir . '/data/templates/sections';

if (!is_dir($templateDir)) {
    mkdir($templateDir, 0777, true);
}

$template = [
    'type' => 'section',
    'name' => $name,
    'created_at' => date('c'),
    'section' => $data['section']
];

$path = $templateDir . '/' . $name . '.json';

$json = json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($path, $json) === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura file'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'file' => 'data/templates/sections/' . $name . '.json'
]);
exit;