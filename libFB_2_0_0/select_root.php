<?php
/**===============================================================================
  Crea select dei files .php/.htm/.html contenuti nella root
  Metodi:
  select_dir()      elenca in una select i files nella root
  Variabili:
  valore            nome del file selezionato
============================================================================= */
 class select_root
{
        public $valini ='';
        public $campo  ='';
        public $valore ='';              // scelta della select
        public $label  ='';

    public function __construct($valini,$campo,$label)
               {
               $this->valini  = $valini;
               $this->campo   = $campo;
               $this->label   = $label;
               }

  public function select_dir()
{
$path = $_SERVER['SCRIPT_FILENAME'];  //identifica il percorso della Directory
$path_parts = pathinfo($path);        // effettua parsing della path
 // apre la directory
$dir_handle = opendir($path_parts['dirname']) or die("Impossibile aprire ".$path_parts['dirname']);
$dh  = opendir($path_parts['dirname']);
while (false !== ($filename = readdir($dh)))
     {
      $files[] = $filename;
     }
//closedir($path_parts);
sort($files);
echo "<fieldset class='input'><div>
          <label for='$this->campo' title='$this->label'>$this->label</label>";
echo "<select name='$this->campo'>";
echo "<option value=''>Scegliere il file</option>";

 foreach ($files as $key=>$this->valore)
     {
          if (strpos($this->valore, '.php',1)
           || strpos($this->valore, '.html',1)
           || strpos($this->valore, '.htm',1))
          {
   //       echo "<a href='$file'>$file</a><br/>";
               if ($this->valini == $this->valore)
                 {
                 echo "<option selected='selected' value=".$this->valini.">
                       ".$this->valini."
                       </option>";
                 }
                 else  echo "<option value=".$this->valore.">".$this->valore."</option>";

          }
     }
echo "</select>";
}

}
?>