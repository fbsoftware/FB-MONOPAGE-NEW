<?php
/**=============================================================================== 
  Modulo promo
  Metodi:
  show()         emette promo
  ...........................................................................
  16/5/20	struttura FLEX
============================================================================= */
     class promo
{
public $colonna=""; 
public $link="";
public $img="";
public $titolo="";
public $testo="";
public $count="";

        public function __construct($colonna,$link,$img,$titolo,$testo,$count)       
               { 
               $this->colonna = $colonna;
               $this->link = $link;
               $this->img = $img;
               $this->titolo = $titolo;
               $this->testo = $testo;
               $this->count = $count;
               }
               
         function show()
			{ 
         	echo	"<div class='".$this->colonna." center'>";
			echo	"<a data-target='#promo".$this->count."' data-toggle='modal'>";
			echo "<img src='".$this->img."' alt='".$this->img."' width='100%'>";
			echo "</a>";
			echo "<h4 class='service-heading'>".$this->titolo."</h4>";
			echo "<span class='text-muted'>".$this->testo."</span>";
			echo "</div>"; 
			}
}

?>