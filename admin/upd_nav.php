<?php  session_start();     ob_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 2.0.1
   * copyright	Copyright (C) 2022 - 2023 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
============================================================================
   * Aggiunto "nhead" per gestire header per ogni voce menu (Max.9).
   * Gestito livello di accesso
   * 1.0.0	nuova toolbar
   09/02/2022 aggiunto tipo "incl" per include
============================================================================*/
require_once('init_admin.php');
require_once("post_".$_SESSION['tab'].".php");
$azione=$_POST['submit'];

// test scelta effettuata sul pgm chiamante
             $scelta = new testSiScelta($nid,$azione);
               $scelta->alert_s();

echo "<body class='admin' data-theme='".TMP::$tcolor."'>";

switch ($azione)
{
//==================================================================================
    case 'ritorno' :
		array_push($_SESSION['esito'],'2');
		$loc = "location:admin.php?".$_SESSION['location']."";
		header($loc);
		break;
//==================================================================================

case 'nuovo':    // scelta tipo voce, prosegue su: upd2_nav.php
    {
	$param  = array('nuovo','ritorno');
	$btx    = new bottoni_str_par('Tipo voce menù','nav','upd2_nav.php',$param);
		$btx->btn($param);
	echo  "<fieldset >";
	$tmod = new DB_tip_i('voce','ntipo','','Tipo voce','');
		$tmod->select();
	echo  "</fieldset></form>";
	break;
     }
//==================================================================================
case 'modifica':
//  toolbar
	$param  = array('modifica','ritorno');
	$btx    = new bottoni_str_par('Voci di menù - modifica','nav','write_nav.php',$param);
		$btx->btn();
    $sql = "SELECT *
			 FROM `".DB::$pref."nav`
			 WHERE `nid` = $nid ";
	foreach($PDO->query($sql) as $row)
	{
      require('fields_nav.php');
      echo  "<fieldset >";
      $f0  = new input(array($nid,'nid',1,'ID record','','h'));
		$f0->field();

      $f1  = new input(array($nprog,'nprog',3,'Progressivo','Progressivo di caricamento','i'));
		$f1->field();


      $ts  = new DB_tip_i('stato','nstat',$nstat,'Stato record','Record attivo/annullato');
		$ts->select();
      $mnu = new DB_sel_l('mnu','bprog',$nmenu,'bmenu',
                          'nmenu','bstat','bmenu','Menu','Nome menù');
		$mnu->select_label();
      $f2 = new input(array($nli,'nli',20,'Voce','Voce di menù','i'));
		$f2->field();
      $f4 = new input(array($ndesc,'ndesc',20,'Sottovoce','Sottooce di menù','i'));
		$f4->field();
      $f3 = new input(array($ntesto,'ntesto',25,'Descrizione','Descrizione voce di menù','i'));
		$f3->field();
      $tv = new input(array($ntipo,'ntipo',10,'Tipo voce','Tipo voce di menù','r'));
		$tv->field();

switch ($ntipo)
{
case 'arg':
       $t = new DB_sel_l('arg','rdesc',$nsotvo,'rcod','nsotvo','rstat','rdesc','Argomento','Argomento dell\'articolo');
       echo $t->select_label()."<br >";
		break;

 case 'cap':
       $t = new DB_sel_l('cap','cdesc',$nsotvo,'ccod','nsotvo','cstat','cdesc','Capitolo','Capitolo dell\'articolo');
       echo $t->select_label()."<br >";
		break;

case 'art':
       $t = new DB_sel_l('art','atit',$nsotvo,'atit','nsotvo','astat','atit','Articolo','Articolo');
       echo $t->select_label()."<br >";
		break;

case 'lnk':
       $ty = new input(array($nsotvo,'nsotvo',30,'Link interno','','i'));
	   echo $ty->field();
		break;
    case 'vid':
           $ty = new input(array($nsotvo,'nsotvo',30,'Video YouTube','','i'));
    	   echo $ty->field();
    		break;
case 'php':
       $tw = new select_root($nsotvo,'nsotvo','Html/php pers.');
       $tw->select_dir();
       echo    "<br />";
		break;

case 'htm':
       $tx = new input(array($nsotvo,'nsotvo',30,'Pagina HTML custom','Pagina HTML personalizzata','i'));
	   echo $tx->field();
		break;

case 'cnt':
      $tw = new select_root($nsotvo,'nsotvo','Html/php pers.');
      $tw->select_dir();
      echo    "<br />";
      break;
case 'url':
       $tz = new input(array($nsotvo,'nsotvo',30,'Link esterno','Link esterno al programma','i'));
	   echo $tz->field();
		break;

    case 'ifr':
          $tw = new select_root($nsotvo,'nsotvo','Programma in iframe');
          $tw->select_dir();
          echo    "<br />";
          break;
   case 'pag':
         $tw = new select_root($nsotvo,'nsotvo','Pagina web','File della pagina');
          $tw->select_dir();
          echo    "<br />";
         break;   
    default:
    echo "Tipo voce errata=".$ntipo;//debug
    break;
}
      $tg = new DB_tip_i('trg','ntarget',$ntarget,'Target','Target:_blank ...');
		$tg->select();
      $f6 = new input(array($nselect,'nselect',1,'Voce corrente (*)','Voce selezionata','i'));
		$f6->field();
      $f7 = new input(array($ntitle,'ntitle',1,'(1)Titoli, (0)dettaglio','1-Mostra solo i titoli  0-mostra tutto','i'));
		$f7->field();
      $f8 = new input(array($nhead,'nhead',1,'(1)Header specifico','1-esiste un header specifico','i'));
		$f8->field();
      $fa = new input(array($npag,'npag',1,'Parametro','Parametro opzionale','i'));
		$fa->field();
      $tz = new input(array($naccesso,'naccesso',1,'Livello accesso','Livello di accesso utente','i'));
		$tz->field();
	}

	  $f2 = new input(array($nmetakey,'nmetakey',33,'Meta keywords','Keywords assegnate alla pagina','tx'));
		$f2->field();
	echo  "</fieldset>";
	echo "</form>";
      break;

      //==================================================================================
      case 'copia':
      //  toolbar
      	$param  = array('copia','ritorno');
      	$btx    = new bottoni_str_par('Voci di menù - copia','nav','write_nav.php',$param);
      		$btx->btn();
          $sql = "SELECT *
      			 FROM `".DB::$pref."nav`
      			 WHERE `nid` = $nid ";
      	foreach($PDO->query($sql) as $row)
      	{
            require('fields_nav.php');
            echo  "<fieldset >";
            $f0  = new input(array($nid,'nid',1,'ID record','','h'));
      		    $f0->field();
          $nav = new DB_ins('nav','nprog');
    		  $num = $nav->insert();
          $f1 = new field($num,'nprog',3,'Progressivo');
    		    $f1->field_i();
            $ts  = new DB_tip_i('stato','nstat',$nstat,'Stato record','Record attivo/annullato');
      		$ts->select();
            $mnu = new DB_sel_l('mnu','bprog',$nmenu,'bmenu',
                                'nmenu','bstat','bmenu','Menu','Nome menù');
      		$mnu->select_label();
            $f2 = new input(array($nli,'nli',20,'Voce','Voce di menù','i'));
      		$f2->field();
            $f4 = new input(array($ndesc,'ndesc',20,'Sottovoce','Sottovoce di menù','i'));
      		$f4->field();
            $f3 = new input(array($ntesto,'ntesto',25,'Descrizione','Descrizione voce di menù','i'));
      		$f3->field();
            $tv = new input(array($ntipo,'ntipo',10,'Tipo voce','Tipo voce di menù','r'));
      		$tv->field();

      switch ($ntipo)
      {
      case 'arg':
             $t = new DB_sel_l('arg','rdesc',$nsotvo,'rcod','nsotvo','rstat','rdesc','Argomento','Argomento dell\'articolo');
             echo $t->select_label()."<br >";
      		break;

       case 'cap':
             $t = new DB_sel_l('cap','cdesc',$nsotvo,'ccod','nsotvo','cstat','cdesc','Capitolo','Capitolo dell\'articolo');
             echo $t->select_label()."<br >";
      		break;

      case 'art':
             $t = new DB_sel_l('art','atit',$nsotvo,'atit','nsotvo','astat','atit','Articolo','Articolo');
             echo $t->select_label()."<br >";
      		break;

      case 'lnk':
             $ty = new input(array($nsotvo,'nsotvo',30,'Link interno','','i'));
      	   echo $ty->field();
      		break;
          case 'vid':
                 $ty = new input(array($nsotvo,'nsotvo',30,'Video YouTube','','i'));
          	   echo $ty->field();
          		break;
      case 'php':
             $tw = new select_root($nsotvo,'nsotvo','Html/php pers.');
             $tw->select_dir();
             echo    "<br />";
      		break;

      case 'htm':
             $tx = new input(array($nsotvo,'nsotvo',30,'Pagina HTML custom','Pagina HTML personalizzata','i'));
      	   echo $tx->field();
      		break;

      case 'cnt':
            $tw = new select_root($nsotvo,'nsotvo','Html/php pers.');
            $tw->select_dir();
            echo    "<br />";
            break;
      case 'url':
             $tz = new input(array($nsotvo,'nsotvo',30,'Link esterno','Link esterno al programma','i'));
      	   echo $tz->field();
      		break;

          case 'ifr':
                $tw = new select_root($nsotvo,'nsotvo','Programma in iframe');
                $tw->select_dir();
                echo    "<br />";
                break;
          default:
          echo "Tipo voce errata=".$ntipo;//debug
          break;
      }
            $tg = new DB_tip_i('trg','ntarget',$ntarget,'Target','Target:_blank ...');
      		$tg->select();
            $f6 = new input(array($nselect,'nselect',1,'Voce corrente (*)','Voce selezionata','i'));
      		$f6->field();
            $f7 = new input(array($ntitle,'ntitle',1,'(1)Titoli, (0)dettaglio','1-Mostra solo i titoli  0-mostra tutto','i'));
      		$f7->field();
            $f8 = new input(array($nhead,'nhead',1,'(1)Header specifico','1-esiste un header specifico','i'));
      		$f8->field();
            $fa = new input(array($npag,'npag',1,'Parametro','Parametro opzionale','i'));
      		$fa->field();
            $tz = new input(array($naccesso,'naccesso',1,'Livello accesso','Livello di accesso utente','i'));
      		$tz->field();
      	}

      	  $f2 = new input(array($nmetakey,'nmetakey',33,'Meta keywords','Keywords assegnate alla pagina','tx'));
      		$f2->field();
      	echo  "</fieldset>";
      	echo "</form>";
            break;

//==================================================================================

case 'cancella':
		// toolbar
	$param  = array('cancella','ritorno');
	$btx    = new bottoni_str_par('Voci di menù - conferma cancellazione','nav','write_nav.php',$param);
		$btx->btn();
    $sql = "SELECT * FROM `".DB::$pref."nav`
			 WHERE `nid` = $nid ";
    foreach($PDO->query($sql) as $row)
	{
    require('fields_nav.php');
      echo "<fieldset>";
$f0 = new field($nid,'nid',1,'ID record');
	$f0->field_h();
$f1 = new field($nprog,'nprog',3,'Progressivo');
	$f1->field_r();
$ts = new field($nstat,'nstat',1,'Stato record');
	$ts->field_r();
$tm = new field($nmenu,'nmenut',20,'Menu');
	$tm->field_r();
$f2 = new field($nli,'nli',20,'Voce');
	$f2->field_r();
$f4 = new field($ndesc,'ndesc',20,'Sottovoce');
	$f4->field_r();
$f3 = new field($ntesto,'ntesto',25,'Descrizione');
	$f3->field_r();
$tv = new field($ntipo,'ntipo',10,'Tipo voce');
	$tv->field_r();
$f8 = new field($nsotvo,'nsotvo',10,'Comando');
	$f8->field_r();
$f5 = new field($ntarget,'ntarget',20,'Target');
	$f5->field_r();
$f6 = new field($nselect,'nselect',1,'Voce corrente (*)');
	$f6->field_r();
$f7 = new field($ntitle,'ntitle',1,'(1)Titoli, (0)dettaglio');
	$f7->field_r();
$f9 = new field($nhead,'nhead',1,'(1)Header specifico');
	$f9->field_r();
$fk = new field($npag,'npag',1,'Parametro');
	$fk->field_r();
$tz = new field($naccesso,'naccesso',1,'Livello accesso');
	$tz->field_r();
$f2 = new input(array($nmetakey,'nmetakey',33,'Meta keywords','Keywords assegnate alla pagina','txr'));
	$f2->field();
      echo  "</fieldset>";
	}
      echo  "</form>";
      break;
}
echo "</body>";
ob_end_flush();
?>
