<?php
/** 
    Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
    package            fbot-boot
    versione 1.0    
    copyright  Copyright (C) 2025 - 2026 FB. All rights reserved.
    license            GNU/GPL
    Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
    all'uso anche improprio di FB open template.
=============================================================================== 
  Crea il 2° livello del navigatore principale.
=============================================================================== */
$file=str_replace('\\','/',__FILE__);
if($file == $_SERVER['SCRIPT_FILENAME']) exit('Accesso non consentito') ;
/*=============================================================================== */ 
$con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
$PDO = new PDO($con,DB::$user,DB::$pw);
$PDO->beginTransaction();  
       $sql2 = "SELECT * 
                FROM `".DB::$pref."nav`  
                WHERE nmenu='admin' and nstat <> 'A' and nli = '$nli'  and ndesc > ''
                ORDER BY nprog";
            echo "<ul class='liv2'>";                
foreach($PDO->query($sql2) as $row2)  
        {   
            $xli         = $row2['ndesc'];
            $xtipo       = $row2['ntipo'];
            $xsotvo      = $row2['nsotvo'];
            $xpag        = $row2['npag'];
            $xnaccesso   = $row2['naccesso'];
     if ($accesso >= $xnaccesso)  
          {         
          if (($xtipo == 'arg') || ($xtipo == 'cap') || ($xtipo == 'art') || ($xtipo == 'htm'))
            {
            echo "<li><a class='current' href='index.php?forma=".$nli."&sub=".$xli."&content=".$xtipo."&dati=".$xsotvo."&pag=".$xpag."'>".$xli."</a></li>";
            }
            else
            {                                                                                                          
            echo "<li><a class='current' href='index.php?forma=".$nli."&sub=".$xli."&content=".$xtipo."&urla=".$xsotvo."&pag=".$xpag."'>".$xli."</a></li>";            
            }
          }
     }
        echo "</ul>";  
?> 