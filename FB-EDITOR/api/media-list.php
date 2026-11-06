<?php
//=============================================
// FB-EDITOR - Media Manager
// API elenco immagini (sola lettura)
//=============================================

require_once dirname(__DIR__) . '/config/app.php';

header('Content-Type: application/json; charset=utf-8');

$media = [];
$folders = [];

$roots = [
    'images' => APP_ROOT . '/assets/images',
    'galleries' => APP_ROOT . '/assets/galleries'
];

$allowedExtensions = [
    'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'
];

//=============================================
// Scansione delle cartelle autorizzate
//=============================================
foreach ($roots as $category => $root) {

    if (!is_dir($root)) {
        continue;
    }

    $rootReal = realpath($root);

    if ($rootReal === false) {
        continue;
    }

// Registra la cartella principale (images o galleries)
$folders[] = $category;

// Registra anche le sottocartelle, comprese quelle vuote
$directoryIterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $rootReal,
        FilesystemIterator::SKIP_DOTS
    ),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($directoryIterator as $entry) {

    // Ignora file e collegamenti simbolici
    if (!$entry->isDir() || $entry->isLink()) {
        continue;
    }

    $realDir = $entry->getRealPath();

    if (
        $realDir === false ||
        !str_starts_with(
            $realDir,
            $rootReal . DIRECTORY_SEPARATOR
        )
    ) {
        continue;
    }

    $relativeDir = str_replace(
        DIRECTORY_SEPARATOR,
        '/',
        substr($realDir, strlen($rootReal) + 1)
    );

    $folders[] = $category . '/' . $relativeDir;
}

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $rootReal,
            FilesystemIterator::SKIP_DOTS
        )
    );

    foreach ($iterator as $file) {

        if (!$file->isFile() || $file->isLink()) {
            continue;
        }

        $realPath = $file->getRealPath();

        if (
            $realPath === false ||
            !str_starts_with(
                $realPath,
                $rootReal . DIRECTORY_SEPARATOR
            )
        ) {
            continue;
        }

        $extension = strtolower(
            $file->getExtension()
        );

        if (!in_array(
            $extension,
            $allowedExtensions,
            true
        )) {
            continue;
        }

        $relative = str_replace(
            DIRECTORY_SEPARATOR,
            '/',
            substr(
                $realPath,
                strlen($rootReal) + 1
            )
        );

        $path = 'assets/' .
            $category . '/' . $relative;

        $url = ASSETS_URL . '/' .
            $category . '/' .
            implode(
                '/',
                array_map(
                    'rawurlencode',
                    explode('/', $relative)
                )
            );

        $media[] = [
            'name' => $file->getFilename(),
            'path' => $path,
            'url' => $url,
            'folder' => dirname($relative) === '.'
            ? $category
            : $category . '/' .
                str_replace('\\', '/', dirname($relative)),
            'type' => $extension,
            'size' => $file->getSize(),
            'modified' => $file->getMTime()
        ];
    }
}

//=============================================
// Ordinamento per percorso
//=============================================
usort($media, function($a, $b) {
    return strcasecmp(
        $a['path'],
        $b['path']
    );
});

// Elimina eventuali duplicati e ordina le cartelle
$folders = array_values(array_unique($folders));

usort($folders, 'strcasecmp');

echo json_encode([
    'success' => true,
    'count' => count($media),
    'folders' => $folders,
    'images' => $media
], JSON_UNESCAPED_SLASHES);
