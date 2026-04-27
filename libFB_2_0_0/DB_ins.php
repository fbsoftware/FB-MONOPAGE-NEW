<?php
/**===============================================================================
  Calcola in valore del progressivo per inserimento record nel database
  Metodi:
  insert()
  Ritorna : progressivo per inserimento
/**  -----------------------------------------------------------
  Esempio: $x = new DB_ins('tabella','campo del progressivo')
============================================================================= */
class DB_ins          extends DB
{
        public static $con  = '';         // collegamento
        public static $PDO  = '';         // collegamento PDO


		public $tabella ='';
        public $progr    ='';
// variabili interne
        public $max     = 0;

    public function __construct($tabella,$progr)
           {
		   self::$con       = $con;
		   self::$PDO       = $PDO;
           $this->tabella 	= $tabella;
           $this->prog    	= $progr;     // campo del progressivo di ordinamento
			//parent::__construct();
			self::$incr;//debug

           }

       public function insert()          // n° record inserimento da confog.ini
              {
              $con = "mysql:host=".self::$host.";dbname=".self::$db."";
              $PDO = new PDO($con,self::$user,self::$pw);
              $PDO->beginTransaction();

              $sql = "   SELECT *
                         FROM ".self::$pref.$this->tabella."
                         ORDER BY ".$this->prog." desc
                         limit 1";

              foreach($PDO->query($sql) as $row)
                    {
                    $this->max  =($row[$this->prog] + self::$incr);
                    }
                    if ($this->max == 0) {$this->max = self::$incr;}
               return $this->max;
              }

       public function insert1()          // n° record inserimento +1
              {
             {
              $con = "mysql:host=".self::$host.";dbname=".self::$db."";
              $PDO = new PDO($con,self::$user,self::$pw);
              $PDO->beginTransaction();

              $sql = "   SELECT *
                         FROM ".self::$pref.$this->tabella."
                         ORDER BY ".$this->prog." desc
                         limit 1";

              foreach($PDO->query($sql) as $row)
                    {
                    $max =($row[$this->prog] + 1);
                    $this->max = $max;
                    }
                    if ($this->max == 0) {$this->max = self::$incr;}
               return $this->max;
              }
              }


}
?>
