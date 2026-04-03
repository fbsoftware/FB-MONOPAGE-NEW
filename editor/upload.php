<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Metodo non consentito'
    ]);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Nessun file ricevuto'
    ]);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore upload: ' . $file['error']
    ]);
    exit;
}

$allowedMime = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowedMime[$mime])) {
    echo json_encode([
        'success' => false,
        'error' => 'Formato non consentito'
    ]);
    exit;
}

$ext = $allowedMime[$mime];

$uploadDir = dirname(__DIR__) . '/uploads';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$baseName = pathinfo($file['name'], PATHINFO_FILENAME);
$baseName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $baseName);
$baseName = trim($baseName, '-');

if ($baseName === '') {
    $baseName = 'img';
}

$filename = $baseName . '-' . time() . '.' . $ext;
$targetPath = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        'success' => false,
        'error' => 'Impossibile salvare il file'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'path' => 'uploads/' . $filename,
    'url'  => '../uploads/' . $filename
]);