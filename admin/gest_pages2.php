<?php  
/** 
  Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
    package		FB open template
    versione 3.1
    copyright	Copyright (C) 2025 - 2026 FB. All rights reserved.
    license		GNU/GPL
    Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
    all'uso anche improprio di FB open template.
============================================================================= */
require_once('init_admin.php');
//print_r($_POST);//debug

$azione = $_POST['submit'] ?? '';

$selezionate = $_POST['sel'] ?? [];
//$ordine = $_POST['ordine'] ?? [];
//$titoli = $_POST['titolo'] ?? [];
$newPage = trim($_POST['new_page'] ?? '');

// modifica pagina
if ($azione === 'modifica') {
  $page = json_encode($selezionate);
  header("Location: /FB-JSON/editor/editor.php?page=" . urlencode($page));
exit;
}

// nuova pagina
if ($azione === 'nuovo') {
  $page = json_encode($newPage);
  header("Location: /FB-JSON/editor/editor.php?page=" . urlencode($page));
exit;
}