<?php
/* * Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.0   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo cap=capitolo.
   * $pag=0  Stampa tutti gli articoli del capitolo. 
   * $pag=1  Stampa tutti i titoli degli articoli del capitolo + link di richiamo. 
   * se "cmostra"=1 mostra il testo del capitolo.       
   * 3.0 uso di 'namespace'
=============================================================================*/
$app = new DB();
try  {
	$PDO = new PDO("mysql:host=".DB::$host.";dbname=".DB::$db."",DB::$user,DB::$pw);
     } catch(PDOException $e) { echo $e->getMessage();  }
$PDO->beginTransaction();

// stampa il testo del capitolo.
       $sql1 = "SELECT * FROM `".DB::$pref."cap` 
                WHERE ccod='$dati' and cstat <> 'A'  ";
foreach($PDO->query($sql) as $row)
      {
        { 
		$ctext   = $row['ctext']; 
		$cmostra = $row['cmostra']; 
        }
        if ($cmostra == 1) {  echo $ctext;   }
      }     
require_once 'cont_artcap.php';      
        
?>