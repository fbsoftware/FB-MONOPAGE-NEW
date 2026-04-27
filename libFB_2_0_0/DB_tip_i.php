<?php
/**=============================================================================== 
  Select delle tipologie prestabilite
  Metodi:
  select()         select della tipologia richiesta 
                    + label, se valorizzata
                    + tooltip se valorizzato
============================================================================= */
class DB_tip_i          extends DB

{       public $tipo   ='';        // tipologia
        public $nome   ='';        // nome della variabile POST
        public $valini ='';        // valore iniziale (if)
        public $label  ='';        // label del campo (if)
        public $toolt  ='';        // Placeholder-tooltip
        
        public function __construct($tipo,$nome,$valini,$label,$toolt)       
               { $this->tipo    = $tipo;     
                 $this->nome    = $nome;     
                 $this->valini  = $valini;  
                 $this->label   = $label; 
                 $this->toolt   = $toolt;                   
               }

        public function select()       
          { 
               echo "<div>"; 
               if ($this->label > '')
               {
               echo "<label for='$this->nome' title='$this->toolt' name='$this->toolt'>$this->label</label>";
               echo "<select name='$this->nome'";
               echo " ><br >";
               }
               $con = "mysql:host=".self::$host.";dbname=".self::$db.""; 
               $PDO = new PDO($con,self::$user,self::$pw);
               $PDO->beginTransaction();			   
               $sql = "SELECT * FROM ".self::$pref."xdb
                  WHERE xtipo = '$this->tipo' and xstat <> 'A'       
                  ORDER BY xtipo,xdes";  
                                                  
              foreach($PDO->query($sql) as $row)            
              {  
              if    ($row['xcod'] == $this->valini)
                    {echo "<option selected value='".$row['xcod']."'>
                       ".$row['xdes']."</option>"; }
              else
                    echo "<option value=".$row['xcod'].">".$row['xdes']."</option>"; 
              }
            echo "</select></div>";
          } 
          
        public function select_lin()      // per lingue con bandierina 
          { 
               echo "<div>"; 
               if ($this->label > '')
               echo "<label for='$this->nome' title='$this->toolt'>$this->label</label>";
               echo "<select name='$this->nome'";
               echo " ><br >";
               $con = "mysql:host=".self::$host.";dbname=".self::$db.""; 
               $PDO = new PDO($con,self::$user,self::$pw);
               $PDO->beginTransaction();
               $sql = "SELECT * FROM ".self::$pref."xdb
                  WHERE xtipo = '$this->tipo'        
                  ORDER BY xtipo,xdes";  
                  
              foreach($PDO->query($sql) as $row)            
              {  if ($row['xcod'] == $this->valini)
                 {echo "<option selected='selected' value='".$row['xcod']."'>
                       ".$row['xdes']."</option>"; }
              else {
              echo "<option value=".$row['xcod']." alt='naz' style='background: #ff0000 url(images/".$row['xcod'].".jpg) no-repeat 0,0;'>".$row['xdes']."</option>";  }
              }
            echo "</select></div>";
          } 

}  
?>