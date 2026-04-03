<?php
/**
---------------------------------------
	Articolo con immagine affiancata
     28/02/21	struttura flex
--------------------------------------- */
echo	"<section id='artimg' class='editor-widget' data-widget='artimg'>";
?>
  <script>
  $( function() {
  $( ".fb-primary p" ).addClass('fb-primary');
  $( ".fb-primary h1" ).addClass('fb-primary');
  } );
  </script>

<?php
// stampa riga di separazione
         require 'widget/space.php';

// cerca gli articoli con immagine
       $sql = "SELECT *
                FROM `".DB::$pref."aim`
                WHERE itmp = '".TMP::$tcod."'
                    and istat <> 'A'
				and icod = '$lcod'  
                ORDER BY iprog ";
     foreach($PDO->query($sql) as $row)
     {    require 'admin/fields_aim.php'; 
	 		$titolo_art =   $iart;
		echo "<div class='f-flex fd-row jc-around fb-secondary'>";		

     //  immagine/video a sinistra ============================================                                      
          if ($iimgpos == 'sx') 
          {	echo "<div class='f-flex f-1'>";		// div interno

               if ($itipo == 'img') // immagine ------------------------------
               { 
		   ?>
			<?php $target	= "artimg".$count; 
 			echo "<a popup-open='".$target."' href='javascript:void(0)'>";?>
  			<img src="<?php echo $iimg; ?>" class="img-thumbnail img-responsive img-h300" title="<?php echo $iimgtit; ?>"> 
 			</a> 
 			
 			<?php
			
			$modal	= new popup_modale($target,$iimgtit,$iimg,"",$iimgalt);
				$modal->popup();
				}
          // video ------------------------------     
			elseif ($itipo == 'video')    
               {
                require 'video.php';
               }
			echo "</div>";  
          // testo ------------------------------ 
				require 'art-img.php';
				$count++;
           }
          
          //  immagine/video a destra =========================================
          elseif ($iimgpos == 'dx') 

          echo "<div class='f-flex f-1'>";		// div interno
          {
          // testo ------------------------------
				require 'art-img.php';
				$count++;

          // immagine/video ------------------------------
          echo "<div class='f-flex f-1'>";	
               if ($itipo == 'img') 
               {  
               ?>
			<?php $target	= "artimg".$count; 			
 			echo "<a popup-open='".$target."' href='javascript:void(0)'>";?>
  			<img src="<?php echo $iimg; ?>" class="img-thumbnail img-responsive img-h300" title="<?php echo $iimgtit; ?>"> 
 			</a> 
 			
 			<?php
			$modal	= new popup_modale($target,$iimgtit,$iimg,"",$iimgalt);
				$modal->popup();
	
               }
               // video  ------------------------------
               elseif ($itipo == 'video')
               {
               require 'video.php';  
               }
				$count++;
			   echo "</div>";
		  }
     echo "</div>";     // flex
          }
echo "</section>";  
?>