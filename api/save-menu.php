<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Metodo non consentito'
    ]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['menu']) || !is_array($data['menu'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Menu non valido'
    ]);
    exit;
}

$rootDir = dirname(__DIR__);
$dataDir = 'FB-JSON/data';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$menuPath = $rootDir . '/data/site-menu.json';
$json = json_encode($data['menu'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($menuPath, $json) === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura menu'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'file' => 'data/site-menu.json'
]);