<?php
//=================================
//  CANCELLA PAGINA
//=================================
require_once dirname(__DIR__, 2) . '/FB-EDITOR/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

$page = $data['page'] ?? '';

$page = trim($page);

if ($page === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Pagina non valida'
    ]);
    exit;
}

// sicurezza: accettiamo solo lettere, numeri, trattino e underscore
if (!preg_match('/^[A-Za-z0-9_-]+$/', $page)) {
    echo json_encode([
        'success' => false,
        'message' => 'Nome pagina non valido'
    ]);
    exit;
}

$jsonFile = DATA_DIR . '/' . $page . '.json';
$htmlFile = PAGES_DIR . '/' . $page . '.html';

if (file_exists($jsonFile)) {
    unlink($jsonFile);
}

if (file_exists($htmlFile)) {
    unlink($htmlFile);
}

echo json_encode([
    'success' => true
]);