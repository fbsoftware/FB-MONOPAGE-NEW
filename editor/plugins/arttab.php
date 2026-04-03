<section id="arttab" class="editor-widget" data-widget="arttab">
  <script>
  $( function() {
    $( "#tabs" ).tabs();
   } 
  );
    $( function() {
    $( "div[role='tabpanel']" ).removeClass("ui-widget-content");
   } 
  );
  </script>
 <style type="text/css">
 /* toglie il padding per allineare le section */
 .ui-tabs{
	 padding: 0 !important;
 }
</style> 
<?php
// cerca nel layout gli articoli per i tab
	 $sql = "SELECT *
                FROM `".DB::$pref."asl`
                WHERE dtmp = '".TMP::$tmenu."'
                    and dtipo = 'arttab'                
                    and dstat <> 'A' 
                    and dcod = '$lcod'
                ORDER BY dprog ";
	foreach($PDO->query($sql) as $rowx)
	{     
          $dcap 	= $rowx['dcap'];
          $ddes 	= $rowx['ddes'];

// lettura articoli della categoria
           $titolo = array();
           $testo  = array();
		   $count=0;
		$sql = "SELECT *
                FROM `".DB::$pref."art`
                WHERE acap = '$dcap'
                    and astat <> 'A' 
                ORDER BY aprog ";
	   foreach($PDO->query($sql) as $row)
        { 
			if ($row['atit']) // omette articoli vuoti
			{
			 array_push($testo ,$row['atext']);	
			 array_push($titolo,$row['atit']);
			 $count++;
			}
        }
	}  ++$i;

// pannello se richiesto
	if (TMP::$ttabtitle == 1) 
{
		echo "<div class='f-flex fd-column  fb-primary'>"; 
		if (TMP::$ttabtit > " ")  { echo "<h1>".TMP::$ttabtit."</h1>"; } 
		if (TMP::$ttabtext > " ") { echo "<p>".TMP::$ttabtext."</p>"; }
		echo "</div>";
}
			
 // articoli 
 echo "<div id = 'tabs' class='fb-secondary' >";

// lettura degli articoli del capitolo

 echo "<ul class='fb-secondary'>";
for ($i = 0; $i < $count; ++$i)
	{
	echo "<li><a href='#tabs-".$i."' >".$titolo[$i]."</a></li>";	  
	}
	echo "</ul>";	
// lettura testi per tab 
for ($i = 0; $i < $count; ++$i)
	{
    echo "<div id='tabs-".$i."'  class='fb-secondary'>";
    $a = new txt($testo[$i]);
		$a->ingloba();
    echo "</div>";                
	}  
echo "</div>";		// #tabs -->
echo "</section>";
?>