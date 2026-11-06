<?php
// FB-EDITOR/lib/media-usage-check.php
// Verifica riferimenti JSON senza modificare file.
require_once dirname(__DIR__) . '/config/app.php';

function checkMediaUsage(string $path): array
{
    $normalizedPath = str_replace('\\', '/', $path);
    $galleryFolder = null;
    if (str_starts_with($normalizedPath, 'assets/galleries/')) {
        $galleryFolder = dirname(substr($normalizedPath, strlen('assets/galleries/')));
        if ($galleryFolder === '.') $galleryFolder = '';
    }

    $references = [];
    $errors = [];
    $checked = 0;
    $scanRoots = [APP_ROOT . '/data', APP_ROOT . '/FB-EDITOR/templates'];

    $checkValue = function ($value, string $key, string $type, string $file) use (&$references, $normalizedPath, $galleryFolder): void {
        if (!is_string($value) || $value === '') return;
        $v = rawurldecode(str_replace('\\', '/', $value));
        $v = explode('?', explode('#', $v, 2)[0], 2)[0];
        $direct = $v === $normalizedPath || str_ends_with($v, '/' . $normalizedPath);
        // Anche URL con percorso percent-encoded e percorsi relativi alla root assets.
        if (!$direct && str_starts_with($normalizedPath, 'assets/')) {
            $withoutAssets = substr($normalizedPath, strlen('assets/'));
            $direct = $v === $withoutAssets || str_ends_with($v, '/' . $withoutAssets);
        }
        if ($direct) {
            $references[] = ['file' => $file, 'type' => $type, 'field' => $key, 'kind' => 'image'];
        }
        if ($galleryFolder !== null && $galleryFolder !== '') {
            $folderReference = ($key === 'folder' && in_array($type, ['gallery', 'imageSlide'], true))
                || $key === 'backgroundGallery';
            if ($folderReference && trim($v, '/') === $galleryFolder) {
                $references[] = ['file' => $file, 'type' => $type, 'field' => $key, 'kind' => 'folder', 'folder' => $galleryFolder];
            }
        }
    };

    $walk = function ($node, string $file, string $type = '') use (&$walk, $checkValue): void {
        if (!is_array($node)) return;
        $nodeType = isset($node['type']) && is_string($node['type']) ? $node['type'] : $type;
        foreach ($node as $key => $value) {
            if (is_string($value)) $checkValue($value, (string)$key, $nodeType, $file);
            elseif (is_array($value)) $walk($value, $file, $nodeType);
        }
    };

    foreach ($scanRoots as $scanRoot) {
        if (!is_dir($scanRoot) || !is_readable($scanRoot)) {
            $errors[] = 'Directory non accessibile: ' . str_replace(APP_ROOT . '/', '', $scanRoot);
            continue;
        }
        try {
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scanRoot, FilesystemIterator::SKIP_DOTS));
            foreach ($it as $entry) {
                if ($entry->isLink() || !$entry->isFile() || strtolower($entry->getExtension()) !== 'json') continue;
                $file = str_replace('\\', '/', substr($entry->getPathname(), strlen(APP_ROOT) + 1));
                $content = @file_get_contents($entry->getPathname());
                if ($content === false) { $errors[] = 'Lettura fallita: ' . $file; continue; }
                $json = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
                    $errors[] = 'JSON non valido: ' . $file;
                    continue;
                }
                $checked++;
                $walk($json, $file);
            }
        } catch (Throwable $e) {
            $errors[] = 'Scansione incompleta: ' . str_replace(APP_ROOT . '/', '', $scanRoot);
        }
    }

    $references = array_values(array_unique($references, SORT_REGULAR));
    return [
        'success' => true,
        'path' => $normalizedPath,
        'used' => count($references) > 0,
        'complete' => count($errors) === 0,
        'checked_json' => $checked,
        'references' => $references,
        'errors' => $errors,
        'note' => 'Controllo dei JSON: non esclude riferimenti presenti solo in HTML, CSS, PHP o JavaScript.'
    ];
}
