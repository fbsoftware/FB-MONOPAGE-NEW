<?php   session_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 2.0.1
   * copyright	Copyright (C) 2022 - 2023 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
=============================================================================
   07/03/21		scelta del menù per mostrare le relative voci
=============================================================================  */

  //   toolbar
$param  = array('mostra','chiudi');
$btx   = new bottoni_str_par('Scelta menù per voci ','nav','gest_nav2.php',$param);
     $btx->btn();
 
 // memorizza location iniziale
	$_SESSION['location'] = $_SERVER['QUERY_STRING'];

// zona messaggi
$parm = $_SESSION['esito'];
$m = new msg($parm);
$m->msg();

// scelta tema
$menu = new DB_nav();
$menu->menu();
echo "</form>";
?>
