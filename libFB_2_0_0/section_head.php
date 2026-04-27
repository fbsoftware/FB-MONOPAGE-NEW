<?php
/**=============================================================================== 
  Emissione della testata di sezione con titolo e testo
  Metodi:
  head()         Emissione della testata
============================================================================= 
	26/5/2019	aggiunto test s-n titolo
============================================================================= */

     class section_head
{
public $sn	=0;
public $tit	=""; 
public $text="";
public $color="";
        public function __construct($sn,$tit,$text,$color)       
               { 
               $this->sn = $sn;
			   $this->tit = $tit;
               $this->text = $text;
               $this->color = $color;
               }
               
         function head()
             { 
			   if ($this->sn > 0)
			   {
				if (isset($this->tit)) { echo "<h1>".$this->tit."</h1>"; } 
				if (isset($this->text)) { echo "<p>".$this->text."</p>"; }
								
			   }
             }
}

?>