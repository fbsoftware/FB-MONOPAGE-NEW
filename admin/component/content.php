<?php
/* * Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 1.4   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Robot di crittura dei contenuti in base alla voce di menu.      
   * 3.0 uso di 'namespace'
=============================================================================*/
$app = new DB();
try  {
	$PDO = new PDO("mysql:host=".DB::$host.";dbname=".DB::$db."",DB::$user,DB::$pw);
     } catch(PDOException $e) { echo $e->getMessage();  }
	$PDO->beginTransaction();
      $sql = "SELECT * 
              FROM `".DB::$pref."nav` 
              WHERE nli='".$forma."' and ndesc='$sub' and nmenu='admin' and nstat <> 'A' and ntipo<>'' 
              ORDER BY nprog";  
foreach($PDO->query($sql) as $row)
		{
          $nsotvo = $row['nsotvo']; 
          $ntipo  = $row['ntipo']; 
          $ndesc  = $row['ndesc']; 
		}

if (isset ($ntipo)) 
{     

switch ($content)  // analizzo tutti i tipi di contenuto
{     
case  'art' : // lettura articoli
     require_once('component/cont_art.php');      
      break;
case  'cap' : // lettura capitoli
     require_once('component/cont_cap.php');      
      break;
case  'arg' : // lettura argomenti
     require_once('component/cont_arg.php');
     break;
case  'vid' : // lettura videoclip YouTube
     require_once('component/cont_vid.php');
     break;
case  'gal' : // lettura gallery Picasa
     require_once('component/cont_gal.php');
     break; 
case  'url' :  //link esterno
      require_once('component/cont_url.php');
     break;
case  'lnk' : //link interno 
      require_once('component/cont_lnk.php');
     break;
case  'htm' : //pagina custom 
      require_once('component/cont_htm.php');
     break;
case  'cnt' :  // contatti
     break;
case  'ifr' :  // iframe
     break;

default : 
     echo $ntipo."Il tipo contenuto da mostrare non e' valido! <br />Contattare l'amministratore del sito.";
     break;      
} 
} 
?>
