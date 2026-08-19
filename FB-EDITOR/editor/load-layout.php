<?php
/**
==================================================
    Mini Elementor - Load Layout
==================================================
 */

error_reporting(0);
ini_set('display_errors', 0);

$tema = $_GET['tema'] ?? null;
$page = $_GET['page'] ?? null;

if (!$tema || !$page) {
  echo json_encode(['error'=>'parametri mancanti']);
  exit;
}

$file = PAGE_DIR.$page."json";

if (!file_exists($file)) {
  echo json_encode(['empty'=>true]);
  exit;
}
header('Content-Type: application/json');
echo file_get_contents($file);
exit;
