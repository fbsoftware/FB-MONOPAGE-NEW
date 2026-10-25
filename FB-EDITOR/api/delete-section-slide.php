<?php

header('Content-Type: application/json; charset=utf-8');

$templateDir = dirname(__DIR__) . '/templates';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (
    !$data ||
    empty($data['folder']) ||
    empty($data['file'])
) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati mancanti'
    ]);
    exit;
}


//===========================================
// NOME CARTELLA
//===========================================

$folder = preg_replace(
    '/[^a-zA-Z0-9\-_]/',
    '',
    trim($data['folder'])
);

if ($folder === '') {

    echo json_encode([
        'success' => false,
        'error' => 'Nome cartella non valido'
    ]);

    exit;
}


//===========================================
// NOME FILE
//===========================================

$file = preg_replace(
    '/[^a-zA-Z0-9\-_\.]/',
    '',
    trim($data['file'])
);

if (
    $file === '' ||
    !preg_match('/^slide[0-9]+\.json$/', $file)
) {

    echo json_encode([
        'success' => false,
        'error' => 'Nome slide non valido'
    ]);

    exit;
}


//===========================================
// PERCORSO SLIDE
//===========================================

$sliderDir = $templateDir . '/' . $folder;
$filePath = $sliderDir . '/' . $file;


//===========================================
// VERIFICA FILE
//===========================================

if (!is_file($filePath)) {

    echo json_encode([
        'success' => false,
        'error' => 'Slide non trovata'
    ]);

    exit;
}


//===========================================
// CANCELLAZIONE
//===========================================

if (!unlink($filePath)) {

    echo json_encode([
        'success' => false,
        'error' => 'Errore cancellazione file'
    ]);

    exit;
}


//===========================================
// RISPOSTA
//===========================================

echo json_encode([
    'success' => true,
    'folder' => $folder,
    'file' => $file
]);