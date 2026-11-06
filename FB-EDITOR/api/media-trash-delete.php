
<?php
// FB-EDITOR/api/media-trash-delete.php
// Eliminazione definitiva di una singola immagine dal cestino.
// Disponibile solo in ambiente locale.

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function deleteReply(int $status, array $data): void {
    http_response_code($status);
    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

// Accesso consentito soltanto da localhost.
if (!in_array(
    $_SERVER['REMOTE_ADDR'] ?? '',
    ['127.0.0.1', '::1'],
    true
)) {
    deleteReply(403, [
        'success' => false,
        'error' => 'Operazione consentita solo da localhost'
    ]);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    deleteReply(405, [
        'success' => false,
        'error' => 'Richiesto POST'
    ]);
}

// Identificativo univoco dell'immagine nel cestino.
$id = $_POST['id'] ?? '';

if (
    !is_string($id) ||
    !preg_match('/^[a-f0-9]{32}$/D', $id)
) {
    deleteReply(400, [
        'success' => false,
        'error' => 'Identificativo non valido'
    ]);
}

// Conferma obbligatoria anche lato server.
if (($_POST['confirm'] ?? '') !== 'DELETE') {
    deleteReply(400, [
        'success' => false,
        'error' => 'Conferma di eliminazione mancante'
    ]);
}

$trashDir = APP_ROOT . '/data/media-trash';

$bin = $trashDir . '/' . $id . '.bin';
$meta = $trashDir . '/' . $id . '.json';

// Non seguire collegamenti simbolici.
if (
    !is_file($bin) ||
    !is_file($meta) ||
    is_link($bin) ||
    is_link($meta)
) {
    deleteReply(404, [
        'success' => false,
        'error' => 'Immagine non trovata nel cestino'
    ]);
}

// Controlla la corrispondenza dei metadati.
$content = @file_get_contents($meta);

if ($content === false) {
    deleteReply(500, [
        'success' => false,
        'error' => 'Impossibile leggere i metadati'
    ]);
}

$data = json_decode($content, true);

if (
    !is_array($data) ||
    ($data['id'] ?? null) !== $id
) {
    deleteReply(409, [
        'success' => false,
        'error' => 'Metadati non validi'
    ]);
}

// Elimina prima il file immagine.
if (!@unlink($bin)) {
    deleteReply(500, [
        'success' => false,
        'error' => 'Impossibile eliminare l’immagine'
    ]);
}

// Elimina poi i metadati.
if (!@unlink($meta)) {
    deleteReply(500, [
        'success' => false,
        'error' =>
            'Immagine eliminata, ma impossibile rimuovere i metadati'
    ]);
}

deleteReply(200, [
    'success' => true,
    'message' => 'Immagine eliminata definitivamente',
    'id' => $id
]);

