<?php

/**=============================================================================== 
  Mappa di dialogo modale con titolo, testo o bottone per link
  Metodi:
  dialog()         emette mappa dialog
============================================================================= */
class dialogo_modale
{
public $id=""; 
public $header="";
public $img="";
public $link="";
public $text="";

        public function __construct($id,$header,$img,$link,$text)       
               { 
               $this->id 	 = $id;
               $this->header = $header;
               $this->img 	 = $img;
               $this->link 	 = $link;
               $this->text 	 = $text;
               }
               
         function dialog()
{   
 
//-- dialogo modale --> 
echo	"<div class='modal fade' id='".$this->id."'>"; 
	echo	"<div class='modal-dialog modal-md'>"; 
		echo	"<div class='modal-content'>";   
			echo	"<div class='modal-header'>";    
			echo	"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";    
			echo	"<h3 class='modal-title'>".$this->header."</h4>";
			echo	"</div>";   	
echo	"<div class='modal-body'>";    	
echo	"<img src='".$this->img."' alt='".$this->img."' class='img-responsive'>";    	
echo	"</div>";   	
echo	"<div class='modal-footer'>"; 
//-- se c'è link = bottone a dx --
	if ($this->link >= ' ') 
	{  	
	echo "<a href='".$this->link."' target='_new' class='btn btn-".TMP::$tcolor."'>";
	echo	$this->text."</a>";  
 	} 
// altrimenti paragrafo a sx
	else	
	{  
	echo "<p>".$this->text."</p>";
	} 
//------------------------------------------
	echo "</div>";    	// footer	
	echo "</div>";		//-- .modal-content --> 	
	echo "</div>";  	//-- .modal-dialog -->	
	echo "</div>";		//-- .modal-fade -->                     	
}
}
?>