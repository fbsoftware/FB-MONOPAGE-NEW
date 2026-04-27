<?php
/*===============================================================================
  Funzioni di utilita' database
  Metodi:
  select_table()             Mostra struttura tabelle del database aperto
============================================================================= */
class DB_sel_table
{
     public $tabella   ='';
     public $pref      ='';

     public function __construct($pref)
          {
           $this->pref = $pref;
          }

    public function select_table()
          // crea select su un campo
          {
     $con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
     $PDO = new PDO($con,DB::$user,DB::$pw);
     $PDO->beginTransaction();  
	 
		echo "<label for='table'>Scegliere la tabella     </label>";
		echo "<select name='table'>";
		$sql = "SHOW TABLES";
		$result = $PDO->query($sql);
		
//		print_r($result);//debug
		while ($tableName = $result->fetch(PDO::FETCH_NUM))
               {
        echo  $this->table = $tableName[0];
               $is = strpos($this->table, $this->pref);
               if ($is !== false)    // filtro il prefisso da considerare
                    {
                    echo "<option value='".$this->table."'>".$this->table."</option>";
                    }
          }
           echo "</select>";
}
}
?>