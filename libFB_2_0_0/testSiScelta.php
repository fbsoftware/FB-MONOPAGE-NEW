<?php
/**===============================================================================
  Classe 'msg'    Testa la scelta per: modifica,copia,cancella.
  metodo alert_s()  Manca la scelta,
============================================================================= */
     class testSiScelta
{
public $id  = '';
public $azione  = '';


        public function __construct($id,$azione)
               {
               $this->id = $id;
               $this->azione = $azione;
               }

        public function alert_s()
            {
              $_SESSION['esito'] = array();
              if (($this->azione == 'modifica' || $this->azione == 'cancella' || $this->azione == 'copia') && $this->id == '')
              {
              array_push($_SESSION['esito'],'4');
              $loc = "location:admin.php?".$_SESSION['location']."";
              header($loc);
              }
            }
}
?>
