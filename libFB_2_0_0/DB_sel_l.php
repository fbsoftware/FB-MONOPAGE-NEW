<?php
/**==============================================================================
  Funzioni di utilita' database
  Metodi:
  select()             select di campo di tabella con label
============================================================================= */
class DB_sel_l          extends DB

{
        public $label   ='';

    public function __construct($tabella,$prog,$valini,$campo,$select,$stato,$option,$label,$toolt)
           {
           $this->tabella = $tabella;        // nome tabella contenente i campi
           $this->prog    = $prog;           // campo di ordinamento
           $this->valini  = $valini;         // valore iniziale (if selected)
           $this->campo   = $campo;          // valore passato a POST
           $this->select  = $select;         // nome variabile POST (name-select) del DB di destinazione
           $this->stato   = $stato;          // campo stato record (!A=valido)
           $this->option  = $option;         // option da mostrare (descrizione)
           $this->label   = $label;          // label della select
           $this->toolt   = $toolt;          // Tooltip relativo
           }

    public function select_label()           // crea select con label su un campo
           {
               if ($this->label > '') 
               {
                echo "<div>";
                echo "<label for='$this->select' title='$this->toolt'>$this->label</label>";
                echo "<select name='$this->select'>";
                $con = "mysql:host=".self::$host.";dbname=".self::$db."";
                $PDO = new PDO($con,self::$user,self::$pw);
                $PDO->beginTransaction();

                echo    $sql="SELECT *
                          FROM ".self::$pref.$this->tabella."
                          WHERE ".$this->stato." !='A'
                          ORDER BY ".$this->campo." ";

                foreach($PDO->query($sql) as $row)
                  {
                    if ($row[$this->campo] == $this->valini)
                      {echo "<option selected='selected' value='".$row[$this->campo]."'>".$row[$this->option]."</option>"; }
                    else
                      {echo "<option value='".$row[$this->campo]."'>".$row[$this->option]."</option>";
                            echo $row[$this->campo]."<br >";}
                  }                 
                echo "</select>";
            echo "</div>";
              }
           }
}
?>