 <?php
/**===============================================================================
  Gestione dei campi input di form a 6 parametri
  1.0.0		tooltip left
  03.03.21	no-bootstrap
============================================================================= */
class input			extends TMP
{
    public $label   = "";       // label campo database / testata
    public $valini  = "";       // valore campo database
    public $lung    = 0;        // lunghezza da mostrare del campo database / cols x textarea
    public $campo   = "";       // nome variabile campo database (Per il suo valore)
    public $pch     = "";       // placeholder
    public $tipo    = "";       // tipo di campo
    public $param   =  array(); // parametri
        public function __construct($param)
               {
               $this->valini   = $param[0];
               $this->campo    = $param[1];
               $this->lung     = $param[2];
               $this->label    = $param[3];
               $this->pch      = $param[4];
               $this->tipo     = $param[5];
               }


        public function field()

          {
		   // label non serve per tipo = h/star
			echo "<div>";
              if (($this->tipo !== 'h') && ($this->tipo !== 'star'))
                  { echo "<label for='$this->campo' title='$this->pch'>$this->label</label>"; }
 switch ($this->tipo) {
case 'ck':      // check box
                echo "<input type='checkbox' id='$this->campo' name='$this->campo'
                         value=$this->valini size='$this->lung'  ";
                if ($this->valini === 1) { echo "checked";}
                echo ">";
 break;

 case 'star':     // immagine stella
          if ($this->valini == '*')
               {
               echo "<input type='image' 
                    name='$this->campo' value= '$this->valini'
                    src='images/star.png '>";
               }
          else
               {
               echo "<input type='image' 
                    name='$this->campo' value= '$this->valini'
                    src='images/null.png '>";
               }
break;

case 'pw':      // input password
                echo "<input type='password'  id='$this->campo' name='$this->campo'
                    value='$this->valini' size='$this->lung' >";
break;

case 'pwr':     // input password readonly
                echo "<input type='password'  readonly  name='$this->campo'
                    value='$this->valini' size='$this->lung' >";
break;

case 'r':       // input readonly
                    echo "<input type='text'  readonly name='$this->campo'
                        id='$this->campo' value= '$this->valini'
                        size='$this->lung' >";
break;

case 'rb':      // input readonly e bold
                    echo "<input type='text' class='titolo_b' readonly='readonly'
                        name='$this->campo'  id='$this->campo' value= '$this->valini'
                        size='$this->lung' >";
break;

case 'i':       // input
                    echo "<input type='text' id='$this->campo' name='$this->campo'
                        value='$this->valini' size='$this->lung' >";
break;

case 'tx':       // textarea
                    echo "<textarea type='text' id='$this->campo' name='$this->campo'
                          cols='$this->lung' rows='5'>$this->valini</textarea>";
break;

case 'txr':       // textarea readonly
                    echo "<textarea type='text' id='$this->campo' name='$this->campo'
                          cols='$this->lung' rows='5' readonly='readonly'>$this->valini</textarea>";
break;

case 'ia':      // input text con autofocus
                    echo "<input type='text' id='$this->campo' name='$this->campo'
                        value='".htmlentities($this->valini, ENT_QUOTES, 'UTF-8')."' size='$this->lung' autofocus>
                         <script>
                         if (!('autofocus' in document.createElement('input')))
                         {
                         document.getElementById('$this->campo').focus();
                         }
                         </script>";
break;

case 'ir':          // input text obbligatorio
                        echo "<input type='text' required='Compilare questo campo'  name='$this->campo'
                            id='$this->campo' value='".htmlentities($this->valini, ENT_QUOTES, 'UTF-8')."' size='$this->lung' >";
break;

case 't' :          // input testate di colonna
                        echo "<input disabled class='fb-color-blue' value='$this->label' size='$this->lung'>";
break;

case 'h':            // input hidden (Lunghezza e label facoltative)
                        echo  "<input type='hidden'
                        name='$this->campo' id='$this->campo' value='$this->valini' >";
break;

case 'ic':            // input text + color picker
                        echo "<input type='color' class='color' name='$this->campo' id='$this->campo'
                            value='$this->valini' size='$this->lung' >";
break;

case 'st':            // input status
                    if ($this->valini != 'A')
                        { echo "<input type='image' class='nobord'
                            src='images/ok.png' height='16px' width='16px'
                          name='$this->campo' id='$this->campo' value='$this->valini' >";}
                    else
                        {echo "<input type='image' class='nobord' src='images/stop.png' height='16px'
                         name='$this->campo' id='$this->campo' value='$this->valini' width='16px' >";}
break;

case 'ip':          // input text + placeholder
                    echo "<input type='text' id='$this->campo' name='$this->campo'
                        value='".htmlentities($this->valini, ENT_QUOTES, 'UTF-8')."' size='$this->lung'  placeholder='$this->pch' >";
break;

case 'ipr':			// input text + placeholder + required
                    echo "<input type='text' required='Compilare questo campo'
                     name='$this->campo' id='$this->campo' value='".htmlentities($this->valini, ENT_QUOTES, 'UTF-8')."'
                     size='$this->lung' placeholder='$this->pch' >";
break;

case 'd1' :			// datepicker 1
        echo "<input type='text' id='datepicker1'
                     name='$this->campo' value='$this->valini'
                     size='$this->lung'>";
break;

case 'd2' :			// datepicker 2
        echo "<input type='text' id='datepicker2'
                     name='$this->campo' value='$this->valini'
                     size='$this->lung'>";
break;

case 'd3' :			// datepicker 3
        echo "<input type='text' id='datepicker3'
                     name='$this->campo' value='$this->valini'
                     size='$this->lung'>";
break;

case 'sn' :			// radio button 0=NO  1=SI
		if($this->valini == 0)
        {
        echo  "<input id='state0' type='radio' value='0' name='$this->campo' id='$this->campo' checked='checked'/>NO&nbsp;&nbsp;&nbsp;";
        echo  "<input id='state1' type='radio' value='1' name='$this->campo'/>SI";
        }
        if($this->valini == 1)
        {
        echo  "<input id='state1' type='radio' value='1' name='$this->campo' checked='checked' />SI&nbsp;&nbsp;&nbsp;";
        echo  "<input id='state0' type='radio' value='0' name='$this->campo' id='$this->campo'/>NO";
        }
break;

default:
break;
        }
     echo "</div>";
     }
/* ---------------------------------------------------------------------------
	05/03/21	STESSE FUNZIONI NON INCAPSULATE IN UN <DIV> e senza label
	--------------------------------------------------------------------------*/
        public function field_n()

{
 switch ($this->tipo)
 {
case 'ck-n':      // check box
                echo "<input type='checkbox' id='$this->campo' name='$this->campo'
                         value=$this->valini size='$this->lung'  ";
                if ($this->valini === 1) { echo "checked";}
                echo ">";
 break;
 case 'star-n':     // immagine stella
          if ($this->valini == '*')
               {
               echo "<input type='image' 
                    name='$this->campo' value= '$this->valini'
                    src='images/star.png' height='24px' width='42px'>";
               }
          else
               {
               echo "<input type='image' 
                    name='$this->campo' value= '$this->valini'
                    src='images/null.png '>";
               }
break;
case 'st-n':            // input status
                    if ($this->valini != 'A')
                        { echo "<input type='image' class='nobord'
                            src='images/bottoni/ok.png' height='16px' width='16px'
                          name='$this->campo' id='$this->campo' value='$this->valini' >";}
                    else
                        {echo "<input type='image' class='nobord' src='images/bottoni/stop.png' height='16px'
                         name='$this->campo' id='$this->campo' value='$this->valini' width='16px' >";}
break;
 }
}

}

?>
