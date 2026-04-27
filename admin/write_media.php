<?php  session_start();
/*** Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
   * package		FB open template
   * versione 3.1
   * copyright	Copyright (C) 2025 - 2026 FB. All rights reserved.
   * license		GNU/GPL
   * Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
   * all'uso anche improprio di FB open template.
   * ------------------------------------------------------------------------
   * Caricamento/cancellazione immagini - esecuzione
============================================================================= */
require_once('init_admin.php');
print_r($_POST);//debug
$azione  = $_POST['submit'];
$_SESSION['esito'] = array();

// mostra stringa bottoni o chiude
switch ($azione)
{
     case 'uscita':
     array_push($_SESSION['esito'],'2');
     $loc = "location:admin.php?urla=widget.php&pag=";
     header($loc);
  break;
	 case 'ritorno':
          array_push($_SESSION['esito'],'2');
          header('location:admin.php?'.$_SESSION['location'].'');
     	break;
// ======================= cancella =============================

     case 'cancella':
	 echo "<br />images/".$img_del;
          unlink("images/".$img_del);
          array_push($_SESSION['esito'],'56');
          header('location:admin.php?'.$_SESSION['location'].'');
     	break;
// ======================= upload =============================
     case 'upload':
// per prima cosa verifico che il file sia stato effettivamente caricato
if (!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name']))
	{
	echo 'Non hai inviato nessun file...';
	exit;
	}
else
	{
	echo "<br />".$uploadDir = 'images/';
	echo "<br />".$userfile_tmp = $_FILES['file']['tmp_name'];
	echo "<br />".$userfile_name = $_FILES['file']['name'];
	echo "<br />".$userfile_tmp."\". $uploaDdir.$userfile_name";
	echo "<br />images/".$_FILES['file']['name'];
if (move_uploaded_file($userfile_tmp, "images/".$_FILES['file']['name']))
	{   //Se l'operazione è andata a buon fine...
	echo 'File inviato con successo.';
  //header('location:admin.php?'.$_SESSION['location'].'');
  }
else{	//Se l'operazione è fallta...
  {
	 array_push($_SESSION['esito'],'3');
   //header('location:admin.php?'.$_SESSION['location'].'');
	}
          array_push($_SESSION['esito'],'57');
          //header('location:admin.php?'.$_SESSION['location'].'');
  }
}
          break;
// ======================= download =============================

case 'download':
//impostiamo la cartella in cui sono presenti i file per il download
$path = $img_del;

// eseguiamo alcuni controlli preventivi
if($img_del == ''){  exit('Nessun file indicato');  }

else if(!is_file($path)){   exit('Il file non esiste');   }

else if(!is_readable($path)){  exit('Il file non ha i permessi per essere scaricato');   }

// otteniamo alcune info sul file
$info = pathinfo( $path );
$extension = $info['extension']; // estensione
$size = filesize($path); // dimensione in byte
$time_file = date( 'r', filemtime( $path ) ); // time ultima modifica

// inviamo gli opportuni headers
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'. basename($path) .'"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . $size);
header('Last-Modified: ' . $time_file);
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

// eliminiamo eventuale output inviato
 ob_end_flush();

// leggiamo il file e inviamo l'output
@readfile($path) or die('SERVER ERROR!');
array_push($_SESSION['esito'],'58');
     break;
}

//header('location:admin.php?'.$_SESSION['location'].'');

?>
