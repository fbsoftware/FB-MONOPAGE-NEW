<?php
/* * Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 1.4   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo vid - clip di YouTube.      
=============================================================================*/
      $sql1 = "SELECT * 
               FROM `".DB::$pref."tmp` 
               WHERE tsel='*' and tstat <> 'A'"; 
      $result = mysql_query($sql1);
      if (mysql_num_rows($result))
         {
         while($row  = mysql_fetch_array($result))
                     { require("../".DB::$site."/administrator/fields_tmp.php"); }        
         }
        
      $sql2 = "SELECT * 
               FROM `".DB::$pref."vid` 
               WHERE vcod='$nsotvo' and vstat <> 'A'"; 
      $result = mysql_query($sql2);
      if (mysql_num_rows($result))
{
      while($row  = mysql_fetch_array($result))
      { require("../".DB::$site."/administrator/fields_vid.php"); 
        
       if ($vmostra == 1)  {echo "<h3>".$vtit."</h3>"; }
       echo $vtexta;
echo  "<object 
       width='".$vwidth."' 
       height='".$vheigh."'>";  
echo  "<param name='movie' 
       value='http://www.youtube.com/v/".$nsotvo."
       &hl=it_IT
       &fs=1
       &autoplay=".$vauto."
       &rel=0
       &color1=0x".$tcolscu."
       &color2=0x".$tcolchi."
       &border=".$vbordo."></param>";
echo  "<param name='allowFullScreen' value='false'></param>";
echo  "<param name='allowscriptaccess' value='always'></param>";

echo  "<embed src='http://www.youtube.com/v/".$nsotvo."&hl=it_IT&fs=1&color1=0x".$tcolscu."&color2=0x".$tcolchi."&border=".$vbordo."&autoplay=".$vauto."'";
echo  " type='application/x-shockwave-flash' 
      allowscriptaccess='always'";
echo   "allowfullscreen='false' 
        width='".$vwidth."' 
        height='".$vheigh."'>
        </embed></object>";  
       echo $vtextb;    
       }
} 
?>
