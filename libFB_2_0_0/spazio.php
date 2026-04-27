<?php
/**===============================================================================
  Crea spaziatura 
  Metodi:
  space()      creazione spaziatura
  Variabili:
  valore            spaziatura in px
============================================================================= */
 class spazio

{
        public $spazio ='';

    public function __construct($spazio)
            {
            $this->spazio  = $spazio;
            }

  public function altezza()
            {
            echo "<div class='f-flex fd-row jc-start ai-center fw fb-secondary'
                style='width:100%; height:".$this->spazio."px;'>";  
            echo "</div>";
            }
}