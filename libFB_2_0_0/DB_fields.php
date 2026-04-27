<?php
/**
Effettua la scrittura di un record in una tabella di database.
database già connesso e transazione pronta.
--------------------------------------------------------------*/
class DB_fields extends DB
{
  public $tab = "";

public function __construct($tab)
  {
    $this->tab = $tab;
  }

public function write()
{
  if (isset($this->tab))
       {
  $sql = "SHOW FULL COLUMNS FROM ".$this->tab;
  $campi = "(";
  $valori = "VALUES(";
  $i   = 0;
  			foreach($PDO->query($sql) as $row)

          {
  				$i++;
  				$campi .= "".$row[0].",";
  				if ($i == 1) {
  					$valori .= "NULL,";
  				}
  				else {
  					$valori .= "'$".$row[0]."',";
  				}


                 }
  						// nomi
  						 $l = strlen($campi);
  						 $campi = substr($campi,0,($l-1));
  							 $campi .= ")";
  							 echo $campi;
  							 // valori
  								$l = strlen($valori);
  								$valori = substr($valori,0,($l-1));
  									$valori .= ")";
  									echo "<br>".$valori;
  			}
        $sql = "INSERT INTO `".DB::$pref.$this->tab."`
                    ".$campi."
                    VALUES ".$valori." ";
        $PDO->exec($sql);
        $PDO->commit();
        array_push($_SESSION['esito'],'54');
}

}
 ?>
