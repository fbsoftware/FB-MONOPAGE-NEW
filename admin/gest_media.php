<?php  session_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.1
   * copyright	Copyright (C) 2025 - 2026 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
============================================================================= */

// memorizza location iniziale
$_SESSION['location'] = $_SERVER['QUERY_STRING'];

 //   bottoni gestione
$param = array('cerca','chiudi');
$btx   = new bottoni_str_par('Gestione dei media','img','gest_media2.php',$param);
     $btx->btn();

// zona messaggi
$parm = $_SESSION['esito'];
$m = new msg($parm);
$m->msg();

// emette tebella con immagini
?>

<?php
        $f = new DB_tip_i('pathi','pcol','','Path immagini','Path immagini da gestire');
                $f->select();
