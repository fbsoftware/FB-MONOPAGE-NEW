<?php
//=============================================
// FB-EDITOR - API sostituzione immagine
//=============================================

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');

function mediaReplaceResponse($success, $message, $status = 200)
{
    http_response_code($status);

    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);

    exit;
}

// IMPORTANTE:
// Inserire qui il controllo di autenticazione
// e autorizzazione amministrativa già utilizzato
// dal progetto, prima di esporre questa API.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    mediaReplaceResponse(false, 'Metodo non consentito', 405);
}

//=============================================
// Controllo parametri
//=============================================

$path = $_POST['path'] ?? '';

if (!is_string($path) || $path === '') {
    mediaReplaceResponse(false, 'Percorso mancante', 400);
}

if (
    !isset($_FILES['image']) ||
    $_FILES['image']['error'] !== UPLOAD_ERR_OK
) {
    mediaReplaceResponse(false, 'Immagine non ricevuta', 400);
}

$file = $_FILES['image'];

if ($file['size'] > 10 * 1024 * 1024) {
    mediaReplaceResponse(false, 'Dimensione massima: 10 MB', 400);
}

//=============================================
// Verifica percorso originale
//=============================================

$path = str_replace('\\', '/', $path);

if (
    !preg_match(
        '#^assets/(images|galleries)/[a-zA-Z0-9_./-]+$#',
        $path
    ) ||
    in_array('..', explode('/', $path), true)
) {
    mediaReplaceResponse(false, 'Percorso non autorizzato', 400);
}

$assetsRoot = realpath(APP_ROOT . '/assets');

if ($assetsRoot === false) {
    mediaReplaceResponse(false, 'Archivio Media non disponibile', 500);
}

$destination = realpath(APP_ROOT . '/' . $path);

if (
    $destination === false ||
    !is_file($destination) ||
    is_link(APP_ROOT . '/' . $path) ||
    !str_starts_with(
        $destination,
        $assetsRoot . DIRECTORY_SEPARATOR
    )
) {
    mediaReplaceResponse(false, 'Immagine originale non valida', 400);
}

//=============================================
// Verifica formato
//=============================================

$extension = strtolower(
    pathinfo($destination, PATHINFO_EXTENSION)
);

$allowed = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'webp' => 'image/webp',
    'gif'  => 'image/gif'
];

if (!isset($allowed[$extension])) {
    mediaReplaceResponse(
        false,
        'Formato non supportato per la sostituzione',
        400
    );
}

$mime = (new finfo(FILEINFO_MIME_TYPE))
    ->file($file['tmp_name']);

if (
    $mime !== $allowed[$extension] ||
    @getimagesize($file['tmp_name']) === false
) {
    mediaReplaceResponse(
        false,
        'La nuova immagine deve avere lo stesso formato dell’originale',
        400
    );
}

//=============================================
// Sostituzione con backup temporaneo
//=============================================

$directory = dirname($destination);

$backup = tempnam($directory, '.media-backup-');

if ($backup === false) {
    mediaReplaceResponse(false, 'Impossibile creare il backup', 500);
}

if (!copy($destination, $backup)) {
    @unlink($backup);
    mediaReplaceResponse(false, 'Impossibile salvare il backup', 500);
}

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    @copy($backup, $destination);
    @unlink($backup);

    mediaReplaceResponse(false, 'Sostituzione non riuscita', 500);
}

@unlink($backup);

mediaReplaceResponse(true, 'Immagine sostituita correttamente');