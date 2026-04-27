<?php
/* ======================================================
	06/03/21	select dei settori presenti in XDB
   ====================================================== */
	
class DB_xdb          extends DB

{
    public function __construct()       
           { 
           }   

    public function settori()      // crea select dei settori di XDB
           {
           echo "<div>";
           echo "<label for='settori' title='Settori'>Settori</label>";
           echo "<select name='settori'>";
          $con = "mysql:host=".self::$host.";dbname=".self::$db."";
          $PDO = new PDO($con,self::$user,self::$pw);
          $PDO->beginTransaction();

		         echo "<option value='tutti'>
                       tutti
                       </option>"; 
                       echo "tutti"."<br >"; 
					   
 echo             $sql="SELECT DISTINCT xtipo 
                    FROM ".self::$pref."xdb"."
                    ORDER BY xtipo ";
          foreach($PDO->query($sql) as $row)

		  {
       echo "<option value='".$row['xtipo']."'>
                       ".$row['xtipo']."
                       </option>"; 
                       echo $row['xtipo']."<br >";                  
            }  
           echo "</select>";
		   echo "</div>";
          }
		  
}		  
?>