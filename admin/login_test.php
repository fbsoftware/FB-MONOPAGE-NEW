<?php  session_start();   ob_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.0
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------
   * Controllo password inserita
============================================================================= */
require_once('errorOn.php');
require_once('loadLibraries.php');
require_once('loadTemplateAdmin.php');
require_once('connectDB.php');

$username  =$_POST['uten'];
$upassword =$_POST['pass'];
$passmd5   =md5($upassword);    // cripto la password

$sql = "SELECT * FROM `".DB::$pref."ute`
        WHERE username='".$username."' and ustat<>'A'";
$stat = $PDO->prepare($sql);
$stat->execute();

		if ($stat->rowCount() < 1)
			{	// utente sconosciuto
			setcookie('err','2',time()+3600,'','','');
			header('location:login.php') ;
			}

foreach($PDO->query($sql) as $row)
  {
    	if ( $row['upassword'] == $passmd5)
            {
            // password valida
            setcookie('admin',$username,time()+3600,'','','');
            setcookie('accesso',$row['uaccesso'],time()+3600,'','','');
            setcookie('numero',$row['uiscritto'],time()+3600,'','','');
            setcookie('err','0',time()+3600,'','','');
			header('location:admin.php?urla=widget.php&pag=');
			}
       else
           {
			// pw invalida
           setcookie('err','1',time()+3600,'','','');
           header('location:login.php') ;
           }

    }
ob_end_flush();
?>
