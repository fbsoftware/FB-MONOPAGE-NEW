<?php
/** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.0   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo arg=argomento.
   * $pag=0  Stampa tutti gli articoli dell'argomento. 
   * $pag=1  Stampa tutti i titoli degli articoli dell'argomento+ link di richiamo. 
   * se "rmostra"=1 mostra il testo dell'argomento.       
   * 3.0 uso di 'namespace'
=============================================================================*/

// stampa il testo dell'argomento se richiesto.
       $sql = "SELECT * FROM `".DB::$pref."arg` 
                WHERE rcod='$dati' and rstat <> 'A'  ";
foreach($PDO->query($sql) as $row)
		{
        $rtext   = $row['rtext']; 
        $rmostra = $row['rmostra']; 
        }
        if ($rmostra == 1) 
			{  echo $rtext;   }
      
// leggo i capitoli dell'argomento e stampo il testo del capitolo(if) ed i suoi articoli.
 
       $sql2 = "SELECT * FROM `".DB::$pref."cap` 
                WHERE carg='$dati' and cstat <> 'A'  
                ORDER BY cprog ";
foreach($PDO->query($sql2) as $row2)

          { 
          $ctext   = $row2['ctext']; 
          $cmostra = $row2['cmostra']; 
          $ccod    = $row2['ccod']; 
          if ($cmostra == 1) {  echo $ctext;   }
          require 'cont_artarg.php'; 
          }                   

?>
