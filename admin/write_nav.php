<?php  session_start();   ob_start();
/** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 2.0.1
   * copyright	Copyright (C) 2022 - 2023 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
=============================================================================
   * Aggiunto "nhead" per gestire header per ogni voce menu (Max.9).
   * Gestito livello di accesso
=============================================================================*/
require_once('init_admin.php');
require_once("post_".$_SESSION['tab'].".php");
$_SESSION['esito'] = array();
$azione=$_POST['submit'];
//print_r($_POST);//debug

// test campi mancanti
             if (($azione != 'cancella') && ($azione != 'ritorno'))
             {
               $m = new testNoDati($nmenu,$nli);
               $m->alert();
             }
switch ($azione)
{
case 'nuovo':
case 'copia':
            include('DB_nuovo.php');
                    break;

case 'modifica':
            include('DB_modifica.php');
                        break;

case 'cancella':
            include('DB_cancella.php');
                        break;
case 'ritorno':
    array_push($_SESSION['esito'],'2');
		   break;

default :     array_push($_SESSION['esito'],'1');
}
  unset($_SESSION['tab']);
  header('location:admin.php?'.$_SESSION['location'].'');
ob_end_flush();
?>
