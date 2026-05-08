<?php
/* * Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 1.3   
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
   * -------------------------------------------------------------------------
   * Scrittura dei contenuti di tipo gal - foto gallery.      
=============================================================================*/
require_once('classi/DB_tmp.php');  // parametri del template    
 Template = new DB_tmp('sito');    Template->read_tmp();
$sql2 = "SELECT * 
               FROM `".DB::$pref."gal` 
               WHERE gcod='$nsotvo' and gstat <> 'A'"; 
      $result = mysql_query($sql2);
      if(mysql_num_rows($result)!=0)
{
      while($row  = mysql_fetch_array($result))
      { require("../".DB::$site."/administrator/fields_gal.php");
       if ($gmostra == 1)  {echo "<h3>".$gtit."</h3>"; }
       echo $gtexta;
echo  "<embed type='application/x-shockwave-flash' ";
echo  "src='http://picasaweb.google.com/s/c/bin/slideshow.swf'";
if ( $gwidth == 0)  {echo " width='400'";}   else {echo " width='".$gwidth."'";} 
if ( $gheigh == 0) {echo " height='300'";}  else {echo " height='".$gheigh."'";}
echo  "flashvars='host=picasaweb.google.com";
if ( $gcaptions == 1)     {echo "&captions=1";}           else {echo "";}
if ( $gnoauto == 1)       {echo "&noautoplay=1";}         else {echo "";} 
if (  Template->colchi != '') {echo "&RGB=0x". Template->colchi;} else {echo "&RGB=0xffffff";}       
echo  "&hl=it&feat=flashalbum&feed=http%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2Ffbsoftware.bresciani%2Falbumid%".$nsotvo."%3Falt%3Drss%26kind%3Dphoto%26hl%3Dit' ";
echo  "pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>";     
}
} 
?>
