<?php
//=============================================
// FB-EDITOR - API caricamento Media
//=============================================

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');

function mediaUploadResponse($success, $message, $status = 200) {
    http_response_code($status);
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    mediaUploadResponse(false, 'Metodo non consentito', 405);
}

// IMPORTANTE: proteggere questo endpoint con
// l'autenticazione amministrativa del progetto,
// prima di esporlo su un server pubblico.

if (!isset($_FILES['image'])) {
    mediaUploadResponse(false, 'Nessuna immagine ricevuta', 400);
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    mediaUploadResponse(false, 'Errore durante il caricamento', 400);
}

if ($file['size'] > 10 * 1024 * 1024) {
    mediaUploadResponse(false, 'Dimensione massima: 10 MB', 400);
}

$folder = trim(
    str_replace('\\', '/', $_POST['folder'] ?? ''),
    '/'
);

$parts = explode('/', $folder);

if (
    count($parts) < 1 ||
    !in_array($parts[0], ['images', 'galleries'], true)
) {
    mediaUploadResponse(false, 'Cartella non autorizzata', 400);
}

foreach ($parts as $part) {
    if (
        $part === '' ||
        $part === '.' ||
        $part === '..' ||
        !preg_match('/^[a-zA-Z0-9_-]+$/', $part)
    ) {
        mediaUploadResponse(false, 'Nome cartella non valido', 400);
    }
}

$base = realpath(APP_ROOT . '/assets');

if ($base === false) {
    mediaUploadResponse(false, 'Archivio Media non disponibile', 500);
}

$targetDir = $base . DIRECTORY_SEPARATOR .
    implode(DIRECTORY_SEPARATOR, $parts);

if (!is_dir($targetDir)) {
    mediaUploadResponse(false, 'Cartella inesistente', 400);
}

$realTarget = realpath($targetDir);

if (
    $realTarget === false ||
    !str_starts_with(
        $realTarget,
        $base . DIRECTORY_SEPARATOR
    )
) {
    mediaUploadResponse(false, 'Percorso non autorizzato', 400);
}

$filename = basename($file['name']);
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

$allowed = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
    'gif' => 'image/gif'
];

if (!isset($allowed[$extension])) {
    mediaUploadResponse(false, 'Formato immagine non consentito', 400);
}

$mime = (new finfo(FILEINFO_MIME_TYPE))
    ->file($file['tmp_name']);

if ($mime !== $allowed[$extension] ||
    @getimagesize($file['tmp_name']) === false) {
    mediaUploadResponse(false, 'Il file non è un’immagine valida', 400);
}

if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
    mediaUploadResponse(false, 'Nome file non valido', 400);
}

$destination = $realTarget . DIRECTORY_SEPARATOR . $filename;

if (file_exists($destination)) {
    mediaUploadResponse(false, 'Esiste già un file con questo nome', 409);
}

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    mediaUploadResponse(false, 'Impossibile salvare il file', 500);
}

mediaUploadResponse(true, 'Immagine caricata correttamente');