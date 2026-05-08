<?php  session_start();    ob_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.0
   * copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
   * ------------------------------------------------------------------------
   * Inserimento in base al tipo voce
   * Gestito livello di accesso
   * 1.0.0	nuova head
============================================================================= */
require_once('init_admin.php');
require_once("post_".$_SESSION['tab'].".php");
@$azione=$_POST['submit'];
echo "<body class='admin' data-theme='".TMP::$tcolor."'>";
// mostra stringa bottoni
switch ($azione)
{
case 'ritorno':
     {
     header('location:admin.php?'.$_SESSION['location'].'');
     break;
     }

case 'nuovo':
     {
// toolbar
	$param  = array('nuovo','ritorno');
	$btx    = new bottoni_str_par('Voci di menù - nuovo','nav','write_nav.php',$param);
		$btx->btn();
echo "<fieldset >";
      $nav = new DB_ins('nav','nprog');
		$num = $nav->insert();
      $f1 = new field($num,'nprog',3,'Progressivo');
		$f1->field_i();
      $ts    = new DB_tip_i('stato','nstat','','Stato record','');
		$ts->select();
      $men = new DB_sel_l('mnu','bprog','','bmenu','nmenu','bstat','bmenu','Menu','');
             $men->select_label();
      $f2 = new field('','nli',20,'Voce');
		$f2->field_i();
      $f4 = new field('','ndesc',20,'Sottovoce');
		$f4->field_i();
      $f3 = new field('','ntesto',25,'Descrizione');
		$f3->field_i();
      $tv = new field($ntipo,'ntipo',20,'Tipo voce');
		$tv->field_r();
switch ($ntipo)
{
case 'arg':
       $t = new DB_sel_l('arg','rdesc','','rcod','nsotvo','rstat','rdesc','Argomento','');
       echo $t->select_label()  ; break;
 case 'cap':
       $t = new DB_sel_l('cap','cdesc','','ccod','nsotvo','cstat','cdesc','Capitolo','');
       echo $t->select_label()  ; break;
case 'art':
       $t = new DB_sel_l('art','atit','','atit','nsotvo','astat','atit','Articolo','');
       echo $t->select_label()  ; break;
case 'lnk':
       $ty = new field($nsotvo,'nsotvo',30,'Link interno');    
       echo $ty->field_i(); break;
case 'ifr':
       $tw = new select_root($nsotvo,'nsotvo','Html/php pers.');
       echo $tw->select_dir();    break;
case 'pag':
       $tw = new select_file('../pagine/',$nsotvo,'nsotvo','Pagina web','File della pagina');
      echo $tw->file();    break;    
default:
   echo "Tipo voce errata=".$ntipo;
   break;   
}
      $tg = new DB_tip_i('trg','ntarget',$ntarget,'Target','Target:_blank ...');
		$tg->select();
      $f6 = new field(0,'nselect',1,'Voce corrente (1)');
	 	$f6->field_i();
      $f7 = new field(0,'ntitle',1,'(1)Titoli, (0)dettaglio');
	 	$f7->field_i();
      $f9 = new field(0,'nhead',1,'(1)Header specifico');
	 	$f9->field_i();
      $fa = new field(0,'npag',1,'Parametro');
	 	$fa->field_i();
      $tz = new field(0,'naccesso',1,'Livello accesso');
	 	$tz->field_i();
	   $f2 = new input(array('','nmetakey',33,'Meta keywords','Keywords assegnate alla pagina','tx'));
		$f2->field();   


	echo  "</fieldset>";
	echo "</form>";
      break;

}
}
echo "</body>";
ob_end_flush();
?>
