<?php
echo	"<section id='footer' class='editor-widget' data-widget='footer'>";
echo    "<div  class='f-flex fd-row  fb-primary jc-around fw'>";
?>
<style type="text/css">
/* per no marker */
#footer li 	{
	list-style:none;
}
#footer li a,
#footer h3
	{
	color: var(--white-color);
}

#footer p	{
		background-color: var(--pri-color);
	border: 1.5px solid var(--pri-color);
	color: var(--black-color);
}
</style>

<?php
// lettura footer elements
        $sql = "SELECT *
                FROM `".DB::$pref."foo`
                WHERE fcod = '$lcod'
                    and ftmp= '".TMP::$tmenu."'
                    and fstat <> 'A'
                ORDER BY fprog ";

		$stmt = $PDO->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		json_encode($rows);
		// = Statomt->rowCount();		non serve per flex

		foreach($rows as $row)
			{
switch ($row['ftipo'])
{
// modulo tipo immagine e/o testo
    case 'img':
			echo "<div class='f-dim1'>";
			if ($row['felemento'])
			{ echo	"<img  class='center footer' src='".$row['felemento']."' alt='".$row['felemento']."'>"; }

			if (isset($row['ftitolo']) || isset($row['ftext']))
				{ 	//echo	"<p class='center little' style='background:transparent;'>";
					echo	"<h3 class='center'>".$row['ftitolo']."</h3>";
					echo	$row['ftext'];
					//echo  "</p>";
				}
			echo  "</div>";
			break;

// modulo tipo contatti
    case 'cnt':
// lettura contatti
        $sql = "SELECT *
                FROM `".DB::$pref."ctt`
                WHERE '".$row['fcod']."' = '".$lcod."'
                	and ecod = '".$row['felemento']."'
                    and etmp= '".TMP::$tmenu."'
                    and estat <> 'A'
                ORDER BY eprog ";
          foreach($PDO->query($sql) as $row)
          {
		  echo "<div class='f-dim1'>";
			if ($row['eimg'])
				{	echo "<img class='center footer' src='".$row['eimg']."' alt='".$row['eimg']."' border='0' />";  }

			if ($row['edes'])
				{ echo "<h3 class='center'>".$row['edes']."</h3>"; }

				//echo "<p class='center little'>";
				echo "<br />".$row['email'];
				echo "<br />".$row['epec'];
				echo "<br />".$row['efax'];
				echo "<br />".$row['esito'];
				echo "<br />".$row['esede'];
				echo "<br />".$row['enote'];
				//echo "</p>";
			echo	"</div>";
  		}    // foreach  ctt
		}    // switch
		}    // foreach  foo
		echo  "</div>";		
		echo	"</section>";	// footer
?>
