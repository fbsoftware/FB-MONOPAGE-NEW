<?php
/**===============================================================================
  Classe 'msg'    Testa la presenza di dati necessari segnalando senza bloccare.
  metodo alert()  Segnalazione mancanze
============================================================================= */
     class testNoDati
{
public $cod  = '';
public $desc = '';

        public function __construct($cod,$desc)
               {
               $this->cod = $cod;
               $this->desc = $desc;
               }

        public function alert()
        {

          // test validità codice
          if ($this->cod <= '')  array_push($_SESSION['esito'],'151');

          // test validità descrizione
          if ($this->desc <= '') array_push($_SESSION['esito'],'154');
        }
}
?>
