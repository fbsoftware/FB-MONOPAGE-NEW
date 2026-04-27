<?php
/** ---------------------------------------------------------------------
  Classe "bottoni_str_par"   Barra titolo con IMG e bottoni di gestione
  ----------------------------------------------------------------------
  parametri:  immagine e titolo della mappa
              tabella di database interessata
              programma di callback
              array diparametri (Bottoni da visualizzare o altri parametri)
              livello di accesso (min=0 , max=9)
  ----------------------------------------------------------------------
  Metodi:
         btn()     per visualizzare la toolbar
------------------------------------------------------------------------- */
         
class bottoni_str_par
{
  public $titolo   =  '';      // titolo
  public $tabella  =  '';      // nome archivio
  public $callbk   =  '';      // pgm di callback
  public $param    =  array(); // parametri
  public $accesso  =  '';      // livello di accesso ( min.0 --> 9 max.)

        public function __construct($titolo,$tabella,$callbk,$param)
               { $this->titolo  = $titolo;
                 $this->tabella = $tabella;
                 $this->callbk  = $callbk;
                 $this->param   = $param;
               }			   
			  
        public function btn()           //  bottoni gestione
          { 
				//echo "<div class='f-flex fd-row jc-between toolbar-color'>";
				// immagine ampiezza = 1
                echo "<div class='f-item'>";
				if (file_exists("images/archivi/".$this->tabella.".png"))
				{
                echo "<img class='marchio' src='images/archivi/".$this->tabella.".png' alt='manca img' height='40'> ";
				}
				echo    "</div>";

                // titolo ampiezza = 4
                echo "<div class='f-item center'>";
                echo "<h1 class='toolbar'>&nbsp;".$this->titolo."</h1>";
                echo    "</div>";

                // bottoni ampiezza = 7
                echo    "<div class='f-item'>";
				if(in_array('enctype',$this->param))
					{
					echo "<form enctype='multipart/form-data' method='post' id='".$this->tabella."' action='".$this->callbk."' onkeypress='return event.keyCode != 13;'>" ;
					}
				else
					{
					echo "<form method='post' id='".$this->tabella."' action='".$this->callbk."' onkeypress='return event.keyCode != 13;'>" ;
					}

				// accessi consentiti ai bottoni
				$b5   = array('nuovo','modifica','cancella','archivia','cerca','ripristina','salva');
				$b0   = array('chiudi','uscita','mostra','stampa','ritorno');

		// scan bottoni e bypass per parametri NON bottone (es.:enctype)
		$length = count($this->param);
	   for ($i = 0; $i < $length; $i++)
		{
			// test per non bottone
			if ($this->param[$i] == 'enctype')
				{
					continue;
				}

			// test se label diversa da azione
                $act = $this->param[$i];
				$pos = strpos($this->param[$i], '|');
			if ($pos === false) // bottone = comando+label
				{

				{
				echo    "<button class='fb-button fb-p025 fb-rad7 fb-m05' type='submit' name='submit' value='".$act."' id='".$act."'>
                         <img src='".DB::$dir_imm."bottoni/".$act.".png' alt='".$act."' height=25 />".$act."</button>";
				}
				}
			else
				{	// comando separato da label
				list($label,$act)=explode('|',$this->param[$i]);
               {
               echo    "<button class='fb-button fb-p025 fb-rad7 fb-m05' type='submit' name='submit' value='".$act."' id='".$act."'>
                         <img src='".DB::$dir_imm."bottoni/".$label.".png' alt='".$label."' height=25 />".$label."</button>";
               }
				}
				}	// endfor
                echo    "</div>";
				echo    "</div>";	// toolbar

	}	// end function
}	// end class
?>
