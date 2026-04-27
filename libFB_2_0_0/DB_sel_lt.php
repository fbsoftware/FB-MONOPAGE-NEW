<?php
/**==============================================================================
  Funzioni di utilita' database
  Metodi:
  select()             select di campo di tabella con label e tooltip
============================================================================= */
class DB_sel_lt          extends DB

{
        public $tabella;        // tabella
        public $progr;           // campo del progressivo di ordinamento
        public $valini;         // valore iniziale (if selected)
        public $campo;          // valore passato a POST
        public $select;         // nome variabile POST (name-select)
        public $stato;          // campo stato record (!A=valido)
        public $option;         // campo option da mostrare
        public $label;          // label select
        public $toolt;          // tooltip

    public function __construct($tabella,$progr,$valini,$campo,$select,$stato,$option,$label,$toolt)
           {
           $this->tabella = $tabella;        // tabella
           $this->prog    = $progr;           // campo del progressivo di ordinamento
           $this->valini  = $valini;         // valore iniziale (if selected)
           $this->campo   = $campo;          // valore passato a POST
           $this->select  = $select;         // nome variabile POST (name-select)
           $this->stato   = $stato;          // campo stato record (!A=valido)
           $this->option  = $option;         // campo option da mostrare
           $this->label   = $label;          // label select
           $this->toolt   = $toolt;          // tooltip
           }

    public function select_lt()           // crea select con label e tooltip su un campo
           {
           echo "<div>";
               if ($this->label > '')
           echo "<label for='$this->select' title='$this->toolt'>$this->label</label>";
           echo "<select name='$this->select'>";
          $con = "mysql:host=".self::$host.";dbname=".self::$db."";
          $PDO = new PDO($con,self::$user,self::$pw);
          $PDO->beginTransaction();

              $sql="SELECT *
                    FROM ".self::$pref.$this->tabella."
                    WHERE ".$this->stato." !='A'
                    ORDER BY ".$this->prog." ";
          foreach($PDO->query($sql) as $row)
            {

               if ($row[$this->campo] == $this->valini)
                 {echo "<option selected='selected' value='".$row[$this->campo]."'>".$row[$this->option]."</option>"; }
               else
                 {echo "<option value='".$row[$this->campo]."'>".$row[$this->option]."";
                       echo $row[$this->campo]."</option><br >";}
            }
           echo "</select>";
		   echo "</div>";
          }
}
?>
