<?php
/* ===========================================================================
	SOLO PER COMPATIBILITA' CON VERSIONI PRECEDENTI
   =========================================================================== */


/**==========================================================================
  Gestione dei campi input di form
============================================================================= */
class fieldi              
{ 
  public $label  = "";   // label campo database / testata
  
        public function __construct($valini,$campo,$lung,$label)       
               { $this->valini   = $valini;
                 $this->lung     = $lung;
                 $this->campo    = $campo;
                 $this->label    = $label;}        
                       
        public function field_r()   // input readonly  
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' class='titolo' readonly='readonly' 
                     name='$this->campo'  id='$this->campo' value= '$this->valini' 
                     size='$this->lung'  ></div>"; 
          } 
                       
        public function field_rb()   // input readonly + bold 
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' class='titolo_b' readonly='readonly' 
                     name='$this->campo'  id='$this->campo' value= '$this->valini' 
                     size='$this->lung'  ></div>"; 
          } 
                                                  
        public function field_ck()   // checkbox  
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='checkbox' class='nobord' id='$this->campo' 
                     name='$this->campo' value=$this->valini 
                     size='$this->lung' ></div>"; 
          } 
                       
        public function field_i()   // input text  
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' id='$this->campo' 
                     name='$this->campo' value='$this->valini' 
                     size='$this->lung' ></div>";
          } 
                       
        public function field_ia()   // input text con autofocus 
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' id='$this->campo' 
                     name='$this->campo' value='$this->valini' 
                     size='$this->lung' autofocus>
                         <script>
                         if (!('autofocus' in document.createElement('input')))
                         {
                         document.getElementById('$this->campo').focus();
                         }
                         </script></div>";
          } 
                       
                       
        public function field_ir()   // input text requested 
          {    echo "<div>
                     <label for='$this->campo'>$this->label</label>
                     <input class='req' type='text' required
                     name='$this->campo' id='$this->campo' value='$this->valini' 
                     size='$this->lung' ></div>";
          } 
                       
        public function field_t()   // input testate di colonna
          {    echo "<div>
                     <label for='$this->campo'>$this->label</label>
                     <input disabled='disabled' class='fb-color-blue' 
                     value='$this->label' size='$this->lung'>
                     </div>"; 
          } 
                       
        public function field_pw()   // input password 
          { 
           echo "<div>
                 <label for='$this->campo'>$this->label</label>
                 <input type='password'  id='$this->campo'
                        name='$this->campo' value='$this->valini' 
                        size='$this->lung' ></div>";
          } 
                       
        public function field_pwr()   // input password readonly
          { 
           echo "
           <div>
                 <label for='$this->campo'>$this->label</label>
                 <input type='password'  readonly
                        name='$this->campo' value='$this->valini' 
                        size='$this->lung'  ></div>
                        ";
          } 
                       
                        
        public function field_h()   // input hidden (Lunghezza e label facoltative)
          { 
          echo  "
          <div><input type='hidden' 
                        name='$this->campo' id='$this->campo' value='$this->valini' >
                        </div>
                        "; 
          } 
                       
        public function field_ic()   // input text + color picker 
          { 
           echo "
           <div>
                 <label for='$this->campo'>$this->label</label> 
                 <input type='text' class='colore'
                        name='$this->campo' id='$this->campo' value='$this->valini' 
                        size='$this->lung' ></div>
                        ";
          } 
                         
         public function field_st()   // input status
          {
          if ($this->valini != 'A') 
             {  echo "<div>
                      <label for='$this->campo'>$this->label</label>
                      <input type='image' class='nobord' src='images/bottoni//ok.png' 
                      name='$this->campo' id='$this->campo' value='$this->valini'
                      height='16px' ></div>
                      ";}
             else 
             {  echo "<div>
                      <label for='$this->campo'>$this->label</label>
                      <input type='image' class='nobord' src='images/bottoni/stop.png'
                      name='$this->campo' id='$this->campo' value='$this->valini' 
                      height='16px' ></div>
                      &nbsp;";}
          }  
                       
        public function field_d1()   // input data-1 con calendario jquery  
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' id='datepicker1' 
                     name='$this->campo' value='$this->valini' 
                     size='$this->lung' ></div>";
          } 
                       
        public function field_d2()   // input data-2 con calendario jquery  
          {    echo "<div> 
                     <label for='$this->campo'>$this->label</label>
                     <input type='text' id='datepicker2' 
                     name='$this->campo' value='$this->valini' 
                     size='$this->lung' ></div>";
          } 

}
?>