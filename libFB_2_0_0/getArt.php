<?php
/**
      * @class:      getArt
      *
      * @description:ritorna i campi del record ricercato
      *
      * @author Fausto Bresciani <fbsoftware@libero.it>
      * @version 0.1
      */
     
class getArt          extends  DB  
     { // BEGIN class getArt
     
     	// variabili
     	public $titolo  =  ''; 
          public $aid     =  ''; 
          public $astat   =  ''; 
          public $aprog   =  0; 
          public $atit    =  ''; 
          public $aalias  =  ''; 
          public $atext   =  ''; 
          public $aarg    =  '';  
          public $acap    =  '';  
     	public $amostra =  0; 

     	// costruttore
       public function __construct($titolo)
     	{
     	$this->titolo  = $titolo ;
          $this->aid     = $aid ;
          $this->astat   = $astat ;
          $this->aprog   = $aprog ;
          $this->atit    = $atit ;
          $this->aalias  = $aalias ;
          $this->atext   = $atext ;
          $this->aarg    = $aarg ;
          $this->acap    = $acap ;
          $this->amostra = $amostra ;
     	}

/************************************************
 * @method:   getFieldsArt()
 * @description:
 * **********************************************/
  public function getFieldsdArt()
     {
              $con = "mysql:host=".self::$host.";dbname=".self::$db."";
              $PDO = new PDO($con,self::$user,self::$pw);
              $PDO->beginTransaction();
              $sql="SELECT *
                    FROM `".DB::$pref."art`
                    WHERE  astat <> 'A' and atit = '$this->titolo' limit 1";
              foreach($PDO->query($sql) as $val)
              {  
               $this->aid       =$val['aid'];
               $this->astat     =$val['astat'];
               $this->aprog     =$val['aprog'];
               $this->atit      =$val['atit'];
               $this->aalias    =$val['aalias'];
               $this->atext     =$val['atext'];
               $this->aarg      =$val['aarg'];
               $this->acap      =$val['acap'];
               $this->amostra   =$val['amostra'];
               }


     } 
}     // END class getArt
?>