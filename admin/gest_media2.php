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

echo "<body class='admin'>";

 //   bottoni gestione
echo "<div style='display:flex;justify-content:space-between;align-items:center;'>";
$param = array('upload','chiudi');
$btx   = new bottoni_str_par('Gestione dei media','img','upd_media.php',$param);
     $btx->btn();

echo "</div>";

// cartella immagini generali sel sito, mostro i file in una tabella
$path_images = $_SERVER['DOCUMENT_ROOT'] . "/FB-JSON/assets/images/";
$url_images  = "/FB-JSON/assets/images/";

$im = new imgUpdTable(
    $path_images,
    $url_images,
    120,
    150,
    7,
    'upd_media.php?path=' . urlencode($url_images)
);
     $im->putUpdTable();
     // memorizza il path scelto
       echo "<input type='hidden' name='img_path' value='".$url_images."' />";
    echo "</form>";
	 echo "</body>";
?>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>