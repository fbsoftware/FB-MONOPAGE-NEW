<?php
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 1.4 (beta)   
   * copyright	Copyright (C) 2025 - 2026 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta 
   * all'uso anche improprio di FB open template.
============================================================================= 
  Legge le variabili relative al template in uso.
=============================================================================== */
$con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
$PDO = new PDO($con,DB::$user,DB::$pw);
$PDO->beginTransaction();  
$sql = "SELECT * FROM `".DB::$pref."tmp` 
          WHERE tcod='".TMP::$tcod."' ";
foreach($PDO->query($sql) as $row)  
   	if ( $row['tfolder'] != "") 
{ 
  $desc   = $row['tcod'] ;
  $path   = $row['tfolder'] ;
  $menu   = $row['tmenu'] ;
}
?> 