<?php

require_once dirname(__DIR__, 2) . '/FB-EDITOR/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

$template = trim($data['template'] ?? '');

if ($template === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Template non valido'
    ]);
    exit;
}

if (!preg_match('/^[A-Za-z0-9_-]+$/', $template)) {
    echo json_encode([
        'success' => false,
        'message' => 'Nome template non valido'
    ]);
    exit;
}

$file = APP_ROOT .
        '/FB-EDITOR/templates/' .
        $template .
        '.json';

if (!file_exists($file)) {
    echo json_encode([
        'success' => false,
        'message' => 'Template non trovato'
    ]);
    exit;
}

if (!unlink($file)) {
    echo json_encode([
        'success' => false,
        'message' => 'Impossibile cancellare il template'
    ]);
    exit;
}

echo json_encode([
    'success' => true
]);