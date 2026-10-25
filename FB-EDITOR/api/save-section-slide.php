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


//===========================================
// NOME CARTELLA
//===========================================

$folder = preg_replace(
    '/[^a-zA-Z0-9\-_]/',
    '-',
    trim($data['name'])
);

if ($folder === '') {
    echo json_encode([
        'success' => false,
        'error' => 'Nome cartella non valido'
    ]);
    exit;
}


//===========================================
// CARTELLA SLIDER
//===========================================

$sliderDir = $templateDir . '/' . $folder;

if (!is_dir($sliderDir)) {

    if (!mkdir($sliderDir, 0777, true)) {

        echo json_encode([
            'success' => false,
            'error' => 'Impossibile creare la cartella'
        ]);

        exit;
    }
}


//===========================================
// TROVA IL PROSSIMO NUMERO SLIDE
//===========================================

$nextNumber = 1;

while (true) {

    $fileName =
        'slide' .
        str_pad($nextNumber, 2, '0', STR_PAD_LEFT) .
        '.json';

    $filePath = $sliderDir . '/' . $fileName;

    if (!file_exists($filePath)) {
        break;
    }

    $nextNumber++;
}


//===========================================
// DATI TEMPLATE
//===========================================

$template = [
    'type' => 'section',
    'name' => pathinfo($fileName, PATHINFO_FILENAME),
    'created_at' => date('c'),
    'section' => $data['section']
];


//===========================================
// JSON
//===========================================

$json = json_encode(
    $template,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);


//===========================================
// SALVATAGGIO
//===========================================

if (file_put_contents($filePath, $json) === false) {

    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura file'
    ]);

    exit;
}


//===========================================
// RISPOSTA
//===========================================

echo json_encode([
    'success' => true,
    'folder' => $folder,
    'file' => '/FB-EDITOR/templates/' . $folder . '/' . $fileName
]);

exit;