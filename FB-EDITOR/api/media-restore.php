<?php
// FB-EDITOR/api/media-restore.php
// Ripristino immagini dal Cestino Media - sviluppo locale.

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function restoreReply(int $status, array $data): void {
    http_response_code($status);
    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)) {
    restoreReply(403, [
        'success' => false,
        'error' => 'Operazione consentita solo da localhost'
    ]);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    restoreReply(405, [
        'success' => false,
        'error' => 'Richiesto POST'
    ]);
}

$id = $_POST['id'] ?? '';

if (!is_string($id) || !preg_match('/^[a-f0-9]{32}$/D', $id)) {
    restoreReply(400, [
        'success' => false,
        'error' => 'Identificativo non valido'
    ]);
}

$trashDir = APP_ROOT . '/data/media-trash';
$bin = $trashDir . '/' . $id . '.bin';
$meta = $trashDir . '/' . $id . '.json';

if (
    !is_file($bin) ||
    !is_file($meta) ||
    is_link($bin) ||
    is_link($meta)
) {
    restoreReply(404, [
        'success' => false,
        'error' => 'Immagine non trovata nel cestino'
    ]);
}

$data = json_decode(
    file_get_contents($meta),
    true
);

$path = $data['original_path'] ?? null;

if (
    !is_array($data) ||
    ($data['id'] ?? null) !== $id ||
    !is_string($path) ||
    !preg_match(
        '~^assets/(images|galleries)/[a-zA-Z0-9_./ -]+\.(jpe?g|png|webp|gif|svg)$~iD',
        $path
    ) ||
    str_contains($path, '..') ||
    str_contains($path, '//') ||
    str_contains($path, '/./')
) {
    restoreReply(400, [
        'success' => false,
        'error' => 'Metadati di ripristino non validi'
    ]);
}

$destination = APP_ROOT . '/' . $path;
$parent = dirname($destination);
$base = realpath(APP_ROOT . '/assets/' . explode('/', $path)[1]);
$realParent = realpath($parent);

if (
    $base === false ||
    $realParent === false ||
    !is_dir($realParent) ||
    !(
        $realParent === $base ||
        str_starts_with($realParent, $base . DIRECTORY_SEPARATOR)
    )
) {
    restoreReply(409, [
        'success' => false,
        'error' => 'Cartella originale non disponibile'
    ]);
}

// Rifiuta cartelle simboliche.
$cursor = $parent;
while (strlen($cursor) >= strlen(APP_ROOT) && $cursor !== APP_ROOT) {
    if (is_link($cursor)) {
        restoreReply(409, [
            'success' => false,
            'error' => 'Cartella simbolica non consentita'
        ]);
    }
    $cursor = dirname($cursor);
}

if (file_exists($destination) || is_link($destination)) {
    restoreReply(409, [
        'success' => false,
        'error' => 'Esiste già un file con lo stesso nome'
    ]);
}

// Crea il file esclusivamente se non esiste.
// In questo modo non sovrascriviamo immagini presenti.
$input = @fopen($bin, 'rb');
$output = @fopen($destination, 'xb');

if (!$input || !$output) {
    if ($input) fclose($input);
    if ($output) fclose($output);

    restoreReply(500, [
        'success' => false,
        'error' => 'Impossibile iniziare il ripristino'
    ]);
}

$copied = stream_copy_to_stream($input, $output);
$size = filesize($bin);

fclose($input);
$closed = fclose($output);

if ($copied === false || $copied !== $size || !$closed) {
    @unlink($destination);

    restoreReply(500, [
        'success' => false,
        'error' => 'Ripristino incompleto'
    ]);
}

// Elimina la copia nel cestino solo dopo il ripristino.
if (!@unlink($bin)) {
    @unlink($destination);

    restoreReply(500, [
        'success' => false,
        'error' => 'Impossibile completare il ripristino'
    ]);
}

@unlink($meta);

restoreReply(200, [
    'success' => true,
    'message' => 'Immagine ripristinata',
    'path' => $path
]);
