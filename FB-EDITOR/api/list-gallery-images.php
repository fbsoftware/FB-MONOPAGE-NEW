<?php
require_once dirname(__DIR__) . '/config/app.php';
header('Content-Type: application/json');

$folder = $_GET['folder'] ?? 'portfolio';
$folder = trim($folder, '/');

$baseDir = APP_ROOT . '/assets/galleries/' . $folder;
$images = [];

if(is_dir($baseDir)){
    foreach(scandir($baseDir) as $file){
        if($file === '.' || $file === '..') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if(in_array($ext, ['jpg','jpeg','png','webp','gif'])){
            $images[] = ASSETS_URL . '/galleries/' . $folder . '/' . $file;
        }
    }
}

sort($images);

echo json_encode([
    'success' => true,
    'images' => $images
]);