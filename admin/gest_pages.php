<?php  session_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.1
   * copyright	Copyright (C) 2025 - 2026 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
============================================================================= */
require_once('init_admin.php');
require_once('errorOn.php');
echo "<body class='admin'>";

$array_file=array();
echo    "<div class='f-flex fd-row fw'>";

 //   bottoni gestione
$param = array('nuovo','modifica','chiudi');
$btx   = new bottoni_str_par('Gestione delle pagine','art','gest_pages2.php',$param);
     $btx->btn();	
echo "</div>";

// nuova pagina
echo "<div class='f-new'>";
        echo "<label>Nuova pagina</label>";
        echo "<input type='text' name='new_page' placeholder='es. servizi'>";
echo "</div>";
echo "<hr>";

// lettura directory
$path = $_SERVER['DOCUMENT_ROOT'] . "/FB-MONOPAGE/data/";
foreach (glob($path . "*.json") as $gx) {
    $array_file[] = basename($gx, ".json");
}
// cartella pagine generali sel sito, mostro i file in una tabella
//   testate
echo "<section id='nav'>";
echo "<div class='table fb-hv80'>";

echo "<div class='th'>";
    echo "<div class='td'>Scelta</div>";
    echo "<div class='td'>Ordine</div>";
    echo "<div class='td'>Pagina</div>";
echo "</div>";

$conto2 = count($array_file);

for($b = 0; $b < $conto2; $b++) {

    $page = $array_file[$b];
echo "<div class='tr'>";
    echo "<div class='td'>";
        echo "<input type='checkbox' name='sel[]' value='" . h($page) . "'>";
    echo "</div>";
    echo "<div class='td'>";
        echo "<input type='text' readonly name='ordine[" . h($page) . "]' value='" . h($b) . "' style='width:60px;'>";
    echo "</div>";
    echo "<div class='td'>";
        echo "<input type='text' readonly  name='titolo[" . h($page) . "]' value='" . h($page) . "' >";
    echo "</div>";
echo "</div>";
}
echo "</div>";//table



echo "</section>";
echo "</form>";