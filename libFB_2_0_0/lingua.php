<?php
// descrizioni in lingua
$valore = '';
if (TMP::$tlang  <= "") TMP::$tlang = 'it';   // lingua di default
$lang = parse_ini_file("language/".TMP::$tlang.".ini");
foreach($lang as $chiave => $valore)
     {
	  $$chiave=$valore;
     }
?>
