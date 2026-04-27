<?php
/**=============================================================================== 
  Classe 'DB_mnu'     Funzioni per tabella 'mnu'
  Metodi:
  select_mnu()         select dei menu
============================================================================= */
class DB_mnu          extends DB

{       public $valore='';   
  
		public function __construct($valore)       
               { $this->valore = $valore; }   
             
         
        public function select_mnu2()       // tipo menu
          { echo "<select name=menu>";
            $sql="SELECT DISTINCT bmenu 
                         FROM ".self::$pref."mnu 
                         ORDER BY bmenu";
            $result = mysql_query($sql);
            while($row = mysql_fetch_array($result))
              {   // includi tutto  
                if  ($n == 0)
                {
                     if ( $_SESSION['selected'] <> '') 
                        {echo "<option selected value='".$_SESSION['selected']."'>".$_SESSION['selected']."</option>"; }
                echo "<option value='tutto' name='sel_mnu'>Tutti ...</option>"; 
                $n++;
                }
              echo "<option  name='sel_mnu' value=".$row['bmenu'].">".$row['bmenu']."</option>";  
              }
            echo "</select>";
          }  
   
}
?>