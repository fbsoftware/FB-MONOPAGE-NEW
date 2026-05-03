<?php
/* * Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.0   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo art=articolo relativi ad un capitolo: 
   * testo e titolo (se richiesto)
   * oppure solo link.           
   * 3.0 uso di 'namespace'
=============================================================================*/

$app = new DB();
try  {
	$PDO = new PDO("mysql:host=".DB::$host.";dbname=".DB::$db."",DB::$user,DB::$pw);
     } 
	 catch(PDOException $e) { echo $e->getMessage();  }
	$PDO->beginTransaction();
    
// leggo gli articoli del capitolo
       $sql = "SELECT * FROM `".DB::$pref."art` 
                WHERE acap='$dati' and astat <> 'A' 
                ORDER BY aprog";   

// prepara il paragrafo se solo titoli
       if ($pag != 0)  { echo "<p>" ; }
	   
foreach($PDO->query($sql) as $row)

        { 
		$atext   = $row['atext'];    
        $amostra = $row['amostra'];   
        $atit    = $row['atit']; 
          if ($pag == 0)  {              
                          if ($amostra == 1)  {echo "<h3>".$atit."</h3>"; }
                          echo $atext; 
                          }
         else
          {
          echo "<a href='index.php?form=".$forma."&sub=".$dati."&content=art&dati=".$atit."&pag=1'>".$atit."</a><br >";
          } 
        }
// chiude il paragrafo se solo titoli
       if ($pag != 0)  { echo "</p>" ; }


?>