<?php
/**
    Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
    package		Gestionale
    versione 1.0
    copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
    license		GNU/GPL
    Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
    all'uso anche improprio di FB open template.
================================================================================
  Visualizza il navigatore principale nei due livelli previsti
================================================================================*/
echo "<section id='header' class='editor-widget' data-widget='header'>";

// LOGO						";
echo "<div class='f-flex fd-row jc-start ai-center fw fb-secondary'>";
echo "<img src='images/icofbot.png'>";
echo "</div>";

// Menù
echo "<div class='f-flex fd-row jc-start ai-center fw fb-secondary'>";
 // lettura voci menu
        $sql = "SELECT *
                FROM `".DB::$pref."nav`
                WHERE nmenu= '".TMP::$tmenu."' and nstat <> 'A' and ndesc <= ' '
                ORDER BY nprog";
          foreach($PDO->query($sql) as $row)
       {
          require 'admin/fields_nav.php';
         if ($row['npag'] == '1')
                {
                echo "<a class='fb-button fb-p025 fb-rad5 fb-upper' href='".DB::$host.DB::$sep.DB::$site.DB::$sep.$row['nsotvo']."'>".$row['nli']."</a>";
			$voce = $row['nli'];
               require_once("/liv3.php");
               echo "";
                }
//        if ($row['npag'] == '0')
		else
                {
                switch ($row['ntipo'])
                {
                case 'lnk':
                case 'art':
                case 'htm':
                    	echo "<a class='fb-button fb-p025 fb-rad5 fb-upper' href='".$row['nsotvo']."'>".$row['nli']."</a>";
                	     break;
                case 'ifr':
                      	echo "<a class='fb-button fb-p025 fb-rad5 fb-upper' header(location:'".$row['nsotvo']."')>".$row['nli']."</a>";
                		break;
                default:

                	break;
                }
                }

        }
echo "</div>";
echo "</section>";
?>