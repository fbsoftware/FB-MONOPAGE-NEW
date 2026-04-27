<?php
/**
 * @class:      imgTable
 *
 * @description:Tabella con immagini dimensionate e celle per riga
 *
 * @author Fausto Bresciani <fbsoftware@libero.it>
 * @version 0.1
 */
class imgTable
{ // BEGIN class imgTable
	// variabili
	public $path   = NULL;       // directory delle immagini
	public $height = 0;          // altezza max immagine
	public $width  = 0;          // larghezza max immagine
	public $numero = 0;          // celle per riga
	// costruttore
  public function __construct($path,$height,$width,$numero)
	{
	$this->path   = $path ;
	$this->height = $height ;
     $this->width  = $width ;
     $this->numero = $numero ;
	}
/************************************************
 * @method:   putTable()
 * @description:Emette immagini
 * **********************************************/
  public function putTable()
     {
		 $array_file=array();
echo    "<div class='f-flex fd-row fw'>";
		 
// lettura directory
foreach (glob($this->path."*.*") as $key => $gx)
{    $array_file[$key] = $gx; }

// cartella immagini generali sel sito, mostro i file in una tabella
$foto = 0;
$riga = $this->numero;
$conto2=count($array_file);
for($b=0; $b<$conto2; $b++)
		{
		
		echo "<div class='f-item' style='width: 10%;'>";
		echo "<input type='checkbox' value=''/>";
		$file_ext = substr($array_file[$b], strripos($array_file[$b], '.'));
		if  ($file_ext=='.jpg'  ||$file_ext=='.JPG'
       ||$file_ext=='.png'  ||$file_ext=='.PNG'
       ||$file_ext=='.gif'  ||$file_ext=='.GIF'
       ||$file_ext=='.bmp'  ||$file_ext=='.BMP'
       ||$file_ext=='.ico'  ||$file_ext=='.ICO')
        {
        echo  "<a href='".$array_file[$b]."' target='_blank'>";	
		$lato = new img_dim($array_file[$b]);
		$max = $lato-->maxdim();
		if ($max == "L") 
			{  echo "<img src='".$array_file[$b]."'  width='".$this->width."' >";
		} else {
				echo "<img src='".$array_file[$b]."'  width='".$this->height."' >";
		}
		echo "</a>";
		}
echo "</div>";
		}
echo "</div>";
		
	 }
 }
?>