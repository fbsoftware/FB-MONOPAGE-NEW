<?php
/* ======================================================
	07/03/21	select dei menù presenti in NAV
   ====================================================== */
	
class DB_nav          extends DB

{
    public function __construct()       
           { 
           }   

    public function menu()      // crea select dei menu di NAV
           {
           echo "<div>";
           echo "<label for='menu' title='Nome del menu'>Voci del menù</label>";
           echo "<select name='menu'>";
          $con = "mysql:host=".self::$host.";dbname=".self::$db."";
          $PDO = new PDO($con,self::$user,self::$pw);
          $PDO->beginTransaction();

		         echo "<option value='tutti'>
                       tutti
                       </option>"; 
                       echo "tutti"."<br >"; 
					   
              $sql="SELECT DISTINCT nmenu
                    FROM ".self::$pref."nav"."
                    ORDER BY nmenu ";
          foreach($PDO->query($sql) as $row)

		  {
       echo "<option value='".$row['nmenu']."'>
                       ".$row['nmenu']."
                       </option>"; 
                       echo $row['nmenu']."<br >";                  
            }  
           echo "</select>";
		   echo "</div>";
          }
		  
}		  
?>