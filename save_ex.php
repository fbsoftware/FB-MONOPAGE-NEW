<?php
header('Content-Type: application/json');

// ===== sicurezza base =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Metodo non consentito'
    ]);
    exit;
}

// ===== leggi input JSON =====
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'error' => 'JSON non valido'
    ]);
    exit;
}

// ===== recupera nome pagina =====
$page = $data['meta']['page'] ?? null;

if (!$page) {
    echo json_encode([
        'success' => false,
        'error' => 'Nome pagina mancante'
    ]);
    exit;
}

// sicurezza nome file
$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

// ===== path file =====
$dataDir = __DIR__ . '/data';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$filePath = $dataDir . '/' . $page . '.json';

// ===== aggiorna timestamp =====
$data['meta']['updated_at'] = date('c');

// ===== salva file =====
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($filePath, $json) === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura file'
    ]);
    exit;
}

// ===== risposta OK =====
echo json_encode([
    'success' => true,
    'file' => "data/$page.json"
]);