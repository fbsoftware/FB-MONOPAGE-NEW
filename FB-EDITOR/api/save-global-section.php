<?php

header('Content-Type: application/json');

$data = json_decode(
    file_get_contents('php://input'),
    true
);

if (!is_array($data)) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati JSON non validi'
    ]);
    exit;
}

$type = $data['type'] ?? '';
$section = $data['section'] ?? null;

if (!in_array($type, ['header', 'footer'], true)) {
    echo json_encode([
        'success' => false,
        'error' => 'Tipo globale non valido'
    ]);
    exit;
}

if (!is_array($section)) {
    echo json_encode([
        'success' => false,
        'error' => 'Sezione non valida'
    ]);
    exit;
}


/*
 * =========================================
 * Cartella globali del sito
 * =========================================
 */

$globalDir = dirname(__DIR__, 2) . '/data/site';

if (!is_dir($globalDir)) {

    if (!mkdir($globalDir, 0777, true)) {

        echo json_encode([
            'success' => false,
            'error' => 'Impossibile creare la cartella data/site'
        ]);

        exit;
    }
}


/*
 * =========================================
 * File destinazione
 * =========================================
 */

$file = $globalDir . '/' . $type . '.json';


/*
 * =========================================
 * Struttura del globale
 * =========================================
 */

$output = [
    'type' => $type,
    'section' => $section
];


$json = json_encode(
    $output,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

if ($json === false) {

    echo json_encode([
        'success' => false,
        'error' => 'Errore nella conversione JSON'
    ]);

    exit;
}


/*
 * =========================================
 * Salvataggio
 * =========================================
 */

if (file_put_contents($file, $json) === false) {

    echo json_encode([
        'success' => false,
        'error' => 'Impossibile salvare il file'
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'type' => $type,
    'file' => $file
]);