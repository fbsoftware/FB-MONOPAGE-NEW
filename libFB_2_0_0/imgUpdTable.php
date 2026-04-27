<?php
/**
================================================================
  class:      imgUpdTable
  description:Tabella con immagini ridimensionate 
  celle per riga
  e possibilità di upload/download.
 author Fausto Bresciani <fbsoftware@libero.it>
 version 0.1
 ================================================================ */
class imgUpdTable    extends imgTable
{ // BEGIN class imgUpdTable
	// variabili
	public $path   = NULL;       // directory delle immagini
	public $height = 0;          // altezza max immagine
	public $width  = 0;          // larghezza max immagine
	public $numero = 0;          // celle per riga
	public $callbk = "";          // action del form
	public $path_img = "";
  public $url = "";

	// costruttore
public function __construct($path, $url, $height, $width, $numero, $callbk)
{
    $this->path   = $path;
    $this->url    = $url;
    $this->height = $height;
    $this->width  = $width;
    $this->numero = $numero;
    $this->callbk = $callbk;
}
/************************************************
 * @method:   putTable()
 * @description:Emette la tabella con immagini
 * **********************************************/
  public function putUpdTable()
     {

$array_file = [];

foreach (glob(rtrim($this->path, '/') . "/*.*") as $filePath) {
    $array_file[] = $filePath;
}

$nn = 0;

echo "<div class='tabella'>";
echo "<table cellpadding='10'>";
echo "<tr><td>";

foreach ($array_file as $filePath) {

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico', 'webp'])) {
        continue;
    }

    $fileName = basename($filePath);
    $fileUrl  = rtrim($this->url, '/') . '/' . $fileName;

    $dim = getimagesize($filePath);

    if ($dim === false) {
        continue;
    }

    $x = $dim[0];
    $y = $dim[1];

    echo "<form method='post' action='" . htmlspecialchars($this->callbk, ENT_QUOTES) . "'>";

    echo "<a class='img' href='" . htmlspecialchars($fileUrl, ENT_QUOTES) . "' target='_blank'>";

    echo "<img class='img-centro'
               src='" . htmlspecialchars($fileUrl, ENT_QUOTES) . "'
               style='
                    max-width:" . (int)$this->width . "px;
                    max-height:" . (int)$this->height . "px;
                    width:auto;
                    height:auto;
                    object-fit:contain;
                    display:block;
               '
               alt=''>";

    echo "</a><br>" . htmlspecialchars($fileName, ENT_QUOTES);

    echo "<div class='row'>";

    echo "<input type='hidden' name='img_del' value='" . htmlspecialchars($filePath, ENT_QUOTES) . "'>";

    echo "<button name='submit' type='submit' value='cancella' class='fb-primary'
              style='padding:5px;margin-top:5px;'>
            <img src='images/bottoni/cancella.png' width='18' vspace='0'
                 alt='canc' align='right'>Cancella
          </button>";

    echo "<button name='submit' type='submit' value='download' class='fb-primary'
              style='padding:5px;margin-top:5px;'>
            <img src='images/bottoni/download.png' width='18' vspace='0'
                 alt='download' align='right'>Download
          </button>";

    echo "</div>";
    echo "</form>";

    $nn++;

    if ($nn % $this->numero == 0) {
        echo "</td></tr><tr><td>";
    } else {
        echo "</td><td>";
    }
}

echo "</td></tr>";
echo "</table>";
echo "</div>";
     }
}