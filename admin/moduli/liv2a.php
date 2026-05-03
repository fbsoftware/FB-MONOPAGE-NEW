<?php
/** FB_template versione 1.4
   Fausto Bresciani
   Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta all'uso
   anche improprio di FB_template.
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
            
          if (($xtipo == 'arg') || ($xtipo == 'cap') || ($xtipo == 'art') || ($xtipo == 'htm'))
            {
            echo "<li><a class='current' href='admin.php?forma=".$nli."&sub=".$xli."&content=".$xtipo."&dati=".$xsotvo."&pag=".$xpag."'>".$xli."</a></li>";
            }
            elseif ($ntipo == 'lnk')
              {
                echo "<a href='FB-JSON/nav-builder/'.$nsotvo.'>".$nli."</a>";
              }
              else
            {
            echo "<li><a class='current' href='admin.php?forma=".$nli."&sub=".$xli."&content=".$xtipo."&urla=".$xsotvo."&pag=".$xpag."'>".$xli."</a></li>";
            }
          
     }
        echo "</ul>";
?>
