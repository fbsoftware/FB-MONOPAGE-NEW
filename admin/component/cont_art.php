<?php
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 1.3   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo art=articolo sulla pagina-menu.      
=============================================================================*/
$app = new DB();
try  {
	$PDO = new PDO("mysql:host=".DB::$host.";dbname=".DB::$db."",DB::$user,DB::$pw);
     } catch(PDOException $e) { echo $e->getMessage();  }
$PDO->beginTransaction();
    $sql = "SELECT * 
               FROM `".DB::$pref."art` 
               WHERE atit='$dati' and astat <> 'A'
               ORDER BY aprog";
	foreach($PDO->query($sql) as $row)
	{
		$text   = $row['atext'];    
        $mostra = $row['amostra'];   
        $tit    = $row['atit'];      
        
       if ($mostra == 1)  {echo "<h3>".$tit."</h3>"; }
       $a = new txt($text);
       $a->ingloba();              // elementi inglobati nel testo

       echo $text;
	}
?>
