<?php
header('Content-Type: application/json; charset=utf-8');

function respond($success, $extra = []) {
    echo json_encode(array_merge(['success' => $success], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

$templateDir = dirname(__DIR__) . '/templates';
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data) || empty($data['name']) || !isset($data['section']) || !is_array($data['section'])) {
    respond(false, ['error' => 'Dati mancanti o non validi']);
}

$folder = preg_replace('/[^a-zA-Z0-9\-_]/', '-', trim($data['name']));
if ($folder === '') {
    respond(false, ['error' => 'Nome cartella non valido']);
}

$mode = $data['mode'] ?? 'new';
if (!in_array($mode, ['new', 'edit'], true)) {
    respond(false, ['error' => 'Modalità salvataggio non valida']);
}

$sliderDir = $templateDir . '/' . $folder;
if ($mode === 'edit') {
    $fileName = $data['slide'] ?? '';
    if (!is_string($fileName) || !preg_match('/^slide[0-9]+\.json$/', $fileName)) {
        respond(false, ['error' => 'Nome slide non valido']);
    }
    $filePath = $sliderDir . '/' . $fileName;
    if (!is_file($filePath)) {
        respond(false, ['error' => 'Slide originale non trovata']);
    }
} else {
    if (!is_dir($sliderDir) && !mkdir($sliderDir, 0777, true) && !is_dir($sliderDir)) {
        respond(false, ['error' => 'Impossibile creare la cartella']);
    }
    $nextNumber = 1;
    do {
        $fileName = 'slide' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT) . '.json';
        $filePath = $sliderDir . '/' . $fileName;
        $nextNumber++;
    } while (file_exists($filePath));
}

$existing = null;
if ($mode === 'edit') {
    $existing = json_decode(file_get_contents($filePath), true);
    if (!is_array($existing)) {
        respond(false, ['error' => 'JSON originale non valido']);
    }
}

$template = [
    'type' => 'section',
    'name' => pathinfo($fileName, PATHINFO_FILENAME),
    'created_at' => $existing['created_at'] ?? date('c'),
    'section' => $data['section']
];
if ($mode === 'edit') {
    $template['updated_at'] = date('c');
}

$json = json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    respond(false, ['error' => 'Errore generazione JSON: ' . json_last_error_msg()]);
}
if (file_put_contents($filePath, $json, LOCK_EX) === false) {
    respond(false, ['error' => 'Errore scrittura file']);
}

respond(true, [
    'folder' => $folder,
    'file' => '/FB-EDITOR/templates/' . $folder . '/' . $fileName,
    'mode' => $mode
]);
