<?php
header('Content-Type: application/json');

$rootDir = dirname(__DIR__);
$menuPath = $rootDir . '/data/site-menu.json';

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