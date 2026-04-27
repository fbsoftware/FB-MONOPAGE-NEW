<?php
/**===============================================================================
  Crea select dei files .php/.htm/.html contenuti in una cartella  
  Metodi:
  file()            elenca in una select i files nella directory
  Variabili:
  valore            nome del file selezionato
============================================================================= */
class select_file 
{        
        public $path   ='';
        public $valini =''; 
        public $campo  ='';
        public $valore ='';              // scelta della select
        public $label  ='';
        public $toolt  ='';              // tooltip
         
    public function __construct($path,$valini,$campo,$label,$toolt)       
               { 
               $this->path    = $path;
               $this->valini  = $valini;
               $this->campo   = $campo;
               $this->label   = $label; 
               $this->toolt   = $toolt;                                            
               }   

    public function file()               
    {   
$dh  = opendir($this->path);
if (is_dir($this->path)) 
{
while (false !== ($filename = readdir($dh))) 
     {
     if(!is_dir($filename))   $files[] = $filename;
     }
//closedir($this->path);       
} 
else {
	echo "Directory non trovata=$filename";
}
// elaborazione dell' array 
sort($files);
echo "<br />";
$nx=count($files);
 echo "<label for='$this->campo' title=$this->toolt>$this->label</label>";
 echo "<select name='$this->campo'>";
for ($n=0; $n<$nx ;$n++ ) 
{
	

if (($files[$n] != '.') && ($files[$n] != '..'))
{
     $file_ext = substr($files[$n], strripos($files[$n], '.')); 
     if  ($file_ext == '.php'  || $file_ext == '.htm' || $file_ext == '.html')
     {
          if ($files[$n] == $this->valini) 
          echo "<option selected='selected' value='$this->valini'>".$files[$n]."</option>";	                                              
          else
          echo "<option value='$files[$n]'>".$files[$n]."</option>";	
     }
}

}
echo "</select>";
}

// ==================================
        public function image()               
    {    
     $f=opendir($this->path) ;
     while(false!==($g=readdir($f)))          //legge fino a false
     {
         if($g!="." && $g!="..")              //elimino il punto ed i doppi punti
         {   if(is_dir($this->path.$g))            //creo un array con le directory trovate
             { $array_dir[]=$g; }
               if(is_file($this->path.$g))       //creo un array con i file trovati
                  { $array_file[]=$g;
                  $numg++; }                 //numero di file trovati
         }
     }
     closedir($f);                           //chiudo la directory
           echo "<fieldset class='input'><div>
                 <label for='$this->campo' title='$this->toolt'>$this->label</label>";    
           echo "<select name='$this->campo'>";
           echo "<option value=''>".NULL."</option>";
           $conto2=count($array_file);
           for($b=0; $b<$conto2; $b++)
           { 
           $file_ext = substr($array_file[$b], strripos($array_file[$b], '.')); 
          if  ($file_ext=='.jpg'  || $file_ext=='.png' || $file_ext=='.gif'
             || $file_ext=='.JPG'  || $file_ext=='.PNG' || $file_ext=='.GIF')
           $this->valore = $array_file[$b]; 
               { 
               if ($this->valini == $this->path.$this->valore)
                 {
                 echo "<option selected='selected' value=".$this->valini.">
                       ".$this->valini."
                       </option>"; 
                 }
                 else  echo "<option value=".$this->path.$this->valore.">".$this->path.$this->valore."</option>"; 
               }
           }    
           echo "</select></div></fieldset>";
    }
}
?>