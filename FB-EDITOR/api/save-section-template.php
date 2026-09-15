<?php

header('Content-Type: application/json; charset=utf-8');

$templateDir = dirname(__DIR__) . '/templates';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || empty($data['name']) || empty($data['section'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati mancanti'
    ]);
    exit;
}

$name = preg_replace(
    '/[^a-zA-Z0-9\-_]/',
    '-',
    trim($data['name'])
);

$overwrite = !empty($data['overwrite']);

if (!is_dir($templateDir)) {
    mkdir($templateDir, 0777, true);
}

$path = $templateDir . '/' . $name . '.json';


//===========================================
// TEMPLATE GIÀ ESISTENTE
//===========================================
if (file_exists($path) && !$overwrite) {

    echo json_encode([
        'success' => false,
        'exists' => true
    ]);

    exit;
}


//===========================================
// SALVATAGGIO TEMPLATE
//===========================================
$template = [
    'type' => 'section',
    'name' => $name,
    'created_at' => date('c'),
    'section' => $data['section']
];

$json = json_encode(
    $template,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);

if (file_put_contents($path, $json) === false) {

    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura file'
    ]);

    exit;
}

echo json_encode([
    'success' => true,
    'file' => '/FB-EDITOR/templates/' . $name . '.json'
]);

exit;