<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//===========================================
// DETERMINA NOME FILE SLIDE
//===========================================

$mode = $data['mode'] ?? 'new';

if ($mode === 'edit') {

    $fileName = $data['slide'] ?? '';

    // Accetta soltanto nomi slideNN.json
    if (!preg_match('/^slide[0-9]+\.json$/', $fileName)) {

        echo json_encode([
            'success' => false,
            'error' => 'Nome slide non valido'
        ]);
        exit;
    }

    $filePath = $sliderDir . '/' . $fileName;

    if (!is_file($filePath)) {

        echo json_encode([
            'success' => false,
            'error' => 'Slide originale non trovata'
        ]);
        exit;
    }

} elseif ($mode === 'new') {

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

} else {

    echo json_encode([
        'success' => false,
        'error' => 'Modalità salvataggio non valida'
    ]);
    exit;
}
