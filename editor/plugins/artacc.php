<?php
/**
   =======================================================
	Articoli di un capitolo in ACCORDION
   =======================================================*/
?>
<section id="artacc" class="editor-widget" data-widget="artacc">
  <script>
  $( function() {
    $( "#accordion" ).accordion({
      collapsible: true,
	  heightStyle: "content"
    });
	    $( "#accordion .ui-accordion-content .ui-widget-content" ).removeClass('ui-widget-content');
        $( "#accordion .ui-accordion-content .ui-widget-content" ).addClass('fb-secondary');
  		$( "#accordion .ui-state-default" ).removeClass('ui-state-default');
        $( "#accordion .ui-state-default" ).addClass('fb-primary');
		$( "#accordion" ).addClass('fb-secondary');
  } );
  </script>
<?php

// cerca nel layout gli articoli per accordion
		$sql = "SELECT *FROM `".DB::$pref."asl`
                WHERE dtmp = '".TMP::$tmenu."'
                    and dtipo = 'artacc'                
                    and dstat <> 'A' 
                    and dcod = '$lcod'
                ORDER BY dprog ";
          foreach($PDO->query($sql) as $row)
          {  
			require('admin/fields_asl.php');	
	
	// stampa il titolo dell'accordion se richiesto	
	if (TMP::$taccotitle == 1) 
	{
		echo "<div class='f-flex fd-column ui-state-default fb-primary'>"; 
		if (TMP::$taccotit > " ") { echo "<h1>".TMP::$taccotit."</h1>"; } 
		if (TMP::$taccotext > " ") { echo TMP::$taccotext; }
		echo "</div>";	
	}  
  
		// lettura articoli della categoria
		$titolo = array();
		$testo  = array();
        $sql = "SELECT *
                FROM `".DB::$pref."art`
                WHERE acap = '$dcap'
                    and astat <> 'A' 
                ORDER BY aprog ";
           foreach($PDO->query($sql) as $row)
			{     
			array_push($testo ,$row['atext']);
			array_push($titolo,$row['atit']);
			}
		  }
// conto gli articoli del capitolo
	$count = count($testo);         

// lettura articoli
	echo "<div id='accordion'>";
	for ($i = 0; $i < $count; ++$i) 
		{
		
		echo "<h3>";
		echo $titolo[$i];
		echo "</h3>";
		echo "<div class='fb-secondary'>";
		$a = new txt($testo[$i]);
		$a->ingloba();
		echo "</div>";                
		}
 ?>    
</div>    <!-- accordion -->
</section>
