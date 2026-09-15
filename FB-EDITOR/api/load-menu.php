<?php
require_once dirname(__DIR__, 2) . '/FB-EDITOR/config/app.php';

header('Content-Type: application/json');

$menuPath = DATA_DIR . '/site-menu.json';

if (!file_exists($menuPath)) {
    echo json_encode([
        'success' => true,
        'menu' => []
    ]);
    exit;
}

$json = file_get_contents($menuPath);
$menu = json_decode($json, true);

echo json_encode([
    'success' => true,
    'menu' => is_array($menu) ? $menu : []
]);