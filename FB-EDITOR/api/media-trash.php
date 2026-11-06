<?php
// FB-EDITOR/api/media-trash.php — spostamento reversibile, SOLO sviluppo locale.
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/lib/media-usage-check.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function trashReply(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)) {
    trashReply(403, ['success' => false, 'error' => 'Operazione disponibile solo da localhost finché manca il login']);
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    trashReply(405, ['success' => false, 'error' => 'Richiesto POST']);
}
$path = $_POST['path'] ?? null;

// Conferma esplicita per immagini utilizzate
$force = ($_POST['force'] ?? '') === 'CONFIRM_USED';

if (!is_string($path) || !preg_match('~^assets/(?:images|galleries)/(?!/)[a-zA-Z0-9_./ -]+\.(?:jpe?g|png|webp|gif|svg)$~iD', $path)
    || str_contains($path, '..') || str_contains($path, '//') || str_contains($path, '/./')) {
    trashReply(400, ['success' => false, 'error' => 'Percorso non valido']);
}
$source = APP_ROOT . '/' . $path;
$real = realpath($source);
$base = realpath(APP_ROOT . '/assets/' . explode('/', $path)[1]);
if ($real === false || $base === false || !is_file($real) || is_link($source) || !str_starts_with($real, $base . DIRECTORY_SEPARATOR)) {
    trashReply(404, ['success' => false, 'error' => 'Immagine non trovata']);
}
// Non attraversare directory simboliche.
$cursor = dirname($source);
while (strlen($cursor) >= strlen(APP_ROOT) && $cursor !== APP_ROOT) {
    if (is_link($cursor)) trashReply(400, ['success' => false, 'error' => 'Cartella simbolica non consentita']);
    $cursor = dirname($cursor);
}
$usage = checkMediaUsage($path);

// La verifica deve essere sempre completa
if (!$usage['complete'] || $usage['checked_json'] === 0) {
    trashReply(409, [
        'success' => false,
        'error' => 'Spostamento bloccato: verifica utilizzo incompleta',
        'usage' => $usage
    ]);
}

// Se utilizzata, richiedi conferma esplicita
if ($usage['used'] && !$force) {
    trashReply(409, [
        'success' => false,
        'requires_confirmation' => true,
        'error' => 'Immagine utilizzata: conferma necessaria',
        'usage' => $usage
    ]);
}
// Verifica conservativa anche nei file pubblicati e negli stili personalizzati.
// Un riferimento sospetto blocca lo spostamento, anche se può essere un falso positivo.
$publishedReferences = [];
$needles = [$path, substr($path, strlen('assets/'))];
$scan = [APP_ROOT . '/pages', APP_ROOT . '/assets'];
foreach ($scan as $folder) {
    if (!is_dir($folder) || !is_readable($folder)) trashReply(409, ['success' => false, 'error' => 'Cartella non leggibile: ' . basename($folder)]);
    try {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $entry) {
            if ($entry->isLink()) trashReply(409, ['success' => false, 'error' => 'Collegamento simbolico durante la scansione']);
            if (!$entry->isFile() || !in_array(strtolower($entry->getExtension()), ['html', 'htm', 'css', 'js'], true)) continue;
            $content = @file_get_contents($entry->getPathname());
            if ($content === false) trashReply(409, ['success' => false, 'error' => 'Impossibile leggere ' . $entry->getFilename()]);
            foreach ($needles as $needle) {
                if (str_contains($content, $needle)) {
                    $publishedReferences[] = str_replace(
                        '\\',
                        '/',
                        substr($entry->getPathname(), strlen(APP_ROOT) + 1)
                    );
                    break;
                }
            }
        }
    } catch (Throwable $e) {
        trashReply(409, ['success' => false, 'error' => 'Scansione dei file pubblicati incompleta']);
    }
}
$publishedReferences = array_values(array_unique($publishedReferences));

if (!empty($publishedReferences) && !$force) {
    trashReply(409, [
        'success' => false,
        'requires_confirmation' => true,
        'error' => 'Immagine utilizzata nei file pubblicati: conferma necessaria',
        'usage' => $usage,
        'published_references' => $publishedReferences
    ]);
}
// Cartella privata del cestino: protezione Apache anche in assenza di login.
$trashDir = APP_ROOT . '/data/media-trash';
if (!is_dir($trashDir) && !@mkdir($trashDir, 0700, true)) {
    trashReply(500, ['success' => false, 'error' => 'Impossibile creare il cestino']);
}
$denyFile = $trashDir . '/.htaccess';
if (!is_file($denyFile) && @file_put_contents($denyFile, "Require all denied\nDeny from all\n", LOCK_EX) === false) {
    trashReply(500, ['success' => false, 'error' => 'Impossibile proteggere il cestino']);
}
$id = bin2hex(random_bytes(16));
$dest = $trashDir . '/' . $id . '.bin';
$meta = $trashDir . '/' . $id . '.json';
$metadata = ['id' => $id, 'original_path' => $path, 'deleted_at' => date(DATE_ATOM), 'name' => basename($path)];
if (@file_put_contents($meta, json_encode($metadata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
    trashReply(500, ['success' => false, 'error' => 'Impossibile salvare i dati di ripristino']);
}
if (!@rename($source, $dest)) {
    @unlink($meta);
    trashReply(500, ['success' => false, 'error' => 'Spostamento fallito']);
}
trashReply(200, ['success' => true, 'message' => 'Immagine spostata nel cestino', 'id' => $id, 'original_path' => $path]);
