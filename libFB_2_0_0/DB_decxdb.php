<?php
/**=============================================================================== 
  Funzioni di utilita' database
  Metodi:
 decod()              decodifica codice di 'xdb'
 ============================================================================= */
class DB_decxdb          extends DB

{       
        public $campo   ='';
        public $tipo    ='';
                                                     
    public function __construct($tipo,$campo)       
           { 
           $this->tipo    = $tipo;           // valore tipo (settore)
           $this->campo   = $campo;          // valore campo chiave
           }   
             
    public function decxdb()       // decodifica del campo chiave
           { 
           $PDO = new DB(); 
              $con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
              $PDO = new PDO($con,DB::$user,DB::$pw);
              $PDO->beginTransaction();
           $sql="SELECT * 
                 FROM ".DB::$pref."xdb 
                 WHERE xstat !='A' 
                    and  xtipo = '".$this->tipo."'
                    and  xcod  = '".$this->campo." '" ;
     foreach($PDO->query($sql) as $row)                     
             {
              return $row['xdes'] ;  
             }
            }
}

?>