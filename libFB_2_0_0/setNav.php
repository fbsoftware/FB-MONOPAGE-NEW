<?php
/**=============================================================================== 
  Settaggio della voce iniziale del menù
  Metodi:
  setNav()         setta la voce di menu corrente 
============================================================================= */
class setNav          

{       public $ambito='';

        public function __construct($ambito)     
          {
          $this->ambito   = $ambito;
         	}

        public function setNav()
	   {
        
// pulisce navigatore scelto  
     $con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
     $PDO = new PDO($con,DB::$user,DB::$pw);
     $PDO->beginTransaction();      
     $sql = "UPDATE ".DB::$pref."nav 
			SET nselect=0 
			WHERE nmenu='".$this->ambito."' ";    
     $PDO->exec($sql);    
     $PDO->commit();
     $con = NULL;
                  
// legge la prima voce se manca
     if($forma <= '')    
     	{
     	$con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
     	$PDO = new PDO($con,DB::$user,DB::$pw);
     	$PDO->beginTransaction();
     	$sql = "SELECT * FROM `".DB::$pref."nav`
		          WHERE nmenu='".$this->ambito."'
				ORDER BY `nprog` limit 1";
     	foreach($PDO->query($sql) as $row)
     		{
     		$forma    =$row['nli'];
     		$sub      =$row['ndesc'];
     		}
     	$con = NULL;
     	} 

// contrassegna come voce corrente 
          $con = "mysql:host=".DB::$host.";dbname=".DB::$db."";
     	$PDO = new PDO($con,DB::$user,DB::$pw);
     	$PDO->beginTransaction();
    
     	$sql = "UPDATE  ".DB::$pref."nav  
               	SET  nselect =1 
               	WHERE nli='$forma' and ndesc='$sub' and nmenu='".TMP::$tmenu."' ";
		$PDO->exec($sql);    
          $PDO->commit();
          $con = NULL;
 	}
} 
?>