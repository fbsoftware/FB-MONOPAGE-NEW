<?php
/**===============================================================================
  Classe 'msg'    Emette DIV con segnalazione di 4 tipi e colorazioni:
                  attenzione per errore
                  operazione eseguita
                  nota esplicativa
                  informazione
  metodo msg()    Segnalazione errori
============================================================================= */
     class msg
{
public $errore = array();

        public function __construct($errore)
               {
               $this->errore = $errore;
               }

        public function msg()
               {
?>
                 <style media="screen">
                   .fb-attenzione{
                     border: 1px solid #dad55e;
                     border-radius: 10px;
                   	background: #fffa90;
                   	color: #777620;
                 }
                 .fb-error {
                   border: 1px solid #f1a899;
                   border-radius: 10px;
                 	background: #fddfdf;
                 	color: #5f3f3f;
                 }
                 .fb-info {
                   border: 1px solid #466B96;
                   border-radius: 10px;
                 	background: #ACC8E5;
                 	color: #112A46;
                 }
                 .fb-highlight
                  {
                   border: 1px solid #4ebf37;
                   border-radius: 10px;
                 	background: #9de090;
                 	color: #133b0b;
                 }
                 </style>

<?php
$isEmpty = empty($this->errore);
if ($isEmpty != 1)
    {
echo	 "<div class='f-flex fd-row jc-start'>";
        $n = count($this->errore);
        for ($i=0; $i < $n; $i++)
        {
                    // danger  0 - 50
               if ($this->errore[$i] > -1  &&  $this->errore[$i] < 51)
                    {echo "<div class='fb-error'>";
                    echo "<p class='fb-p05'><img src='".DB::$dir_imm."/attenzione.png' height=20 alt='stop'>";}
                    // success  51 - 100
               if ($this->errore[$i] > 50  &&  $this->errore[$i] < 101)
                    {echo "<div class='fb-highlight'>";
                    echo "<p class='fb-p05'><img src='".DB::$dir_imm."/ok.png' height=20 alt='ok'>"; }
                    // warning  101 - 150
               if ($this->errore[$i] > 100  &&  $this->errore[$i] < 151)
                    { echo "<div class='fb-info'>";
                    echo "<p class='fb-p05'><img src='".DB::$dir_imm."/info.png' height=20 alt='nota'>";}
                    // info  151 - 200
               if ($this->errore[$i] > 150  &&  $this->errore[$i] < 201)
                    { echo "<div class='fb-attenzione'>";
                    echo "<p class='fb-p05'><img src='".DB::$dir_imm."/stop.png' height=20 alt='info'>"; }
              // messaggio
                    $f1 = new DB_decxdb('msg',$this->errore[$i]);
					          echo "&nbsp;&nbsp;&nbsp;&nbsp;".$f1->decxdb();
                    echo "</p>";
                    echo "</div>";

                  }
                echo "</div>";
        }
               unset($_SESSION['esito']);
               }
}
?>
