<?php   session_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 2.0.1
   * copyright	Copyright (C) 2022 - 2023 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
=============================================================================
   * Gestione della tabella 'nav' voci di menu e sottovoci.
   * Aggiunto "nhead" per gestire header per ogni voce menu (Max.9).
   * 1.0.0	nuovo head breve
=============================================================================  */
require_once('init_admin.php');
$_SESSION['tab'] = "nav";
 if ($_POST['submit'] == 'chiudi')
  header('location:admin.php?urla=widget.php&pag=');

echo "<body class='admin' data-theme='".TMP::$tcolor."'>";
  //   toolbar
	$param  = array('nuovo','modifica','copia','cancella','ritorno');
	$btx    = new bottoni_str_par('Voci di menù','nav','upd_nav.php',$param);
		$btx->btn();

     // mostra la tabella filtrata
echo "<section id='nav'>";

echo "<div class='table fb-hv80'>";

echo "<div class='th'>";
echo "<div class='td'>Scelta</div>";
echo "<div class='td'>Stato</div>";
echo "<div class='td'>Progr.</div>";
echo "<div class='td'>Menù</div>";
echo "<div class='td'>Voce</div>";
echo "<div class='td'>Sottovoce</div>";
echo "<div class='td'>Descrizione</div>";
echo "<div class='td'>Tipo</div>";
echo "<div class='td'>Contenuto</div>";
echo "<div class='td'>Accesso</div>";
echo "<div class='td'>Parametro</div>";
echo "</div>";
 // lettura database
if ($_POST['menu'] == 'tutti')
	{
     $sql = "  SELECT *
               FROM `".DB::$pref."nav`
               ORDER BY nmenu,nli,nprog ";}
else {
     $sql = "  SELECT *
               FROM `".DB::$pref."nav`
			   WHERE nmenu = '".$_POST['menu']."'
               ORDER BY nmenu,nli,nprog";}

            foreach($PDO->query($sql) as $row)
  {  require('fields_nav.php');

		echo "<div class='tr'>";
		$f2 = new input(array($nid,'nid',2,'','Spuntare per scegliere l elemento','ck-n'));
		echo "<div class='td'>"; $f2->field_n(); echo "</div>";
		$f3 = new input(array($nstat,'nstat',2,'','','st-n'));
		echo "<div class='td'>"; $f3->field_n(); echo "</div>";
       ?>
     <div class='td'><?php echo $nprog ?></div>
     <div class='td'><?php echo $nmenu ?></div>
     <div class='td'><?php echo $nli ?></div>
     <div class='td'><?php echo $ndesc ?></div>
     <div class='td'><?php echo $ntesto ?></div>
     <div class='td'><?php echo $ntipo ?></div>
     <div class='td'><?php echo $nsotvo ?></div>
     <div class='td'><?php echo $naccesso ?></div>
	 <div class='td'><?php echo $npag ?></div>

<?php
     echo "</div>";
          }
	echo "</div>";
	echo "</section>";
	echo "</form>";
	echo "</body>";
?>
